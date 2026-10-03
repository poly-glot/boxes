<?php
get_header();

update_post_thumbnail_cache();

get_template_part( 'template-parts/page-hero', null, array(
	'plain' => true,
	'title' => wp_strip_all_tags( get_the_archive_title() ),
) );
?>
<section class="section--tight section" aria-label="Journal articles">
	<div class="container">
		<?php get_template_part( 'template-parts/article-grid', null, array( 'level' => 2, 'posts' => $GLOBALS['wp_query']->posts ) ); ?>
	</div>
</section>
<?php
get_footer();
