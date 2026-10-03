<ul>
	<?php foreach ( $args['items'] as $item ) : ?>
		<li><strong><?php echo esc_html( boxes_row_text( $item, 'label' ) ); ?></strong> <?php echo esc_html( boxes_row_text( $item, 'text' ) ); ?></li>
	<?php endforeach; ?>
</ul>
