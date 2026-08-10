<?php
/**
 * Inquiry confirmation page.
 *
 * @package the-ebook-edit
 */

get_header();
?>

<section class="page-hero"><div class="container text-center"><p class="eyebrow">Inquiry received</p><h1>Thank you for sharing your project.</h1><p class="lead">Your message has been submitted. We aim to reply within 24 hours. If you need to reach us directly, email <a href="mailto:info@theebookedit.com">info@theebookedit.com</a>.</p><div class="button-row" style="justify-content:center"><a class="button button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Return home</a><a class="button button-outline" href="<?php echo esc_url( home_url( '/insights/' ) ); ?>">Read the insights</a></div></div></section>

<?php
get_footer();
