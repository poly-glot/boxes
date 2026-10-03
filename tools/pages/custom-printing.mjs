import { errors, field, formPanel, pageHero, steps } from '../partials/components.mjs';

const surfaces = [
    ['Paper bags and carriers:', 'counter bags, grocery bags and twisted or flat-handle carriers, printed in one or two colours on one or both sides.'],
    ['Mailer boxes:', 'crash-lock mailers printed on the outside in one colour, or inside and out in up to two colours.'],
    ['Packing tape:', 'kraft paper tape printed with a repeating design in one colour.'],
    ['Labels and stickers:', 'round and rectangular labels in full colour, on kraft or white paper.'],
    ['Cotton totes:', 'printed on one side in up to two colours.'],
];

const minimums = [
    ['Paper counter bags', '2,000', '10–15 working days'],
    ['Paper carriers', '500', '15–20 working days'],
    ['Mailer boxes', '250', '15–20 working days'],
    ['Kraft paper tape', '36 rolls', '10–15 working days'],
    ['Labels and stickers', '1,000', '5–7 working days'],
    ['Cotton totes', '100', '10–15 working days'],
];

const artwork = [
    'Send vector artwork as a PDF, SVG or EPS file, with all text converted to outlines.',
    'For one- and two-colour printing, tell us the colour references you want matched. We will show you how they look on kraft and on white, because the same ink prints darker on brown paper.',
    'For full-colour labels, send a PDF at 300 dpi at actual size, with 3 mm bleed on every edge.',
    'Keep fine lines at 0.5 pt or thicker and text at 6 pt or larger, especially on corrugated board.',
    'Not sure your file is ready? Send what you have. We will check it and tell you what needs to change before you pay anything.',
];

const processSteps = [
    ['Enquire.', 'Tell us what you would like printed, the quantity and when you need it.'],
    ['Quote and proof.', 'We send a fixed quote and a digital proof showing your design on the product, usually within 2 working days.'],
    ['Approve.', 'You approve the proof and pay a 50% deposit, or confirm the order on your trade account.'],
    ['Print and deliver.', 'We print, check a sample from the run and dispatch within the lead time above.'],
];

const enquiry = formPanel({
    prefix: 'printing',
    title: 'Ask about custom printing',
    submit: 'Send enquiry',
    confirmation: 'Thank you. This is a demonstration website, so your enquiry has not been sent. In a real shop, we would send a quote and proof within 2 working days.',
    fields: [
        field({ id: 'printing-name', label: 'Your name', required: true, autocomplete: 'name', error: 'Enter your name' }),
        field({ id: 'printing-company', label: 'Company name', required: true, autocomplete: 'organization', error: 'Enter your company name' }),
        field({ id: 'printing-email', label: 'Email address', type: 'email', required: true, autocomplete: 'email', error: errors.email }),
        field({ id: 'printing-phone', label: 'Phone number', type: 'tel', autocomplete: 'tel' }),
        field({
            id: 'printing-product',
            label: 'What would you like printed?',
            type: 'select',
            required: true,
            error: 'Choose what you would like printed',
            options: ['Paper bags', 'Paper carriers', 'Mailer boxes', 'Packing tape', 'Labels and stickers', 'Cotton totes', 'Not sure yet'],
        }),
        field({ id: 'printing-quantity', label: 'Quantity', type: 'number', required: true, hint: 'An estimate is fine.', error: 'Enter your quantity', attributes: ' min="1" step="1" inputmode="numeric"' }),
        field({ id: 'printing-colours', label: 'Number of print colours', type: 'select', options: ['One', 'Two', 'Full colour', 'Not sure'] }),
        field({ id: 'printing-date', label: 'When do you need it?', type: 'date' }),
        field({ id: 'printing-design', label: 'Tell us about your design', type: 'textarea', hint: 'Describe the design, or tell us if you need help with artwork.' }),
        field({ id: 'printing-artwork', label: 'Artwork file', type: 'file', full: true, hint: 'PDF, SVG or EPS, up to 10 MB. In this demonstration, files are not uploaded.', attributes: ' accept=".pdf,.svg,.eps"' }),
    ],
});

export default {
    path: '/custom-printing/',
    title: 'Custom printing',
    description: 'Custom-printed paper bags, carriers, mailer boxes, tape, labels and totes, from 100 units. Artwork guidance, lead times and a free digital proof.',
    crumbs: [{ label: 'Custom printing' }],
    body: `${pageHero({
        title: 'Custom printing',
        lead: 'Your logo or pattern on bags, boxes, tape and labels, printed in runs that suit a small business.',
        image: { src: '/assets/img/pages/custom-printing.webp', alt: 'A small printing press printing a simple stripe pattern onto brown kraft paper bags' },
    })}
<section class="section" aria-labelledby="surfaces-title">
    <div class="container">
        <div class="prose">
            <h2 id="surfaces-title">What we print on</h2>
            <ul>
                ${surfaces.map(([label, text]) => `<li><strong>${label}</strong> ${text}</li>`).join('\n            ')}
            </ul>
        </div>
    </div>
</section>
<section class="section band--surface" aria-labelledby="minimums-title">
    <div class="container stack">
        <h2 id="minimums-title">Minimum quantities</h2>
        <section class="table-scroll" aria-labelledby="minimums-caption" tabindex="0">
            <table class="data-table">
                <caption class="visually-hidden" id="minimums-caption">Minimum order quantities and lead times for custom printing</caption>
                <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Minimum order</th>
                        <th scope="col">Lead time after artwork approval</th>
                    </tr>
                </thead>
                <tbody>
                    ${minimums.map(([product, minimum, lead]) => `<tr><th scope="row">${product}</th><td>${minimum}</td><td>${lead}</td></tr>`).join('\n                    ')}
                </tbody>
            </table>
        </section>
        <p class="prose">Lead times are from the day you approve your proof, and the delivery rules on our <a href="/delivery/">delivery page</a> apply once your order is dispatched. Custom-printed items cannot be returned unless they are faulty.</p>
    </div>
</section>
<div class="section container">
    <div class="grid-2">
        <section class="prose" aria-labelledby="artwork-title">
            <h2 id="artwork-title">Artwork guidance</h2>
            <ul>
                ${artwork.map((item) => `<li>${item}</li>`).join('\n                ')}
            </ul>
        </section>
        <section class="stack" aria-labelledby="process-title">
            <h2 id="process-title">How it works</h2>
            ${steps(processSteps)}
        </section>
    </div>
</div>
<div class="container section">
    ${enquiry}
</div>`,
};
