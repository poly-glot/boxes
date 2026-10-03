import { icon } from '../partials/html.mjs';
import { pageHero, phoneLink } from '../partials/components.mjs';

const values = [
    ['tag', 'Straight prices.', 'Every price is per pack and ex VAT, with bulk discounts shown on the product page rather than hidden behind a quote.'],
    ['check', 'Practical advice.', 'We would rather sell you a smaller box that fits than a bigger one that needs a bag of void fill.'],
    ['leaf', 'Less waste.', 'Most of our range is paper and board, much of it with recycled content, and we are honest about where plastic still makes sense.'],
    ['truck', 'Keeping our word.', 'If we say it will be dispatched today, it will be. If something goes wrong, we will tell you before you have to ask.'],
];

const value = ([symbol, title, text]) => `<li class="benefits__item">
                <span class="benefits__icon">${icon(symbol)}</span>
                <span class="benefits__text"><strong class="benefits__title">${title}</strong> ${text}</span>
            </li>`;

export default {
    path: '/about/',
    title: 'About us',
    description: 'Boxes is a family-run packaging supplier in Northampton, founded in 2009. A team of 24 selling plain, practical packaging in packs to small businesses.',
    crumbs: [{ label: 'About Boxes' }],
    body: `${pageHero({
        title: 'About Boxes',
        lead: 'We are a family-run packaging supplier in Northampton. Since 2009 we have sold boxes, bags and tape in sensible pack sizes to the small businesses that keep high streets and online shops going.',
        image: { src: '/assets/img/pages/about.webp', alt: 'An orderly packaging warehouse with racks of flat-packed cartons, where two members of staff check an order together at a packing bench' },
    })}
<section class="section" aria-label="Our story">
    <div class="container">
        <div class="prose">
            <h2>Where we started</h2>
            <p>Boxes began in 2009 in a rented lock-up on the edge of Northampton, with one van, a second-hand tape gun and a list of a dozen local shops. The founders had spent years buying packaging for a small mail-order business and were tired of the same problem: the big suppliers wanted orders by the pallet, and the small ones charged as if every box were a gift.</p>
            <p>The idea was simple. Sell good, plain packaging in packs a small business can actually use, show the price for every quantity up front, and answer the phone. Seventeen years on, that is still most of what we do.</p>
            <h2>The warehouse</h2>
            <p>In 2016 we moved to Unit 4 at Brookfield Trade Park, a 2,800 square metre warehouse with room for around 1,400 pallet spaces. It sits a few minutes from the M1, which is why orders placed before 2pm can leave the same afternoon and reach most of mainland UK the next working day.</p>
            <p>Everything we sell is picked and packed on site. Our packing team uses the same boxes, mailers and paper tape you can buy from us, so if something does not work well, we tend to find out first.</p>
            <h2>The team</h2>
            <p>There are 24 of us now. Most of the team live within a few miles of the trade park, and several have been here more than ten years. You will speak to the same customer team whether you buy one pack of tape a month or a lorry-load of cartons a week, and trade customers have a named account manager.</p>
        </div>
    </div>
</section>
<section class="benefits band--surface" aria-labelledby="values-title">
    <div class="container stack">
        <h2 id="values-title">What we care about</h2>
        <ul class="benefits__list">
            ${values.map(value).join('\n            ')}
        </ul>
    </div>
</section>
<section class="section" aria-labelledby="visit-title">
    <div class="container">
        <div class="notice">
            <h2 class="notice__title" id="visit-title">Come and see us</h2>
            <p>Trade customers are welcome to visit the warehouse to see samples or talk through a bespoke box. Call ahead on ${phoneLink} and we will put the kettle on.</p>
        </div>
    </div>
</section>`,
};
