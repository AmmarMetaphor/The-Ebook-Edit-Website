<?php
/**
 * The Ebook Edit — the approved website.
 *
 * Everything that makes the approved single-file design behave like an
 * ordinary WordPress website: routing, assets, head metadata, the two lead
 * forms, the booking calendar and the WhatsApp destination.
 *
 * The approved design routed thirteen views with a hash router in one
 * document. Every view is now a real WordPress page at a real URL, so the
 * browser, search engines and Google Analytics all see ordinary page loads
 * and no virtual page views have to be invented. The markup in the page
 * templates is the approved markup; only links, image sources, the forms and
 * the calendar resolve through WordPress.
 *
 * Three presentations live in this theme and never load each other's assets:
 *
 *   * the website — these templates, assets/css/site.css, assets/js/site.js;
 *   * the Meta Ads landing page — see inc/landing.php;
 *   * the Insights library — the earlier book presentation, which keeps its
 *     own shell in header-book.php and footer-book.php so its published
 *     URLs and content are unchanged by this release.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The website's routes, in navigation order, as route key => page path.
 *
 * The keys are the approved design's own route names. They are used for the
 * navigation's current-page state, for the analytics page grouping, and to
 * look up a page's head metadata.
 *
 * @return array<string, string>
 */
function teebe_site_routes() {
	return array(
		'home'                 => '/',
		'services'             => '/services/',
		'writing'              => '/writing/',
		'editing'              => '/editing/',
		'publishing'           => '/publishing/',
		'process'              => '/process/',
		'portfolio'            => '/portfolio/',
		'about'                => '/about/',
		'contact'              => '/contact/',
		'book-consultation'    => '/book-consultation/',
		'thank-you'            => '/thank-you/',
		'privacy-policy'       => '/privacy-policy/',
		'terms-and-conditions' => '/terms-and-conditions/',
	);
}

/**
 * The route currently being rendered, or '' for anything that is not one of
 * the website's thirteen pages.
 *
 * @return string
 */
function teebe_site_route() {
	$route = '';

	if ( is_front_page() ) {
		$route = 'home';
	} elseif ( is_page() ) {
		$slug = (string) get_post_field( 'post_name', get_queried_object_id() );

		if ( isset( teebe_site_routes()[ $slug ] ) && 'home' !== $slug ) {
			$route = $slug;
		}
	}

	return $route;
}

/**
 * Whether the page being rendered still uses the earlier book presentation.
 *
 * Only the Insights library does: its hub and its four articles. They are not
 * part of the approved website design, they are indexed, and nothing in this
 * release changes them, so they keep the shell and the stylesheets they were
 * published with.
 *
 * @return bool
 */
function teebe_is_book_page() {
	if ( ! is_page() ) {
		return false;
	}

	if ( 'insights' === (string) get_post_field( 'post_name', get_queried_object_id() ) ) {
		return true;
	}

	return 0 === strpos( (string) get_page_template_slug(), 'template-insight-' );
}

/**
 * Whether the page being rendered is part of the approved website design.
 *
 * True for the thirteen routes and also for anything else WordPress serves
 * through the default templates — a page an administrator adds later, a
 * search result, a 404 — because those render inside the website's shell.
 *
 * @return bool
 */
function teebe_is_website() {
	return ! teebe_is_landing() && ! teebe_is_book_page();
}

/**
 * The approved artwork, as image key => theme-relative file.
 *
 * The covers, platform marks and logo are shared with the Meta Ads landing
 * page and live in one directory so the release carries a single copy of
 * each. Every file is WebP, converted from the approved artwork.
 *
 * @return array<string, string>
 */
function teebe_site_images() {
	return array(
		'logo'                      => 'assets/images/landing/the-ebook-edit-logo.webp',
		'cover-1'                   => 'assets/images/landing/book-1-from-the-white-house-to-the-outhouse.webp',
		'cover-2'                   => 'assets/images/landing/book-2-mila-and-the-gentle-dino.webp',
		'cover-3'                   => 'assets/images/landing/book-3-the-ghost-of-blackthorn-palace.webp',
		'cover-4'                   => 'assets/images/landing/book-4-the-inner-compass.webp',
		'cover-5'                   => 'assets/images/landing/book-5-rising-through-the-storm.webp',
		'cover-6'                   => 'assets/images/landing/book-6-the-other-side-of-maybe.webp',
		'plat-abebooks'             => 'assets/images/landing/platform-abebooks.webp',
		'plat-penguin-random-house' => 'assets/images/landing/platform-penguin-random-house.webp',
		'plat-scribd'               => 'assets/images/landing/platform-scribd.webp',
		'plat-rakuten-kobo'         => 'assets/images/landing/platform-rakuten-kobo.webp',
		'plat-barnes-noble'         => 'assets/images/landing/platform-barnes-noble.webp',
		'plat-amazon'               => 'assets/images/landing/platform-amazon.webp',
	);
}

