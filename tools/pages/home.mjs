import { esc, icon } from '../partials/html.mjs';
import { articleCard, articlePages, categoryTiles, productGrid } from '../partials/components.mjs';

const copy = {
    documentTitle: 'Boxes | Trade packaging for small businesses',
    title: 'Trade packaging for small businesses',
    description: 'Boxes, mailing bags, paper bags, food packaging, labels and tape in trade packs. Bulk discounts, same-day dispatch before 2pm, free delivery over £150.',
    heading: 'Trade packaging for small businesses',
    slides: [
        { title: 'Packaging for small businesses, in packs', lead: 'Boxes, mailers, bags and tape in sensible pack sizes, with bulk prices that drop as you order more.', cta: 'Shop all packaging', href: '/shop/', alt: 'A tidy packing bench with brown cartons, paper tape and mailers ready to send' },
        { title: 'Plastic-free mailing that still protects', lead: 'Honeycomb paper mailers, kraft tape and paper wrap that go straight into the recycling with the box.', cta: 'See new arrivals', href: '/collections/new-arrivals/', alt: 'A ceramic mug being wrapped in honeycomb kraft paper beside an open mailer box' },
        { title: 'Café and bakery packaging, ready tomorrow', lead: 'Cups, food boxes and bakery bags dispatched the same day when you order before 2pm.', cta: 'Shop food packaging', href: '/shop/food-packaging/', alt: 'A café counter with paper coffee cups, kraft food boxes and bakery bags with pastries' },
    ],
    promos: [
        { slug: 'new-arrivals', label: 'New arrivals', note: 'Plastic-free and recycled lines, new this season.' },
        { slug: 'best-sellers', label: 'Best sellers', note: 'The staples our customers reorder every month.' },
    ],
    categoriesTitle: 'Shop categories',
    benefitsTitle: 'Why order from Boxes',
    benefits: [
        { icon: 'clock', title: 'Next-working-day delivery', text: 'Order by 2pm on a working day and mainland UK orders arrive tomorrow.' },
        { icon: 'truck', title: 'Free delivery over £150', text: 'Standard delivery is £7.95 ex VAT on smaller orders.' },
        { icon: 'user', title: 'Trade accounts', text: '30-day terms for approved businesses, with one person to call.' },
        { icon: 'leaf', title: 'Recycled and recyclable', text: 'Most of the range has recycled content and goes in kerbside recycling.' },
    ],
    bestSellersTitle: 'Best sellers',
    bestSellersLink: 'See all best sellers',
    sustainability: {
        title: 'Less plastic, less air, fewer vans',
        text: ['Most of what we sell is paper and board, much of it with recycled content, and we label clearly how each item can be recycled. Where plastic is still the right tool, we stock versions made with recycled material. We also help you choose box sizes that waste less space, and we take back pallets and clean cardboard from local trade customers.'],
        cta: 'Read about our approach to sustainability',
        alt: 'A stack of flattened cardboard bundled with paper string beside a wooden pallet',
    },
    journalTitle: 'From the journal',
    journalLink: 'All journal articles',
};

const fallbackArticles = [
    { path: '/journal/packing-bench-setup/', title: 'Setting up a packing bench that saves you an hour a day', article: { date: '2026-09-17', author: 'Priya Natarajan', excerpt: 'A well-arranged packing bench turns twenty fiddly minutes into five. How to lay it out, what to keep within reach and how to batch your orders.', image: { src: '/assets/img/products/kraft-paper-tape-800.webp' } } },
    { path: '/journal/food-packaging-rules/', title: 'Food packaging for cafés: the rules in plain English', article: { date: '2026-08-27', author: 'Priya Natarajan', excerpt: 'What a small café, bakery or takeaway needs to know about food-contact materials, single-use plastics, allergen labels and recycling, without the legal language.', image: { src: '/assets/img/products/kraft-food-box-lidded-800.webp' } } },
    { path: '/journal/plastic-free-mailing/', title: 'Going plastic-free with your mailing: what actually works', article: { date: '2026-08-06', author: 'Owen Marsh', excerpt: 'Paper can replace most of the plastic in an online order, but not all of it. What to swap, what to keep and what to tell customers.', image: { src: '/assets/img/products/honeycomb-paper-mailer-800.webp' } } },
];

const heroSources = (slide) => {
    const set = (format) => [960, 1440, 1920].map((width) => `/assets/img/hero/slide-${slide}-${width}.${format} ${width}w`).join(', ');

    return { avif: set('avif'), webp: set('webp'), jpg: set('jpg') };
};

const heroSizes = '(min-width: 77.5rem) 1240px, 100vw';

const heroSlide = (slide, index) => {
    const number = index + 1;
    const sources = heroSources(number);
    const loading = index === 0 ? 'fetchpriority="high"' : 'loading="lazy"';

    return `<li class="hero-carousel__slide" id="slide-${number}" data-carousel-slide>
            <picture class="hero-carousel__picture">
                <source type="image/avif" srcset="${sources.avif}" sizes="${heroSizes}">
                <source type="image/webp" srcset="${sources.webp}" sizes="${heroSizes}">
                <img class="hero-carousel__image" src="/assets/img/hero/slide-${number}-1440.jpg" srcset="${sources.jpg}" sizes="${heroSizes}" width="1920" height="800" ${loading} alt="${esc(slide.alt)}">
            </picture>
            <div class="hero-carousel__content">
                <h2 class="hero-carousel__title">${slide.title}</h2>
                <p class="hero-carousel__lead">${slide.lead}</p>
                <a class="button button--action" href="${slide.href}">${slide.cta}</a>
            </div>
        </li>`;
};

