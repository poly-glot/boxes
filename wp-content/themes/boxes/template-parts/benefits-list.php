<ul class="benefits__list">
	<?php foreach ( $args['items'] as $item ) : ?>
		<?php
		$title = boxes_row_text( $item, 'title' );
		$text  = boxes_row_text( $item, $args['text_key'] ?? 'text' );
		?>
		<li class="benefits__item">
			<span class="benefits__icon"><?php echo boxes_icon( boxes_row_text( $item, 'icon' ) ?: 'check' ); ?></span>
			<?php if ( '' === $title ) : ?>
				<span class="benefits__text"><?php echo esc_html( $text ); ?></span>
			<?php else : ?>
				<span class="benefits__text"><strong class="benefits__title"><?php echo esc_html( $title ); ?></strong> <?php echo esc_html( $text ); ?></span>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>
