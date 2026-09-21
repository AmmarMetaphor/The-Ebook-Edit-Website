<?php
/**
 * The Ebook Edit — one-click site setup.
 *
 * Creates the WordPress page records, primary navigation menu, and homepage
 * configuration the theme's templates need to route correctly. It never runs
 * automatically — only when an administrator clicks the button on
 * Appearance → The Ebook Edit Setup — and it is safe to run more than once:
 * every step checks for existing data before creating anything.
 *
 * The website's designed content is not stored here. It lives in the theme's
 * page-*.php templates, ported from the original static site. This file only
 * creates the routing records WordPress needs to serve those templates at the
 * right URLs.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the admin screen under Appearance.
 */
function teebe_setup_admin_menu() {
	add_theme_page(
		__( 'The Ebook Edit Setup', 'the-ebook-edit' ),
		__( 'The Ebook Edit Setup', 'the-ebook-edit' ),
		'manage_options',
		'teebe-setup',
		'teebe_setup_render_page'
	);
}
add_action( 'admin_menu', 'teebe_setup_admin_menu' );

/**
 * Page records the setup routine creates or finds, keyed by slug.
 *
 * post_content is left empty for every one of them: the design and copy come
 * from the matching page-{slug}.php template through the template hierarchy,
 * and the enquiry forms are found by title, so nothing has to be pasted into
 * a page and an administrator cannot break the design by editing one.
 *
 * @return array<string, array<string, mixed>>
 */
function teebe_setup_page_definitions() {
	return array(
		'home'                 => array( 'title' => 'Home' ),
		'services'             => array( 'title' => 'Services' ),
		'writing'              => array( 'title' => 'Book Writing' ),
		'editing'              => array( 'title' => 'Book Editing' ),
		'publishing'           => array( 'title' => 'Book Publishing' ),
		'process'              => array( 'title' => 'Process' ),
		'portfolio'            => array( 'title' => 'Portfolio' ),
		'about'                => array( 'title' => 'About' ),
		'insights'             => array( 'title' => 'Insights' ),
		'contact'              => array( 'title' => 'Contact' ),
		'book-consultation'    => array( 'title' => 'Book a Free Consultation' ),
		'thank-you'            => array( 'title' => 'Thank You' ),
		'privacy-policy'       => array( 'title' => 'Privacy Policy' ),
		'terms-and-conditions' => array( 'title' => 'Terms & Conditions' ),
	);
}

/**
 * Insights articles created as child pages of Insights, each routed to its
 * existing slug-specific template via the "Template Name" the template file
 * already declares — see template-insight-*.php.
 *
 * @return array<int, array<string, string>>
 */
function teebe_setup_article_definitions() {
	return array(
		array(
			'slug'     => 'turn-expertise-into-an-ebook',
			'title'    => 'How to Turn Your Expertise Into an Ebook',
			'template' => 'template-insight-turn-expertise.php',
		),
		array(
			'slug'     => 'editing-levels-explained',
			'title'    => 'Editing Levels Explained',
			'template' => 'template-insight-editing-levels.php',
		),
		array(
			'slug'     => 'pre-publishing-checklist',
			'title'    => 'A Pre-Publishing Checklist for a Professional Ebook',
			'template' => 'template-insight-pre-publishing.php',
		),
		array(
			'slug'     => 'kindle-and-ebook-platform-guide',
			'title'    => 'Publishing an Ebook on Kindle and Other Platforms',
			'template' => 'template-insight-kindle-platforms.php',
		),
	);
}

/**
 * Finds a page by slug, or creates it when it does not already exist.
 *
 * @param string $slug   Page slug.
 * @param array  $args   'title' (required), 'status', 'parent_id', 'template', 'content'.
 * @param array  $report Report array, passed by reference.
 * @return int Page ID, or 0 on failure.
 */
function teebe_setup_get_or_create_page( $slug, $args, &$report ) {
	$existing = get_page_by_path( $slug, OBJECT, 'page' );

	if ( $existing ) {
		$report['existing'][] = $args['title'];
		return (int) $existing->ID;
	}

	$status = isset( $args['status'] ) ? $args['status'] : 'publish';

	$postarr = array(
		'post_title'   => $args['title'],
		'post_name'    => $slug,
		'post_status'  => $status,
		'post_type'    => 'page',
		'post_content' => isset( $args['content'] ) ? $args['content'] : '',
	);

	if ( ! empty( $args['parent_id'] ) ) {
		$postarr['post_parent'] = (int) $args['parent_id'];
	}

	$id = wp_insert_post( $postarr, true );

	if ( is_wp_error( $id ) ) {
		/* translators: 1: page title, 2: error message. */
		$report['warnings'][] = sprintf( __( 'Could not create the "%1$s" page: %2$s', 'the-ebook-edit' ), $args['title'], $id->get_error_message() );
		return 0;
	}

	if ( ! empty( $args['template'] ) ) {
		update_post_meta( $id, '_wp_page_template', $args['template'] );
	}

	$report['created'][] = $args['title'];

	return (int) $id;
}

