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
 * Whether a legal template still contains its unreviewed starter wording.
 *
 * Both page-privacy.php and page-terms.php currently read "has not yet been
 * professionally reviewed" in their on-page notice. Once that notice is
 * replaced with reviewed, finalized copy, setup will publish the page
 * normally instead of leaving it as a draft.
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

	return false !== stripos( $contents, 'has not yet been professionally reviewed' );
}

/**
 * Creates or updates the Contact page's post_content with the Contact Form 7
 * shortcode for the form titled "Project Inquiry", without touching the
 * surrounding theme-designed layout.
 *
 * @param int   $contact_page_id Contact page ID.
 * @param array $report          Report array, passed by reference.
 */
function teebe_setup_connect_contact_form( $contact_page_id, &$report ) {
	if ( empty( $contact_page_id ) ) {
		return;
	}

	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		$report['warnings'][] = __( 'Contact Form 7 is not active. Install and activate it, create a form titled "Project Inquiry", then run setup again.', 'the-ebook-edit' );
		return;
	}

	$page = get_post( $contact_page_id );

	if ( ! $page ) {
		return;
	}

	$current_content = trim( (string) $page->post_content );

	if ( false !== strpos( $current_content, '[contact-form-7' ) ) {
		$report['contact_form'] = __( 'The Contact Form 7 shortcode is already present on the Contact page.', 'the-ebook-edit' );
		return;
	}

	if ( '' !== $current_content ) {
		$report['warnings'][] = __( 'The Contact page already has custom content, so the Contact Form 7 shortcode was not added automatically. Add [contact-form-7 ...] to it by hand if needed.', 'the-ebook-edit' );
		return;
	}

	$form_query = new WP_Query(
		array(
			'post_type'              => 'wpcf7_contact_form',
			'title'                  => 'Project Inquiry',
			'posts_per_page'         => 1,
			'post_status'            => 'any',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	if ( ! $form_query->have_posts() ) {
		$report['warnings'][] = __( 'Create the Contact Form 7 form "Project Inquiry", then run setup again.', 'the-ebook-edit' );
		return;
	}

	$form_post = $form_query->posts[0];

	if ( 0 !== strcasecmp( $form_post->post_title, 'Project Inquiry' ) ) {
		$report['warnings'][] = __( 'Create the Contact Form 7 form "Project Inquiry", then run setup again.', 'the-ebook-edit' );
		return;
	}

	$shortcode = sprintf( '[contact-form-7 id="%d" title="%s"]', $form_post->ID, esc_attr( $form_post->post_title ) );

	wp_update_post(
		array(
			'ID'           => $contact_page_id,
			'post_content' => $shortcode,
		)
	);

	$report['contact_form'] = __( 'Connected the "Project Inquiry" Contact Form 7 form to the Contact page.', 'the-ebook-edit' );
}

/**
 * Moves WordPress's default "Sample Page" to Trash, but only when it is
 * clearly still the untouched default content. Anything an administrator has
 * edited is left alone.
 *
 * @param array $report Report array, passed by reference.
 */
function teebe_setup_maybe_trash_sample_page( &$report ) {
	$sample = get_page_by_path( 'sample-page', OBJECT, 'page' );

	if ( ! $sample || 'trash' === $sample->post_status ) {
		return;
	}

	if ( 'Sample Page' !== $sample->post_title ) {
		return;
	}

	if ( false === strpos( (string) $sample->post_content, 'This is an example page' ) ) {
		$report['warnings'][] = __( 'A page at "sample-page" exists but does not look like the untouched WordPress default, so it was left in place.', 'the-ebook-edit' );
		return;
	}

	wp_trash_post( $sample->ID );
	$report['sample_page'] = __( 'Moved the default WordPress "Sample Page" to Trash.', 'the-ebook-edit' );
}

/**
 * Creates the "Primary Navigation" menu when it does not already exist, adds
 * any of its items that are missing, and assigns it to the theme's primary
 * menu location. Existing items are matched by the page they point to, so
 * running setup again never duplicates an entry.
 *
 * @param array $ids    Page IDs keyed by slug.
 * @param array $report Report array, passed by reference.
 */
function teebe_setup_primary_menu( $ids, &$report ) {
	$menu_name = 'Primary Navigation';
	$menu      = wp_get_nav_menu_object( $menu_name );
	$created   = false;

	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );

		if ( is_wp_error( $menu_id ) ) {
			/* translators: %s: error message. */
			$report['warnings'][] = sprintf( __( 'Could not create the Primary Navigation menu: %s', 'the-ebook-edit' ), $menu_id->get_error_message() );
			return;
		}

		$menu    = wp_get_nav_menu_object( $menu_id );
		$created = true;
	}

	if ( ! $menu ) {
		return;
	}

	$items = array(
		array(
			'slug'  => 'services',
			'label' => 'Services',
		),
		array(
			'slug'  => 'process',
			'label' => 'Process',
		),
		array(
			'slug'  => 'portfolio',
			'label' => 'Portfolio',
		),
		array(
			'slug'  => 'about',
			'label' => 'About',
		),
		array(
			'slug'  => 'insights',
			'label' => 'Insights',
		),
		array(
			'slug'    => 'contact',
			'label'   => 'Start a project',
			'classes' => 'nav-cta',
		),
	);

	$existing_object_ids = array();
	$existing_items       = wp_get_nav_menu_items( $menu->term_id );

	if ( $existing_items ) {
		foreach ( $existing_items as $existing_item ) {
			$existing_object_ids[ (int) $existing_item->object_id ] = true;
		}
	}

	$added = array();

	foreach ( $items as $position => $item ) {
		$page_id = isset( $ids[ $item['slug'] ] ) ? (int) $ids[ $item['slug'] ] : 0;

		if ( ! $page_id || isset( $existing_object_ids[ $page_id ] ) ) {
			continue;
		}

		$menu_item_args = array(
			'menu-item-title'     => $item['label'],
			'menu-item-object-id' => $page_id,
			'menu-item-object'    => 'page',
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => $position + 1,
		);

		if ( ! empty( $item['classes'] ) ) {
			$menu_item_args['menu-item-classes'] = $item['classes'];
		}

		wp_update_nav_menu_item( $menu->term_id, 0, $menu_item_args );
		$added[] = $item['label'];
	}

	$locations             = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary']  = $menu->term_id;
	set_theme_mod( 'nav_menu_locations', $locations );

	if ( $created ) {
		/* translators: %d: number of menu items added. */
		$report['menu'] = sprintf( __( 'Created the "Primary Navigation" menu with %d item(s) and assigned it to the Primary Navigation location.', 'the-ebook-edit' ), count( $added ) );
	} elseif ( ! empty( $added ) ) {
		$report['menu'] = sprintf(
			/* translators: %s: comma-separated list of menu item labels. */
			__( 'Added the missing item(s) to the existing "Primary Navigation" menu: %s.', 'the-ebook-edit' ),
			implode( ', ', $added )
		);
	} else {
		$report['menu'] = __( 'The "Primary Navigation" menu already existed with all its items and was left as-is.', 'the-ebook-edit' );
	}
}

