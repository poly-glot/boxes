<ol class="steps">
	<?php foreach ( $args['items'] as $item ) : ?>
		<li class="steps__item"><strong class="steps__title"><?php echo esc_html( boxes_row_text( $item, 'title' ) ); ?></strong> <span class="steps__text"><?php echo esc_html( boxes_row_text( $item, 'text' ) ); ?></span></li>
	<?php endforeach; ?>
</ol>
