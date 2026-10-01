<?php
/**
 * Document head, flow lines and the website header, shared by every page of
 * the approved website design.
 *
 * The Insights library still uses the earlier book presentation and opens its
 * own shell through get_header( 'book' ) — see header-book.php. The Meta Ads
 * landing page renders a complete document of its own, as it always has.
 *
 * The Thank You page is the one distraction-free page: it keeps the brand,
 * unlinked and centred, and drops the menu button and the page navigation, so a visitor
 * who has just enquired is offered the consultation rather than the rest of
 * the site. Its one way back is the Return to Home action below the
 * calendar. See teebe_site_is_distraction_free() in inc/site.php.
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
<?php if ( teebe_site_is_distraction_free() ) : ?>
    <span class="brand"><img data-asset="logo" src="<?php echo esc_url( teebe_site_image( 'logo' ) ); ?>" alt="The Ebook Edit"></span>
<?php else : ?>
    <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'The Ebook Edit home', 'the-ebook-edit' ); ?>"><img data-asset="logo" src="<?php echo esc_url( teebe_site_image( 'logo' ) ); ?>" alt="The Ebook Edit"></a>
    <button class="menu-btn" id="menuBtn" aria-label="<?php esc_attr_e( 'Open navigation', 'the-ebook-edit' ); ?>" aria-expanded="false">☰</button>
    <?php teebe_site_nav(); ?>
<?php endif; ?>
  </div>
</header>

<main id="main">
