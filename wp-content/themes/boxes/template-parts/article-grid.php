<ul class="article-grid">
	<?php foreach ( $args['posts'] as $article ) : ?>
		<li><?php get_template_part( 'template-parts/article-card', null, array( 'level' => $args['level'] ?? 3, 'post' => $article ) ); ?></li>
	<?php endforeach; ?>
</ul>
