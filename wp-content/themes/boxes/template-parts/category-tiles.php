<?php
$by_category = boxes_products_by_category();
$tile_sizes  = '(min-width: 77.5rem) 232px, (min-width: 48rem) 19vw, 45vw';
?>
<ul class="category-tiles">
	<?php foreach ( boxes_terms( 'product_category' ) as $category ) : ?>
		<?php $first = $by_category[ $category->slug ][0] ?? null; ?>
		<li class="category-tiles__item">
			<a class="category-tile" href="<?php echo esc_url( boxes_term_url( $category ) ); ?>">
				<?php
				if ( $first ) {
					get_template_part( 'template-parts/product-picture', null, array(
						'class'   => 'category-tile__image',
						'product' => $first,
						'sizes'   => $tile_sizes,
					) );
				}
				?>
				<span class="category-tile__label"><?php echo esc_html( $category->name ); ?></span>
			</a>
		</li>
	<?php endforeach; ?>
	<?php foreach ( BOXES_SERVICE_PAGES as $path ) : ?>
		<?php $service = boxes_page( $path ); ?>
		<?php if ( $service ) : ?>
			<li class="category-tiles__item">
				<a class="category-tile" href="<?php echo esc_url( (string) get_permalink( $service ) ); ?>">
					<?php
					echo boxes_img( array(
						'class'   => 'category-tile__image',
						'src'     => boxes_image_url( (int) get_post_thumbnail_id( $service ), 'boxes-page' ),
						'width'   => '1600',
						'height'  => '900',
						'loading' => 'lazy',
						'alt'     => '',
					) );
					?>
					<span class="category-tile__label"><?php echo esc_html( $service->post_title ); ?></span>
				</a>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
