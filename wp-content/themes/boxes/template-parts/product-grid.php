<?php
$classes = empty( $args['wide'] ) ? 'product-grid' : 'product-grid product-grid--wide';
?>
<ul class="<?php echo esc_attr( $classes ); ?>">
	<?php foreach ( $args['products'] as $product ) : ?>
		<li class="product-grid__item"><?php get_template_part( 'template-parts/product-card', null, array( 'level' => $args['level'] ?? 3, 'prefix' => $args['prefix'] ?? 'card', 'product' => $product ) ); ?></li>
	<?php endforeach; ?>
</ul>
