<form class="search-form" role="search" aria-label="<?php echo esc_attr( $args['label'] ); ?>" action="<?php echo esc_url( boxes_page_url( BOXES_SEARCH_PAGE ) ); ?>" method="get">
	<label class="visually-hidden" for="<?php echo esc_attr( $args['id'] ); ?>">Search by keyword or SKU</label>
	<div class="search-form__row">
		<input class="field__control search-form__input" id="<?php echo esc_attr( $args['id'] ); ?>" type="search" name="s" placeholder="Search by keyword or SKU" autocomplete="off">
		<button class="button" type="submit">Search</button>
	</div>
</form>
