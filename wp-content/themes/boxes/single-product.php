<?php
get_header();

while ( have_posts() ) :
	the_post();

	$product = boxes_product( get_post() );
	$tiers   = $product['tiers'];
	$related = boxes_related_products( $product['id'] );
	?>
	<div class="product container">
		<div class="product__gallery">
			<?php
			get_template_part( 'template-parts/product-picture', null, array(
				'alt'     => $product['image']['alt'],
				'class'   => 'product__image',
				'eager'   => true,
				'product' => $product,
				'sizes'   => '(min-width: 64rem) 290px, (min-width: 48rem) 40vw, 100vw',
			) );
			?>
		</div>
		<div class="product__summary">
			<h1 class="product__title"><?php echo esc_html( $product['name'] ); ?></h1>
			<p class="product__sku">SKU: <?php echo esc_html( $product['sku'] ); ?> <span aria-hidden="true">&middot;</span> <?php echo esc_html( $product['pack'] ); ?></p>
			<p class="product__lead"><?php echo esc_html( $product['summary'] ); ?></p>
			<?php get_template_part( 'template-parts/stock-status', null, array( 'note' => true, 'product' => $product ) ); ?>
		</div>
		<aside class="product__purchase" aria-labelledby="bulk-title">
			<h2 class="product__purchase-title" id="bulk-title">Bulk discounts</h2>
			<table class="data-table data-table--bulk">
				<caption class="visually-hidden"><?php echo esc_html( 'Bulk discount prices for ' . $product['name'] ); ?></caption>
				<thead>
					<tr><th scope="col">Quantity</th><th scope="col">Price per pack</th></tr>
				</thead>
				<tbody>
					<?php foreach ( $tiers as $index => $tier ) : ?>
						<tr><td><?php echo esc_html( boxes_tier_quantity( $tiers, $index ) ); ?></td><td class="data-table__price"><?php echo esc_html( boxes_money( $tier['price'] ) ); ?></td></tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<p class="product__note">Discounts are applied in your basket.</p>
			<p class="price price--large"><span class="price__amount"><?php echo esc_html( boxes_money( $product['price'] ) ); ?></span> <span class="price__tax"><?php echo boxes_ex_vat(); ?></span></p>
			<p class="price__inc"><?php echo esc_html( boxes_money( boxes_with_vat( $product['price'], boxes_vat_percent() ) ) ); ?> <?php echo boxes_inc_vat(); ?></p>
			<?php get_template_part( 'template-parts/add-to-basket', null, array( 'large' => true, 'prefix' => 'product', 'product' => $product ) ); ?>
		</aside>
		<?php get_template_part( 'template-parts/product-tabs', null, array( 'product' => $product ) ); ?>
	</div>
	<?php if ( $related ) : ?>
		<section class="related section section--tight" aria-labelledby="related-title">
			<div class="container">
				<h2 class="related__title" id="related-title">You may also need</h2>
				<?php get_template_part( 'template-parts/product-grid', null, array( 'prefix' => 'related', 'products' => $related ) ); ?>
			</div>
		</section>
	<?php endif; ?>
	<?php
endwhile;

get_footer();
