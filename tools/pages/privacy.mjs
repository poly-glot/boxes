import { pageHero } from '../partials/components.mjs';

export default {
    path: '/privacy/',
    title: 'Privacy notice',
    description: 'How Boxes would collect, use, share and keep personal data for orders, trade accounts and enquiries, and your rights. A demonstration privacy notice.',
    crumbs: [{ label: 'Privacy notice' }],
    body: `${pageHero({ title: 'Privacy notice', plain: true })}
<div class="container section--tight section stack">
    <p class="meta">Last updated: <time datetime="2026-09-01">1 September 2026</time></p>
    <div class="notice">
        <h2 class="notice__title">This is a demonstration website</h2>
        <p>Boxes is a fictional company and this is a demonstration website. The forms on this site do not send or store anything, and no one at Boxes will contact you. This notice shows how a real business of this kind would explain its use of personal data.</p>
    </div>
    <div class="prose">
<h2>Who we are</h2>
<p>Boxes Packaging Ltd ("Boxes", "we", "us") is the controller of personal data collected through this website. We are registered in England and Wales, company no. 00000000, at Unit 4, Brookfield Trade Park, Northampton NN4 0ZZ. You can contact us about privacy at hello@boxes.junaid.guru or on 01632 960 418.</p>
<h2>What we collect</h2>
<ul>
    <li><strong>Orders and trade accounts:</strong> your name, company, job title, email address, phone number, delivery and billing addresses, purchase order references and order history.</li>
    <li><strong>Enquiries:</strong> the details you give us in the contact, custom printing and bespoke box forms, and any files you attach.</li>
    <li><strong>Credit checks:</strong> for trade account applications, information about your business from a credit reference agency.</li>
    <li><strong>Your basket:</strong> the products and quantities in your basket are kept in your own browser's local storage, not sent to us, until you place an order.</li>
</ul>
<p>We do not collect special category data, and we do not knowingly collect data from children.</p>
<h2>Why we use it and our lawful basis</h2>
<ul>
    <li>To process and deliver orders, handle returns and manage trade accounts: <strong>performance of a contract</strong>.</li>
    <li>To keep accounting and tax records: <strong>legal obligation</strong>.</li>
    <li>To answer enquiries, carry out credit checks and prevent fraud: <strong>legitimate interests</strong> in running our business.</li>
    <li>To send occasional news about new ranges to trade customers: <strong>legitimate interests</strong>, with an unsubscribe link in every email. We never sell your details.</li>
</ul>
<h2>Who we share it with</h2>
<p>We share only what is needed with our courier partner (to deliver your order), our payment provider (to take payment), a credit reference agency (for trade applications), our accountants, and the companies that host our website and email. Each acts under a written contract. Data is stored in the UK or in countries with UK adequacy regulations, or protected by approved contract terms.</p>
<h2>How long we keep it</h2>
<p>Order and invoice records are kept for six years after the end of the financial year, as required for tax. Enquiries that do not lead to an order are deleted after 12 months. Trade account records are kept for six years after the account is closed.</p>
<h2>Cookies and similar technology</h2>
<p>This website does not set cookies. Your basket is saved in your browser's local storage under the name <code>boxes-basket</code>, and you can clear it at any time by emptying your basket or clearing your browser's site data. Fonts are loaded from Google Fonts, which receives your IP address when the page loads.</p>
<h2>Your rights</h2>
<p>You have the right to ask for a copy of your data, to have it corrected or deleted, to object to or restrict how we use it, and to ask for it to be transferred. Email hello@boxes.junaid.guru and we will reply within one month. If you are not happy with our response, you can complain to the Information Commissioner's Office at ico.org.uk.</p>
    </div>
</div>`,
};
