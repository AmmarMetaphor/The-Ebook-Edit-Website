<?php
/**
 * The Ebook Edit — theme setup, assets, book boot, and contact-form hooks.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_theme_file_path( 'inc/seo-data.php' );
require_once get_theme_file_path( 'inc/seo-meta.php' );
require_once get_theme_file_path( 'inc/setup.php' );

/**
 * Theme supports.
 *
 * No menu location is registered: the book's chapter tabs are the site
 * navigation and they are part of the page templates, exactly as on the
 * published static site.
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
 * The two stylesheets and the book engine, versioned by file modification time
 * so browsers pick up changes without the manual cache-busting query string
 * the static site used.
 */
function teebe_assets() {
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
 * Adds the body classes the book stylesheets key off, so the WordPress page
 * carries the same classes as its static counterpart.
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
 * This is the same script the static site inlines in <head>. It must run
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
 * Renders the book's chapter tabs.
 *
 * The designed pages carry their own copy of this markup, generated from the
 * static site. This helper exists for index.php and page.php, the fallback
 * templates WordPress uses for anything an administrator adds later.
 */
function teebe_book_tabs() {
	$tabs = array(
		'/services/'  => 'Services',
		'/process/'   => 'Process',
		'/portfolio/' => 'Portfolio',
		'/about/'     => 'About',
		'/insights/'  => 'Insights',
	);

	echo '<nav class="book-tabs" aria-label="' . esc_attr__( 'Primary navigation', 'the-ebook-edit' ) . '">';

	foreach ( $tabs as $path => $label ) {
		printf(
			'<a class="book-tab" href="%s">%s</a>',
			esc_url( home_url( $path ) ),
			esc_html( $label )
		);
	}

	printf(
		'<a class="book-tab book-tab-cta" href="%s">%s</a>',
		esc_url( home_url( '/contact/' ) ),
		esc_html__( 'Start a project', 'the-ebook-edit' )
	);

	echo '</nav>';
}

/**
 * Renders one of the site's two enquiry forms.
 *
 * The static site posts to Netlify Forms. WordPress has no equivalent, so the
 * form body is supplied by a Contact Form 7 form whose markup — including the
 * book page's own classes — is given in DEPLOYMENT.md. The shortcode lives in
 * the page's post_content, which is the only thing this theme stores there;
 * all design and copy stay in the templates.
 *
 * When the form has not been configured yet, an on-page notice explains what
 * to do rather than showing a form that cannot deliver anything.
 *
 * @param string $key Form key: 'project-inquiry' or 'publishing-journey'.
 */
function teebe_render_enquiry_form( $key = 'project-inquiry' ) {
	$shortcode = teebe_enquiry_shortcode( $key );

	if ( '' !== $shortcode ) {
		echo do_shortcode( $shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Contact Form 7 escapes its own output.
		return;
	}

	$titles = array(
		'project-inquiry'    => 'Project Inquiry',
		'publishing-journey' => 'Publishing Journey',
	);
	$title  = isset( $titles[ $key ] ) ? $titles[ $key ] : $titles['project-inquiry'];

	echo '<div class="m-pg"><div class="notice"><p><strong>';
	esc_html_e( 'Enquiry form not configured yet.', 'the-ebook-edit' );
	echo '</strong> ';
	printf(
		/* translators: %s: Contact Form 7 form title. */
		esc_html__( 'Install Contact Form 7, create the form named "%s" using the markup in the theme\'s DEPLOYMENT.md, then add its shortcode to this page.', 'the-ebook-edit' ),
		esc_html( $title )
	);
	echo '</p><p>';
	printf(
		/* translators: %s: mailto link. */
		esc_html__( 'In the meantime, enquiries can be sent by email to %s.', 'the-ebook-edit' ),
		'<a href="mailto:support@theebookedit.com">support@theebookedit.com</a>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static markup.
	);
	echo '</p></div></div>';
}

/**
 * The Contact Form 7 shortcode stored on the page that renders a given form.
 *
 * @param string $key Form key.
 * @return string Shortcode, or '' when none is configured.
 */
function teebe_enquiry_shortcode( $key ) {
	$slugs = array(
		'project-inquiry'    => 'contact',
		'publishing-journey' => 'home',
	);

	if ( ! isset( $slugs[ $key ] ) ) {
		return '';
	}

	$page = get_page_by_path( $slugs[ $key ], OBJECT, 'page' );

	if ( ! $page ) {
		return '';
	}

	$content = trim( (string) $page->post_content );

	if ( false === strpos( $content, '[contact-form-7' ) ) {
		return '';
	}

	return $content;
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
	echo esc_html__( 'The Ebook Edit: install and activate Contact Form 7 to enable the enquiry forms. See DEPLOYMENT.md in the theme folder for the form markup and settings.', 'the-ebook-edit' );
	echo '</p></div>';
}
add_action( 'admin_notices', 'teebe_cf7_admin_notice' );