/**
 * Every Contact Form 7 form the setup routine creates.
 *
 * Three: the website's homepage and contact forms, from inc/site.php, and
 * the Meta Ads landing page's, from inc/landing.php. Each template finds its
 * own by title, so no shortcode is written into any page and a form can be
 * moved or restyled without editing page content.
 *
 * Keeping the landing page's form separate from the website's is deliberate:
 * it is what lets a lead be attributed to the page it came from without
 * reading anything a visitor typed.
 *
 * @return array<int, array<string, mixed>>
 */
function teebe_setup_all_form_definitions() {
	$forms = array();

	if ( function_exists( 'teebe_site_form_definitions' ) ) {
		$forms = array_values( teebe_site_form_definitions() );
	}

	if ( function_exists( 'teebe_landing_form_definition' ) ) {
		$forms[] = teebe_landing_form_definition();
	}

	return $forms;
}

/**
 * Creates the Contact Form 7 forms from the bodies bundled with the theme,
 * so the WordPress site renders the same forms as the published website.
 *
 * A form is created only when no form with that title exists, so running setup
 * again never duplicates one and never overwrites a form that has been edited.
 * No mail server settings are written: Contact Form 7 sends through whatever
 * WordPress is already configured to use, and no credentials are stored here
 * or anywhere else in the theme.
 *
 * @param array $report Report array, passed by reference.
 */
function teebe_setup_create_cf7_forms( &$report ) {
	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		return;
	}

	foreach ( teebe_setup_all_form_definitions() as $form ) {
		if ( teebe_setup_find_cf7_form( $form['title'] ) ) {
			continue;
		}

		$path = get_theme_file_path( $form['body'] );

		if ( ! file_exists( $path ) ) {
			/* translators: %s: file name. */
			$report['warnings'][] = sprintf( __( 'The bundled form body %s is missing from the theme, so the form was not created.', 'the-ebook-edit' ), $form['body'] );
			continue;
		}

		$body = str_replace(
			'{{home}}',
			untrailingslashit( home_url() ),
			(string) file_get_contents( $path )
		);

		$contact_form = WPCF7_ContactForm::get_template( array( 'title' => $form['title'] ) );

		if ( ! $contact_form ) {
			continue;
		}

		$contact_form->set_properties(
			array(
				'form' => $body,
				'mail' => teebe_setup_cf7_mail( $form ),
			)
		);

		$id = $contact_form->save();

		if ( ! $id ) {
			/* translators: %s: Contact Form 7 form title. */
			$report['warnings'][] = sprintf( __( 'Could not create the Contact Form 7 form "%s". Create it by hand using the markup in DEPLOYMENT.md.', 'the-ebook-edit' ), $form['title'] );
			continue;
		}

		/* translators: %s: Contact Form 7 form title. */
		$report['forms'][] = sprintf( __( 'Created the Contact Form 7 form "%s" from the markup bundled with the theme.', 'the-ebook-edit' ), $form['title'] );
	}
}

/**
 * The mail template for one form.
 *
 * The From address is on the site's own domain so the message passes SPF and
 * DMARC checks; the visitor's address goes in Reply-To. Change the recipient
 * under Contact → Contact Forms → Mail at any time.
 *
 * A form's 'fields' may be a plain list of tag names, in which case the label
 * is derived from the name, or a map of label => tag name where the derived
 * label would read poorly.
 *
 * @param array $form Form definition.
 * @return array<string, mixed>
 */
function teebe_setup_cf7_mail( $form ) {
	$host = wp_parse_url( home_url(), PHP_URL_HOST );
	$host = $host ? preg_replace( '/^www\./', '', $host ) : 'example.com';

	$lines = array();

	foreach ( $form['fields'] as $label => $field ) {
		if ( ! is_string( $label ) ) {
			$label = ucwords( str_replace( array( '-', '_' ), ' ', $field ) );
		}

		$lines[] = sprintf( '%s: [%s]', $label, $field );
	}

	$body = implode( "\n", $lines ) . "\n\n"
		. sprintf( '-- Sent from %s', home_url( '/' ) ) . "\n";

	return array(
		'subject'            => sprintf( '[%s] %s', get_bloginfo( 'name' ), $form['subject'] ),
		'sender'             => sprintf( '%s <wordpress@%s>', get_bloginfo( 'name' ), $host ),
		'recipient'          => empty( $form['recipient'] ) ? get_option( 'admin_email' ) : $form['recipient'],
		'body'               => $body,
		'additional_headers' => 'Reply-To: [email]',
		'attachments'        => '',
		'use_html'           => false,
		'exclude_blank'      => false,
	);
}

