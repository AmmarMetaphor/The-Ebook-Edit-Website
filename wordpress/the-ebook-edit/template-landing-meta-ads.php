<?php
/**
 * Template Name: The Ebook Edit — Meta Ads Landing Page
 *
 * The approved Meta Ads landing page, ported into this theme as a page
 * template. Assign it to a page (Pages → Add New → Page Attributes →
 * Template) and that page renders this design at its own address, for
 * example /start-your-book/.
 *
 * This template deliberately does NOT call get_header() or get_footer().
 * The website's own shell opens a <main id="main"> landmark and loads the
 * book stylesheets and the book engine, all of which would fight this
 * page: the landing page carries its own complete design system, its own
 * <header> and <footer>, and its own <main id="landing-view">. Reusing the
 * shell would produce two headers, two main landmarks and two competing
 * stylesheets.
 *
 * Everything WordPress itself needs is still here, so plugins behave
 * normally: language_attributes(), wp_head(), body_class(), wp_body_open()
 * and wp_footer(). inc/landing.php swaps the website's stylesheets for the
 * landing page's own on this template only, so no other page is affected.
 *
 * The markup below is the approved landing page, copied verbatim. The only
 * changes are the ones WordPress requires: image sources now resolve
 * through get_theme_file_uri(), and the prototype's <form> — which had no
 * backend — is rendered by Contact Form 7 so enquiries are really
 * delivered. Do not redesign it.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="flow-wrap" aria-hidden="true">
  <div class="flow-line one"></div>
  <div class="flow-line two"></div>
</div>

<header>
  <div class="container header-inner">
    <a class="brand" href="#home" aria-label="The Ebook Edit home"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/the-ebook-edit-logo.webp' ) ); ?>" alt="The Ebook Edit"></a>
    <a class="btn btn-gold header-cta" href="#contact">Speak with our Consultant</a>
  </div>
</header>

<main id="landing-view" class="page-view is-active">
<section class="hero" id="home">
  <div class="container hero-grid">
    <div class="hero-copy">
        <div class="eyebrow">Professional ebook writing, editing & publishing</div>
        <h1>Let Your Story<br>Become <span>Your<br>Legacy</span></h1>
        <p class="lead">From first idea to final publication, we help shape books that are clear, polished and ready to leave an impression.</p>
        <div class="hero-actions">
          <a class="btn hero-primary-cta" href="#contact">Book your Free Consultation</a>
        </div>
    </div>

    <aside class="lead-card" id="contact">
      <div class="form-icon">✒</div>
      <h3>Let's Bring Your Book to Its Best</h3>
      <p class="intro">Tell us about your project and we'll help identify the right next step.</p>
      <?php
      /*
       * Contact Form 7 renders the six-field enquiry form here. Its body is
       * bundled with the theme at cf7/landing-enquiry.txt and reproduces this
       * page's approved form markup exactly — the same wrappers, labels,
       * dropdown values and error slots — so the rendered page is identical to
       * the approved design while the submission is delivered by WordPress.
       */
      teebe_render_landing_form();
      ?>
    </aside>
  </div>
</section>

