<?php
get_header();

while ( have_posts() ) :
	the_post();

	$page_post = get_post();

	get_template_part( 'template-parts/page-hero', null, boxes_hero_args( $page_post ) );
	?>
	<section class="section" aria-labelledby="styles-title">
		<div class="container">
			<div class="prose">
				<h2 id="styles-title"><?php echo esc_html( boxes_text( 'list_title' ) ); ?></h2>
				<p><?php echo esc_html( boxes_text( 'list_intro' ) ); ?></p>
				<?php get_template_part( 'template-parts/labelled-list', null, array( 'items' => boxes_rows( 'list_items' ) ) ); ?>
			</div>
		</div>
	</section>
	<section class="section band--surface" aria-labelledby="grades-title">
		<div class="container stack">
			<h2 id="grades-title"><?php echo esc_html( boxes_text( 'table_title' ) ); ?></h2>
			<p class="prose"><?php echo esc_html( boxes_text( 'table_intro' ) ); ?></p>
			<?php get_template_part( 'template-parts/data-table', null, array( 'caption_id' => 'grades-caption', 'page_id' => $page_post->ID ) ); ?>
			<p class="prose"><?php echo boxes_inline( boxes_text( 'table_note' ) ); ?></p>
		</div>
	</section>
	<?php get_template_part( 'template-parts/guide-and-steps', null, array( 'guide_id' => 'samples-title', 'page_id' => $page_post->ID ) ); ?>
	<div class="container section">
		<?php get_template_part( 'template-parts/form-panel', null, boxes_form_panel_args( 'bespoke', $page_post ) ); ?>
	</div>
	<?php
endwhile;

get_footer();
