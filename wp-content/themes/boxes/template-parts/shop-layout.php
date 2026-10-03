<?php
$products = $args['products'];
$count    = count( $products );
$shop_url = boxes_page_url( 'shop' );
?>
<div class="shop-layout container">
	<nav class="sidebar-nav" aria-label="Shop categories and collections">
		<h2 class="sidebar-nav__heading">Categories</h2>
		<ul class="sidebar-nav__list">
			<?php foreach ( boxes_terms( 'product_category' ) as $term ) : ?>
				<li><a class="sidebar-nav__link" href="<?php echo esc_url( boxes_term_url( $term ) ); ?>"<?php echo boxes_current( boxes_term_url( $term ) ); ?>><?php echo esc_html( $term->name ); ?></a></li>
			<?php endforeach; ?>
		</ul>
		<h2 class="sidebar-nav__heading">Collections</h2>
		<ul class="sidebar-nav__list">
			<?php foreach ( boxes_terms( 'collection' ) as $term ) : ?>
				<li><a class="sidebar-nav__link" href="<?php echo esc_url( boxes_term_url( $term ) ); ?>"<?php echo boxes_current( boxes_term_url( $term ) ); ?>><?php echo esc_html( $term->name ); ?></a></li>
			<?php endforeach; ?>
			<li><a class="sidebar-nav__link" href="<?php echo esc_url( $shop_url ); ?>"<?php echo boxes_current( $shop_url ); ?>>Shop all packaging</a></li>
		</ul>
	</nav>
	<div class="shop-layout__main">
		<p class="shop-layout__count"><?php echo esc_html( sprintf( 'Showing %d %s', $count, 1 === $count ? 'product' : 'products' ) ); ?></p>
		<?php get_template_part( 'template-parts/product-grid', null, array( 'level' => 2, 'prefix' => 'list', 'products' => $products ) ); ?>
	</div>
</div>
