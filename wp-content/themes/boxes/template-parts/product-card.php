<?php
$product = $args['product'];
$level   = (int) ( $args['level'] ?? 3 );
?>
<article class="product-card">
	<div class="product-card__main">
		<?php
		get_template_part( 'template-parts/product-picture', null, array(
			'class'   => 'product-card__image',
			'product' => $product,
			'sizes'   => '(min-width: 64rem) 280px, (min-width: 30rem) 45vw, 90vw',
		) );
		?>
		<h<?php echo esc_attr( (string) $level ); ?> class="product-card__title"><a class="product-card__link" href="<?php echo esc_url( $product['url'] ); ?>"><?php echo esc_html( $product['name'] ); ?></a></h<?php echo esc_attr( (string) $level ); ?>>
		<p class="product-card__meta"><span class="product-card__sku">SKU: <?php echo esc_html( $product['sku'] ); ?></span> <span class="product-card__pack"><?php echo esc_html( $product['pack'] ); ?></span></p>
		<p class="product-card__price"><span class="price__amount"><?php echo esc_html( boxes_money( $product['price'] ) ); ?></span> <?php echo boxes_ex_vat(); ?> <span class="product-card__per">per pack</span></p>
		<?php get_template_part( 'template-parts/stock-status', null, array( 'product' => $product ) ); ?>
	</div>
	<?php get_template_part( 'template-parts/add-to-basket', null, array( 'prefix' => $args['prefix'] ?? 'card', 'product' => $product ) ); ?>
</article>
