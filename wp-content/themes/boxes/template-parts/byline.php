<?php
$article = $args['post'];
?>
<p class="byline"><span class="byline__author"><?php echo esc_html( boxes_text( 'author_name', $article->ID ) ); ?></span>, <?php echo esc_html( boxes_text( 'author_role', $article->ID ) ); ?> <span aria-hidden="true">&middot;</span> <?php echo boxes_article_time( $article ); ?><?php echo boxes_reading_time( $article ); ?></p>
