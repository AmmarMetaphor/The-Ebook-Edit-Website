<?php
/**
 * The Ebook Edit — Google Analytics 4, Microsoft Clarity, the Meta Pixel,
 * and the funnel events that measure them.
 *
 * One authoritative implementation for the whole theme. All three tags are
 * printed here, once per rendered document, through wp_head — which every
 * presentation calls, including the template that renders a document of its
 * own — so no template carries a copy and no page can end up with two. The
 * Meta Pixel's <noscript> image is body markup, so it is printed on
 * wp_body_open instead, likewise once.
 *
 * The measurement identifiers are the approved ones and are filterable, so a
 * staging site can turn them off or swap them without editing the theme:
 *
 *     add_filter( 'teebe_analytics_enabled', '__return_false' );          // GA4 + Clarity
 *     add_filter( 'teebe_analytics_marketing_enabled', '__return_false' ); // Meta Pixel
 *
 * Consent is split the way the law and the consent plugins split it:
 * Google Analytics and Clarity are measurement, the Meta Pixel is
 * advertising. Each has its own gate, so a visitor who accepts statistics
 * but refuses marketing gets exactly that.
 *
 * Nothing personal is ever sent to any of them. Every event parameter in
 * this file is a page path, a route name, a form name, a call-to-action
 * label or a location within the layout. Names, email addresses, telephone
 * numbers and anything a visitor typed stay in the Contact Form 7
 * submission and its notification email. No Advanced Matching is
 * configured.
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
 * The approved Meta Pixel ID.
 *
 * @return string
 */
function teebe_analytics_meta_pixel_id() {
	return (string) apply_filters( 'teebe_analytics_meta_pixel_id', '1492057326110606' );
}

/**
 * Whether any measurement may run at all for this request.
 *
 * Nothing is measured in the admin, during an AJAX or REST request, or
 * under WP-CLI: none of those is a visitor looking at a page.
 *
 * @return bool
 */
function teebe_analytics_is_public_request() {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return false;
	}

	return ! ( defined( 'WP_CLI' ) && WP_CLI );
}

/**
 * Whether one consent category has been granted.
 *
 * No consent manager is bundled with this theme, and adding a second cookie
 * banner to a site that may already have one would be worse than adding
 * none. The decision is delegated instead:
 *
 *   * when a plugin implementing the WordPress Consent API is active, the
 *     visitor's own choice for that category decides — which is what
 *     CookieYes, Complianz and Real Cookie Banner all expose;
 *   * otherwise the tags load, and the exact admin configuration still
 *     required before production is recorded in DEPLOYMENT.md.
 *
 * @param string $category Consent API category: 'statistics' or 'marketing'.
 * @return bool
 */
function teebe_analytics_has_consent( $category ) {
	if ( ! function_exists( 'wp_has_consent' ) ) {
		return true;
	}

	return (bool) wp_has_consent( $category );
}

/**
 * Whether the measurement tags — Google Analytics 4 and Microsoft Clarity —
 * should load. These are the *statistics* category.
 *
 * @return bool
 */
function teebe_analytics_enabled() {
	if ( ! teebe_analytics_is_public_request() ) {
		return false;
	}

	return (bool) apply_filters( 'teebe_analytics_enabled', teebe_analytics_has_consent( 'statistics' ) );
}

/**
 * Whether the advertising tag — the Meta Pixel — should load. This is the
 * *marketing* category, which a visitor may refuse while accepting
 * statistics, so it is a separate decision from the one above.
 *
 * @return bool
 */
function teebe_analytics_marketing_enabled() {
	if ( ! teebe_analytics_is_public_request() ) {
		return false;
	}

	return (bool) apply_filters( 'teebe_analytics_marketing_enabled', teebe_analytics_has_consent( 'marketing' ) );
}

/**
 * Prints the Google tag, the Microsoft Clarity tag and the Meta Pixel,
 * exactly as supplied.
 *
 * Runs at priority 1 so measurement starts before the theme's own metadata
 * and before anything a plugin adds later in the head.
 */
function teebe_analytics_head() {
	// wp_head runs once per document, and this is the only place any of the
	// three tags appears in the repository, so the guard should never fire.
	// It is here so that if a plugin ever calls wp_head a second time, the
	// document still ends up with one Google tag, one Clarity tag and one
	// Meta Pixel initialisation.
	if ( ! empty( $GLOBALS['teebe_analytics_printed'] ) ) {
		return;
	}

	$GLOBALS['teebe_analytics_printed'] = true;

	teebe_analytics_print_measurement_tags();
	teebe_analytics_print_meta_pixel();
}
add_action( 'wp_head', 'teebe_analytics_head', 1 );

/**
 * Google Analytics 4 and Microsoft Clarity — the statistics category.
 */
function teebe_analytics_print_measurement_tags() {
	if ( ! teebe_analytics_enabled() ) {
		return;
	}

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

/**
 * The Meta Pixel base code — the marketing category.
 *
 * Exactly as supplied, including its single fbq('track', 'PageView'). Pages
 * are real WordPress URLs, so that one automatic PageView per page load is
 * the whole story: the theme never sends a second, manual one.
 *
 * The <noscript> image that accompanies this snippet is body markup and is
 * printed by teebe_analytics_body_open() instead of here, behind the same
 * consent decision, so the fallback cannot become a way around it.
 */
function teebe_analytics_print_meta_pixel() {
	if ( ! teebe_analytics_marketing_enabled() ) {
		return;
	}

	$pixel = teebe_analytics_meta_pixel_id();

	if ( '' === $pixel ) {
		return;
	}
	?>
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '<?php echo esc_js( $pixel ); ?>');
fbq('track', 'PageView');
</script>
<!-- End Meta Pixel Code -->
	<?php
}

/**
 * The Meta Pixel's <noscript> fallback, immediately after <body>.
 *
 * It belongs in the body rather than the head because it is an image, and
 * it is gated on exactly the same marketing consent as the script above: a
 * visitor who refuses advertising cookies gets neither, so the fallback is
 * not a quiet way around the consent manager.
 */
function teebe_analytics_body_open() {
	if ( ! empty( $GLOBALS['teebe_analytics_noscript_printed'] )
		|| ! teebe_analytics_marketing_enabled() ) {
		return;
	}

	$pixel = teebe_analytics_meta_pixel_id();

	if ( '' === $pixel ) {
		return;
	}

	$GLOBALS['teebe_analytics_noscript_printed'] = true;
	?>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=<?php echo rawurlencode( $pixel ); ?>&ev=PageView&noscript=1"
/></noscript>
	<?php
}
add_action( 'wp_body_open', 'teebe_analytics_body_open', 1 );

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
