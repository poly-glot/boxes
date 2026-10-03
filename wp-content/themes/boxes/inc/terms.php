<?php

const BOXES_TERM_FIELDS = array(
	'intro'       => array(
		'label' => 'Intro',
		'type'  => 'string',
	),
	'description' => array(
		'label' => 'Meta description',
		'type'  => 'string',
	),
	'position'    => array(
		'label' => 'Position',
		'type'  => 'integer',
	),
);

const BOXES_TERM_NONCE = 'boxes_term_fields';

function boxes_register_term_meta(): void {
	foreach ( BOXES_SHOP_TAXONOMIES as $taxonomy ) {
		foreach ( BOXES_TERM_FIELDS as $key => $field ) {
			register_term_meta( $taxonomy, $key, array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => $field['type'],
			) );
		}

		add_action( $taxonomy . '_add_form_fields', 'boxes_term_add_fields' );
		add_action( $taxonomy . '_edit_form_fields', 'boxes_term_edit_fields' );
		add_action( 'created_' . $taxonomy, 'boxes_save_term_fields' );
		add_action( 'edited_' . $taxonomy, 'boxes_save_term_fields' );
	}
}

add_action( 'init', 'boxes_register_term_meta' );

function boxes_term_input( string $key, string $value ): string {
	$name = 'boxes_term_' . $key;

	if ( 'integer' === BOXES_TERM_FIELDS[ $key ]['type'] ) {
		return sprintf( '<input id="%1$s" name="%1$s" type="number" min="0" step="1" value="%2$s">', esc_attr( $name ), esc_attr( $value ) );
	}

	return sprintf( '<textarea id="%1$s" name="%1$s" rows="3">%2$s</textarea>', esc_attr( $name ), esc_textarea( $value ) );
}

function boxes_term_add_fields(): void {
	wp_nonce_field( BOXES_TERM_NONCE, BOXES_TERM_NONCE );

	foreach ( BOXES_TERM_FIELDS as $key => $field ) {
		printf(
			'<div class="form-field"><label for="%s">%s</label>%s</div>',
			esc_attr( 'boxes_term_' . $key ),
			esc_html( $field['label'] ),
			boxes_term_input( $key, '' )
		);
	}
}

function boxes_term_edit_fields( WP_Term $term ): void {
	wp_nonce_field( BOXES_TERM_NONCE, BOXES_TERM_NONCE );

	foreach ( BOXES_TERM_FIELDS as $key => $field ) {
		printf(
			'<tr class="form-field"><th scope="row"><label for="%s">%s</label></th><td>%s</td></tr>',
			esc_attr( 'boxes_term_' . $key ),
			esc_html( $field['label'] ),
			boxes_term_input( $key, boxes_term_text( $key, $term->term_id ) )
		);
	}
}

function boxes_save_term_fields( int $term_id ): void {
	$nonce = sanitize_text_field( wp_unslash( (string) ( $_POST[ BOXES_TERM_NONCE ] ?? '' ) ) );

	if ( ! wp_verify_nonce( $nonce, BOXES_TERM_NONCE ) || ! current_user_can( 'edit_term', $term_id ) ) {
		return;
	}

	foreach ( BOXES_TERM_FIELDS as $key => $field ) {
		$raw   = wp_unslash( (string) ( $_POST[ 'boxes_term_' . $key ] ?? '' ) );
		$value = 'integer' === $field['type'] ? absint( $raw ) : sanitize_textarea_field( $raw );

		update_term_meta( $term_id, $key, $value );
	}
}
