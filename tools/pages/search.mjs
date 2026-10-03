import { esc } from '../partials/html.mjs';
import { categoryUrl, pageHero } from '../partials/components.mjs';

export default (site) => ({
    path: '/search/',
    title: 'Search',
    description: 'Search the Boxes range by product name, material or SKU.',
    searchable: false,
    crumbs: [{ label: 'Search' }],
    body: `${pageHero({ title: 'Search', plain: true })}
<div class="search-page container">
    <form class="search-form" role="search" aria-label="Search again" action="/search/" method="get">
        <label class="visually-hidden" for="search-page-input">Search by keyword or SKU</label>
        <div class="search-form__row">
            <input class="field__control search-form__input" id="search-page-input" type="search" name="s" placeholder="Search by keyword or SKU" autocomplete="off">
            <button class="button" type="submit">Search</button>
        </div>
    </form>
    <section class="search-results" aria-labelledby="results-title" data-search>
        <h2 class="search-results__title" id="results-title" tabindex="-1">Results</h2>
        <p class="search-results__summary" role="status" data-search-summary></p>
        <div class="search-results__fallback" data-search-fallback>
            <p data-search-nojs>Search needs JavaScript in this demonstration. You can browse every product from the category list below, or see the full <a href="/price-list/">price list</a>.</p>
            <ul class="search-results__categories">
                ${site.categories.map((category) => `<li><a href="${categoryUrl(category)}">${esc(category.name)}</a></li>`).join('\n                ')}
            </ul>
        </div>
        <ol class="search-results__list" data-search-list></ol>
        <template data-search-result>
            <li class="result">
                <img class="result__image" src="/assets/img/products/single-wall-postal-carton-400.webp" width="400" height="400" loading="lazy" alt="" data-result-image>
                <div class="result__body">
                    <p class="result__type" data-result-type></p>
                    <h3 class="result__title"><a class="result__link" href="/" data-result-link>Result</a></h3>
                    <p class="result__meta" data-result-meta></p>
                    <p class="result__excerpt" data-result-excerpt></p>
                </div>
            </li>
        </template>
    </section>
</div>`,
});
