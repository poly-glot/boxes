const entities = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };

export const esc = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => entities[character]);

export const icon = (name, className = '') => `<svg class="icon${className ? ` ${className}` : ''}" aria-hidden="true" focusable="false"><use href="#icon-${name}" /></svg>`;

const pounds = new Intl.NumberFormat('en-GB', { style: 'currency', currency: 'GBP' });

export const money = (amount) => pounds.format(amount);

const longDate = new Intl.DateTimeFormat('en-GB', { day: 'numeric', month: 'long', year: 'numeric', timeZone: 'UTC' });

export const formatDate = (isoDate) => longDate.format(new Date(`${isoDate}T00:00:00Z`));

export const currentAttr = (href, page) => {
    if (href === page.path) {
        return ' aria-current="page"';
    }

    return href === page.current ? ' aria-current="true"' : '';
};

export const company = {
    name: 'Boxes',
    legalName: 'Boxes Packaging Ltd',
    phone: '01632 960 418',
    phoneHref: 'tel:+441632960418',
    email: 'hello@boxes.junaid.guru',
    ordersEmail: 'orders@boxes.junaid.guru',
    address: ['Unit 4, Brookfield Trade Park', 'Northampton', 'NN4 0ZZ'],
    hours: 'Monday to Friday, 8am to 5pm',
    companyNumber: '00000000',
    vatNumber: 'GB 000 0000 00',
};

export const phoneText = company.phone.replaceAll(' ', '&nbsp;');
