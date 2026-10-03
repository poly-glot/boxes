<?php
$article = $args['post'];
$level   = (string) ( $args['level'] ?? 3 );
?>
<article class="article-card">
	<?php
	echo boxes_img( array(
		'class'   => 'article-card__image',
		'src'     => boxes_image_url( (int) get_post_thumbnail_id( $article ), 'boxes-product' ),
		'width'   => '800',
		'height'  => '450',
		'loading' => 'lazy',
		'alt'     => '',
	) );
	?>
	<div class="article-card__body">
		<h<?php echo esc_attr( $level ); ?> class="article-card__title"><a class="article-card__link" href="<?php echo esc_url( (string) get_permalink( $article ) ); ?>"><?php echo esc_html( $article->post_title ); ?></a></h<?php echo esc_attr( $level ); ?>>
		<p class="article-card__meta"><?php echo esc_html( boxes_text( 'author_name', $article->ID ) ); ?> <span aria-hidden="true">&middot;</span> <?php echo boxes_article_time( $article ); ?><?php echo boxes_reading_time( $article ); ?></p>
		<p class="article-card__excerpt"><?php echo esc_html( $article->post_excerpt ); ?></p>
	</div>
</article>
