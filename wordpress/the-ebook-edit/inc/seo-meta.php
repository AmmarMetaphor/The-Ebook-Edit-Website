<?php
/**
 * Page metadata carried over from the static site.
 *
 * Titles and descriptions are the original wording. Every URL is derived from
 * home_url(), so changing the WordPress site address updates all canonical,
 * Open Graph, and JSON-LD URLs without editing templates.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Metadata for every page carried over from the static site, keyed by page slug
 * ('front' for the homepage, '404' for the not-found template).
 *
 * @return array<string, array<string, mixed>>
 */
function teebe_seo_map() {
	return array(
		'front'                           => array(
			'title'       => 'The Ebook Edit | Ebook Writing, Editing & Publishing Services',
			'description' => 'Professional ebook writing, editing, formatting, and publishing support for authors, experts, founders, and organizations.',
			'path'        => '/',
		),
		'services'                        => array(
			'title'       => 'Ebook Services | The Ebook Edit',
			'description' => 'Explore ebook writing, editing, formatting, conversion, and publishing support tailored to your manuscript.',
			'path'        => '/services/',
		),
		'writing'                         => array(
			'title'       => 'Ebook Writing & Ghostwriting | The Ebook Edit',
			'description' => 'Turn your expertise, notes, interviews, or rough draft into a clear, engaging ebook through ghostwriting or collaborative writing.',
			'path'        => '/writing/',
		),
		'editing'                         => array(
			'title'       => 'Ebook Editing Services | The Ebook Edit',
			'description' => 'Developmental editing, line editing, copy editing, and proofreading to strengthen your ebook while preserving your voice.',
			'path'        => '/editing/',
		),
		'publishing'                      => array(
			'title'       => 'Ebook Formatting & Kindle Publishing Support | The Ebook Edit',
			'description' => 'Prepare EPUB and Kindle-ready files, metadata, and upload checklists for Amazon KDP, Apple Books, Kobo, Google Play Books, Barnes & Noble Press, and Draft2Digital.',
			'path'        => '/publishing/',
		),
		'process'                         => array(
			'title'       => 'Our Ebook Production Process | The Ebook Edit',
			'description' => 'See the structured process used to plan, write, edit, format, and prepare an ebook for publication.',
			'path'        => '/process/',
		),
		'portfolio'                       => array(
			'title'       => 'Ebook Portfolio | The Ebook Edit',
			'description' => 'Representative ebook project types showing how writing, editing, formatting, and publishing support can be shaped around different books.',
			'path'        => '/portfolio/',
		),
		'about'                           => array(
			'title'       => 'About The Ebook Edit',
			'description' => 'Learn about The Ebook Edit, a boutique brand for thoughtful ebook writing, editing, formatting, and publishing support.',
			'path'        => '/about/',
		),
		'insights'                        => array(
			'title'       => 'Ebook Writing & Publishing Insights | The Ebook Edit',
			'description' => 'Practical articles about ebook writing, editing levels, formatting, Kindle/KDP publishing preparation, and author workflows.',
			'path'        => '/insights/',
		),
		'contact'                         => array(
			'title'       => 'Start an Ebook Project | The Ebook Edit',
			'description' => 'Tell The Ebook Edit about your manuscript, writing idea, editing needs, publishing goals, and target timeline.',
			'path'        => '/contact/',
		),
		'privacy'                         => array(
			'title'       => 'Privacy Policy | The Ebook Edit',
			'description' => 'Privacy policy starter for The Ebook Edit website.',
			'path'        => '/privacy/',
			'noindex'     => true,
		),
		'terms'                           => array(
			'title'       => 'Website Terms | The Ebook Edit',
			'description' => 'Website terms starter for The Ebook Edit.',
			'path'        => '/terms/',
			'noindex'     => true,
		),
		'thank-you'                       => array(
			'title'       => 'Thank You | The Ebook Edit',
			'description' => 'Thank you for contacting The Ebook Edit.',
			'path'        => '/thank-you/',
		),
		'turn-expertise-into-an-ebook'    => array(
			'title'       => 'How to Turn Your Expertise into an Ebook | The Ebook Edit',
			'description' => 'Learn how to define a reader, develop a book promise, build an outline, organize sources, and plan an ebook manuscript.',
			'path'        => '/insights/turn-expertise-into-an-ebook/',
			'og_type'     => 'article',
			'headline'    => 'How to Turn Your Expertise into an Ebook',
		),
		'editing-levels-explained'        => array(
			'title'       => 'Editing Levels Explained | The Ebook Edit',
			'description' => 'Understand developmental editing, line editing, copy editing, and proofreading—and when each stage should happen.',
			'path'        => '/insights/editing-levels-explained/',
			'og_type'     => 'article',
			'headline'    => 'Editing Levels Explained',
		),
		'pre-publishing-checklist'        => array(
			'title'       => 'Pre-Publishing Ebook Checklist | The Ebook Edit',
			'description' => 'Use this checklist to review editorial readiness, permissions, metadata, formatting, account ownership, and launch materials.',
			'path'        => '/insights/pre-publishing-checklist/',
			'og_type'     => 'article',
			'headline'    => 'Pre-Publishing Ebook Checklist',
		),
		'kindle-and-ebook-platform-guide' => array(
			'title'       => 'Publishing an Ebook on Kindle and Other Platforms | The Ebook Edit',
			'description' => 'What Amazon KDP, Apple Books, Kobo Writing Life, Google Play Books, Barnes & Noble Press, and Draft2Digital ask for, and how to prepare before you publish.',
			'path'        => '/insights/kindle-and-ebook-platform-guide/',
			'og_type'     => 'article',
			'headline'    => 'Publishing an Ebook on Kindle and Other Platforms',
		),
		'404'                             => array(
			'title'       => 'Page Not Found | The Ebook Edit',
			'description' => 'The requested page could not be found.',
			'path'        => null,
		),
	);
}

