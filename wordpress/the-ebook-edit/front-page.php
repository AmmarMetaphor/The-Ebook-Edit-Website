<?php
/**
 * Home — the approved website homepage.
 *
 * Ported from the approved single-file design
 * (theebookeditcompletewebsite-refined (4).html), which routed its views
 * with a hash router. Each view is now a real WordPress page served at a
 * real URL, so search engines, analytics and the browser history all see
 * ordinary page loads. The markup below is the approved markup; only the
 * links, image sources and the enquiry form are resolved through
 * WordPress.
 *
 * wordpress/sync-from-static.py does not generate this file.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="site-view" id="home-view">
<section class="hero" id="home-hero">
  <div class="container hero-grid">
    <div class="hero-copy">
        <div class="eyebrow">Professional ebook writing, editing & publishing</div>
        <h1>Let Your Story<br>Become <span>Your<br>Legacy</span></h1>
        <p class="lead">From first idea to final publication, we help shape books that are clear, polished and ready to leave an impression.</p>
        <div class="hero-actions">
          <a class="btn hero-primary-cta" href="<?php echo esc_url( home_url( '/book-consultation/' ) ); ?>">Book Your Free Consultation</a>
        </div>
    </div>

    <aside class="lead-card" id="home-contact">
      <div class="form-icon">✒</div>
      <h3>Let's Bring Your Book to Its Best</h3>
      <p class="intro">Tell us about your project and we'll help identify the right next step.</p>
      <?php teebe_render_site_form( 'home' ); ?>
    </aside>
  </div>
</section>

<section class="platform-wrap" aria-label="Publishing platforms">
  <div class="platform-label"><strong>Publishing Ready Support</strong> for major platforms</div>
  <div class="platform-marquee">
    <div class="platform-track">
      <div class="platform-group">
        <div class="platform-logo" aria-label="AbeBooks"><img data-asset="plat-abebooks" src="<?php echo esc_url( teebe_site_image( 'plat-abebooks' ) ); ?>" alt="AbeBooks"></div>
        <div class="platform-logo" aria-label="Penguin Random House"><img data-asset="plat-penguin-random-house" src="<?php echo esc_url( teebe_site_image( 'plat-penguin-random-house' ) ); ?>" alt="Penguin Random House"></div>
        <div class="platform-logo" aria-label="Scribd"><img data-asset="plat-scribd" src="<?php echo esc_url( teebe_site_image( 'plat-scribd' ) ); ?>" alt="Scribd"></div>
        <div class="platform-logo" aria-label="Rakuten Kobo"><img data-asset="plat-rakuten-kobo" src="<?php echo esc_url( teebe_site_image( 'plat-rakuten-kobo' ) ); ?>" alt="Rakuten Kobo"></div>
        <div class="platform-logo" aria-label="Barnes & Noble"><img data-asset="plat-barnes-noble" src="<?php echo esc_url( teebe_site_image( 'plat-barnes-noble' ) ); ?>" alt="Barnes & Noble"></div>
        <div class="platform-logo" aria-label="Amazon"><img data-asset="plat-amazon" src="<?php echo esc_url( teebe_site_image( 'plat-amazon' ) ); ?>" alt="Amazon"></div>
        <div class="platform-logo" aria-label="AbeBooks"><img data-asset="plat-abebooks" src="<?php echo esc_url( teebe_site_image( 'plat-abebooks' ) ); ?>" alt="AbeBooks"></div>
        <div class="platform-logo" aria-label="Penguin Random House"><img data-asset="plat-penguin-random-house" src="<?php echo esc_url( teebe_site_image( 'plat-penguin-random-house' ) ); ?>" alt="Penguin Random House"></div>
        <div class="platform-logo" aria-label="Scribd"><img data-asset="plat-scribd" src="<?php echo esc_url( teebe_site_image( 'plat-scribd' ) ); ?>" alt="Scribd"></div>
        <div class="platform-logo" aria-label="Rakuten Kobo"><img data-asset="plat-rakuten-kobo" src="<?php echo esc_url( teebe_site_image( 'plat-rakuten-kobo' ) ); ?>" alt="Rakuten Kobo"></div>
        <div class="platform-logo" aria-label="Barnes & Noble"><img data-asset="plat-barnes-noble" src="<?php echo esc_url( teebe_site_image( 'plat-barnes-noble' ) ); ?>" alt="Barnes & Noble"></div>
        <div class="platform-logo" aria-label="Amazon"><img data-asset="plat-amazon" src="<?php echo esc_url( teebe_site_image( 'plat-amazon' ) ); ?>" alt="Amazon"></div>
      </div>
      <div class="platform-group" aria-hidden="true">
        <div class="platform-logo" aria-label="AbeBooks"><img data-asset="plat-abebooks" src="<?php echo esc_url( teebe_site_image( 'plat-abebooks' ) ); ?>" alt="AbeBooks"></div>
        <div class="platform-logo" aria-label="Penguin Random House"><img data-asset="plat-penguin-random-house" src="<?php echo esc_url( teebe_site_image( 'plat-penguin-random-house' ) ); ?>" alt="Penguin Random House"></div>
        <div class="platform-logo" aria-label="Scribd"><img data-asset="plat-scribd" src="<?php echo esc_url( teebe_site_image( 'plat-scribd' ) ); ?>" alt="Scribd"></div>
        <div class="platform-logo" aria-label="Rakuten Kobo"><img data-asset="plat-rakuten-kobo" src="<?php echo esc_url( teebe_site_image( 'plat-rakuten-kobo' ) ); ?>" alt="Rakuten Kobo"></div>
        <div class="platform-logo" aria-label="Barnes & Noble"><img data-asset="plat-barnes-noble" src="<?php echo esc_url( teebe_site_image( 'plat-barnes-noble' ) ); ?>" alt="Barnes & Noble"></div>
        <div class="platform-logo" aria-label="Amazon"><img data-asset="plat-amazon" src="<?php echo esc_url( teebe_site_image( 'plat-amazon' ) ); ?>" alt="Amazon"></div>
        <div class="platform-logo" aria-label="AbeBooks"><img data-asset="plat-abebooks" src="<?php echo esc_url( teebe_site_image( 'plat-abebooks' ) ); ?>" alt="AbeBooks"></div>
        <div class="platform-logo" aria-label="Penguin Random House"><img data-asset="plat-penguin-random-house" src="<?php echo esc_url( teebe_site_image( 'plat-penguin-random-house' ) ); ?>" alt="Penguin Random House"></div>
        <div class="platform-logo" aria-label="Scribd"><img data-asset="plat-scribd" src="<?php echo esc_url( teebe_site_image( 'plat-scribd' ) ); ?>" alt="Scribd"></div>
        <div class="platform-logo" aria-label="Rakuten Kobo"><img data-asset="plat-rakuten-kobo" src="<?php echo esc_url( teebe_site_image( 'plat-rakuten-kobo' ) ); ?>" alt="Rakuten Kobo"></div>
        <div class="platform-logo" aria-label="Barnes & Noble"><img data-asset="plat-barnes-noble" src="<?php echo esc_url( teebe_site_image( 'plat-barnes-noble' ) ); ?>" alt="Barnes & Noble"></div>
        <div class="platform-logo" aria-label="Amazon"><img data-asset="plat-amazon" src="<?php echo esc_url( teebe_site_image( 'plat-amazon' ) ); ?>" alt="Amazon"></div>
      </div>
    </div>
  </div>
</section>

<section class="featured" id="home-work-preview" aria-label="Work we're proud of" aria-roledescription="carousel">
  <div class="container">
    <div class="book-carousel"><div class="featured-grid book-slide is-active" role="group" aria-roledescription="slide" aria-label="1 of 6: From the White House to the Outhouse">
    <div class="book-stage">
      <div class="book-pedestal"></div>
      <img class="featured-book" data-asset="cover-1" src="<?php echo esc_url( teebe_site_image( 'cover-1' ) ); ?>" alt="From the White House to the Outhouse book cover">
    </div>
    <div class="featured-copy">
      <div class="eyebrow">Work we're proud of</div>
      <h2>From the White House<br>to the Outhouse</h2>
      <p class="short">A bold and deeply personal title brought to life with careful editorial attention and a distinctive visual identity. This project reflects our approach to shaping an author's voice into a polished book designed to connect with readers and leave a lasting impression.</p>
      <div class="featured-stat">
        <div><strong>Editorial</strong><span>Structure, clarity & polish</span></div>
        <div><strong>Presentation</strong><span>Reader ready finish</span></div>
        <div><strong>Publishing</strong><span>Platform preparation</span></div>
      </div>
      <a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/book-consultation/' ) ); ?>">Start Your Book →</a>
    </div>
  </div>
<div class="featured-grid book-slide book-slide-new" role="group" aria-roledescription="slide" aria-label="2 of 6: Mila and the Gentle Dino" aria-hidden="true" inert>
    <div class="book-stage"><div class="book-pedestal"></div><img class="featured-book" data-asset="cover-2" src="<?php echo esc_url( teebe_site_image( 'cover-2' ) ); ?>" alt="Mila and the Gentle Dino book cover" width="1024" height="1536"></div>
    <div class="featured-copy">
      <div class="eyebrow">Work we're proud of</div>
      <h2>Mila and the Gentle Dino</h2>
      <p class="book-genre">Children’s fiction</p>
      <p class="short">Mila and the Gentle Dino is a heartwarming story about an unexpected friendship between a curious young girl and a kind-hearted dinosaur who others are afraid to understand. As Mila spends time with her new friend, she discovers that being different does not mean being frightening, and that kindness begins with listening, understanding, and seeing the world through someone else’s eyes.</p><p class="short">Their friendship introduces children to empathy in a simple way: listening to others, noticing how they feel, including those who seem different, and choosing kindness before making assumptions.</p>
      <p class="book-tagline"><em>A gentle story about friendship, empathy and seeing beyond first impressions.</em></p>
      <a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/book-consultation/' ) ); ?>">Start Your Book →</a>
    </div>
  </div>
<div class="featured-grid book-slide book-slide-new" role="group" aria-roledescription="slide" aria-label="3 of 6: The Ghost of Blackthorn Palace" aria-hidden="true" inert>
    <div class="book-stage"><div class="book-pedestal"></div><img class="featured-book" data-asset="cover-3" src="<?php echo esc_url( teebe_site_image( 'cover-3' ) ); ?>" alt="The Ghost of Blackthorn Palace book cover" width="1024" height="1536"></div>
    <div class="featured-copy">
      <div class="eyebrow">Work we're proud of</div>
      <h2>The Ghost of Blackthorn Palace</h2>
      <p class="book-genre">Gothic fiction</p>
      <p class="short">The Ghost of Blackthorn Palace follows a young woman drawn to an abandoned estate where locals refuse to set foot after dark. Inside, she begins seeing the ghost of a girl who died in the palace decades ago, but the spirit is not simply haunting the halls. She is trying to reveal what happened to her.</p><p class="short">As buried family secrets surface and the palace grows increasingly hostile, one question becomes impossible to ignore: is the ghost asking for help, or waiting for someone to take her place?</p>
      <p class="book-tagline"><em>Some stories are haunted by ghosts. Others by the truth they refuse to bury.</em></p>
      <a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/book-consultation/' ) ); ?>">Start Your Book →</a>
    </div>
  </div>
<div class="featured-grid book-slide book-slide-new" role="group" aria-roledescription="slide" aria-label="4 of 6: The Inner Compass" aria-hidden="true" inert>
    <div class="book-stage"><div class="book-pedestal"></div><img class="featured-book" data-asset="cover-4" src="<?php echo esc_url( teebe_site_image( 'cover-4' ) ); ?>" alt="The Inner Compass book cover" width="1024" height="1536"></div>
    <div class="featured-copy">
      <div class="eyebrow">Work we're proud of</div>
      <h2>The Inner Compass</h2>
      <p class="book-genre">Personal development</p>
      <p class="short">The Inner Compass is a practical guide for anyone who feels successful on the outside but uncertain about what comes next. Through reflection, mindset shifts, and purposeful action, life coach Sophia Bennett helps readers cut through distraction, rebuild confidence, and make decisions that align with the life they genuinely want to create.</p>
      <p class="book-tagline"><em>Find clarity when success no longer tells you what comes next.</em></p>
      <a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/book-consultation/' ) ); ?>">Start Your Book →</a>
    </div>
  </div>
<div class="featured-grid book-slide book-slide-new" role="group" aria-roledescription="slide" aria-label="5 of 6: Rising Through the Storm" aria-hidden="true" inert>
    <div class="book-stage"><div class="book-pedestal"></div><img class="featured-book" data-asset="cover-5" src="<?php echo esc_url( teebe_site_image( 'cover-5' ) ); ?>" alt="Rising Through the Storm book cover" width="1024" height="1536"></div>
    <div class="featured-copy">
      <div class="eyebrow">Work we're proud of</div>
      <h2>Rising Through the Storm</h2>
      <p class="book-genre">Memoir / Autobiography</p>
      <p class="short">Rising Through the Storm is the story of Daniel Mercer, a man shaped by failure, loss, responsibility, and the relentless pressure to keep moving forward. Looking back on the choices that tested him most, he reflects on the moments that changed his direction, the people who influenced his journey, and the lessons that only hardship could teach. It is an honest account of resilience, reinvention, and the belief that even after the hardest seasons, a better chapter can still be written.</p>
      <p class="book-tagline"><em>A story of resilience, reinvention and finding the strength to begin again.</em></p>
      <a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/book-consultation/' ) ); ?>">Start Your Book →</a>
    </div>
  </div>
<div class="featured-grid book-slide book-slide-new" role="group" aria-roledescription="slide" aria-label="6 of 6: The Other Side of Maybe" aria-hidden="true" inert>
    <div class="book-stage"><div class="book-pedestal"></div><img class="featured-book" data-asset="cover-6" src="<?php echo esc_url( teebe_site_image( 'cover-6' ) ); ?>" alt="The Other Side of Maybe book cover" width="1024" height="1536"></div>
    <div class="featured-copy">
      <div class="eyebrow">Work we're proud of</div>
      <h2>The Other Side of Maybe</h2>
      <p class="book-genre">Contemporary fiction</p>
      <p class="short">When a young woman leaves behind the life she knows for a fresh start in an unfamiliar city, she expects distance to make everything simpler. Instead, she finds new friendships, difficult choices, and truths that force her to question the future she thought she wanted. The Other Side of Maybe is a story about courage, belonging, and what can happen when starting over changes more than just your surroundings.</p>
      <p class="book-tagline"><em>Sometimes starting over changes more than where you live.</em></p>
      <a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/book-consultation/' ) ); ?>">Start Your Book →</a>
    </div>
  </div></div>
    <div class="book-controls" aria-label="Book carousel controls">
      <button type="button" data-book-prev aria-label="Previous book">←</button>
      <div class="book-dots" aria-label="Choose a book">
        <button type="button" data-book-index="0" aria-label="View book 1: From the White House to the Outhouse" aria-current="true"></button>
        <button type="button" data-book-index="1" aria-label="View book 2: Mila and the Gentle Dino" aria-current="false"></button>
        <button type="button" data-book-index="2" aria-label="View book 3: The Ghost of Blackthorn Palace" aria-current="false"></button>
        <button type="button" data-book-index="3" aria-label="View book 4: The Inner Compass" aria-current="false"></button>
        <button type="button" data-book-index="4" aria-label="View book 5: Rising Through the Storm" aria-current="false"></button>
        <button type="button" data-book-index="5" aria-label="View book 6: The Other Side of Maybe" aria-current="false"></button>
      </div>
      <span class="book-count" aria-live="off">01 / 06</span>
      <button type="button" data-book-next aria-label="Next book">→</button>
      <button type="button" data-book-pause aria-label="Pause automatic book transitions">Pause</button>
    </div>
    <span class="book-sr-status" aria-live="polite" aria-atomic="true"></span>
  </div>
  <div class="quill" aria-hidden="true">❧</div>
</section>

<section class="services" id="home-services-preview">
  <div class="container">
    <div class="services-shell">
      <div class="services-intro">
        <div class="eyebrow">Our Services</div>
        <h2>Write, Refine, Design & Publish Your Book With Us</h2>
        <p>Focused book services from manuscript development to publishing and promotion — built around the stage your project is in now.</p>
        <a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Explore All Services →</a>
      </div>

      <article class="service-feature" data-service="Book Publishing">
        <div class="service-mark">▤</div>
        <h3>Book Publishing</h3>
        <p>Prepare your book for release with professional formatting, publishing guidance and platform ready support for digital and print destinations.</p>
      </article>
    </div>

    <div class="service-grid">
      <article class="service-card" data-service="Book Editing">
        <div class="service-mark">✎</div>
        <h3>Book Editing</h3>
        <p>Improve structure, clarity, consistency and flow while preserving the voice of the book.</p>
      </article>

      <article class="service-card" data-service="Book Cover Design">
        <div class="service-mark">◈</div>
        <h3>Book Cover Design</h3>
        <p>Create a strong visual first impression with a distinctive, genre aware cover direction.</p>
      </article>

      <article class="service-card" data-service="Proofreading">
        <div class="service-mark">⌕</div>
        <h3>Proofreading</h3>
        <p>Resolve spelling, grammar, punctuation and formatting issues before publication.</p>
      </article>

      <article class="service-card" data-service="Book Illustrations">
        <div class="service-mark">✦</div>
        <h3>Book Illustrations</h3>
        <p>Bring ideas and scenes to life with illustration support tailored to the book and its readers.</p>
      </article>

      <article class="service-card" data-service="Children Books">
        <div class="service-mark">★</div>
        <h3>Children Books</h3>
        <p>Develop engaging, age appropriate content and presentation for younger readers.</p>
      </article>

      <article class="service-card" data-service="Book Marketing">
        <div class="service-mark">↗</div>
        <h3>Book Marketing</h3>
        <p>Support discoverability through launch planning, promotional content and reader facing campaigns.</p>
      </article>
    </div>
  </div>
</section>

<section class="process" id="home-process-preview">
  <div class="container">
    <div class="process-heading">
      <h2>Our Process</h2>
      <p class="process-tagline">Simple. Collaborative. Effective.</p>
    </div>
    <div class="steps">
      <div class="step"><div class="step-icon">☵</div><h3>Consult</h3><p>We learn about your book, your goals and where you are in the journey.</p></div>
      <div class="step"><div class="step-icon">▤</div><h3>Edit</h3><p>We refine the manuscript with careful attention to clarity, structure and voice.</p></div>
      <div class="step"><div class="step-icon">✓</div><h3>Review</h3><p>You review the work, share feedback and approve the direction.</p></div>
      <div class="step"><div class="step-icon">↗</div><h3>Publish</h3><p>We prepare the final files and guide the project toward publication.</p></div>
    </div>
  </div>
</section>

<section class="testimonials">
  <div class="container">
    <div class="testimonial-heading">
      <div class="eyebrow">Testimonials</div>
      <h2>What Our Clients Have to Say</h2>
    </div>
    <div class="testimonial-grid">
      <article class="testimonial-card">
        <div class="testimonial-quote-mark" aria-hidden="true">“</div>
        <blockquote class="testimonial-quote">The proofreading gave my manuscript the polish it needed, and the publishing support made the final stage feel clear and manageable. I was genuinely pleased with how everything came together.</blockquote>
        <div class="testimonial-client">
          <strong>Steve Elliott</strong>
          <span>Author • Proofreading &amp; Publishing</span>
        </div>
      </article>

      <article class="testimonial-card">
        <div class="testimonial-quote-mark" aria-hidden="true">“</div>
        <blockquote class="testimonial-quote">I felt supported throughout the process. The manuscript was handled with care, the communication was clear, and the finished book felt far more polished and ready for publication.</blockquote>
        <div class="testimonial-client">
          <strong>Linda</strong>
          <span>Author • Editorial &amp; Publishing Support</span>
        </div>
      </article>

      <article class="testimonial-card">
        <div class="testimonial-quote-mark" aria-hidden="true">“</div>
        <blockquote class="testimonial-quote">The team helped turn my children's book idea into a finished book I was genuinely proud to publish. The support throughout development was excellent, and seeing the book perform so well on Amazon made the experience even more rewarding.</blockquote>
        <div class="testimonial-client">
          <strong>Ashley</strong>
          <span>Children's Book Author • Book Development &amp; Publishing</span>
        </div>
      </article>

      <article class="testimonial-card">
        <div class="testimonial-quote-mark" aria-hidden="true">“</div>
        <blockquote class="testimonial-quote">The illustrations brought the book to life in a way that matched the vision I had from the beginning. Having the same team support the publication process made the whole journey feel much easier and more cohesive.</blockquote>
        <div class="testimonial-client">
          <strong>Olivia</strong>
          <span>Author • Illustration &amp; Publishing Support</span>
        </div>
      </article>
    </div>
  </div>
</section>

<section class="final-cta">
  <div class="container">
    <h2>Publish a Book Readers Love<br><span>Build Momentum That Sells</span></h2>
    <p>From writing and editing to design, publishing and marketing, we help prepare your book to connect with readers and compete in the marketplace.</p>
    <a class="btn btn-blue" href="<?php echo esc_url( home_url( '/book-consultation/' ) ); ?>">Get Started</a>
  </div>
</section>
</section>

<?php
get_footer();
