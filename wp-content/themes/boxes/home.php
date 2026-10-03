<?php
get_header();

$page_post = get_post( (int) get_option( 'page_for_posts' ) );

update_post_thumbnail_cache();

if ( $page_post ) {
	get_template_part( 'template-parts/page-hero', null, boxes_hero_args( $page_post, true ) );
}
?>
<section class="section--tight section" aria-label="Journal articles">
	<div class="container">
		<?php get_template_part( 'template-parts/article-grid', null, array( 'level' => 2, 'posts' => $GLOBALS['wp_query']->posts ) ); ?>
	</div>
</section>
<?php
get_footer();
