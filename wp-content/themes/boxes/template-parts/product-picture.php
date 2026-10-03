<?php
$product = $args['product'];
$eager   = ! empty( $args['eager'] );

echo boxes_img( array(
	'class'         => $args['class'],
	'src'           => $product['image']['large'],
	'srcset'        => sprintf( '%s 400w, %s 800w', $product['image']['small'], $product['image']['large'] ),
	'sizes'         => $args['sizes'],
	'width'         => '800',
	'height'        => '800',
	'loading'       => $eager ? null : 'lazy',
	'fetchpriority' => $eager ? 'high' : null,
	'alt'           => $args['alt'] ?? '',
) );
