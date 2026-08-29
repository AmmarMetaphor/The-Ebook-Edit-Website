<?php
/**
 * Template Name: Insight — Pre-Publishing Checklist
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
        <h1>A pre-publishing checklist for a professional ebook</h1>
        <p class="lead">Review the manuscript, permissions, metadata, formatting, ownership, and launch materials before submitting the book.</p>
        <div class="article-meta"><span>9 minute read</span><span>Updated August 2026</span></div>
        <div class="page-ornament" aria-hidden="true"></div>
      </div>
      <div class="prose-page">
        <article class="prose"><p>The final upload can feel like the last simple step. In reality, publication brings together editorial, technical, legal, commercial, and administrative decisions. A checklist helps prevent small oversights from becoming public problems.</p><h2>Editorial readiness</h2><ul><li>The final manuscript is approved and no longer receiving structural changes.</li><li>Copy editing is complete.</li><li>All author queries are resolved.</li><li>The formatted output has been proofread.</li><li>Headings, lists, tables, figures, captions, and links are consistent.</li></ul><h2>Rights and permissions</h2><ul><li>You own or have permission to use all text, images, illustrations, data, and substantial quotations.</li><li>Any client stories or personal information have appropriate consent or anonymisation.</li><li>Licensing conditions and required attributions are documented.</li><li>Professional legal review has been obtained where the subject or claims create meaningful risk.</li></ul><h2>Front matter and back matter</h2><p>Confirm the title page, copyright notice, edition information, disclaimer where appropriate, table of contents, acknowledgments, author bio, resources, references, and contact or next-step links. Keep the content useful and avoid crowding the opening pages with unnecessary promotion.</p><h2>Metadata</h2><ul><li>Title and subtitle match the cover and interior.</li><li>Author name is presented consistently.</li><li>The book description is accurate and readable.</li><li>Keywords and categories reflect the actual audience and subject.</li><li>Edition, language, publication date, and contributor details are correct.</li></ul><h2>File quality</h2><ul><li>The EPUB opens correctly in relevant preview tools.</li><li>The table of contents is clickable and correctly ordered.</li><li>Links work and are appropriate for the final edition.</li><li>Images are readable, proportioned, and not unnecessarily large.</li><li>Paragraphs, indentation, spacing, and headings are consistent.</li><li>No editing comments, tracked changes, placeholders, or hidden notes remain.</li></ul><h2>Cover and branding</h2><p>Check title legibility at thumbnail size, author-name consistency, image licensing, correct dimensions for the chosen platform (for example, Amazon KDP, Apple Books, Kobo, or Barnes &amp; Noble Press), and whether the cover accurately represents the genre and promise of the book.</p><h2>Account and ownership</h2><ul><li>The author or publisher controls the publishing account.</li><li>Payment and tax details belong to the correct entity.</li><li>Passwords are not embedded in project files or shared through insecure messages.</li><li>Final files and editable source files are stored in at least two secure locations.</li></ul><h2>Launch materials</h2><p>Prepare a concise landing-page description, author bio, cover image, retailer or download links (Amazon, Apple Books, Kobo, Google Play Books, Barnes &amp; Noble, or others), email announcement, social copy, and a plan for corrections after launch. Do not wait until publication day to decide how readers will discover the book.</p><h2>After publication</h2><p>Download or inspect the live edition, test the purchase or signup path, check the product description, verify links, record the published file version, and maintain a correction log for the next update.</p><blockquote>Publication is a handoff to the reader, not the end of editorial responsibility.</blockquote><p>A professional release is the result of many small, verified decisions made before the upload button is pressed. For a closer look at preparing files for specific stores, see our guide to <a href="<?php echo esc_url( home_url( '/insights/kindle-and-ebook-platform-guide/' ) ); ?>">publishing an ebook on Kindle and other platforms</a> or visit <a href="<?php echo esc_url( home_url( '/publishing/' ) ); ?>">publishing support</a>.</p></article>
      </div>
      <div class="insert-page">
        <div class="assessment-insert">
          <h3>Preparing final files?</h3>
          <p>Formatting and publishing support can organise the last stage and reduce avoidable launch errors.</p>
          <a class="button button-gold" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Discuss your ebook</a>
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
