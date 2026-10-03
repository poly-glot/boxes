<?php
get_header();

get_template_part( 'template-parts/page-hero', null, array(
	'lead'  => 'The page may have moved, or the address may have a typo. Try searching by keyword or SKU, or start from one of these:',
	'plain' => true,
	'title' => 'We could not find that page',
) );
?>
<div class="not-found container">
	<?php get_template_part( 'template-parts/search-form', null, array( 'id' => 'not-found-search', 'label' => 'Search the shop' ) ); ?>
	<ul class="link-list">
		<li><a href="<?php echo esc_url( boxes_page_url( 'shop' ) ); ?>">Shop all packaging</a></li>
		<li><a href="<?php echo esc_url( boxes_page_url( 'delivery' ) ); ?>">Delivery &amp; returns</a></li>
		<li><a href="<?php echo esc_url( boxes_page_url( 'contact' ) ); ?>">Contact us</a></li>
		<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
	</ul>
</div>
<?php
get_footer();