<section class="platform-wrap" aria-label="Publishing platforms">
  <div class="platform-label"><strong>Publishing Ready Support</strong> for major platforms</div>
  <div class="platform-marquee">
    <div class="platform-track">
      <div class="platform-group">
        <div class="platform-logo" aria-label="AbeBooks"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-abebooks.webp' ) ); ?>" alt="AbeBooks"></div>
        <div class="platform-logo" aria-label="Penguin Random House"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-penguin-random-house.webp' ) ); ?>" alt="Penguin Random House"></div>
        <div class="platform-logo" aria-label="Scribd"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-scribd.webp' ) ); ?>" alt="Scribd"></div>
        <div class="platform-logo" aria-label="Rakuten Kobo"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-rakuten-kobo.webp' ) ); ?>" alt="Rakuten Kobo"></div>
        <div class="platform-logo" aria-label="Barnes & Noble"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-barnes-noble.webp' ) ); ?>" alt="Barnes & Noble"></div>
        <div class="platform-logo" aria-label="Amazon"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-amazon.webp' ) ); ?>" alt="Amazon"></div>
        <div class="platform-logo" aria-label="AbeBooks"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-abebooks.webp' ) ); ?>" alt="AbeBooks"></div>
        <div class="platform-logo" aria-label="Penguin Random House"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-penguin-random-house.webp' ) ); ?>" alt="Penguin Random House"></div>
        <div class="platform-logo" aria-label="Scribd"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-scribd.webp' ) ); ?>" alt="Scribd"></div>
        <div class="platform-logo" aria-label="Rakuten Kobo"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-rakuten-kobo.webp' ) ); ?>" alt="Rakuten Kobo"></div>
        <div class="platform-logo" aria-label="Barnes & Noble"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-barnes-noble.webp' ) ); ?>" alt="Barnes & Noble"></div>
        <div class="platform-logo" aria-label="Amazon"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-amazon.webp' ) ); ?>" alt="Amazon"></div>
      </div>
      <div class="platform-group" aria-hidden="true">
        <div class="platform-logo" aria-label="AbeBooks"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-abebooks.webp' ) ); ?>" alt="AbeBooks"></div>
        <div class="platform-logo" aria-label="Penguin Random House"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-penguin-random-house.webp' ) ); ?>" alt="Penguin Random House"></div>
        <div class="platform-logo" aria-label="Scribd"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-scribd.webp' ) ); ?>" alt="Scribd"></div>
        <div class="platform-logo" aria-label="Rakuten Kobo"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-rakuten-kobo.webp' ) ); ?>" alt="Rakuten Kobo"></div>
        <div class="platform-logo" aria-label="Barnes & Noble"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-barnes-noble.webp' ) ); ?>" alt="Barnes & Noble"></div>
        <div class="platform-logo" aria-label="Amazon"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-amazon.webp' ) ); ?>" alt="Amazon"></div>
        <div class="platform-logo" aria-label="AbeBooks"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-abebooks.webp' ) ); ?>" alt="AbeBooks"></div>
        <div class="platform-logo" aria-label="Penguin Random House"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-penguin-random-house.webp' ) ); ?>" alt="Penguin Random House"></div>
        <div class="platform-logo" aria-label="Scribd"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-scribd.webp' ) ); ?>" alt="Scribd"></div>
        <div class="platform-logo" aria-label="Rakuten Kobo"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-rakuten-kobo.webp' ) ); ?>" alt="Rakuten Kobo"></div>
        <div class="platform-logo" aria-label="Barnes & Noble"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-barnes-noble.webp' ) ); ?>" alt="Barnes & Noble"></div>
        <div class="platform-logo" aria-label="Amazon"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/platform-amazon.webp' ) ); ?>" alt="Amazon"></div>
      </div>
    </div>
  </div>
</section>

<section class="featured" id="work" aria-label="Work we're proud of" aria-roledescription="carousel">
  <div class="container">
    <div class="book-carousel"><div class="featured-grid book-slide is-active" role="group" aria-roledescription="slide" aria-label="1 of 6: From the White House to the Outhouse">
    <div class="book-stage">
      <div class="book-pedestal"></div>
      <img class="featured-book" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/book-1-from-the-white-house-to-the-outhouse.jpg' ) ); ?>" alt="From the White House to the Outhouse book cover">
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
      <a class="btn btn-ghost" href="#contact">Start Your Project →</a>
    </div>
  </div>
