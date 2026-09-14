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
 *   * the Contact Form 7 form that replaces the approved prototype form.
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
 * The page template files this integration applies to: the Meta Ads landing
 * page, and the thank-you page a delivered enquiry leads to.
 */
const TEEBE_LANDING_TEMPLATE    = 'template-landing-meta-ads.php';
const TEEBE_THANK_YOU_TEMPLATE  = 'template-landing-thank-you.php';

/**
 * Whether the request being rendered is the landing page.
 *
 * @return bool
 */
function teebe_is_landing() {
	return is_page() && is_page_template( TEEBE_LANDING_TEMPLATE );
}

/**
 * Whether the request being rendered is the consultation thank-you page.
 *
 * @return bool
 */
function teebe_is_thank_you() {
	return is_page() && is_page_template( TEEBE_THANK_YOU_TEMPLATE );
}

/**
 * Whether either funnel template is being rendered. Both replace the
 * website's assets with the landing design; neither uses the book.
 *
 * @return bool
 */
function teebe_is_funnel() {
	return teebe_is_landing() || teebe_is_thank_you();
}

/**
 * The funnel's own stylesheet and scripts, in place of the website's.
 *
 * Called from teebe_assets() instead of the book assets, so neither funnel
 * page loads styles.css, book.css, wordpress.css or book.js. Those carry the
 * book presentation and would fight this design system. Contact Form 7's
 * assets are untouched and still load normally.
 *
 * Both pages share one stylesheet and one WhatsApp script. Only the landing
 * page loads landing.js, which is the carousel and the lead form: the
 * thank-you page has neither, and loading it there would do nothing but cost
 * the visitor a download on the page where they are booking.
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
		'the-ebook-edit-landing-whatsapp',
		get_theme_file_uri( 'assets/js/landing-whatsapp.js' ),
		array(),
		(string) filemtime( get_theme_file_path( 'assets/js/landing-whatsapp.js' ) ),
		$deferred
	);

	wp_add_inline_script(
		'the-ebook-edit-landing-whatsapp',
		'window.teebeLanding = ' . wp_json_encode(
			array(
				'whatsappNumber'  => teebe_landing_whatsapp_number(),
				'whatsappMessage' => teebe_landing_whatsapp_message(),
				'thankYouUrl'     => teebe_landing_thank_you_url(),
			)
		) . ';',
		'before'
	);

	if ( ! teebe_is_landing() ) {
		return;
	}

	wp_enqueue_script(
		'the-ebook-edit-landing',
		get_theme_file_uri( 'assets/js/landing.js' ),
		array( 'the-ebook-edit-landing-whatsapp' ),
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
 * Returning an empty string disables the redirect: the visitor then stays on
 * the landing page and sees Contact Form 7's own confirmation.
 *
 * @return string
 */
function teebe_landing_thank_you_url() {
	return (string) apply_filters( 'teebe_landing_thank_you_url', home_url( '/thank-you/' ) );
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
 * Returning an empty string restores the approved fallback: the button shows
 * a short notice and scrolls the visitor to the enquiry form instead.
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
		__( 'Hello The Ebook Edit, I would like to discuss an ebook project.', 'the-ebook-edit' )
	);
}

/**
 * The notice shown if the WhatsApp button is pressed while no number is
 * configured. The approved file carried a note addressed to whoever was
 * setting the prototype up; this is the visitor-facing equivalent, and with
 * a number configured it is never shown at all.
 *
 * @return string
 */
