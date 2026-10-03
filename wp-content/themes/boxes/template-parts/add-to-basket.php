<?php
$product  = $args['product'];
$field_id = $args['prefix'] . '-' . strtolower( $product['sku'] );
$classes  = empty( $args['large'] ) ? 'add-to-basket' : 'add-to-basket add-to-basket--large';
?>
<form class="<?php echo esc_attr( $classes ); ?>" action="<?php echo esc_url( boxes_page_url( 'basket' ) ); ?>" method="get" data-sku="<?php echo esc_attr( $product['sku'] ); ?>" data-name="<?php echo esc_attr( $product['name'] ); ?>">
	<input type="hidden" name="add" value="<?php echo esc_attr( $product['sku'] ); ?>">
	<div class="add-to-basket__field">
		<label class="add-to-basket__label" for="<?php echo esc_attr( $field_id ); ?>">Quantity (packs)</label>
		<input class="add-to-basket__quantity" id="<?php echo esc_attr( $field_id ); ?>" type="number" name="qty" value="1" min="1" max="<?php echo esc_attr( (string) BOXES_MAX_QUANTITY ); ?>" step="1" inputmode="numeric" required>
	</div>
	<button class="button button--action add-to-basket__button" type="submit">Add to basket<span class="visually-hidden">: <?php echo esc_html( $product['name'] ); ?></span></button>
	<p class="add-to-basket__status" role="status" data-basket-status></p>
</form>
