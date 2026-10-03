<?php
get_header();

while ( have_posts() ) :
	the_post();

	$page_post = get_post();

	get_template_part( 'template-parts/page-hero', null, boxes_hero_args( $page_post ) );
	?>
	<section class="section" aria-label="Our story">
		<div class="container">
			<div class="prose">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
	<section class="benefits band--surface" aria-labelledby="values-title">
		<div class="container stack">
			<h2 id="values-title"><?php echo esc_html( boxes_text( 'highlights_title' ) ); ?></h2>
			<?php get_template_part( 'template-parts/benefits-list', null, array( 'items' => boxes_rows( 'highlights' ) ) ); ?>
		</div>
	</section>
	<section class="section" aria-labelledby="visit-title">
		<div class="container">
			<?php get_template_part( 'template-parts/notice', null, array( 'id' => 'visit-title', 'text' => boxes_text( 'notice_text' ), 'title' => boxes_text( 'notice_title' ) ) ); ?>
		</div>
	</section>
	<?php
endwhile;

get_footer();
