<?php
/**
 * Renders the theme's templates outside WordPress so the WordPress build can
 * be compared with the published static site.
 *
 * This is a development tool. It lives beside sync-from-static.py, outside the
 * theme folder, so it is never part of the installable ZIP. It implements just
 * enough of the WordPress API for the theme's templates to run: enough to prove
 * the markup, asset URLs, head metadata and book structure match the static
 * pages, not to emulate WordPress.
 *
 * Usage:  php wordpress/verify-theme.php <output-directory>
 *
 * @package the-ebook-edit
 */

// phpcs:disable WordPress.NamingConventions, WordPress.Security, Squiz.Commenting

define( 'ABSPATH', __DIR__ . '/' );
define( 'MINUTE_IN_SECONDS', 60 );
define( 'OBJECT', 'OBJECT' );

$theme_dir = __DIR__ . '/the-ebook-edit';
$out_dir   = isset( $argv[1] ) ? rtrim( $argv[1], '/' ) : __DIR__ . '/preview';
$site_url  = 'http://127.0.0.1:8791';

// Where the preview serves the theme's own files from.
$assets_base = '/theme';

$GLOBALS['teebe_preview'] = array(
	'key'      => 'front',
	'slug'     => '',
	'is_404'   => false,
	'is_front' => true,
	'template' => '',
	'inline'   => array(),
	'styles'   => array(),
	'scripts'  => array(),
	'actions'  => array(),
	'filters'  => array(),
);

/* ---------------------------------------------------------------- plumbing */

function add_action( $hook, $callback, $priority = 10, $args = 1 ) {
	$GLOBALS['teebe_preview']['actions'][ $hook ][ $priority ][] = $callback;
}

function add_filter( $hook, $callback, $priority = 10, $args = 1 ) {
	$GLOBALS['teebe_preview']['filters'][ $hook ][ $priority ][] = $callback;
}

function do_action( $hook ) {
	$hooks = $GLOBALS['teebe_preview']['actions'][ $hook ] ?? array();
	ksort( $hooks );

	foreach ( $hooks as $callbacks ) {
		foreach ( $callbacks as $callback ) {
			call_user_func( $callback );
		}
	}
}

function apply_filters( $hook, $value ) {
	$hooks = $GLOBALS['teebe_preview']['filters'][ $hook ] ?? array();
	ksort( $hooks );

	foreach ( $hooks as $callbacks ) {
		foreach ( $callbacks as $callback ) {
			$value = call_user_func( $callback, $value );
		}
	}

	return $value;
}

function remove_action( $hook, $callback ) {}

/* ------------------------------------------------------------- WP surface  */

function __( $text, $domain = '' ) {
	return $text; }
function esc_html__( $text, $domain = '' ) {
	return esc_html( $text ); }
function esc_attr__( $text, $domain = '' ) {
	return esc_attr( $text ); }
function esc_html_e( $text, $domain = '' ) {
	echo esc_html( $text ); }
function esc_attr_e( $text, $domain = '' ) {
	echo esc_attr( $text ); }
function esc_html( $text ) {
	return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' ); }
function esc_attr( $text ) {
	return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' ); }
function esc_url( $url ) {
	return htmlspecialchars( (string) $url, ENT_QUOTES, 'UTF-8' ); }
function esc_url_raw( $url ) {
	return (string) $url; }
function untrailingslashit( $string ) {
	return rtrim( (string) $string, '/' ); }
function trailingslashit( $string ) {
	return untrailingslashit( $string ) . '/'; }
function wp_json_encode( $data, $flags = 0 ) {
	return json_encode( $data, $flags ); }
function language_attributes() {
	echo 'lang="en"'; }
function bloginfo( $what ) {
	echo 'charset' === $what ? 'utf-8' : ''; }
function get_bloginfo( $what ) {
	return 'The Ebook Edit'; }

function home_url( $path = '' ) {
	global $site_url;
	return $site_url . '/' . ltrim( (string) $path, '/' );
}
function site_url( $path = '' ) {
	return home_url( $path ); }

function get_theme_file_uri( $rel = '' ) {
	global $assets_base;
	return $assets_base . '/' . ltrim( (string) $rel, '/' );
}
function get_theme_file_path( $rel = '' ) {
	global $theme_dir;
	return $theme_dir . '/' . ltrim( (string) $rel, '/' );
}
function get_stylesheet_uri() {
	return get_theme_file_uri( 'style.css' ); }
function get_template_directory_uri() {
	return get_theme_file_uri( '' ); }

function wp_enqueue_style( $handle, $src = '', $deps = array(), $ver = '' ) {
	$GLOBALS['teebe_preview']['styles'][ $handle ] = $src . ( $ver ? '?ver=' . $ver : '' );
}
function wp_enqueue_script( $handle, $src = '', $deps = array(), $ver = '', $args = array() ) {
	$GLOBALS['teebe_preview']['scripts'][ $handle ] = $src . ( $ver ? '?ver=' . $ver : '' );
}

function is_404() {
	return $GLOBALS['teebe_preview']['is_404']; }
function is_front_page() {
	return $GLOBALS['teebe_preview']['is_front']; }
function is_page() {
	return ! is_404() && ! is_front_page(); }
function is_admin() {
	return false; }
function has_site_icon() {
	return false; }
function have_posts() {
	return false; }
function the_post() {}
function get_queried_object_id() {
	return 1; }
function get_post_field( $field, $id = 0 ) {
	return $GLOBALS['teebe_preview']['slug']; }
function get_page_by_path( $slug, $output = null, $type = 'page' ) {
	return null; }
function get_post( $id ) {
	return null; }
function do_shortcode( $content ) {
	return $content; }

function is_page_template( $template = '' ) {
	return $GLOBALS['teebe_preview']['template'] === $template; }
function get_page_template_slug( $id = 0 ) {
	return $GLOBALS['teebe_preview']['template']; }
function is_singular( $types = '' ) {
	return ! is_404(); }
function wp_doing_ajax() {
	return false; }
function wp_parse_url( $url, $component = -1 ) {
	return parse_url( $url, $component ); }
function sanitize_key( $key ) {
	return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $key ) ); }