/**
 * Runs the full setup routine and returns a report of what happened.
 *
 * @return array<string, mixed>
 */
function teebe_run_setup() {
	$report = array(
		'created'      => array(),
		'existing'     => array(),
		'drafted'      => array(),
		'warnings'     => array(),
		'menu'         => '',
		'homepage'     => '',
		'contact_form' => '',
		'sample_page'  => '',
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

	teebe_setup_connect_contact_form( isset( $ids['contact'] ) ? $ids['contact'] : 0, $report );
	teebe_setup_maybe_trash_sample_page( $report );
	teebe_setup_primary_menu( $ids, $report );

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

	$report = teebe_run_setup();

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
			<?php esc_html_e( 'The Ebook Edit website content is supplied by the installed theme templates converted from the original GitHub website. This setup only creates the WordPress routing records, menus and homepage configuration required for those templates.', 'the-ebook-edit' ); ?>
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
					<?php if ( ! empty( $report['menu'] ) ) : ?>
						<li><?php echo esc_html( $report['menu'] ); ?></li>
					<?php endif; ?>
					<?php if ( ! empty( $report['contact_form'] ) ) : ?>
						<li><?php echo esc_html( $report['contact_form'] ); ?></li>
					<?php endif; ?>
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
			<?php submit_button( __( 'Set up The Ebook Edit website', 'the-ebook-edit' ) ); ?>
		</form>

		<p>
			<?php esc_html_e( 'Safe to run more than once: existing pages, menus, and settings are detected and left alone, and nothing is duplicated.', 'the-ebook-edit' ); ?>
		</p>
	</div>
	<?php
}
