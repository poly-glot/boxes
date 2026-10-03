<div class="section-head">
	<h2 class="section-head__title" id="<?php echo esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['title'] ); ?></h2>
	<?php if ( ! empty( $args['link_url'] ) ) : ?>
		<a class="text-link" href="<?php echo esc_url( $args['link_url'] ); ?>"><?php echo esc_html( $args['link_label'] ); ?><?php echo boxes_icon( 'chevron-right' ); ?></a>
	<?php endif; ?>
</div>
