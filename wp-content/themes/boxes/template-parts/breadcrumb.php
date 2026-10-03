<?php
$crumbs = boxes_crumbs();

if ( ! $crumbs ) {
	return;
}

$last = count( $crumbs ) - 1;
?>
<nav class="breadcrumb container" aria-label="Breadcrumb">
	<ol class="breadcrumb__list">
		<li class="breadcrumb__item"><a class="breadcrumb__link breadcrumb__link--home" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo boxes_icon( 'home' ); ?><span class="visually-hidden">Home</span></a></li>
		<?php foreach ( $crumbs as $index => $crumb ) : ?>
			<?php if ( $index === $last ) : ?>
				<li class="breadcrumb__item"><span class="breadcrumb__current" aria-current="page"><?php echo esc_html( $crumb['label'] ); ?></span></li>
			<?php else : ?>
				<li class="breadcrumb__item"><a class="breadcrumb__link" href="<?php echo esc_url( $crumb['url'] ); ?>"><?php echo esc_html( $crumb['label'] ); ?></a></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ol>
</nav>
