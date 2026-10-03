import { company, currentAttr, esc, icon, phoneText } from './html.mjs';

const utilityLinks = [
    ['/about/', 'About'],
    ['/sustainability/', 'Sustainability'],
    ['/delivery/', 'Delivery &amp; returns'],
    ['/journal/', 'Journal'],
    ['/contact/', 'Contact'],
];

const logo = (className = '') => `<a class="logo${className ? ` ${className}` : ''}" href="/">
    <svg class="logo__mark" aria-hidden="true" focusable="false"><use href="#logo-mark" /></svg>
    <span class="logo__wordmark" aria-hidden="true">boxes</span><span class="visually-hidden">Boxes home</span>
</a>`;

const utilityBar = (page) => `<div class="utility-bar">
    <div class="utility-bar__inner container">
        <ul class="utility-bar__contact">
            <li><a class="utility-bar__link" href="${company.phoneHref}">${icon('phone')}<span class="visually-hidden">Telephone </span>${phoneText}</a></li>
            <li><a class="utility-bar__link" href="mailto:${company.email}">${icon('mail')}<span class="utility-bar__email"><span class="visually-hidden">Email </span>${company.email}</span></a></li>
        </ul>
        <nav class="utility-nav" aria-label="Company">
            <ul class="utility-nav__list">
                ${utilityLinks.map(([href, label]) => `<li><a class="utility-nav__link" href="${href}"${currentAttr(href, page)}>${label}</a></li>`).join('\n                ')}
            </ul>
            <a class="pill" href="/price-list/"${currentAttr('/price-list/', page)}>Price list</a>
        </nav>
    </div>
</div>`;

const categoryMenu = (page, categories) => `<nav class="category-menu" aria-label="Shop categories">
    <details class="category-menu__disclosure" data-disclosure>
        <summary class="category-menu__toggle">Shop categories${icon('chevron-down', 'category-menu__chevron')}</summary>
        <div class="category-menu__panel">
            <ul class="category-menu__list">
                ${categories.map((category) => {
                    const href = `/shop/${category.slug}/`;

                    return `<li><a class="category-menu__link" href="${href}"${currentAttr(href, page)}>${esc(category.name)}</a></li>`;
                }).join('\n                ')}
            </ul>
            <a class="category-menu__all" href="/shop/"${currentAttr('/shop/', page)}>Shop all packaging${icon('chevron-right')}</a>
        </div>
    </details>
</nav>`;

const searchForm = () => `<form class="site-search" role="search" action="/search/" method="get">
    <label class="visually-hidden" for="site-search-input">Search by keyword or SKU</label>
    <input class="site-search__input" id="site-search-input" type="search" name="s" placeholder="Search by keyword or SKU" autocomplete="off">
    <button class="site-search__button" type="submit">${icon('search')}<span class="visually-hidden">Search</span></button>
</form>`;

const accountLinks = (page) => `<ul class="header-actions">
    <li>
        <a class="icon-link" href="/trade-accounts/"${currentAttr('/trade-accounts/', page)}>
            <span class="icon-link__circle">${icon('user')}</span>
            <span class="icon-link__label">Trade account</span>
        </a>
    </li>
    <li>
        <a class="icon-link" href="/basket/"${currentAttr('/basket/', page)}>
            <span class="icon-link__circle">${icon('basket')}<span class="icon-link__badge" aria-hidden="true" data-basket-badge hidden></span></span>
            <span class="icon-link__label">Basket<span class="visually-hidden" data-basket-summary>, empty</span></span>
        </a>
    </li>
</ul>`;

export const header = (page, site) => `<header class="site-header">
    ${utilityBar(page)}
    <div class="site-header__main container">
        ${logo()}
        ${categoryMenu(page, site.categories)}
        ${searchForm()}
        ${accountLinks(page)}
    </div>
</header>`;
