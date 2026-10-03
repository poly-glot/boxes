<?php
$is_order = 'order' === $args['prefix'];
$title    = $is_order ? BOXES_CONFIRMATION_TITLES['order'] : BOXES_CONFIRMATION_TITLES['enquiry'];
$placed   = $is_order && boxes_sent();
$level    = $placed ? 'h2' : 'h3';
?>
<div class="notice notice--success" id="<?php echo esc_attr( $args['prefix'] . '-confirmation' ); ?>" tabindex="-1"<?php echo boxes_sent() ? '' : ' hidden'; ?><?php echo $placed ? ' data-basket-order-placed' : ''; ?>>
	<<?php echo tag_escape( $level ); ?> class="notice__title"><?php echo esc_html( $title ); ?></<?php echo tag_escape( $level ); ?>>
	<p><?php echo esc_html( BOXES_CONFIRMATION_TEXT ); ?></p>
</div>