/**
 * The URL of one approved image.
 *
 * @param string $key Image key from teebe_site_images().
 * @return string
 */
function teebe_site_image( $key ) {
	$images = teebe_site_images();

	if ( ! isset( $images[ $key ] ) ) {
		return '';
	}

	return get_theme_file_uri( $images[ $key ] );
}

/**
 * The website's stylesheet and script.
 *
 * Called from teebe_assets() in place of the book assets. The website design
 * is complete in one stylesheet; Contact Form 7's own assets are untouched
 * and still load normally.
 */
function teebe_site_assets() {
	wp_enqueue_style(
		'the-ebook-edit-site',
		get_theme_file_uri( 'assets/css/site.css' ),
		array(),
		(string) filemtime( get_theme_file_path( 'assets/css/site.css' ) )
	);

	wp_enqueue_script(
		'the-ebook-edit-site',
		get_theme_file_uri( 'assets/js/site.js' ),
		array(),
		(string) filemtime( get_theme_file_path( 'assets/js/site.js' ) ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_add_inline_script(
		'the-ebook-edit-site',
		'window.teebeSite = ' . wp_json_encode(
			array(
				'thankYouUrl' => teebe_site_thank_you_url(),
			)
		) . ';',
		'before'
	);
}

/**
 * Where a delivered website enquiry sends the visitor.
 *
 * The same page the Meta Ads funnel uses: one Thank You experience for every
 * flow, at one URL, as the approved design requires. Derived from the site
 * address so it is correct on staging and live without editing the theme.
 *
 * @return string
 */
function teebe_site_thank_you_url() {
	return (string) apply_filters( 'teebe_site_thank_you_url', home_url( '/thank-you/' ) );
}

/**
 * The floating WhatsApp button's destination: a real wa.me conversation,
 * built from the one number configured for the whole theme.
 *
 * The approved prototype shipped an empty number and showed a reminder toast
 * in its place. Production uses the published business number, which the
 * landing page already opens, so both presentations reach the same chat.
 *
 * @return string
 */
function teebe_site_whatsapp_url() {
	return teebe_landing_whatsapp_url();
}

/**
 * Renders the website navigation, marking the current route.
 *
 * The approved design grouped the three service pages under the Services
 * link; that grouping is the data-nav attribute, kept here so the markup
 * matches and the rule that styles the current link still applies.
 */
function teebe_site_nav() {
	$route = teebe_site_route();

	$links = array(
		array( 'home', '/', 'Home', array( 'home' ) ),
		array( 'services', '/services/', 'Services', array( 'services', 'writing', 'editing', 'publishing' ) ),
		array( 'portfolio', '/portfolio/', 'Portfolio', array( 'portfolio' ) ),
		array( 'process', '/process/', 'Process', array( 'process' ) ),
		array( 'about', '/about/', 'About', array( 'about' ) ),
		array( 'contact', '/contact/', 'Contact', array( 'contact' ) ),
	);

	echo '<nav class="nav" id="nav">';

	foreach ( $links as $link ) {
		list( , $path, $label, $matches ) = $link;

		printf(
			'<a href="%s" data-nav="%s"%s>%s</a>',
			esc_url( home_url( $path ) ),
			esc_attr( implode( ' ', $matches ) ),
			in_array( $route, $matches, true ) ? ' aria-current="page"' : '',
			esc_html( $label )
		);
	}

	printf(
		'<a class="btn btn-gold nav-cta" href="%s" data-cta-location="header">%s</a>',
		esc_url( home_url( '/book-consultation/' ) ),
		esc_html__( 'Speak with Our Expert', 'the-ebook-edit' )
	);

	echo '</nav>';
}

/**
 * The one approved HighLevel booking calendar.
 *
 * The embed is exactly as supplied: the booking URL, calendar id, iframe
 * attributes and script are untouched, and nothing is injected into the
 * cross-origin frame. Only the panel around it is styled. Both
 * /book-consultation/ and /thank-you/ call this, so there is one calendar
 * configuration rather than two copies drifting apart.
 *
 * A completed booking is recorded only when HighLevel redirects back with
 * ?conversion=appointment_booked — see inc/analytics.php. Nothing here
 * treats a calendar load as a booking.
 */
function teebe_render_booking_calendar() {
	?>
		<div class="ghl-slot">
		<div class="ghl-embed" id="ghl-embed">
<iframe src="https://api.leadconnectorhq.com/widget/booking/XQxrNiP8LHrC16L5qr2t" allow="payment" style="width: 100%;border:none;overflow: hidden;" scrolling="no" id="XQxrNiP8LHrC16L5qr2t_1789374415925"></iframe><br><script src="https://link.msgsndr.com/js/form_embed.js" type="text/javascript"></script>
		</div>
		</div>
	<?php
}

/**
 * The website's two lead forms, as form key => Contact Form 7 configuration.
 *
 * Both collect the same six required fields. The Contact form additionally
 * carries the service the visitor arrived from, when a contextual call to
 * action supplied one. The Meta Ads landing page keeps its own third form —
 * see inc/landing.php — so a lead can be attributed to the page it came from
 * without reading anything personal.
 *
 * No mail server settings are written here or anywhere else in the theme:
 * Contact Form 7 sends through whatever WordPress is configured to use, and
 * no credentials belong in this repository.
 *
 * @return array<string, array<string, mixed>>
 */
function teebe_site_form_definitions() {
	$fields = array(
		'Full Name'         => 'full_name',
		'Email'             => 'email',
		'Mobile / WhatsApp' => 'mobile_whatsapp',
		'Book Type'         => 'book_type',
		'Book Stage'        => 'book_stage',
		'Expected Budget'   => 'expected_budget',
	);

	return array(
		'home'    => array(
			'title'      => 'Home Page Enquiry',
			'html_class' => 'lead-form',
			'html_id'    => 'leadForm',
			'page'       => 'Home',
			'body'       => 'cf7/site-home-enquiry.txt',
			'subject'    => 'New book enquiry from the homepage',
			'recipient'  => 'support@theebookedit.com',
			'fields'     => $fields,
		),
		/*
		 * The Contact form's service_interest is not listed here. It is a
		 * hidden input rather than a Contact Form 7 tag, so a
		 * [service_interest] line in the mail template would depend on the
		 * plugin resolving a tag it never registered. inc/attribution.php
		 * puts it at the top of the Marketing Attribution block instead,
		 * and only when the visitor actually arrived from a service call to
		 * action.
		 */
		'contact' => array(
			'title'      => 'Contact Page Enquiry',
			'html_class' => 'lead-form',
			'html_id'    => 'contactForm',
			'page'       => 'Contact',
			'body'       => 'cf7/site-contact-enquiry.txt',
			'subject'    => 'New book enquiry from the contact page',
			'recipient'  => 'support@theebookedit.com',
			'fields'     => $fields,
		),
	);
}

/**
 * Renders one of the website's two lead forms.
 *
 * The form is found by its title rather than by a shortcode pasted into the
 * page, so the page records carry no content and an administrator cannot
 * break the design by editing them. When the form has not been created yet,
 * an on-page notice explains what to do rather than showing a form that
 * cannot deliver anything.
 *
 * @param string $key Form key: 'home' or 'contact'.
 */
function teebe_render_site_form( $key ) {
	$forms = teebe_site_form_definitions();

	if ( ! isset( $forms[ $key ] ) ) {
		return;
	}

	$form = $forms[ $key ];

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

/**
 * The service a contextual call to action sent the visitor to Contact with.
 *
 * Arrives as ?service=Book+Editing. Query parameters are never trusted: the
 * value is unslashed, stripped of tags, trimmed and length-limited before it
 * is shown or stored, and it is only accepted when it matches one of the
 * services the website actually offers.
 *
 * @return string Empty when no recognised service was supplied.
 */
function teebe_site_service_context() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only display of a public link parameter.
	if ( empty( $_GET['service'] ) || ! is_string( $_GET['service'] ) ) {
		return '';
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only display of a public link parameter.
	$value = sanitize_text_field( wp_unslash( $_GET['service'] ) );

	$allowed = array(
		'Book Writing',
		'Book Editing',
		'Book Publishing',
		'Book Cover Design',
		'Book Illustrations',
		'Book Formatting',
		'Book Marketing',
		'Children Books',
		'Proofreading',
	);

	foreach ( $allowed as $service ) {
		if ( 0 === strcasecmp( $service, $value ) ) {
			return $service;
		}
	}

	return '';
}

/**
 * Registers the Contact form's service-context block as a Contact Form 7 tag.
 *
 * The block is part of the approved form design, and its hidden input has to
 * post with the rest of the form, so it is a form tag rather than markup
 * printed around the shortcode. inc/attribution.php puts the posted value
 * into the submission data and the notification email.
 */
function teebe_site_register_cf7_tags() {
	if ( ! function_exists( 'wpcf7_add_form_tag' ) ) {
		return;
	}

	wpcf7_add_form_tag( 'teebe_service_context', 'teebe_site_cf7_service_context' );
}
add_action( 'wpcf7_init', 'teebe_site_register_cf7_tags' );

/**
 * Renders the service-context block.
 *
 * @return string
 */
function teebe_site_cf7_service_context() {
	$service = teebe_site_service_context();

	return sprintf(
		'<div class="service-context" id="serviceContext"%s><span>%s</span> <strong id="serviceContextName">%s</strong>'
		. '<button type="button" id="serviceContextClear" aria-label="%s">✕</button></div>'
		. '<input type="hidden" name="service_interest" id="service_interest" value="%s">',
		'' === $service ? ' hidden' : '',
		esc_html__( 'Enquiry about:', 'the-ebook-edit' ),
		esc_html( $service ),
		esc_attr__( 'Remove service context', 'the-ebook-edit' ),
		esc_attr( $service )
	);
}

/**
 * Adds the body classes the website's own rules and scripts key off.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function teebe_site_body_class( $classes ) {
	if ( ! teebe_is_website() ) {
		return $classes;
	}

	$classes[] = 'teebe-site';

	$route = teebe_site_route();

	if ( '' !== $route ) {
		$classes[] = 'teebe-route-' . $route;
	}

	return $classes;
}
add_filter( 'body_class', 'teebe_site_body_class' );

/**
 * The website's own browser theme colour, as the approved design declares it.
 *
 * @param string $color Default theme colour.
 * @return string
 */
function teebe_site_theme_color( $color ) {
	return teebe_is_website() ? '#051a43' : $color;
}
add_filter( 'teebe_theme_color', 'teebe_site_theme_color' );

/**
 * Head metadata for the website's thirteen pages.
 *
 * Titles are the approved design's own. Descriptions are written from the
 * page's approved opening copy; none of them claims anything the page does
 * not say. Structured data is deliberately limited to the organisation and
 * to the breadcrumb WordPress can state truthfully.
 *
 * @return array<string, array<string, mixed>>
 */
function teebe_site_seo_data() {
	$org = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'ProfessionalService',
		'name'        => 'The Ebook Edit',
		'url'         => '{{home}}/',
		'description' => 'Professional ebook writing, editing, formatting and publishing support.',
		'email'       => 'support@theebookedit.com',
		'areaServed'  => 'Worldwide',
		'serviceType' => array( 'Ebook writing', 'Ebook editing', 'Ebook formatting', 'Publishing support' ),
		'parentOrganization' => array(
			'@type'   => 'Organization',
			'name'    => 'Inovantage Limited',
			'address' => array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => '9 Cheriton Road',
				'addressLocality' => 'Leicester',
				'addressRegion'   => 'England',
				'postalCode'      => 'LE2 8DE',
				'addressCountry'  => 'GB',
			),
		),
	);

	return array(
		'home'                 => array(
			'title'        => 'The Ebook Edit | Writing, Editing & Publishing',
			'description'  => 'The Ebook Edit — professional ebook writing, editing, formatting and publishing support.',
			'path'         => '/',
			'preload_logo' => true,
			'schema'       => array( $org ),
		),
		'services'             => array(
			'title'       => 'Services | The Ebook Edit',
			'description' => 'Writing, editing, cover design, illustration, formatting, publishing and marketing support. Choose one stage or bring everything together.',
			'path'        => '/services/',
		),
		'writing'              => array(
			'title'       => 'Book Writing | The Ebook Edit',
			'description' => 'Writing and ghostwriting support that turns notes, recordings, expertise or a rough draft into a structured manuscript that still sounds like you.',
			'path'        => '/writing/',
		),
		'editing'              => array(
			'title'       => 'Book Editing | The Ebook Edit',
			'description' => 'Developmental, line and copy editing plus proofreading. Each level fixes a different problem, and we help you choose the one your manuscript needs.',
			'path'        => '/editing/',
		),
		'publishing'           => array(
			'title'       => 'Book Publishing | The Ebook Edit',
			'description' => 'Publishing preparation for digital and print: formatting, EPUB, Kindle readiness, metadata, cover specifications and platform upload guidance.',
			'path'        => '/publishing/',
		),
		'process'              => array(
			'title'       => 'Our Process | The Ebook Edit',
			'description' => 'Six stages from first idea to publication, with your feedback and approval guiding each one.',
			'path'        => '/process/',
		),
		'portfolio'            => array(
			'title'       => 'Portfolio | The Ebook Edit',
			'description' => 'A selection of books shaped with editorial, design and publishing support, each developed around its story, audience and purpose.',
			'path'        => '/portfolio/',
		),
		'about'                => array(
			'title'       => 'About The Ebook Edit',
			'description' => 'The Ebook Edit helps authors, experts, coaches and entrepreneurs turn ideas, material and manuscripts into a professionally written and published book.',
			'path'        => '/about/',
		),
		'contact'              => array(
			'title'       => 'Start a Project | The Ebook Edit',
			'description' => 'Tell us where your book is today — an idea, an outline, a manuscript or a published book that needs more support — and we will suggest the next step.',
			'path'        => '/contact/',
		),
		'book-consultation'    => array(
			'title'       => 'Book Your Free Consultation | The Ebook Edit',
			'description' => 'Choose a convenient time to speak with our book consultant about your idea, manuscript or publishing goals.',
			'path'        => '/book-consultation/',
		),
		'thank-you'            => array(
			'title'       => 'Thank You | The Ebook Edit',
			'description' => 'Thank you for taking the next step with The Ebook Edit.',
			'path'        => '/thank-you/',
			'noindex'     => true,
		),
		'privacy-policy'       => array(
			'title'       => 'Privacy Policy | The Ebook Edit',
			'description' => 'How The Ebook Edit collects, uses and protects information submitted through this website.',
			'path'        => '/privacy-policy/',
		),
		'terms-and-conditions' => array(
			'title'       => 'Terms & Conditions | The Ebook Edit',
			'description' => 'General information about the use of The Ebook Edit website and enquiries relating to our book services.',
			'path'        => '/terms-and-conditions/',
		),
		// Not a route, but the not-found template's head metadata moved here
		// with the rest of the website's when inc/seo-data.php became the
		// Insights library's alone.
		'404'                  => array(
			'title'       => 'Page Not Found | The Ebook Edit',
			'description' => 'The requested page could not be found.',
			'path'        => '',
		),
	);
}

/**
 * Replaces the generated metadata for any slug the website now serves.
 *
 * inc/seo-data.php is generated from the earlier static site and still
 * carries entries — and book body classes — for slugs this release has
 * redesigned. The website's own entries win, and the Insights entries are
 * left exactly as they were.
 *
 * @param array<string, array<string, mixed>> $map Generated metadata map.
 * @return array<string, array<string, mixed>>
 */
function teebe_site_seo_map( $map ) {
	foreach ( teebe_site_seo_data() as $route => $entry ) {
		$map[ 'home' === $route ? 'front' : $route ] = $entry;
	}

	return $map;
}
add_filter( 'teebe_seo_map', 'teebe_site_seo_map' );

/**
 * Sends the earlier legal-page addresses to the pages that replaced them.
 *
 * /privacy/ and /terms/ were the slugs the static site published. The
 * approved design names them /privacy-policy/ and /terms-and-conditions/, so
 * the old addresses redirect permanently rather than falling to a 404 — and
 * only once the replacement page actually exists, so a site that has not run
 * setup yet is never sent somewhere that is not there.
 */
function teebe_site_legacy_redirects() {
	if ( is_admin() || ! is_page() ) {
		return;
	}

	$moved = array(
		'privacy' => '/privacy-policy/',
		'terms'   => '/terms-and-conditions/',
	);

	$slug = (string) get_post_field( 'post_name', get_queried_object_id() );

	if ( ! isset( $moved[ $slug ] ) ) {
		return;
	}

	$target = get_page_by_path( trim( $moved[ $slug ], '/' ), OBJECT, 'page' );

	if ( ! $target || 'publish' !== $target->post_status ) {
		return;
	}

	wp_safe_redirect( home_url( $moved[ $slug ] ), 301 );
	exit;
}
add_action( 'template_redirect', 'teebe_site_legacy_redirects' );
