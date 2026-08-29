<?php
/**
 * front — generated from the static site by wordpress/sync-from-static.py.
 * Edit the static page and re-run the script; do not hand-edit this file.
 *
 * @package the-ebook-edit
 */

get_header();
?>

<div class="book-experience" id="book-experience">
  <div class="book-stage">
    <div class="book-scene">
      <div class="book" id="book">
        <div class="book-shadow" aria-hidden="true"></div>

        <!-- Front cover: the homepage hero lives on the book itself. -->
        <header class="book-cover" id="book-cover">
          <div class="cover-front">
            <span class="cover-spine" aria-hidden="true"></span>
            <div class="cover-plaque">
              <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/brand/the-ebook-edit-logo.webp' ) ); ?>" alt="The Ebook Edit" width="760" height="615">
            </div>
            <p class="cover-eyebrow">Ebook writing · editing · publishing</p>
            <h1 class="cover-title">Let Your Story<br>Become <span>Your Legacy</span></h1>
            <div class="cover-rule" aria-hidden="true"></div>
            <p class="cover-lead">Professional ebook writing, editing, formatting and publishing support for authors, experts and organisations ready to transform an idea or manuscript into a polished book.</p>
            <p class="cover-cta"><a class="button button-gold cover-open" href="#chapter-1">Open the book</a></p>
            <p class="cover-hint">or scroll to begin<span class="cover-chevron" aria-hidden="true">⌄</span></p>
            <div class="cover-shade" aria-hidden="true"></div>
          </div>
          <div class="cover-back" aria-hidden="true"></div>
        </header>

        <!-- Chapter tabs: the site navigation, attached to the book's top edge. -->
        <nav class="book-tabs" id="book-tabs" aria-label="Primary navigation">
          <a class="book-tab" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Services</a>
          <a class="book-tab" href="<?php echo esc_url( home_url( '/process/' ) ); ?>">Process</a>
          <a class="book-tab" href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>">Portfolio</a>
          <a class="book-tab" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
          <a class="book-tab" href="<?php echo esc_url( home_url( '/insights/' ) ); ?>">Insights</a>
          <a class="book-tab book-tab-cta" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Start a project</a>
        </nav>

        <div class="book-board-open" aria-hidden="true"></div>

        <div class="book-block" id="book-block">
          <div class="paper-left" aria-hidden="true"></div>
          <div class="paper-right" aria-hidden="true"></div>

          <section class="spread" id="chapter-1" aria-labelledby="reach-title">
            <div class="page page-left">
              <div class="page-inner">
                <p class="eyebrow">Looking to get your book published and seen by more readers?</p>
                <h2 id="reach-title">Reach a Global Audience with Expert Publishing Support.</h2>
                <p class="feature-heading">A Clearer Path to Successful Self Publishing</p>
                <p>From manuscript preparation and editing to formatting, publishing and launch readiness, we help turn your book into a professional publication prepared for the marketplace.</p>
                <p class="feature-note">You bring the story. We help shape, polish and prepare it for readers.</p>
                <div class="page-actions">
                  <a class="button button-gold" href="#enquiry">Talk to an Ebook Specialist</a>
                </div>
                <p class="page-more"><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">See Our Services →</a></p>
                <div class="page-emblem" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M12 6c-2-1.5-4.5-2-8-2v14c3.5 0 6 .5 8 2 2-1.5 4.5-2 8-2V4c-3.5 0-6 .5-8 2z"/><path d="M12 6v14"/></svg></div>
              </div>
              <span class="folio folio-left" aria-hidden="true">2</span>
            </div>
            <div class="page page-right page-form-page">
              <div class="page-inner">
                <p class="eyebrow">Let's talk about your book</p>
                <h2>Tell Us Where You Are in Your Publishing Journey</h2>
                <p class="page-lead">Share a few details and we will help identify the most suitable next step.</p>
                <?php
                  /*
                   * Contact Form 7 renders the enquiry form here. The static
                   * site posts to Netlify Forms, which WordPress has no
                   * equivalent of, so the form body — including its book page
                   * classes — is supplied by a CF7 form. Paste the markup from
                   * DEPLOYMENT.md into a form named "publishing-journey" and the page renders
                   * exactly as the static site does.
                   */
                  teebe_render_enquiry_form( 'publishing-journey' );
                  ?>
              </div>
              <span class="folio folio-right" aria-hidden="true">3</span>
            </div>
          </section>

          <section class="spread" id="chapter-2" aria-labelledby="journey-map-title">
            <div class="page page-left">
              <div class="page-inner">
                <h2 id="journey-map-title">How We Bring Your Book to Life</h2>
                <p class="info-sub">From First Idea to Publication in Five Clear Stages</p>
                <ol class="info-stages">
                  <li><span class="info-no" aria-hidden="true">01</span><div class="info-body"><h3>Discovery &amp; Assessment</h3><p>We understand your idea, manuscript, goals and intended readers.</p></div></li>
                  <li><span class="info-no" aria-hidden="true">02</span><div class="info-body"><h3>Writing &amp; Editing</h3><p>We develop or refine the manuscript with careful attention to clarity, structure and voice.</p></div></li>
                </ol>
                <div class="info-flow info-flow-out" aria-hidden="true"></div>
              </div>
              <span class="folio folio-left" aria-hidden="true">4</span>
            </div>
            <div class="page page-right">
              <div class="page-inner">
                <div class="info-flow info-flow-in" aria-hidden="true"></div>
                <div class="info-emblem" aria-hidden="true">
                  <svg viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="57" fill="#fffdf6" stroke="#f5a70a" stroke-width="2"/>
                    <circle cx="60" cy="60" r="49" fill="none" stroke="rgba(245, 167, 10, 0.4)" stroke-width="1"/>
                    <path d="M60 44c-6.5-4.5-14.5-6.5-24-6.5v41c9.5 0 17.5 2 24 6.5 6.5-4.5 14.5-6.5 24-6.5v-41c-9.5 0-17.5 2-24 6.5z" fill="#0047b9"/>
                    <path d="M60 44v41" stroke="#fdf9ee" stroke-width="3"/>
                    <path d="M42 52c4.5 0 8.5.9 12 2.6M42 60c4.5 0 8.5.9 12 2.6M66 54.6c3.5-1.7 7.5-2.6 12-2.6M66 62.6c3.5-1.7 7.5-2.6 12-2.6" stroke="rgba(253, 249, 238, 0.75)" stroke-width="1.6" fill="none" stroke-linecap="round"/>
                    <path d="M60 30l2 4.2 4.2 2-4.2 2-2 4.2-2-4.2-4.2-2 4.2-2z" fill="#f5a70a"/>
                    <circle cx="87" cy="41" r="2.2" fill="#0090c8"/>
                    <circle cx="33" cy="79" r="2.2" fill="#0090c8"/>
                  </svg>
                </div>
                <ol class="info-stages info-stages-cont" start="3">
                  <li><span class="info-no" aria-hidden="true">03</span><div class="info-body"><h3>Design &amp; Formatting</h3><p>We prepare the visual presentation and professional files required for digital or print publication.</p></div></li>
                  <li><span class="info-no" aria-hidden="true">04</span><div class="info-body"><h3>Publishing Preparation</h3><p>We help prepare your book and publishing materials for the appropriate platform and release process.</p></div></li>
                  <li><span class="info-no" aria-hidden="true">05</span><div class="info-body"><h3>Launch &amp; Marketing Support</h3><p>Where required, we help position the book for discovery through practical launch and promotional support.</p></div></li>
                </ol>
              </div>
              <span class="folio folio-right" aria-hidden="true">5</span>
            </div>
          </section>

          <section class="spread" id="chapter-3" aria-labelledby="pathways-title">
            <div class="page page-left">
              <div class="page-inner">
                <p class="eyebrow">Where is your book today?</p>
                <h2 id="pathways-title">Start Wherever You Are.</h2>
                <p class="page-lead">You do not need to know which service you need. Choose the stage that feels closest to where your book is today, and we will help guide the next step.</p>
                <a class="journey-card" href="<?php echo esc_url( home_url( '/writing/' ) ); ?>">
                  <span class="jc-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="9" r="5"/><path d="M10 14v3h4v-3"/><path d="M10 20h4"/></svg></span>
                  <span class="jc-body">
                    <strong class="jc-title">I Only Have an Idea</strong>
                    <span class="jc-text">Turn the concept into a clear audience, structure and writing plan.</span>
                    <span class="jc-cta" aria-hidden="true">Explore Writing →</span>
                  </span>
                </a>
                <div class="jc-link" aria-hidden="true"></div>
                <a class="journey-card" href="<?php echo esc_url( home_url( '/writing/' ) ); ?>">
                  <span class="jc-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="9" y="3" width="6" height="10" rx="3"/><path d="M6 11a6 6 0 0 0 12 0"/><path d="M12 17v4"/></svg></span>
                  <span class="jc-body">
                    <strong class="jc-title">I Have Notes, Recordings or Existing Material</strong>
                    <span class="jc-text">Transform raw material into a coherent, professionally developed manuscript.</span>
                    <span class="jc-cta" aria-hidden="true">Explore Ghostwriting →</span>
                  </span>
                </a>
              </div>
              <span class="folio folio-left" aria-hidden="true">6</span>
            </div>
            <div class="page page-right">
              <div class="page-inner">
                <a class="journey-card" href="<?php echo esc_url( home_url( '/editing/' ) ); ?>">
                  <span class="jc-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M7 3h7l4 4v14H7z"/><path d="M14 3v4h4"/><path d="M10 12h6M10 16h6"/></svg></span>
                  <span class="jc-body">
                    <strong class="jc-title">I Have a Complete Manuscript</strong>
                    <span class="jc-text">Identify the right level of editing, refinement and final polish.</span>
                    <span class="jc-cta" aria-hidden="true">Explore Editing →</span>
                  </span>
                </a>
                <div class="jc-link" aria-hidden="true"></div>
                <a class="journey-card" href="<?php echo esc_url( home_url( '/publishing/' ) ); ?>">
                  <span class="jc-ico" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 6c-2-1.5-4.5-2-8-2v14c3.5 0 6 .5 8 2 2-1.5 4.5-2 8-2V4c-3.5 0-6 .5-8 2z"/><path d="M12 6v14"/></svg></span>
                  <span class="jc-body">
                    <strong class="jc-title">I Am Ready to Format and Publish</strong>
                    <span class="jc-text">Prepare professional files and move confidently toward publication.</span>
                    <span class="jc-cta" aria-hidden="true">Explore Publishing →</span>
                  </span>
                </a>
                <p class="page-more jc-more">Not sure where you fit? <a href="#enquiry">Tell us about your book →</a></p>
              </div>
              <span class="folio folio-right" aria-hidden="true">7</span>
            </div>
          </section>

          <section class="spread" id="chapter-4" aria-labelledby="services-title">
            <div class="page page-left">
              <div class="page-inner">
                <p class="eyebrow">Everything your ebook needs</p>
                <h2 id="services-title">One editorial partner. Every essential stage.</h2>
                <p class="page-lead">Choose a focused service for an existing manuscript or combine stages into an end-to-end publishing journey.</p>
                <a class="entry service-entry" href="<?php echo esc_url( home_url( '/writing/' ) ); ?>">
                  <span class="entry-no" aria-hidden="true">01</span>
                  <span class="entry-body">
                    <span class="entry-title">Ebook writing</span>
                    <span class="entry-text">Shape a strong concept, outline the reader journey, and develop original chapters in a voice that sounds like you.</span>
                    <span class="entry-cta" aria-hidden="true">Explore writing services →</span>
                  </span>
                </a>
                <a class="entry service-entry" href="<?php echo esc_url( home_url( '/editing/' ) ); ?>">
                  <span class="entry-no" aria-hidden="true">02</span>
                  <span class="entry-body">
                    <span class="entry-title">Editing</span>
                    <span class="entry-text">Strengthen structure, clarity, tone, flow, grammar, and consistency without flattening the personality of the work.</span>
                    <span class="entry-cta" aria-hidden="true">Explore editing services →</span>
                  </span>
                </a>
              </div>
              <span class="folio folio-left" aria-hidden="true">8</span>
            </div>
            <div class="page page-right">
              <div class="page-inner">
                <a class="entry service-entry" href="<?php echo esc_url( home_url( '/publishing/' ) ); ?>#formatting">
                  <span class="entry-no" aria-hidden="true">03</span>
                  <span class="entry-body">
                    <span class="entry-title">Formatting</span>
                    <span class="entry-text">Prepare clean, readable interiors for EPUB and PDF, including headings, navigation, front matter, and final checks.</span>
                    <span class="entry-cta" aria-hidden="true">Explore formatting support →</span>
                  </span>
                </a>
                <a class="entry service-entry" href="<?php echo esc_url( home_url( '/publishing/' ) ); ?>">
                  <span class="entry-no" aria-hidden="true">04</span>
                  <span class="entry-body">
                    <span class="entry-title">Publishing support</span>
                    <span class="entry-text">Plan metadata, platform files, upload steps, and launch assets for Amazon KDP, Apple Books, Kobo, Google Play Books, Barnes &amp; Noble Press, and wider distribution.</span>
                    <span class="entry-cta" aria-hidden="true">Explore publishing support →</span>
                  </span>
                </a>
                <p class="page-more"><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">All services →</a></p>
              </div>
              <span class="folio folio-right" aria-hidden="true">9</span>
            </div>
          </section>

          <section class="spread" id="chapter-5" aria-labelledby="platforms-title">
            <div class="page page-left">
              <div class="page-inner">
                <p class="eyebrow">Publishing platforms</p>
                <h2 id="platforms-title">Prepare your ebook for the platforms that matter.</h2>
                <p class="page-lead">We help organise platform-ready files, metadata and publishing materials for leading ebook stores and wider distribution routes.</p>
                <p class="platform-note-page">Platform names are shown to describe compatible publishing-support services. The Ebook Edit is an independent editorial service.</p>
              </div>
              <span class="folio folio-left" aria-hidden="true">10</span>
            </div>
            <div class="page page-right">
              <div class="page-inner">
                <!--
                  Platform names are set as text because no approved platform logo files are
                  bundled with this site. To swap in approved artwork later, drop the file into
                  assets/images/platforms/ and replace a list item's text with, for example:
                    <img class="imprint-logo" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/platforms/amazon-kdp.svg' ) ); ?>" alt="Amazon Kindle Direct Publishing" loading="lazy" decoding="async">
                  Check each platform's current brand guidelines before using its logo.
                -->
                <ul class="imprint-list">
                  <li>Amazon Kindle Direct Publishing</li>
                  <li>Apple Books</li>
                  <li>Kobo Writing Life</li>
                  <li>Google Play Books</li>
                  <li>Barnes &amp; Noble Press / NOOK</li>
                  <li>Draft2Digital</li>
                </ul>
                <p class="page-more"><a href="<?php echo esc_url( home_url( '/publishing/' ) ); ?>">Explore publishing support →</a></p>
              </div>
              <span class="folio folio-right" aria-hidden="true">11</span>
            </div>
          </section>

          <section class="spread" id="chapter-6" aria-labelledby="finish-title">
            <div class="page page-left">
              <div class="page-inner">
                <div class="close-mark" aria-hidden="true">&#8221;</div>
                <p class="eyebrow">Your next chapter</p>
                <h2 id="finish-title">Your Book Deserves a Strong Finish.</h2>
                <p class="page-lead">Whether you are beginning with an idea, refining a manuscript or preparing to publish, the right next step can turn uncertainty into momentum.</p>
                <p class="close-line">Let's make that next step clear.</p>
                <div class="page-actions">
                  <a class="button button-gold" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Start Your Ebook Project</a>
                </div>
                <p class="page-more"><a href="https://wa.me/447348954631?text=Hello%20The%20Ebook%20Edit%2C%20I%20would%20like%20to%20discuss%20an%20ebook%20project." target="_blank" rel="noopener noreferrer">Chat With Us on WhatsApp →</a></p>
              </div>
              <span class="folio folio-left" aria-hidden="true">12</span>
            </div>
            <div class="page page-right">
              <div class="page-inner">
                <div class="colophon">
                  <div class="colophon-plaque">
                    <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/brand/the-ebook-edit-logo.webp' ) ); ?>" alt="The Ebook Edit" width="760" height="615" loading="lazy" decoding="async">
                  </div>
                  <p class="colophon-line">Thoughtful ebook writing, editing, formatting, and publishing support—shaped around your voice and your reader.</p>
                  <p class="colophon-contact">Email <a href="mailto:support@theebookedit.com">support@theebookedit.com</a><br>UK and US · We aim to reply within 24 hours</p>
                  <div class="page-ornament" aria-hidden="true"></div>
                  <!-- TODO before launch: add confirmed Instagram / Facebook profile URLs
                       here as a small social row. Do not publish placeholder "#" links. -->
                  <p class="colophon-end">Thank you for reading</p>
                  <p class="micro-colophon">© <span data-year></span> The Ebook Edit · <a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>">Privacy</a> · <a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>">Terms</a></p>
                </div>
              </div>
              <span class="folio folio-right" aria-hidden="true">13</span>
            </div>
          </section>

          <div class="book-spine-shade" aria-hidden="true"></div>
          <div class="turn-shade" aria-hidden="true"></div>
          <div class="book-leaf" aria-hidden="true">
            <div class="leaf-front"></div>
            <div class="leaf-back"></div>
          </div>
        </div>

        <div class="book-board-closed" aria-hidden="true"></div>
        <div class="book-ribbon" aria-hidden="true"><span class="ribbon-fill"></span></div>
      </div>
    </div>
  </div>
  <div class="book-endcap" aria-hidden="true"></div>
</div>

<?php
get_footer();
