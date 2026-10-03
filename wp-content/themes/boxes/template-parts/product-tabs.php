<?php
$product = $args['product'];
?>
<div class="product__tabs tabs">
	<ul class="tabs__list">
		<li><a class="tabs__tab" href="#description">Description</a></li>
		<li><a class="tabs__tab" href="#specification">Specification</a></li>
		<li><a class="tabs__tab" href="#delivery">Delivery &amp; returns</a></li>
	</ul>
	<section class="tabs__panel" id="description" tabindex="-1" aria-labelledby="description-title">
		<h2 class="tabs__title" id="description-title">Product description</h2>
		<div class="prose">
			<?php the_content(); ?>
		</div>
	</section>
	<section class="tabs__panel" id="specification" tabindex="-1" aria-labelledby="specification-title">
		<h2 class="tabs__title" id="specification-title">Specification</h2>
		<table class="data-table data-table--spec">
			<caption class="visually-hidden"><?php echo esc_html( 'Specification for ' . $product['name'] ); ?></caption>
			<tbody>
				<tr><th scope="row">SKU</th><td><?php echo esc_html( $product['sku'] ); ?></td></tr>
				<?php foreach ( boxes_rows( 'specification', $product['id'] ) as $row ) : ?>
					<tr><th scope="row"><?php echo esc_html( boxes_row_text( $row, 'label' ) ); ?></th><td><?php echo esc_html( boxes_row_text( $row, 'value' ) ); ?></td></tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</section>
	<section class="tabs__panel" id="delivery" tabindex="-1" aria-labelledby="delivery-title">
		<h2 class="tabs__title" id="delivery-title">Delivery &amp; returns</h2>
		<div class="prose">
			<p><?php echo boxes_inline( boxes_option( 'product_delivery_text' ) ); ?></p>
			<?php if ( 'made-to-order' === $product['stock'] ) : ?>
				<p><?php echo esc_html( boxes_option( 'product_made_to_order_text' ) ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</div>