<div class="featured-grid book-slide book-slide-new" role="group" aria-roledescription="slide" aria-label="2 of 6: Mila and the Gentle Dino" aria-hidden="true" inert>
    <div class="book-stage"><div class="book-pedestal"></div><img class="featured-book" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/book-2-mila-and-the-gentle-dino.webp' ) ); ?>" alt="Mila and the Gentle Dino book cover" width="1024" height="1536"></div>
    <div class="featured-copy">
      <div class="eyebrow">Work we're proud of</div>
      <h2>Mila and the Gentle Dino</h2>
      <p class="book-genre">Children’s fiction</p>
      <p class="short">Mila and the Gentle Dino is a heartwarming story about an unexpected friendship between a curious young girl and a kind-hearted dinosaur who others are afraid to understand. As Mila spends time with her new friend, she discovers that being different does not mean being frightening, and that kindness begins with listening, understanding, and seeing the world through someone else’s eyes.</p><p class="short">Their friendship introduces children to empathy in a simple way: listening to others, noticing how they feel, including those who seem different, and choosing kindness before making assumptions.</p>
      <p class="book-tagline"><em>A gentle story about friendship, empathy and seeing beyond first impressions.</em></p>
      <a class="btn btn-ghost" href="#contact">Start Your Project →</a>
    </div>
  </div>
<div class="featured-grid book-slide book-slide-new" role="group" aria-roledescription="slide" aria-label="3 of 6: The Ghost of Blackthorn Palace" aria-hidden="true" inert>
    <div class="book-stage"><div class="book-pedestal"></div><img class="featured-book" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/book-3-the-ghost-of-blackthorn-palace.webp' ) ); ?>" alt="The Ghost of Blackthorn Palace book cover" width="1024" height="1536"></div>
    <div class="featured-copy">
      <div class="eyebrow">Work we're proud of</div>
      <h2>The Ghost of Blackthorn Palace</h2>
      <p class="book-genre">Gothic fiction</p>
      <p class="short">The Ghost of Blackthorn Palace follows a young woman drawn to an abandoned estate where locals refuse to set foot after dark. Inside, she begins seeing the ghost of a girl who died in the palace decades ago, but the spirit is not simply haunting the halls. She is trying to reveal what happened to her.</p><p class="short">As buried family secrets surface and the palace grows increasingly hostile, one question becomes impossible to ignore: is the ghost asking for help, or waiting for someone to take her place?</p>
      <p class="book-tagline"><em>Some stories are haunted by ghosts. Others by the truth they refuse to bury.</em></p>
      <a class="btn btn-ghost" href="#contact">Start Your Project →</a>
    </div>
  </div>
<div class="featured-grid book-slide book-slide-new" role="group" aria-roledescription="slide" aria-label="4 of 6: The Inner Compass" aria-hidden="true" inert>
    <div class="book-stage"><div class="book-pedestal"></div><img class="featured-book" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/book-4-the-inner-compass.webp' ) ); ?>" alt="The Inner Compass book cover" width="1024" height="1536"></div>
    <div class="featured-copy">
      <div class="eyebrow">Work we're proud of</div>
      <h2>The Inner Compass</h2>
      <p class="book-genre">Personal development</p>
      <p class="short">The Inner Compass is a practical guide for anyone who feels successful on the outside but uncertain about what comes next. Through reflection, mindset shifts, and purposeful action, life coach Sophia Bennett helps readers cut through distraction, rebuild confidence, and make decisions that align with the life they genuinely want to create.</p>
      <p class="book-tagline"><em>Find clarity when success no longer tells you what comes next.</em></p>
      <a class="btn btn-ghost" href="#contact">Start Your Project →</a>
    </div>
  </div>
<div class="featured-grid book-slide book-slide-new" role="group" aria-roledescription="slide" aria-label="5 of 6: Rising Through the Storm" aria-hidden="true" inert>
    <div class="book-stage"><div class="book-pedestal"></div><img class="featured-book" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/book-5-rising-through-the-storm.webp' ) ); ?>" alt="Rising Through the Storm book cover" width="1024" height="1536"></div>
    <div class="featured-copy">
      <div class="eyebrow">Work we're proud of</div>
      <h2>Rising Through the Storm</h2>
      <p class="book-genre">Memoir / Autobiography</p>
      <p class="short">Rising Through the Storm is the story of Daniel Mercer, a man shaped by failure, loss, responsibility, and the relentless pressure to keep moving forward. Looking back on the choices that tested him most, he reflects on the moments that changed his direction, the people who influenced his journey, and the lessons that only hardship could teach. It is an honest account of resilience, reinvention, and the belief that even after the hardest seasons, a better chapter can still be written.</p>
      <p class="book-tagline"><em>A story of resilience, reinvention and finding the strength to begin again.</em></p>
      <a class="btn btn-ghost" href="#contact">Start Your Project →</a>
    </div>
  </div>
