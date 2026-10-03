<input type="hidden" name="action" value="<?php echo esc_attr( $args['action'] ); ?>">
<input type="hidden" name="boxes_nonce" value="<?php echo esc_attr( wp_create_nonce( $args['action'] ) ); ?>">
<input type="hidden" name="form" value="<?php echo esc_attr( $args['form'] ); ?>">
