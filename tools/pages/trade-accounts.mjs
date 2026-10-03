import { icon } from '../partials/html.mjs';
import { consent, errors, field, formPanel, pageHero } from '../partials/components.mjs';

const intro = [
    ['who-title', 'Who can apply', 'Trade accounts are for UK businesses, charities, schools and public bodies that buy packaging regularly. You do not need to spend a minimum amount, but we usually open accounts for businesses that expect to order at least once a month. Sole traders are welcome.'],
    ['terms-title', 'How 30-day terms work', 'Approved accounts can pay by invoice 30 days from the end of the month in which goods are dispatched. Terms are subject to approval: we carry out a credit check when you apply, and we may start with a lower credit limit and review it after three months. You can still pay by card at any time.'],
    ['manager-title', 'Your account manager', 'Every trade account has a named account manager in our customer team. They will learn what you usually order, remind you when you are running low if you would like them to, and help with sizing, samples and custom printing. You will have their direct line and email.'],
];

const benefits = [
    '30-day payment terms, subject to approval',
    'A named account manager with a direct line',
    'Bulk discounts applied to every order, as shown on product pages',
    'Monthly statements and invoices sent to your accounts team',
    'Purchase order references on every invoice',
    'Repeat orders by phone or email in a single message',
    'Free take-back of clean cardboard and pallets within 25 miles of Northampton',
    'Early notice of price changes and new ranges',
];

const steps = [
    'Send the application form below.',
    'We run a credit check and reply within 2 working days.',
    'Your account manager calls to introduce themselves and set up your account.',
    'You place your first order on account.',
];

const application = formPanel({
    prefix: 'trade',
    title: 'Apply for a trade account',
    submit: 'Send application',
    confirmation: 'Thank you. This is a demonstration website, so your application has not been sent. In a real shop, we would reply within 2 working days.',
    fields: [
        field({ id: 'trade-name', label: 'Your name', required: true, autocomplete: 'name', error: 'Enter your name' }),
        field({ id: 'trade-job', label: 'Job title', autocomplete: 'organization-title' }),
        field({ id: 'trade-company', label: 'Company name', required: true, autocomplete: 'organization', hint: 'As registered, if you are a limited company.', error: 'Enter your company name' }),
        field({
            id: 'trade-type',
            label: 'Business type',
            type: 'select',
            required: true,
            error: 'Choose a business type',
            options: ['Online seller', 'Café, bakery or takeaway', 'Shop or gift shop', 'Maker or studio', 'Office or business', 'Charity, school or public body', 'Other'],
        }),
        field({ id: 'trade-number', label: 'Company number', autocomplete: 'off', hint: 'Leave blank if you are a sole trader or partnership.' }),
        field({ id: 'trade-vat', label: 'VAT number', autocomplete: 'off' }),
        field({ id: 'trade-email', label: 'Email address', type: 'email', required: true, autocomplete: 'email', hint: 'We send your account details here.', error: errors.email }),
        field({ id: 'trade-phone', label: 'Phone number', type: 'tel', required: true, autocomplete: 'tel', error: errors.phone }),
        field({ id: 'trade-postcode', label: 'Business postcode', required: true, autocomplete: 'postal-code', hint: 'UK postcodes only, for example NN4 0ZZ.', error: errors.postcode, attributes: ' spellcheck="false"' }),
        field({ id: 'trade-accounts', label: 'Accounts email address', type: 'email', autocomplete: 'off', hint: 'If invoices and statements should go to someone else.', error: errors.email }),
        field({
            id: 'trade-spend',
            label: 'Expected monthly spend',
            type: 'select',
            required: true,
            error: 'Choose your expected monthly spend',
            options: ['Under £250', '£250 to £1,000', '£1,000 to £5,000', 'Over £5,000'],
        }),
        field({ id: 'trade-usual', label: 'What do you usually buy?', type: 'textarea', hint: 'A rough list is fine. It helps your account manager prepare.' }),
        consent({ id: 'trade-consent', label: 'I agree that Boxes may carry out a credit check on my business.', error: 'Tick the box to confirm you agree to a credit check' }),
    ],
});

export default {
    path: '/trade-accounts/',
    title: 'Trade accounts',
    description: 'Apply for a Boxes trade account: 30-day terms subject to approval, a named account manager, bulk discounts and monthly statements.',
    crumbs: [{ label: 'Trade accounts' }],
    body: `${pageHero({
        title: 'Trade accounts',
        lead: 'Buy on 30-day terms, with one person who knows your business and your usual order.',
        image: { src: '/assets/img/pages/trade-accounts.webp', alt: 'A shop owner unpacking brown cartons of stock onto the shelves of a small stockroom' },
    })}
<div class="section container">
    <div class="grid-3">
        ${intro.map(([id, title, text]) => `<section class="prose" aria-labelledby="${id}">
            <h2 id="${id}">${title}</h2>
            <p>${text}</p>
        </section>`).join('\n        ')}
    </div>
</div>
<section class="benefits band--surface" aria-labelledby="benefits-title">
    <div class="container stack">
        <h2 id="benefits-title">Benefits</h2>
        <ul class="benefits__list">
            ${benefits.map((benefit) => `<li class="benefits__item">
                <span class="benefits__icon">${icon('check')}</span>
                <span class="benefits__text">${benefit}</span>
            </li>`).join('\n            ')}
        </ul>
    </div>
</section>
<section class="section" aria-labelledby="next-title">
    <div class="container">
        <div class="prose">
            <h2 id="next-title">What happens next</h2>
            <ol>
                ${steps.map((step) => `<li>${step}</li>`).join('\n            ')}
            </ol>
        </div>
    </div>
</section>
<div class="container section">
    ${application}
</div>`,
};
