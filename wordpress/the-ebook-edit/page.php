<?php
/**
 * Fallback template for pages an administrator adds after installation.
 *
 * Every page of the approved website has its own page-{slug}.php template.
 * Anything new is rendered in the website's shell with the same prose
 * treatment the legal pages use, so it reads as part of the site.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>

<section class="site-view">
  <section class="page-hero">
    <div class="flow-wrap" aria-hidden="true"><div class="flow-line one"></div><div class="flow-line two"></div></div>
    <div class="container" style="position:relative;z-index:1">
      <div class="v-reveal">
        <h1 class="v-h1" tabindex="-1"><?php the_title(); ?></h1>
      </div>
    </div>
  </section>
  <section class="v-section tight">
    <div class="container">
      <div class="legal v-reveal"><?php the_content(); ?></div>
    </div>
  </section>
</section>

	<?php
endwhile;

get_footer();
