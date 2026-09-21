<?php
/**
 * Thank You — /thank-you/.
 *
 * The shared conversion endpoint for every flow: a delivered enquiry from
 * either website form, a delivered enquiry from the Meta Ads landing page,
 * and a confirmed booking returning from HighLevel. It therefore claims
 * nothing about what the visitor has already done, and the calendar is
 * offered rather than assumed.
 *
 * inc/site.php marks it noindex, follow. inc/analytics.php fires
 * appointment_booked here, once, only when HighLevel returns a confirmed
 * booking as ?conversion=appointment_booked — never on a plain visit.
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

<section class="site-view" id="thank-you-view">
  <section class="page-hero center thanks-hero">
    <div class="flow-wrap" aria-hidden="true"><div class="flow-line one"></div><div class="flow-line two"></div></div>
    <div class="container" style="position:relative;z-index:1">
      <div class="v-reveal">
        <div class="thanks-mark" aria-hidden="true">✓</div>
        <div class="eyebrow">Thank You</div>
        <h1 class="v-h1" tabindex="-1">You’re One Step Closer to Your Book</h1>
        <p class="v-lead">Thanks for taking the next step with The Ebook Edit. Whether you’ve shared your book details or booked a consultation directly, we look forward to learning more about your project and helping you identify the right next step.</p>
      </div>
    </div>
  </section>
  <section class="v-section tight consult-section">
    <div class="container">
      <div class="consult-card v-reveal">
        <div class="consult-card-head">
          <div class="consult-mark" aria-hidden="true">✒</div>
          <h2>Haven’t booked your consultation yet?</h2>
          <p>Choose a convenient time below to speak with our book consultant.</p>
        </div>
        <?php teebe_render_booking_calendar(); ?>
        <p class="thanks-note thanks-reassure">If your consultation is already scheduled, you’re all set. We look forward to speaking with you.</p>
      </div>
      <div class="actions thanks-actions"><a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/' ) ); ?>">Return to Home</a></div>
    </div>
  </section>
</section>

<?php
get_footer();