<div class="featured-grid book-slide book-slide-new" role="group" aria-roledescription="slide" aria-label="6 of 6: The Other Side of Maybe" aria-hidden="true" inert>
    <div class="book-stage"><div class="book-pedestal"></div><img class="featured-book" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/book-6-the-other-side-of-maybe.webp' ) ); ?>" alt="The Other Side of Maybe book cover" width="1024" height="1536"></div>
    <div class="featured-copy">
      <div class="eyebrow">Work we're proud of</div>
      <h2>The Other Side of Maybe</h2>
      <p class="book-genre">Contemporary fiction</p>
      <p class="short">When a young woman leaves behind the life she knows for a fresh start in an unfamiliar city, she expects distance to make everything simpler. Instead, she finds new friendships, difficult choices, and truths that force her to question the future she thought she wanted. The Other Side of Maybe is a story about courage, belonging, and what can happen when starting over changes more than just your surroundings.</p>
      <p class="book-tagline"><em>Sometimes starting over changes more than where you live.</em></p>
      <a class="btn btn-ghost" href="#contact">Start Your Project →</a>
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

<section class="services" id="services">
  <div class="container">
    <div class="services-shell">
      <div class="services-intro">
        <div class="eyebrow">Our Services</div>
        <h2>Write, Refine, Design & Publish Your Book With Us.</h2>
        <p>Focused book services from manuscript development to publishing and promotion — built around the stage your project is in now.</p>
        <a class="btn btn-ghost" href="#contact">Start a Project →</a>
      </div>

      <article class="service-feature" data-service="Book Publishing">
        <div class="service-mark">▤</div>
        <h3>Book Publishing</h3>
        <p>Prepare your book for release with professional formatting, publishing guidance and platform ready support for digital and print destinations.</p>
        <a class="service-link" href="#contact">Discuss Publishing →</a>
      </article>
    </div>

    <div class="service-grid">
      <article class="service-card" data-service="Book Editing">
        <div class="service-mark">✎</div>
        <h3>Book Editing</h3>
        <p>Improve structure, clarity, consistency and flow while preserving the voice of the book.</p>
        <a class="service-link" href="#contact">Read More →</a>
      </article>

      <article class="service-card" data-service="Book Cover Design">
        <div class="service-mark">◈</div>
        <h3>Book Cover Design</h3>
        <p>Create a strong visual first impression with a distinctive, genre aware cover direction.</p>
        <a class="service-link" href="#contact">Read More →</a>
      </article>

      <article class="service-card" data-service="Proofreading">
        <div class="service-mark">⌕</div>
        <h3>Proofreading</h3>
        <p>Resolve spelling, grammar, punctuation and formatting issues before publication.</p>
        <a class="service-link" href="#contact">Read More →</a>
      </article>

      <article class="service-card" data-service="Book Illustrations">
        <div class="service-mark">✦</div>
        <h3>Book Illustrations</h3>
        <p>Bring ideas and scenes to life with illustration support tailored to the book and its readers.</p>
        <a class="service-link" href="#contact">Read More →</a>
      </article>

      <article class="service-card" data-service="Children Books">
        <div class="service-mark">★</div>
        <h3>Children Books</h3>
        <p>Develop engaging, age appropriate content and presentation for younger readers.</p>
        <a class="service-link" href="#contact">Read More →</a>
      </article>

      <article class="service-card" data-service="Book Marketing">
        <div class="service-mark">↗</div>
        <h3>Book Marketing</h3>
        <p>Support discoverability through launch planning, promotional content and reader facing campaigns.</p>
        <a class="service-link" href="#contact">Read More →</a>
      </article>
    </div>
  </div>
</section>

