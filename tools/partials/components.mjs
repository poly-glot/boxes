import { company, esc, formatDate, icon, money, phoneText } from './html.mjs';

export const productUrl = (product) => `/products/${product.slug}/`;
export const categoryUrl = (category) => `/shop/${category.slug}/`;
export const collectionUrl = (collection) => `/collections/${collection.slug}/`;

export const productImage = (product) => ({
    small: `/assets/img/products/${product.slug}-400.webp`,
    large: `/assets/img/products/${product.slug}-800.webp`,
    alt: product.image?.alt ?? '',
});

export const withVat = (amount) => Math.round(amount * 120) / 100;

const taxLabel = (short, long) => `<span aria-hidden="true">${short}</span><span class="visually-hidden">${long}</span>`;

export const exVat = taxLabel('ex VAT', 'excluding VAT');
export const incVat = taxLabel('inc VAT', 'including VAT');

export const productPicture = (product, { sizes, className, alt = '', eager = false }) => {
    const image = productImage(product);
    const loading = eager ? 'fetchpriority="high"' : 'loading="lazy"';

    return `<img class="${className}" src="${image.large}" srcset="${image.small} 400w, ${image.large} 800w" sizes="${sizes}" width="800" height="800" ${loading} alt="${esc(alt)}">`;
};

const stockLabels = {
    'in-stock': ['In stock', 'check'],
    'low-stock': ['Low stock', 'clock'],
    'made-to-order': ['Made to order', 'clock'],
};

export const stockStatus = (product, { note = false } = {}) => {
    const [label, symbol] = stockLabels[product.stock] ?? stockLabels['in-stock'];
    const detail = note && product.stockNote ? `<span class="stock__note">${esc(product.stockNote)}</span>` : '';

    return `<p class="stock stock--${product.stock}">${icon(symbol, 'stock__icon')}<span class="stock__label">${label}</span>${detail}</p>`;
};

export const addToBasket = (product, { idPrefix, large = false }) => {
    const id = `${idPrefix}-${product.sku.toLowerCase()}`;
    const modifier = large ? ' add-to-basket--large' : '';

    return `<form class="add-to-basket${modifier}" action="/basket/" method="get" data-sku="${esc(product.sku)}" data-name="${esc(product.name)}">
    <input type="hidden" name="add" value="${esc(product.sku)}">
    <div class="add-to-basket__field">
        <label class="add-to-basket__label" for="${id}">Quantity (packs)</label>
        <input class="add-to-basket__quantity" id="${id}" type="number" name="qty" value="1" min="1" max="999" step="1" inputmode="numeric" required>
    </div>
    <button class="button button--action add-to-basket__button" type="submit">Add to basket<span class="visually-hidden">: ${esc(product.name)}</span></button>
    <p class="add-to-basket__status" role="status" data-basket-status></p>
</form>`;
};

export const productCard = (product, { level = 3, idPrefix = 'card' } = {}) => `<article class="product-card">
    <div class="product-card__main">
        ${productPicture(product, { className: 'product-card__image', sizes: '(min-width: 64rem) 280px, (min-width: 30rem) 45vw, 90vw' })}
        <h${level} class="product-card__title"><a class="product-card__link" href="${productUrl(product)}">${esc(product.name)}</a></h${level}>
        <p class="product-card__meta"><span class="product-card__sku">SKU: ${esc(product.sku)}</span> <span class="product-card__pack">${esc(product.pack)}</span></p>
        <p class="product-card__price"><span class="price__amount">${money(product.price)}</span> ${exVat} <span class="product-card__per">per pack</span></p>
        ${stockStatus(product)}
    </div>
    ${addToBasket(product, { idPrefix })}
</article>`;

export const productGrid = (products, { level = 3, wide = false, idPrefix = 'card' } = {}) => `<ul class="product-grid${wide ? ' product-grid--wide' : ''}">
    ${products.map((product) => `<li class="product-grid__item">${productCard(product, { level, idPrefix })}</li>`).join('\n    ')}
</ul>`;

const serviceTiles = [
    { href: '/custom-printing/', label: 'Custom printing', image: '/assets/img/pages/custom-printing.webp' },
    { href: '/bespoke-boxes/', label: 'Bespoke boxes', image: '/assets/img/pages/bespoke-boxes.webp' },
];

