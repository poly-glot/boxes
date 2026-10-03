import { company, phoneText } from '../partials/html.mjs';
import { demoNote, errors, field, honeypot } from '../partials/components.mjs';

const orderFields = [
    { id: 'order-name', label: 'Your name', required: true, autocomplete: 'name', error: 'Enter your name' },
    { id: 'order-company', label: 'Company name', required: true, autocomplete: 'organization', error: 'Enter your company name' },
    { id: 'order-email', label: 'Email address', type: 'email', required: true, autocomplete: 'email', hint: 'We send your order confirmation here.', error: errors.email },
    { id: 'order-phone', label: 'Phone number', type: 'tel', required: true, autocomplete: 'tel', hint: 'For the driver if there is a problem with delivery.', error: errors.phone },
    { id: 'order-postcode', label: 'Delivery postcode', required: true, autocomplete: 'postal-code', hint: 'UK postcodes only, for example NN4 0ZZ.', error: errors.postcode, attributes: ' spellcheck="false"' },
    { id: 'order-reference', label: 'Purchase order reference', autocomplete: 'off', hint: 'If your accounts team needs one on the invoice.' },
    { id: 'order-notes', label: 'Notes', type: 'textarea', hint: 'Delivery instructions, opening times or anything else we should know.' },
];

export default {
    path: '/basket/',
    title: 'Your basket',
    description: 'Review the packs in your basket, see bulk discounts and delivery costs, and place a trade order with Boxes.',
    searchable: false,
    crumbs: [{ label: 'Basket' }],
    body: `<div class="basket container" data-basket>
    <h1 class="basket__title" id="basket-title" tabindex="-1">Your basket</h1>
    <p class="visually-hidden" role="status" data-basket-live></p>
    <div class="notice" data-basket-fallback>
        <h2 class="notice__title">Your basket needs JavaScript</h2>
        <p>The basket needs JavaScript to work in this demonstration. You can still browse every product and price, or call us on <a href="${company.phoneHref}">${phoneText}</a> or email <a href="mailto:${company.ordersEmail}">${company.ordersEmail}</a> to place a trade order.</p>
    </div>
    <div class="notice" data-basket-empty hidden>
        <h2 class="notice__title">Your basket is empty</h2>
        <p>Browse the shop to add packs to your basket. Bulk discounts are applied here automatically as you add more.</p>
        <a class="button" href="/shop/">Go to the shop</a>
    </div>
    <div class="basket__filled" data-basket-filled hidden>
        <div class="basket__lines">
            <table class="data-table basket-table">
                <caption class="visually-hidden">Products in your basket</caption>
                <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Price per pack</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Line total</th>
                        <th scope="col"><span class="visually-hidden">Remove item</span></th>
                    </tr>
                </thead>
                <tbody data-basket-lines></tbody>
            </table>
            <template data-basket-line>
                <tr class="basket-table__row">
                    <th class="basket-table__product" scope="row">
                        <span class="basket-table__item">
                            <img class="basket-table__image" src="/assets/img/products/single-wall-postal-carton-400.webp" width="400" height="400" alt="" data-line-image>
                            <span class="basket-table__details">
                                <a class="basket-table__name" href="/shop/" data-line-link>Product</a>
                                <span class="basket-table__meta" data-line-meta></span>
                            </span>
                        </span>
                    </th>
                    <td class="basket-table__price"><span data-line-price></span><span class="basket-table__per"> per pack</span><span class="basket-table__tier" data-line-tier hidden></span></td>
                    <td class="basket-table__quantity">
                        <label class="visually-hidden" for="basket-quantity" data-line-label>Quantity</label>
                        <input class="basket-table__input" id="basket-quantity" type="number" min="1" max="999" step="1" inputmode="numeric" data-line-quantity>
                    </td>
                    <td class="basket-table__total" data-line-total></td>
                    <td class="basket-table__remove">
                        <button class="basket-table__remove-button" type="button" data-line-remove>Remove<span class="visually-hidden" data-line-remove-name></span></button>
                    </td>
                </tr>
            </template>
        </div>
        <section class="basket-summary" aria-labelledby="summary-title">
            <h2 class="visually-hidden" id="summary-title">Order summary</h2>
            <dl class="basket-summary__totals">
                <div class="basket-summary__row"><dt>Subtotal <span class="basket-summary__tax">(ex VAT)</span></dt><dd data-basket-subtotal></dd></div>
                <div class="basket-summary__row"><dt>Delivery <span class="basket-summary__tax">(ex VAT)</span></dt><dd data-basket-delivery></dd></div>
                <div class="basket-summary__row"><dt>VAT at 20%</dt><dd data-basket-vat></dd></div>
                <div class="basket-summary__row basket-summary__row--total"><dt>Total <span class="basket-summary__tax">(inc VAT)</span></dt><dd data-basket-total></dd></div>
            </dl>
            <p class="basket-summary__note" data-basket-delivery-note></p>
            <p class="basket-summary__dispatch">Order before 2pm on a working day for same-day dispatch. Mainland UK next working day; Highlands, islands and Northern Ireland 2&ndash;3 working days.</p>
        </section>
        <section class="order-form" aria-labelledby="order-title">
            <h2 class="order-form__title" id="order-title">Place a trade order</h2>
            <p class="order-form__intro">Tell us where to send your order. We will confirm stock and delivery by email within one working hour during opening times.</p>
            <form class="form" action="#" method="post" data-demo-form="order-confirmation">
                <div class="form-grid">
                ${orderFields.map(field).join('\n                ')}
                </div>
                ${honeypot('order')}
                <div class="form__actions">
                    <button class="button button--action" type="submit">Place trade order</button>
                </div>
                <p class="form__note">${demoNote}</p>
            </form>
            <div class="notice notice--success" id="order-confirmation" tabindex="-1" hidden>
                <h3 class="notice__title">Thank you, this was a demonstration</h3>
                <p>This is a demonstration shop, so no order has been placed, no payment has been taken and nothing will be delivered. Your basket has been left as it was, so you can keep exploring. In a real shop, this is where you would see your order reference and an estimated delivery date.</p>
            </div>
        </section>
    </div>
</div>`,
};