<section class="process" id="process">
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
    <h2>Publish a Book Readers Love.<br><span>Build Momentum That Sells.</span></h2>
    <p>From writing and editing to design, publishing and marketing, we help prepare your book to connect with readers and compete in the marketplace.</p>
    <a class="btn btn-blue" href="#contact">Get Started Today →</a>
  </div>
</section>
</main>

<div id="about-us-view" class="page-view internal-page" hidden>
  <section class="ip-hero">
    <div class="container">
      <a class="ip-back" href="#home">← Back to The Ebook Edit</a>
      <div class="eyebrow">About The Ebook Edit</div>
      <h1 class="ip-title" tabindex="-1">Let Your Story Become Your Legacy.</h1>
      <p class="ip-intro">The Ebook Edit helps authors, experts and organisations turn ideas, existing material and manuscripts into professionally developed books.</p>
    </div>
  </section>
  <section class="ip-body">
    <div class="container">
      <div class="ip-panel ip-narrow">
        <p>From writing and editorial refinement to cover design, illustration, formatting, publishing and promotional support, we can support one stage of a project or help guide a book from concept to publication.</p>
        <p>Our approach centres on clarity, collaboration and careful presentation, beginning with an understanding of the book, its intended readers and the stage it has already reached.</p>
      </div>

      <div class="ip-section-head">
        <div class="eyebrow">What We Do</div>
        <h2>Support at Every Stage</h2>
      </div>
      <div class="ip-grid">
        <article class="ip-card"><div class="service-mark">✎</div><h3>Ebook Writing</h3><p>Turn an idea, notes or existing material into a structured manuscript.</p></article>
        <article class="ip-card"><div class="service-mark">◈</div><h3>Book Editing</h3><p>Strengthen structure, clarity, consistency and voice.</p></article>
        <article class="ip-card"><div class="service-mark">⌕</div><h3>Proofreading</h3><p>Polish spelling, grammar, punctuation and final presentation.</p></article>
        <article class="ip-card"><div class="service-mark">▣</div><h3>Book Cover Design</h3><p>Create a professional visual identity appropriate to the book and its readers.</p></article>
        <article class="ip-card"><div class="service-mark">✦</div><h3>Book Illustration</h3><p>Develop engaging visual artwork to bring stories and ideas to life.</p></article>
        <article class="ip-card"><div class="service-mark">★</div><h3>Children's Books</h3><p>Support age appropriate writing, presentation and illustration.</p></article>
        <article class="ip-card"><div class="service-mark">▤</div><h3>Formatting</h3><p>Prepare professional digital and print ready book files.</p></article>
        <article class="ip-card"><div class="service-mark">↗</div><h3>Publishing Support</h3><p>Prepare books for appropriate publishing platforms and release.</p></article>
        <article class="ip-card"><div class="service-mark">◉</div><h3>Book Marketing</h3><p>Support launch preparation, positioning and discoverability.</p></article>
      </div>

      <div class="ip-cta">
        <h2>Ready to Begin?</h2>
        <p>Tell us about your book and where it stands today, or write to <a href="mailto:support@theebookedit.com">support@theebookedit.com</a>.</p>
        <div class="ip-cta-actions">
          <a class="btn btn-gold" href="#contact">Start Your Publishing Journey →</a>
          <a class="btn btn-ghost" href="#work">See Our Work</a>
        </div>
      </div>
    </div>
  </section>
