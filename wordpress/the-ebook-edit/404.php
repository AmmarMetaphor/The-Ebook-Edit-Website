<?php
/**
 * 404 — generated from the static site by wordpress/sync-from-static.py.
 * Edit the static page and re-run the script; do not hand-edit this file.
 *
 * @package the-ebook-edit
 */

get_header();
?>

<div class="book-experience book-static">
  <div class="book-stage">
    <nav class="book-tabs" aria-label="Primary navigation">
          <a class="book-tab" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Services</a>
          <a class="book-tab" href="<?php echo esc_url( home_url( '/process/' ) ); ?>">Process</a>
          <a class="book-tab" href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>">Portfolio</a>
          <a class="book-tab" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
          <a class="book-tab" href="<?php echo esc_url( home_url( '/insights/' ) ); ?>">Insights</a>
          <a class="book-tab book-tab-cta" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Start a project</a>
        </nav>
    <div class="book-block">
      <div class="title-page">
        <p><a class="bookplate" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="The Ebook Edit home"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/brand/the-ebook-edit-logo.webp' ) ); ?>" alt="The Ebook Edit" width="760" height="615"></a></p>
        <p class="eyebrow">404</p>
        <h1>This page could not be found.</h1>
        <p class="lead">The link may be outdated, or the page may have moved.</p>
      </div>
      <div class="closing-page">
        <div class="missing-slot">
          <span class="ghost-no" aria-hidden="true">404</span>
          <p class="entry-label">Missing chapter</p>
        </div>
        <div class="page-actions">
          <a class="button button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Go to homepage</a>
          <a class="button button-outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact us</a>
        </div>
        <p class="page-more"><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Explore the services →</a> · <a href="<?php echo esc_url( home_url( '/insights/' ) ); ?>">Read the insights →</a></p>
        <p class="micro-colophon">© <span data-year></span> The Ebook Edit · <a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>">Privacy</a> · <a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>">Terms</a></p>
      </div>
    </div>
  </div>
  <div class="book-endcap" aria-hidden="true"></div>
</div>

<?php
get_footer();
