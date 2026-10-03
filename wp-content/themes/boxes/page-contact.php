<?php
get_header();

while ( have_posts() ) :
	the_post();

	$page_post = get_post();

	get_template_part( 'template-parts/page-hero', null, boxes_hero_args( $page_post ) );
	?>
	<div class="with-aside container">
		<div class="with-aside__main">
			<?php get_template_part( 'template-parts/form-panel', null, boxes_form_panel_args( 'contact', $page_post ) ); ?>
		</div>
		<section class="with-aside__aside" aria-labelledby="details-title">
			<h2 id="details-title"><?php echo esc_html( boxes_text( 'details_title' ) ); ?></h2>
			<div class="prose">
				<?php foreach ( boxes_rows( 'details' ) as $detail ) : ?>
					<p><strong><?php echo esc_html( boxes_row_text( $detail, 'label' ) . ':' ); ?></strong> <?php echo boxes_inline( boxes_row_text( $detail, 'value' ) ); ?></p>
				<?php endforeach; ?>
			</div>
		</section>
	</div>
	<section class="section band--surface" aria-labelledby="find-us-title">
		<div class="split container">
			<div class="split__text">
				<h2 class="split__title" id="find-us-title"><?php echo esc_html( boxes_text( 'location_title' ) ); ?></h2>
				<div class="prose">
					<?php echo wp_kses_post( boxes_text( 'location_text' ) ); ?>
				</div>
			</div>
			<?php get_template_part( 'template-parts/map-frame', null, array( 'alt' => boxes_text( 'map_alt' ), 'caption' => boxes_text( 'map_caption' ) ) ); ?>
		</div>
	</section>
	<?php
endwhile;

get_footer();
