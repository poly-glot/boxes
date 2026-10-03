<?php

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

$catalogue_file = __DIR__ . '/catalogue.json';

if ( ! is_readable( $catalogue_file ) ) {
	fwrite( STDERR, "catalogue MISSING {$catalogue_file}\n" );
	exit( 1 );
}

$catalogue = json_decode( file_get_contents( $catalogue_file ), true );

if ( ! is_array( $catalogue ) || empty( $catalogue['products'] ) ) {
	fwrite( STDERR, "catalogue INVALID {$catalogue_file}\n" );
	exit( 1 );
}

if ( ! post_type_exists( 'product' ) || ! taxonomy_exists( 'product_category' ) || ! taxonomy_exists( 'collection' ) ) {
	fwrite( STDERR, "model MISSING: run php wp-content/mu-plugins/yacf/build.php\n" );
	exit( 1 );
}

if ( get_stylesheet() !== 'boxes' ) {
	switch_theme( 'boxes' );
	echo "theme: switched to boxes\n";
}

if ( ! function_exists( 'boxes_setup' ) ) {
	require_once get_theme_file_path( 'functions.php' );
	boxes_setup();
}

kses_remove_filters();

function boxes_seed_media_signature(): string {
	return md5( wp_json_encode( [ wp_get_registered_image_subsizes(), wp_image_editor_supports( [ 'mime_type' => 'image/webp' ] ) ] ) );
}

function boxes_seed_attachment( $key, $file, $alt ) {
	$existing = get_posts( [
		'meta_key'    => '_boxes_seed_file',
		'meta_value'  => $key,
		'numberposts' => 1,
		'post_status' => 'any',
		'post_type'   => 'attachment',
	] );

	$signature = boxes_seed_media_signature();

	if ( $existing && get_post_meta( $existing[0]->ID, '_boxes_seed_signature', true ) === $signature ) {
		$id = (int) $existing[0]->ID;
		update_post_meta( $id, '_wp_attachment_image_alt', $alt );
		return [ $id, 0 ];
	}

	if ( $existing ) {
		wp_delete_attachment( $existing[0]->ID, true );
		echo "media REGENERATING {$key}\n";
	}

	$source = __DIR__ . '/img/' . $file;

	if ( ! file_exists( $source ) ) {
		echo "media MISSING {$file}\n";
		return [ 0, 0 ];
	}

	$tmp = wp_tempnam( $key );
	copy( $source, $tmp );

	$id = media_handle_sideload( [ 'name' => $key, 'tmp_name' => $tmp ], 0 );

	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		echo "media FAILED {$key}: " . $id->get_error_message() . "\n";
		return [ 0, 0 ];
	}

	update_post_meta( $id, '_boxes_seed_file', $key );
	update_post_meta( $id, '_boxes_seed_signature', $signature );
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );

	return [ (int) $id, 1 ];
}

function boxes_seed_upsert( $id, $args, $meta = [], $thumb = 0 ) {
	$args = array_merge( [ 'post_status' => 'publish' ], $args );

	if ( $id ) {
		$args['ID'] = $id;
		$id         = wp_update_post( $args );
		$created    = 0;
	} else {
		$id      = wp_insert_post( $args );
		$created = 1;
	}

	foreach ( $meta as $key => $value ) {
		update_post_meta( $id, $key, $value );
	}

	if ( $thumb ) {
		set_post_thumbnail( $id, $thumb );
	} else {
		delete_post_thumbnail( $id );
	}

	return [ (int) $id, $created ];
}

function boxes_seed_post( $type, $path, $args, $meta = [], $thumb = 0 ) {
	$found = get_page_by_path( $path, OBJECT, $type );
	$id    = $found && $found->post_type === $type ? $found->ID : 0;

	return boxes_seed_upsert( $id, array_merge( [ 'post_name' => basename( $path ), 'post_type' => $type ], $args ), $meta, $thumb );
}

function boxes_seed_term( $taxonomy, $slug, $name, $meta ) {
	$term    = get_term_by( 'slug', $slug, $taxonomy );
	$created = 0;

	if ( $term ) {
		wp_update_term( $term->term_id, $taxonomy, [ 'name' => $name ] );
		$id = (int) $term->term_id;
	} else {
		$inserted = wp_insert_term( $name, $taxonomy, [ 'slug' => $slug ] );

		if ( is_wp_error( $inserted ) ) {
			fwrite( STDERR, "term FAILED {$taxonomy} {$slug}: " . $inserted->get_error_message() . "\n" );
			exit( 1 );
		}

		$id      = (int) $inserted['term_id'];
		$created = 1;
	}

	foreach ( $meta as $key => $value ) {
		update_term_meta( $id, $key, $value );
	}

	return [ $id, $created ];
}

function boxes_seed_pick( $ids, $slugs ) {
	return array_values( array_filter( array_map( fn( $slug ) => $ids[ $slug ] ?? 0, $slugs ) ) );
}

function boxes_seed_paragraphs( $paragraphs ) {
	return implode( "\n", array_map( fn( $paragraph ) => '<p>' . htmlspecialchars( $paragraph, ENT_NOQUOTES, 'UTF-8' ) . '</p>', $paragraphs ) );
}

function boxes_seed_collection_positions( $collections ) {
	$sequence = [];
	$placed   = [];

	foreach ( $collections as $collection ) {
		$previous = null;

		foreach ( $collection['products'] as $slug ) {
			if ( ! isset( $placed[ $slug ] ) ) {
				$at = null === $previous ? 0 : array_search( $previous, $sequence, true ) + 1;
				array_splice( $sequence, $at, 0, [ $slug ] );
				$placed[ $slug ] = true;
			}

			$previous = $slug;
		}
	}

	return array_flip( $sequence );
}

function boxes_seed_menu( $name, $items ) {
	$menu     = wp_get_nav_menu_object( $name );
	$id       = $menu ? $menu->term_id : wp_create_nav_menu( $name );
	$existing = array_column( wp_get_nav_menu_items( $id ) ?: [], null, 'title' );
	$created  = 0;

	foreach ( array_values( $items ) as $index => [ $title, $url ] ) {
		$item = [
			'menu-item-position' => $index + 1,
			'menu-item-status'   => 'publish',
			'menu-item-title'    => $title,
			'menu-item-type'     => 'custom',
			'menu-item-url'      => $url,
		];

		$current = $existing[ $title ] ?? null;

		if ( ! $current ) {
			wp_update_nav_menu_item( $id, 0, $item );
			$created++;
		} elseif ( (int) $current->menu_order !== $item['menu-item-position'] || $current->url !== $url ) {
			wp_update_nav_menu_item( $id, $current->db_id, $item );
		}
	}

	return [ (int) $id, $created ];
}

update_option( 'blogdescription', 'Trade packaging for small businesses' );
update_option( 'blogname', 'Boxes' );
update_option( 'date_format', 'j F Y' );
update_option( 'start_of_week', 1 );
update_option( 'time_format', 'H:i' );
update_option( 'timezone_string', 'Europe/London' );

echo "settings: identity, date formats and timezone set\n";

$phone_link = '<a href="tel:+441632960418">01632&nbsp;960&nbsp;418</a>';
$ex_vat     = '<span aria-hidden="true">ex VAT</span><span class="visually-hidden">excluding VAT</span>';

