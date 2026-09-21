<?php
/**
 * The Ebook Edit — marketing attribution on every lead.
 *
 * The website receives paid traffic, so each enquiry has to say which
 * campaign produced it. The browser collects that from the link the visitor
 * arrived on and from the referrer, writes it into hidden fields Contact
 * Form 7 renders, and this file reads those fields back on submission:
 *
 *   * every value is sanitised and length-limited before it is used;
 *   * the values join the Contact Form 7 submission data, so Flamingo stores
 *     them alongside the enquiry when it is installed — and delivery never
 *     depends on Flamingo being there;
 *   * a compact "Marketing Attribution" block is appended to the internal
 *     notification email, leaving the visitor-facing form untouched.
 *
 * Nothing here is sent to an analytics service, and nothing personal is
 * stored in the browser: the fields hold campaign parameters, click
 * identifiers, the landing page and the referring site, and nothing else.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The attribution fields carried with every lead, as field name => label.
 *
 * The order is the order they appear in the notification email.
 *
 * @return array<string, string>
 */
function teebe_attribution_fields() {
	return array(
		'utm_source'         => 'Source',
		'utm_medium'         => 'Medium',
		'utm_campaign'       => 'Campaign',
		'utm_content'        => 'Content',
		'utm_term'           => 'Term',
		'gclid'              => 'GCLID',
		'gbraid'             => 'GBRAID',
		'wbraid'             => 'WBRAID',
		'fbclid'             => 'FBCLID',
		'msclkid'            => 'MSCLKID',
		'landing_page'       => 'Landing Page',
		'original_referrer'  => 'Referrer',
		'submission_page'    => 'Submitted From',
		'first_touch_source' => 'First Touch',
		'latest_touch_source' => 'Latest Touch',
	);
}

/**
 * Fields that hold a URL and are sanitised as one.
 *
 * @return string[]
 */
function teebe_attribution_url_fields() {
	return array( 'landing_page', 'original_referrer', 'submission_page' );
}

/**
 * The longest value accepted for any one field.
 *
 * Long enough for a real campaign URL, short enough that a crafted request
 * cannot pad a notification email.
 */
const TEEBE_ATTRIBUTION_MAX_LENGTH = 400;

/**
 * Adds the empty attribution inputs to every Contact Form 7 form.
 *
 * Contact Form 7's own hook for this, so the fields are part of the form it
 * renders and post with it. They are rendered empty and filled in by
 * assets/js/attribution.js: the values are only knowable in the browser.
 *
 * @param array<string, string> $fields Hidden fields Contact Form 7 will render.
 * @return array<string, string>
 */
function teebe_attribution_hidden_fields( $fields ) {
	foreach ( array_keys( teebe_attribution_fields() ) as $name ) {
		$fields[ $name ] = '';
	}

	return $fields;
}
add_filter( 'wpcf7_form_hidden_fields', 'teebe_attribution_hidden_fields' );

/**
 * One posted value, sanitised for its kind.
 *
 * @param string $name  Field name.
 * @param mixed  $value Raw posted value.
 * @return string Empty when the value is missing or unusable.
 */
function teebe_attribution_clean( $name, $value ) {
	if ( ! is_string( $value ) ) {
		return '';
	}

	$value = wp_unslash( $value );

	if ( in_array( $name, teebe_attribution_url_fields(), true ) ) {
		// Drops anything that is not an allowed protocol, so a javascript:
		// or data: URL never reaches the inbox as a clickable line.
		$value = esc_url_raw( $value );
	} else {
		$value = sanitize_text_field( $value );
	}

	// Each value becomes one "Label: value" line in the notification email.
	// sanitize_text_field() already collapses whitespace, but esc_url_raw()
	// does not promise to, and one line per field is the point.
	$value = trim( preg_replace( '/\s+/', ' ', $value ) );

	if ( '' === $value ) {
		return '';
	}

	if ( function_exists( 'mb_substr' ) ) {
		return mb_substr( $value, 0, TEEBE_ATTRIBUTION_MAX_LENGTH );
	}

	return substr( $value, 0, TEEBE_ATTRIBUTION_MAX_LENGTH );
}

