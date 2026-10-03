<?php
get_header();

while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/page-hero', null, boxes_hero_args( get_post() ) );
	?>
	<section class="section" aria-label="Delivery, returns and damaged goods">
		<div class="container">
			<div class="prose">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
	<section class="section band--surface" aria-labelledby="faq-title">
		<div class="container stack">
			<h2 id="faq-title"><?php echo esc_html( boxes_text( 'faq_title' ) ); ?></h2>
			<?php get_template_part( 'template-parts/faq', null, array( 'items' => boxes_rows( 'faqs' ) ) ); ?>
		</div>
	</section>
	<?php
endwhile;

get_footer();
