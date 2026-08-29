<?php
/**
 * Head metadata for the pages carried over from the static site.
 *
 * The wording, Open Graph tags and JSON-LD are generated from the static
 * pages' <head> into inc/seo-data.php by wordpress/sync-from-static.py. Every
 * URL is derived from home_url(), so changing the WordPress site address
 * updates all canonical, Open Graph and structured-data URLs without editing
 * a single template.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The generated metadata map, built once per request.
 *
 * @return array<string, array<string, mixed>>
 */
function teebe_seo_map() {
	static $map = null;

	if ( null === $map ) {
		$map = teebe_seo_data();
	}

	return $map;
}

/**
 * Metadata for the page currently being rendered.
 *
 * @return array<string, mixed> Empty when the page has no carried-over metadata.
 */
function teebe_seo_entry() {
	$map = teebe_seo_map();

	if ( is_404() ) {
		return isset( $map['404'] ) ? $map['404'] : array();
	}

	if ( is_front_page() ) {
		return isset( $map['front'] ) ? $map['front'] : array();
	}

	if ( is_page() ) {
		$slug = (string) get_post_field( 'post_name', get_queried_object_id() );

		if ( isset( $map[ $slug ] ) ) {
			return $map[ $slug ];
		}
	}

	return array();
}

/**
 * Resolves the {{home}} token the generated data uses in place of a hard-coded
 * domain, so the theme carries no site address of its own.
 *
 * @param mixed $value String, array, or scalar.
 * @return mixed
 */
function teebe_seo_resolve( $value ) {
	if ( is_string( $value ) ) {
		return str_replace( '{{home}}', untrailingslashit( home_url() ), $value );
	}

	if ( is_array( $value ) ) {
		return array_map( 'teebe_seo_resolve', $value );
	}

	return $value;
}

/**
 * Uses the static site's page title wording where one exists.
 *
 * @param string $title Default WordPress document title.
 * @return string
 */
function teebe_document_title( $title ) {
	$entry = teebe_seo_entry();

	return isset( $entry['title'] ) ? $entry['title'] : $title;
}
add_filter( 'pre_get_document_title', 'teebe_document_title' );

/**
 * Applies the noindex directive the static legal pages carried.
 *
 * @param array $robots Robots directives.
 * @return array
 */
function teebe_robots( $robots ) {
	$entry = teebe_seo_entry();

	if ( ! empty( $entry['noindex'] ) ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}

	return $robots;
}
add_filter( 'wp_robots', 'teebe_robots' );

/**
 * The theme emits its own canonical tag from home_url().
 */
function teebe_replace_canonical() {
	remove_action( 'wp_head', 'rel_canonical' );
}
add_action( 'wp', 'teebe_replace_canonical' );

/**
 * Prints the head metadata: description, canonical, social tags, icons,
 * and the JSON-LD the static page published.
 */
function teebe_head_meta() {
	$entry = teebe_seo_entry();
	$image = get_theme_file_uri( 'assets/images/brand/the-ebook-edit-og.jpg' );

	echo '<meta name="theme-color" content="#0047b9">' . "\n";

	if ( ! $entry ) {
		$canonical = wp_get_canonical_url();

		if ( $canonical ) {
			printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $canonical ) );
		}

		teebe_print_icons();

		return;
	}

	$url         = ! empty( $entry['path'] ) ? home_url( $entry['path'] ) : '';
	$title       = isset( $entry['title'] ) ? $entry['title'] : '';
	$description = isset( $entry['description'] ) ? $entry['description'] : '';
	$og_type     = isset( $entry['og_type'] ) ? $entry['og_type'] : 'website';

	if ( '' !== $description ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
	}

	if ( '' !== $url ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $url ) );
	}

	printf( '<meta property="og:type" content="%s">' . "\n", esc_attr( $og_type ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );

	if ( '' !== $url ) {
		printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	}

	printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	echo '<meta name="twitter:card" content="summary">' . "\n";
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image ) );

	teebe_print_icons();

	if ( ! empty( $entry['preload_logo'] ) ) {
		printf(
			'<link rel="preload" as="image" href="%s">' . "\n",
			esc_url( get_theme_file_uri( 'assets/images/brand/the-ebook-edit-logo.webp' ) )
		);
	}

	if ( empty( $entry['schema'] ) ) {
		return;
	}

	foreach ( $entry['schema'] as $document ) {
		printf(
			'<script type="application/ld+json">%s</script>' . "\n",
			wp_json_encode( teebe_seo_resolve( $document ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		);
	}
}
add_action( 'wp_head', 'teebe_head_meta', 2 );

/**
 * Falls back to the bundled icons until a Site Icon is set in the Customizer.
 */
function teebe_print_icons() {
	if ( has_site_icon() ) {
		return;
	}

	printf(
		'<link rel="icon" href="%s" sizes="48x48">' . "\n",
		esc_url( get_theme_file_uri( 'assets/images/favicon.ico' ) )
	);
	printf(
		'<link rel="icon" type="image/png" sizes="512x512" href="%s">' . "\n",
		esc_url( get_theme_file_uri( 'assets/images/brand/the-ebook-edit-icon-512.png' ) )
	);
	printf(
		'<link rel="apple-touch-icon" href="%s">' . "\n",
		esc_url( get_theme_file_uri( 'assets/images/brand/apple-touch-icon.png' ) )
	);
}
