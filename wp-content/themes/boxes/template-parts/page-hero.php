<?php
$image_id  = (int) ( $args['image'] ?? 0 );
$image_url = boxes_image_url( $image_id, 'boxes-page' );
$lead      = (string) ( $args['lead'] ?? '' );
$modifiers = array_filter( array(
	empty( $args['plain'] ) ? '' : 'page-hero--plain',
	'' === $image_url ? '' : 'page-hero--with-image',
) );
?>
<header class="<?php echo esc_attr( implode( ' ', array_merge( array( 'page-hero' ), $modifiers ) ) ); ?>">
	<div class="page-hero__inner container">
		<div class="page-hero__text">
			<h1 class="page-hero__title"><?php echo esc_html( $args['title'] ); ?></h1>
			<?php if ( '' !== $lead ) : ?>
				<p class="page-hero__lead"><?php echo boxes_inline( $lead ); ?></p>
			<?php endif; ?>
		</div>
		<?php
		echo boxes_img( array(
			'class'  => 'page-hero__image',
			'src'    => $image_url,
			'width'  => '1600',
			'height' => '900',
			'alt'    => boxes_image_alt( $image_id ),
		) );
		?>
	</div>
</header>
