import { company } from '../partials/html.mjs';
import { emailLink, errors, field, formPanel, mapFrame, pageHero, phoneLink } from '../partials/components.mjs';

const contactForm = formPanel({
    prefix: 'contact',
    title: 'Send us a message',
    submit: 'Send message',
    confirmation: 'Thank you. This is a demonstration website, so your message has not been sent. In a real shop, we would reply within one working day.',
    fields: [
        field({ id: 'contact-name', label: 'Your name', required: true, autocomplete: 'name', error: 'Enter your name' }),
        field({ id: 'contact-company', label: 'Company name', autocomplete: 'organization' }),
        field({ id: 'contact-email', label: 'Email address', type: 'email', required: true, autocomplete: 'email', error: errors.email }),
        field({ id: 'contact-phone', label: 'Phone number', type: 'tel', autocomplete: 'tel', hint: 'If you would prefer us to call you back.' }),
        field({
            id: 'contact-topic',
            label: 'What is it about?',
            type: 'select',
            required: true,
            error: 'Choose what your message is about',
            options: ['An existing order', 'A new order or product question', 'Returns or damaged goods', 'Trade accounts', 'Custom printing or bespoke boxes', 'Something else'],
        }),
        field({ id: 'contact-order', label: 'Order number', autocomplete: 'off', hint: 'If your message is about an existing order.' }),
        field({ id: 'contact-message', label: 'Your message', type: 'textarea', required: true, error: 'Enter your message' }),
    ],
});

const details = [
    ['Address', `${company.legalName}, Unit 4, Brookfield Trade Park, Northampton NN4 0ZZ`],
    ['Phone', phoneLink],
    ['General enquiries', emailLink(company.email)],
    ['Orders and returns', emailLink(company.ordersEmail)],
    ['Opening hours', `${company.hours}. Closed weekends and bank holidays.`],
];

export default {
    path: '/contact/',
    title: 'Contact us',
    description: 'Call 01632 960 418 or email hello@boxes.junaid.guru, Monday to Friday 8am to 5pm. Boxes, Unit 4, Brookfield Trade Park, Northampton NN4 0ZZ.',
    crumbs: [{ label: 'Contact us' }],
    body: `${pageHero({
        title: 'Contact us',
        lead: 'Our customer team answers the phone and email from 8am to 5pm, Monday to Friday. For help with an order, have your order number to hand. For anything else, from sizing advice to a trade account, just ask.',
    })}
<div class="with-aside container">
    <div class="with-aside__main">
        ${contactForm}
    </div>
    <section class="with-aside__aside" aria-labelledby="details-title">
        <h2 id="details-title">Contact details</h2>
        <div class="prose">
            ${details.map(([label, value]) => `<p><strong>${label}:</strong> ${value}</p>`).join('\n            ')}
        </div>
    </section>
</div>
<section class="section band--surface" aria-labelledby="find-us-title">
    <div class="split container">
        <div class="split__text">
            <h2 class="split__title" id="find-us-title">Find us</h2>
            <div class="prose">
                <p>Brookfield Trade Park is signposted from the A45 on the south side of Northampton, about five minutes from the M1. Unit 4 is at the far end of the park on the right, with visitor parking outside the main door.</p>
                <p>${company.legalName}<br>${company.address.join('<br>')}</p>
            </div>
        </div>
        ${mapFrame({ alt: 'Illustrative map showing Brookfield Trade Park on the south side of Northampton', caption: 'Brookfield Trade Park, Northampton NN4 0ZZ. Visits by appointment.' })}
    </div>
</section>`,
};
