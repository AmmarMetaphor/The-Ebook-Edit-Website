<?php
/**
 * Template Name: Insight — Kindle and Platforms
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
          <a class="book-tab" href="<?php echo esc_url( home_url( '/insights/' ) ); ?>" aria-current="page">Insights</a>
          <a class="book-tab book-tab-cta" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Start a project</a>
        </nav>
    <div class="book-block">
      <div class="title-page">
        <p><a class="bookplate" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="The Ebook Edit home"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/brand/the-ebook-edit-logo.webp' ) ); ?>" alt="The Ebook Edit" width="760" height="615"></a></p>
        <p class="eyebrow">Publishing</p>
        <h1>Publishing an ebook on Kindle and other platforms</h1>
        <p class="lead">A practical look at what Amazon Kindle Direct Publishing (KDP), Apple Books, Kobo Writing Life, Google Play Books, Barnes &amp; Noble Press, and Draft2Digital ask for, and how to prepare before you submit.</p>
        <div class="article-meta"><span>10 minute read</span><span>Updated August 2026</span></div>
        <div class="page-ornament" aria-hidden="true"></div>
      </div>
      <div class="prose-page">
        <article class="prose">

<p>Every ebook platform wants roughly the same things: a clean, correctly formatted file, accurate metadata, a compliant cover, and a completed account with your rights, tax, and payment details in order. The differences are mostly in file format, submission steps, and store-specific settings. Knowing what to expect before you start makes the upload stage far less stressful.</p>

<h2>What Amazon KDP asks for</h2>
<p>Amazon Kindle Direct Publishing (KDP) is a self-service platform: you create and control your own KDP account, and you make every publishing decision yourself. Preparing to submit typically involves a Kindle-compatible manuscript file, a title and subtitle, a book description, keywords and categories, a cover that meets KDP's dimension and format guidelines, and pricing and territory choices that you set inside your own account. KDP also provides an online previewer so you can check how the file renders before it goes live.</p>

<h2>How Apple Books, Kobo, and Google Play Books differ</h2>
<p>Apple Books, Kobo Writing Life, and Google Play Books each accept EPUB files rather than a Kindle-specific format, and each has its own publisher dashboard, metadata fields, and content guidelines. The underlying preparation work is similar to KDP — clean navigation, consistent headings, working links, and accurate metadata — but each store's submission form, review process, and account setup are separate from Amazon's and from each other. Publishing to more than one of these stores means completing more than one account and more than one set of platform-specific steps.</p>

<h2>Barnes &amp; Noble Press and NOOK</h2>
<p>Barnes &amp; Noble Press is the self-publishing platform behind the NOOK storefront. Like the other stores, it uses its own dashboard for uploading an EPUB file, entering metadata, and setting pricing. Cover requirements and description length limits are set by Barnes &amp; Noble Press directly, so it's worth checking the current guidelines in your account before final formatting.</p>

<h2>Going wide with Draft2Digital</h2>
<p>Rather than uploading separately to every store, some authors use a distribution service such as Draft2Digital to submit one prepared file to multiple retailers at once. This can simplify the submission process, but the author's account with the distributor — and the underlying accounts or agreements at each connected retailer — still belong to the author. A distributor is a delivery route, not a publisher, and it does not change who owns the rights or who makes the final publishing decisions.</p>

<h2>What stays in the author's hands</h2>
<p>Regardless of which platform or combination of platforms you choose, a few things always remain with the author or rights holder:</p>
<ul>
<li>Creating and logging into your own publishing accounts</li>
<li>Confirming you hold the rights to publish the work</li>
<li>Entering your own tax and payment information</li>
<li>Setting pricing, territories, and availability</li>
<li>Making the final decision of when — and whether — to publish</li>
</ul>
<p>Editorial and formatting support can prepare the file, metadata, and a checklist tailored to your chosen platform, but it does not replace these account-level decisions.</p>

<h2>Where formatting and editing support fits</h2>
<p>Most of the platform-specific work above depends on the same foundation: a manuscript that has been properly edited, a file that has been correctly formatted and validated, and metadata that has been written and organised in advance. That preparation is what our <a href="<?php echo esc_url( home_url( '/publishing/' ) ); ?>">publishing support</a> covers — readying the manuscript, files, and materials so that whichever platform you choose, the submission step is straightforward.</p>

<h2>A simple platform-readiness checklist</h2>
<ul>
<li>Manuscript is fully edited and proofread</li>
<li>File format matches the target platform (Kindle-compatible file, or EPUB for Apple Books, Kobo, Google Play Books, or Barnes &amp; Noble Press)</li>
<li>Title, description, keywords, and categories are drafted</li>
<li>Cover meets the chosen platform's dimension and file requirements</li>
<li>Publishing account, rights, tax, and payment details are set up and confirmed by the author</li>
</ul>

<blockquote>The platform handles distribution. The author and rights holder handle the account, the rights, and the decision to publish.</blockquote>

<p>The Ebook Edit is an independent editorial and formatting service and is not affiliated with, endorsed by, or officially connected to Amazon, Apple, Kobo, Google, Barnes &amp; Noble, or Draft2Digital. All product and platform names are trademarks of their respective owners.</p>

</article>
      </div>
      <div class="insert-page">
        <div class="assessment-insert">
          <h3>Preparing for Kindle or another platform?</h3>
          <p>Formatting and publishing support can prepare your files, metadata, and checklist for the platform you choose.</p>
          <a class="button button-gold" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Plan publishing support</a>
        </div>
      </div>
      <div class="closing-page">
        <p class="page-more"><a href="<?php echo esc_url( home_url( '/insights/' ) ); ?>">More insights →</a></p>
        <p class="micro-colophon">© <span data-year></span> The Ebook Edit · <a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>">Privacy</a> · <a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>">Terms</a></p>
      </div>
    </div>
  </div>
  <div class="book-endcap" aria-hidden="true"></div>
</div>

<?php
get_footer();
