<?php
/**
 * Nav menu walker that emits the flat anchor markup the site stylesheet expects.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Outputs bare <a> elements instead of the default <ul>/<li> structure.
 */
class Teebe_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * No sub-menu markup: the navigation is a single level.
	 *
	 * @param string $output Menu markup.
	 * @param int    $depth  Menu depth.
	 * @param array  $args   Menu arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {}

	/**
	 * No sub-menu markup: the navigation is a single level.
	 *
	 * @param string $output Menu markup.
	 * @param int    $depth  Menu depth.
	 * @param array  $args   Menu arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	/**
	 * Renders one navigation link.
	 *
	 * Add the CSS class "nav-cta" to a menu item in the WordPress menu editor to
	 * render it as the gold call-to-action button.
	 *
	 * @param string  $output Menu markup.
	 * @param WP_Post $item   Menu item.
	 * @param int     $depth  Menu depth.
	 * @param array   $args   Menu arguments.
	 * @param int     $id     Menu item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( $depth > 0 ) {
			return;
		}

		$classes = is_array( $item->classes ) ? array_filter( $item->classes ) : array();

		$output .= sprintf(
			'<a href="%1$s"%2$s%3$s>%4$s</a>',
			esc_url( $item->url ),
			in_array( 'nav-cta', $classes, true ) ? ' class="nav-cta"' : '',
			teebe_is_current_nav_url( $item->url ) ? ' aria-current="page"' : '',
			esc_html( $item->title )
		);
	}

	/**
	 * Links are self-contained, so nothing closes them here.
	 *
	 * @param string  $output Menu markup.
	 * @param WP_Post $item   Menu item.
	 * @param int     $depth  Menu depth.
	 * @param array   $args   Menu arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {}
}
