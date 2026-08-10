<?php
/**
 * Contact page. The page body holds the Contact Form 7 shortcode, which renders
 * inside the original form shell.
 *
 * @package the-ebook-edit
 */

get_header();

$teebe_form_markup = '';

if ( have_posts() ) {
	the_post();
	$teebe_form_markup = trim( get_the_content() );
}
?>

<section class="page-hero"><div class="container"><div class="breadcrumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> / Contact</div><p class="eyebrow">Start a project</p><h1>Tell us about the ebook you want to create or improve.</h1><p class="lead">The more context you provide, the easier it is to recommend the right scope, sequence, and next step.</p></div></section>
<section class="section"><div class="container split"><div><p class="eyebrow">Helpful information</p><h2>What to include in your inquiry</h2><ul class="service-list"><li>Your ebook topic, genre, and intended reader</li><li>Current manuscript stage and approximate word count</li><li>Services you think you need</li><li>Your desired completion or launch date</li><li>Any source material, brand requirements, or publishing platform (for example, Amazon KDP, Apple Books, Kobo, Google Play Books, Barnes &amp; Noble Press, or Draft2Digital)</li></ul><div class="notice"><strong>Contact details:</strong> Email <a href="mailto:info@theebookedit.com">info@theebookedit.com</a>. We work with authors and organizations across the UK and US, and we aim to reply within 24 hours.</div></div><div class="form-shell">
<?php
if ( '' !== $teebe_form_markup ) {
	the_content();
} else {
	echo '<div class="notice"><strong>Contact form not configured.</strong> Add the Contact Form 7 shortcode to this page in WordPress. The form configuration is in DEPLOYMENT.md inside the theme folder.</div>';
}
?>
</div></div></section>
<section class="section section-soft"><div class="container"><p class="eyebrow">Frequently asked questions</p><h2>Before you send the inquiry</h2><div class="faqs"><details><summary>Can I ask for help if I only have an idea?</summary><p>Yes. A writing or book-development project can begin with a concept, notes, interviews, or existing content rather than a complete draft.</p></details><details><summary>Do you guarantee sales or bestseller status?</summary><p>No. Editorial and publishing support can improve quality and readiness, but sales depend on many factors outside an editor’s control.</p></details><details><summary>Will you publish my ebook for me on Kindle or another platform?</summary><p>No. You keep control of your publishing accounts, rights, tax and payment details, and the final decision to publish. We prepare your files, metadata, and a practical plan for your chosen platform(s) — see <a href="<?php echo esc_url( home_url( '/publishing/' ) ); ?>">publishing support</a> for more detail.</p></details><details><summary>Will my material remain confidential?</summary><p>Confidentiality expectations, file handling, access, and any formal agreement should be confirmed before sensitive material is shared.</p></details></div></div></section>

<?php
get_footer();
