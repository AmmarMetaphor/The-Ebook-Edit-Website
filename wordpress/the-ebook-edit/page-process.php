<?php
/**
 * Our Process — /process/.
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

<section class="site-view" id="process-view">
<section class="page-hero center "><div class="flow-wrap" aria-hidden="true"><div class="flow-line one"></div><div class="flow-line two"></div></div><div class="container" style="position:relative;z-index:1"><div class="v-reveal"><div class="eyebrow">Our Process</div><h1 class="v-h1" tabindex="-1">From First Idea<br>to Publication</h1><p class="v-lead">We make the process simple and collaborative, with your feedback and approval guiding each stage</p><div class="actions"><a class="btn btn-gold" href="<?php echo esc_url( home_url( '/book-consultation/' ) ); ?>">Book Your Free Consultation</a><a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>">View Our Work</a></div></div></div></section><section class="v-section tight"><div class="container"><div class="orbit-wrap v-reveal" aria-label="Six stages around one book"><div class="orbit-ring r1"></div><div class="orbit-ring r2"></div><div class="orbit-book"><img src="<?php echo esc_url( teebe_site_image( 'cover-4' ) ); ?>" class="v-cover" data-asset="cover-4" alt="The Inner Compass book cover" width="200" decoding="async" ></div><div class="orb o1 v-reveal" data-delay="0"><span class="num">01</span><strong>Discover</strong></div><div class="orb o2 v-reveal" data-delay="1"><span class="num">02</span><strong>Plan</strong></div><div class="orb o3 v-reveal" data-delay="2"><span class="num">03</span><strong>Create</strong></div><div class="orb o4 v-reveal" data-delay="0"><span class="num">04</span><strong>Polish</strong></div><div class="orb o5 v-reveal" data-delay="1"><span class="num">05</span><strong>Publish</strong></div><div class="orb o6 v-reveal" data-delay="2"><span class="num">06</span><strong>Grow</strong></div></div></div></section><section class="v-section tight"><div class="container"><div class="timeline"><div class="timeline-progress" aria-hidden="true"></div><ol class="tl-list"><li class="tl-item v-reveal"><div class="tl-side">Stage 01</div><div class="tl-num" aria-hidden="true">01</div><div class="tl-card"><h3>Discover</h3><p>We learn the book, the reader and the goal.</p></div></li><li class="tl-item v-reveal"><div class="tl-side">Stage 02</div><div class="tl-num" aria-hidden="true">02</div><div class="tl-card"><h3>Plan</h3><p>The right sequence of writing, editing, design and publishing.</p></div></li><li class="tl-item v-reveal"><div class="tl-side">Stage 03</div><div class="tl-num" aria-hidden="true">03</div><div class="tl-card"><h3>Create</h3><p>Writing, illustration or structure takes shape.</p></div></li><li class="tl-item v-reveal"><div class="tl-side">Stage 04</div><div class="tl-num" aria-hidden="true">04</div><div class="tl-card"><h3>Polish</h3><p>Editing and proofreading sharpen every page.</p></div></li><li class="tl-item v-reveal"><div class="tl-side">Stage 05</div><div class="tl-num" aria-hidden="true">05</div><div class="tl-card"><h3>Publish</h3><p>Files, platforms and release, prepared with you.</p></div></li><li class="tl-item v-reveal"><div class="tl-side">Stage 06</div><div class="tl-num" aria-hidden="true">06</div><div class="tl-card"><h3>Grow</h3><p>Positioning and promotion after launch, where wanted.</p></div></li></ol></div></div></section><section class="cta-band"><div class="container v-reveal"><h2>Ready for the First Conversation?<span>It starts with a few details</span></h2><p>Tell us about the book and the stage it has reached.</p><div class="actions"><a class="btn btn-gold" href="<?php echo esc_url( home_url( '/book-consultation/' ) ); ?>">Book Your Free Consultation</a></div></div></section>
</section>

<?php
get_footer();