$articles = [
	'choosing-the-right-box-size' => [
		'alt'     => 'A brown cardboard carton with its flaps open, ready to be measured',
		'content' => <<<'HTML'
			<p>Pick up any parcel on a sorting belt and there is a good chance a third of it is empty space. That space costs money twice: once in the board and void fill it takes to fill it, and again in postage, because couriers increasingly charge by size as well as weight. Choosing the right box is one of the cheapest savings a small business can make.</p>
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
			<p>Next time you pack an order, close the lid and give the box a gentle shake. If you can hear or feel the item move, the box is too big or the wrap is too thin. If the flaps bulge, it is too small. When it sits still and the flaps close flat, you have the right size, and you have probably stopped paying to post air.</p>
			HTML,
		'date'    => '2026-07-14 09:00:00',
		'excerpt' => 'Most parcels are bigger than they need to be. Here is how to measure, choose a short list of sizes and cut both postage and void fill.',
		'image'   => 'single-wall-postal-carton.jpg',
		'meta'    => [
			'author_name'      => 'Owen Marsh',
			'author_role'      => 'Packaging technologist',
			'document_title'   => 'How to choose the right box size | Boxes',
			'meta_description' => 'Measure your product, read box sizes correctly, understand volumetric weight and build a short list of box sizes that cuts postage and void fill.',
			'reading_minutes'  => 4,
		],
		'title'   => 'How to choose the right box size (and stop paying to post air)',
	],
	'plastic-free-mailing'        => [
		'alt'     => 'A brown paper mailer open to show its honeycomb paper lining',
		'content' => <<<'HTML'
			<p>Customers notice plastic in their parcels, and many now mention it in reviews. Most of what goes into a typical online order has a paper alternative that works just as well. A few swaps cost more, take longer to pack or protect less, and it is better to know that before you change everything at once.</p>
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
			<p>Most businesses can remove the majority of the plastic from their parcels in a month without any customer noticing a difference, except the one they were hoping for.</p>
			HTML,
		'date'    => '2026-08-06 09:00:00',
		'excerpt' => 'Paper can replace most of the plastic in an online order, but not all of it. What to swap, what to keep and what to tell customers.',
		'image'   => 'honeycomb-paper-mailer.jpg',
		'meta'    => [
			'author_name'      => 'Owen Marsh',
			'author_role'      => 'Packaging technologist',
			'document_title'   => 'Going plastic-free with your mailing | Boxes',
			'meta_description' => 'What to swap, what to keep and what to tell customers: paper tape, void fill, paper mailers, honeycomb wrap, and what recyclable and compostable mean.',
			'reading_minutes'  => 5,
		],
		'title'   => 'Going plastic-free with your mailing: what actually works',
	],
	'food-packaging-rules'        => [
		'alt'     => 'Two brown kraft takeaway boxes, one closed and one open and empty',
		'content' => <<<'HTML'
			<p>Café owners often ask our customer team whether a cup, box or bag is "allowed". The rules are spread across several pieces of law and differ slightly between England, Scotland, Wales and Northern Ireland. This guide gathers the parts that matter most to a small food business. It is not legal advice; your local environmental health team is the place to check anything specific.</p>
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
			<p>If you are unsure whether an item suits your menu, call our customer team. We would rather spend ten minutes on the phone than sell you a box that softens before it reaches the customer.</p>
			HTML,
		'date'    => '2026-08-27 09:00:00',
		'excerpt' => 'What a small café, bakery or takeaway needs to know about food-contact materials, single-use plastics, allergen labels and recycling, without the legal language.',
		'image'   => 'kraft-food-box-lidded.jpg',
		'meta'    => [
			'author_name'      => 'Priya Natarajan',
			'author_role'      => 'Customer team lead',
			'document_title'   => 'Food packaging rules for cafés, in plain English | Boxes',
			'meta_description' => 'Food-contact rules, single-use plastic bans, paper cups, compostables, allergen labels and storing packaging: a plain-English guide for cafés and bakeries.',
			'reading_minutes'  => 5,
		],
		'title'   => 'Food packaging for cafés: the rules in plain English',
	],
	'packing-bench-setup'         => [
		'alt'     => 'Rolls of brown kraft paper tape, one partly unwound',
		'content' => <<<'HTML'
			<p>When we visit customers who pack their own orders, the same thing comes up: the packing is quick; the walking, searching and reaching are slow. A bench that is set up properly can easily save an hour a day for a business sending forty or fifty parcels, and it makes the work less tiring. Here is what our own packing team has learned.</p>
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
			<p>You do not need to buy anything to start. Measure your bench height, move the most used items within arm's reach, and batch one session of orders. Time a normal day before and after. Most customers notice the difference within a week, and the hour they save is better spent than looking for the tape.</p>
			HTML,
		'date'    => '2026-09-17 09:00:00',
		'excerpt' => 'A well-arranged packing bench turns twenty fiddly minutes into five. How to lay it out, what to keep within reach and how to batch your orders.',
		'image'   => 'kraft-paper-tape.jpg',
		'meta'    => [
			'author_name'      => 'Priya Natarajan',
			'author_role'      => 'Customer team lead',
			'document_title'   => 'Setting up a packing bench that saves time | Boxes',
			'meta_description' => 'Bench height, three working zones, what to keep within reach, pre-folding and batching orders: a practical guide to packing parcels faster.',
			'reading_minutes'  => 4,
		],
		'title'   => 'Setting up a packing bench that saves you an hour a day',
	],
];

$media = [];

foreach ( $catalogue['products'] as $product ) {
	$media[ "{$product['slug']}.jpg" ] = [ "{$product['slug']}.jpg", $product['image']['alt'] ?? '' ];
}

$media += [
	'hero-slide-1.jpg'           => [ 'hero-slide-1.jpg', 'A tidy packing bench with brown cartons, paper tape and mailers ready to send' ],
	'hero-slide-2.jpg'           => [ 'hero-slide-2.jpg', 'A ceramic mug being wrapped in honeycomb kraft paper beside an open mailer box' ],
	'hero-slide-3.jpg'           => [ 'hero-slide-3.jpg', 'A café counter with paper coffee cups, kraft food boxes and bakery bags with pastries' ],
	'promo-new-arrivals.jpg'     => [ 'promo-new-arrivals.jpg', 'Honeycomb paper mailers, kraft tape and a pillow box arranged on a light table' ],
	'promo-best-sellers.jpg'     => [ 'promo-best-sellers.jpg', 'Brown postal cartons, a roll of shipping labels and bubble wrap stacked on a warehouse shelf' ],
	'pages-about.jpg'            => [ 'pages-about.jpg', 'An orderly packaging warehouse with racks of flat-packed cartons, where two members of staff check an order together at a packing bench' ],
	'pages-sustainability.jpg'   => [ 'pages-sustainability.jpg', 'Bales of flattened cardboard and a stack of reusable wooden pallets in a warehouse loading bay' ],
	'pages-custom-printing.jpg'  => [ 'pages-custom-printing.jpg', 'A small printing press printing a simple stripe pattern onto brown kraft paper bags' ],
	'pages-bespoke-boxes.jpg'    => [ 'pages-bespoke-boxes.jpg', 'Hands measuring a prototype corrugated box with a steel rule on a cutting table, beside a stack of flat box blanks' ],
	'pages-trade-accounts.jpg'   => [ 'pages-trade-accounts.jpg', 'A shop owner unpacking brown cartons of stock onto the shelves of a small stockroom' ],
	'teaser-sustainability.jpg'  => [ 'pages-sustainability.jpg', 'A stack of flattened cardboard bundled with paper string beside a wooden pallet' ],
];

