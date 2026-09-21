<?php
/**
 * The Ebook Edit — theme setup, assets, book boot, and contact-form hooks.
 *
 * The theme carries three presentations, each with its own stylesheet and
 * script, and no page ever loads another's:
 *
 *   inc/site.php       the approved website — every public page except the
 *                      two below;
 *   inc/landing.php    the Meta Ads landing page;
 *   header-book.php    the Insights library, which keeps the earlier book
 *                      presentation so its published URLs and content are
 *                      unchanged.
 *
 * Analytics and marketing attribution are shared by all three:
 *
 *   inc/analytics.php    Google Analytics 4, Microsoft Clarity, the events;
 *   inc/attribution.php  campaign attribution on every lead.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_theme_file_path( 'inc/seo-data.php' );
require_once get_theme_file_path( 'inc/seo-meta.php' );
require_once get_theme_file_path( 'inc/setup.php' );
require_once get_theme_file_path( 'inc/landing.php' );
require_once get_theme_file_path( 'inc/site.php' );
require_once get_theme_file_path( 'inc/analytics.php' );
require_once get_theme_file_path( 'inc/attribution.php' );

/**
 * Theme supports.
 *
 * No menu location is registered: the navigation is part of the approved
 * design and is rendered by teebe_site_nav() in inc/site.php.
 */
function teebe_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
}
add_action( 'after_setup_theme', 'teebe_setup' );

/**
 * Each presentation's own assets, versioned by file modification time so
 * browsers pick up changes without the manual cache-busting query string the
 * static site used. No page loads another presentation's stylesheet.
 */
function teebe_assets() {
	// Measurement and attribution are the same everywhere, and load first
	// so window.teebeTrack exists before any presentation's own script.
	teebe_analytics_assets();
	teebe_attribution_assets();

	// The Meta Ads landing page carries its own complete design system and
	// must not load the website's or the book's, which would fight it. See
	// inc/landing.php.
	if ( teebe_is_landing() ) {
		teebe_landing_assets();
		return;
	}

	// Everything except the Insights library is the approved website.
	if ( ! teebe_is_book_page() ) {
		teebe_site_assets();
		return;
	}

	// style.css carries only the theme header WordPress requires; it is
	// enqueued first so a child theme can still override from it.
	wp_enqueue_style(
		'the-ebook-edit',
		get_stylesheet_uri(),
		array(),
		(string) filemtime( get_theme_file_path( 'style.css' ) )
	);

	wp_enqueue_style(
		'the-ebook-edit-base',
		get_theme_file_uri( 'assets/css/styles.css' ),
		array( 'the-ebook-edit' ),
		(string) filemtime( get_theme_file_path( 'assets/css/styles.css' ) )
	);

	wp_enqueue_style(
		'the-ebook-edit-book',
		get_theme_file_uri( 'assets/css/book.css' ),
		array( 'the-ebook-edit-base' ),
		(string) filemtime( get_theme_file_path( 'assets/css/book.css' ) )
	);

	// Hand-maintained integration layer: makes Contact Form 7's markup match
	// the design. Loaded last so it wins over the plugin's own stylesheet.
	wp_enqueue_style(
		'the-ebook-edit-wordpress',
		get_theme_file_uri( 'assets/css/wordpress.css' ),
		array( 'the-ebook-edit-book' ),
		(string) filemtime( get_theme_file_path( 'assets/css/wordpress.css' ) )
	);

	wp_enqueue_script(
		'the-ebook-edit-book',
		get_theme_file_uri( 'assets/js/book.js' ),
		array(),
		(string) filemtime( get_theme_file_path( 'assets/js/book.js' ) ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'teebe_assets' );

/**
 * Adds the body classes the book stylesheets key off, for the Insights pages
 * whose generated metadata still declares them.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function teebe_body_class( $classes ) {
	$entry = teebe_seo_entry();

	if ( empty( $entry['body_class'] ) ) {
		return $classes;
	}

	foreach ( preg_split( '/\s+/', $entry['body_class'] ) as $class ) {
		if ( '' !== $class && ! in_array( $class, $classes, true ) ) {
			$classes[] = $class;
		}
	}

	return $classes;
}
add_filter( 'body_class', 'teebe_body_class' );

/**
 * Prints the pre-paint mode check so the correct book state renders on the
 * first frame with no layout flash.
 *
 * Called from header-book.php, the Insights library's shell. It must run
 * before paint, which rules out an external file, so it is printed inline and
 * kept byte-for-byte in step with the static pages.
 */
function teebe_boot_script() {
	$entry = teebe_seo_entry();

	if ( empty( $entry['cinematic'] ) ) {
		// Article, legal, thank-you and 404 pages always use the flow layout.
		echo "<script>document.documentElement.classList.add('book-js');</script>\n";
		return;
	}
	?>
<script>
  /* Pre-paint mode check so the correct book state renders on the first
     frame with no layout flash: desktop cinematic, mobile portrait book,
     or the flow fallback. book.js confirms boot; if it ever fails to load,
     the timer clears the classes so all content stays reachable. */
  (function () {
    var c = document.documentElement.classList;
    c.add('book-js');
    try {
      var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (!reduce && window.matchMedia('(min-width: 1000px) and (min-height: 640px)').matches) {
        c.add('book-cinematic');
      } else if (!reduce && window.matchMedia('(max-width: 900px) and (min-width: 360px) and (min-height: 740px)').matches) {
        c.add('book-mbook');
      }
      if (c.contains('book-cinematic') || c.contains('book-mbook')) {
        window.__bookBoot = window.setTimeout(function () {
          c.remove('book-cinematic');
          c.remove('book-mbook');
        }, 2500);
      }
    } catch (e) {}
  })();
</script>
	<?php
}

/**
 * Points robots.txt at the sitemap WordPress generates natively.
 *
 * @param string $output Existing robots.txt body.
 * @param bool   $public Whether the site is set to be indexed.
 * @return string
 */
function teebe_robots_txt( $output, $public ) {
	if ( ! $public ) {
		return $output;
	}

	return "User-agent: *\nAllow: /\n\nSitemap: " . esc_url_raw( home_url( '/wp-sitemap.xml' ) ) . "\n";
}
add_filter( 'robots_txt', 'teebe_robots_txt', 10, 2 );

/**
 * Treats a filled honeypot field as spam. Contact Form 7 has no built-in
 * honeypot, so the hidden hp-field in the form is checked here.
 *
 * @param bool                  $spam       Current spam verdict.
 * @param WPCF7_Submission|null $submission Current submission.
 * @return bool
 */
function teebe_cf7_honeypot_spam( $spam, $submission = null ) {
	if ( $spam ) {
		return $spam;
	}

	if ( ! $submission || ! method_exists( $submission, 'get_posted_data' ) ) {
		return $spam;
	}

	$posted = $submission->get_posted_data();

	return ! empty( $posted['hp-field'] );
}
add_filter( 'wpcf7_spam', 'teebe_cf7_honeypot_spam', 10, 2 );

// The enquiry forms supply their own grid markup, which auto-paragraphing breaks.
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/**
 * Reminds an administrator to install the plugin the enquiry forms depend on.
 */
function teebe_cf7_admin_notice() {
	if ( class_exists( 'WPCF7' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	echo '<div class="notice notice-warning is-dismissible"><p>';
	echo esc_html__( 'The Ebook Edit: install and activate Contact Form 7, then run Appearance → The Ebook Edit Setup to create the enquiry forms. See DEPLOYMENT.md in the theme folder.', 'the-ebook-edit' );
	echo '</p></div>';
}
add_action( 'admin_notices', 'teebe_cf7_admin_notice' );
