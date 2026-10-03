<div class="notice">
	<h2 class="notice__title"<?php echo empty( $args['id'] ) ? '' : ' id="' . esc_attr( $args['id'] ) . '"'; ?>><?php echo esc_html( $args['title'] ); ?></h2>
	<p><?php echo boxes_inline( $args['text'] ); ?></p>
</div>
