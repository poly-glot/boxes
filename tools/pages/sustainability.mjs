import { pageHero } from '../partials/components.mjs';

export default {
    path: '/sustainability/',
    title: 'Sustainability',
    description: 'How Boxes chooses materials, publishes recycled content, takes back cardboard and pallets, plans fuller delivery routes and is honest about the limits.',
    crumbs: [{ label: 'Sustainability' }],
    body: `${pageHero({
        title: 'Sustainability',
        lead: 'Packaging exists to be thrown away, so the choices behind it matter. Here is what we do, what we are still working on and where the honest answer is &ldquo;it depends&rdquo;.',
        image: { src: '/assets/img/pages/sustainability.webp', alt: 'Bales of flattened cardboard and a stack of reusable wooden pallets in a warehouse loading bay' },
    })}
<section class="section" aria-label="Our approach">
    <div class="container">
        <div class="prose">
            <h2>Paper first, plastic where it earns its place</h2>
            <p>About four in five of the products we sell are made from paper or board: cartons, mailers, bags, tape, wrap, labels and tags. Paper is widely recycled in the UK and most of it can go in a household or business recycling bin without being taken apart. Where we have a paper option that does the same job, such as kraft paper tape, honeycomb wrap or paper-padded mailers, we put it first.</p>
            <p>Plastic still has a place. A polythene mailing bag keeps clothing dry on a wet doorstep, bubble wrap is the most forgiving protection for glass, and stretch film is the safest way to hold a pallet together. In those cases we choose versions with recycled content and say clearly on the product page how they can be recycled.</p>
            <h2>Recycled content</h2>
            <p>Most of our corrugated cartons contain between 70% and 80% recycled fibre. Our grey mailing bags contain 70% recycled polythene, and our bubble wrap and stretch film around 30%. We publish the recycled content for each product in its specification so you can compare like with like, and when a figure is a maximum rather than a guarantee, we say &ldquo;up to&rdquo;.</p>
            <h2>Take-back for local trade customers</h2>
            <p>If you are a trade customer within 25 miles of Northampton, our delivery driver will collect clean, flattened cardboard and wooden pallets when they drop off your next order. Cardboard is baled at our warehouse and collected for recycling; pallets are inspected, repaired where needed and reused. There is no charge, but we do ask that the cardboard is dry and free of food waste.</p>
            <h2>Fewer vans, fuller vans</h2>
            <p>We plan local delivery routes so that each van does one loop a day rather than several partial ones, and we ask customers who order little and often whether a weekly delivery day would suit them. Consolidating orders this way cuts the number of journeys, which matters more than almost anything else we could change about a parcel. National orders go with our courier partner on their existing routes.</p>
            <h2>Choosing the right size</h2>
            <p>The most effective change a small business can make is often the simplest: use a box that fits. Smaller boxes mean less board, less void fill and lower postage. Our journal has <a href="/journal/choosing-the-right-box-size/">a guide to choosing box sizes</a>, and our customer team is happy to look at your products and suggest a shorter list of sizes.</p>
        </div>
    </div>
</section>
<section class="section band--surface" aria-labelledby="unsolved-title">
    <div class="container">
        <div class="prose">
            <h2 id="unsolved-title">What we have not solved</h2>
            <p>Paper coffee cups need a plastic lining to hold liquid, and most kerbside schemes will not take them. Compostable food packaging only breaks down in industrial composting, which not every area offers. Thermal labels cannot be recycled on their own. We stock these products because customers need them, and we explain on each page how to deal with them at the end of their life rather than make claims we cannot stand behind.</p>
        </div>
    </div>
</section>`,
};