const heroCarousel = () => `<section class="hero-carousel" aria-label="Featured ranges" data-carousel>
    <div class="hero-carousel__inner container">
        <ul class="hero-carousel__track" data-carousel-track>
        ${copy.slides.map(heroSlide).join('\n        ')}
        </ul>
        <div class="hero-carousel__controls">
            <button class="hero-carousel__arrow" type="button" data-carousel-previous hidden>${icon('chevron-left')}<span class="visually-hidden">Previous slide</span></button>
            <ol class="hero-carousel__dots">
                ${copy.slides.map((slide, index) => `<li><a class="hero-carousel__dot" href="#slide-${index + 1}" data-carousel-dot><span class="visually-hidden">Show slide ${index + 1} of ${copy.slides.length}</span></a></li>`).join('\n                ')}
            </ol>
            <button class="hero-carousel__arrow" type="button" data-carousel-next hidden>${icon('chevron-right')}<span class="visually-hidden">Next slide</span></button>
        </div>
    </div>
</section>`;

const promoTiles = () => `<section class="section section--tight" aria-label="Collections">
    <ul class="promo-tiles container">
        ${copy.promos.map((promo) => `<li>
            <a class="promo-tile" href="/collections/${promo.slug}/">
                <img class="promo-tile__image" src="/assets/img/promo/${promo.slug}.webp" width="1200" height="600" loading="lazy" alt="">
                <span class="promo-tile__text">
                    <span class="promo-tile__label">${promo.label}</span>
                    <span class="promo-tile__note">${promo.note}</span>
                </span>
                ${icon('chevron-right', 'promo-tile__icon')}
            </a>
        </li>`).join('\n        ')}
    </ul>
</section>`;

const categoriesSection = (site) => `<section class="section section--tight" aria-labelledby="categories-title">
    <div class="container">
        <div class="section-head">
            <h2 class="section-head__title" id="categories-title">${copy.categoriesTitle}</h2>
        </div>
        ${categoryTiles(site)}
    </div>
</section>`;

const benefits = () => `<section class="benefits" aria-labelledby="benefits-title">
    <div class="container">
        <h2 class="visually-hidden" id="benefits-title">${copy.benefitsTitle}</h2>
        <ul class="benefits__list">
            ${copy.benefits.map((benefit) => `<li class="benefits__item">
                <span class="benefits__icon">${icon(benefit.icon)}</span>
                <span class="benefits__text"><strong class="benefits__title">${benefit.title}</strong> ${benefit.text}</span>
            </li>`).join('\n            ')}
        </ul>
    </div>
</section>`;

const bestSellers = (site) => {
    const collection = site.collectionBySlug.get('best-sellers');
    const products = (collection?.products ?? []).map((slug) => site.productBySlug.get(slug)).filter(Boolean).slice(0, 4);

    return `<section class="section" aria-labelledby="best-sellers-title">
    <div class="container">
        <div class="section-head">
            <h2 class="section-head__title" id="best-sellers-title">${copy.bestSellersTitle}</h2>
            <a class="text-link" href="/collections/best-sellers/">${copy.bestSellersLink}${icon('chevron-right')}</a>
        </div>
        ${productGrid(products, { level: 3, wide: true, idPrefix: 'home' })}
    </div>
</section>`;
};

const sustainability = () => `<section class="section band--surface" aria-labelledby="sustainability-title">
    <div class="split container">
        <img class="split__image" src="/assets/img/pages/sustainability.webp" width="1600" height="900" loading="lazy" alt="${esc(copy.sustainability.alt)}">
        <div class="split__text">
            <h2 class="split__title" id="sustainability-title">${copy.sustainability.title}</h2>
            <div class="prose">
                ${copy.sustainability.text.map((paragraph) => `<p>${paragraph}</p>`).join('\n                ')}
            </div>
            <a class="button button--outline" href="/sustainability/">${copy.sustainability.cta}</a>
        </div>
    </div>
</section>`;

const journal = (site) => {
    const articles = articlePages(site);
    const latest = (articles.length > 0 ? articles : fallbackArticles).slice(0, 3);

    return `<section class="section" aria-labelledby="journal-title">
    <div class="container">
        <div class="section-head">
            <h2 class="section-head__title" id="journal-title">${copy.journalTitle}</h2>
            <a class="text-link" href="/journal/">${copy.journalLink}${icon('chevron-right')}</a>
        </div>
        <ul class="article-grid">
            ${latest.map((page) => `<li>${articleCard(page, { level: 3 })}</li>`).join('\n            ')}
        </ul>
    </div>
</section>`;
};

export default {
    path: '/',
    documentTitle: copy.documentTitle,
    title: copy.title,
    description: copy.description,
    searchable: false,
    body: (site) => `<h1 class="visually-hidden">${esc(copy.heading)}</h1>
${heroCarousel()}
${promoTiles()}
${categoriesSection(site)}
${benefits()}
${bestSellers(site)}
${sustainability()}
${journal(site)}`,
};
