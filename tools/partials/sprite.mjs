const icons = {
    phone: '<path d="M6.6 3.5h2.9l1.4 4.2-2 1.4a12 12 0 0 0 6 6l1.4-2 4.2 1.4v2.9a1.9 1.9 0 0 1-1.9 1.9A15.8 15.8 0 0 1 4.7 5.4a1.9 1.9 0 0 1 1.9-1.9Z" />',
    mail: '<rect x="3" y="5" width="18" height="14" rx="1.5" /><path d="m3.5 6 8.5 7 8.5-7" />',
    search: '<circle cx="10.5" cy="10.5" r="6.5" /><path d="m15.5 15.5 5 5" />',
    user: '<circle cx="12" cy="8" r="4" /><path d="M4.5 20.5c1-4 4-6 7.5-6s6.5 2 7.5 6" />',
    basket: '<path d="M2.5 9.5h19M4.5 9.5 6 20h12l1.5-10.5M8 9.5 11 4M16 9.5 13 4M9.5 13v4M14.5 13v4" />',
    'chevron-down': '<path d="m5 9 7 7 7-7" />',
    'chevron-left': '<path d="M15 5 8 12l7 7" />',
    'chevron-right': '<path d="m9 5 7 7-7 7" />',
    home: '<path d="M3.5 11 12 4l8.5 7M6 9.5V20h12V9.5M10 20v-5h4v5" />',
    check: '<path d="m5 12.5 4.5 4.5L19 7.5" />',
    clock: '<circle cx="12" cy="12" r="8.5" /><path d="M12 7.5V12l3 2" />',
    truck: '<path d="M2.5 6.5h11v10h-11zM13.5 9.5h4l3 3.5v3.5h-7" /><circle cx="6.5" cy="17.5" r="1.8" /><circle cx="17" cy="17.5" r="1.8" />',
    leaf: '<path d="M5 19C5 10 10 5 20 4c0 10-5 15-13 15ZM5 19l8-8" />',
    tag: '<path d="M3.5 12.5V4h8.5l8.5 8.5-8.5 8.5Z" /><circle cx="8" cy="8.5" r="1.5" />',
    close: '<path d="m5.5 5.5 13 13M18.5 5.5l-13 13" />',
    plus: '<path d="M12 5v14M5 12h14" />',
    minus: '<path d="M5 12h14" />',
};

const logoMark = '<symbol id="logo-mark" viewBox="0 0 48 48"><path d="M24 14 40 22l2-9-16-8Z" fill="currentColor" fill-opacity="0.18" /><path d="M8 22 24 30 40 22M8 22 24 14M8 22v16l16 8 16-8V22M24 30v16" /></symbol>';

const symbols = Object.entries(icons).map(([name, paths]) => `<symbol id="icon-${name}" viewBox="0 0 24 24">${paths}</symbol>`);

export const sprite = () => `<svg class="sprite" xmlns="http://www.w3.org/2000/svg" hidden>
    ${logoMark}
    ${symbols.join('\n    ')}
</svg>`;
