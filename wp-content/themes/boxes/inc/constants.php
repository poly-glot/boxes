<?php

const BOXES_FONTS_URL = 'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;1,400&display=swap';

const BOXES_SCRIPTS = array( 'navigation', 'carousel', 'basket', 'search' );

const BOXES_SEARCH_INDEX_VAR = 'boxes_search_index';

const BOXES_SEARCH_PAGE = 'search';

const BOXES_SHOP_TAXONOMIES = array( 'product_category', 'collection' );

const BOXES_MAX_QUANTITY = 999;

const BOXES_ENQUIRY_CONTACT_KEYS = array( 'name', 'company', 'email', 'phone' );

const BOXES_SEARCH_INDEX_PATH = 'assets/search-index.json';

const BOXES_STOCK = array(
	'in-stock'      => array(
		'icon'  => 'check',
		'label' => 'In stock',
	),
	'low-stock'     => array(
		'icon'  => 'clock',
		'label' => 'Low stock',
	),
	'made-to-order' => array(
		'icon'  => 'clock',
		'label' => 'Made to order',
	),
);

const BOXES_ICONS = array(
	'phone'         => '<path d="M6.6 3.5h2.9l1.4 4.2-2 1.4a12 12 0 0 0 6 6l1.4-2 4.2 1.4v2.9a1.9 1.9 0 0 1-1.9 1.9A15.8 15.8 0 0 1 4.7 5.4a1.9 1.9 0 0 1 1.9-1.9Z" />',
	'mail'          => '<rect x="3" y="5" width="18" height="14" rx="1.5" /><path d="m3.5 6 8.5 7 8.5-7" />',
	'search'        => '<circle cx="10.5" cy="10.5" r="6.5" /><path d="m15.5 15.5 5 5" />',
	'user'          => '<circle cx="12" cy="8" r="4" /><path d="M4.5 20.5c1-4 4-6 7.5-6s6.5 2 7.5 6" />',
	'basket'        => '<path d="M2.5 9.5h19M4.5 9.5 6 20h12l1.5-10.5M8 9.5 11 4M16 9.5 13 4M9.5 13v4M14.5 13v4" />',
	'chevron-down'  => '<path d="m5 9 7 7 7-7" />',
	'chevron-left'  => '<path d="M15 5 8 12l7 7" />',
	'chevron-right' => '<path d="m9 5 7 7-7 7" />',
	'home'          => '<path d="M3.5 11 12 4l8.5 7M6 9.5V20h12V9.5M10 20v-5h4v5" />',
	'check'         => '<path d="m5 12.5 4.5 4.5L19 7.5" />',
	'clock'         => '<circle cx="12" cy="12" r="8.5" /><path d="M12 7.5V12l3 2" />',
	'truck'         => '<path d="M2.5 6.5h11v10h-11zM13.5 9.5h4l3 3.5v3.5h-7" /><circle cx="6.5" cy="17.5" r="1.8" /><circle cx="17" cy="17.5" r="1.8" />',
	'leaf'          => '<path d="M5 19C5 10 10 5 20 4c0 10-5 15-13 15ZM5 19l8-8" />',
	'tag'           => '<path d="M3.5 12.5V4h8.5l8.5 8.5-8.5 8.5Z" /><circle cx="8" cy="8.5" r="1.5" />',
	'close'         => '<path d="m5.5 5.5 13 13M18.5 5.5l-13 13" />',
	'plus'          => '<path d="M12 5v14M5 12h14" />',
	'minus'         => '<path d="M5 12h14" />',
);

const BOXES_LOGO_MARK = '<symbol id="logo-mark" viewBox="0 0 48 48"><path d="M24 14 40 22l2-9-16-8Z" fill="currentColor" fill-opacity="0.18" /><path d="M8 22 24 30 40 22M8 22 24 14M8 22v16l16 8 16-8V22M24 30v16" /></symbol>';

const BOXES_SERVICE_PAGES = array( 'custom-printing', 'bespoke-boxes' );

const BOXES_CONFIRMATION_TITLES = array(
	'enquiry' => 'Thank you, we have your message',
	'order'   => 'Thank you, we have your order',
);

const BOXES_CONFIRMATION_TEXT = 'We will reply by email within one working hour during opening times.';

const BOXES_ERROR_EMAIL = 'Enter an email address in the correct format, like name@example.co.uk';

