<?php
get_header();

$page_post   = get_queried_object();
$by_category = boxes_products_by_category();

get_template_part( 'template-parts/page-hero', null, boxes_hero_args( $page_post, true ) );
?>
<section class="section section--tight" aria-labelledby="browse-title">
	<div class="container">
		<h2 class="visually-hidden" id="browse-title">Browse by category</h2>
		<?php get_template_part( 'template-parts/category-tiles' ); ?>
	</div>
</section>
<?php foreach ( boxes_terms( 'product_category' ) as $category ) : ?>
	<section class="section section--tight" aria-labelledby="<?php echo esc_attr( 'shop-' . $category->slug ); ?>">
		<div class="container">
			<?php
			get_template_part( 'template-parts/section-head', null, array(
				'id'         => 'shop-' . $category->slug,
				'link_label' => 'View all ' . $category->name,
				'link_url'   => boxes_term_url( $category ),
				'title'      => $category->name,
			) );
			get_template_part( 'template-parts/product-grid', null, array( 'prefix' => 'shop', 'products' => $by_category[ $category->slug ] ?? array(), 'wide' => true ) );
			?>
		</div>
	</section>
<?php endforeach; ?>
<?php
get_footer();