foreach ( $articles as $slug => $article ) {
	$media[ "journal-{$slug}.jpg" ] = [ $article['image'], $article['alt'] ];
}

$img           = [];
$created_media = 0;

foreach ( $media as $key => [ $file, $alt ] ) {
	[ $img[ $key ], $created ] = boxes_seed_attachment( $key, $file, $alt );
	$created_media += $created;
}

echo "media: {$created_media} created, " . count( array_filter( $img ) ) . ' of ' . count( $media ) . " ready\n";

$category_ids       = [];
$created_categories = 0;

foreach ( array_values( $catalogue['categories'] ) as $index => $category ) {
	[ $category_ids[ $category['slug'] ], $created ] = boxes_seed_term( 'product_category', $category['slug'], $category['name'], [
		'description' => $category['description'],
		'intro'       => $category['intro'],
		'position'    => $index + 1,
	] );

	$created_categories += $created;
}

echo "categories: {$created_categories} created, " . count( $category_ids ) . " total\n";

$collection_ids      = [];
$collections_by_slug = [];
$created_collections = 0;

foreach ( array_values( $catalogue['collections'] ) as $index => $collection ) {
	[ $collection_ids[ $collection['slug'] ], $created ] = boxes_seed_term( 'collection', $collection['slug'], $collection['name'], [
		'description' => $collection['description'],
		'intro'       => $collection['intro'],
		'position'    => $index + 1,
	] );

	foreach ( $collection['products'] as $slug ) {
		$collections_by_slug[ $slug ][] = $collection['slug'];
	}

	$created_collections += $created;
}

echo "collections: {$created_collections} created, " . count( $collection_ids ) . " total\n";

$product_ids_by_sku = [];

foreach ( get_posts( [ 'numberposts' => -1, 'post_status' => 'any', 'post_type' => 'product' ] ) as $existing_product ) {
	$product_ids_by_sku[ get_post_meta( $existing_product->ID, 'sku', true ) ] = $existing_product->ID;
}

$positions        = boxes_seed_collection_positions( $catalogue['collections'] );
$product_ids      = [];
$created_products = 0;

foreach ( array_values( $catalogue['products'] ) as $index => $product ) {
	$slug = $product['slug'];

	[ $product_ids[ $slug ], $created ] = boxes_seed_upsert( $product_ids_by_sku[ $product['sku'] ] ?? 0, [
		'menu_order'   => $index,
		'post_content' => boxes_seed_paragraphs( $product['description'] ),
		'post_name'    => $slug,
		'post_title'   => $product['name'],
		'post_type'    => 'product',
	], [
		'collection_position' => isset( $positions[ $slug ] ) ? $positions[ $slug ] + 1 : 0,
		'pack'                => $product['pack'],
		'price'               => $product['price'],
		'sku'                 => $product['sku'],
		'specification'       => array_map( fn( $row ) => [ 'label' => $row[0], 'value' => $row[1] ], $product['specification'] ),
		'stock'               => $product['stock'],
		'stock_note'          => $product['stockNote'] ?? '',
		'summary'             => $product['summary'],
		'tiers'               => array_map( fn( $tier ) => [ 'min' => $tier['min'], 'price' => $tier['price'] ], $product['tiers'] ),
	], $img[ "{$slug}.jpg" ] ?? 0 );

	wp_set_object_terms( $product_ids[ $slug ], [ $category_ids[ $product['category'] ] ], 'product_category' );
	wp_set_object_terms( $product_ids[ $slug ], boxes_seed_pick( $collection_ids, $collections_by_slug[ $slug ] ?? [] ), 'collection' );

	$created_products += $created;
}

echo "products: {$created_products} created, " . count( $product_ids ) . " total\n";

foreach ( $catalogue['products'] as $product ) {
	update_post_meta( $product_ids[ $product['slug'] ], 'related', boxes_seed_pick( $product_ids, $product['related'] ?? [] ) );
}

echo 'relations: ' . count( $product_ids ) . " products linked to related products\n";

$post_ids      = [];
$created_posts = 0;

foreach ( $articles as $slug => $article ) {
	[ $post_ids[ $slug ], $created ] = boxes_seed_post( 'post', $slug, [
		'post_content'  => $article['content'],
		'post_date'     => $article['date'],
		'post_date_gmt' => get_gmt_from_date( $article['date'] ),
		'post_excerpt'  => $article['excerpt'],
		'post_title'    => $article['title'],
	], $article['meta'], $img[ "journal-{$slug}.jpg" ] ?? 0 );

	$created_posts += $created;
}

echo "journal: {$created_posts} created, " . count( $post_ids ) . " total\n";

