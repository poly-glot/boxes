<ul class="<?php echo esc_attr( $args['class'] ?? 'site-footer__list' ); ?>">
	<?php foreach ( $args['links'] as $link ) : ?>
		<li><a class="site-footer__link" href="<?php echo esc_url( $link['url'] ); ?>"<?php echo boxes_current( $link['url'] ); ?>><?php echo esc_html( $link['label'] ); ?></a></li>
	<?php endforeach; ?>
</ul>
