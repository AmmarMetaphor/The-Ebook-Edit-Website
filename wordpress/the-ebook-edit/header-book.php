<?php
/**
 * Document head and the opening of the book stage.
 *
 * The website has no navigation bar: the book's own chapter tabs are the
 * primary navigation, and they are part of each page template. This file
 * therefore only opens the document and the <main> landmark.
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
<?php teebe_boot_script(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to main content', 'the-ebook-edit' ); ?></a>
<main id="main">
