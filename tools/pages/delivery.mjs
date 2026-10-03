import { company, icon } from '../partials/html.mjs';
import { emailLink, exVat, pageHero, phoneLink } from '../partials/components.mjs';

const orders = emailLink(company.ordersEmail);

const rules = [
    ['Dispatch:', 'orders placed before 2pm on a working day are dispatched the same working day. Orders placed after 2pm, or at a weekend or on a bank holiday, are dispatched the next working day.'],
    ['Standard delivery:', `£7.95 ${exVat} per order.`],
    ['Free delivery:', `on orders over £150 ${exVat}.`],
    ['Mainland UK:', 'delivered the next working day after dispatch.'],
    ['Highlands, islands and Northern Ireland:', 'delivered 2–3 working days after dispatch.'],
    ['Made-to-order items:', 'dispatched within 5 working days of your order. Anything else on the same order can be sent ahead at no extra charge if you ask.'],
    ['Large orders:', 'orders of a full pallet or more are delivered on a pallet service. We will call you to agree a delivery day.'],
];

const faqs = [
    ['When is the cut-off for same-day dispatch?', '2pm, Monday to Friday. Orders placed by then leave our warehouse the same day.'],
    ['Do you deliver on Saturdays?', 'Not as standard. If you need a Saturday delivery, call us before you order and we will check whether our courier partner can do it for your postcode, and what it costs.'],
    ['How is the free delivery threshold worked out?', `On the order subtotal excluding VAT, after any bulk discounts. If your subtotal is over £150 ${exVat}, delivery is free.`],
    ['Can I collect my order from the warehouse?', 'We do not run a collection counter, because every order goes through the same picking and checking process. Trade customers who need something urgently can call us and we will see what we can do.'],
    ['Do you deliver outside the UK?', 'Not at the moment. We deliver to addresses in Great Britain and Northern Ireland only.'],
    ['How will I know when my order has been dispatched?', 'We email a tracking link as soon as your parcel leaves us. If you have a trade account, your account manager can also send dispatch confirmations to a second email address, such as your accounts team.'],
    ['Can I change or cancel an order after placing it?', `Yes, if it has not yet been dispatched. Call us on ${phoneLink} as soon as you can. Once an order has been picked and packed, it is treated as a return.`],
    ['Can I return a pack I have opened?', 'Usually not, because we cannot sell an opened pack again. If an item is faulty, it does not matter whether the pack is open: tell us and we will put it right.'],
    ['How long do refunds take?', 'We process refunds within 5 working days of receiving your return. Card refunds can take a further 3–5 working days to appear, depending on your bank.'],
    ['Do you take back packaging?', 'Trade customers within 25 miles of Northampton can give our driver clean, flattened cardboard and wooden pallets when we deliver. See our <a href="/sustainability/">sustainability page</a> for details.'],
];

const faq = ([question, answer]) => `<details class="faq__item">
                <summary class="faq__question">${question}${icon('plus', 'faq__icon--open')}${icon('minus', 'faq__icon--close')}</summary>
                <div class="faq__answer prose"><p>${answer}</p></div>
            </details>`;

const listItems = (items) => items.map((item) => `<li>${item}</li>`).join('\n            ');

export default {
    path: '/delivery/',
    title: 'Delivery & returns',
    description: 'Same-day dispatch before 2pm, £7.95 ex VAT delivery, free over £150 ex VAT. Next working day to mainland UK. Returns on unopened packs within 30 days.',
    crumbs: [{ label: 'Delivery & returns' }],
    body: `${pageHero({
        title: 'Delivery &amp; returns',
        lead: `Clear rules, the same for every order. If anything here does not answer your question, call us on ${phoneLink}.`,
    })}
<section class="section" aria-label="Delivery, returns and damaged goods">
    <div class="container">
        <div class="prose">
            <h2>Delivery</h2>
            <ul>
                ${listItems(rules.map(([label, text]) => `<strong>${label}</strong> ${text}`))}
            </ul>
            <p>Every order is sent with a tracking link by email as soon as it leaves our warehouse.</p>
            <h2>Returns</h2>
            <p>You can return any unopened pack within 30 days of delivery for a refund or credit. Packs must be sealed, unused and in a condition we can sell again.</p>
            <ol>
                ${listItems([
                    `Email ${orders} with your order number and the items you would like to return.`,
                    'We will reply within one working day with a returns reference and a collection date.',
                    `Our courier partner collects the parcel. If the return is not because of our mistake, we deduct £7.95 ${exVat} for the collection.`,
                    'We refund to your original payment method, or credit your trade account, within 5 working days of receiving the goods.',
                ])}
            </ol>
            <p>We cannot accept returns of opened packs, made-to-order items, custom-printed items or bespoke boxes, unless they are damaged or faulty.</p>
            <h2>Damaged or missing goods</h2>
            <p>If something arrives damaged, or part of an order is missing, tell us within 2 working days of delivery.</p>
            <ol>
                ${listItems([
                    'Take a photo of the damaged item and the outer packaging before you throw anything away.',
                    `Email the photos to ${orders} with your order number, or call ${phoneLink}.`,
                    'We will send a replacement on our next dispatch, or refund the item if you prefer. You will not be charged for delivery or collection.',
                ])}
            </ol>
            <p>If a delivery arrives visibly damaged, you can sign for it as &ldquo;damaged&rdquo; or refuse it, and let us know the same day.</p>
        </div>
    </div>
</section>
<section class="section band--surface" aria-labelledby="faq-title">
    <div class="container stack">
        <h2 id="faq-title">Frequently asked questions</h2>
        <div class="faq">
            ${faqs.map(faq).join('\n            ')}
        </div>
    </div>
</section>`,
};
