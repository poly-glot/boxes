<?php

add_action( 'admin_post_nopriv_boxes_enquiry', 'boxes_handle_enquiry' );
add_action( 'admin_post_boxes_enquiry', 'boxes_handle_enquiry' );
add_action( 'admin_post_nopriv_boxes_order', 'boxes_handle_order' );
add_action( 'admin_post_boxes_order', 'boxes_handle_order' );

function boxes_redirect( string $url ): never {
	wp_safe_redirect( $url, 303 );
	exit;
}

function boxes_posted(): array {
	return wp_unslash( $_POST );
}

function boxes_form_is_genuine( array $posted, string $action ): bool {
	$nonce = sanitize_text_field( (string) ( $posted['boxes_nonce'] ?? '' ) );

	return (bool) wp_verify_nonce( $nonce, $action );
}

function boxes_is_bot( array $posted ): bool {
	return '' !== trim( (string) ( $posted['website'] ?? '' ) );
}

function boxes_form_fields( array $fields ): array {
	$flat = array();

	foreach ( $fields as $field ) {
		$flat = array_merge( $flat, 'group' === ( $field['type'] ?? '' ) ? boxes_form_fields( $field['fields'] ) : array( $field ) );
	}

	return $flat;
}

function boxes_field_value( array $field, mixed $raw ): string {
	$type  = $field['type'] ?? 'text';
	$value = is_scalar( $raw ) ? trim( (string) $raw ) : '';

	return match ( $type ) {
		'email'             => sanitize_email( $value ),
		'textarea'          => sanitize_textarea_field( $value ),
		'select', 'choices' => in_array( $value, $field['options'], true ) ? $value : '',
		'consent'           => '' === $value ? '' : '1',
		'number'            => is_numeric( $value ) ? $value : '',
		'date'              => preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ? $value : '',
		'file'              => '',
		default             => sanitize_text_field( $value ),
	};
}

function boxes_form_values( array $fields, array $posted ): array {
	$values = array();

	foreach ( boxes_form_fields( $fields ) as $field ) {
		$name            = boxes_field_name( $field['id'] );
		$values[ $name ] = boxes_field_value( $field, $posted[ $name ] ?? '' );
	}

	return $values;
}

function boxes_form_is_complete( array $fields, array $values ): bool {
	foreach ( boxes_form_fields( $fields ) as $field ) {
		$value       = $values[ boxes_field_name( $field['id'] ) ] ?? '';
		$is_required = ! empty( $field['required'] ) || 'consent' === ( $field['type'] ?? '' );
		$is_email    = 'email' === ( $field['type'] ?? '' );

		if ( $is_required && '' === $value ) {
			return false;
		}

		if ( $is_email && '' !== $value && ! is_email( $value ) ) {
			return false;
		}
	}

	return true;
}

function boxes_form_return( string $form ): string {
	return boxes_page_url( BOXES_FORMS[ $form ]['page'] );
}

function boxes_form_sent( string $form ): string {
	return add_query_arg( 'sent', '1', boxes_form_return( $form ) ) . '#' . $form . '-confirmation';
}

function boxes_submission_title( string $label, string $who ): string {
	return sprintf( '%s from %s – %s', $label, $who, wp_date( 'j F Y, H:i' ) );
}

function boxes_answer_lines( array $fields, array $values ): string {
	$contact_keys = array_flip( BOXES_ENQUIRY_CONTACT_KEYS );
	$lines        = array();

	foreach ( boxes_form_fields( $fields ) as $field ) {
		$name  = boxes_field_name( $field['id'] );
		$value = $values[ $name ] ?? '';

		if ( '' === $value || isset( $contact_keys[ $name ] ) ) {
			continue;
		}

		$answer  = 'consent' === ( $field['type'] ?? '' ) ? 'Yes' : $value;
		$lines[] = $field['label'] . ': ' . $answer;
	}

	return implode( "\n", $lines );
}

function boxes_enquiry_meta( string $form, array $fields, array $values ): array {
	$contact = array_intersect_key( $values, array_flip( BOXES_ENQUIRY_CONTACT_KEYS ) );

	return array_merge( $contact, array(
		'fields' => boxes_answer_lines( $fields, $values ),
		'form'   => $form,
	) );
}

