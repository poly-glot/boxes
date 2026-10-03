import { esc, money } from '../partials/html.mjs';
import { pageHero, productUrl } from '../partials/components.mjs';

const tierCell = (tier, index) => {
    if (!tier) {
        return '<td class="price-table__price">&ndash;</td>';
    }

    const quantity = index === 0 ? '' : ` <span class="price-table__min">from ${tier.min} packs</span>`;

    return `<td class="price-table__price">${money(tier.price)}${quantity}</td>`;
};

const categoryTable = (site, category) => {
    const products = site.productsByCategory.get(category.slug) ?? [];
    const id = `prices-${category.slug}`;

    return `<div class="price-list__section">
    <h2 class="price-list__title" id="${id}">${esc(category.name)}</h2>
    <section class="table-scroll" aria-labelledby="${id}" tabindex="0">
        <table class="data-table price-table">
            <caption class="visually-hidden">${esc(category.name)} prices, ex VAT</caption>
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
                ${products.map((product) => `<tr>
                    <th scope="row"><a href="${productUrl(product)}">${esc(product.name)}</a></th>
                    <td class="price-table__sku">${esc(product.sku)}</td>
                    <td>${esc(product.pack)}</td>
                    ${[0, 1, 2].map((index) => tierCell(product.tiers[index], index)).join('\n                    ')}
                </tr>`).join('\n                ')}
            </tbody>
        </table>
    </section>
</div>`;
};

export default (site) => ({
    path: '/price-list/',
    title: 'Price list',
    description: 'The full Boxes price list: every product with SKU, pack size and bulk discount prices ex VAT. Printable for your purchasing file.',
    crumbs: [{ label: 'Price list' }],
    body: `${pageHero({ title: 'Price list', lead: 'Every product we stock, with its SKU, pack size and three bulk prices. All prices are per pack, in pounds sterling, excluding VAT, and are correct for this demonstration only. Print this page or save it as a PDF for your purchasing file.', plain: true })}
<div class="price-list container">
    <div class="price-list__actions">
        <button class="button button--outline" type="button" data-print hidden>Print this price list</button>
    </div>
    ${site.categories.map((category) => categoryTable(site, category)).join('\n    ')}
    <p class="price-list__note">Delivery is £7.95 ex VAT, free on orders over £150 ex VAT. VAT is charged at 20%.</p>
</div>`,
});
