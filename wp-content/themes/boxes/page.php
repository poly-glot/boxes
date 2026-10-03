<?php
get_header();

while ( have_posts() ) :
	the_post();

	$last_updated = boxes_text( 'last_updated' );
	$notice_title = boxes_text( 'notice_title' );

	get_template_part( 'template-parts/page-hero', null, boxes_hero_args( get_post(), true ) );
	?>
	<div class="container section--tight section stack">
		<?php if ( '' !== $last_updated ) : ?>
			<p class="meta">Last updated: <time datetime="<?php echo esc_attr( $last_updated ); ?>"><?php echo esc_html( boxes_date( $last_updated ) ); ?></time></p>
		<?php endif; ?>
		<?php if ( '' !== $notice_title ) : ?>
			<?php get_template_part( 'template-parts/notice', null, array( 'text' => boxes_text( 'notice_text' ), 'title' => $notice_title ) ); ?>
		<?php endif; ?>
		<div class="prose">
			<?php the_content(); ?>
		</div>
	</div>
	<?php
endwhile;

get_footer();
