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
/**
 * Stands in for the Contact Form 7 plugin: the pages that carry an enquiry
 * form report a shortcode, and do_shortcode() expands it using the form body
 * bundled with the theme. That lets the comparison check the real form markup
 * rather than the "not configured yet" notice.
 */
function get_page_by_path( $slug, $output = null, $type = 'page' ) {
	$forms = array(
		'contact' => array( 'project-inquiry', 'start-form', '' ),
		'home'    => array( 'publishing-journey', 'page-form', 'enquiry' ),
	);

	if ( ! isset( $forms[ $slug ] ) ) {
		return null;
	}

	list( $key, $class, $id ) = $forms[ $slug ];

	return (object) array(
		'ID'           => 1,
		'post_content' => sprintf(
			'[contact-form-7 id="1" title="%s" html_class="%s"%s]',
			$key,
			$class,
			'' !== $id ? ' html_id="' . $id . '"' : ''
		),
	);
}

function get_post( $id ) {
	return null; }

function do_shortcode( $content ) {
	return preg_replace_callback(
		'/\[contact-form-7 id="\d+" title="([a-z\-]+)" html_class="([a-z\-]+)"(?: html_id="([a-z\-]+)")?\]/',
		function ( $m ) {
			$body = (string) file_get_contents( get_theme_file_path( 'cf7/' . $m[1] . '.txt' ) );
			$body = str_replace( '{{home}}', untrailingslashit( home_url() ), $body );

			return sprintf(
				"<div class=\"wpcf7\"><form class=\"%s wpcf7-form init\"%s>\n"
					. "<div class=\"screen-reader-response\"><p role=\"status\" aria-live=\"polite\" aria-atomic=\"true\"></p><ul></ul></div>\n"
					. "%s"
					. "<div class=\"wpcf7-response-output\" aria-hidden=\"true\"></div>\n"
					. "</form></div>",
				$m[2],
				isset( $m[3] ) && '' !== $m[3] ? ' id="' . $m[3] . '"' : '',
				teebe_preview_cf7_controls( $body )
			);
		},
		$content
	);
}

/**
 * Renders Contact Form 7 tags the way the plugin does, so the preview shows
 * the real controls and the viewport checks measure the real layout.
 *
 * @param string $body Form body containing CF7 tags.
 * @return string
 */
function teebe_preview_cf7_controls( $body ) {
	return preg_replace_callback(
		'/\[(textarea|text|email|select|submit)(\*?)([^\]]*)\]/',
		function ( $m ) {
			list( , $kind, $star, $rest ) = $m;

			preg_match_all( '/"([^"]*)"/', $rest, $quoted );
			$values = $quoted[1];
			$bare   = preg_replace( '/"[^"]*"/', '', $rest );
			$words  = preg_split( '/\s+/', trim( $bare ), -1, PREG_SPLIT_NO_EMPTY );

			if ( 'submit' === $kind ) {
				$classes = array( 'wpcf7-form-control', 'wpcf7-submit' );

				foreach ( $words as $word ) {
					if ( 0 === strpos( $word, 'class:' ) ) {
						$classes[] = substr( $word, 6 );
					}
				}

				return sprintf(
					'<input class="%s" type="submit" value="%s"><span class="wpcf7-spinner"></span>',
					esc_attr( implode( ' ', $classes ) ),
					esc_attr( $values ? $values[0] : 'Send' )
				);
			}

			$name  = array_shift( $words );
			$id    = '';
			$auto  = '';
			$rows  = 2;
			$place = '';

			foreach ( $words as $word ) {
				if ( 0 === strpos( $word, 'id:' ) ) {
					$id = substr( $word, 3 );
				} elseif ( 0 === strpos( $word, 'autocomplete:' ) ) {
					$auto = substr( $word, 13 );
				} elseif ( preg_match( '/^\d+x(\d+)$/', $word, $size ) ) {
					$rows = (int) $size[1];
				} elseif ( 'placeholder' === $word && $values ) {
					$place = array_shift( $values );
				}
			}

			$required = '*' === $star;
			$attrs    = sprintf(
				' name="%s"%s%s aria-invalid="false"%s',
				esc_attr( $name ),
				$id ? ' id="' . esc_attr( $id ) . '"' : '',
				$auto ? ' autocomplete="' . esc_attr( $auto ) . '"' : '',
				$required ? ' aria-required="true"' : ''
			);

			if ( 'select' === $kind ) {
				$options = '';

				foreach ( $values as $index => $value ) {
					$options .= sprintf(
						'<option value="%s">%s</option>',
						esc_attr( 0 === $index ? '' : $value ),
						esc_html( $value )
					);
				}

				$control = sprintf(
					'<select class="wpcf7-form-control wpcf7-select%s"%s>%s</select>',
					$required ? ' wpcf7-validates-as-required' : '',
					$attrs,
					$options
				);
			} elseif ( 'textarea' === $kind ) {
				$control = sprintf(
					'<textarea cols="40" rows="%d" maxlength="2000" class="wpcf7-form-control wpcf7-textarea%s"%s%s></textarea>',
					$rows,
					$required ? ' wpcf7-validates-as-required' : '',
					$place ? ' placeholder="' . esc_attr( $place ) . '"' : '',
					$attrs
				);
			} else {
				$control = sprintf(
					'<input size="40" maxlength="400" class="wpcf7-form-control wpcf7-%s%s" value="" type="%s"%s>',
					'email' === $kind ? 'email' : 'text',
					$required ? ' wpcf7-validates-as-required' : '',
					'email' === $kind ? 'email' : 'text',
					$attrs
				);
			}

			return sprintf(
				'<span class="wpcf7-form-control-wrap" data-name="%s">%s</span>',
				esc_attr( $name ),
				$control
			);
		},
		$body
	);
}
function current_user_can( $cap ) {
	return false; }