function sanitize_text_field( $text ) {
	return trim( preg_replace( '/[\r\n\t ]+/', ' ', wp_strip_all_tags( (string) $text ) ) ); }
function wp_strip_all_tags( $text ) {
	return strip_tags( (string) $text ); }
function wp_unslash( $value ) {
	return is_string( $value ) ? stripslashes( $value ) : $value; }
function esc_js( $text ) {
	return addslashes( (string) $text ); }
function wp_add_inline_script( $handle, $data, $position = 'after' ) {
	$GLOBALS['teebe_preview']['inline'][ $handle ][] = $data; }
function _e( $text, $domain = '' ) {
	echo $text; }
function current_user_can( $cap ) {
	return false; }
function add_theme_support() {}
function add_theme_page() {}
function wp_get_canonical_url() {
	return home_url( '/' ); }
function wp_get_document_title() {
	return apply_filters( 'pre_get_document_title', 'The Ebook Edit' ); }
function get_permalink( $post = 0 ) {
	$slug = $GLOBALS['teebe_preview']['slug'];
	return ( '' === $slug || 'front' === $slug ) ? home_url( '/' ) : home_url( '/' . $slug . '/' ); }
function get_the_title() {
	return ''; }
function the_title() {}
function the_content() {}
function posts_nav_link() {}
function wp_nonce_field() {}
function submit_button() {}
function admin_url( $path = '' ) {
	return home_url( '/wp-admin/' . ltrim( $path, '/' ) ); }
function get_transient( $key ) {
	return false; }
function delete_transient( $key ) {}
function set_transient( $key, $value, $ttl ) {}
function wp_safe_redirect( $url ) {}
function check_admin_referer() {}
function wp_die( $message ) {
	exit( 1 ); }
function wp_trash_post( $id ) {}
function wp_insert_post( $arr, $error = false ) {
	return 0; }
function wp_update_post( $arr ) {}
function update_post_meta() {}
function update_option() {}
function get_option( $name, $default = false ) {
	return $default; }
function get_post_status( $id ) {
	return 'draft'; }
function flush_rewrite_rules() {}

function body_class() {
	$classes = apply_filters( 'body_class', array() );
	echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
}

/**
 * Emulates WordPress core's wp_robots(), which runs on wp_head and prints
 * whatever the wp_robots filter returns. Core's own defaults are left out so
 * the check sees only what the theme contributes.
 */
function wp_robots() {
	$robots     = apply_filters( 'wp_robots', array() );
	$directives = array();

	foreach ( $robots as $directive => $value ) {
		if ( true === $value ) {
			$directives[] = $directive;
		} elseif ( $value ) {
			$directives[] = $directive . ':' . $value;
		}
	}

	if ( $directives ) {
		printf( '<meta name="robots" content="%s">' . "\n", esc_attr( implode( ', ', $directives ) ) );
	}
}

function wp_head() {
	// Core's _wp_render_title_tag(), enabled by add_theme_support( 'title-tag' ).
	printf( "<title>%s</title>\n", esc_html( wp_get_document_title() ) );

	do_action( 'wp_head' );
	wp_robots();

	foreach ( $GLOBALS['teebe_preview']['styles'] as $handle => $src ) {
		printf( '<link rel="stylesheet" id="%s-css" href="%s" media="all">' . "\n", esc_attr( $handle ), esc_url( $src ) );
	}
}

