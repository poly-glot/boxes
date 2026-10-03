import { esc, icon } from '../partials/html.mjs';
import { categoryTiles, categoryUrl, pageHero, productGrid } from '../partials/components.mjs';

const categorySection = (site, category) => `<section class="section section--tight" aria-labelledby="shop-${category.slug}">
    <div class="container">
        <div class="section-head">
            <h2 class="section-head__title" id="shop-${category.slug}">${esc(category.name)}</h2>
            <a class="text-link" href="${categoryUrl(category)}">View all ${esc(category.name)}${icon('chevron-right')}</a>
        </div>
        ${productGrid(site.productsByCategory.get(category.slug) ?? [], { level: 3, wide: true, idPrefix: 'shop' })}
    </div>
</section>`;

export default (site) => ({
    path: '/shop/',
    title: 'Shop all packaging',
    description: 'Browse every Boxes range: cartons, mailing bags, paper and carrier bags, food packaging, gift boxes, labels and tape. Trade prices per pack ex VAT.',
    crumbs: [{ label: 'Shop' }],
    body: `${pageHero({ title: 'Shop all packaging', lead: 'Everything we stock, in eight ranges. Every price is per pack and excludes VAT, and every product page shows the bulk discounts that apply when you order more packs. Not sure which size you need? Call us on <a href="tel:+441632960418">01632&nbsp;960&nbsp;418</a> and we will help you choose.', plain: true })}
<section class="section section--tight" aria-labelledby="browse-title">
    <div class="container">
        <h2 class="visually-hidden" id="browse-title">Browse by category</h2>
        ${categoryTiles(site)}
    </div>
</section>
${site.categories.map((category) => categorySection(site, category)).join('\n')}`,
});