const tile = ({ href, label, image }) => `<li class="category-tiles__item">
        <a class="category-tile" href="${href}">
            ${image}
            <span class="category-tile__label">${label}</span>
        </a>
    </li>`;

export const categoryTiles = (site) => {
    const tileSizes = '(min-width: 77.5rem) 232px, (min-width: 48rem) 19vw, 45vw';

    const categoryItems = site.categories.map((category) => {
        const first = site.productsByCategory.get(category.slug)?.[0];
        const image = first ? productPicture(first, { className: 'category-tile__image', sizes: tileSizes }) : '';

        return tile({ href: categoryUrl(category), label: esc(category.name), image });
    });

    const serviceItems = serviceTiles.map((service) => tile({
        ...service,
        image: `<img class="category-tile__image" src="${service.image}" width="1600" height="900" loading="lazy" alt="">`,
    }));

    return `<ul class="category-tiles">
    ${[...categoryItems, ...serviceItems].join('\n    ')}
</ul>`;
};

export const sidebarNav = (site, page) => {
    const link = (href, label) => {
        const current = href === page.path ? ' aria-current="page"' : '';

        return `<li><a class="sidebar-nav__link" href="${href}"${current}>${esc(label)}</a></li>`;
    };

    return `<nav class="sidebar-nav" aria-label="Shop categories and collections">
    <h2 class="sidebar-nav__heading">Categories</h2>
    <ul class="sidebar-nav__list">
        ${site.categories.map((category) => link(categoryUrl(category), category.name)).join('\n        ')}
    </ul>
    <h2 class="sidebar-nav__heading">Collections</h2>
    <ul class="sidebar-nav__list">
        ${site.collections.map((collection) => link(collectionUrl(collection), collection.name)).join('\n        ')}
        ${link('/shop/', 'Shop all packaging')}
    </ul>
</nav>`;
};

export const shopLayout = (site, page, products) => `<div class="shop-layout container">
    ${sidebarNav(site, page)}
    <div class="shop-layout__main">
        <p class="shop-layout__count">Showing ${products.length} ${products.length === 1 ? 'product' : 'products'}</p>
        ${productGrid(products, { level: 2, idPrefix: 'list' })}
    </div>
</div>`;

export const pageHero = ({ title, lead = '', image = null, plain = false }) => {
    const modifiers = [plain && 'page-hero--plain', image && 'page-hero--with-image'].filter(Boolean).map((name) => ` ${name}`).join('');
    const leadHtml = lead ? `<p class="page-hero__lead">${lead}</p>` : '';
    const imageHtml = image ? `<img class="page-hero__image" src="${image.src}" width="1600" height="900" alt="${esc(image.alt ?? '')}">` : '';

    return `<header class="page-hero${modifiers}">
    <div class="page-hero__inner container">
        <div class="page-hero__text">
            <h1 class="page-hero__title">${title}</h1>
            ${leadHtml}
        </div>
        ${imageHtml}
    </div>
</header>`;
};

export const articlePages = (site) => site.pages
    .filter((page) => page.article)
    .sort((a, b) => b.article.date.localeCompare(a.article.date));

export const articleCard = (page, { level = 3 } = {}) => {
    const { article } = page;

    return `<article class="article-card">
    <img class="article-card__image" src="${article.image.src}" width="800" height="450" loading="lazy" alt="">
    <div class="article-card__body">
        <h${level} class="article-card__title"><a class="article-card__link" href="${page.path}">${esc(page.title)}</a></h${level}>
        <p class="article-card__meta">${esc(article.author)} <span aria-hidden="true">&middot;</span> <time datetime="${article.date}">${formatDate(article.date)}</time>${article.minutes ? ` <span aria-hidden="true">&middot;</span> ${article.minutes} min read` : ''}</p>
        <p class="article-card__excerpt">${esc(article.excerpt)}</p>
    </div>
</article>`;
};

export const mapFrame = ({ alt, caption }) => `<figure class="map-frame">
            <img class="map-frame__map" src="/assets/img/pages/map.svg" width="1600" height="900" loading="lazy" alt="${esc(alt)}">
            <figcaption class="map-frame__caption">${caption}</figcaption>
        </figure>`;

export const steps = (items) => `<ol class="steps">
                ${items.map(([title, text]) => `<li class="steps__item"><strong class="steps__title">${title}</strong> <span class="steps__text">${text}</span></li>`).join('\n                ')}
            </ol>`;

