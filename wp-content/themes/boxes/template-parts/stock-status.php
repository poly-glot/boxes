<?php
$product = $args['product'];
$stock   = BOXES_STOCK[ $product['stock'] ] ?? BOXES_STOCK['in-stock'];
$note    = ! empty( $args['note'] ) ? $product['stock_note'] : '';
?>
<p class="stock stock--<?php echo esc_attr( $product['stock'] ); ?>"><?php echo boxes_icon( $stock['icon'], 'stock__icon' ); ?><span class="stock__label"><?php echo esc_html( $stock['label'] ); ?></span><?php if ( '' !== $note ) : ?><span class="stock__note"><?php echo esc_html( $note ); ?></span><?php endif; ?></p>
