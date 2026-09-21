<?php
/**
 * Document head, flow lines and the website header, shared by every page of
 * the approved website design.
 *
 * The Insights library still uses the earlier book presentation and opens its
 * own shell through get_header( 'book' ) — see header-book.php. The Meta Ads
 * landing page and its consultation thank-you page render complete documents
 * of their own, as they always have.
 *
 * @package the-ebook-edit
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to main content', 'the-ebook-edit' ); ?></a>
<div class="flow-wrap" aria-hidden="true">
  <div class="flow-line one"></div>
  <div class="flow-line two"></div>
</div>

<header>
  <div class="container header-inner">
    <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'The Ebook Edit home', 'the-ebook-edit' ); ?>"><img data-asset="logo" src="<?php echo esc_url( teebe_site_image( 'logo' ) ); ?>" alt="The Ebook Edit"></a>
    <button class="menu-btn" id="menuBtn" aria-label="<?php esc_attr_e( 'Open navigation', 'the-ebook-edit' ); ?>" aria-expanded="false">☰</button>
    <?php teebe_site_nav(); ?>
  </div>
</header>

<main id="main">