/**
 * Every attribution value posted with the current submission.
 *
 * @return array<string, string> Only the fields that carry a value.
 */
function teebe_attribution_posted_values() {
	$values = array();

	foreach ( array_keys( teebe_attribution_fields() ) as $name ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Contact Form 7 verifies the submission; these values are sanitised here and never trusted.
		if ( ! isset( $_POST[ $name ] ) ) {
			continue;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- As above.
		$clean = teebe_attribution_clean( $name, $_POST[ $name ] );

		if ( '' !== $clean ) {
			$values[ $name ] = $clean;
		}
	}

	return $values;
}

/**
 * Puts the attribution values, and the Contact form's service context, into
 * the Contact Form 7 submission data.
 *
 * Contact Form 7 only collects the fields declared as form tags, so these
 * would otherwise be dropped. Adding them here is what makes them visible
 * to Flamingo, which reads the same data — without making Flamingo a
 * requirement for the enquiry to be delivered.
 *
 * @param array<string, mixed> $posted Submission data.
 * @return array<string, mixed>
 */
function teebe_attribution_posted_data( $posted ) {
	foreach ( teebe_attribution_posted_values() as $name => $value ) {
		$posted[ $name ] = $value;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Contact Form 7 verifies the submission; the value is sanitised here.
	if ( isset( $_POST['service_interest'] ) && ! isset( $posted['service_interest'] ) ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- As above.
		$service = sanitize_text_field( wp_unslash( $_POST['service_interest'] ) );

		if ( '' !== $service ) {
			$posted['service_interest'] = $service;
		}
	}

	return $posted;
}
add_filter( 'wpcf7_posted_data', 'teebe_attribution_posted_data' );

/**
 * Appends the Marketing Attribution block to the internal notification.
 *
 * Only the internal message is changed: Contact Form 7's second mail, the
 * autoresponder to the visitor, is left exactly as configured. Nothing is
 * appended when a submission carries no attribution at all, so a direct
 * visit produces the same clean email it always did.
 *
 * @param array<string, string>     $components Mail components.
 * @param WPCF7_ContactForm|null    $form       Form being sent.
 * @param WPCF7_Mail|null           $mail       Mail object.
 * @return array<string, string>
 */
function teebe_attribution_mail_components( $components, $form = null, $mail = null ) {
	if ( $mail && method_exists( $mail, 'name' ) && 'mail' !== $mail->name() ) {
		return $components;
	}

	$values  = teebe_attribution_posted_values();
	$labels  = teebe_attribution_fields();
	$service = '';

	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Contact Form 7 verifies the submission; the value is sanitised here.
	if ( isset( $_POST['service_interest'] ) ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- As above.
		$service = sanitize_text_field( wp_unslash( $_POST['service_interest'] ) );
	}

	if ( empty( $values ) && '' === $service ) {
		return $components;
	}

	$lines = array( '', '-- Marketing Attribution --' );

	if ( '' !== $service ) {
		$lines[] = 'Service Interest: ' . $service;
	}

	foreach ( $labels as $name => $label ) {
		if ( isset( $values[ $name ] ) ) {
			$lines[] = $label . ': ' . $values[ $name ];
		}
	}

	$block = implode( "\n", $lines ) . "\n";

	if ( ! empty( $components['body'] ) && false !== strpos( $components['body'], '<' )
		&& false !== stripos( $components['body'], '</' ) ) {
		$components['body'] .= "\n<pre>" . esc_html( $block ) . '</pre>';
	} else {
		$components['body'] .= "\n" . $block;
	}

	return $components;
}
add_filter( 'wpcf7_mail_components', 'teebe_attribution_mail_components', 10, 3 );

/**
 * The script that fills the hidden fields in.
 *
 * Enqueued wherever Contact Form 7 might render a form, which is every
 * public page: a lead form appears on the homepage, the contact page and
 * the Meta Ads landing page.
 */
function teebe_attribution_assets() {
	wp_enqueue_script(
		'the-ebook-edit-attribution',
		get_theme_file_uri( 'assets/js/attribution.js' ),
		array(),
		(string) filemtime( get_theme_file_path( 'assets/js/attribution.js' ) ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
