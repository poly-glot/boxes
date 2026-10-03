<?php
$field    = $args['field'];
$field_id = $field['id'];
$type     = $field['type'] ?? 'text';
$required = ! empty( $field['required'] );
$hint     = $field['hint'] ?? '';
$error    = $field['error'] ?? '';

if ( 'group' === $type ) :
	?>
	<fieldset class="fieldset form-grid__full" aria-describedby="<?php echo esc_attr( $field_id . '-hint' ); ?>">
		<legend class="fieldset__legend"><?php echo esc_html( $field['legend'] ); ?><?php echo boxes_marker( false ); ?></legend>
		<p class="field__hint" id="<?php echo esc_attr( $field_id . '-hint' ); ?>"><?php echo esc_html( $hint ); ?></p>
		<div class="form-grid form-grid--thirds">
			<?php foreach ( $field['fields'] as $child ) : ?>
				<?php get_template_part( 'template-parts/form-field', null, array( 'field' => $child ) ); ?>
			<?php endforeach; ?>
		</div>
	</fieldset>
	<?php
	return;
endif;

if ( 'choices' === $type ) :
	?>
	<fieldset class="fieldset form-grid__full">
		<legend class="fieldset__legend"><?php echo esc_html( $field['label'] ); ?><?php echo boxes_marker( false ); ?></legend>
		<?php foreach ( $field['options'] as $index => $option ) : ?>
			<div class="check">
				<input class="check__input" id="<?php echo esc_attr( $field_id . '-' . ( $index + 1 ) ); ?>" type="radio" name="<?php echo esc_attr( boxes_field_name( $field_id ) ); ?>" value="<?php echo esc_attr( $option ); ?>">
				<label class="check__label" for="<?php echo esc_attr( $field_id . '-' . ( $index + 1 ) ); ?>"><?php echo esc_html( $option ); ?></label>
			</div>
		<?php endforeach; ?>
	</fieldset>
	<?php
	return;
endif;

if ( 'consent' === $type ) :
	?>
	<div class="check form-grid__full">
		<input class="check__input" id="<?php echo esc_attr( $field_id ); ?>" type="checkbox" name="<?php echo esc_attr( boxes_field_name( $field_id ) ); ?>" required aria-describedby="<?php echo esc_attr( $field_id . '-error' ); ?>">
		<label class="check__label" for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_html( $field['label'] ); ?><?php echo boxes_marker( true ); ?></label>
		<p class="field__error" id="<?php echo esc_attr( $field_id . '-error' ); ?>"><?php echo esc_html( $error ); ?></p>
	</div>
	<?php
	return;
endif;

$described = trim( ( '' === $hint ? '' : $field_id . '-hint' ) . ' ' . ( '' === $error ? '' : $field_id . '-error' ) );
$is_wide   = ! empty( $field['full'] ) || 'textarea' === $type;
$is_input  = ! in_array( $type, array( 'textarea', 'select' ), true );
$control   = array_merge(
	array(
		'class'        => 'field__control',
		'id'           => $field_id,
		'name'         => boxes_field_name( $field_id ),
		'type'         => $is_input ? $type : null,
		'autocomplete' => $is_input ? ( $field['autocomplete'] ?? null ) : null,
		'required'     => $required,
	),
	$field['attributes'] ?? array(),
	array( 'aria-describedby' => '' === $described ? null : $described )
);
?>
<div class="<?php echo esc_attr( $is_wide ? 'field form-grid__full' : 'field' ); ?>">
	<label class="field__label" for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_html( $field['label'] ); ?><?php echo boxes_marker( $required ); ?></label>
	<?php if ( '' !== $hint ) : ?>
		<p class="field__hint" id="<?php echo esc_attr( $field_id . '-hint' ); ?>"><?php echo esc_html( $hint ); ?></p>
	<?php endif; ?>
	<?php if ( 'textarea' === $type ) : ?>
		<textarea<?php echo boxes_attributes( $control ); ?>></textarea>
	<?php elseif ( 'select' === $type ) : ?>
		<select<?php echo boxes_attributes( $control ); ?>><option value="">Choose an option</option><?php foreach ( $field['options'] as $option ) : ?><option><?php echo esc_html( $option ); ?></option><?php endforeach; ?></select>
	<?php else : ?>
		<input<?php echo boxes_attributes( $control ); ?>>
	<?php endif; ?>
	<?php if ( '' !== $error ) : ?>
		<p class="field__error" id="<?php echo esc_attr( $field_id . '-error' ); ?>"><?php echo esc_html( $error ); ?></p>
	<?php endif; ?>
</div>