/**
 * Finds a Contact Form 7 form by its exact title.
 *
 * @param string $title Form title.
 * @return WP_Post|null
 */
function teebe_setup_find_cf7_form( $title ) {
	$query = new WP_Query(
		array(
			'post_type'              => 'wpcf7_contact_form',
			'title'                  => $title,
			'posts_per_page'         => 1,
			'post_status'            => 'any',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	if ( ! $query->have_posts() ) {
		return null;
	}

	$form_post = $query->posts[0];

	return 0 === strcasecmp( $form_post->post_title, $title ) ? $form_post : null;
}

/**
 * Moves WordPress's default "Sample Page" to Trash.
 *
 * This is the only step that removes anything, so it is opt-in: it runs only
 * when the administrator ticks the box on the setup screen, and even then only
 * when the page is still the untouched WordPress default — same title, same
 * starter text. Anything edited, renamed or written by a person is left
 * exactly where it is, and Trash is reversible either way.
 *
 * @param array $report Report array, passed by reference.
 */
function teebe_setup_trash_sample_page( &$report ) {
	$sample = get_page_by_path( 'sample-page', OBJECT, 'page' );

	if ( ! $sample || 'trash' === $sample->post_status ) {
		return;
	}

	if ( 'Sample Page' !== $sample->post_title
		|| false === strpos( (string) $sample->post_content, 'This is an example page' ) ) {
		$report['warnings'][] = __( 'A page at "sample-page" exists but is not the untouched WordPress default, so it was left in place. Delete it yourself from Pages if it is not needed.', 'the-ebook-edit' );
		return;
	}

	wp_trash_post( $sample->ID );
	$report['sample_page'] = __( 'Moved the default WordPress "Sample Page" to Trash. Restore it from Pages → Trash if you need it back.', 'the-ebook-edit' );
}

/**
 * Runs the full setup routine and returns a report of what happened.
 *
 * @param bool $trash_sample_page Whether to move an untouched default
 *                                "Sample Page" to Trash. Opt-in, off by default.
 * @return array<string, mixed>
 */
function teebe_run_setup( $trash_sample_page = false ) {
	$report = array(
		'created'     => array(),
		'existing'    => array(),
		'warnings'    => array(),
		'forms'       => array(),
		'homepage'    => '',
		'sample_page' => '',
	);

	$ids = array();

	foreach ( teebe_setup_page_definitions() as $slug => $args ) {
		$ids[ $slug ] = teebe_setup_get_or_create_page( $slug, $args, $report );
	}

	foreach ( teebe_setup_article_definitions() as $article ) {
		teebe_setup_get_or_create_page(
			$article['slug'],
			array(
				'title'     => $article['title'],
				'template'  => $article['template'],
				'parent_id' => $ids['insights'],
			),
			$report
		);
	}

	if ( ! empty( $ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['home'] );
		update_option( 'page_for_posts', 0 );
		$report['homepage'] = __( 'Set the static homepage to "Home".', 'the-ebook-edit' );
	}

	teebe_setup_create_cf7_forms( $report );

	if ( $trash_sample_page ) {
		teebe_setup_trash_sample_page( $report );
	}

	flush_rewrite_rules();

	return $report;
}

/**
 * Handles the setup form submission from Appearance → The Ebook Edit Setup.
 *
 * Runs only on an explicit POST from an administrator with a valid nonce —
 * never on a normal page load.
 */
function teebe_handle_setup_submit() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'the-ebook-edit' ) );
	}

	check_admin_referer( 'teebe_run_setup', 'teebe_setup_nonce' );

	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified above.
	$trash_sample_page = isset( $_POST['teebe_trash_sample_page'] ) && '1' === $_POST['teebe_trash_sample_page'];

	$report = teebe_run_setup( $trash_sample_page );

	set_transient( 'teebe_setup_report_' . get_current_user_id(), $report, MINUTE_IN_SECONDS * 5 );

	wp_safe_redirect( add_query_arg( array( 'teebe-setup-complete' => '1' ), wp_get_referer() ? wp_get_referer() : admin_url( 'themes.php?page=teebe-setup' ) ) );
	exit;
}
add_action( 'admin_post_teebe_run_setup', 'teebe_handle_setup_submit' );

/**
 * Renders the admin screen, including the results report after setup runs.
 */
