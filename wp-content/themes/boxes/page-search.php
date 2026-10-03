<?php
get_header();

get_template_part( 'template-parts/page-hero', null, boxes_hero_args( get_queried_object(), true ) );
?>
<div class="search-page container">
	<?php get_template_part( 'template-parts/search-form', null, array( 'id' => 'search-page-input', 'label' => 'Search again' ) ); ?>
	<section class="search-results" aria-labelledby="results-title" data-search>
		<h2 class="search-results__title" id="results-title" tabindex="-1">Results</h2>
		<p class="search-results__summary" role="status" data-search-summary></p>
		<div class="search-results__fallback" data-search-fallback>
			<p data-search-nojs>Search needs JavaScript in this demonstration. You can browse every product from the category list below, or see the full <a href="<?php echo esc_url( boxes_page_url( 'price-list' ) ); ?>">price list</a>.</p>
			<ul class="search-results__categories">
				<?php foreach ( boxes_terms( 'product_category' ) as $category ) : ?>
					<li><a href="<?php echo esc_url( boxes_term_url( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<ol class="search-results__list" data-search-list></ol>
		<template data-search-result>
			<li class="result">
				<img class="result__image" src="<?php echo esc_url( get_theme_file_uri( 'assets/logo.svg' ) ); ?>" width="400" height="400" loading="lazy" alt="" data-result-image>
				<div class="result__body">
					<p class="result__type" data-result-type></p>
					<h3 class="result__title"><a class="result__link" href="<?php echo esc_url( home_url( '/' ) ); ?>" data-result-link>Result</a></h3>
					<p class="result__meta" data-result-meta></p>
					<p class="result__excerpt" data-result-excerpt></p>
				</div>
			</li>
		</template>
	</section>
</div>
<?php
get_footer();
