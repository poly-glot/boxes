import { pageHero } from '../partials/components.mjs';

export default {
    path: '/accessibility/',
    title: 'Accessibility statement',
    description: 'How the Boxes website aims to meet WCAG 2.2 AA, how we test it, known limitations and how to contact us about accessibility.',
    crumbs: [{ label: 'Accessibility statement' }],
    body: `${pageHero({ title: 'Accessibility statement', plain: true })}
<div class="container section--tight section stack">
    <p class="meta">Last updated: <time datetime="2026-10-01">1 October 2026</time></p>
    <div class="prose">
<p>This statement covers the Boxes demonstration website at boxes.junaid.guru.</p>
<h2>Our aim</h2>
<p>We want everyone to be able to browse the range, compare prices and contact us, whatever device or assistive technology they use. The site is built to meet the Web Content Accessibility Guidelines (WCAG) 2.2 at level AA.</p>
<h2>What you should be able to do</h2>
<ul>
    <li>Navigate the whole site using a keyboard alone, with a visible focus indicator.</li>
    <li>Use the site with a screen reader, with headings, landmarks and labelled form fields.</li>
    <li>Zoom text up to 400% without content being lost or needing to scroll sideways.</li>
    <li>Understand stock levels without relying on colour, because every status has an icon and words.</li>
    <li>Read prices clearly: wherever we write "ex VAT", screen readers hear "excluding VAT".</li>
    <li>Use the home page carousel without anything moving on its own, and with reduced motion if you have asked your device for it.</li>
</ul>
<h2>How we test</h2>
<p>We check every page with automated tools against WCAG 2.2 AA, validate the HTML, and test by hand with a keyboard and a screen reader in a current desktop browser.</p>
<h2>Known limitations</h2>
<p>We are not aware of any parts of the site that fail WCAG 2.2 AA. The basket and the search page need JavaScript to work. Without JavaScript, every product, price and the full price list can still be read, and the basket page explains how to order by phone or email.</p>
<h2>Contact us about accessibility</h2>
<p>If you find something that is hard to use, or you need information in a different format, tell us and we will reply within 5 working days.</p>
<ul>
    <li>Email: hello@boxes.junaid.guru</li>
    <li>Phone: 01632 960 418, Monday to Friday, 8am to 5pm</li>
    <li>Post: Boxes Packaging Ltd, Unit 4, Brookfield Trade Park, Northampton NN4 0ZZ</li>
</ul>
    </div>
</div>`,
};
