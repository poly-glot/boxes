<?php
$prefix = $args['form'];
$form   = BOXES_FORMS[ $prefix ];
?>
<section class="form-panel" aria-labelledby="<?php echo esc_attr( $prefix . '-title' ); ?>">
	<h2 class="form-panel__title" id="<?php echo esc_attr( $prefix . '-title' ); ?>"><?php echo esc_html( $args['title'] ); ?></h2>
	<form class="form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post"<?php echo boxes_sent() ? ' hidden' : ''; ?>>
		<?php get_template_part( 'template-parts/form-security', null, array( 'action' => 'boxes_enquiry', 'form' => $prefix ) ); ?>
		<div class="form-grid">
			<?php foreach ( $form['fields'] as $field ) : ?>
				<?php get_template_part( 'template-parts/form-field', null, array( 'field' => $field ) ); ?>
			<?php endforeach; ?>
		</div>
		<?php get_template_part( 'template-parts/honeypot', null, array( 'prefix' => $prefix ) ); ?>
		<div class="form__actions"><button class="button button--action" type="submit"><?php echo esc_html( $form['submit'] ); ?></button></div>
		<p class="form__note"><?php echo esc_html( boxes_option( 'form_note' ) ); ?></p>
	</form>
	<?php get_template_part( 'template-parts/confirmation', null, array( 'prefix' => $prefix ) ); ?>
</section>
