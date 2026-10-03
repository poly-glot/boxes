<?php
get_header();

$page_post    = get_queried_object();
$page_id      = $page_post instanceof WP_Post ? $page_post->ID : 0;
$heading      = $page_post instanceof WP_Post ? boxes_heading( $page_post ) : (string) get_bloginfo( 'description' );
$slides       = boxes_option_rows( 'hero_slides' );
$promos       = boxes_option_rows( 'promo_tiles' );
$teaser_image = (int) get_option( 'teaser_image' );
$best_sellers = boxes_collection_products( 'best-sellers', 4 );
$articles     = boxes_query( array( 'post_type' => 'post', 'posts_per_page' => 3 ) );
$collection   = get_term_by( 'slug', 'best-sellers', 'collection' );

boxes_prime_attachments( array_merge(
	array_column( $slides, 'image' ),
	array_column( $promos, 'image' ),
	array( $teaser_image ),
	array_map( fn ( string $path ): int => (int) get_post_thumbnail_id( boxes_page_id( $path ) ), BOXES_SERVICE_PAGES )
) );
?>
<h1 class="visually-hidden"><?php echo esc_html( $heading ); ?></h1>
<?php get_template_part( 'template-parts/hero-carousel', null, array( 'slides' => $slides ) ); ?>
<section class="section section--tight" aria-label="Collections">
	<ul class="promo-tiles container">
		<?php foreach ( $promos as $promo ) : ?>
			<li>
				<a class="promo-tile" href="<?php echo esc_url( boxes_url( boxes_row_text( $promo, 'url' ) ) ); ?>">
					<?php
					echo boxes_img( array(
						'class'   => 'promo-tile__image',
						'src'     => boxes_image_url( (int) ( $promo['image'] ?? 0 ), 'boxes-promo' ),
						'width'   => '1200',
						'height'  => '600',
						'loading' => 'lazy',
						'alt'     => '',
					) );
					?>
					<span class="promo-tile__text">
						<span class="promo-tile__label"><?php echo esc_html( boxes_row_text( $promo, 'title' ) ); ?></span>
						<span class="promo-tile__note"><?php echo esc_html( boxes_row_text( $promo, 'line' ) ); ?></span>
					</span>
					<?php echo boxes_icon( 'chevron-right', 'promo-tile__icon' ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
<section class="section section--tight" aria-labelledby="categories-title">
	<div class="container">
		<?php get_template_part( 'template-parts/section-head', null, array( 'id' => 'categories-title', 'title' => boxes_text( 'home_categories_title', $page_id ) ) ); ?>
		<?php get_template_part( 'template-parts/category-tiles' ); ?>
	</div>
</section>
<section class="benefits" aria-labelledby="benefits-title">
	<div class="container">
		<h2 class="visually-hidden" id="benefits-title"><?php echo esc_html( boxes_text( 'home_benefits_title', $page_id ) ); ?></h2>
		<?php get_template_part( 'template-parts/benefits-list', null, array( 'items' => boxes_option_rows( 'benefits' ), 'text_key' => 'line' ) ); ?>
	</div>
</section>
<section class="section" aria-labelledby="best-sellers-title">
	<div class="container">
		<?php
		get_template_part( 'template-parts/section-head', null, array(
			'id'         => 'best-sellers-title',
			'link_label' => boxes_text( 'home_best_sellers_link', $page_id ),
			'link_url'   => $collection instanceof WP_Term ? boxes_term_url( $collection ) : '',
			'title'      => boxes_text( 'home_best_sellers_title', $page_id ),
		) );
		get_template_part( 'template-parts/product-grid', null, array( 'prefix' => 'home', 'products' => $best_sellers, 'wide' => true ) );
		?>
	</div>
</section>
<section class="section band--surface" aria-labelledby="sustainability-title">
	<div class="split container">
		<?php
		echo boxes_img( array(
			'class'   => 'split__image',
			'src'     => boxes_image_url( $teaser_image, 'boxes-page' ),
			'width'   => '1600',
			'height'  => '900',
			'loading' => 'lazy',
			'alt'     => boxes_image_alt( $teaser_image ),
		) );
		?>
		<div class="split__text">
			<h2 class="split__title" id="sustainability-title"><?php echo esc_html( boxes_option( 'teaser_heading' ) ); ?></h2>
			<div class="prose">
				<?php foreach ( boxes_lines( boxes_option( 'teaser_text' ) ) as $paragraph ) : ?>
					<p><?php echo esc_html( $paragraph ); ?></p>
				<?php endforeach; ?>
			</div>
			<a class="button button--outline" href="<?php echo esc_url( boxes_url( boxes_option( 'teaser_link_url' ) ) ); ?>"><?php echo esc_html( boxes_option( 'teaser_link_label' ) ); ?></a>
		</div>
	</div>
</section>
<section class="section" aria-labelledby="journal-title">
	<div class="container">
		<?php
		get_template_part( 'template-parts/section-head', null, array(
			'id'         => 'journal-title',
			'link_label' => boxes_text( 'home_journal_link', $page_id ),
			'link_url'   => boxes_page_url( 'journal' ),
			'title'      => boxes_text( 'home_journal_title', $page_id ),
		) );
		get_template_part( 'template-parts/article-grid', null, array( 'posts' => $articles ) );
		?>
	</div>
</section>
<?php
get_footer();
