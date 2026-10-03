import { esc, money } from '../partials/html.mjs';
import { addToBasket, categoryUrl, exVat, incVat, productGrid, productPicture, productUrl, stockStatus, withVat } from '../partials/components.mjs';

const tierQuantity = (tiers, index) => {
    const { min } = tiers[index];
    const next = tiers[index + 1];

    if (!next) {
        return `${min}+ packs`;
    }

    return next.min - 1 === min ? `${min} ${min === 1 ? 'pack' : 'packs'}` : `${min}&ndash;${next.min - 1} packs`;
};

const bulkTable = (product) => `<table class="data-table data-table--bulk">
    <caption class="visually-hidden">Bulk discount prices for ${esc(product.name)}</caption>
    <thead>
        <tr><th scope="col">Quantity</th><th scope="col">Price per pack</th></tr>
    </thead>
    <tbody>
        ${product.tiers.map((tier, index) => `<tr><td>${tierQuantity(product.tiers, index)}</td><td class="data-table__price">${money(tier.price)}</td></tr>`).join('\n        ')}
    </tbody>
</table>`;

const specificationTable = (product) => `<table class="data-table data-table--spec">
    <caption class="visually-hidden">Specification for ${esc(product.name)}</caption>
    <tbody>
        <tr><th scope="row">SKU</th><td>${esc(product.sku)}</td></tr>
        ${product.specification.map(([label, value]) => `<tr><th scope="row">${esc(label)}</th><td>${esc(value)}</td></tr>`).join('\n        ')}
    </tbody>
</table>`;

const tabs = (product) => `<div class="product__tabs tabs">
    <ul class="tabs__list">
        <li><a class="tabs__tab" href="#description">Description</a></li>
        <li><a class="tabs__tab" href="#specification">Specification</a></li>
        <li><a class="tabs__tab" href="#delivery">Delivery &amp; returns</a></li>
    </ul>
    <section class="tabs__panel" id="description" tabindex="-1" aria-labelledby="description-title">
        <h2 class="tabs__title" id="description-title">Product description</h2>
        <div class="prose">
            ${product.description.map((paragraph) => `<p>${esc(paragraph)}</p>`).join('\n            ')}
        </div>
    </section>
    <section class="tabs__panel" id="specification" tabindex="-1" aria-labelledby="specification-title">
        <h2 class="tabs__title" id="specification-title">Specification</h2>
        ${specificationTable(product)}
    </section>
    <section class="tabs__panel" id="delivery" tabindex="-1" aria-labelledby="delivery-title">
        <h2 class="tabs__title" id="delivery-title">Delivery &amp; returns</h2>
        <div class="prose">
            <p>Orders placed before 2pm on a working day are dispatched the same day. Standard delivery is £7.95 ex VAT, and free on orders over £150 ex VAT. Mainland UK deliveries arrive the next working day; the Highlands, islands and Northern Ireland take 2&ndash;3 working days. Unopened packs can be returned within 30 days. Read the full <a href="/delivery/">delivery and returns policy</a>.</p>
            ${product.stock === 'made-to-order' ? '<p>This item is assembled to order and dispatched within 5 working days. Made-to-order items cannot be returned unless they arrive damaged or faulty.</p>' : ''}
        </div>
    </section>
</div>`;

const purchase = (product) => `<aside class="product__purchase" aria-labelledby="bulk-title">
    <h2 class="product__purchase-title" id="bulk-title">Bulk discounts</h2>
    ${bulkTable(product)}
    <p class="product__note">Discounts are applied in your basket.</p>
    <p class="price price--large"><span class="price__amount">${money(product.price)}</span> <span class="price__tax">${exVat}</span></p>
    <p class="price__inc">${money(withVat(product.price))} ${incVat}</p>
    ${addToBasket(product, { idPrefix: 'product', large: true })}
</aside>`;

const productPage = (site, product) => {
    const category = site.categoryBySlug.get(product.category);
    const related = (product.related ?? []).map((slug) => site.productBySlug.get(slug)).filter(Boolean).slice(0, 3);
    const relatedSection = related.length === 0 ? '' : `<section class="related section section--tight" aria-labelledby="related-title">
    <div class="container">
        <h2 class="related__title" id="related-title">You may also need</h2>
        ${productGrid(related, { level: 3, idPrefix: 'related' })}
    </div>
</section>`;

    return {
        path: productUrl(product),
        title: product.name,
        description: product.summary,
        current: categoryUrl(category),
        searchable: false,
        crumbs: [{ label: 'Shop', href: '/shop/' }, { label: category.name, href: categoryUrl(category) }, { label: product.name }],
        body: `<div class="product container">
    <div class="product__gallery">
        ${productPicture(product, { className: 'product__image', sizes: '(min-width: 64rem) 290px, (min-width: 48rem) 40vw, 100vw', alt: product.image?.alt ?? '', eager: true })}
    </div>
    <div class="product__summary">
        <h1 class="product__title">${esc(product.name)}</h1>
        <p class="product__sku">SKU: ${esc(product.sku)} <span aria-hidden="true">&middot;</span> ${esc(product.pack)}</p>
        <p class="product__lead">${esc(product.summary)}</p>
        ${stockStatus(product, { note: true })}
    </div>
    ${purchase(product)}
    ${tabs(product)}
</div>
${relatedSection}`,
    };
};

export default (site) => site.products.map((product) => productPage(site, product));