$pages = [
	'home'            => [
		'meta'  => [
			'document_title'          => 'Boxes | Trade packaging for small businesses',
			'home_benefits_title'     => 'Why order from Boxes',
			'home_best_sellers_link'  => 'See all best sellers',
			'home_best_sellers_title' => 'Best sellers',
			'home_categories_title'   => 'Shop categories',
			'home_journal_link'       => 'All journal articles',
			'home_journal_title'      => 'From the journal',
			'meta_description'        => 'Boxes, mailing bags, paper bags, food packaging, labels and tape in trade packs. Bulk discounts, same-day dispatch before 2pm, free delivery over £150.',
		],
		'title' => 'Trade packaging for small businesses',
	],
	'shop'            => [
		'meta'  => [
			'crumb_label'      => 'Shop',
			'lead'             => "Everything we stock, in eight ranges. Every price is per pack and excludes VAT, and every product page shows the bulk discounts that apply when you order more packs. Not sure which size you need? Call us on {$phone_link} and we will help you choose.",
			'meta_description' => 'Browse every Boxes range: cartons, mailing bags, paper and carrier bags, food packaging, gift boxes, labels and tape. Trade prices per pack ex VAT.',
		],
		'title' => 'Shop all packaging',
	],
	'basket'          => [
		'meta'  => [
			'crumb_label'       => 'Basket',
			'form_confirmation' => 'This is a demonstration shop, so no order has been placed, no payment has been taken and nothing will be delivered. Your basket has been left as it was, so you can keep exploring. In a real shop, this is where you would see your order reference and an estimated delivery date.',
			'form_intro'        => 'Tell us where to send your order. We will confirm stock and delivery by email within one working hour during opening times.',
			'form_title'        => 'Place a trade order',
			'meta_description'  => 'Review the packs in your basket, see bulk discounts and delivery costs, and place a trade order with Boxes.',
		],
		'title' => 'Your basket',
	],
	'search'          => [
		'meta'  => [
			'meta_description' => 'Search the Boxes range by product name, material or SKU.',
		],
		'title' => 'Search',
	],
	'price-list'      => [
		'meta'  => [
			'lead'             => 'Every product we stock, with its SKU, pack size and three bulk prices. All prices are per pack, in pounds sterling, excluding VAT, and are correct for this demonstration only. Print this page or save it as a PDF for your purchasing file.',
			'meta_description' => 'The full Boxes price list: every product with SKU, pack size and bulk discount prices ex VAT. Printable for your purchasing file.',
			'table_note'       => 'Delivery is £7.95 ex VAT, free on orders over £150 ex VAT. VAT is charged at 20%.',
		],
		'title' => 'Price list',
	],
	'about'           => [
		'content'   => <<<'HTML'
			<h2>Where we started</h2>
			<p>Boxes began in 2009 in a rented lock-up on the edge of Northampton, with one van, a second-hand tape gun and a list of a dozen local shops. The founders had spent years buying packaging for a small mail-order business and were tired of the same problem: the big suppliers wanted orders by the pallet, and the small ones charged as if every box were a gift.</p>
			<p>The idea was simple. Sell good, plain packaging in packs a small business can actually use, show the price for every quantity up front, and answer the phone. Seventeen years on, that is still most of what we do.</p>
			<h2>The warehouse</h2>
			<p>In 2016 we moved to Unit 4 at Brookfield Trade Park, a 2,800 square metre warehouse with room for around 1,400 pallet spaces. It sits a few minutes from the M1, which is why orders placed before 2pm can leave the same afternoon and reach most of mainland UK the next working day.</p>
			<p>Everything we sell is picked and packed on site. Our packing team uses the same boxes, mailers and paper tape you can buy from us, so if something does not work well, we tend to find out first.</p>
			<h2>The team</h2>
			<p>There are 24 of us now. Most of the team live within a few miles of the trade park, and several have been here more than ten years. You will speak to the same customer team whether you buy one pack of tape a month or a lorry-load of cartons a week, and trade customers have a named account manager.</p>
			HTML,
		'meta'      => [
			'heading'          => 'About Boxes',
			'highlights'       => [
				[ 'icon' => 'tag', 'text' => 'Every price is per pack and ex VAT, with bulk discounts shown on the product page rather than hidden behind a quote.', 'title' => 'Straight prices.' ],
				[ 'icon' => 'check', 'text' => 'We would rather sell you a smaller box that fits than a bigger one that needs a bag of void fill.', 'title' => 'Practical advice.' ],
				[ 'icon' => 'leaf', 'text' => 'Most of our range is paper and board, much of it with recycled content, and we are honest about where plastic still makes sense.', 'title' => 'Less waste.' ],
				[ 'icon' => 'truck', 'text' => 'If we say it will be dispatched today, it will be. If something goes wrong, we will tell you before you have to ask.', 'title' => 'Keeping our word.' ],
			],
			'highlights_title' => 'What we care about',
			'lead'             => 'We are a family-run packaging supplier in Northampton. Since 2009 we have sold boxes, bags and tape in sensible pack sizes to the small businesses that keep high streets and online shops going.',
			'meta_description' => 'Boxes is a family-run packaging supplier in Northampton, founded in 2009. A team of 24 selling plain, practical packaging in packs to small businesses.',
			'notice_text'      => "Trade customers are welcome to visit the warehouse to see samples or talk through a bespoke box. Call ahead on {$phone_link} and we will put the kettle on.",
			'notice_title'     => 'Come and see us',
		],
		'thumbnail' => 'pages-about.jpg',
		'title'     => 'About us',
	],
	'sustainability'  => [
		'content'   => <<<'HTML'
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
			HTML,
		'meta'      => [
			'band_text'        => 'Paper coffee cups need a plastic lining to hold liquid, and most kerbside schemes will not take them. Compostable food packaging only breaks down in industrial composting, which not every area offers. Thermal labels cannot be recycled on their own. We stock these products because customers need them, and we explain on each page how to deal with them at the end of their life rather than make claims we cannot stand behind.',
			'band_title'       => 'What we have not solved',
			'lead'             => 'Packaging exists to be thrown away, so the choices behind it matter. Here is what we do, what we are still working on and where the honest answer is &ldquo;it depends&rdquo;.',
			'meta_description' => 'How Boxes chooses materials, publishes recycled content, takes back cardboard and pallets, plans fuller delivery routes and is honest about the limits.',
		],
		'thumbnail' => 'pages-sustainability.jpg',
		'title'     => 'Sustainability',
	],
	'delivery'        => [
		'content' => <<<'HTML'
			<h2>Delivery</h2>
			<ul>
			    <li><strong>Dispatch:</strong> orders placed before 2pm on a working day are dispatched the same working day. Orders placed after 2pm, or at a weekend or on a bank holiday, are dispatched the next working day.</li>
			<li><strong>Standard delivery:</strong> £7.95 <span aria-hidden="true">ex VAT</span><span class="visually-hidden">excluding VAT</span> per order.</li>
			<li><strong>Free delivery:</strong> on orders over £150 <span aria-hidden="true">ex VAT</span><span class="visually-hidden">excluding VAT</span>.</li>
			<li><strong>Mainland UK:</strong> delivered the next working day after dispatch.</li>
			<li><strong>Highlands, islands and Northern Ireland:</strong> delivered 2–3 working days after dispatch.</li>
			<li><strong>Made-to-order items:</strong> dispatched within 5 working days of your order. Anything else on the same order can be sent ahead at no extra charge if you ask.</li>
			<li><strong>Large orders:</strong> orders of a full pallet or more are delivered on a pallet service. We will call you to agree a delivery day.</li>
			</ul>
			<p>Every order is sent with a tracking link by email as soon as it leaves our warehouse.</p>
			<h2>Returns</h2>
			<p>You can return any unopened pack within 30 days of delivery for a refund or credit. Packs must be sealed, unused and in a condition we can sell again.</p>
			<ol>
			    <li>Email <a href="mailto:orders@boxes.junaid.guru">orders@boxes.junaid.guru</a> with your order number and the items you would like to return.</li>
			<li>We will reply within one working day with a returns reference and a collection date.</li>
			<li>Our courier partner collects the parcel. If the return is not because of our mistake, we deduct £7.95 <span aria-hidden="true">ex VAT</span><span class="visually-hidden">excluding VAT</span> for the collection.</li>
			<li>We refund to your original payment method, or credit your trade account, within 5 working days of receiving the goods.</li>
			</ol>
			<p>We cannot accept returns of opened packs, made-to-order items, custom-printed items or bespoke boxes, unless they are damaged or faulty.</p>
			<h2>Damaged or missing goods</h2>
			<p>If something arrives damaged, or part of an order is missing, tell us within 2 working days of delivery.</p>
			<ol>
			    <li>Take a photo of the damaged item and the outer packaging before you throw anything away.</li>
			<li>Email the photos to <a href="mailto:orders@boxes.junaid.guru">orders@boxes.junaid.guru</a> with your order number, or call <a href="tel:+441632960418">01632&nbsp;960&nbsp;418</a>.</li>
			<li>We will send a replacement on our next dispatch, or refund the item if you prefer. You will not be charged for delivery or collection.</li>
			</ol>
			<p>If a delivery arrives visibly damaged, you can sign for it as &ldquo;damaged&rdquo; or refuse it, and let us know the same day.</p>
			HTML,
		'meta'    => [
			'faq_title'        => 'Frequently asked questions',
			'faqs'             => [
				[ 'answer' => '2pm, Monday to Friday. Orders placed by then leave our warehouse the same day.', 'question' => 'When is the cut-off for same-day dispatch?' ],
				[ 'answer' => 'Not as standard. If you need a Saturday delivery, call us before you order and we will check whether our courier partner can do it for your postcode, and what it costs.', 'question' => 'Do you deliver on Saturdays?' ],
				[ 'answer' => "On the order subtotal excluding VAT, after any bulk discounts. If your subtotal is over £150 {$ex_vat}, delivery is free.", 'question' => 'How is the free delivery threshold worked out?' ],
				[ 'answer' => 'We do not run a collection counter, because every order goes through the same picking and checking process. Trade customers who need something urgently can call us and we will see what we can do.', 'question' => 'Can I collect my order from the warehouse?' ],
				[ 'answer' => 'Not at the moment. We deliver to addresses in Great Britain and Northern Ireland only.', 'question' => 'Do you deliver outside the UK?' ],
				[ 'answer' => 'We email a tracking link as soon as your parcel leaves us. If you have a trade account, your account manager can also send dispatch confirmations to a second email address, such as your accounts team.', 'question' => 'How will I know when my order has been dispatched?' ],
				[ 'answer' => "Yes, if it has not yet been dispatched. Call us on {$phone_link} as soon as you can. Once an order has been picked and packed, it is treated as a return.", 'question' => 'Can I change or cancel an order after placing it?' ],
				[ 'answer' => 'Usually not, because we cannot sell an opened pack again. If an item is faulty, it does not matter whether the pack is open: tell us and we will put it right.', 'question' => 'Can I return a pack I have opened?' ],
				[ 'answer' => 'We process refunds within 5 working days of receiving your return. Card refunds can take a further 3–5 working days to appear, depending on your bank.', 'question' => 'How long do refunds take?' ],
				[ 'answer' => 'Trade customers within 25 miles of Northampton can give our driver clean, flattened cardboard and wooden pallets when we deliver. See our <a href="/sustainability/">sustainability page</a> for details.', 'question' => 'Do you take back packaging?' ],
			],
			'lead'             => "Clear rules, the same for every order. If anything here does not answer your question, call us on {$phone_link}.",
			'meta_description' => 'Same-day dispatch before 2pm, £7.95 ex VAT delivery, free over £150 ex VAT. Next working day to mainland UK. Returns on unopened packs within 30 days.',
		],
		'title'   => 'Delivery & returns',
	],
	'trade-accounts'  => [
		'meta'      => [
			'form_confirmation' => 'Thank you. This is a demonstration website, so your application has not been sent. In a real shop, we would reply within 2 working days.',
			'form_title'        => 'Apply for a trade account',
			'highlights'        => array_map( fn( $text ) => [ 'icon' => 'check', 'text' => $text, 'title' => '' ], [
				'30-day payment terms, subject to approval',
				'A named account manager with a direct line',
				'Bulk discounts applied to every order, as shown on product pages',
				'Monthly statements and invoices sent to your accounts team',
				'Purchase order references on every invoice',
				'Repeat orders by phone or email in a single message',
				'Free take-back of clean cardboard and pallets within 25 miles of Northampton',
				'Early notice of price changes and new ranges',
			] ),
			'highlights_title'  => 'Benefits',
			'intro_columns'     => [
				[ 'anchor' => 'who-title', 'text' => 'Trade accounts are for UK businesses, charities, schools and public bodies that buy packaging regularly. You do not need to spend a minimum amount, but we usually open accounts for businesses that expect to order at least once a month. Sole traders are welcome.', 'title' => 'Who can apply' ],
				[ 'anchor' => 'terms-title', 'text' => 'Approved accounts can pay by invoice 30 days from the end of the month in which goods are dispatched. Terms are subject to approval: we carry out a credit check when you apply, and we may start with a lower credit limit and review it after three months. You can still pay by card at any time.', 'title' => 'How 30-day terms work' ],
				[ 'anchor' => 'manager-title', 'text' => 'Every trade account has a named account manager in our customer team. They will learn what you usually order, remind you when you are running low if you would like them to, and help with sizing, samples and custom printing. You will have their direct line and email.', 'title' => 'Your account manager' ],
			],
			'lead'              => 'Buy on 30-day terms, with one person who knows your business and your usual order.',
			'meta_description'  => 'Apply for a Boxes trade account: 30-day terms subject to approval, a named account manager, bulk discounts and monthly statements.',
			'steps'             => array_map( fn( $text ) => [ 'text' => $text, 'title' => '' ], [
				'Send the application form below.',
				'We run a credit check and reply within 2 working days.',
				'Your account manager calls to introduce themselves and set up your account.',
				'You place your first order on account.',
			] ),
			'steps_title'       => 'What happens next',
		],
		'thumbnail' => 'pages-trade-accounts.jpg',
		'title'     => 'Trade accounts',
	],
	'custom-printing' => [
		'meta'      => [
			'form_confirmation' => 'Thank you. This is a demonstration website, so your enquiry has not been sent. In a real shop, we would send a quote and proof within 2 working days.',
			'form_title'        => 'Ask about custom printing',
			'guide_content'     => <<<'HTML'
				<ul>
				<li>Send vector artwork as a PDF, SVG or EPS file, with all text converted to outlines.</li>
				<li>For one- and two-colour printing, tell us the colour references you want matched. We will show you how they look on kraft and on white, because the same ink prints darker on brown paper.</li>
				<li>For full-colour labels, send a PDF at 300 dpi at actual size, with 3 mm bleed on every edge.</li>
				<li>Keep fine lines at 0.5 pt or thicker and text at 6 pt or larger, especially on corrugated board.</li>
				<li>Not sure your file is ready? Send what you have. We will check it and tell you what needs to change before you pay anything.</li>
				</ul>
				HTML,
			'guide_title'       => 'Artwork guidance',
			'lead'              => 'Your logo or pattern on bags, boxes, tape and labels, printed in runs that suit a small business.',
			'list_intro'        => '',
			'list_items'        => [
				[ 'label' => 'Paper bags and carriers:', 'text' => 'counter bags, grocery bags and twisted or flat-handle carriers, printed in one or two colours on one or both sides.' ],
				[ 'label' => 'Mailer boxes:', 'text' => 'crash-lock mailers printed on the outside in one colour, or inside and out in up to two colours.' ],
				[ 'label' => 'Packing tape:', 'text' => 'kraft paper tape printed with a repeating design in one colour.' ],
				[ 'label' => 'Labels and stickers:', 'text' => 'round and rectangular labels in full colour, on kraft or white paper.' ],
				[ 'label' => 'Cotton totes:', 'text' => 'printed on one side in up to two colours.' ],
			],
			'list_title'        => 'What we print on',
			'meta_description'  => 'Custom-printed paper bags, carriers, mailer boxes, tape, labels and totes, from 100 units. Artwork guidance, lead times and a free digital proof.',
			'steps'             => [
				[ 'text' => 'Tell us what you would like printed, the quantity and when you need it.', 'title' => 'Enquire.' ],
				[ 'text' => 'We send a fixed quote and a digital proof showing your design on the product, usually within 2 working days.', 'title' => 'Quote and proof.' ],
				[ 'text' => 'You approve the proof and pay a 50% deposit, or confirm the order on your trade account.', 'title' => 'Approve.' ],
				[ 'text' => 'We print, check a sample from the run and dispatch within the lead time above.', 'title' => 'Print and deliver.' ],
			],
			'steps_title'       => 'How it works',
			'table_caption'     => 'Minimum order quantities and lead times for custom printing',
			'table_columns'     => [
				[ 'label' => 'Product' ],
				[ 'label' => 'Minimum order' ],
				[ 'label' => 'Lead time after artwork approval' ],
			],
			'table_intro'       => '',
			'table_note'        => 'Lead times are from the day you approve your proof, and the delivery rules on our <a href="/delivery/">delivery page</a> apply once your order is dispatched. Custom-printed items cannot be returned unless they are faulty.',
			'table_rows'        => [
				[ 'cell_1' => 'Paper counter bags', 'cell_2' => '2,000', 'cell_3' => '10–15 working days' ],
				[ 'cell_1' => 'Paper carriers', 'cell_2' => '500', 'cell_3' => '15–20 working days' ],
				[ 'cell_1' => 'Mailer boxes', 'cell_2' => '250', 'cell_3' => '15–20 working days' ],
				[ 'cell_1' => 'Kraft paper tape', 'cell_2' => '36 rolls', 'cell_3' => '10–15 working days' ],
				[ 'cell_1' => 'Labels and stickers', 'cell_2' => '1,000', 'cell_3' => '5–7 working days' ],
				[ 'cell_1' => 'Cotton totes', 'cell_2' => '100', 'cell_3' => '10–15 working days' ],
			],
			'table_title'       => 'Minimum quantities',
		],
		'thumbnail' => 'pages-custom-printing.jpg',
		'title'     => 'Custom printing',
	],
	'bespoke-boxes'   => [
		'meta'      => [
			'form_confirmation' => 'Thank you. This is a demonstration website, so your enquiry has not been sent. In a real shop, we would reply with questions or a quote within 2 working days.',
			'form_title'        => 'Ask about a bespoke box',
			'guide_content'     => "<p>Before a production run, we make a plain, unprinted sample box to your dimensions in the board grade you have chosen. Your first sample is free and is dispatched within 5 working days; further samples are £15.00 {$ex_vat} each. Pack your product into it, post it to yourself, and tell us what you would change.</p>",
			'guide_title'       => 'Samples',
			'lead'              => 'When none of our stock sizes fits, we will make a box that does, from 250 boxes upwards.',
			'list_intro'        => 'We make boxes to your internal dimensions, from 100 × 100 × 50 mm up to 1,200 × 800 × 800 mm. The most common styles are:',
			'list_items'        => [
				[ 'label' => 'Regular slotted cartons:', 'text' => 'the classic four-flap shipping box, quick to make and economical in board.' ],
				[ 'label' => 'Crash-lock and tuck-lid mailers:', 'text' => 'fast to assemble and tidy to open, for subscription boxes and online orders.' ],
				[ 'label' => 'Die-cut boxes with inserts:', 'text' => 'a box and cardboard fitment that hold a product still without void fill.' ],
				[ 'label' => 'Two-piece and telescopic boxes:', 'text' => 'a base and a lid for long or awkward items, such as prints, tools or garden equipment.' ],
			],
			'list_title'        => 'Sizes and styles',
			'meta_description'  => 'Boxes made to your dimensions from 250 units: board grades explained, a free first sample in 5 working days and production within 15 working days.',
			'steps'             => [
				[ 'text' => 'Send your product dimensions and weight, or post us one of your products.', 'title' => 'Tell us what goes inside.' ],
				[ 'text' => 'We suggest a style, size and board grade, with a fixed price per box at two or three quantities.', 'title' => 'We design and quote.' ],
				[ 'text' => 'We make a sample. You test it and approve it, or we adjust and make another.', 'title' => 'Sample and approve.' ],
				[ 'text' => 'Your boxes are made and dispatched within 15 working days of approval. We can hold stock for trade customers and send it out as you need it.', 'title' => 'Production.' ],
			],
			'steps_title'       => 'How it works',
			'table_caption'     => 'Corrugated and rigid board grades with thickness and typical use',
			'table_columns'     => [
				[ 'label' => 'Grade' ],
				[ 'label' => 'Thickness' ],
				[ 'label' => 'Good for' ],
			],
			'table_intro'       => 'Corrugated board is described by the height of its flutes, the waves of paper between the two flat liners. Taller flutes cushion more; thinner ones fold neatly and print better.',
			'table_note'        => 'We can also tell you the burst and edge-crush strength of each grade if your customers or your courier ask for it.',
			'table_rows'        => [
				[ 'cell_1' => 'E-flute', 'cell_2' => '1.5 mm', 'cell_3' => 'Mailer boxes and retail boxes up to about 3 kg' ],
				[ 'cell_1' => 'B-flute', 'cell_2' => '3 mm', 'cell_3' => 'Everyday postal cartons up to about 15 kg' ],
				[ 'cell_1' => 'C-flute', 'cell_2' => '4 mm', 'cell_3' => 'Fragile items that need more cushioning' ],
				[ 'cell_1' => 'BC double wall', 'cell_2' => '7 mm', 'cell_3' => 'Heavy loads up to about 30 kg and pallet stacking' ],
				[ 'cell_1' => 'Greyboard (rigid)', 'cell_2' => '1–2 mm solid board', 'cell_3' => 'Presentation boxes that are kept, not posted' ],
			],
			'table_title'       => 'Board grades explained',
		],
		'thumbnail' => 'pages-bespoke-boxes.jpg',
		'title'     => 'Bespoke boxes',
	],
	'contact'         => [
		'meta'  => [
			'details'           => [
				[ 'label' => 'Address', 'value' => 'Boxes Packaging Ltd, Unit 4, Brookfield Trade Park, Northampton NN4 0ZZ' ],
				[ 'label' => 'Phone', 'value' => $phone_link ],
				[ 'label' => 'General enquiries', 'value' => '<a href="mailto:hello@boxes.junaid.guru">hello@boxes.junaid.guru</a>' ],
				[ 'label' => 'Orders and returns', 'value' => '<a href="mailto:orders@boxes.junaid.guru">orders@boxes.junaid.guru</a>' ],
				[ 'label' => 'Opening hours', 'value' => 'Monday to Friday, 8am to 5pm. Closed weekends and bank holidays.' ],
			],
			'details_title'     => 'Contact details',
			'form_confirmation' => 'Thank you. This is a demonstration website, so your message has not been sent. In a real shop, we would reply within one working day.',
			'form_title'        => 'Send us a message',
			'lead'              => 'Our customer team answers the phone and email from 8am to 5pm, Monday to Friday. For help with an order, have your order number to hand. For anything else, from sizing advice to a trade account, just ask.',
			'location_text'     => <<<'HTML'
				<p>Brookfield Trade Park is signposted from the A45 on the south side of Northampton, about five minutes from the M1. Unit 4 is at the far end of the park on the right, with visitor parking outside the main door.</p>
				<p>Boxes Packaging Ltd<br>Unit 4, Brookfield Trade Park<br>Northampton<br>NN4 0ZZ</p>
				HTML,
			'location_title'    => 'Find us',
			'map_alt'           => 'Illustrative map showing Brookfield Trade Park on the south side of Northampton',
			'map_caption'       => 'Brookfield Trade Park, Northampton NN4 0ZZ. Visits by appointment.',
			'meta_description'  => 'Call 01632 960 418 or email hello@boxes.junaid.guru, Monday to Friday 8am to 5pm. Boxes, Unit 4, Brookfield Trade Park, Northampton NN4 0ZZ.',
		],
		'title' => 'Contact us',
	],
	'journal'         => [
		'meta'  => [
			'lead'             => 'Practical guides from our customer team and packaging technologist: how to choose sizes, cut waste, keep within the rules and pack orders faster. Written for small businesses, with no jargon we have not explained.',
			'meta_description' => 'Practical packaging guides for small businesses: box sizes, plastic-free mailing, food packaging rules for cafés and packing bench set-up.',
		],
		'title' => 'Journal',
	],
	'privacy'         => [
		'content' => <<<'HTML'
			<h2>Who we are</h2>
			<p>Boxes Packaging Ltd ("Boxes", "we", "us") is the controller of personal data collected through this website. We are registered in England and Wales, company no. 00000000, at Unit 4, Brookfield Trade Park, Northampton NN4 0ZZ. You can contact us about privacy at hello@boxes.junaid.guru or on 01632 960 418.</p>
			<h2>What we collect</h2>
			<ul>
			    <li><strong>Orders and trade accounts:</strong> your name, company, job title, email address, phone number, delivery and billing addresses, purchase order references and order history.</li>
			    <li><strong>Enquiries:</strong> the details you give us in the contact, custom printing and bespoke box forms, and any files you attach.</li>
			    <li><strong>Credit checks:</strong> for trade account applications, information about your business from a credit reference agency.</li>
			    <li><strong>Your basket:</strong> the products and quantities in your basket are kept in your own browser's local storage, not sent to us, until you place an order.</li>
			</ul>
			<p>We do not collect special category data, and we do not knowingly collect data from children.</p>
			<h2>Why we use it and our lawful basis</h2>
			<ul>
			    <li>To process and deliver orders, handle returns and manage trade accounts: <strong>performance of a contract</strong>.</li>
			    <li>To keep accounting and tax records: <strong>legal obligation</strong>.</li>
			    <li>To answer enquiries, carry out credit checks and prevent fraud: <strong>legitimate interests</strong> in running our business.</li>
			    <li>To send occasional news about new ranges to trade customers: <strong>legitimate interests</strong>, with an unsubscribe link in every email. We never sell your details.</li>
			</ul>
			<h2>Who we share it with</h2>
			<p>We share only what is needed with our courier partner (to deliver your order), our payment provider (to take payment), a credit reference agency (for trade applications), our accountants, and the companies that host our website and email. Each acts under a written contract. Data is stored in the UK or in countries with UK adequacy regulations, or protected by approved contract terms.</p>
			<h2>How long we keep it</h2>
			<p>Order and invoice records are kept for six years after the end of the financial year, as required for tax. Enquiries that do not lead to an order are deleted after 12 months. Trade account records are kept for six years after the account is closed.</p>
			<h2>Cookies and similar technology</h2>
			<p>This website does not set cookies. Your basket is saved in your browser's local storage under the name <code>boxes-basket</code>, and you can clear it at any time by emptying your basket or clearing your browser's site data. Fonts are loaded from Google Fonts, which receives your IP address when the page loads.</p>
			<h2>Your rights</h2>
			<p>You have the right to ask for a copy of your data, to have it corrected or deleted, to object to or restrict how we use it, and to ask for it to be transferred. Email hello@boxes.junaid.guru and we will reply within one month. If you are not happy with our response, you can complain to the Information Commissioner's Office at ico.org.uk.</p>
			HTML,
		'meta'    => [
			'last_updated'     => '2026-09-01',
			'meta_description' => 'How Boxes would collect, use, share and keep personal data for orders, trade accounts and enquiries, and your rights. A demonstration privacy notice.',
			'notice_text'      => 'Boxes is a fictional company and this is a demonstration website. The forms on this site do not send or store anything, and no one at Boxes will contact you. This notice shows how a real business of this kind would explain its use of personal data.',
			'notice_title'     => 'This is a demonstration website',
		],
		'title'   => 'Privacy notice',
	],
	'terms'           => [
		'content' => <<<'HTML'
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
			HTML,
		'meta'    => [
			'last_updated'     => '2026-09-01',
			'meta_description' => 'Example business-to-business terms of sale for Boxes, a fictional packaging supplier: prices, orders, payment, delivery, returns and liability.',
			'notice_text'      => 'Boxes is a fictional company and this is a demonstration website. These terms are an example of how a UK business-to-business packaging supplier might set out its terms of sale. No contract is formed through this site, and no goods are sold or delivered.',
			'notice_title'     => 'This is a demonstration website',
		],
		'title'   => 'Terms of sale',
	],
	'accessibility'   => [
		'content' => <<<'HTML'
			<p>This statement covers the Boxes demonstration website at boxes.junaid.guru.</p>
			<h2>Our aim</h2>
			<p>We want everyone to be able to browse the range, compare prices and contact us, whatever device or assistive technology they use. The site is built to meet the Web Content Accessibility Guidelines (WCAG) 2.2 at level AA.</p>
			<h2>What you should be able to do</h2>
			<ul>
			    <li>Navigate the whole site using a keyboard alone, with a visible focus indicator.</li>
			    <li>Use the site with a screen reader, with headings, landmarks and labelled form fields.</li>
			    <li>Zoom text up to 400% without content being lost or needing to scroll sideways.</li>
			    <li>Understand stock levels without relying on colour, because every status has an icon and words.</li>
			    <li>Read prices clearly: wherever we write "ex VAT", screen readers hear "excluding VAT".</li>
			    <li>Use the home page carousel without anything moving on its own, and with reduced motion if you have asked your device for it.</li>
			</ul>
			<h2>How we test</h2>
			<p>We check every page with automated tools against WCAG 2.2 AA, validate the HTML, and test by hand with a keyboard and a screen reader in a current desktop browser.</p>
			<h2>Known limitations</h2>
			<p>We are not aware of any parts of the site that fail WCAG 2.2 AA. The basket and the search page need JavaScript to work. Without JavaScript, every product, price and the full price list can still be read, and the basket page explains how to order by phone or email.</p>
			<h2>Contact us about accessibility</h2>
			<p>If you find something that is hard to use, or you need information in a different format, tell us and we will reply within 5 working days.</p>
			<ul>
			    <li>Email: hello@boxes.junaid.guru</li>
			    <li>Phone: 01632 960 418, Monday to Friday, 8am to 5pm</li>
			    <li>Post: Boxes Packaging Ltd, Unit 4, Brookfield Trade Park, Northampton NN4 0ZZ</li>
			</ul>
			HTML,
		'meta'    => [
			'last_updated'     => '2026-10-01',
			'meta_description' => 'How the Boxes website aims to meet WCAG 2.2 AA, how we test it, known limitations and how to contact us about accessibility.',
		],
		'title'   => 'Accessibility statement',
	],
];

