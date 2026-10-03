<?php
get_header();

while ( have_posts() ) :
	the_post();

	$page_post = get_post();

	get_template_part( 'template-parts/page-hero', null, boxes_hero_args( $page_post ) );
	?>
	<section class="section" aria-labelledby="surfaces-title">
		<div class="container">
			<div class="prose">
				<h2 id="surfaces-title"><?php echo esc_html( boxes_text( 'list_title' ) ); ?></h2>
				<?php get_template_part( 'template-parts/labelled-list', null, array( 'items' => boxes_rows( 'list_items' ) ) ); ?>
			</div>
		</div>
	</section>
	<section class="section band--surface" aria-labelledby="minimums-title">
		<div class="container stack">
			<h2 id="minimums-title"><?php echo esc_html( boxes_text( 'table_title' ) ); ?></h2>
			<?php get_template_part( 'template-parts/data-table', null, array( 'caption_id' => 'minimums-caption', 'page_id' => $page_post->ID ) ); ?>
			<p class="prose"><?php echo boxes_inline( boxes_text( 'table_note' ) ); ?></p>
		</div>
	</section>
	<?php get_template_part( 'template-parts/guide-and-steps', null, array( 'guide_id' => 'artwork-title', 'page_id' => $page_post->ID ) ); ?>
	<div class="container section">
		<?php get_template_part( 'template-parts/form-panel', null, boxes_form_panel_args( 'printing', $page_post ) ); ?>
	</div>
	<?php
endwhile;

get_footer();
