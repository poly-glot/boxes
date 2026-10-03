<?php
get_header();

$page_post   = get_queried_object();
$by_category = boxes_products_by_category();

get_template_part( 'template-parts/page-hero', null, boxes_hero_args( $page_post, true ) );
?>
<div class="price-list container">
	<div class="price-list__actions">
		<button class="button button--outline" type="button" data-print hidden>Print this price list</button>
	</div>
	<?php foreach ( boxes_terms( 'product_category' ) as $category ) : ?>
		<?php $table_id = 'prices-' . $category->slug; ?>
		<div class="price-list__section">
			<h2 class="price-list__title" id="<?php echo esc_attr( $table_id ); ?>"><?php echo esc_html( $category->name ); ?></h2>
			<section class="table-scroll" aria-labelledby="<?php echo esc_attr( $table_id ); ?>" tabindex="0">
				<table class="data-table price-table">
					<caption class="visually-hidden"><?php echo esc_html( $category->name . ' prices, ex VAT' ); ?></caption>
					<thead>
						<tr>
							<th scope="col">Product</th>
							<th scope="col">SKU</th>
							<th scope="col">Pack</th>
							<th scope="col">Price per pack</th>
							<th scope="col">Bulk price</th>
							<th scope="col">Best price</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $by_category[ $category->slug ] ?? array() as $product ) : ?>
							<tr>
								<th scope="row"><a href="<?php echo esc_url( $product['url'] ); ?>"><?php echo esc_html( $product['name'] ); ?></a></th>
								<td class="price-table__sku"><?php echo esc_html( $product['sku'] ); ?></td>
								<td><?php echo esc_html( $product['pack'] ); ?></td>
								<?php for ( $index = 0; $index < 3; $index++ ) : ?>
									<?php $tier = $product['tiers'][ $index ] ?? null; ?>
									<?php if ( ! $tier ) : ?>
										<td class="price-table__price">&ndash;</td>
									<?php elseif ( 0 === $index ) : ?>
										<td class="price-table__price"><?php echo esc_html( boxes_money( $tier['price'] ) ); ?></td>
									<?php else : ?>
										<td class="price-table__price"><?php echo esc_html( boxes_money( $tier['price'] ) ); ?> <span class="price-table__min"><?php echo esc_html( sprintf( 'from %d packs', $tier['min'] ) ); ?></span></td>
									<?php endif; ?>
								<?php endfor; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</section>
		</div>
	<?php endforeach; ?>
	<p class="price-list__note"><?php echo boxes_inline( boxes_text( 'table_note', $page_post->ID ) ); ?></p>
</div>
<?php
get_footer();
