import { choices, errors, exVat, field, formPanel, pageHero, steps } from '../partials/components.mjs';

const styles = [
    ['Regular slotted cartons:', 'the classic four-flap shipping box, quick to make and economical in board.'],
    ['Crash-lock and tuck-lid mailers:', 'fast to assemble and tidy to open, for subscription boxes and online orders.'],
    ['Die-cut boxes with inserts:', 'a box and cardboard fitment that hold a product still without void fill.'],
    ['Two-piece and telescopic boxes:', 'a base and a lid for long or awkward items, such as prints, tools or garden equipment.'],
];

const grades = [
    ['E-flute', '1.5 mm', 'Mailer boxes and retail boxes up to about 3 kg'],
    ['B-flute', '3 mm', 'Everyday postal cartons up to about 15 kg'],
    ['C-flute', '4 mm', 'Fragile items that need more cushioning'],
    ['BC double wall', '7 mm', 'Heavy loads up to about 30 kg and pallet stacking'],
    ['Greyboard (rigid)', '1–2 mm solid board', 'Presentation boxes that are kept, not posted'],
];

const processSteps = [
    ['Tell us what goes inside.', 'Send your product dimensions and weight, or post us one of your products.'],
    ['We design and quote.', 'We suggest a style, size and board grade, with a fixed price per box at two or three quantities.'],
    ['Sample and approve.', 'We make a sample. You test it and approve it, or we adjust and make another.'],
    ['Production.', 'Your boxes are made and dispatched within 15 working days of approval. We can hold stock for trade customers and send it out as you need it.'],
];

const millimetres = ' min="1" step="1" inputmode="numeric"';

const dimensions = `<fieldset class="fieldset form-grid__full" aria-describedby="bespoke-size-hint">
                <legend class="fieldset__legend">Internal length, width and height in mm <span class="field__marker">(optional)</span></legend>
                <p class="field__hint" id="bespoke-size-hint">Leave blank if you would like us to work it out.</p>
                <div class="form-grid form-grid--thirds">
                    ${[['length', 'Length'], ['width', 'Width'], ['height', 'Height']].map(([name, label]) => field({ id: `bespoke-${name}`, label: `${label} in mm`, type: 'number', attributes: millimetres })).join('\n                    ')}
                </div>
            </fieldset>`;

const enquiry = formPanel({
    prefix: 'bespoke',
    title: 'Ask about a bespoke box',
    submit: 'Send enquiry',
    confirmation: 'Thank you. This is a demonstration website, so your enquiry has not been sent. In a real shop, we would reply with questions or a quote within 2 working days.',
    fields: [
        field({ id: 'bespoke-name', label: 'Your name', required: true, autocomplete: 'name', error: 'Enter your name' }),
        field({ id: 'bespoke-company', label: 'Company name', required: true, autocomplete: 'organization', error: 'Enter your company name' }),
        field({ id: 'bespoke-email', label: 'Email address', type: 'email', required: true, autocomplete: 'email', error: errors.email }),
        field({ id: 'bespoke-phone', label: 'Phone number', type: 'tel', autocomplete: 'tel' }),
        field({ id: 'bespoke-contents', label: 'What will go in the box?', type: 'textarea', required: true, hint: 'For example, a 1 kg candle in a glass jar, or three paperback books.', error: 'Enter what will go in the box' }),
        dimensions,
        field({ id: 'bespoke-weight', label: 'Weight of contents in kg', type: 'number', attributes: ' min="0" step="0.1" inputmode="decimal"' }),
        field({ id: 'bespoke-style', label: 'Box style', type: 'select', options: ['Shipping carton', 'Mailer box', 'Box with insert', 'Two-piece box', 'Not sure'] }),
        field({
            id: 'bespoke-quantity',
            label: 'Quantity per order',
            type: 'select',
            required: true,
            error: 'Choose a quantity per order',
            options: ['250 to 499', '500 to 999', '1,000 to 4,999', '5,000 or more'],
        }),
        choices({ id: 'bespoke-printing', legend: 'Do you need printing?', options: ['No', 'Yes, one or two colours', 'Not sure yet'] }),
        field({ id: 'bespoke-notes', label: 'Anything else?', type: 'textarea' }),
    ],
});

export default {
    path: '/bespoke-boxes/',
    title: 'Bespoke boxes',
    description: 'Boxes made to your dimensions from 250 units: board grades explained, a free first sample in 5 working days and production within 15 working days.',
    crumbs: [{ label: 'Bespoke boxes' }],
    body: `${pageHero({
        title: 'Bespoke boxes',
        lead: 'When none of our stock sizes fits, we will make a box that does, from 250 boxes upwards.',
        image: { src: '/assets/img/pages/bespoke-boxes.webp', alt: 'Hands measuring a prototype corrugated box with a steel rule on a cutting table, beside a stack of flat box blanks' },
    })}
<section class="section" aria-labelledby="styles-title">
    <div class="container">
        <div class="prose">
            <h2 id="styles-title">Sizes and styles</h2>
            <p>We make boxes to your internal dimensions, from 100 × 100 × 50 mm up to 1,200 × 800 × 800 mm. The most common styles are:</p>
            <ul>
                ${styles.map(([label, text]) => `<li><strong>${label}</strong> ${text}</li>`).join('\n            ')}
            </ul>
        </div>
    </div>
</section>
<section class="section band--surface" aria-labelledby="grades-title">
    <div class="container stack">
        <h2 id="grades-title">Board grades explained</h2>
        <p class="prose">Corrugated board is described by the height of its flutes, the waves of paper between the two flat liners. Taller flutes cushion more; thinner ones fold neatly and print better.</p>
        <section class="table-scroll" aria-labelledby="grades-caption" tabindex="0">
            <table class="data-table">
                <caption class="visually-hidden" id="grades-caption">Corrugated and rigid board grades with thickness and typical use</caption>
                <thead>
                    <tr>
                        <th scope="col">Grade</th>
                        <th scope="col">Thickness</th>
                        <th scope="col">Good for</th>
                    </tr>
                </thead>
                <tbody>
                    ${grades.map(([grade, thickness, use]) => `<tr><th scope="row">${grade}</th><td>${thickness}</td><td>${use}</td></tr>`).join('\n                    ')}
                </tbody>
            </table>
        </section>
        <p class="prose">We can also tell you the burst and edge-crush strength of each grade if your customers or your courier ask for it.</p>
    </div>
</section>
<div class="section container">
    <div class="grid-2">
        <section class="prose" aria-labelledby="samples-title">
            <h2 id="samples-title">Samples</h2>
            <p>Before a production run, we make a plain, unprinted sample box to your dimensions in the board grade you have chosen. Your first sample is free and is dispatched within 5 working days; further samples are £15.00 ${exVat} each. Pack your product into it, post it to yourself, and tell us what you would change.</p>
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
