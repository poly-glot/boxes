import { esc } from '../partials/html.mjs';
import { collectionUrl, pageHero, shopLayout } from '../partials/components.mjs';

export default (site) => site.collections.map((collection) => {
    const page = { path: collectionUrl(collection) };
    const products = collection.products.map((slug) => site.productBySlug.get(slug)).filter(Boolean);

    return {
        ...page,
        title: collection.name,
        description: collection.description,
        kind: 'Collection',
        crumbs: [{ label: 'Shop', href: '/shop/' }, { label: collection.name }],
        body: `${pageHero({ title: esc(collection.name), lead: esc(collection.intro), plain: true })}
${shopLayout(site, page, products)}`,
    };
});
