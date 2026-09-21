<?php
/**
 * The Ebook Edit — Meta Ads landing page integration.
 *
 * Everything that makes template-landing-meta-ads.php behave like a normal
 * WordPress page while still rendering the approved standalone design:
 *
 *   * its own stylesheet and script in place of the website's book assets,
 *     on this template only;
 *   * the head metadata the approved page carried;
 *   * the approved HighLevel lead form that replaces the prototype form.
 *
 * This file is hand-maintained. wordpress/sync-from-static.py regenerates
 * the rest of the website from the published static pages and never touches
 * the landing page, which has no static counterpart.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The page template this integration applies to.
 *
 * The funnel's thank-you step is no longer a template of its own: the
 * approved design has one Thank You experience at /thank-you/, shared by the
 * website and by this landing page, and the website's page-thank-you.php
 * renders it. See inc/site.php.
 */
const TEEBE_LANDING_TEMPLATE = 'template-landing-meta-ads.php';

/**
 * Whether the request being rendered is the landing page.
 *
 * @return bool
 */
function teebe_is_landing() {
	return is_page() && is_page_template( TEEBE_LANDING_TEMPLATE );
}

/**
 * The funnel's own stylesheet and scripts, in place of the website's.
 *
 * Called from teebe_assets() instead of the book assets, so this page does
 * not load site.css, styles.css, book.css or their scripts. Those carry the
 * other two presentations and would fight this design system.
 *
 * The HighLevel embed library that renders the lead form is enqueued
 * separately, by teebe_ghl_form_assets() in inc/site.php, which is what keeps
 * it to one copy per page across the whole theme.
 */
function teebe_landing_assets() {
	wp_enqueue_style(
		'the-ebook-edit-landing',
		get_theme_file_uri( 'assets/css/landing.css' ),
		array(),
		(string) filemtime( get_theme_file_path( 'assets/css/landing.css' ) )
	);

	$deferred = array(
		'in_footer' => true,
		'strategy'  => 'defer',
	);

	wp_enqueue_script(
		'the-ebook-edit-landing',
		get_theme_file_uri( 'assets/js/landing.js' ),
		array(),
		(string) filemtime( get_theme_file_path( 'assets/js/landing.js' ) ),
		$deferred
	);
}

/**
 * Where a delivered landing-page enquiry sends the visitor.
 *
 * Derived from the site address, so it is correct on a staging domain and on
 * the live domain without editing the theme, and carries no hard-coded host.
 * Point it at a different page — if the consultation thank-you template is
 * published at a slug other than /thank-you/ — with:
 *
 *     add_filter( 'teebe_landing_thank_you_url', fn() => home_url( '/book-a-call/' ) );
 *
 * HighLevel performs the redirect itself, from the form's own settings; this
 * is the address the page's calls to action use.
 *
 * @return string
 */
function teebe_landing_thank_you_url() {
	return (string) apply_filters( 'teebe_landing_thank_you_url', home_url( '/thank-you/' ) );
}

/**
 * Where every conversion call to action on the landing page points.
 *
 * The landing page has two deliberate routes to the same place: a visitor who
 * is ready books straight away through any call to action, and a visitor who
 * would rather tell us about the book first fills in the form and is taken to
 * the same page by HighLevel once the enquiry is captured.
 *
 * If the thank-you destination has been turned off, calls to action fall back
 * to the enquiry form rather than becoming dead links.
 *
 * @return string
 */
function teebe_landing_cta_href() {
	$url = teebe_landing_thank_you_url();

	return '' !== $url ? $url : '#contact';
}

/**
 * The floating WhatsApp button's destination: a real wa.me conversation.
 *
 * Built from the one configured number and message, so the landing page and
 * the thank-you page open the same chat and no number is written into a
 * template or a script. The number is digits only, as wa.me requires — any
 * plus sign, space, hyphen or bracket a filter introduces is stripped here
 * rather than producing a link that silently fails.
 *
 * Returns '' when no number is configured, which is the only case in which
 * the button is not rendered at all.
 *
 * @return string
 */
function teebe_landing_whatsapp_url() {
	$number = preg_replace( '/\D+/', '', teebe_landing_whatsapp_number() );

	if ( '' === $number ) {
		return '';
	}

	$url = 'https://wa.me/' . $number;

	$message = teebe_landing_whatsapp_message();

	if ( '' !== $message ) {
		$url .= '?text=' . rawurlencode( $message );
	}

	return $url;
}

/**
 * The WhatsApp number the floating button opens, digits only, in
 * international format.
 *
 * The approved file shipped this as an empty constant to fill in by hand.
 * It defaults here to the number already published across theebookedit.com,
 * and can be changed without editing the theme:
 *
 *     add_filter( 'teebe_landing_whatsapp_number', fn() => '441234567890' );
 *
 * Returning an empty string removes the button altogether, which is the only
 * case in which it is not rendered.
 *
 * @return string
 */
function teebe_landing_whatsapp_number() {
	return (string) apply_filters( 'teebe_landing_whatsapp_number', '447348954631' );
}

/**
 * The message the WhatsApp button pre-fills.
 *
 * @return string
 */
function teebe_landing_whatsapp_message() {
	return (string) apply_filters(
		'teebe_landing_whatsapp_message',
		__( 'Hello, I’m interested in discussing my book project with The Ebook Edit.', 'the-ebook-edit' )
	);
}

/**
 * Adds the body class every landing-page style is scoped to.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function teebe_landing_body_class( $classes ) {
	if ( teebe_is_landing() && ! in_array( 'teebe-landing', $classes, true ) ) {
		$classes[] = 'teebe-landing';
	}

	return $classes;
}
add_filter( 'body_class', 'teebe_landing_body_class' );

/**
 * The landing page's own browser theme colour, which is darker than the rest
 * of the website's.
 *
 * @param string $color Default theme colour.
 * @return string
 */
function teebe_landing_theme_color( $color ) {
	return teebe_is_landing() ? '#051a43' : $color;
}
add_filter( 'teebe_theme_color', 'teebe_landing_theme_color' );

/**
 * Prints the description and social tags the approved page carried.
 *
 * The rest of the website's metadata is generated into inc/seo-data.php from
 * the static site. The landing page has no static counterpart, so its
 * metadata is declared here. teebe_head_meta() has already printed the theme
 * colour, canonical URL and icons by this point, and prints no description or
 * Open Graph tags for a page it has no entry for, so nothing is duplicated.
 */
function teebe_landing_head_meta() {
	if ( ! teebe_is_landing() ) {
		return;
	}

	$description = __( 'The Ebook Edit — professional ebook writing, editing, formatting and publishing support.', 'the-ebook-edit' );
	$title       = wp_get_document_title();
	$url         = get_permalink();
	$image       = get_theme_file_uri( 'assets/images/brand/the-ebook-edit-og.jpg' );

	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
	echo '<meta property="og:type" content="website">' . "\n";
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );

	if ( $url ) {
		printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	}

	printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	echo '<meta name="twitter:card" content="summary">' . "\n";
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image ) );
}
add_action( 'wp_head', 'teebe_landing_head_meta', 3 );

/**
 * Renders the landing page's approved HighLevel lead form.
 *
 * The same embed architecture as the website's two — see
 * teebe_render_ghl_form() in inc/site.php. The landing page keeps a form of
 * its own so a lead can be attributed to the page it came from.
 */
function teebe_render_landing_form() {
	teebe_render_ghl_form( 'landing' );
}