$page_ids      = [];
$created_pages = 0;

foreach ( $pages as $slug => $page ) {
	[ $page_ids[ $slug ], $created ] = boxes_seed_post( 'page', $slug, [
		'post_content' => $page['content'] ?? '',
		'post_title'   => $page['title'],
	], $page['meta'], $img[ $page['thumbnail'] ?? '' ] ?? 0 );

	$created_pages += $created;
}

echo "pages: {$created_pages} created, " . count( $page_ids ) . " total\n";

$options = [
	'address'                    => "Unit 4, Brookfield Trade Park\nNorthampton\nNN4 0ZZ",
	'article_note'               => 'This article is general guidance for small businesses. It is not legal advice, and products mentioned are part of a fictional demonstration shop.',
	'benefits'                   => [
		[ 'icon' => 'clock', 'line' => 'Order by 2pm on a working day and mainland UK orders arrive tomorrow.', 'title' => 'Next-working-day delivery' ],
		[ 'icon' => 'truck', 'line' => 'Standard delivery is £7.95 ex VAT on smaller orders.', 'title' => 'Free delivery over £150' ],
		[ 'icon' => 'user', 'line' => '30-day terms for approved businesses, with one person to call.', 'title' => 'Trade accounts' ],
		[ 'icon' => 'leaf', 'line' => 'Most of the range has recycled content and goes in kerbside recycling.', 'title' => 'Recycled and recyclable' ],
	],
	'company_name'               => 'Boxes',
	'company_number'             => '00000000',
	'delivery_charge'            => 7.95,
	'delivery_cutoff'            => 'Order before 2pm on a working day for same-day dispatch.',
	'delivery_free_over'         => 150,
	'delivery_regions'           => 'Mainland UK next working day; Highlands, islands and Northern Ireland 2–3 working days.',
	'email'                      => 'hello@boxes.junaid.guru',
	'footer_notice'              => 'Boxes is a fictional company created for this demonstration website. Products, prices and stock levels are illustrative; no orders are taken or fulfilled.',
	'form_note'                  => 'This is a demonstration website. Please do not enter real personal or business details.',
	'hero_slides'                => [
		[ 'button_label' => 'Shop all packaging', 'button_url' => '/shop/', 'headline' => 'Packaging for small businesses, in packs', 'image' => $img['hero-slide-1.jpg'], 'lead' => 'Boxes, mailers, bags and tape in sensible pack sizes, with bulk prices that drop as you order more.' ],
		[ 'button_label' => 'See new arrivals', 'button_url' => '/collections/new-arrivals/', 'headline' => 'Plastic-free mailing that still protects', 'image' => $img['hero-slide-2.jpg'], 'lead' => 'Honeycomb paper mailers, kraft tape and paper wrap that go straight into the recycling with the box.' ],
		[ 'button_label' => 'Shop food packaging', 'button_url' => '/shop/food-packaging/', 'headline' => 'Café and bakery packaging, ready tomorrow', 'image' => $img['hero-slide-3.jpg'], 'lead' => 'Cups, food boxes and bakery bags dispatched the same day when you order before 2pm.' ],
	],
	'hours'                      => 'Monday to Friday, 8am to 5pm',
	'legal_name'                 => 'Boxes Packaging Ltd',
	'orders_email'               => 'orders@boxes.junaid.guru',
	'phone'                      => '01632 960 418',
	'phone_href'                 => 'tel:+441632960418',
	'product_delivery_text'      => 'Orders placed before 2pm on a working day are dispatched the same day. Standard delivery is £7.95 ex VAT, and free on orders over £150 ex VAT. Mainland UK deliveries arrive the next working day; the Highlands, islands and Northern Ireland take 2&ndash;3 working days. Unopened packs can be returned within 30 days. Read the full <a href="/delivery/">delivery and returns policy</a>.',
	'product_made_to_order_text' => 'This item is assembled to order and dispatched within 5 working days. Made-to-order items cannot be returned unless they arrive damaged or faulty.',
	'promo_tiles'                => [
		[ 'image' => $img['promo-new-arrivals.jpg'], 'line' => 'Plastic-free and recycled lines, new this season.', 'title' => 'New arrivals', 'url' => '/collections/new-arrivals/' ],
		[ 'image' => $img['promo-best-sellers.jpg'], 'line' => 'The staples our customers reorder every month.', 'title' => 'Best sellers', 'url' => '/collections/best-sellers/' ],
	],
	'teaser_heading'             => 'Less plastic, less air, fewer vans',
	'teaser_image'               => $img['teaser-sustainability.jpg'],
	'teaser_link_label'          => 'Read about our approach to sustainability',
	'teaser_link_url'            => '/sustainability/',
	'teaser_text'                => 'Most of what we sell is paper and board, much of it with recycled content, and we label clearly how each item can be recycled. Where plastic is still the right tool, we stock versions made with recycled material. We also help you choose box sizes that waste less space, and we take back pallets and clean cardboard from local trade customers.',
	'vat_number'                 => 'GB 000 0000 00',
	'vat_rate'                   => 20,
];