function teebe_landing_whatsapp_fallback_message() {
	return (string) apply_filters(
		'teebe_landing_whatsapp_fallback_message',
		__( 'WhatsApp is unavailable at the moment — please use the enquiry form.', 'the-ebook-edit' )
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

	if ( teebe_is_thank_you() && ! in_array( 'teebe-thank-you', $classes, true ) ) {
		$classes[] = 'teebe-thank-you';
	}

	return $classes;
}
add_filter( 'body_class', 'teebe_landing_body_class' );

/**
 * Keeps the consultation thank-you page out of search results.
 *
 * It is the end of an advertising funnel, reachable only by submitting the
 * landing page's form, and nothing on it is useful to someone arriving from
 * a search engine. Applied through WordPress's own robots filter rather than
 * a tag in the template, so it also governs the X-Robots-Tag header a host or
 * plugin may add.
 *
 * Only this template is affected: the website, the landing page and the legal
 * pages keep whatever directives they already had.
 *
 * @param array $robots Robots directives.
 * @return array
 */
function teebe_thank_you_robots( $robots ) {
	if ( teebe_is_thank_you() ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
		unset( $robots['follow'] );
	}

	return $robots;
}
add_filter( 'wp_robots', 'teebe_thank_you_robots', 20 );

/**
 * The landing page's own browser theme colour, which is darker than the rest
 * of the website's.
 *
 * @param string $color Default theme colour.
 * @return string
 */
function teebe_landing_theme_color( $color ) {
	return teebe_is_funnel() ? '#051a43' : $color;
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
	if ( ! teebe_is_funnel() ) {
		return;
	}

	$description = teebe_is_thank_you()
		? __( 'Choose a time to speak with a consultant from The Ebook Edit about your book.', 'the-ebook-edit' )
		: __( 'The Ebook Edit — professional ebook writing, editing, formatting and publishing support.', 'the-ebook-edit' );
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
 * The landing page's enquiry form, as the setup routine creates it.
 *
 * The form body in cf7/landing-enquiry.txt reproduces the approved form
 * markup exactly — the same wrappers, labels, required markers, dropdown
 * values and error slots — so Contact Form 7 renders the approved design
 * rather than its own.
 *
 * Unlike the website's two other forms, this one is addressed to the
 * published support mailbox rather than the WordPress administrator email.
 * No mail server settings are written here or anywhere else in the theme:
 * Contact Form 7 sends through whatever WordPress is already configured to
 * use, and no credentials belong in this repository.
 *
 * @return array<string, string|array>
 */
function teebe_landing_form_definition() {
	return array(
		'title'      => 'Start Your Book',
		'html_class' => 'lead-form',
		'html_id'    => 'leadForm',
		'page'       => 'the landing page',
		'body'       => 'cf7/landing-enquiry.txt',
		'subject'    => 'New book enquiry from the landing page',
		'recipient'  => 'support@theebookedit.com',
		'fields'     => array(
			'Full Name'         => 'full_name',
			'Email'             => 'email',
			'Mobile / WhatsApp' => 'mobile_whatsapp',
			'Book Type'         => 'book_type',
			'Book Stage'        => 'book_stage',
			'Expected Budget'   => 'expected_budget',
		),
	);
}

/**
 * Renders the landing page's enquiry form.
 *
 * The website's other two forms find their shortcode in the page's own
 * content, which ties each to one fixed slug. This template can be assigned
 * to a page with any slug, so the form is looked up by its title instead and
 * no shortcode has to be pasted anywhere.
 *
 * When the form has not been created yet, an on-page notice explains what to
 * do rather than showing a form that cannot deliver anything.
 */
function teebe_render_landing_form() {
	$form = teebe_landing_form_definition();

	if ( class_exists( 'WPCF7_ContactForm' ) && function_exists( 'teebe_setup_find_cf7_form' ) ) {
		$form_post = teebe_setup_find_cf7_form( $form['title'] );

		if ( $form_post ) {
			echo do_shortcode( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Contact Form 7 escapes its own output.
				sprintf(
					'[contact-form-7 id="%d" title="%s" html_class="%s" html_id="%s"]',
					(int) $form_post->ID,
					esc_attr( $form_post->post_title ),
					esc_attr( $form['html_class'] ),
					esc_attr( $form['html_id'] )
				)
			);
			return;
		}
	}

	echo '<div class="form-status show"><p class="form-status-title">';
	esc_html_e( 'Enquiry form not configured yet.', 'the-ebook-edit' );
	echo '</p><p class="form-note">';
	printf(
		/* translators: %s: Contact Form 7 form title. */
		esc_html__( 'Install Contact Form 7, then run Appearance → The Ebook Edit Setup to create the "%s" form. Until then, enquiries can be sent by email.', 'the-ebook-edit' ),
		esc_html( $form['title'] )
	);
	echo '</p><p class="form-note"><a href="mailto:support@theebookedit.com">support@theebookedit.com</a></p></div>';
}
