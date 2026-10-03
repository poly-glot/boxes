import { pageHero } from '../partials/components.mjs';

export default {
    path: '/terms/',
    title: 'Terms of sale',
    description: 'Example business-to-business terms of sale for Boxes, a fictional packaging supplier: prices, orders, payment, delivery, returns and liability.',
    crumbs: [{ label: 'Terms of sale' }],
    body: `${pageHero({ title: 'Terms of sale', plain: true })}
<div class="container section--tight section stack">
    <p class="meta">Last updated: <time datetime="2026-09-01">1 September 2026</time></p>
    <div class="notice">
        <h2 class="notice__title">This is a demonstration website</h2>
        <p>Boxes is a fictional company and this is a demonstration website. These terms are an example of how a UK business-to-business packaging supplier might set out its terms of sale. No contract is formed through this site, and no goods are sold or delivered.</p>
    </div>
    <div class="prose">
<h2>1. About these terms</h2>
<p>1.1 These terms apply to every sale of goods by Boxes Packaging Ltd, company no. 00000000, Unit 4, Brookfield Trade Park, Northampton NN4 0ZZ ("we", "us") to a business customer ("you").</p>
<p>1.2 We sell to businesses only. By placing an order you confirm that you are buying for the purposes of your trade, business or profession.</p>
<p>1.3 These terms override any terms in your purchase order or other documents, unless we agree otherwise in writing.</p>
<h2>2. Prices and VAT</h2>
<p>2.1 Prices are in pounds sterling, per pack, and exclude VAT, which is added at the rate in force on the date of dispatch.</p>
<p>2.2 Bulk discounts apply per product, according to the number of packs of that product on a single order.</p>
<p>2.3 Delivery is charged at £7.95 ex VAT per order and is free on orders over £150 ex VAT, calculated on the subtotal after discounts.</p>
<p>2.4 We may correct obvious pricing errors before we accept your order.</p>
<h2>3. Orders</h2>
<p>3.1 Your order is an offer to buy. A contract is formed when we send an order confirmation by email.</p>
<p>3.2 We may decline an order, for example if an item is out of stock. We will tell you promptly and refund any payment in full.</p>
<p>3.3 Custom-printed items and bespoke boxes are made to an approved proof or sample. It is your responsibility to check the proof carefully before you approve it.</p>
<h2>4. Payment</h2>
<p>4.1 Unless you have a trade account, payment is due in full by card when you place your order.</p>
<p>4.2 Trade account customers must pay invoices within 30 days of the end of the month in which goods are dispatched. Trade terms and credit limits are subject to approval and may be reviewed at any time.</p>
<p>4.3 We may charge interest on late payments under the Late Payment of Commercial Debts (Interest) Act 1998, and suspend deliveries on an account that is overdue.</p>
<h2>5. Delivery</h2>
<p>5.1 Orders placed before 2pm on a working day are dispatched the same working day. Mainland UK deliveries are made the next working day after dispatch; the Highlands, islands and Northern Ireland take 2–3 working days. Made-to-order items are dispatched within 5 working days.</p>
<p>5.2 Delivery times are estimates. We are not liable for delays caused by events outside our reasonable control.</p>
<p>5.3 Risk in the goods passes to you on delivery. Ownership passes to you when we have received payment in full.</p>
<h2>6. Damaged, missing and faulty goods</h2>
<p>6.1 You must tell us about damaged or missing goods within 2 working days of delivery, with photographs where possible.</p>
<p>6.2 If goods are faulty or do not match their description, we will replace them or refund their price, at your choice, and collect them at our cost.</p>
<h2>7. Returns</h2>
<p>7.1 You may return unopened packs in a resaleable condition within 30 days of delivery. We will refund or credit the price of the goods, less a collection charge of £7.95 ex VAT if the return is not due to our error.</p>
<p>7.2 Opened packs, made-to-order items, custom-printed items and bespoke boxes cannot be returned unless they are damaged or faulty.</p>
<h2>8. Liability</h2>
<p>8.1 Our total liability to you under any contract is limited to the price of the goods in that order.</p>
<p>8.2 We are not liable for loss of profit, loss of business or any indirect or consequential loss.</p>
<p>8.3 Nothing in these terms limits liability for death or personal injury caused by negligence, for fraud, or for anything else that cannot be limited by law.</p>
<h2>9. General</h2>
<p>9.1 If any part of these terms is found to be unenforceable, the rest remains in force.</p>
<p>9.2 These terms and any contract under them are governed by the law of England and Wales, and the courts of England and Wales have exclusive jurisdiction.</p>
    </div>
</div>`,
};
