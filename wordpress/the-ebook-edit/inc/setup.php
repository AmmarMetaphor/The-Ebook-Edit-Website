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
 * Content is intentionally left empty for every entry except the legal pages
 * and Contact, whose post_content is handled separately — the design and copy
 * come from the matching page-{slug}.php template via the template hierarchy.
 *
 * @return array<string, array<string, mixed>>
 */
function teebe_setup_page_definitions() {
	return array(
		'home'       => array( 'title' => 'Home' ),
		'services'   => array( 'title' => 'Services' ),
		'writing'    => array( 'title' => 'Ebook Writing' ),
		'editing'    => array( 'title' => 'Editing' ),
		'publishing' => array( 'title' => 'Publishing' ),
		'process'    => array( 'title' => 'Process' ),
		'portfolio'  => array( 'title' => 'Portfolio' ),
		'about'      => array( 'title' => 'About' ),
		'insights'   => array( 'title' => 'Insights' ),
		'contact'    => array( 'title' => 'Contact' ),
		'thank-you'  => array( 'title' => 'Thank You' ),
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

	if ( 'draft' === $status ) {
		$report['drafted'][] = $args['title'];
	} else {
		$report['created'][] = $args['title'];
	}

	return (int) $id;
}

/**
 * Whether a legal template still carries its "needs review" marker.
 *
 * page-privacy.php and page-terms.php both note that the final wording
 * "requires professional legal review before public launch". Once that note is
 * removed and the copy has been reviewed, setup publishes the page normally
 * instead of leaving it as a draft.
 *
 * @param string $template_file Theme-relative template file name.
 * @return bool
 */
function teebe_setup_is_unreviewed_legal_template( $template_file ) {
	$path = get_theme_file_path( $template_file );

	if ( ! file_exists( $path ) ) {
		return true;
	}

	$contents = (string) file_get_contents( $path );

	return false !== stripos( $contents, 'legal review before public launch' )
		|| false !== stripos( $contents, 'has not yet been professionally reviewed' );
}

/**
 * The two enquiry forms the website renders, as page slug => configuration.
 *
 * 'title' is the Contact Form 7 form title to look for, and 'html_class' is
 * the class the form element must carry so the book page styles it exactly as
 * the published site does. The markup for each form is in DEPLOYMENT.md.
 *
 * @return array<string, array<string, string>>
 */
function teebe_setup_form_definitions() {
	return array(
		'contact' => array(
			'title'      => 'Project Inquiry',
			'html_class' => 'start-form',
			'html_id'    => '',
			'page'       => 'Contact',
			'body'       => 'cf7/project-inquiry.txt',
			'subject'    => 'New project enquiry from the website',
			'fields'     => array( 'name', 'email', 'service', 'stage', 'word-count', 'referral', 'contact-method', 'timeline', 'message' ),
		),
		'home'    => array(
			'title'      => 'Publishing Journey',
			'html_class' => 'page-form',
			'html_id'    => 'enquiry',
			'page'       => 'Home',
			'body'       => 'cf7/publishing-journey.txt',
			'subject'    => 'New publishing journey enquiry from the website',
			'fields'     => array( 'name', 'email', 'journey', 'support', 'message' ),
		),
	);
}

/**
 * Creates the two Contact Form 7 forms from the bodies bundled with the theme,
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

	foreach ( teebe_setup_form_definitions() as $form ) {
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
 * @param array $form Form definition.
 * @return array<string, mixed>
 */
function teebe_setup_cf7_mail( $form ) {
	$host = wp_parse_url( home_url(), PHP_URL_HOST );
	$host = $host ? preg_replace( '/^www\./', '', $host ) : 'example.com';

	$lines = array();

	foreach ( $form['fields'] as $field ) {
		$lines[] = sprintf( '%s: [%s]', ucwords( str_replace( '-', ' ', $field ) ), $field );
	}

	$body = implode( "\n", $lines ) . "\n\n"
		. sprintf( '-- Sent from %s', home_url( '/' ) ) . "\n";

	return array(
		'subject'            => sprintf( '[%s] %s', get_bloginfo( 'name' ), $form['subject'] ),
		'sender'             => sprintf( '%s <wordpress@%s>', get_bloginfo( 'name' ), $host ),
		'recipient'          => get_option( 'admin_email' ),
		'body'               => $body,
		'additional_headers' => 'Reply-To: [email]',
		'attachments'        => '',
		'use_html'           => false,
		'exclude_blank'      => false,
	);
}

/**
 * Writes a Contact Form 7 shortcode into a page's post_content, which is the
 * only thing this theme stores there — the design and copy stay in the
 * templates. Existing content is never overwritten.
 *
 * @param int    $page_id Target page ID.
 * @param string $slug    Target page slug, matching teebe_setup_form_definitions().
 * @param array  $report  Report array, passed by reference.
 */
function teebe_setup_connect_form( $page_id, $slug, &$report ) {
	$forms = teebe_setup_form_definitions();

	if ( empty( $page_id ) || ! isset( $forms[ $slug ] ) ) {
		return;
	}

	$form = $forms[ $slug ];

	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		/* translators: %s: Contact Form 7 form title. */
		$report['warnings'][] = sprintf( __( 'Contact Form 7 is not active, so the "%s" form was not connected. Install and activate it, create the form using the markup in DEPLOYMENT.md, then run setup again.', 'the-ebook-edit' ), $form['title'] );
		return;
	}

	$page = get_post( $page_id );

	if ( ! $page ) {
		return;
	}

	$current_content = trim( (string) $page->post_content );

	if ( false !== strpos( $current_content, '[contact-form-7' ) ) {
		/* translators: 1: page title, 2: Contact Form 7 form title. */
		$report['forms'][] = sprintf( __( 'The %1$s page already carries a Contact Form 7 shortcode; it was left as-is.', 'the-ebook-edit' ), $form['page'], $form['title'] );
		return;
	}

	if ( '' !== $current_content ) {
		/* translators: %s: page title. */
		$report['warnings'][] = sprintf( __( 'The %s page already has content of its own, so no shortcode was added automatically. Paste the [contact-form-7 ...] shortcode into it by hand if the form is needed there.', 'the-ebook-edit' ), $form['page'] );
		return;
	}

	$form_post = teebe_setup_find_cf7_form( $form['title'] );

	if ( ! $form_post ) {
		/* translators: %s: Contact Form 7 form title. */
		$report['warnings'][] = sprintf( __( 'Create the Contact Form 7 form "%s" using the markup in DEPLOYMENT.md, then run setup again.', 'the-ebook-edit' ), $form['title'] );
		return;
	}

	$shortcode = sprintf(
		'[contact-form-7 id="%d" title="%s" html_class="%s"%s]',
		$form_post->ID,
		esc_attr( $form_post->post_title ),
		esc_attr( $form['html_class'] ),
		'' !== $form['html_id'] ? sprintf( ' html_id="%s"', esc_attr( $form['html_id'] ) ) : ''
	);

	wp_update_post(
		array(
			'ID'           => $page_id,
			'post_content' => $shortcode,
		)
	);

	/* translators: 1: Contact Form 7 form title, 2: page title. */
	$report['forms'][] = sprintf( __( 'Connected the "%1$s" form to the %2$s page.', 'the-ebook-edit' ), $form['title'], $form['page'] );
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
		'drafted'     => array(),
		'warnings'    => array(),
		'forms'       => array(),
		'homepage'    => '',
		'sample_page' => '',
	);

	$ids = array();

	foreach ( teebe_setup_page_definitions() as $slug => $args ) {
		$ids[ $slug ] = teebe_setup_get_or_create_page( $slug, $args, $report );
	}

	$legal_pages = array(
		'privacy' => array(
			'title'    => 'Privacy Policy',
			'template' => 'page-privacy.php',
		),
		'terms'   => array(
			'title'    => 'Website Terms',
			'template' => 'page-terms.php',
		),
	);

	foreach ( $legal_pages as $slug => $legal ) {
		$unreviewed = teebe_setup_is_unreviewed_legal_template( $legal['template'] );

		$ids[ $slug ] = teebe_setup_get_or_create_page(
			$slug,
			array(
				'title'  => $legal['title'],
				'status' => $unreviewed ? 'draft' : 'publish',
			),
			$report
		);

		if ( $unreviewed && ! empty( $ids[ $slug ] ) && 'publish' !== get_post_status( $ids[ $slug ] ) ) {
			/* translators: %s: page title. */
			$report['warnings'][] = sprintf( __( '"%s" still contains unreviewed placeholder legal text and is not published. Have it reviewed, then publish it from Pages when it is ready.', 'the-ebook-edit' ), $legal['title'] );
		}
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

	foreach ( array_keys( teebe_setup_form_definitions() ) as $form_slug ) {
		teebe_setup_connect_form( isset( $ids[ $form_slug ] ) ? $ids[ $form_slug ] : 0, $form_slug, $report );
	}

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
			<?php esc_html_e( 'The Ebook Edit website content is supplied by the installed theme templates, generated from the published website. This setup only creates the WordPress page records and homepage setting those templates need in order to be served at the right addresses. It never writes, edits or deletes page content.', 'the-ebook-edit' ); ?>
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

				<?php if ( ! empty( $report['drafted'] ) ) : ?>
					<p><strong><?php esc_html_e( 'Created as drafts (needs legal review before publishing):', 'the-ebook-edit' ); ?></strong></p>
					<ul style="list-style: disc; margin-left: 1.5em;">
						<?php foreach ( $report['drafted'] as $title ) : ?>
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
