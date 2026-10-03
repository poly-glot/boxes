import { esc, formatDate } from '../partials/html.mjs';
import { articleCard, articlePages, pageHero } from '../partials/components.mjs';

const footerNote = 'This article is general guidance for small businesses. It is not legal advice, and products mentioned are part of a fictional demonstration shop.';

const articles = [
    {
        slug: 'choosing-the-right-box-size',
        title: 'How to choose the right box size (and stop paying to post air)',
        documentTitle: 'How to choose the right box size | Boxes',
        description: 'Measure your product, read box sizes correctly, understand volumetric weight and build a short list of box sizes that cuts postage and void fill.',
        author: 'Owen Marsh',
        role: 'Packaging technologist',
        date: '2026-07-14',
        minutes: 4,
        standfirst: 'Most parcels are bigger than they need to be. Here is how to measure, choose a short list of sizes and cut both postage and void fill.',
        image: { src: '/assets/img/products/single-wall-postal-carton-800.webp', alt: 'A brown cardboard carton with its flaps open, ready to be measured' },
        body: `<p>Pick up any parcel on a sorting belt and there is a good chance a third of it is empty space. That space costs money twice: once in the board and void fill it takes to fill it, and again in postage, because couriers increasingly charge by size as well as weight. Choosing the right box is one of the cheapest savings a small business can make.</p>
<h2>Measure the product, not the old box</h2>
<p>Start with the item itself, not the box you have been using. Measure its longest side, then the next longest, then the shortest, in millimetres. If it is irregular, such as a mug with a handle or a lamp with a shade, measure the smallest imaginary rectangular block it would fit inside.</p>
<p>Then decide how much protection it needs. A folded jumper needs almost none. A hardback book needs a few millimetres so the corners do not touch the walls. A glass jar wrapped in two layers of honeycomb paper needs around 20 to 30 mm on every side for the wrap itself. Add that allowance to each of your three measurements, and you have the internal size of the box you need.</p>
<h2>Read box sizes the right way</h2>
<p>Box sizes are almost always given as internal dimensions, in the order length × width × height. Length and width describe the opening, the face with the flaps. Height is the depth from the opening to the base.</p>
<p>That order matters. A 305 × 229 × 152 mm carton and a 152 × 229 × 305 mm carton hold the same volume, but the first opens wide and shallow, which is easy to pack, and the second opens small and deep, which suits bottles but not books. When you compare sizes from different suppliers, check that they are listing internal measurements, because board thickness can add 6 to 14 mm to the outside.</p>
<h2>Understand how size affects postage</h2>
<p>Most couriers set price bands by both weight and dimensions. Many also use volumetric weight: they multiply length by width by height in centimetres and divide by a set number, often 5,000. If the result is higher than the real weight, you pay for the higher one.</p>
<p>A 400 × 300 × 300 mm box has a volumetric weight of 7.2 kg on that formula, even if it holds a 2 kg cushion. Move the same cushion into a 350 × 250 × 150 mm box, compressed slightly, and the volumetric weight drops to 2.6 kg. For a light, bulky item, that can be the difference between one price band and the next.</p>
<p>The other number to know is the cut-off for your courier's smaller parcel band. Many sit somewhere around 450 × 350 × 160 mm. If your product can fit a box just inside that limit, it is usually worth designing your packing around it.</p>
<h2>Build a short list of sizes</h2>
<p>You do not need a box for every product. Most small businesses we work with find that three to five sizes cover nine orders out of ten. To find yours:</p>
<ol>
    <li>List your twenty most common orders, including multi-item ones.</li>
    <li>Work out the internal size each would need, using the method above.</li>
    <li>Group them. Orders within about 20 mm of each other in every dimension can share a box.</li>
    <li>Choose a standard size for each group, from stock where possible, because stock sizes are cheaper and always available.</li>
</ol>
<p>Keep one larger box for the occasional odd order, and accept that a few orders will need a little void fill. Paying for a sixth or seventh size to save a handful of paper at the edges is rarely worth it.</p>
<h2>Choose the board to match the load</h2>
<p>Size and strength go together. A crash-lock mailer in E-flute board is fine for a 1 kg candle but will bow under a 6 kg box of books. As a rough guide, single-wall cartons suit loads up to around 15 kg, double-wall cartons up to around 30 kg, and anything that will be stacked on a pallet or travel a long way benefits from double wall, whatever its weight.</p>
<p>If an item is fragile, a stronger box is often a better answer than more wrap. A box that holds its shape protects the contents from crushing; extra bubble wrap cannot.</p>
<h2>When a stock size is not enough</h2>
<p>Sometimes no stock size fits. If you send hundreds of the same product each month, a made-to-measure box with a simple cardboard insert can remove void fill completely and speed up packing, because the product drops into place. It usually needs a run of 250 or more to make sense, and the first sample tells you quickly whether it works.</p>
<h2>A five-minute check</h2>
<p>Next time you pack an order, close the lid and give the box a gentle shake. If you can hear or feel the item move, the box is too big or the wrap is too thin. If the flaps bulge, it is too small. When it sits still and the flaps close flat, you have the right size, and you have probably stopped paying to post air.</p>`,
    },
    {
        slug: 'plastic-free-mailing',
        title: 'Going plastic-free with your mailing: what actually works',
        documentTitle: 'Going plastic-free with your mailing | Boxes',
        description: 'What to swap, what to keep and what to tell customers: paper tape, void fill, paper mailers, honeycomb wrap, and what recyclable and compostable mean.',
        author: 'Owen Marsh',
        role: 'Packaging technologist',
        date: '2026-08-06',
        minutes: 5,
        standfirst: 'Paper can replace most of the plastic in an online order, but not all of it. What to swap, what to keep and what to tell customers.',
        image: { src: '/assets/img/products/honeycomb-paper-mailer-800.webp', alt: 'A brown paper mailer open to show its honeycomb paper lining' },
        body: `<p>Customers notice plastic in their parcels, and many now mention it in reviews. Most of what goes into a typical online order has a paper alternative that works just as well. A few swaps cost more, take longer to pack or protect less, and it is better to know that before you change everything at once.</p>
<h2>Start with the easy swaps</h2>
<p>Some changes cost almost nothing and your customers will not notice anything except the absence of plastic.</p>
<p><strong>Tape.</strong> Kraft paper tape seals a carton as well as plastic tape for most parcels, works in a standard tape gun and goes into recycling with the box. Plastic tape left on a carton is one of the most common contaminants in cardboard recycling, so this swap helps your customer as well as you.</p>
<p><strong>Void fill.</strong> Crumpled kraft paper, paper crinkle or honeycomb wrap all do the job of air pillows and polystyrene chips. They are heavier per box, so weigh a test parcel to check you are not pushed into a higher postage band.</p>
<p><strong>Labels and stickers.</strong> Kraft and uncoated paper labels can go into paper recycling. Thermal shipping labels cannot be recycled on their own, but on a cardboard box most schemes accept them, so they are not a reason to delay the rest.</p>
<h2>Mailers: the swaps that need a little thought</h2>
<p><strong>Padded envelopes.</strong> Paper-padded mailers, lined with recycled paper fibre or honeycomb paper, replace bubble-lined envelopes for books, accessories and small gifts. They protect well against knocks but less well against crushing, so add a piece of board for anything with a fragile edge.</p>
<p><strong>Clothing.</strong> Here paper is a real trade-off. A paper mailing bag looks good and recycles easily, but it can tear if it is overfilled and it does not keep out rain on a doorstep. If your customers are often out when parcels arrive, a recycled polythene bag may be the more sensible choice. Many sellers use paper for small, light items and keep polythene for bulky knitwear and coats.</p>
<p><strong>Fragile items.</strong> Honeycomb kraft wrap is now good enough for most glass and ceramics, provided you use two or three layers and a box with no more than 20 to 30 mm of space on each side. For very fragile or valuable items, bubble wrap still protects best, and recycled bubble wrap is a reasonable compromise.</p>
<h2>Read the claims carefully</h2>
<p>Packaging words are easy to misread, and a few are worth understanding before you put them on your website.</p>
<p><strong>Recyclable</strong> means the material can be recycled where collection exists. Paper and card are collected almost everywhere in the UK. Polythene bags and films are not usually collected at the kerbside, though most large supermarkets have collection points.</p>
<p><strong>Recycled content</strong> is the share of the material that came from recycled sources. It tells you about how the packaging was made, not how it will be disposed of.</p>
<p><strong>Compostable</strong> usually means industrially compostable, tested against a standard such as EN 13432, which sets out how quickly a material must break down in a commercial composting facility. It does not mean it will break down in a garden heap, and compostable mailers should never go in plastic or paper recycling.</p>
<p><strong>FSC</strong> refers to the Forest Stewardship Council, an organisation that certifies forests and the supply chains that use their wood. Paper or board sold as FSC-certified has been tracked from a certified source. If you want to use the FSC logo on your own packaging, you need your own chain-of-custody certification, so ask your supplier before you print it.</p>
<h2>Think about cost and time</h2>
<p>Paper alternatives are often a few pence more per parcel, and some take longer to use. Honeycomb wrap is quicker than bubble wrap once you are used to it; paper void fill is slower than air pillows unless you have a dispenser. Test a week of orders before switching completely, and time your packing so you know the real cost.</p>
<p>There is also a cost on the other side. In the UK, the Plastic Packaging Tax applies to manufacturers and importers of plastic packaging with less than 30% recycled content. You are unlikely to pay it directly as a small business, but it is built into the price of virgin plastic, and that gap is likely to stay.</p>
<h2>Tell your customers what to do</h2>
<p>Plastic-free packaging only works if it ends up in the right bin. One line on your packing slip or website is enough: "This box, tape and paper can all go in your recycling." If you still use a polythene bag, say where it can be recycled. Customers appreciate being told, and it makes the effort visible.</p>
<h2>A sensible order of changes</h2>
<p>If you are starting from a typical mix of plastic tape, air pillows and bubble mailers, this order spreads the cost and lets you learn as you go:</p>
<ol>
    <li>Switch to kraft paper tape.</li>
    <li>Replace air pillows or chips with paper void fill.</li>
    <li>Move small, sturdy items into paper-padded or honeycomb mailers.</li>
    <li>Trial honeycomb wrap for fragile items, keeping bubble wrap for the most delicate.</li>
    <li>Decide on clothing based on how often your parcels meet the weather.</li>
</ol>
<p>Most businesses can remove the majority of the plastic from their parcels in a month without any customer noticing a difference, except the one they were hoping for.</p>`,
    },
    {
        slug: 'food-packaging-rules',
        title: 'Food packaging for cafés: the rules in plain English',
        documentTitle: 'Food packaging rules for cafés, in plain English | Boxes',
        description: 'Food-contact rules, single-use plastic bans, paper cups, compostables, allergen labels and storing packaging: a plain-English guide for cafés and bakeries.',
        author: 'Priya Natarajan',
        role: 'Customer team lead',
        date: '2026-08-27',
        minutes: 5,
        standfirst: 'What a small café, bakery or takeaway needs to know about food-contact materials, single-use plastics, allergen labels and recycling, without the legal language.',
        image: { src: '/assets/img/products/kraft-food-box-lidded-800.webp', alt: 'Two brown kraft takeaway boxes, one closed and one open and empty' },
        body: `<p>Café owners often ask our customer team whether a cup, box or bag is "allowed". The rules are spread across several pieces of law and differ slightly between England, Scotland, Wales and Northern Ireland. This guide gathers the parts that matter most to a small food business. It is not legal advice; your local environmental health team is the place to check anything specific.</p>
<h2>Anything that touches food must be suitable for food</h2>
<p>The core rule is simple. Packaging that comes into contact with food must not pass anything into the food in amounts that could harm health, change what the food is, or spoil its taste or smell. This applies to cups, boxes, bags, wrapping paper, lids and cutlery alike.</p>
<p>In practice, buy packaging sold as suitable for food contact and use it for the food it was made for. A bag sold for dry bakery goods may not suit something hot and oily.</p>
<p>For plastic items, including the linings of paper cups, your supplier should be able to provide a declaration of compliance confirming the material meets the food-contact rules. If an environmental health officer asks, you should be able to show where your packaging came from, so keep invoices that name what you bought.</p>
<h2>Single-use plastic bans</h2>
<p>Since October 2023, England has banned the supply of several single-use plastic items, including plastic plates, cutlery and balloon sticks, and polystyrene food and drink containers and cups. Single-use plastic bowls and trays are also banned when they are used for food eaten on the premises or served to order, with some exceptions for pre-packed food. Scotland and Wales have similar bans, with a few differences in detail.</p>
<p>Earlier rules already restrict plastic straws, stirrers and cotton buds. Straws can still be supplied on request for people who need them for medical or accessibility reasons.</p>
<p>For most cafés, the practical result is that takeaway packaging is now paper, board, bagasse, or another non-plastic material, and that is what our food range is built around.</p>
<h2>Paper cups are allowed, but they need the right bin</h2>
<p>Paper hot cups are not banned. They have a thin plastic lining to hold liquid, which is why they cannot go in ordinary paper recycling. Many high streets, stations and larger retailers now have cup recycling points, and some councils accept cups in mixed recycling. If you sell a lot of takeaway drinks, it is worth offering a discount for reusable cups and a cup recycling bin by the door.</p>
<h2>Compostable packaging and your waste</h2>
<p>Bagasse clamshells, some cups and some cutlery are sold as compostable. Almost all of them need industrial composting, which happens at a commercial facility, not in a garden heap. They should go in food waste where your collection accepts them, never in paper or plastic recycling, where they contaminate the batch.</p>
<p>England's Simpler Recycling rules have required most businesses with ten or more full-time staff to separate food waste, paper and card, and other dry recycling since March 2025. Micro businesses with fewer than ten staff are due to follow from March 2027. If you are close to that size, now is a good time to talk to your waste collector about bins.</p>
<h2>Allergen labels on food you pack yourself</h2>
<p>If you make food and pack it on the premises before a customer chooses it, such as sandwiches in a chiller or boxed salads on the counter, it counts as prepacked for direct sale. Since October 2021, these items must carry a label with the name of the food and a full ingredients list, with the fourteen major allergens emphasised, for example in bold.</p>
<p>Food packed in front of the customer after they order does not need this label, but you must still be able to tell them about allergens if they ask. Plain kraft boxes and bags take a printed or handwritten label well; leave a clear panel free for it.</p>
<h2>Carrier bags</h2>
<p>In England, shops must charge at least 10p for single-use plastic carrier bags. Paper carrier bags are not covered by the English charge, but Scotland, Wales and Northern Ireland have their own carrier bag rules that can include paper, so check if you trade there.</p>
<h2>Storing packaging hygienically</h2>
<p>Store food packaging as carefully as dry ingredients: off the floor, clean and dry, away from cleaning chemicals and in its outer wrapping until needed. Throw away anything that has been damp or dusty.</p>
<h2>Packaging producer responsibility</h2>
<p>Extended producer responsibility asks businesses that place packaging on the market to report it and, above certain sizes, pay towards collecting it. The thresholds are set by turnover and tonnage, and most small cafés fall below them; check them each year if you are growing quickly.</p>
<h2>A short checklist</h2>
<ul>
    <li>Buy packaging sold as suitable for food contact, and use it for the food it was made for.</li>
    <li>Keep invoices that show what you bought and from whom.</li>
    <li>Avoid banned single-use plastic items.</li>
    <li>Label anything you prepack for direct sale with full ingredients and allergens.</li>
    <li>Store packaging clean, dry and off the floor.</li>
    <li>Tell customers which bin your cups and boxes belong in.</li>
</ul>
<p>If you are unsure whether an item suits your menu, call our customer team. We would rather spend ten minutes on the phone than sell you a box that softens before it reaches the customer.</p>`,
    },
    {
        slug: 'packing-bench-setup',
        title: 'Setting up a packing bench that saves you an hour a day',
        documentTitle: 'Setting up a packing bench that saves time | Boxes',
        description: 'Bench height, three working zones, what to keep within reach, pre-folding and batching orders: a practical guide to packing parcels faster.',
        author: 'Priya Natarajan',
        role: 'Customer team lead',
        date: '2026-09-17',
        minutes: 4,
        standfirst: 'A well-arranged packing bench turns twenty fiddly minutes into five. How to lay it out, what to keep within reach and how to batch your orders.',
        image: { src: '/assets/img/products/kraft-paper-tape-800.webp', alt: 'Rolls of brown kraft paper tape, one partly unwound' },
        body: `<p>When we visit customers who pack their own orders, the same thing comes up: the packing is quick; the walking, searching and reaching are slow. A bench that is set up properly can easily save an hour a day for a business sending forty or fifty parcels, and it makes the work less tiring. Here is what our own packing team has learned.</p>
<h2>Get the height right</h2>
<p>The most important measurement is the height of the work surface. For standing work, the bench top should be roughly at the height of your wrists when your arms hang by your sides, which for most people is between 900 and 950 mm. Too low and you stoop over every parcel; too high and your shoulders tire.</p>
<p>If more than one person packs, choose a height that suits the tallest, then give shorter colleagues a firm platform to stand on. An anti-fatigue mat makes a noticeable difference over a full day.</p>
<p>The bench should be deep enough to take your largest common box with space around it, usually 750 to 900 mm, and wide enough for three zones side by side.</p>
<h2>Work in three zones</h2>
<p>Lay the bench out in the order the work flows, left to right or right to left, whichever suits the room:</p>
<ol>
    <li><strong>Incoming.</strong> Picked items and the order slip arrive here, in a tray or crate per order.</li>
    <li><strong>Packing.</strong> The clear space in the middle where the box is folded, filled and sealed.</li>
    <li><strong>Outgoing.</strong> Scales, label printer and a space for finished parcels, ideally next to the door or the collection point.</li>
</ol>
<p>Each order moves in one direction and never doubles back. That alone stops parcels getting mixed up.</p>
<h2>Keep everything in reach</h2>
<p>Stand in the packing zone and stretch your arms out. Everything you use on every order should be inside that reach, without stepping: tape gun, scissors or knife, void fill, wrap, stickers, and your two or three most used box sizes. Things you use on some orders can go one step away. Anything you use once a week can live on a shelf.</p>
<p>A few simple fixtures help:</p>
<ul>
    <li>A roll holder above or behind the bench for honeycomb wrap or bubble wrap, so you pull and tear with one hand.</li>
    <li>A tape gun on a hook or in a holster at the edge of the bench, always in the same place.</li>
    <li>Flat-packed boxes stored on edge under the bench, divided by size, so you can pull one without moving the rest.</li>
    <li>A wall-mounted rail or shallow shelves for mailers, labels and tags.</li>
</ul>
<h2>Pre-fold, pre-cut, pre-stock</h2>
<p>Five minutes at the start of the day saves far more later. Fold up the boxes you know you will need, especially regular slotted cartons, which take longer than crash-lock mailers. Tear off a batch of wrap or paper void fill. Make sure the label printer has a full roll and a spare on the shelf.</p>
<p>Keep a simple restocking list on the wall. When anything drops to a day's supply, tick it, and reorder once a week. Running out of tape halfway through a busy afternoon costs far more time than ordering it.</p>
<h2>Batch your orders</h2>
<p>Picking and packing one order at a time feels orderly, but it means walking the same route through your stock again and again. Instead:</p>
<ul>
    <li><strong>Print every pick list and label at once</strong>, at a set time, such as after the 11am and 2pm cut-offs you choose for yourself.</li>
    <li><strong>Pick in batches</strong>, collecting items for ten or twenty orders in one walk.</li>
    <li><strong>Pack in groups of the same box size</strong>, so you are not switching between sizes and void fill amounts on every order.</li>
</ul>
<p>For many small businesses, batching is where most of the hour is saved.</p>
<h2>Standardise the box and the wrap</h2>
<p>The fewer decisions on each order, the faster it goes. Write a short packing guide and stick it above the bench: which box for which product, how many layers of wrap, what goes on top. It is invaluable when someone new helps out in a busy season, and it keeps every parcel looking the same.</p>
<h2>Weigh and label without moving</h2>
<p>Put the scales and label printer side by side at the end of the bench, with the computer or tablet between them. If your postage software can read the scales directly, set that up; it removes a source of typing mistakes. Thermal label printers are worth it once you send more than a handful of parcels a day: no ink, no cutting and no peeling sheets.</p>
<h2>Look after the space</h2>
<p>A tidy bench is a fast bench. Sweep away offcuts and empty tape cores at the end of each day, flatten incoming cardboard for reuse as void fill or recycling, and keep a bin for anything that cannot be recycled right by the bench, so it does not end up in the wrong place.</p>
<h2>Try it for a week</h2>
<p>You do not need to buy anything to start. Measure your bench height, move the most used items within arm's reach, and batch one session of orders. Time a normal day before and after. Most customers notice the difference within a week, and the hour they save is better spent than looking for the tape.</p>`,
    },
];