</div>
<div id="privacy-policy-view" class="page-view internal-page" hidden>
  <section class="ip-hero">
    <div class="container">
      <a class="ip-back" href="#home">← Back to The Ebook Edit</a>
      <div class="eyebrow">Privacy Policy</div>
      <h1 class="ip-title" tabindex="-1">Your Information Matters.</h1>
      <p class="ip-intro">This Privacy Policy explains how The Ebook Edit may collect, use and protect information submitted when you enquire about our writing, editing, design, formatting, publishing or related book services.</p>
    </div>
  </section>
  <section class="ip-body">
    <div class="container">
      <div class="ip-panel ip-narrow ip-legal">
        <section class="ip-block">
          <h2><span class="ip-num">01</span>Information We Collect</h2>
          <p>Visitors may provide information including:</p>
          <ul>
            <li>Name</li>
            <li>Email address</li>
            <li>Project or service requirements</li>
            <li>Information about a manuscript, book idea or publishing project</li>
            <li>Other information voluntarily included in an enquiry</li>
          </ul>
        </section>
        <section class="ip-block">
          <h2><span class="ip-num">02</span>How We Use Information</h2>
          <p>Information submitted through this website is used to:</p>
          <ul>
            <li>Respond to enquiries</li>
            <li>Understand project requirements</li>
            <li>Communicate about requested services</li>
            <li>Prepare consultations or project discussions</li>
            <li>Improve the enquiry experience</li>
          </ul>
        </section>
        <section class="ip-block">
          <h2><span class="ip-num">03</span>Website and Analytics Information</h2>
          <p>The website may use essential technical functionality and, where configured, analytics or advertising technologies to understand website performance and campaign effectiveness. If such tools are introduced or changed, this policy will be reviewed so that it accurately reflects the live configuration of the website.</p>
        </section>
        <section class="ip-block">
          <h2><span class="ip-num">04</span>Sharing Information</h2>
          <p>The Ebook Edit does not sell personal information submitted through project enquiries.</p>
          <p>Information may be processed by service providers required to support website hosting, communication, analytics or other legitimate business operations where applicable.</p>
        </section>
        <section class="ip-block">
          <h2><span class="ip-num">05</span>Data Retention</h2>
          <p>Information is retained only for as long as reasonably required for the purpose for which it was collected and for legitimate operational or legal requirements.</p>
        </section>
        <section class="ip-block">
          <h2><span class="ip-num">06</span>Third Party Services</h2>
          <p>Third party publishing platforms and external websites operate according to their own policies and terms. The Ebook Edit is not responsible for their content or practices.</p>
        </section>
        <section class="ip-block">
          <h2><span class="ip-num">07</span>Contact</h2>
          <p>Questions about this Privacy Policy or about information you have shared with us can be sent to <a href="mailto:support@theebookedit.com">support@theebookedit.com</a>.</p>
        </section>
      </div>
    </div>
  </section>
