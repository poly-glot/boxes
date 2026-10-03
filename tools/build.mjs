import { mkdir, readdir, readFile, rm, writeFile } from 'node:fs/promises';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath, pathToFileURL } from 'node:url';
import { renderDocument } from './partials/layout.mjs';
import { productImage, productUrl, categoryUrl } from './partials/components.mjs';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const staticDir = join(root, 'static');
const pagesDir = join(root, 'tools', 'pages');
const cataloguePath = resolve(root, process.env.BOXES_CATALOGUE ?? 'content/catalogue.json');
const keptStaticEntries = new Set(['assets']);
const generatedAssets = ['search-index.json'];

const indexBy = (items, key) => new Map(items.map((item) => [item[key], item]));

const groupBy = (items, key) => {
    const groups = new Map();

    for (const item of items) {
        groups.set(item[key], [...(groups.get(item[key]) ?? []), item]);
    }

    return groups;
};

const createSite = (catalogue) => ({
    ...catalogue,
    categoryBySlug: indexBy(catalogue.categories, 'slug'),
    collectionBySlug: indexBy(catalogue.collections, 'slug'),
    productBySlug: indexBy(catalogue.products, 'slug'),
    productsByCategory: groupBy(catalogue.products, 'category'),
    pages: [],
});

const loadPages = async (site) => {
    const files = (await readdir(pagesDir)).filter((file) => file.endsWith('.mjs')).sort();
    const pages = [];

    for (const file of files) {
        const { default: exported } = await import(pathToFileURL(join(pagesDir, file)));
        const produced = typeof exported === 'function' ? await exported(site) : exported;

        pages.push(...[produced].flat().map((page) => ({ ...page, source: file })));
    }

    return pages;
};

const requiredFields = ['path', 'title', 'description', 'body'];

const assertValidPages = (pages) => {
    const seen = new Set();

    for (const page of pages) {
        const missing = requiredFields.filter((field) => !page[field]);

        if (missing.length > 0) {
            throw new Error(`${page.source}: page ${page.path ?? '(no path)'} is missing ${missing.join(', ')}`);
        }

        if (!/^\/([a-z0-9-]+\/)*([a-z0-9-]+\.html)?$/.test(page.path)) {
            throw new Error(`${page.source}: path ${page.path} must be root-relative and end in / or .html`);
        }

        if (seen.has(page.path)) {
            throw new Error(`${page.source}: duplicate path ${page.path}`);
        }

        seen.add(page.path);
    }
};

const outputFile = (path) => join(staticDir, path.endsWith('.html') ? path : join(path, 'index.html'));

const searchIndex = (site, pages) => ({
    products: site.products.map((product) => ({
        slug: product.slug,
        name: product.name,
        sku: product.sku,
        pack: product.pack,
        price: product.price,
        tiers: product.tiers,
        stock: product.stock,
        image: productImage(product),
        category: product.category,
        categoryName: site.categoryBySlug.get(product.category)?.name ?? '',
        url: productUrl(product),
        summary: product.summary,
    })),
    categories: site.categories.map((category) => ({
        slug: category.slug,
        name: category.name,
        url: categoryUrl(category),
        summary: category.description,
    })),
    pages: pages
        .filter((page) => page.searchable !== false)
        .map((page) => ({ kind: page.kind ?? (page.article ? 'Journal' : 'Page'), title: page.title, url: page.path, summary: page.description })),
});

const cleanStatic = async () => {
    await mkdir(staticDir, { recursive: true });

    const entries = await readdir(staticDir);
    const removable = entries.filter((entry) => !keptStaticEntries.has(entry));

    await Promise.all(removable.map((entry) => rm(join(staticDir, entry), { recursive: true, force: true })));
    await Promise.all(generatedAssets.map((file) => rm(join(staticDir, 'assets', file), { force: true })));
};

const stylesheets = async () => {
    const cssDir = join(staticDir, 'assets', 'css');
    const folder = async (name) => (await readdir(join(cssDir, name))).filter((file) => file.endsWith('.css')).sort().map((file) => `/assets/css/${name}/${file}`);

    return [
        '/assets/css/base/tokens.css',
        '/assets/css/base/base.css',
        '/assets/css/base/layout.css',
        ...(await folder('atoms')),
        ...(await folder('components')),
    ];
};

const build = async () => {
    const catalogue = JSON.parse(await readFile(cataloguePath, 'utf8'));
    const site = createSite(catalogue);
    const pages = await loadPages(site);

    assertValidPages(pages);
    site.pages = pages;

    await cleanStatic();

    const css = await stylesheets();

    for (const page of pages) {
        const file = outputFile(page.path);
        const body = typeof page.body === 'function' ? page.body(site) : page.body;

        await mkdir(dirname(file), { recursive: true });
        await writeFile(file, renderDocument({ ...page, body }, { css, site }));
    }

    await writeFile(join(staticDir, 'assets', 'search-index.json'), `${JSON.stringify(searchIndex(site, pages), null, 2)}\n`);

    console.log(`Built ${pages.length} pages from ${cataloguePath.replace(`${root}/`, '')}`);
};

await build();
