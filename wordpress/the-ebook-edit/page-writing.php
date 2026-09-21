<?php
/**
 * Book Writing — /writing/.
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

<section class="site-view" id="writing-view">
<section class="page-hero "><div class="flow-wrap" aria-hidden="true"><div class="flow-line one"></div><div class="flow-line two"></div></div><div class="container" style="position:relative;z-index:1"><div class="page-hero-grid"><div class="v-reveal"><div class="eyebrow">From idea to manuscript</div><h1 class="v-h1" tabindex="-1">You Bring the Idea.<br>We Help Build the Book.</h1><p class="v-lead">Notes, recordings, expertise or a rough draft. Writing and ghostwriting support turns what you have into a structured manuscript that still sounds like you.</p><div class="actions"><a class="btn btn-gold" href="<?php echo esc_url( home_url( '/book-consultation/' ) ); ?>">Get Your Free Book Consultation</a><a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>">View Our Work</a></div></div><div class="v-reveal" data-delay="1"><div class="ms-visual" aria-hidden="true"><!-- IMAGE PLACEHOLDER: writing workspace / manuscript pages. Recommended ratio 4:5, suggested source 1200x1500. Built in CSS until an approved image exists. -->
      <div class="ms-page p1"><i></i><i class="s"></i><i></i><i class="m"></i><i></i><i class="s"></i></div>
      <div class="ms-page p2"><b>Chapter One</b><i></i><i class="m"></i><i></i><i class="s"></i><i></i><i class="m"></i><i></i></div>
      <div class="ms-note">Notes → structure → draft</div><img src="<?php echo esc_url( teebe_site_image( 'cover-4' ) ); ?>" class="v-cover ms-book" data-asset="cover-4" alt="The Inner Compass book cover" decoding="async" ></div></div></div></div></section><section class="v-section tight"><div class="container"><div class="v-head center v-reveal"><div class="eyebrow">The writing journey</div><h2>Six Steps From Idea to Manuscript.</h2></div><div class="flow-row"><div class="flow-step v-reveal" data-delay="0"><div class="dot">✦</div><span>Idea</span></div><div class="flow-step v-reveal" data-delay="1"><div class="dot">◈</div><span>Notes / Recordings</span></div><div class="flow-step v-reveal" data-delay="2"><div class="dot">▤</div><span>Structure</span></div><div class="flow-step v-reveal" data-delay="0"><div class="dot">✎</div><span>Drafting</span></div><div class="flow-step v-reveal" data-delay="1"><div class="dot">✓</div><span>Review</span></div><div class="flow-step v-reveal" data-delay="2"><div class="dot">▣</div><span>Finished Manuscript</span></div></div></div></section><section class="v-section"><div class="container"><div class="v-head v-reveal"><div><div class="eyebrow">What we write</div><h2>Different Books,<br>Same Care.</h2></div><p>Every project begins with your material and your reader. The format follows.</p></div><div class="deliver three"><div class="d-card v-reveal" data-delay="0"><div class="n">01</div><h3>Book Writing</h3><p>From a clear brief to a complete manuscript in your voice.</p></div><div class="d-card v-reveal" data-delay="1"><div class="n">02</div><h3>Ghostwriting</h3><p>Your ideas, your name, written with you rather than for you.</p></div><div class="d-card v-reveal" data-delay="2"><div class="n">03</div><h3>Interview Based Writing</h3><p>Recorded conversations shaped into structured chapters.</p></div><div class="d-card v-reveal" data-delay="0"><div class="n">04</div><h3>Non-fiction Development</h3><p>Argument, examples and flow arranged so readers keep going.</p></div><div class="d-card v-reveal" data-delay="1"><div class="n">05</div><h3>Memoir / Autobiography</h3><p>Life material organised into a story with shape and honesty.</p></div><div class="d-card v-reveal" data-delay="2"><div class="n">06</div><h3>Business / Professional Books</h3><p>Expertise turned into a book that opens doors.</p></div></div></div></section><section class="v-section tight"><div class="container"><div class="example v-reveal"><div class="v-stage"><div class="v-pedestal"></div><img src="<?php echo esc_url( teebe_site_image( 'cover-5' ) ); ?>" class="v-cover v-cover-tilt" data-asset="cover-5" alt="Rising Through the Storm book cover" width="240" decoding="async" ></div><div><div class="eyebrow">From our work</div><h3>Rising Through the Storm</h3><p class="book-genre">Memoir / Autobiography</p><p>The story of Daniel Mercer, shaped by failure, loss and the pressure to keep moving forward.</p><div class="actions"><a class="btn btn-ghost btn-sm" href="<?php echo esc_url( home_url( '/portfolio/' ) . '#pf-rising-through-the-storm' ); ?>">View in Our Work →</a></div></div></div></div></section><section class="cta-band"><div class="container v-reveal"><h2>Ready to Start Writing?<span>Tell us about your book.</span></h2><p>Where it stands today is all we need to suggest the right way to begin.</p><div class="actions"><a class="btn btn-gold" href="<?php echo esc_url( home_url( '/book-consultation/' ) ); ?>">Get Your Free Book Consultation</a></div></div></section>
</section>

<?php
get_footer();
