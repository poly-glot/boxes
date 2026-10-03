<?php
$company = boxes_company();
$columns = array(
	'shop'    => 'Shop',
	'help'    => 'Help',
	'company' => 'Company',
);
$legal   = sprintf(
	'© %s %s. Registered in England and Wales, company no. %s. VAT %s.',
	wp_date( 'Y' ),
	$company['legal_name'],
	$company['company_number'],
	$company['vat_number']
);
?>
<footer class="site-footer">
	<div class="site-footer__inner container">
		<nav class="site-footer__nav" aria-label="Footer">
			<?php foreach ( $columns as $location => $heading ) : ?>
				<div class="site-footer__column">
					<h2 class="site-footer__heading"><?php echo esc_html( $heading ); ?></h2>
					<?php get_template_part( 'template-parts/link-list', null, array( 'links' => boxes_menu_links( $location ) ) ); ?>
				</div>
			<?php endforeach; ?>
		</nav>
		<div class="site-footer__column site-footer__contact">
			<h2 class="site-footer__heading">Contact</h2>
			<address class="site-footer__address"><?php echo implode( '<br>', array_map( 'esc_html', array_merge( array( $company['legal_name'] ), $company['address'] ) ) ); ?></address>
			<p><a class="site-footer__link" href="<?php echo esc_url( 'tel:' . $company['phone_href'] ); ?>"><span class="visually-hidden">Telephone </span><?php echo boxes_nbsp( $company['phone'] ); ?></a><br><a class="site-footer__link" href="<?php echo esc_url( 'mailto:' . $company['email'] ); ?>"><span class="visually-hidden">Email </span><?php echo esc_html( $company['email'] ); ?></a></p>
			<p><?php echo esc_html( $company['hours'] ); ?></p>
		</div>
		<div class="site-footer__legal">
			<p><?php echo esc_html( $legal ); ?></p>
			<nav aria-label="Legal">
				<?php get_template_part( 'template-parts/link-list', null, array( 'class' => 'site-footer__legal-list', 'links' => boxes_menu_links( 'legal' ) ) ); ?>
			</nav>
			<p class="site-footer__notice"><?php echo esc_html( $company['footer_notice'] ); ?></p>
		</div>
	</div>
</footer>