function wp_body_open() {
	do_action( 'wp_body_open' );
}

function wp_footer() {
	foreach ( $GLOBALS['teebe_preview']['scripts'] as $handle => $src ) {
		foreach ( $GLOBALS['teebe_preview']['inline'][ $handle ] ?? array() as $inline ) {
			printf( '<script id="%s-js-before">%s</script>' . "\n", esc_attr( $handle ), $inline );
		}

		printf( '<script id="%s-js" src="%s" defer></script>' . "\n", esc_attr( $handle ), esc_url( $src ) );
	}
}

function get_header( $name = '' ) {
	global $theme_dir;
	require $theme_dir . ( '' !== $name ? "/header-{$name}.php" : '/header.php' );
}

function get_footer( $name = '' ) {
	global $theme_dir;
	require $theme_dir . ( '' !== $name ? "/footer-{$name}.php" : '/footer.php' );
}

/* --------------------------------------------------------------- rendering */

require $theme_dir . '/functions.php';
do_action( 'after_setup_theme' );
do_action( 'wp_enqueue_scripts' );

$pages = array(
	// The approved website, ported from the final approved design. There is
	// no static counterpart to compare these against: the static site in the
	// repository root is the earlier book presentation, which this release
	// replaces everywhere except the Insights library.
	'front'                           => array( '', 'front-page.php' ),
	'services'                        => array( '', 'page-services.php' ),
	'writing'                         => array( '', 'page-writing.php' ),
	'editing'                         => array( '', 'page-editing.php' ),
	'publishing'                      => array( '', 'page-publishing.php' ),
	'process'                         => array( '', 'page-process.php' ),
	'portfolio'                       => array( '', 'page-portfolio.php' ),
	'about'                           => array( '', 'page-about.php' ),
	'contact'                         => array( '', 'page-contact.php' ),
	'book-consultation'               => array( '', 'page-book-consultation.php' ),
	'thank-you'                       => array( '', 'page-thank-you.php' ),
	'privacy-policy'                  => array( '', 'page-privacy-policy.php' ),
	'terms-and-conditions'            => array( '', 'page-terms-and-conditions.php' ),
	'404'                             => array( '', '404.php' ),
	// The Insights library, unchanged by this release and still comparable
	// with the static pages it was generated from.
	'insights'                        => array( 'insights.html', 'page-insights.php' ),
	'turn-expertise-into-an-ebook'    => array( 'insights/turn-expertise-into-an-ebook.html', 'template-insight-turn-expertise.php' ),
	'editing-levels-explained'        => array( 'insights/editing-levels-explained.html', 'template-insight-editing-levels.php' ),
	'pre-publishing-checklist'        => array( 'insights/pre-publishing-checklist.html', 'template-insight-pre-publishing.php' ),
	'kindle-and-ebook-platform-guide' => array( 'insights/kindle-and-ebook-platform-guide.html', 'template-insight-kindle-platforms.php' ),
	// The Meta Ads landing page, likewise with no static counterpart.
	'start-your-book'                 => array( '', 'template-landing-meta-ads.php' ),
);

if ( ! is_dir( $out_dir ) ) {
	mkdir( $out_dir, 0777, true );
}

foreach ( $pages as $key => $page ) {
	$GLOBALS['teebe_preview']['key']      = $key;
	$GLOBALS['teebe_preview']['slug']     = $key;
	$GLOBALS['teebe_preview']['is_404']   = ( '404' === $key );
	$GLOBALS['teebe_preview']['is_front'] = ( 'front' === $key );
	// Only a page assigned a "Template Name" template reports one.
	$GLOBALS['teebe_preview']['template'] = 0 === strpos( $page[1], 'template-' ) ? $page[1] : '';
	// Each page resolves its own assets, exactly as a real request would: the
	// landing page swaps the book stylesheets for its own.
	$GLOBALS['teebe_preview']['styles']   = array();
	$GLOBALS['teebe_preview']['scripts']  = array();
	$GLOBALS['teebe_preview']['inline']   = array();
	// wp_head and wp_body_open each run once per document; every rendered
	// page here is a new document.
	$GLOBALS['teebe_analytics_printed']          = false;
	$GLOBALS['teebe_analytics_noscript_printed'] = false;
	do_action( 'wp_enqueue_scripts' );

	ob_start();
	require $theme_dir . '/' . $page[1];
	$html = ob_get_clean();

	$name = ( 'front' === $key ) ? 'index' : $key;
	file_put_contents( $out_dir . '/' . $name . '.html', $html );
	echo "rendered {$name}.html\n";
}
