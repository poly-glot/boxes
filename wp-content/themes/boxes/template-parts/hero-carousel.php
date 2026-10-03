<?php
$slides = $args['slides'];
$count  = count( $slides );
$sizes  = '(min-width: 77.5rem) 1240px, 100vw';
?>
<section class="hero-carousel" aria-label="Featured ranges" data-carousel>
	<div class="hero-carousel__inner container">
		<ul class="hero-carousel__track" data-carousel-track>
			<?php foreach ( $slides as $index => $slide ) : ?>
				<?php
				$image_id = (int) ( $slide['image'] ?? 0 );
				$srcset   = array_map( fn ( string $size, int $width ): string => boxes_image_url( $image_id, $size ) . ' ' . $width . 'w', array( 'boxes-hero-960', 'boxes-hero-1440', 'boxes-hero' ), array( 960, 1440, 1920 ) );
				?>
				<li class="hero-carousel__slide" id="slide-<?php echo esc_attr( (string) ( $index + 1 ) ); ?>" data-carousel-slide>
					<picture class="hero-carousel__picture">
						<?php
						echo boxes_img( array(
							'class'         => 'hero-carousel__image',
							'src'           => boxes_image_url( $image_id, 'boxes-hero-1440' ),
							'srcset'        => implode( ', ', $srcset ),
							'sizes'         => $sizes,
							'width'         => '1920',
							'height'        => '800',
							'fetchpriority' => 0 === $index ? 'high' : null,
							'loading'       => 0 === $index ? null : 'lazy',
							'alt'           => boxes_image_alt( $image_id ),
						) );
						?>
					</picture>
					<div class="hero-carousel__content">
						<h2 class="hero-carousel__title"><?php echo esc_html( boxes_row_text( $slide, 'headline' ) ); ?></h2>
						<p class="hero-carousel__lead"><?php echo esc_html( boxes_row_text( $slide, 'lead' ) ); ?></p>
						<a class="button button--action" href="<?php echo esc_url( boxes_url( boxes_row_text( $slide, 'button_url' ) ) ); ?>"><?php echo esc_html( boxes_row_text( $slide, 'button_label' ) ); ?></a>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="hero-carousel__controls">
			<button class="hero-carousel__arrow" type="button" data-carousel-previous hidden><?php echo boxes_icon( 'chevron-left' ); ?><span class="visually-hidden">Previous slide</span></button>
			<ol class="hero-carousel__dots">
				<?php for ( $number = 1; $number <= $count; $number++ ) : ?>
					<li><a class="hero-carousel__dot" href="#slide-<?php echo esc_attr( (string) $number ); ?>" data-carousel-dot><span class="visually-hidden"><?php echo esc_html( sprintf( 'Show slide %d of %d', $number, $count ) ); ?></span></a></li>
				<?php endfor; ?>
			</ol>
			<button class="hero-carousel__arrow" type="button" data-carousel-next hidden><?php echo boxes_icon( 'chevron-right' ); ?><span class="visually-hidden">Next slide</span></button>
		</div>
	</div>
</section>