const indexPage = {
    path: '/journal/',
    title: 'Journal',
    description: 'Practical packaging guides for small businesses: box sizes, plastic-free mailing, food packaging rules for cafés and packing bench set-up.',
    crumbs: [{ label: 'Journal' }],
    body: (site) => `${pageHero({
        title: 'Journal',
        lead: 'Practical guides from our customer team and packaging technologist: how to choose sizes, cut waste, keep within the rules and pack orders faster. Written for small businesses, with no jargon we have not explained.',
        plain: true,
    })}
<section class="section--tight section" aria-label="Journal articles">
    <div class="container">
        <ul class="article-grid">
            ${articlePages(site).map((page) => `<li>${articleCard(page, { level: 2 })}</li>`).join('\n            ')}
        </ul>
    </div>
</section>`,
};

const moreFromJournal = (site, current) => `<section class="section band--surface" aria-labelledby="more-journal">
    <div class="container stack">
        <h2 id="more-journal">More from the journal</h2>
        <ul class="article-grid">
            ${articlePages(site).filter((page) => page.path !== current).map((page) => `<li>${articleCard(page, { level: 3 })}</li>`).join('\n            ')}
        </ul>
    </div>
</section>`;

const articleBody = (entry, path) => (site) => `<article class="article container">
    <header class="article__header">
        <h1 class="article__title">${esc(entry.title)}</h1>
        <p class="article__standfirst">${esc(entry.standfirst)}</p>
        <p class="byline"><span class="byline__author">${esc(entry.author)}</span>, ${esc(entry.role)} <span aria-hidden="true">&middot;</span> <time datetime="${entry.date}">${formatDate(entry.date)}</time> <span aria-hidden="true">&middot;</span> ${entry.minutes} min read</p>
    </header>
    <img class="article__image" src="${entry.image.src}" width="800" height="450" alt="${esc(entry.image.alt)}">
    <div class="article__body prose">
${entry.body}
    </div>
    <footer class="article__footer meta">${footerNote}</footer>
</article>
${moreFromJournal(site, path)}`;

const articlePage = (entry) => {
    const path = `/journal/${entry.slug}/`;

    return {
        path,
        title: entry.title,
        documentTitle: entry.documentTitle,
        description: entry.description,
        crumbs: [{ label: 'Journal', href: '/journal/' }, { label: entry.title }],
        current: '/journal/',
        article: {
            date: entry.date,
            author: entry.author,
            role: entry.role,
            excerpt: entry.standfirst,
            image: entry.image,
            minutes: entry.minutes,
        },
        body: articleBody(entry, path),
    };
};

export default [indexPage, ...articles.map(articlePage)];
