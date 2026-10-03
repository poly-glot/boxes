<?php
get_header();

while ( have_posts() ) :
	the_post();

	$article  = get_post();
	$image_id = (int) get_post_thumbnail_id( $article );
	$others   = boxes_query( array(
		'post__not_in'   => array( $article->ID ),
		'post_type'      => 'post',
		'posts_per_page' => -1,
	) );
	?>
	<article class="article container">
		<header class="article__header">
			<h1 class="article__title"><?php echo esc_html( $article->post_title ); ?></h1>
			<p class="article__standfirst"><?php echo esc_html( $article->post_excerpt ); ?></p>
			<?php get_template_part( 'template-parts/byline', null, array( 'post' => $article ) ); ?>
		</header>
		<?php
		echo boxes_img( array(
			'class'  => 'article__image',
			'src'    => boxes_image_url( $image_id, 'boxes-product' ),
			'width'  => '800',
			'height' => '450',
			'alt'    => boxes_image_alt( $image_id ),
		) );
		?>
		<div class="article__body prose">
			<?php the_content(); ?>
		</div>
		<footer class="article__footer meta"><?php echo esc_html( boxes_option( 'article_note' ) ); ?></footer>
	</article>
	<?php if ( $others ) : ?>
		<section class="section band--surface" aria-labelledby="more-journal">
			<div class="container stack">
				<h2 id="more-journal">More from the journal</h2>
				<?php get_template_part( 'template-parts/article-grid', null, array( 'posts' => $others ) ); ?>
			</div>
		</section>
	<?php endif; ?>
	<?php
endwhile;

get_footer();
