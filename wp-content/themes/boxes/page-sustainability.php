<?php
get_header();

while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/page-hero', null, boxes_hero_args( get_post() ) );
	?>
	<section class="section" aria-label="Our approach">
		<div class="container">
			<div class="prose">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
	<section class="section band--surface" aria-labelledby="unsolved-title">
		<div class="container">
			<div class="prose">
				<h2 id="unsolved-title"><?php echo esc_html( boxes_text( 'band_title' ) ); ?></h2>
				<?php foreach ( boxes_lines( boxes_text( 'band_text' ) ) as $paragraph ) : ?>
					<p><?php echo esc_html( $paragraph ); ?></p>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
