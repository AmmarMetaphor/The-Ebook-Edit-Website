<?php
/**
 * The Ebook Edit — theme setup, assets, navigation, and contact-form hooks.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_theme_file_path( 'inc/nav-walker.php' );
require_once get_theme_file_path( 'inc/seo-meta.php' );

/**
 * Theme supports and menu locations.
 */
function teebe_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'the-ebook-edit' ),
			'social'  => __( 'Social Links', 'the-ebook-edit' ),
		)
	);
}
add_action( 'after_setup_theme', 'teebe_setup' );

/**
 * Stylesheet and script, versioned by file modification time so browsers pick
 * up changes without the manual cache-busting query string the static site used.
 */
function teebe_assets() {
	wp_enqueue_style(
		'the-ebook-edit',
		get_stylesheet_uri(),
		array(),
		(string) filemtime( get_theme_file_path( 'style.css' ) )
	);

	wp_enqueue_script(
		'the-ebook-edit',
		get_theme_file_uri( 'assets/js/main.js' ),
		array(),
		(string) filemtime( get_theme_file_path( 'assets/js/main.js' ) ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_localize_script(
		'the-ebook-edit',
		'theEbookEdit',
		array( 'thankYouUrl' => esc_url_raw( home_url( '/thank-you/' ) ) )
	);
}
add_action( 'wp_enqueue_scripts', 'teebe_assets' );

/**
 * Primary navigation items, in order, as path => label.
 *
 * There is deliberately no Home item: the header logo is the link home.
 *
 * @return array<string, string>
 */
function teebe_nav_items() {
	return array(
		'/services/'  => 'Services',
		'/process/'   => 'Process',
		'/portfolio/' => 'Portfolio',
		'/about/'     => 'About',
		'/insights/'  => 'Insights',
	);
}

/**
 * Which navigation item should be marked as the current page.
 *
 * The service detail pages sit outside the primary menu but highlight
 * "Services", and the articles highlight "Insights", matching the original
 * static site. Pages with no menu presence highlight nothing.
 *
 * @return string Site path of the active item, or '' when none is active.
 */
function teebe_active_nav_path() {
	if ( is_front_page() ) {
		return '/';
	}

	if ( ! is_page() ) {
		return '';
	}

	$map = array(
		'services'                        => '/services/',
		'writing'                         => '/services/',
		'editing'                         => '/services/',
		'publishing'                      => '/services/',
		'process'                         => '/process/',
		'portfolio'                       => '/portfolio/',
		'about'                           => '/about/',
		'insights'                        => '/insights/',
		'turn-expertise-into-an-ebook'    => '/insights/',
		'editing-levels-explained'        => '/insights/',
		'pre-publishing-checklist'        => '/insights/',
		'kindle-and-ebook-platform-guide' => '/insights/',
	);

	$slug = (string) get_post_field( 'post_name', get_queried_object_id() );

	return isset( $map[ $slug ] ) ? $map[ $slug ] : '';
}

/**
 * Whether a navigation URL points at the currently active section.
 *
 * @param string $url Absolute or relative URL.
 * @return bool
 */
function teebe_is_current_nav_url( $url ) {
	$active = teebe_active_nav_path();

	if ( '' === $active ) {
		return false;
	}

	$item   = untrailingslashit( (string) wp_parse_url( $url, PHP_URL_PATH ) );
	$target = untrailingslashit( (string) wp_parse_url( home_url( $active ), PHP_URL_PATH ) );

	return $item === $target;
}

/**
 * Renders the primary navigation links, using an assigned menu when one exists
 * and the original link set otherwise, so the theme works on activation.
 */
function teebe_primary_nav() {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'items_wrap'     => '%3$s',
				'depth'          => 1,
				'walker'         => new Teebe_Nav_Walker(),
			)
		);

		return;
	}

	teebe_default_nav();
}

/**
 * The navigation link set carried over from the static site.
 */
function teebe_default_nav() {
	foreach ( teebe_nav_items() as $path => $label ) {
		printf(
			'<a href="%1$s"%2$s>%3$s</a>',
			esc_url( home_url( $path ) ),
			teebe_is_current_nav_url( home_url( $path ) ) ? ' aria-current="page"' : '',
			esc_html( $label )
		);
	}

	printf(
		'<a class="nav-cta" href="%s">Start a project</a>',
		esc_url( home_url( '/contact/' ) )
	);
}

/**
 * Publishing platforms named in the homepage section, as logo slug => name.
 *
 * These are platform names used to describe what the publishing-support service
 * prepares files for. They are not partners, and no affiliation is implied.
 *
 * @return array<string, string>
 */
function teebe_platforms() {
	return array(
		'amazon-kdp'         => 'Amazon Kindle Direct Publishing',
		'apple-books'        => 'Apple Books',
		'kobo-writing-life'  => 'Kobo Writing Life',
		'google-play-books'  => 'Google Play Books',
		'barnes-noble-press' => 'Barnes & Noble Press / NOOK',
		'draft2digital'      => 'Draft2Digital',
	);
}

/**
 * URL of an approved platform logo bundled with the theme, or '' when there is
 * none, in which case the template falls back to a text tile.
 *
 * Approved artwork goes in the theme's assets/images/platforms/ folder, named
 * after the slug in teebe_platforms() — for example amazon-kdp.svg.
 *
 * @param string $slug Platform slug.
 * @return string
 */
function teebe_platform_logo( $slug ) {
	foreach ( array( 'svg', 'png', 'webp' ) as $extension ) {
		$relative = 'assets/images/platforms/' . $slug . '.' . $extension;

		if ( file_exists( get_theme_file_path( $relative ) ) ) {
			return get_theme_file_uri( $relative );
		}
	}

	return '';
}

/**
 * Whether a Social Links menu has been created and assigned.
 *
 * The footer heading and list are only printed when this is true, so an
 * unconfigured site shows no empty block.
 *
 * @return bool
 */
function teebe_has_social_nav() {
	return has_nav_menu( 'social' );
}

/**
 * Renders the footer social links from the Social Links menu.
 *
 * Link labels come from the menu items, so accounts are added in
 * Appearance → Menus without editing the theme. Nothing is hard-coded and no
 * external icon font or script is loaded.
 */
function teebe_social_nav() {
	if ( ! teebe_has_social_nav() ) {
		return;
	}

	wp_nav_menu(
		array(
			'theme_location'  => 'social',
			'container'       => 'div',
			'container_class' => 'social-links',
			'menu_class'      => 'social-list',
			'depth'           => 1,
			'link_before'     => '<span class="social-label">',
			'link_after'      => '</span>',
			'fallback_cb'     => false,
		)
	);
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

// The contact form supplies its own grid markup, which auto-paragraphing breaks.
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/**
 * Reminds an administrator to install the plugin the contact form depends on.
 */
function teebe_cf7_admin_notice() {
	if ( class_exists( 'WPCF7' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	echo '<div class="notice notice-warning is-dismissible"><p>';
	echo esc_html__( 'The Ebook Edit: install and activate Contact Form 7 to enable the contact form. See DEPLOYMENT.md in the theme folder for the form configuration.', 'the-ebook-edit' );
	echo '</p></div>';
}
add_action( 'admin_notices', 'teebe_cf7_admin_notice' );
