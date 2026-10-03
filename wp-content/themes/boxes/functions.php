<?php

require_once get_theme_file_path( 'inc/constants.php' );
require_once get_theme_file_path( 'inc/helpers.php' );
require_once get_theme_file_path( 'inc/catalogue.php' );
require_once get_theme_file_path( 'inc/terms.php' );
require_once get_theme_file_path( 'inc/navigation.php' );
require_once get_theme_file_path( 'inc/search-index.php' );
require_once get_theme_file_path( 'inc/forms.php' );

function boxes_setup(): void {
	add_rewrite_rule( '^' . preg_quote( BOXES_SEARCH_INDEX_PATH ) . '$', 'index.php?' . BOXES_SEARCH_INDEX_VAR . '=1', 'top' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus( array(
		'utility'    => 'Utility bar',
		'categories' => 'Shop categories',
		'shop'       => 'Footer: Shop',
		'help'       => 'Footer: Help',
		'company'    => 'Footer: Company',
		'legal'      => 'Footer: Legal',
	) );

	add_image_size( 'boxes-product-small', 400, 400, true );
	add_image_size( 'boxes-product', 800, 800, true );
	add_image_size( 'boxes-hero-960', 960, 400, true );
	add_image_size( 'boxes-hero-1440', 1440, 600, true );
	add_image_size( 'boxes-hero', 1920, 800, true );
	add_image_size( 'boxes-promo', 1200, 600, true );
	add_image_size( 'boxes-page', 1600, 900, true );
}

add_action( 'after_setup_theme', 'boxes_setup' );

function boxes_stylesheets(): array {
	$css   = get_theme_file_path( 'assets/css/' );
	$parts = array_merge( glob( $css . 'atoms/*.css' ), glob( $css . 'components/*.css' ) );

	return array_merge(
		array( 'base/tokens.css', 'base/base.css', 'base/layout.css' ),
		array_map( fn ( string $path ): string => substr( $path, strlen( $css ) ), $parts )
	);
}

function boxes_enqueue_assets(): void {
	wp_enqueue_style( 'boxes-fonts', BOXES_FONTS_URL, array(), null );

	$version = wp_get_theme()->get( 'Version' );
	$bundle  = get_theme_file_path( 'assets/css/main.css' );

	if ( file_exists( $bundle ) ) {
		wp_enqueue_style( 'boxes-main', get_theme_file_uri( 'assets/css/main.css' ), array( 'boxes-fonts' ), (string) filemtime( $bundle ) );
	} else {
		$previous = array( 'boxes-fonts' );

		foreach ( boxes_stylesheets() as $sheet ) {
			$handle = 'boxes-' . str_replace( array( '/', '.css' ), array( '-', '' ), $sheet );
			wp_enqueue_style( $handle, get_theme_file_uri( 'assets/css/' . $sheet ), $previous, $version );
			$previous = array( $handle );
		}
	}

	foreach ( BOXES_SCRIPTS as $script ) {
		wp_enqueue_script_module( 'boxes-' . $script, get_theme_file_uri( 'assets/js/' . $script . '.js' ), array(), $version );
	}

	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}

add_action( 'wp_enqueue_scripts', 'boxes_enqueue_assets', 20 );

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content', 'wpautop' );

add_filter( 'run_wptexturize', '__return_false' );

add_filter( 'document_title_separator', fn (): string => '|' );

add_filter( 'image_editor_output_format', fn ( array $formats ): array => $formats + array( 'image/jpeg' => 'image/webp' ) );

add_filter( 'query_vars', fn ( array $vars ): array => array_merge( $vars, array( BOXES_SEARCH_INDEX_VAR ) ) );

function boxes_search_page_request( array $vars ): array {
	if ( ! array_key_exists( 's', $vars ) ) {
		return $vars;
	}

	unset( $vars['s'] );

	return array( 'pagename' => BOXES_SEARCH_PAGE ) + $vars;
}

add_filter( 'request', 'boxes_search_page_request' );

function boxes_meta_description(): void {
	$description = boxes_description();

	if ( '' !== $description ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
	}
}

add_action( 'wp_head', 'boxes_meta_description', 1 );

function boxes_main_query( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_home() || $query->is_tax( BOXES_SHOP_TAXONOMIES ) ) {
		$query->set( 'posts_per_page', -1 );
	}

	if ( $query->is_tax( 'product_category' ) ) {
		$query->set( 'order', 'ASC' );
		$query->set( 'orderby', 'menu_order' );
	}

	if ( $query->is_tax( 'collection' ) ) {
		$query->set( 'meta_key', 'collection_position' );
		$query->set( 'order', 'ASC' );
		$query->set( 'orderby', 'meta_value_num' );
	}
}

add_action( 'pre_get_posts', 'boxes_main_query' );

function boxes_script_module_attributes( array $attributes ): array {
	$is_theme_module = str_starts_with( (string) ( $attributes['id'] ?? '' ), 'boxes-' ) && 'module' === ( $attributes['type'] ?? '' );

	if ( $is_theme_module ) {
		unset( $attributes['id'] );
	}

	return $attributes;
}

add_filter( 'wp_script_attributes', 'boxes_script_module_attributes' );
