<?php
/**
 * The Ebook Edit — Google Analytics 4, Microsoft Clarity and the funnel
 * events that measure them.
 *
 * One authoritative implementation for the whole theme. Both tags are
 * printed here, once per rendered document, through wp_head — which every
 * presentation calls, including the two templates that render documents of
 * their own — so no template carries a copy and no page can end up with two.
 *
 * The measurement identifiers are the approved ones and are filterable, so a
 * staging site can turn them off or swap them without editing the theme:
 *
 *     add_filter( 'teebe_analytics_enabled', '__return_false' );
 *
 * No Meta Pixel is installed: none was supplied, and inventing one would
 * send a real site's traffic to an account nobody owns. The event layer
 * below is written so an approved Pixel can be added later in one place.
 *
 * Nothing personal is ever sent to either service. Every event parameter in
 * this file is a page path, a route name, a form name, a call-to-action
 * label or a location within the layout. Names, email addresses, telephone
 * numbers and anything a visitor typed stay in the Contact Form 7
 * submission and its notification email.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The approved Google Analytics 4 measurement ID.
 *
 * @return string
 */
function teebe_analytics_ga4_id() {
	return (string) apply_filters( 'teebe_analytics_ga4_id', 'G-EQFMTN2WJF' );
}

/**
 * The approved Microsoft Clarity project ID.
 *
 * @return string
 */
function teebe_analytics_clarity_id() {
	return (string) apply_filters( 'teebe_analytics_clarity_id', 'yl7loe6vel' );
}

/**
 * Whether analytics should load for this request.
 *
 * Consent: no consent manager is bundled with this theme, and adding a
 * second cookie banner to a site that may already have one would be worse
 * than adding none. Instead the decision is delegated:
 *
 *   * when a plugin implementing the WordPress Consent API is active, the
 *     visitor's "statistics" consent decides, which is what CookieYes,
 *     Complianz and Real Cookie Banner all expose;
 *   * otherwise the tags load, and the exact admin configuration still
 *     required before production is recorded in DEPLOYMENT.md;
 *   * either way 'teebe_analytics_enabled' has the final say.
 *
 * @return bool
 */
function teebe_analytics_enabled() {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return false;
	}

	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		return false;
	}

	$enabled = true;

	// Set by a consent-management plugin that implements the WordPress
	// Consent API. Absent, the site has no consent manager yet.
	if ( function_exists( 'wp_has_consent' ) ) {
		$enabled = (bool) wp_has_consent( 'statistics' );
	}

	return (bool) apply_filters( 'teebe_analytics_enabled', $enabled );
}

/**
 * Prints the Google tag and the Microsoft Clarity tag, exactly as supplied.
 *
 * Runs at priority 1 so measurement starts before the theme's own metadata
 * and before anything a plugin adds later in the head.
 */
function teebe_analytics_head() {
	// wp_head runs once per document, and this is the only place either tag
	// appears in the repository, so the guard should never fire. It is here
	// so that if a plugin ever calls wp_head a second time, the document
	// still ends up with exactly one Google tag and one Clarity tag.
	if ( ! empty( $GLOBALS['teebe_analytics_printed'] ) || ! teebe_analytics_enabled() ) {
		return;
	}

	$GLOBALS['teebe_analytics_printed'] = true;

	$ga4 = teebe_analytics_ga4_id();

	if ( '' !== $ga4 ) {
		?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga4 ); ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?php echo esc_js( $ga4 ); ?>');
</script>
		<?php
	}

	$clarity = teebe_analytics_clarity_id();

	if ( '' !== $clarity ) {
		?>
<!-- Microsoft Clarity -->
<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "<?php echo esc_js( $clarity ); ?>");
</script>
		<?php
	}
}
add_action( 'wp_head', 'teebe_analytics_head', 1 );

/**
 * Whether HighLevel has returned the visitor here after a confirmed booking.
 *
 * The calendar is a cross-origin frame and is never inspected. The only
 * signal that an appointment exists is HighLevel's own post-booking
 * redirect, which carries ?conversion=appointment_booked. The parameter is
 * never trusted as input: it is unslashed, sanitised and then compared
 * against the one value that means anything.
 *
 * @return bool
 */
function teebe_analytics_booking_confirmed() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only check of a public redirect parameter.
	if ( empty( $_GET['conversion'] ) || ! is_string( $_GET['conversion'] ) ) {
		return false;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only check of a public redirect parameter.
	$conversion = sanitize_key( wp_unslash( $_GET['conversion'] ) );

	return 'appointment_booked' === $conversion;
}

/**
 * The event helper and the events every presentation shares.
 *
 * Enqueued on every public page, before the presentation's own script, so
 * window.teebeTrack exists by the time anything wants to call it.
 */
function teebe_analytics_assets() {
	wp_enqueue_script(
		'the-ebook-edit-analytics',
		get_theme_file_uri( 'assets/js/analytics.js' ),
		array(),
		(string) filemtime( get_theme_file_path( 'assets/js/analytics.js' ) ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_add_inline_script(
		'the-ebook-edit-analytics',
		'window.teebeAnalytics = ' . wp_json_encode( teebe_analytics_config() ) . ';',
		'before'
	);
}

/**
 * What the event layer needs to know about the page it is running on.
 *
 * Every value is derived from the theme, never from what a visitor typed.
 *
 * @return array<string, mixed>
 */
function teebe_analytics_config() {
	$route = teebe_site_route();

	if ( '' === $route && teebe_is_landing() ) {
		$route = 'landing-page';
	}

	/*
	 * Which lead form is which, so generate_lead and form_start can name the
	 * form without reading anything a visitor entered. Keyed by the form's
	 * HTML id, which the theme sets when it renders the Contact Form 7
	 * shortcode.
	 */
	$forms = teebe_is_landing()
		? array( 'leadForm' => 'landing_page_form' )
		: array(
			'leadForm'    => 'home_page_form',
			'contactForm' => 'contact_page_form',
		);

	return array(
		'route'            => $route,
		'pagePath'         => teebe_analytics_page_path(),
		'leadOrigin'       => '' !== $route ? $route : 'other',
		'forms'            => $forms,
		'thankYouUrl'      => teebe_site_thank_you_url(),
		'bookingPath'      => wp_parse_url( home_url( '/book-consultation/' ), PHP_URL_PATH ),
		'isBookingPage'    => 'book-consultation' === $route,
		'isThankYouPage'   => 'thank-you' === $route,
		'bookingConfirmed' => teebe_analytics_booking_confirmed(),
	);
}

/**
 * The current page's path, matching what GA4 records as page_path.
 *
 * Taken from the page's own permalink rather than the request, so a query
 * string, a tracking parameter or a trailing slash cannot split one page
 * into several rows in a report.
 *
 * @return string
 */
function teebe_analytics_page_path() {
	$permalink = is_singular() ? get_permalink() : home_url( '/' );
	$path      = $permalink ? wp_parse_url( $permalink, PHP_URL_PATH ) : '';

	return is_string( $path ) && '' !== $path ? $path : '/';
}
