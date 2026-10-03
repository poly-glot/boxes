import { pageHero } from '../partials/components.mjs';

export default {
    path: '/404.html',
    title: 'Page not found',
    description: 'The page you were looking for could not be found. Search the Boxes range or browse all packaging.',
    searchable: false,
    body: `${pageHero({ title: 'We could not find that page', lead: 'The page may have moved, or the address may have a typo. Try searching by keyword or SKU, or start from one of these:', plain: true })}
<div class="not-found container">
    <form class="search-form" role="search" aria-label="Search the shop" action="/search/" method="get">
        <label class="visually-hidden" for="not-found-search">Search by keyword or SKU</label>
        <div class="search-form__row">
            <input class="field__control search-form__input" id="not-found-search" type="search" name="s" placeholder="Search by keyword or SKU" autocomplete="off">
            <button class="button" type="submit">Search</button>
        </div>
    </form>
    <ul class="link-list">
        <li><a href="/shop/">Shop all packaging</a></li>
        <li><a href="/delivery/">Delivery &amp; returns</a></li>
        <li><a href="/contact/">Contact us</a></li>
        <li><a href="/">Home</a></li>
    </ul>
</div>`,
};