function add_theme_support() {}
function add_theme_page() {}
function wp_get_canonical_url() {
	return home_url( '/' ); }
function wp_get_document_title() {
	return apply_filters( 'pre_get_document_title', 'The Ebook Edit' ); }
function get_permalink() {
	return home_url( '/' ); }
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
function class_exists_wpcf7() {
	return false; }

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

	// Contact Form 7 enqueues its own stylesheet before the theme's. The rules
	// that affect layout are reproduced here so the theme's integration layer
	// is tested against them rather than against nothing.
	echo "<style id=\"contact-form-7-css\">\n"
		. ".wpcf7 .screen-reader-response{position:absolute;overflow:hidden;clip:rect(1px,1px,1px,1px);height:1px;width:1px;margin:0;padding:0;border:0}\n"
		. ".wpcf7 form .wpcf7-response-output{margin:2em .5em 1em;padding:.2em 1em;border:2px solid #00a0d2}\n"
		. ".wpcf7 form.init .wpcf7-response-output,.wpcf7 form.resetting .wpcf7-response-output,.wpcf7 form.submitting .wpcf7-response-output{display:none}\n"
		. ".wpcf7-not-valid-tip{color:#dc3232;font-size:1em;font-weight:400;display:block}\n"
		. ".wpcf7-spinner{visibility:hidden;display:inline-block;background-color:#23282d;opacity:.75;width:24px;height:24px;border:0;border-radius:100%;padding:0;margin:0 24px;position:relative}\n"
		. "</style>\n";

	foreach ( $GLOBALS['teebe_preview']['styles'] as $handle => $src ) {
		printf( '<link rel="stylesheet" id="%s-css" href="%s" media="all">' . "\n", esc_attr( $handle ), esc_url( $src ) );
	}
}

function wp_body_open() {
	do_action( 'wp_body_open' );
}

function wp_footer() {
	foreach ( $GLOBALS['teebe_preview']['scripts'] as $handle => $src ) {
		printf( '<script id="%s-js" src="%s" defer></script>' . "\n", esc_attr( $handle ), esc_url( $src ) );
	}
}

function get_header() {
	global $theme_dir;
	require $theme_dir . '/header.php';
}

function get_footer() {
	global $theme_dir;
	require $theme_dir . '/footer.php';
}

/* --------------------------------------------------------------- rendering */

require $theme_dir . '/functions.php';
do_action( 'after_setup_theme' );
do_action( 'wp_enqueue_scripts' );

$pages = array(
	'front'                           => array( 'index.html', 'front-page.php' ),
	'services'                        => array( 'services.html', 'page-services.php' ),
	'writing'                         => array( 'writing.html', 'page-writing.php' ),
	'editing'                         => array( 'editing.html', 'page-editing.php' ),
	'publishing'                      => array( 'publishing.html', 'page-publishing.php' ),
	'process'                         => array( 'process.html', 'page-process.php' ),
	'portfolio'                       => array( 'portfolio.html', 'page-portfolio.php' ),
	'about'                           => array( 'about.html', 'page-about.php' ),
	'insights'                        => array( 'insights.html', 'page-insights.php' ),
	'contact'                         => array( 'contact.html', 'page-contact.php' ),
	'thank-you'                       => array( 'thank-you.html', 'page-thank-you.php' ),
	'privacy'                         => array( 'privacy.html', 'page-privacy.php' ),
	'terms'                           => array( 'terms.html', 'page-terms.php' ),
	'404'                             => array( '404.html', '404.php' ),
	'turn-expertise-into-an-ebook'    => array( 'insights/turn-expertise-into-an-ebook.html', 'template-insight-turn-expertise.php' ),
	'editing-levels-explained'        => array( 'insights/editing-levels-explained.html', 'template-insight-editing-levels.php' ),
	'pre-publishing-checklist'        => array( 'insights/pre-publishing-checklist.html', 'template-insight-pre-publishing.php' ),
	'kindle-and-ebook-platform-guide' => array( 'insights/kindle-and-ebook-platform-guide.html', 'template-insight-kindle-platforms.php' ),
);

if ( ! is_dir( $out_dir ) ) {
	mkdir( $out_dir, 0777, true );
}

foreach ( $pages as $key => $page ) {
	$GLOBALS['teebe_preview']['key']      = $key;
	$GLOBALS['teebe_preview']['slug']     = $key;
	$GLOBALS['teebe_preview']['is_404']   = ( '404' === $key );
	$GLOBALS['teebe_preview']['is_front'] = ( 'front' === $key );

	ob_start();
	require $theme_dir . '/' . $page[1];
	$html = ob_get_clean();

	$name = ( 'front' === $key ) ? 'index' : $key;
	file_put_contents( $out_dir . '/' . $name . '.html', $html );
	echo "rendered {$name}.html\n";
}
