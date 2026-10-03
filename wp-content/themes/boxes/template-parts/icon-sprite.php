<svg class="sprite" xmlns="http://www.w3.org/2000/svg" hidden>
	<?php echo BOXES_LOGO_MARK; ?>
	<?php foreach ( array_keys( BOXES_ICONS ) as $name ) : ?>
		<symbol id="icon-<?php echo esc_attr( $name ); ?>" viewBox="0 0 24 24"><?php echo BOXES_ICONS[ $name ]; ?></symbol>
	<?php endforeach; ?>
</svg>
