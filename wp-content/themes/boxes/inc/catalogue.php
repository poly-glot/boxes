<?php

function boxes_company(): array {
	static $company = null;

	$company ??= array(
		'address'        => boxes_lines( boxes_option( 'address' ) ),
		'company_number' => boxes_option( 'company_number' ),
		'email'          => boxes_option( 'email' ),
		'footer_notice'  => boxes_option( 'footer_notice' ),
		'hours'          => boxes_option( 'hours' ),
		'legal_name'     => boxes_option( 'legal_name' ),
		'name'           => boxes_option( 'company_name', 'Boxes' ),
		'orders_email'   => boxes_option( 'orders_email' ),
		'phone'          => boxes_option( 'phone' ),
		'phone_href'     => boxes_tel_href( boxes_option( 'phone_href' ) ),
		'vat_number'     => boxes_option( 'vat_number' ),
	);

	return $company;
}

function boxes_tel_href( string $value ): string {
	return str_starts_with( $value, 'tel:' ) ? $value : 'tel:' . $value;
}

function boxes_shop_rules(): array {
	return array(
		'delivery'  => (float) boxes_option( 'delivery_charge', '7.95' ),
		'free_from' => (float) boxes_option( 'delivery_free_over', '150' ),
		'vat_rate'  => boxes_vat_percent() / 100,
	);
}

function boxes_vat_percent(): float {
	return (float) boxes_option( 'vat_rate', '20' );
}

function boxes_term_url( WP_Term $term ): string {
	$link = get_term_link( $term );

	return is_string( $link ) ? $link : '';
}

function boxes_terms( string $taxonomy ): array {
	static $terms = array();

	if ( ! isset( $terms[ $taxonomy ] ) ) {
		$found = get_terms( array(
			'hide_empty' => false,
			'meta_key'   => 'position',
			'order'      => 'ASC',
			'orderby'    => 'meta_value_num',
			'taxonomy'   => $taxonomy,
		) );

		$terms[ $taxonomy ] = is_array( $found ) ? $found : array();
	}

	return $terms[ $taxonomy ];
}

function boxes_product_category( int $product_id ): ?WP_Term {
	$terms = get_the_terms( $product_id, 'product_category' );

	return is_array( $terms ) && $terms ? $terms[0] : null;
}

function boxes_tiers( int $product_id ): array {
	return array_map( fn ( array $tier ): array => array(
		'min'   => (int) ( $tier['min'] ?? 1 ),
		'price' => (float) ( $tier['price'] ?? 0 ),
	), array_filter( boxes_rows( 'tiers', $product_id ), 'is_array' ) );
}

function boxes_product( WP_Post $post ): array {
	static $products = array();

	if ( isset( $products[ $post->ID ] ) ) {
		return $products[ $post->ID ];
	}

	$image_id = (int) get_post_thumbnail_id( $post );
	$category = boxes_product_category( $post->ID );

	$products[ $post->ID ] = array(
		'category'   => $category,
		'id'         => $post->ID,
		'image'      => array(
			'alt'   => boxes_image_alt( $image_id ),
			'large' => boxes_image_url( $image_id, 'boxes-product' ),
			'small' => boxes_image_url( $image_id, 'boxes-product-small' ),
		),
		'name'       => $post->post_title,
		'pack'       => boxes_text( 'pack', $post->ID ),
		'price'      => (float) boxes_text( 'price', $post->ID ),
		'sku'        => boxes_text( 'sku', $post->ID ),
		'slug'       => $post->post_name,
		'stock'      => boxes_text( 'stock', $post->ID ) ?: 'in-stock',
		'stock_note' => boxes_text( 'stock_note', $post->ID ),
		'summary'    => boxes_text( 'summary', $post->ID ),
		'tiers'      => boxes_tiers( $post->ID ),
		'url'        => (string) get_permalink( $post ),
	);

	return $products[ $post->ID ];
}

function boxes_products( array $posts ): array {
	return array_map( 'boxes_product', $posts );
}

function boxes_product_query( array $args = array() ): array {
	return boxes_products( boxes_query( $args + array(
		'order'          => 'ASC',
		'orderby'        => 'menu_order',
		'post_type'      => 'product',
		'posts_per_page' => -1,
	) ) );
}

function boxes_all_products(): array {
	static $products = null;

	$products ??= boxes_product_query();

	return $products;
}

function boxes_products_by_category(): array {
	$groups = array();

	foreach ( boxes_all_products() as $product ) {
		$slug              = $product['category']?->slug ?? '';
		$groups[ $slug ][] = $product;
	}

	return $groups;
}

function boxes_collection_products( string $slug, int $limit = -1 ): array {
	return boxes_product_query( array(
		'meta_key'       => 'collection_position',
		'orderby'        => 'meta_value_num',
		'posts_per_page' => $limit,
		'tax_query'      => array(
			array(
				'field'    => 'slug',
				'taxonomy' => 'collection',
				'terms'    => $slug,
			),
		),
	) );
}

function boxes_products_by_sku( array $skus ): array {
	if ( ! $skus ) {
		return array();
	}

	$products = boxes_product_query( array(
		'meta_query' => array(
			array(
				'compare' => 'IN',
				'key'     => 'sku',
				'value'   => $skus,
			),
		),
	) );

	return array_column( $products, null, 'sku' );
}

function boxes_related_products( int $product_id ): array {
	$ids = array_slice( boxes_ids( boxes_meta( 'related', $product_id ) ), 0, 3 );

	return boxes_products( boxes_ordered( $ids, 'product' ) );
}

function boxes_tier_quantity( array $tiers, int $index ): string {
	$min  = $tiers[ $index ]['min'];
	$next = $tiers[ $index + 1 ] ?? null;

	if ( ! $next ) {
		return $min . '+ packs';
	}

	$last = $next['min'] - 1;

	if ( $last === $min ) {
		return $min . ( 1 === $min ? ' pack' : ' packs' );
	}

	return $min . '–' . $last . ' packs';
}
