<?php

function boxes_menu_links( string $location ): array {
	$locations = get_nav_menu_locations();
	$menu_id   = (int) ( $locations[ $location ] ?? 0 );
	$items     = $menu_id ? wp_get_nav_menu_items( $menu_id ) : array();

	return array_map( fn ( WP_Post $item ): array => array(
		'label' => (string) $item->title,
		'url'   => boxes_url( (string) $item->url ),
	), is_array( $items ) ? $items : array() );
}

function boxes_section_path(): string {
	static $section = null;

	if ( null !== $section ) {
		return $section;
	}

	$category = is_singular( 'product' ) ? boxes_product_category( (int) get_queried_object_id() ) : null;

	$section = match ( true ) {
		null !== $category       => boxes_path( boxes_term_url( $category ) ),
		is_singular( 'post' )    => boxes_path( boxes_page_url( 'journal' ) ),
		default                  => '',
	};

	return $section;
}

function boxes_current_value( string $url ): string {
	$path = boxes_path( $url );

	return match ( $path ) {
		boxes_current_path() => 'page',
		boxes_section_path() => 'true',
		default              => '',
	};
}

function boxes_current( string $url ): string {
	$value = boxes_current_value( $url );

	return '' === $value ? '' : sprintf( ' aria-current="%s"', esc_attr( $value ) );
}

function boxes_heading( WP_Post $page ): string {
	return boxes_text( 'heading', $page->ID ) ?: $page->post_title;
}

function boxes_crumb_label( WP_Post $page ): string {
	return boxes_text( 'crumb_label', $page->ID ) ?: boxes_heading( $page );
}

function boxes_page_crumb( string $path ): array {
	$page = boxes_page( $path );

	return array(
		'label' => $page ? boxes_crumb_label( $page ) : '',
		'url'   => boxes_page_url( $path ),
	);
}

function boxes_current_crumb( string $label ): array {
	return array(
		'label' => $label,
		'url'   => '',
	);
}

function boxes_crumbs(): array {
	if ( is_front_page() || is_404() ) {
		return array();
	}

	if ( is_home() ) {
		return array( boxes_current_crumb( boxes_page_crumb( 'journal' )['label'] ) );
	}

	if ( is_singular( 'post' ) ) {
		return array( boxes_page_crumb( 'journal' ), boxes_current_crumb( get_the_title() ) );
	}

	if ( is_singular( 'product' ) ) {
		$category = boxes_product_category( (int) get_queried_object_id() );
		$crumbs   = array( boxes_page_crumb( 'shop' ) );

		if ( $category ) {
			$crumbs[] = array(
				'label' => $category->name,
				'url'   => boxes_term_url( $category ),
			);
		}

		return array_merge( $crumbs, array( boxes_current_crumb( get_the_title() ) ) );
	}

	if ( is_tax( BOXES_SHOP_TAXONOMIES ) ) {
		return array( boxes_page_crumb( 'shop' ), boxes_current_crumb( single_term_title( '', false ) ) );
	}

	$queried = get_queried_object();

	return $queried instanceof WP_Post ? array( boxes_current_crumb( boxes_crumb_label( $queried ) ) ) : array();
}

function boxes_seo_post_id(): int {
	if ( is_home() ) {
		return (int) get_option( 'page_for_posts' );
	}

	return is_singular() ? (int) get_queried_object_id() : 0;
}

function boxes_description(): string {
	if ( is_404() ) {
		return 'The page you were looking for could not be found. Search the Boxes range or browse all packaging.';
	}

	if ( is_tax( BOXES_SHOP_TAXONOMIES ) ) {
		return boxes_term_text( 'description', (int) get_queried_object_id() );
	}

	$post_id = boxes_seo_post_id();

	if ( ! $post_id ) {
		return (string) get_bloginfo( 'description' );
	}

	return 'product' === get_post_type( $post_id ) ? boxes_text( 'summary', $post_id ) : boxes_text( 'meta_description', $post_id );
}

function boxes_document_title( string $title ): string {
	$post_id = boxes_seo_post_id();

	return $post_id ? boxes_text( 'document_title', $post_id ) : $title;
}

add_filter( 'pre_get_document_title', 'boxes_document_title' );

function boxes_hero_args( WP_Post $page, bool $plain = false ): array {
	return array(
		'image' => (int) get_post_thumbnail_id( $page ),
		'lead'  => boxes_text( 'lead', $page->ID ),
		'plain' => $plain,
		'title' => boxes_heading( $page ),
	);
}

function boxes_form_panel_args( string $form, WP_Post $page ): array {
	return array(
		'form'  => $form,
		'title' => boxes_text( 'form_title', $page->ID ),
	);
}