</div>
<div id="terms-and-conditions-view" class="page-view internal-page" hidden>
  <section class="ip-hero">
    <div class="container">
      <a class="ip-back" href="#home">← Back to The Ebook Edit</a>
      <div class="eyebrow">Terms &amp; Conditions</div>
      <h1 class="ip-title" tabindex="-1">Clear Terms for Working Together.</h1>
      <p class="ip-intro">These terms provide general information about the use of The Ebook Edit website and enquiries relating to our writing, editing, design, formatting, publishing and promotional services.</p>
    </div>
  </section>
  <section class="ip-body">
    <div class="container">
      <div class="ip-panel ip-narrow ip-legal">
        <section class="ip-block">
          <h2><span class="ip-num">01</span>Website Use</h2>
          <p>The website provides general information about The Ebook Edit and its services. Content may be updated from time to time and does not in itself form a binding offer.</p>
        </section>
        <section class="ip-block">
          <h2><span class="ip-num">02</span>Our Services</h2>
          <p>The Ebook Edit provides professional book services which may include:</p>
          <ul>
            <li>Ebook Writing</li>
            <li>Ghostwriting</li>
            <li>Developmental Editing</li>
            <li>Line and Copy Editing</li>
            <li>Proofreading</li>
            <li>Book Cover Design</li>
            <li>Illustration</li>
            <li>Children's Book Development</li>
            <li>Formatting</li>
            <li>Publishing Support</li>
            <li>Book Marketing Support</li>
          </ul>
        </section>
        <section class="ip-block">
          <h2><span class="ip-num">03</span>Project Scope</h2>
          <p>The exact deliverables, schedule, revision process and responsibilities for each project are confirmed separately before work begins. Any project-specific agreement or proposal takes precedence over the general information on this website.</p>
        </section>
        <section class="ip-block">
          <h2><span class="ip-num">04</span>Client Materials</h2>
          <p>Clients are responsible for ensuring they have appropriate rights to materials supplied for use in a project, including manuscripts, text, images and other content.</p>
        </section>
        <section class="ip-block">
          <h2><span class="ip-num">05</span>Editorial and Creative Work</h2>
          <p>Writing, editing, illustration, design and publishing support involve professional and creative judgement and may require client review and approval at agreed stages.</p>
        </section>
        <section class="ip-block">
          <h2><span class="ip-num">06</span>Publishing Platforms</h2>
          <p>The Ebook Edit is an independent service provider. References to Amazon, Kobo, Barnes &amp; Noble, Scribd and other third party platforms do not imply ownership, endorsement or formal affiliation unless expressly stated. Publication remains subject to the policies and decisions of the relevant platform.</p>
        </section>
        <section class="ip-block">
          <h2><span class="ip-num">07</span>Marketing Results</h2>
          <p>Marketing and promotional support may assist presentation, discoverability and reach, but specific sales, rankings, reviews or commercial outcomes cannot be guaranteed.</p>
        </section>
        <section class="ip-block">
          <h2><span class="ip-num">08</span>Intellectual Property</h2>
          <p>Ownership and usage rights for final project materials are governed by the written agreement applicable to the individual project.</p>
        </section>
        <section class="ip-block">
          <h2><span class="ip-num">09</span>Payments and Cancellation</h2>
          <p>Payment schedules, cancellation terms and any applicable refund arrangements are confirmed in the individual proposal or project agreement.</p>
        </section>
        <section class="ip-block">
          <h2><span class="ip-num">10</span>Contact</h2>
          <p>Questions about these terms can be sent to <a href="mailto:support@theebookedit.com">support@theebookedit.com</a>.</p>
        </section>
      </div>
    </div>
  </section>
</div>

<footer>
  <div class="container footer-inner">
    <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/landing/the-ebook-edit-logo.webp' ) ); ?>" alt="The Ebook Edit">
    <div class="footer-note">
      support@theebookedit.com · Professional ebook writing, editing, formatting and publishing support.<br>
      Platform logos shown for publishing platform reference; trademarks belong to their respective owners.
      <nav class="footer-links" aria-label="Company and legal information">
        <a href="#about-us">About Us</a>
        <a href="#privacy-policy">Privacy Policy</a>
        <a href="#terms-and-conditions">Terms &amp; Conditions</a>
      </nav>
    </div>
  </div>
</footer>

<a class="whatsapp" id="whatsapp" href="#contact" aria-label="Chat with The Ebook Edit on WhatsApp">
  <svg viewBox="0 0 32 32" fill="currentColor" aria-hidden="true"><path d="M16.1 3.2A12.6 12.6 0 0 0 5.4 22.4L3.7 28.8l6.6-1.7A12.6 12.6 0 1 0 16.1 3.2Zm0 22.9c-1.8 0-3.5-.5-5-1.3l-.4-.2-3.9 1 1-3.8-.3-.4A10.3 10.3 0 1 1 16.1 26Zm5.6-7.7c-.3-.2-1.8-.9-2.1-1-.3-.1-.5-.2-.7.2-.2.3-.8 1-.9 1.2-.2.2-.3.2-.7.1-1.8-.9-3-1.6-4.2-3.7-.3-.5.3-.5.9-1.6.1-.2 0-.4 0-.6l-1-2.4c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.2 1.2-1.2 2.9s1.3 3.4 1.4 3.6c.2.2 2.5 3.8 6 5.3 2.3 1 3.2 1.1 4.3.9.7-.1 1.8-.8 2.1-1.5.3-.7.3-1.3.2-1.5-.1-.2-.4-.3-.7-.4Z"/></svg>
  <span class="whatsapp-tip">Chat on WhatsApp</span>
</a>
<div class="toast" id="toast"><?php echo esc_html( teebe_landing_whatsapp_fallback_message() ); ?></div>
<?php wp_footer(); ?>
</body>
</html>
