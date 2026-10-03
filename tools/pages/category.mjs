import { esc } from '../partials/html.mjs';
import { categoryUrl, pageHero, shopLayout } from '../partials/components.mjs';

export default (site) => site.categories.map((category) => {
    const page = { path: categoryUrl(category) };
    const products = site.productsByCategory.get(category.slug) ?? [];

    return {
        ...page,
        title: category.name,
        description: category.description,
        searchable: false,
        crumbs: [{ label: 'Shop', href: '/shop/' }, { label: category.name }],
        body: `${pageHero({ title: esc(category.name), lead: esc(category.intro), plain: true })}
${shopLayout(site, page, products)}`,
    };
});
