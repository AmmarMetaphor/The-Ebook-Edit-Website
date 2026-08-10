<?php
/**
 * Not-found template.
 *
 * @package the-ebook-edit
 */

get_header();
?>

<section class="page-hero"><div class="container text-center"><p class="eyebrow">404</p><h1>This page could not be found.</h1><p class="lead">The link may be outdated, or the page may have moved.</p><div class="button-row" style="justify-content:center"><a class="button button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Go to homepage</a><a class="button button-outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact us</a></div></div></section>

<?php
get_footer();
