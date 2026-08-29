<?php
/**
 * contact — generated from the static site by wordpress/sync-from-static.py.
 * Edit the static page and re-run the script; do not hand-edit this file.
 *
 * @package the-ebook-edit
 */

get_header();
?>

<div class="book-experience book-open-start">
  <div class="book-stage">
    <div class="book-scene">
      <div class="book">
        <div class="book-shadow" aria-hidden="true"></div>

        <nav class="book-tabs" aria-label="Primary navigation">
          <a class="book-tab" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Services</a>
          <a class="book-tab" href="<?php echo esc_url( home_url( '/process/' ) ); ?>">Process</a>
          <a class="book-tab" href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>">Portfolio</a>
          <a class="book-tab" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
          <a class="book-tab" href="<?php echo esc_url( home_url( '/insights/' ) ); ?>">Insights</a>
          <a class="book-tab book-tab-cta" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" aria-current="page">Start a project</a>
        </nav>

        <div class="book-board-open" aria-hidden="true"></div>

        <div class="book-block">
          <div class="paper-left" aria-hidden="true"></div>
          <div class="paper-right" aria-hidden="true"></div>

          <section class="spread" id="chapter-1" aria-labelledby="start-title">
            <div class="page page-left">
              <div class="page-inner">
                <div class="m-pg">
                <p class="bookplate-row"><a class="bookplate" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="The Ebook Edit home"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/brand/the-ebook-edit-logo.webp' ) ); ?>" alt="The Ebook Edit" width="760" height="615"></a></p>
                <p class="eyebrow">Start a project</p>
                <h1 id="start-title">Tell Us About the Book You Want to Create or Improve.</h1>
                <p class="page-lead">Whether you have an early idea, notes, a manuscript in progress or a book ready for publication, tell us where you are and we will help identify the most suitable next step.</p>
                <p class="feature-note">You do not need to know exactly which service you need before getting in touch.</p>
                </div>
                <div class="m-pg">
                <p class="eyebrow">What happens next</p>
                <ol class="stage-list start-steps">
                  <li><span class="stage-no" aria-hidden="true">1</span><div class="stage-body"><p>We review your enquiry.</p></div></li>
                  <li><span class="stage-no" aria-hidden="true">2</span><div class="stage-body"><p>We identify the most suitable next step.</p></div></li>
                  <li><span class="stage-no" aria-hidden="true">3</span><div class="stage-body"><p>We contact you using your preferred method.</p></div></li>
                </ol>
                <p class="start-hint">Helpful details include your topic, current manuscript stage and the kind of support you think you may need.</p>
                </div>
              </div>
              <span class="folio folio-left" aria-hidden="true">2</span>
            </div>
            <div class="page page-right">
              <div class="page-inner">
                <?php
                  /*
                   * Contact Form 7 renders the enquiry form here. The static
                   * site posts to Netlify Forms, which WordPress has no
                   * equivalent of, so the form body — including its book page
                   * classes — is supplied by a CF7 form. Paste the markup from
                   * DEPLOYMENT.md into a form named "project-inquiry" and the page renders
                   * exactly as the static site does.
                   */
                  teebe_render_enquiry_form( 'project-inquiry' );
                  ?>
                <p class="micro-colophon">Prefer email? <a href="mailto:support@theebookedit.com">support@theebookedit.com</a> · © <span data-year></span> The Ebook Edit · <a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>">Privacy</a> · <a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>">Terms</a></p>
              </div>
              <span class="folio folio-right" aria-hidden="true">3</span>
            </div>
          </section>

          <div class="book-spine-shade" aria-hidden="true"></div>
          <div class="turn-shade" aria-hidden="true"></div>
          <div class="book-leaf" aria-hidden="true">
            <div class="leaf-front"></div>
            <div class="leaf-back"></div>
          </div>
        </div>

        <div class="book-ribbon" aria-hidden="true"><span class="ribbon-fill"></span></div>
      </div>
    </div>
  </div>
  <div class="book-endcap" aria-hidden="true"></div>
</div>

<?php
get_footer();
