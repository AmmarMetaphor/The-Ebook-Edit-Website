<?php
/**
 * Not found.
 *
 * Rendered in the website's shell with the approved design's own page-hero
 * and button classes, so a broken link still lands somewhere that looks
 * like the website and offers a way onward. Nothing new is designed here.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="site-view" id="not-found-view">
  <section class="page-hero center">
    <div class="flow-wrap" aria-hidden="true"><div class="flow-line one"></div><div class="flow-line two"></div></div>
    <div class="container" style="position:relative;z-index:1">
      <div class="v-reveal">
        <div class="eyebrow">404</div>
        <h1 class="v-h1" tabindex="-1"><?php esc_html_e( 'This page could not be found.', 'the-ebook-edit' ); ?></h1>
        <p class="v-lead"><?php esc_html_e( 'The link may be outdated, or the page may have moved.', 'the-ebook-edit' ); ?></p>
        <div class="actions">
          <a class="btn btn-gold" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to homepage', 'the-ebook-edit' ); ?></a>
          <a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact us', 'the-ebook-edit' ); ?></a>
        </div>
      </div>
    </div>
  </section>
  <section class="v-section tight">
    <div class="container">
      <div class="v-head center v-reveal">
        <h2><?php esc_html_e( 'Try one of these instead', 'the-ebook-edit' ); ?></h2>
      </div>
      <div class="actions v-reveal">
        <a class="btn btn-ghost btn-sm" href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Services', 'the-ebook-edit' ); ?></a>
        <a class="btn btn-ghost btn-sm" href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>"><?php esc_html_e( 'Portfolio', 'the-ebook-edit' ); ?></a>
        <a class="btn btn-ghost btn-sm" href="<?php echo esc_url( home_url( '/process/' ) ); ?>"><?php esc_html_e( 'Process', 'the-ebook-edit' ); ?></a>
        <a class="btn btn-ghost btn-sm" href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'the-ebook-edit' ); ?></a>
      </div>
    </div>
  </section>
</section>

<?php
get_footer();
