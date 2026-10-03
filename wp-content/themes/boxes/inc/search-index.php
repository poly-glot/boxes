<?php

function boxes_index_product( array $product ): array {
	return array(
		'slug'         => $product['slug'],
		'name'         => boxes_plain( $product['name'] ),
		'sku'          => $product['sku'],
		'pack'         => $product['pack'],
		'price'        => $product['price'],
		'tiers'        => $product['tiers'],
		'stock'        => $product['stock'],
		'image'        => array(
			'small' => $product['image']['small'],
			'large' => $product['image']['large'],
			'alt'   => boxes_plain( $product['image']['alt'] ),
		),
		'category'     => $product['category']?->slug ?? '',
		'categoryName' => boxes_plain( $product['category']?->name ?? '' ),
		'url'          => $product['url'],
		'summary'      => boxes_plain( $product['summary'] ),
	);
}

function boxes_index_category( WP_Term $term ): array {
	return array(
		'slug'    => $term->slug,
		'name'    => boxes_plain( $term->name ),
		'url'     => boxes_term_url( $term ),
		'summary' => boxes_term_text( 'description', $term->term_id ),
	);
}

function boxes_index_entry( string $kind, string $title, string $url, string $summary, string $order ): array {
	return array(
		'kind'    => $kind,
		'order'   => $order,
		'summary' => $summary,
		'title'   => $title,
		'url'     => $url,
	);
}

function boxes_index_pages(): array {
	$excluded = array_filter( array( (int) get_option( 'page_on_front' ), boxes_page_id( 'basket' ), boxes_page_id( BOXES_SEARCH_PAGE ) ) );
	$entries  = array();

	foreach ( boxes_query( array( 'post__not_in' => $excluded, 'post_type' => 'page', 'posts_per_page' => -1 ) ) as $page ) {
		$entries[] = boxes_index_entry( 'Page', boxes_plain( $page->post_title ), (string) get_permalink( $page ), boxes_text( 'meta_description', $page->ID ), $page->post_name );
	}

	foreach ( boxes_terms( 'collection' ) as $index => $term ) {
		$entries[] = boxes_index_entry( 'Collection', boxes_plain( $term->name ), boxes_term_url( $term ), boxes_term_text( 'description', $term->term_id ), 'collection ' . $index );
	}

	$posts = boxes_query( array( 'order' => 'ASC', 'orderby' => 'date', 'post_type' => 'post', 'posts_per_page' => -1 ) );

	foreach ( $posts as $post ) {
		$entries[] = boxes_index_entry( 'Journal', boxes_plain( $post->post_title ), (string) get_permalink( $post ), boxes_text( 'meta_description', $post->ID ) ?: $post->post_excerpt, 'journal ' . $post->post_date );
	}

	usort( $entries, fn ( array $a, array $b ): int => strcmp( $a['order'], $b['order'] ) );

	return array_map( fn ( array $entry ): array => array(
		'kind'    => $entry['kind'],
		'title'   => $entry['title'],
		'url'     => $entry['url'],
		'summary' => $entry['summary'],
	), $entries );
}

function boxes_search_index(): array {
	return array(
		'products'   => array_map( 'boxes_index_product', boxes_all_products() ),
		'categories' => array_map( 'boxes_index_category', boxes_terms( 'product_category' ) ),
		'pages'      => boxes_index_pages(),
	);
}

function boxes_serve_search_index( WP $wp ): void {
	$is_index = ! empty( $wp->query_vars[ BOXES_SEARCH_INDEX_VAR ] ) || BOXES_SEARCH_INDEX_PATH === $wp->request;

	if ( ! $is_index ) {
		return;
	}

	wp_send_json( boxes_search_index(), 200, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
}

add_action( 'parse_request', 'boxes_serve_search_index' );