export const demoNote = 'This is a demonstration website. Please do not enter real personal or business details.';
const choosePrompt = '<option value="">Choose an option</option>';

export const errors = {
    email: 'Enter an email address in the correct format, like name@example.co.uk',
    phone: 'Enter a UK phone number, like 01632 960 418',
    postcode: 'Enter a full UK postcode, like NN4 0ZZ',
};

const marker = (required) => ` <span class="field__marker">(${required ? 'required' : 'optional'})</span>`;
const fieldName = (id) => id.slice(id.indexOf('-') + 1);

const describedBy = (id, hint, error) => {
    const ids = [hint && `${id}-hint`, error && `${id}-error`].filter(Boolean).join(' ');

    return ids ? ` aria-describedby="${ids}"` : '';
};

const control = ({ id, type, required, autocomplete, options, attributes, ariaDescribedBy }) => {
    const name = `id="${id}" name="${fieldName(id)}"`;
    const rest = `${required ? ' required' : ''}${attributes}${ariaDescribedBy}`;

    if (type === 'textarea') {
        return `<textarea class="field__control" ${name}${rest}></textarea>`;
    }

    if (type === 'select') {
        return `<select class="field__control" ${name}${rest}>${choosePrompt}${options.map((option) => `<option>${option}</option>`).join('')}</select>`;
    }

    const autocompleteAttribute = autocomplete ? ` autocomplete="${autocomplete}"` : '';

    return `<input class="field__control" ${name} type="${type}"${autocompleteAttribute}${rest}>`;
};

export const field = ({ id, label, type = 'text', required = false, hint = '', error = '', autocomplete = '', options = [], attributes = '', full = false }) => {
    const hintHtml = hint ? `<p class="field__hint" id="${id}-hint">${hint}</p>` : '';
    const errorHtml = error ? `<p class="field__error" id="${id}-error">${error}</p>` : '';
    const wide = full || type === 'textarea' ? ' form-grid__full' : '';
    const input = control({ id, type, required, autocomplete, options, attributes, ariaDescribedBy: describedBy(id, hint, error) });

    return `<div class="field${wide}">
                <label class="field__label" for="${id}">${label}${marker(required)}</label>
                ${hintHtml}
                ${input}
                ${errorHtml}
            </div>`;
};

export const choices = ({ id, legend, options }) => `<fieldset class="fieldset form-grid__full">
                <legend class="fieldset__legend">${legend}${marker(false)}</legend>
                ${options.map((option, index) => `<div class="check">
                    <input class="check__input" id="${id}-${index + 1}" type="radio" name="${fieldName(id)}" value="${option}">
                    <label class="check__label" for="${id}-${index + 1}">${option}</label>
                </div>`).join('\n                ')}
            </fieldset>`;

export const consent = ({ id, label, error }) => `<div class="check form-grid__full">
                <input class="check__input" id="${id}" type="checkbox" name="${fieldName(id)}" required aria-describedby="${id}-error">
                <label class="check__label" for="${id}">${label}${marker(true)}</label>
                <p class="field__error" id="${id}-error">${error}</p>
            </div>`;

export const honeypot = (prefix) => `<div class="visually-hidden">
            <label for="${prefix}-website">Leave this field empty</label>
            <input id="${prefix}-website" type="text" name="website" tabindex="-1" autocomplete="off">
        </div>`;

export const formPanel = ({ prefix, title, fields, submit, confirmation }) => `<section class="form-panel" aria-labelledby="${prefix}-title">
    <h2 class="form-panel__title" id="${prefix}-title">${title}</h2>
    <form class="form" action="#" method="post" data-demo-form="${prefix}-confirmation">
        <div class="form-grid">
            ${fields.join('\n            ')}
        </div>
        ${honeypot(prefix)}
        <div class="form__actions"><button class="button button--action" type="submit">${submit}</button></div>
        <p class="form__note">${demoNote}</p>
    </form>
    <div class="notice notice--success" id="${prefix}-confirmation" tabindex="-1" hidden>
        <h3 class="notice__title">Thank you, this was a demonstration</h3>
        <p>${confirmation}</p>
    </div>
</section>`;

export const phoneLink = `<a href="${company.phoneHref}">${phoneText}</a>`;
export const emailLink = (address) => `<a href="mailto:${address}">${address}</a>`;
