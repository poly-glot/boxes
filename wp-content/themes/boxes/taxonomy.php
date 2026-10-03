<?php
get_header();

$term = get_queried_object();

update_post_thumbnail_cache();

get_template_part( 'template-parts/page-hero', null, array(
	'lead'  => boxes_term_text( 'intro', $term->term_id ),
	'plain' => true,
	'title' => $term->name,
) );
get_template_part( 'template-parts/shop-layout', null, array( 'products' => boxes_products( $GLOBALS['wp_query']->posts ) ) );

get_footer();
