<?php
$page_id = $args['page_id'];
?>
<div class="section container">
	<div class="grid-2">
		<section class="prose" aria-labelledby="<?php echo esc_attr( $args['guide_id'] ); ?>">
			<h2 id="<?php echo esc_attr( $args['guide_id'] ); ?>"><?php echo esc_html( boxes_text( 'guide_title', $page_id ) ); ?></h2>
			<?php echo wp_kses_post( boxes_text( 'guide_content', $page_id ) ); ?>
		</section>
		<section class="stack" aria-labelledby="process-title">
			<h2 id="process-title"><?php echo esc_html( boxes_text( 'steps_title', $page_id ) ); ?></h2>
			<?php get_template_part( 'template-parts/steps', null, array( 'items' => boxes_rows( 'steps', $page_id ) ) ); ?>
		</section>
	</div>
</div>