/**
 * Metadata for the page currently being rendered.
 *
 * @return array<string, mixed> Empty when the page has no carried-over metadata.
 */
function teebe_seo_entry() {
	$map = teebe_seo_map();

	if ( is_404() ) {
		return $map['404'];
	}

	if ( is_front_page() ) {
		return $map['front'];
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
 * Uses the original page title wording where one exists.
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
 * Structured data for the current page, preserving only the schema the static
 * site already published.
 *
 * @param array       $entry Metadata entry for the current page.
 * @param string|null $url   Canonical URL, or null when the page has none.
 * @return array<int, array<string, mixed>> One or more JSON-LD documents.
 */
function teebe_seo_schema( $entry, $url ) {
	if ( ! empty( $entry['headline'] ) ) {
		return array(
			array(
				'@context'  => 'https://schema.org',
				'@type'     => 'Article',
				'headline'  => $entry['headline'],
				'url'       => $url,
				'publisher' => array(
					'@type' => 'Organization',
					'name'  => 'The Ebook Edit',
				),
			),
		);
	}

	$is_publishing = isset( $entry['path'] ) && '/publishing/' === $entry['path'];

	if ( $is_publishing ) {
		$documents = array(
			array(
				'@context'    => 'https://schema.org',
				'@type'       => 'Service',
				'name'        => 'Ebook Formatting and Publishing Support',
				'url'         => $url,
				'serviceType' => 'Ebook formatting and publishing preparation',
				'provider'    => array(
					'@type' => 'Organization',
					'name'  => 'The Ebook Edit',
				),
				'areaServed'  => 'Worldwide',
				'description' => 'Formatting, metadata, and upload preparation for Amazon Kindle Direct Publishing (KDP), Apple Books, Kobo Writing Life, Google Play Books, Barnes & Noble Press, and Draft2Digital.',
			),
		);
	} else {
		$service = array(
			'@context' => 'https://schema.org',
			'@type'    => 'ProfessionalService',
			'name'     => 'The Ebook Edit',
		);

		if ( $url ) {
			$service['url'] = $url;
		}

		$service['description'] = 'Professional ebook writing, editing, formatting, and publishing support.';
		$service['areaServed']  = 'Worldwide';
		$service['serviceType'] = array( 'Ebook writing', 'Ebook editing', 'Ebook formatting', 'Publishing support' );

		$documents = array( $service );
	}

	$faqs = teebe_seo_faqs( isset( $entry['path'] ) ? $entry['path'] : '' );

	if ( $faqs ) {
		$documents[] = $faqs;
	}

	return $documents;
}

/**
 * FAQPage structured data for the two pages that publish visible FAQs.
 *
 * @param string $path Site path of the current page.
 * @return array<string, mixed>|null
 */
function teebe_seo_faqs( $path ) {
	$sets = array(
		'/contact/'    => array(
			array(
				'Can I ask for help if I only have an idea?',
				'Yes. A writing or book-development project can begin with a concept, notes, interviews, or existing content rather than a complete draft.',
			),
			array(
				'Do you guarantee sales or bestseller status?',
				"No. Editorial and publishing support can improve quality and readiness, but sales depend on many factors outside an editor's control.",
			),
			array(
				'Will you publish my ebook for me on Kindle or another platform?',
				'No. You keep control of your publishing accounts, rights, tax and payment details, and the final decision to publish. We prepare your files, metadata, and a practical plan for your chosen platform(s).',
			),
			array(
				'Will my material remain confidential?',
				'Confidentiality expectations, file handling, access, and any formal agreement should be confirmed before sensitive material is shared.',
			),
		),
		'/publishing/' => array(
			array(
				'Will The Ebook Edit publish my ebook for me on Kindle or another platform?',
				'No. You create and control your own publishing accounts, rights, tax details, and payment information, and you make the final decision to publish. We prepare your files, metadata, and a practical checklist for your chosen platform(s).',
			),
			array(
				'Do you guarantee platform approval, sales, or bestseller status?',
				"No. We prepare your manuscript and materials to a platform's stated requirements, but approval, sales, and rankings depend on factors outside an editor's control.",
			),
			array(
				'Are you affiliated with Amazon, Apple, Kobo, Google, Barnes & Noble, or Draft2Digital?',
				'No. The Ebook Edit is an independent editorial and formatting service and is not affiliated with, endorsed by, or officially connected to these companies. All product and platform names are trademarks of their respective owners.',
			),
		),
	);

	if ( ! isset( $sets[ $path ] ) ) {
		return null;
	}

	$questions = array();

	foreach ( $sets[ $path ] as $pair ) {
		$questions[] = array(
			'@type'          => 'Question',
			'name'           => $pair[0],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $pair[1],
			),
		);
	}

	return array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $questions,
	);
}

