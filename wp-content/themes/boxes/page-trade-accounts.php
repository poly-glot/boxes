<?php
get_header();

while ( have_posts() ) :
	the_post();

	$page_post = get_post();

	get_template_part( 'template-parts/page-hero', null, boxes_hero_args( $page_post ) );
	?>
	<div class="section container">
		<div class="grid-3">
			<?php foreach ( boxes_rows( 'intro_columns' ) as $column ) : ?>
				<?php $anchor = boxes_row_text( $column, 'anchor' ); ?>
				<section class="prose" aria-labelledby="<?php echo esc_attr( $anchor ); ?>">
					<h2 id="<?php echo esc_attr( $anchor ); ?>"><?php echo esc_html( boxes_row_text( $column, 'title' ) ); ?></h2>
					<p><?php echo esc_html( boxes_row_text( $column, 'text' ) ); ?></p>
				</section>
			<?php endforeach; ?>
		</div>
	</div>
	<section class="benefits band--surface" aria-labelledby="benefits-title">
		<div class="container stack">
			<h2 id="benefits-title"><?php echo esc_html( boxes_text( 'highlights_title' ) ); ?></h2>
			<?php get_template_part( 'template-parts/benefits-list', null, array( 'items' => boxes_rows( 'highlights' ) ) ); ?>
		</div>
	</section>
	<section class="section" aria-labelledby="next-title">
		<div class="container">
			<div class="prose">
				<h2 id="next-title"><?php echo esc_html( boxes_text( 'steps_title' ) ); ?></h2>
				<ol>
					<?php foreach ( boxes_rows( 'steps' ) as $step ) : ?>
						<li><?php echo esc_html( boxes_row_text( $step, 'text' ) ); ?></li>
					<?php endforeach; ?>
				</ol>
			</div>
		</div>
	</section>
	<div class="container section">
		<?php get_template_part( 'template-parts/form-panel', null, boxes_form_panel_args( 'trade', $page_post ) ); ?>
	</div>
	<?php
endwhile;

get_footer();
