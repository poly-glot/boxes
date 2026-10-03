<div class="faq">
	<?php foreach ( $args['items'] as $item ) : ?>
		<details class="faq__item">
			<summary class="faq__question"><?php echo esc_html( boxes_row_text( $item, 'question' ) ); ?><?php echo boxes_icon( 'plus', 'faq__icon--open' ); ?><?php echo boxes_icon( 'minus', 'faq__icon--close' ); ?></summary>
			<div class="faq__answer prose"><p><?php echo boxes_inline( boxes_row_text( $item, 'answer' ) ); ?></p></div>
		</details>
	<?php endforeach; ?>
</div>