const BOXES_ERROR_PHONE = 'Enter a UK phone number, like 01632 960 418';

const BOXES_ERROR_POSTCODE = 'Enter a full UK postcode, like NN4 0ZZ';

const BOXES_POSTCODE_HINT = 'UK postcodes only, for example NN4 0ZZ.';

const BOXES_WHOLE_NUMBER = array(
	'min'       => '1',
	'step'      => '1',
	'inputmode' => 'numeric',
);

const BOXES_FORMS = array(
	'order'    => array(
		'label'  => 'Order',
		'page'   => 'basket',
		'fields' => array(
			array(
				'autocomplete' => 'name',
				'error'        => 'Enter your name',
				'id'           => 'order-name',
				'label'        => 'Your name',
				'required'     => true,
			),
			array(
				'autocomplete' => 'organization',
				'error'        => 'Enter your company name',
				'id'           => 'order-company',
				'label'        => 'Company name',
				'required'     => true,
			),
			array(
				'autocomplete' => 'email',
				'error'        => BOXES_ERROR_EMAIL,
				'hint'         => 'We send your order confirmation here.',
				'id'           => 'order-email',
				'label'        => 'Email address',
				'required'     => true,
				'type'         => 'email',
			),
			array(
				'autocomplete' => 'tel',
				'error'        => BOXES_ERROR_PHONE,
				'hint'         => 'For the driver if there is a problem with delivery.',
				'id'           => 'order-phone',
				'label'        => 'Phone number',
				'required'     => true,
				'type'         => 'tel',
			),
			array(
				'attributes'   => array( 'spellcheck' => 'false' ),
				'autocomplete' => 'postal-code',
				'error'        => BOXES_ERROR_POSTCODE,
				'hint'         => BOXES_POSTCODE_HINT,
				'id'           => 'order-postcode',
				'label'        => 'Delivery postcode',
				'required'     => true,
			),
			array(
				'autocomplete' => 'off',
				'hint'         => 'If your accounts team needs one on the invoice.',
				'id'           => 'order-reference',
				'label'        => 'Purchase order reference',
			),
			array(
				'hint'  => 'Delivery instructions, opening times or anything else we should know.',
				'id'    => 'order-notes',
				'label' => 'Notes',
				'type'  => 'textarea',
			),
		),
	),
	'contact'  => array(
		'label'  => 'Contact page',
		'page'   => 'contact',
		'submit' => 'Send message',
		'fields' => array(
			array(
				'autocomplete' => 'name',
				'error'        => 'Enter your name',
				'id'           => 'contact-name',
				'label'        => 'Your name',
				'required'     => true,
			),
			array(
				'autocomplete' => 'organization',
				'id'           => 'contact-company',
				'label'        => 'Company name',
			),
			array(
				'autocomplete' => 'email',
				'error'        => BOXES_ERROR_EMAIL,
				'id'           => 'contact-email',
				'label'        => 'Email address',
				'required'     => true,
				'type'         => 'email',
			),
			array(
				'autocomplete' => 'tel',
				'hint'         => 'If you would prefer us to call you back.',
				'id'           => 'contact-phone',
				'label'        => 'Phone number',
				'type'         => 'tel',
			),
			array(
				'error'    => 'Choose what your message is about',
				'id'       => 'contact-topic',
				'label'    => 'What is it about?',
				'options'  => array( 'An existing order', 'A new order or product question', 'Returns or damaged goods', 'Trade accounts', 'Custom printing or bespoke boxes', 'Something else' ),
				'required' => true,
				'type'     => 'select',
			),
			array(
				'autocomplete' => 'off',
				'hint'         => 'If your message is about an existing order.',
				'id'           => 'contact-order',
				'label'        => 'Order number',
			),
			array(
				'error'    => 'Enter your message',
				'id'       => 'contact-message',
				'label'    => 'Your message',
				'required' => true,
				'type'     => 'textarea',
			),
		),
	),
	'trade'    => array(
		'label'  => 'Trade account application',
		'page'   => 'trade-accounts',
		'submit' => 'Send application',
		'fields' => array(
			array(
				'autocomplete' => 'name',
				'error'        => 'Enter your name',
				'id'           => 'trade-name',
				'label'        => 'Your name',
				'required'     => true,
			),
			array(
				'autocomplete' => 'organization-title',
				'id'           => 'trade-job',
				'label'        => 'Job title',
			),
			array(
				'autocomplete' => 'organization',
				'error'        => 'Enter your company name',
				'hint'         => 'As registered, if you are a limited company.',
				'id'           => 'trade-company',
				'label'        => 'Company name',
				'required'     => true,
			),
			array(
				'error'    => 'Choose a business type',
				'id'       => 'trade-type',
				'label'    => 'Business type',
				'options'  => array( 'Online seller', 'Café, bakery or takeaway', 'Shop or gift shop', 'Maker or studio', 'Office or business', 'Charity, school or public body', 'Other' ),
				'required' => true,
				'type'     => 'select',
			),
			array(
				'autocomplete' => 'off',
				'hint'         => 'Leave blank if you are a sole trader or partnership.',
				'id'           => 'trade-number',
				'label'        => 'Company number',
			),
			array(
				'autocomplete' => 'off',
				'id'           => 'trade-vat',
				'label'        => 'VAT number',
			),
			array(
				'autocomplete' => 'email',
				'error'        => BOXES_ERROR_EMAIL,
				'hint'         => 'We send your account details here.',
				'id'           => 'trade-email',
				'label'        => 'Email address',
				'required'     => true,
				'type'         => 'email',
			),
			array(
				'autocomplete' => 'tel',
				'error'        => BOXES_ERROR_PHONE,
				'id'           => 'trade-phone',
				'label'        => 'Phone number',
				'required'     => true,
				'type'         => 'tel',
			),
			array(
				'attributes'   => array( 'spellcheck' => 'false' ),
				'autocomplete' => 'postal-code',
				'error'        => BOXES_ERROR_POSTCODE,
				'hint'         => BOXES_POSTCODE_HINT,
				'id'           => 'trade-postcode',
				'label'        => 'Business postcode',
				'required'     => true,
			),
			array(
				'autocomplete' => 'off',
				'error'        => BOXES_ERROR_EMAIL,
				'hint'         => 'If invoices and statements should go to someone else.',
				'id'           => 'trade-accounts',
				'label'        => 'Accounts email address',
				'type'         => 'email',
			),
			array(
				'error'    => 'Choose your expected monthly spend',
				'id'       => 'trade-spend',
				'label'    => 'Expected monthly spend',
				'options'  => array( 'Under £250', '£250 to £1,000', '£1,000 to £5,000', 'Over £5,000' ),
				'required' => true,
				'type'     => 'select',
			),
			array(
				'hint'  => 'A rough list is fine. It helps your account manager prepare.',
				'id'    => 'trade-usual',
				'label' => 'What do you usually buy?',
				'type'  => 'textarea',
			),
			array(
				'error' => 'Tick the box to confirm you agree to a credit check',
				'id'    => 'trade-consent',
				'label' => 'I agree that Boxes may carry out a credit check on my business.',
				'type'  => 'consent',
			),
		),
	),
	'printing' => array(
		'label'  => 'Custom printing',
		'page'   => 'custom-printing',
		'submit' => 'Send enquiry',
		'fields' => array(
			array(
				'autocomplete' => 'name',
				'error'        => 'Enter your name',
				'id'           => 'printing-name',
				'label'        => 'Your name',
				'required'     => true,
			),
			array(
				'autocomplete' => 'organization',
				'error'        => 'Enter your company name',
				'id'           => 'printing-company',
				'label'        => 'Company name',
				'required'     => true,
			),
			array(
				'autocomplete' => 'email',
				'error'        => BOXES_ERROR_EMAIL,
				'id'           => 'printing-email',
				'label'        => 'Email address',
				'required'     => true,
				'type'         => 'email',
			),
			array(
				'autocomplete' => 'tel',
				'id'           => 'printing-phone',
				'label'        => 'Phone number',
				'type'         => 'tel',
			),
			array(
				'error'    => 'Choose what you would like printed',
				'id'       => 'printing-product',
				'label'    => 'What would you like printed?',
				'options'  => array( 'Paper bags', 'Paper carriers', 'Mailer boxes', 'Packing tape', 'Labels and stickers', 'Cotton totes', 'Not sure yet' ),
				'required' => true,
				'type'     => 'select',
			),
			array(
				'attributes' => BOXES_WHOLE_NUMBER,
				'error'      => 'Enter your quantity',
				'hint'       => 'An estimate is fine.',
				'id'         => 'printing-quantity',
				'label'      => 'Quantity',
				'required'   => true,
				'type'       => 'number',
			),
			array(
				'id'      => 'printing-colours',
				'label'   => 'Number of print colours',
				'options' => array( 'One', 'Two', 'Full colour', 'Not sure' ),
				'type'    => 'select',
			),
			array(
				'id'    => 'printing-date',
				'label' => 'When do you need it?',
				'type'  => 'date',
			),
			array(
				'hint'  => 'Describe the design, or tell us if you need help with artwork.',
				'id'    => 'printing-design',
				'label' => 'Tell us about your design',
				'type'  => 'textarea',
			),
			array(
				'attributes' => array( 'accept' => '.pdf,.svg,.eps' ),
				'full'       => true,
				'hint'       => 'PDF, SVG or EPS, up to 10 MB. In this demonstration, files are not uploaded.',
				'id'         => 'printing-artwork',
				'label'      => 'Artwork file',
				'type'       => 'file',
			),
		),
	),
	'bespoke'  => array(
		'label'  => 'Bespoke boxes',
		'page'   => 'bespoke-boxes',
		'submit' => 'Send enquiry',
		'fields' => array(
			array(
				'autocomplete' => 'name',
				'error'        => 'Enter your name',
				'id'           => 'bespoke-name',
				'label'        => 'Your name',
				'required'     => true,
			),
			array(
				'autocomplete' => 'organization',
				'error'        => 'Enter your company name',
				'id'           => 'bespoke-company',
				'label'        => 'Company name',
				'required'     => true,
			),
			array(
				'autocomplete' => 'email',
				'error'        => BOXES_ERROR_EMAIL,
				'id'           => 'bespoke-email',
				'label'        => 'Email address',
				'required'     => true,
				'type'         => 'email',
			),
			array(
				'autocomplete' => 'tel',
				'id'           => 'bespoke-phone',
				'label'        => 'Phone number',
				'type'         => 'tel',
			),
			array(
				'error'    => 'Enter what will go in the box',
				'hint'     => 'For example, a 1 kg candle in a glass jar, or three paperback books.',
				'id'       => 'bespoke-contents',
				'label'    => 'What will go in the box?',
				'required' => true,
				'type'     => 'textarea',
			),
			array(
				'hint'   => 'Leave blank if you would like us to work it out.',
				'id'     => 'bespoke-size',
				'legend' => 'Internal length, width and height in mm',
				'type'   => 'group',
				'fields' => array(
					array(
						'attributes' => BOXES_WHOLE_NUMBER,
						'id'         => 'bespoke-length',
						'label'      => 'Length in mm',
						'type'       => 'number',
					),
					array(
						'attributes' => BOXES_WHOLE_NUMBER,
						'id'         => 'bespoke-width',
						'label'      => 'Width in mm',
						'type'       => 'number',
					),
					array(
						'attributes' => BOXES_WHOLE_NUMBER,
						'id'         => 'bespoke-height',
						'label'      => 'Height in mm',
						'type'       => 'number',
					),
				),
			),
			array(
				'attributes' => array(
					'min'       => '0',
					'step'      => '0.1',
					'inputmode' => 'decimal',
				),
				'id'         => 'bespoke-weight',
				'label'      => 'Weight of contents in kg',
				'type'       => 'number',
			),
			array(
				'id'      => 'bespoke-style',
				'label'   => 'Box style',
				'options' => array( 'Shipping carton', 'Mailer box', 'Box with insert', 'Two-piece box', 'Not sure' ),
				'type'    => 'select',
			),
			array(
				'error'    => 'Choose a quantity per order',
				'id'       => 'bespoke-quantity',
				'label'    => 'Quantity per order',
				'options'  => array( '250 to 499', '500 to 999', '1,000 to 4,999', '5,000 or more' ),
				'required' => true,
				'type'     => 'select',
			),
			array(
				'id'      => 'bespoke-printing',
				'label'   => 'Do you need printing?',
				'options' => array( 'No', 'Yes, one or two colours', 'Not sure yet' ),
				'type'    => 'choices',
			),
			array(
				'id'    => 'bespoke-notes',
				'label' => 'Anything else?',
				'type'  => 'textarea',
			),
		),
	),
);
