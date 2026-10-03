<?php
get_header();

$page_post = get_queried_object();
$company   = boxes_company();
?>
<div class="basket container" data-basket>
	<h1 class="basket__title" id="basket-title" tabindex="-1"><?php echo esc_html( boxes_heading( $page_post ) ); ?></h1>
	<p class="visually-hidden" role="status" data-basket-live></p>
	<?php if ( boxes_sent() ) : ?>
		<?php get_template_part( 'template-parts/confirmation', null, array( 'prefix' => 'order' ) ); ?>
	<?php endif; ?>
	<div class="notice" data-basket-fallback>
		<h2 class="notice__title">Your basket needs JavaScript</h2>
		<p>The basket needs JavaScript to work in this demonstration. You can still browse every product and price, or call us on <a href="<?php echo esc_url( $company['phone_href'] ); ?>"><?php echo boxes_nbsp( $company['phone'] ); ?></a> or email <a href="<?php echo esc_url( 'mailto:' . $company['orders_email'] ); ?>"><?php echo esc_html( $company['orders_email'] ); ?></a> to place a trade order.</p>
	</div>
	<div class="notice" data-basket-empty hidden>
		<h2 class="notice__title">Your basket is empty</h2>
		<p>Browse the shop to add packs to your basket. Bulk discounts are applied here automatically as you add more.</p>
		<a class="button" href="<?php echo esc_url( boxes_page_url( 'shop' ) ); ?>">Go to the shop</a>
	</div>
	<div class="basket__filled" data-basket-filled hidden>
		<div class="basket__lines">
			<table class="data-table basket-table">
				<caption class="visually-hidden">Products in your basket</caption>
				<thead>
					<tr>
						<th scope="col">Product</th>
						<th scope="col">Price per pack</th>
						<th scope="col">Quantity</th>
						<th scope="col">Line total</th>
						<th scope="col"><span class="visually-hidden">Remove item</span></th>
					</tr>
				</thead>
				<tbody data-basket-lines></tbody>
			</table>
			<template data-basket-line>
				<tr class="basket-table__row">
					<th class="basket-table__product" scope="row">
						<span class="basket-table__item">
							<img class="basket-table__image" src="<?php echo esc_url( get_theme_file_uri( 'assets/logo.svg' ) ); ?>" width="400" height="400" alt="" data-line-image>
							<span class="basket-table__details">
								<a class="basket-table__name" href="<?php echo esc_url( boxes_page_url( 'shop' ) ); ?>" data-line-link>Product</a>
								<span class="basket-table__meta" data-line-meta></span>
							</span>
						</span>
					</th>
					<td class="basket-table__price"><span data-line-price></span><span class="basket-table__per"> per pack</span><span class="basket-table__tier" data-line-tier hidden></span></td>
					<td class="basket-table__quantity">
						<label class="visually-hidden" for="basket-quantity" data-line-label>Quantity</label>
						<input class="basket-table__input" id="basket-quantity" type="number" min="1" max="<?php echo esc_attr( (string) BOXES_MAX_QUANTITY ); ?>" step="1" inputmode="numeric" data-line-quantity>
					</td>
					<td class="basket-table__total" data-line-total></td>
					<td class="basket-table__remove">
						<button class="basket-table__remove-button" type="button" data-line-remove>Remove<span class="visually-hidden" data-line-remove-name></span></button>
					</td>
				</tr>
			</template>
		</div>
		<section class="basket-summary" aria-labelledby="summary-title">
			<h2 class="visually-hidden" id="summary-title">Order summary</h2>
			<dl class="basket-summary__totals">
				<div class="basket-summary__row"><dt>Subtotal <span class="basket-summary__tax">(ex VAT)</span></dt><dd data-basket-subtotal></dd></div>
				<div class="basket-summary__row"><dt>Delivery <span class="basket-summary__tax">(ex VAT)</span></dt><dd data-basket-delivery></dd></div>
				<div class="basket-summary__row"><dt><?php echo esc_html( sprintf( 'VAT at %s%%', boxes_vat_percent() ) ); ?></dt><dd data-basket-vat></dd></div>
				<div class="basket-summary__row basket-summary__row--total"><dt>Total <span class="basket-summary__tax">(inc VAT)</span></dt><dd data-basket-total></dd></div>
			</dl>
			<p class="basket-summary__note" data-basket-delivery-note></p>
			<p class="basket-summary__dispatch"><?php echo esc_html( boxes_option( 'delivery_cutoff' ) . ' ' . boxes_option( 'delivery_regions' ) ); ?></p>
		</section>
		<section class="order-form" aria-labelledby="order-title">
			<h2 class="order-form__title" id="order-title"><?php echo esc_html( boxes_text( 'form_title', $page_post->ID ) ); ?></h2>
			<p class="order-form__intro"><?php echo esc_html( boxes_text( 'form_intro', $page_post->ID ) ); ?></p>
			<form class="form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post"<?php echo boxes_sent() ? ' hidden' : ''; ?>>
				<?php get_template_part( 'template-parts/form-security', null, array( 'action' => 'boxes_order', 'form' => 'order' ) ); ?>
				<input type="hidden" name="lines" value="[]" data-basket-order-lines>
				<div class="form-grid">
					<?php foreach ( BOXES_FORMS['order']['fields'] as $field ) : ?>
						<?php get_template_part( 'template-parts/form-field', null, array( 'field' => $field ) ); ?>
					<?php endforeach; ?>
				</div>
				<?php get_template_part( 'template-parts/honeypot', null, array( 'prefix' => 'order' ) ); ?>
				<div class="form__actions">
					<button class="button button--action" type="submit">Place trade order</button>
				</div>
				<p class="form__note"><?php echo esc_html( boxes_option( 'form_note' ) ); ?></p>
			</form>
			<?php if ( ! boxes_sent() ) : ?>
				<?php get_template_part( 'template-parts/confirmation', null, array( 'prefix' => 'order' ) ); ?>
			<?php endif; ?>
		</section>
	</div>
</div>
<?php
get_footer();