function boxes_handle_enquiry(): void {
	$posted = boxes_posted();
	$form   = sanitize_key( (string) ( $posted['form'] ?? '' ) );

	$is_enquiry = isset( BOXES_FORMS[ $form ] ) && 'order' !== $form;

	if ( ! $is_enquiry || ! boxes_form_is_genuine( $posted, 'boxes_enquiry' ) ) {
		boxes_redirect( home_url( '/' ) );
	}

	if ( boxes_is_bot( $posted ) ) {
		boxes_redirect( boxes_form_sent( $form ) );
	}

	$fields = BOXES_FORMS[ $form ]['fields'];
	$values = boxes_form_values( $fields, $posted );

	if ( ! boxes_form_is_complete( $fields, $values ) ) {
		boxes_redirect( boxes_form_return( $form ) . '#' . $form . '-title' );
	}

	wp_insert_post( wp_slash( array(
		'meta_input'  => boxes_enquiry_meta( $form, $fields, $values ),
		'post_status' => 'private',
		'post_title'  => boxes_submission_title( BOXES_FORMS[ $form ]['label'], $values['name'] ),
		'post_type'   => 'enquiry',
	) ) );

	boxes_redirect( boxes_form_sent( $form ) );
}

function boxes_posted_lines( mixed $json ): array {
	$decoded = is_string( $json ) ? json_decode( $json, true ) : null;
	$lines   = array();

	foreach ( is_array( $decoded ) ? $decoded : array() as $line ) {
		$sku      = strtoupper( (string) preg_replace( '/[^A-Za-z0-9-]/', '', (string) ( $line['sku'] ?? '' ) ) );
		$quantity = is_numeric( $line['qty'] ?? null ) ? (int) $line['qty'] : 0;

		if ( '' === $sku || $quantity < 1 ) {
			continue;
		}

		$lines[ $sku ] = boxes_clamp_quantity( ( $lines[ $sku ] ?? 0 ) + $quantity );
	}

	return $lines;
}

function boxes_clamp_quantity( int $quantity ): int {
	return min( max( $quantity, 1 ), BOXES_MAX_QUANTITY );
}

function boxes_unit_price( array $product, int $quantity ): float {
	$unit = (float) $product['price'];
	$best = -1;

	foreach ( $product['tiers'] as $tier ) {
		$min = (int) $tier['min'];

		if ( $min <= $quantity && $min > $best ) {
			$best = $min;
			$unit = (float) $tier['price'];
		}
	}

	return $unit;
}

function boxes_order_totals( array $lines, array $products_by_sku, array $rules ): array {
	$priced = array();

	foreach ( $lines as $sku => $quantity ) {
		$product = $products_by_sku[ $sku ] ?? null;

		if ( ! $product ) {
			continue;
		}

		$unit     = boxes_unit_price( $product, $quantity );
		$priced[] = array(
			'name'  => $product['name'],
			'qty'   => $quantity,
			'sku'   => $sku,
			'total' => round( $unit * $quantity, 2 ),
			'unit'  => $unit,
		);
	}

	$subtotal = round( array_sum( array_column( $priced, 'total' ) ), 2 );
	$is_free  = 0.0 === $subtotal || $subtotal >= $rules['free_from'];
	$delivery = $is_free ? 0.0 : (float) $rules['delivery'];
	$vat      = round( ( $subtotal + $delivery ) * $rules['vat_rate'], 2 );

	return array(
		'delivery' => $delivery,
		'lines'    => $priced,
		'subtotal' => $subtotal,
		'total'    => round( $subtotal + $delivery + $vat, 2 ),
		'vat'      => $vat,
	);
}

function boxes_handle_order(): void {
	$posted = boxes_posted();

	if ( ! boxes_form_is_genuine( $posted, 'boxes_order' ) ) {
		boxes_redirect( home_url( '/' ) );
	}

	if ( boxes_is_bot( $posted ) ) {
		boxes_redirect( boxes_form_sent( 'order' ) );
	}

	$fields = BOXES_FORMS['order']['fields'];
	$values = boxes_form_values( $fields, $posted );
	$lines  = boxes_posted_lines( $posted['lines'] ?? '' );
	$totals = boxes_order_totals( $lines, boxes_products_by_sku( array_keys( $lines ) ), boxes_shop_rules() );

	if ( ! $totals['lines'] || ! boxes_form_is_complete( $fields, $values ) ) {
		boxes_redirect( boxes_form_return( 'order' ) . '#order-title' );
	}

	wp_insert_post( wp_slash( array(
		'meta_input'  => array_merge( $values, array(
			'delivery' => $totals['delivery'],
			'lines'    => wp_json_encode( $totals['lines'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ),
			'subtotal' => $totals['subtotal'],
			'total'    => $totals['total'],
			'vat'      => $totals['vat'],
		) ),
		'post_status' => 'private',
		'post_title'  => boxes_submission_title( BOXES_FORMS['order']['label'], $values['company'] ),
		'post_type'   => 'order_request',
	) ) );

	boxes_redirect( boxes_form_sent( 'order' ) );
}
