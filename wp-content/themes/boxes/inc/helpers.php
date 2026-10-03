<?php

function boxes_meta( string $name, ?int $post_id = null ): mixed {
	return get_post_meta( $post_id ?? (int) get_the_ID(), $name, true );
}

function boxes_text( string $name, ?int $post_id = null ): string {
	$value = boxes_meta( $name, $post_id );

	return is_scalar( $value ) ? (string) $value : '';
}

function boxes_rows( string $name, ?int $post_id = null ): array {
	$value = boxes_meta( $name, $post_id );

	return is_array( $value ) ? array_values( $value ) : array();
}

function boxes_row_text( array $row, string $key ): string {
	$value = $row[ $key ] ?? '';

	return is_scalar( $value ) ? (string) $value : '';
}

function boxes_term_text( string $name, int $term_id ): string {
	$value = get_term_meta( $term_id, $name, true );

	return is_scalar( $value ) ? (string) $value : '';
}

function boxes_option( string $name, string $fallback = '' ): string {
	$value = get_option( $name, '' );

	return is_scalar( $value ) && '' !== (string) $value ? (string) $value : $fallback;
}

function boxes_option_rows( string $name ): array {
	$value = get_option( $name, array() );

	return is_array( $value ) ? array_values( $value ) : array();
}

function boxes_ids( mixed $value ): array {
	return array_values( array_filter( array_map( 'intval', (array) $value ) ) );
}

function boxes_url( string $value ): string {
	return str_starts_with( $value, '/' ) ? home_url( $value ) : $value;
}

function boxes_path( string $url ): string {
	$path = (string) wp_parse_url( $url, PHP_URL_PATH );

	return '' === $path ? '/' : trailingslashit( $path );
}

function boxes_pages_by_slug(): array {
	static $pages = null;

	$pages ??= array_column( boxes_query( array( 'post_type' => 'page', 'posts_per_page' => -1 ) ), null, 'post_name' );

	return $pages;
}

function boxes_page( string $path ): ?WP_Post {
	return boxes_pages_by_slug()[ $path ] ?? null;
}

function boxes_page_url( string $path ): string {
	$page = boxes_page( $path );

	return $page ? (string) get_permalink( $page ) : home_url( '/' . $path . '/' );
}

function boxes_page_id( string $path ): int {
	return boxes_page( $path )?->ID ?? 0;
}

function boxes_query( array $args ): array {
	$query = new WP_Query( $args + array(
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'post_status'         => 'publish',
	) );

	update_post_thumbnail_cache( $query );

	return $query->posts;
}

function boxes_posts_by_id( mixed $ids, string|array $post_type ): array {
	$ids = boxes_ids( $ids );

	if ( ! $ids ) {
		return array();
	}

	$posts = boxes_query( array(
		'orderby'        => 'post__in',
		'post__in'       => $ids,
		'post_type'      => $post_type,
		'posts_per_page' => count( $ids ),
	) );

	return array_combine( wp_list_pluck( $posts, 'ID' ), $posts );
}

function boxes_ordered( mixed $ids, string|array $post_type ): array {
	return array_values( boxes_posts_by_id( $ids, $post_type ) );
}

function boxes_attributes( array $attributes ): string {
	$markup = '';

	foreach ( $attributes as $name => $value ) {
		if ( null === $value || false === $value ) {
			continue;
		}

		$markup .= true === $value ? ' ' . $name : sprintf( ' %s="%s"', $name, esc_attr( (string) $value ) );
	}

	return $markup;
}

function boxes_img( array $attributes ): string {
	if ( empty( $attributes['src'] ) ) {
		return '';
	}

	return '<img' . boxes_attributes( $attributes ) . '>';
}

function boxes_image_url( int $attachment_id, string $size ): string {
	return $attachment_id ? (string) wp_get_attachment_image_url( $attachment_id, $size ) : '';
}

function boxes_image_alt( int $attachment_id ): string {
	return $attachment_id ? (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) : '';
}

function boxes_icon( string $name, string $class = '' ): string {
	return sprintf( '<svg class="%s" aria-hidden="true" focusable="false"><use href="#icon-%s" /></svg>', esc_attr( trim( 'icon ' . $class ) ), esc_attr( $name ) );
}

function boxes_money( float $amount ): string {
	return '£' . number_format( $amount, 2 );
}

function boxes_with_vat( float $amount, float $percent ): float {
	return round( $amount * ( 100 + $percent ) ) / 100;
}

function boxes_tax_label( string $short, string $long ): string {
	return sprintf( '<span aria-hidden="true">%s</span><span class="visually-hidden">%s</span>', esc_html( $short ), esc_html( $long ) );
}

function boxes_ex_vat(): string {
	return boxes_tax_label( 'ex VAT', 'excluding VAT' );
}

function boxes_inc_vat(): string {
	return boxes_tax_label( 'inc VAT', 'including VAT' );
}

function boxes_date( string $ymd, string $format = 'j F Y' ): string {
	$date = DateTimeImmutable::createFromFormat( '!Y-m-d', $ymd, wp_timezone() );

	return $date ? (string) wp_date( $format, $date->getTimestamp() ) : '';
}

function boxes_lines( string $text ): array {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\R/', $text ) ) ) );
}

function boxes_inline( string $html ): string {
	return wp_kses_post( trim( (string) preg_replace( '#</?p(\s[^>]*)?>#', '', $html ) ) );
}

function boxes_nbsp( string $text ): string {
	return str_replace( ' ', '&nbsp;', esc_html( $text ) );
}

function boxes_current_path(): string {
	static $path = null;

	$path ??= boxes_path( wp_unslash( (string) ( $_SERVER['REQUEST_URI'] ?? '/' ) ) );

	return $path;
}

function boxes_sent(): bool {
	return isset( $_GET['sent'] ) && '1' === sanitize_key( wp_unslash( (string) $_GET['sent'] ) );
}

function boxes_marker( bool $required ): string {
	return sprintf( ' <span class="field__marker">(%s)</span>', $required ? 'required' : 'optional' );
}

function boxes_field_name( string $field_id ): string {
	return substr( $field_id, strpos( $field_id, '-' ) + 1 );
}

function boxes_article_time( WP_Post $post ): string {
	return sprintf( '<time datetime="%s">%s</time>', esc_attr( get_the_date( 'Y-m-d', $post ) ), esc_html( get_the_date( 'j F Y', $post ) ) );
}

function boxes_reading_time( WP_Post $post ): string {
	$minutes = (int) boxes_text( 'reading_minutes', $post->ID );

	return $minutes ? sprintf( ' <span aria-hidden="true">&middot;</span> %d min read', $minutes ) : '';
}

function boxes_prime_attachments( array $ids ): void {
	$ids = boxes_ids( $ids );

	if ( $ids ) {
		_prime_post_caches( $ids, false, true );
	}
}

function boxes_plain( string $text ): string {
	return html_entity_decode( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
}
