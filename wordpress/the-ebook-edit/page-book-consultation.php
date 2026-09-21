<?php
/**
 * Book a Free Consultation — /book-consultation/.
 *
 * The booking calendar is the one approved HighLevel embed, rendered by
 * teebe_render_booking_calendar() so this page and /thank-you/ share a
 * single authoritative configuration.
 *
 * Ported from the approved design; hand-maintained from here.
 * wordpress/sync-from-static.py does not generate this file.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="site-view" id="book-consultation-view">
  <section class="page-hero center consult-hero">
    <div class="flow-wrap" aria-hidden="true"><div class="flow-line one"></div><div class="flow-line two"></div></div>
    <div class="container" style="position:relative;z-index:1">
      <div class="v-reveal">
        <div class="eyebrow">Free Consultation</div>
        <h1 class="v-h1" tabindex="-1">Let’s Talk About Your Book.</h1>
        <p class="v-lead">Choose a convenient time below to speak with our book consultant about your idea, manuscript or publishing goals.</p>
      </div>
    </div>
  </section>
  <section class="v-section tight consult-section">
    <div class="container">
      <div class="consult-card v-reveal">
        <div class="consult-card-head">
          <div class="consult-mark" aria-hidden="true">✒</div>
          <h2>Book Your Free Consultation</h2>
          <p>Select a date and time that works for you.</p>
        </div>
        <?php teebe_render_booking_calendar(); ?>
      </div>
    </div>
  </section>
</section>

<?php
get_footer();
