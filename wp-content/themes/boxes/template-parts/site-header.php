<?php
$company        = boxes_company();
$categories     = boxes_menu_links( 'categories' );
$shop_url       = boxes_page_url( 'shop' );
$shop_path      = boxes_path( $shop_url );
$category_links = array_values( array_filter( $categories, fn ( array $link ): bool => boxes_path( $link['url'] ) !== $shop_path ) );
?>
<header class="site-header">
	<div class="utility-bar">
		<div class="utility-bar__inner container">
			<ul class="utility-bar__contact">
				<li><a class="utility-bar__link" href="<?php echo esc_url( 'tel:' . $company['phone_href'] ); ?>"><?php echo boxes_icon( 'phone' ); ?><span class="visually-hidden">Telephone </span><?php echo boxes_nbsp( $company['phone'] ); ?></a></li>
				<li><a class="utility-bar__link" href="<?php echo esc_url( 'mailto:' . $company['email'] ); ?>"><?php echo boxes_icon( 'mail' ); ?><span class="utility-bar__email"><span class="visually-hidden">Email </span><?php echo esc_html( $company['email'] ); ?></span></a></li>
			</ul>
			<nav class="utility-nav" aria-label="Company">
				<ul class="utility-nav__list">
					<?php foreach ( boxes_menu_links( 'utility' ) as $link ) : ?>
						<li><a class="utility-nav__link" href="<?php echo esc_url( $link['url'] ); ?>"<?php echo boxes_current( $link['url'] ); ?>><?php echo esc_html( $link['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
				<a class="pill" href="<?php echo esc_url( boxes_page_url( 'price-list' ) ); ?>"<?php echo boxes_current( boxes_page_url( 'price-list' ) ); ?>>Price list</a>
			</nav>
		</div>
	</div>
	<div class="site-header__main container">
		<?php get_template_part( 'template-parts/logo' ); ?>
		<nav class="category-menu" aria-label="Shop categories">
			<details class="category-menu__disclosure" data-disclosure>
				<summary class="category-menu__toggle">Shop categories<?php echo boxes_icon( 'chevron-down', 'category-menu__chevron' ); ?></summary>
				<div class="category-menu__panel">
					<ul class="category-menu__list">
						<?php foreach ( $category_links as $link ) : ?>
							<li><a class="category-menu__link" href="<?php echo esc_url( $link['url'] ); ?>"<?php echo boxes_current( $link['url'] ); ?>><?php echo esc_html( $link['label'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
					<a class="category-menu__all" href="<?php echo esc_url( $shop_url ); ?>"<?php echo boxes_current( $shop_url ); ?>>Shop all packaging<?php echo boxes_icon( 'chevron-right' ); ?></a>
				</div>
			</details>
		</nav>
		<form class="site-search" role="search" action="<?php echo esc_url( boxes_page_url( BOXES_SEARCH_PAGE ) ); ?>" method="get">
			<label class="visually-hidden" for="site-search-input">Search by keyword or SKU</label>
			<input class="site-search__input" id="site-search-input" type="search" name="s" placeholder="Search by keyword or SKU" autocomplete="off">
			<button class="site-search__button" type="submit"><?php echo boxes_icon( 'search' ); ?><span class="visually-hidden">Search</span></button>
		</form>
		<ul class="header-actions">
			<li>
				<a class="icon-link" href="<?php echo esc_url( boxes_page_url( 'trade-accounts' ) ); ?>"<?php echo boxes_current( boxes_page_url( 'trade-accounts' ) ); ?>>
					<span class="icon-link__circle"><?php echo boxes_icon( 'user' ); ?></span>
					<span class="icon-link__label">Trade account</span>
				</a>
			</li>
			<li>
				<a class="icon-link" href="<?php echo esc_url( boxes_page_url( 'basket' ) ); ?>"<?php echo boxes_current( boxes_page_url( 'basket' ) ); ?>>
					<span class="icon-link__circle"><?php echo boxes_icon( 'basket' ); ?><span class="icon-link__badge" aria-hidden="true" data-basket-badge hidden></span></span>
					<span class="icon-link__label">Basket<span class="visually-hidden" data-basket-summary>, empty</span></span>
				</a>
			</li>
		</ul>
	</div>
</header>
