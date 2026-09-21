<?php
/**
 * Contact — /contact/.
 *
 * The enquiry form is the approved HighLevel "Contact Page Enquiry"
 * embed, rendered inside the approved card by teebe_render_ghl_form() in
 * inc/site.php. The company panel, the office address and the contact
 * links beside it are unchanged.
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

<section class="site-view" id="contact-view">
<section class="page-hero contact-hero"><div class="flow-wrap" aria-hidden="true"><div class="flow-line one"></div><div class="flow-line two"></div></div><div class="container" style="position:relative;z-index:1"><div class="start-grid">
  <div class="start-aside v-reveal"><div class="eyebrow">Start your project</div><h1 class="v-h1" tabindex="-1">Tell Us Where Your Book<br>Is Today.</h1><p class="v-lead">Whether you have an idea, an outline, a manuscript or a published book that needs more support, start here.</p>
    <ul class="start-points"><li><span class="n">1</span><span>Share your details and where the book stands.</span></li><li><span class="n">2</span><span>We reply with the right next step, not a package.</span></li><li><span class="n">3</span><span>Scope, schedule and reviews are agreed before work begins.</span></li></ul>
    <div class="contact-ways"><a href="mailto:support@theebookedit.com"><span class="ico">✉</span>support@theebookedit.com</a><a href="<?php echo esc_url( teebe_site_whatsapp_url() ); ?>" target="_blank" rel="noopener noreferrer" data-whatsapp><span class="ico">☏</span>Chat on WhatsApp</a></div>
    <aside class="company-panel v-reveal" aria-labelledby="company-office-title">
      <div class="company-block">
        <h3 class="company-title" id="company-office-title">UK Company Office Address</h3>
        <address class="company-address">9 Cheriton Road<br>Leicester, England<br>LE2 8DE</address>
      </div>
      <div class="company-block">
        <h3 class="company-title">Company Information</h3>
        <p class="company-legal">The Ebook Edit is a trading name of <strong>Inovantage Limited</strong>. Registered in United Kingdom <strong>Company No. 14524243</strong>. <strong>Registered Office</strong>: 9 Cheriton Road, Leicester, England, LE2 8DE</p>
      </div>
    </aside>
    <div class="start-covers" aria-hidden="true"><img src="<?php echo esc_url( teebe_site_image( 'cover-4' ) ); ?>" data-asset="cover-4" alt="The Inner Compass book cover" decoding="async" ><img src="<?php echo esc_url( teebe_site_image( 'cover-2' ) ); ?>" data-asset="cover-2" alt="Mila and the Gentle Dino book cover" decoding="async" ><img src="<?php echo esc_url( teebe_site_image( 'cover-3' ) ); ?>" data-asset="cover-3" alt="The Ghost of Blackthorn Palace book cover" decoding="async" ></div></div>
  <div class="v-reveal" data-delay="1"><div class="lead-card lead-card-wide"><div class="form-icon" aria-hidden="true">✒</div><h2 class="lc-title">Let's Bring Your Book to Its Best</h2><p class="intro">Tell us about your project and we'll help identify the right next step.</p>
  <?php teebe_render_site_form( 'contact' ); ?></div></div>
</div></div></section><section class="v-section tight"><div class="container"><div class="t-single v-reveal"><div class="t-mark" aria-hidden="true">“</div><blockquote class="t-quote">The proofreading gave my manuscript the polish it needed, and the publishing support made the final stage feel clear and manageable. I was genuinely pleased with how everything came together.</blockquote><div class="t-client"><strong>Steve Elliott</strong><span>Author • Proofreading &amp; Publishing</span></div></div></div></section>
</section>

<?php
get_footer();