/**
 * Prints the head metadata: description, canonical, social tags, icon, JSON-LD.
 */
function teebe_head_meta() {
	$entry = teebe_seo_entry();
	$image = get_theme_file_uri( 'assets/images/the-ebook-edit-logo.png' );

	echo '<meta name="theme-color" content="#044dd5">' . "\n";

	if ( ! $entry ) {
		$canonical = wp_get_canonical_url();

		if ( $canonical ) {
			printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $canonical ) );
		}

		teebe_print_icon();

		return;
	}

	$url         = isset( $entry['path'] ) && $entry['path'] ? home_url( $entry['path'] ) : null;
	$title       = $entry['title'];
	$description = $entry['description'];
	$og_type     = isset( $entry['og_type'] ) ? $entry['og_type'] : 'website';

	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );

	if ( $url ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $url ) );
	}

	printf( '<meta property="og:type" content="%s">' . "\n", esc_attr( $og_type ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );

	if ( $url ) {
		printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	}

	printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	echo '<meta name="twitter:card" content="summary">' . "\n";
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image ) );

	teebe_print_icon();

	foreach ( teebe_seo_schema( $entry, $url ) as $document ) {
		printf(
			'<script type="application/ld+json">%s</script>' . "\n",
			wp_json_encode( $document, JSON_UNESCAPED_UNICODE )
		);
	}
}
add_action( 'wp_head', 'teebe_head_meta', 2 );

/**
 * Falls back to the bundled favicon until a Site Icon is set in the Customizer.
 */
function teebe_print_icon() {
	if ( has_site_icon() ) {
		return;
	}

	printf(
		'<link rel="icon" href="%s">' . "\n",
		esc_url( get_theme_file_uri( 'assets/images/favicon.ico' ) )
	);
}
