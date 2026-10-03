import { company, currentAttr, esc, phoneText } from './html.mjs';

const helpLinks = [
    ['/delivery/', 'Delivery &amp; returns'],
    ['/trade-accounts/', 'Trade accounts'],
    ['/price-list/', 'Price list'],
    ['/contact/', 'Contact us'],
];

const companyLinks = [
    ['/about/', 'About Boxes'],
    ['/sustainability/', 'Sustainability'],
    ['/journal/', 'Journal'],
    ['/custom-printing/', 'Custom printing'],
    ['/bespoke-boxes/', 'Bespoke boxes'],
];

const legalLinks = [
    ['/privacy/', 'Privacy'],
    ['/terms/', 'Terms of sale'],
    ['/accessibility/', 'Accessibility'],
];

const linkList = (links, page, className = 'site-footer__list') => `<ul class="${className}">
                ${links.map(([href, label]) => `<li><a class="site-footer__link" href="${href}"${currentAttr(href, page)}>${label}</a></li>`).join('\n                ')}
            </ul>`;

export const footer = (page, site) => `<footer class="site-footer">
    <div class="site-footer__inner container">
        <nav class="site-footer__nav" aria-label="Footer">
            <div class="site-footer__column">
                <h2 class="site-footer__heading">Shop</h2>
                ${linkList(site.categories.map((category) => [`/shop/${category.slug}/`, esc(category.name)]), page)}
            </div>
            <div class="site-footer__column">
                <h2 class="site-footer__heading">Help</h2>
                ${linkList(helpLinks, page)}
            </div>
            <div class="site-footer__column">
                <h2 class="site-footer__heading">Company</h2>
                ${linkList(companyLinks, page)}
            </div>
        </nav>
        <div class="site-footer__column site-footer__contact">
            <h2 class="site-footer__heading">Contact</h2>
            <address class="site-footer__address">${company.legalName}<br>${company.address.join('<br>')}</address>
            <p><a class="site-footer__link" href="${company.phoneHref}"><span class="visually-hidden">Telephone </span>${phoneText}</a><br><a class="site-footer__link" href="mailto:${company.email}"><span class="visually-hidden">Email </span>${company.email}</a></p>
            <p>${company.hours}</p>
        </div>
        <div class="site-footer__legal">
            <p>&copy; ${new Date().getFullYear()} ${company.legalName}. Registered in England and Wales, company no. ${company.companyNumber}. VAT ${company.vatNumber}.</p>
            <nav aria-label="Legal">
                ${linkList(legalLinks, page, 'site-footer__legal-list')}
            </nav>
            <p class="site-footer__notice">Boxes is a fictional company created for this demonstration website. Products, prices and stock levels are illustrative; no orders are taken or fulfilled.</p>
        </div>
    </div>
</footer>`;
