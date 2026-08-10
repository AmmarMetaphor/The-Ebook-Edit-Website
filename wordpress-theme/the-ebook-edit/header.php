<?php
/**
 * Site header and primary navigation.
 *
 * @package the-ebook-edit
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to main content</a>
<header class="site-header">
  <div class="container nav-wrap">
    <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="The Ebook Edit home">
      <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/the-ebook-edit-logo.png' ) ); ?>" alt="The Ebook Edit">
    </a>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-navigation">Menu</button>
    <nav class="site-nav" id="site-navigation" aria-label="Primary navigation">
      <?php teebe_primary_nav(); ?>
    </nav>
  </div>
</header>
<main id="main">