function teebe_setup_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$report = get_transient( 'teebe_setup_report_' . get_current_user_id() );

	if ( $report ) {
		delete_transient( 'teebe_setup_report_' . get_current_user_id() );
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'The Ebook Edit Setup', 'the-ebook-edit' ); ?></h1>
		<p>
			<?php esc_html_e( 'The Ebook Edit website content is supplied by the installed theme templates. This setup only creates the WordPress page records, the homepage setting and the Contact Form 7 enquiry forms those templates need in order to work. It never writes, edits or deletes page content.', 'the-ebook-edit' ); ?>
		</p>

		<p>
			<?php
			printf(
				/* translators: 1: page template name, 2: Contact Form 7 form title. */
				esc_html__( 'The Meta Ads landing page is not created here, because it is not part of the website. To publish it, add a page yourself under Pages → Add New, give it the address you want to advertise, and choose "%1$s" under Page Attributes → Template. Setup does create its enquiry form, "%2$s", which that template finds on its own — there is no shortcode to paste.', 'the-ebook-edit' ),
				esc_html__( 'The Ebook Edit — Meta Ads Landing Page', 'the-ebook-edit' ),
				'Start Your Book'
			);
			?>
		</p>

		<p>
			<?php esc_html_e( 'All three enquiry forms are found by title, so no shortcode is stored in any page. Mail delivery is a WordPress setting, not a theme setting: install and configure an SMTP plugin such as WP Mail SMTP so Contact Form 7 can actually send. No mail server credentials are stored in this theme.', 'the-ebook-edit' ); ?>
		</p>

		<p>
			<?php esc_html_e( 'Google Analytics 4 and Microsoft Clarity are built into the theme and load on every public page. This site is UK-facing: install a consent-management plugin that implements the WordPress Consent API and categorise both as statistics, and the theme will honour the visitor\'s choice automatically. Until one is installed, both tags load on every visit. See DEPLOYMENT.md.', 'the-ebook-edit' ); ?>
		</p>

		<?php if ( is_array( $report ) ) : ?>
			<div class="notice notice-success">
				<h2><?php esc_html_e( 'The Ebook Edit setup complete', 'the-ebook-edit' ); ?></h2>

				<?php if ( ! empty( $report['created'] ) ) : ?>
					<p><strong><?php esc_html_e( 'Created:', 'the-ebook-edit' ); ?></strong></p>
					<ul style="list-style: disc; margin-left: 1.5em;">
						<?php foreach ( $report['created'] as $title ) : ?>
							<li><?php echo esc_html( $title ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

	<?php if ( ! empty( $report['existing'] ) ) : ?>
					<p><strong><?php esc_html_e( 'Already existed and left untouched:', 'the-ebook-edit' ); ?></strong></p>
					<ul style="list-style: disc; margin-left: 1.5em;">
						<?php foreach ( $report['existing'] as $title ) : ?>
							<li><?php echo esc_html( $title ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<p><strong><?php esc_html_e( 'Configured:', 'the-ebook-edit' ); ?></strong></p>
				<ul style="list-style: disc; margin-left: 1.5em;">
					<?php if ( ! empty( $report['homepage'] ) ) : ?>
						<li><?php echo esc_html( $report['homepage'] ); ?></li>
					<?php endif; ?>
					<?php foreach ( $report['forms'] as $form_note ) : ?>
						<li><?php echo esc_html( $form_note ); ?></li>
					<?php endforeach; ?>
					<?php if ( ! empty( $report['sample_page'] ) ) : ?>
						<li><?php echo esc_html( $report['sample_page'] ); ?></li>
					<?php endif; ?>
				</ul>

				<?php if ( ! empty( $report['warnings'] ) ) : ?>
					<p><strong><?php esc_html_e( 'Needs attention:', 'the-ebook-edit' ); ?></strong></p>
					<ul style="list-style: disc; margin-left: 1.5em;">
						<?php foreach ( $report['warnings'] as $warning ) : ?>
							<li><?php echo esc_html( $warning ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="teebe_run_setup">
			<?php wp_nonce_field( 'teebe_run_setup', 'teebe_setup_nonce' ); ?>
			<p>
				<label>
					<input type="checkbox" name="teebe_trash_sample_page" value="1">
					<?php esc_html_e( 'Also move the default WordPress "Sample Page" to Trash (only if it is still the untouched WordPress default).', 'the-ebook-edit' ); ?>
				</label>
			</p>
			<?php submit_button( __( 'Set up The Ebook Edit website', 'the-ebook-edit' ) ); ?>
		</form>

		<p>
			<?php esc_html_e( 'Safe to run more than once: existing pages and settings are detected and left alone, and nothing is duplicated. Nothing you have written is ever deleted or overwritten — the one optional removal is the checkbox above, and it moves the page to Trash, where it can be restored.', 'the-ebook-edit' ); ?>
		</p>
	</div>
	<?php
}