foreach ( $options as $name => $value ) {
	update_option( $name, $value );
}

echo 'options: ' . count( $options ) . " shop settings set\n";

$category_links = array_map( fn( $category ) => [ $category['name'], "/shop/{$category['slug']}/" ], $catalogue['categories'] );

$menus = [
	'utility'    => [ 'Utility', [
		[ 'About', '/about/' ],
		[ 'Sustainability', '/sustainability/' ],
		[ 'Delivery & returns', '/delivery/' ],
		[ 'Journal', '/journal/' ],
		[ 'Contact', '/contact/' ],
	] ],
	'categories' => [ 'Shop categories', [ ...$category_links, [ 'Shop all packaging', '/shop/' ] ] ],
	'shop'       => [ 'Footer shop', $category_links ],
	'help'       => [ 'Footer help', [
		[ 'Delivery & returns', '/delivery/' ],
		[ 'Trade accounts', '/trade-accounts/' ],
		[ 'Price list', '/price-list/' ],
		[ 'Contact us', '/contact/' ],
	] ],
	'company'    => [ 'Footer company', [
		[ 'About Boxes', '/about/' ],
		[ 'Sustainability', '/sustainability/' ],
		[ 'Journal', '/journal/' ],
		[ 'Custom printing', '/custom-printing/' ],
		[ 'Bespoke boxes', '/bespoke-boxes/' ],
	] ],
	'legal'      => [ 'Footer legal', [
		[ 'Privacy', '/privacy/' ],
		[ 'Terms of sale', '/terms/' ],
		[ 'Accessibility', '/accessibility/' ],
	] ],
];

