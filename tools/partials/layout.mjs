import { esc, icon } from './html.mjs';
import { sprite } from './sprite.mjs';
import { header } from './header.mjs';
import { footer } from './footer.mjs';

const scripts = ['navigation', 'carousel', 'basket', 'search'];
const fonts = 'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;1,400&amp;display=swap';

const head = (page, css) => `<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>${esc(page.documentTitle ?? `${page.title} | Boxes`)}</title>
    <meta name="description" content="${esc(page.description)}">
    <link rel="icon" href="/assets/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="${fonts}">
    ${css.map((href) => `<link rel="stylesheet" href="${href}">`).join('\n    ')}
    ${scripts.map((name) => `<script type="module" src="/assets/js/${name}.js"></script>`).join('\n    ')}
</head>`;

const breadcrumb = (crumbs) => {
    if (!crumbs?.length) {
        return '';
    }

    const items = crumbs.map(({ label, href }, index) => {
        const isLast = index === crumbs.length - 1;

        return isLast
            ? `<li class="breadcrumb__item"><span class="breadcrumb__current" aria-current="page">${esc(label)}</span></li>`
            : `<li class="breadcrumb__item"><a class="breadcrumb__link" href="${href}">${esc(label)}</a></li>`;
    });

    return `<nav class="breadcrumb container" aria-label="Breadcrumb">
    <ol class="breadcrumb__list">
        <li class="breadcrumb__item"><a class="breadcrumb__link breadcrumb__link--home" href="/">${icon('home')}<span class="visually-hidden">Home</span></a></li>
        ${items.join('\n        ')}
    </ol>
</nav>`;
};

const withoutBlankLines = (html) => html.replace(/\n\s*\n/g, '\n');

export const renderDocument = (page, { css, site }) => withoutBlankLines(`<!DOCTYPE html>
<html lang="en-GB">
${head(page, css)}
<body>
<a class="skip-link" href="#main">Skip to main content</a>
${sprite()}
${header(page, site)}
<main class="site-main" id="main" tabindex="-1">
${breadcrumb(page.crumbs)}
${page.body}
</main>
${footer(page, site)}
</body>
</html>
`);
