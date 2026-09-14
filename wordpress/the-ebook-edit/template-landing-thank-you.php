<?php
/**
 * Template Name: The Ebook Edit — Consultation Thank You
 *
 * Where a visitor lands after the Meta Ads landing page's enquiry form has
 * really been delivered: the second step of that funnel, and the page on
 * which the consultation is actually booked.
 *
 * It follows the architecture template-landing-meta-ads.php established, for
 * the same reasons: it renders its own document rather than calling
 * get_header() and get_footer(), because the website's shell opens a
 * <main id="main"> landmark and loads the book stylesheets and the book
 * engine, and it would also put the website's navigation in front of a
 * visitor who is one click from booking. Everything WordPress itself needs
 * is still here — language_attributes(), wp_head(), body_class(),
 * wp_body_open() and wp_footer() — so plugins behave normally and the
 * calendar's own script loads.
 *
 * The design is the landing page's: this template loads the same
 * assets/css/landing.css and reuses its type, palette, panel and footer
 * classes, so the page reads as the next step in one funnel rather than a
 * different site. Only the calendar container is new, and those few rules
 * live at the end of that stylesheet.
 *
 * inc/landing.php marks this template noindex, nofollow — it is a
 * conversion endpoint, not a page that should be found in search.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
<div class="flow-wrap" aria-hidden="true">
  <div class="flow-line one"></div>
  <div class="flow-line two"></div>
</div>

<header>
  <div class="container header-inner">
    <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="The Ebook Edit home"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/the-ebook-edit-logo.webp' ) ); ?>" alt="The Ebook Edit"></a>
  </div>
</header>

<main id="main" class="ty-page">
  <section class="ip-hero">
    <div class="container">
      <div class="eyebrow">Thank you</div>
      <h1 class="ip-title" tabindex="-1">Your Consultation Is One Step Away.</h1>
      <p class="ip-intro">We’ve received your book details. Choose a convenient time below to speak with our consultant.</p>
    </div>
  </section>

  <section class="ip-body">
    <div class="container">
      <div class="ty-calendar-head">
        <h2>Book Your Free Consultation</h2>
        <p>Select a date and time that works for you.</p>
      </div>

      <div class="ip-panel ty-calendar">
        <?php
        /*
         * The booking calendar, exactly as supplied by HighLevel. Nothing is
         * injected into the third-party frame; only the panel around it is
         * styled, and the iframe is given a width and a minimum height so it
         * still fills the panel if the widget's own resize script is blocked.
         */
        ?>
        <iframe src="https://api.leadconnectorhq.com/widget/booking/XQxrNiP8LHrC16L5qr2t" allow="payment" style="width: 100%;border:none;overflow: hidden;" scrolling="no" id="XQxrNiP8LHrC16L5qr2t_1789374415925"></iframe><br><script src="https://link.msgsndr.com/js/form_embed.js" type="text/javascript"></script>
      </div>

      <p class="ty-fallback">Having trouble viewing the calendar? <a href="https://api.leadconnectorhq.com/widget/booking/XQxrNiP8LHrC16L5qr2t" target="_blank" rel="noopener noreferrer">Open the booking calendar</a>.</p>
    </div>
  </section>
</main>

<footer>
  <div class="container footer-inner">
    <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/the-ebook-edit-logo.webp' ) ); ?>" alt="The Ebook Edit">
    <div class="footer-note">
      <a href="mailto:support@theebookedit.com">support@theebookedit.com</a> · Professional ebook writing, editing, formatting and publishing support.
      <nav class="footer-links" aria-label="Company and legal information">
        <a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>">Privacy Policy</a>
        <a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>">Terms &amp; Conditions</a>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to The Ebook Edit</a>
      </nav>
    </div>
  </div>
</footer>

<a class="whatsapp" id="whatsapp" href="<?php echo esc_url( teebe_landing_whatsapp_url() ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Chat with The Ebook Edit on WhatsApp">
  <svg viewBox="0 0 32 32" fill="currentColor" aria-hidden="true"><path d="M16.1 3.2A12.6 12.6 0 0 0 5.4 22.4L3.7 28.8l6.6-1.7A12.6 12.6 0 1 0 16.1 3.2Zm0 22.9c-1.8 0-3.5-.5-5-1.3l-.4-.2-3.9 1 1-3.8-.3-.4A10.3 10.3 0 1 1 16.1 26Zm5.6-7.7c-.3-.2-1.8-.9-2.1-1-.3-.1-.5-.2-.7.2-.2.3-.8 1-.9 1.2-.2.2-.3.2-.7.1-1.8-.9-3-1.6-4.2-3.7-.3-.5.3-.5.9-1.6.1-.2 0-.4 0-.6l-1-2.4c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.2 1.2-1.2 2.9s1.3 3.4 1.4 3.6c.2.2 2.5 3.8 6 5.3 2.3 1 3.2 1.1 4.3.9.7-.1 1.8-.8 2.1-1.5.3-.7.3-1.3.2-1.5-.1-.2-.4-.3-.7-.4Z"/></svg>
  <span class="whatsapp-tip">Chat on WhatsApp</span>
</a>

<?php wp_footer(); ?>
</body>
</html>