$locations     = get_theme_mod( 'nav_menu_locations', [] );
$created_items = 0;

foreach ( $menus as $location => [ $name, $items ] ) {
	[ $locations[ $location ], $created ] = boxes_seed_menu( $name, $items );
	$created_items += $created;
}

set_theme_mod( 'nav_menu_locations', $locations );

echo "menus: {$created_items} created, " . count( $menus ) . " menus assigned to locations\n";

update_option( 'page_for_posts', $page_ids['journal'] );
update_option( 'page_on_front', $page_ids['home'] );
update_option( 'show_on_front', 'page' );
update_option( 'wp_page_for_privacy_policy', $page_ids['privacy'] );

$GLOBALS['wp_rewrite']->set_permalink_structure( '/journal/%postname%/' );
flush_rewrite_rules( false );

echo "reading: front page, posts page and permalinks set, rewrite rules flushed\n";

foreach ( [ [ 'post', 'hello-world' ], [ 'page', 'sample-page' ], [ 'page', 'privacy-policy' ] ] as [ $default_type, $default_slug ] ) {
	foreach ( get_posts( [ 'name' => $default_slug, 'numberposts' => 1, 'post_status' => [ 'draft', 'publish' ], 'post_type' => $default_type ] ) as $default_post ) {
		wp_delete_post( $default_post->ID, true );
	}
}

echo "defaults: removed\n";
echo "SEED COMPLETE\n";
