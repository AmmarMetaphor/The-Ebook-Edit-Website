<?php
/**
 * Insights page metadata, generated from the static site by
 * wordpress/sync-from-static.py. Do not hand-edit: change the static
 * page's <head> and re-run the script.
 *
 * The approved website's own pages are not here. Their metadata is
 * hand-maintained in inc/site.php, which replaces any entry in this
 * map for a slug the website now serves.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Metadata for the Insights pages, keyed by slug.
 *
 * @return array<string, array<string, mixed>>
 */
function teebe_seo_data() {
	return array(
		'insights' => array(
			'title' => 'Ebook Writing & Publishing Insights | The Ebook Edit',
			'description' => 'Practical articles about ebook writing, editing levels, formatting, Kindle/KDP publishing preparation, and author workflows.',
			'path' => '/insights/',
			'og_type' => 'website',
			'body_class' => 'book-home book-theme-insights',
			'cinematic' => true,
			'preload_logo' => false,
			'schema' => array( array( '@context' => 'https://schema.org', '@type' => 'ProfessionalService', 'name' => 'The Ebook Edit', 'url' => '{{home}}/insights', 'description' => 'Professional ebook writing, editing, formatting, and publishing support.', 'email' => 'support@theebookedit.com', 'areaServed' => 'Worldwide', 'serviceType' => array( 'Ebook writing', 'Ebook editing', 'Ebook formatting', 'Publishing support' ) ) ),
		),
		'turn-expertise-into-an-ebook' => array(
			'title' => 'How to Turn Your Expertise into an Ebook | The Ebook Edit',
			'description' => 'Learn how to define a reader, develop a book promise, build an outline, organise sources, and plan an ebook manuscript.',
			'path' => '/insights/turn-expertise-into-an-ebook/',
			'og_type' => 'article',
			'body_class' => 'book-home book-theme-insights',
			'cinematic' => false,
			'preload_logo' => false,
			'schema' => array( array( '@context' => 'https://schema.org', '@type' => 'Article', 'headline' => 'How to Turn Your Expertise into an Ebook', 'url' => '{{home}}/insights/turn-expertise-into-an-ebook', 'dateModified' => '2026-08-27', 'publisher' => array( '@type' => 'Organization', 'name' => 'The Ebook Edit' ) ) ),
		),
		'editing-levels-explained' => array(
			'title' => 'Editing Levels Explained | The Ebook Edit',
			'description' => 'Understand developmental editing, line editing, copy editing, and proofreading—and when each stage should happen.',
			'path' => '/insights/editing-levels-explained/',
			'og_type' => 'article',
			'body_class' => 'book-home book-theme-insights',
			'cinematic' => false,
			'preload_logo' => false,
			'schema' => array( array( '@context' => 'https://schema.org', '@type' => 'Article', 'headline' => 'Editing Levels Explained', 'url' => '{{home}}/insights/editing-levels-explained', 'dateModified' => '2026-08-27', 'publisher' => array( '@type' => 'Organization', 'name' => 'The Ebook Edit' ) ) ),
		),
		'pre-publishing-checklist' => array(
			'title' => 'Pre-Publishing Ebook Checklist | The Ebook Edit',
			'description' => 'Use this checklist to review editorial readiness, permissions, metadata, formatting, account ownership, and launch materials.',
			'path' => '/insights/pre-publishing-checklist/',
			'og_type' => 'article',
			'body_class' => 'book-home book-theme-insights',
			'cinematic' => false,
			'preload_logo' => false,
			'schema' => array( array( '@context' => 'https://schema.org', '@type' => 'Article', 'headline' => 'Pre-Publishing Ebook Checklist', 'url' => '{{home}}/insights/pre-publishing-checklist', 'dateModified' => '2026-08-27', 'publisher' => array( '@type' => 'Organization', 'name' => 'The Ebook Edit' ) ) ),
		),
		'kindle-and-ebook-platform-guide' => array(
			'title' => 'Publishing an Ebook on Kindle and Other Platforms | The Ebook Edit',
			'description' => 'What Amazon KDP, Apple Books, Kobo Writing Life, Google Play Books, Barnes & Noble Press, and Draft2Digital ask for, and how to prepare before you publish.',
			'path' => '/insights/kindle-and-ebook-platform-guide/',
			'og_type' => 'article',
			'body_class' => 'book-home book-theme-insights',
			'cinematic' => false,
			'preload_logo' => false,
			'schema' => array( array( '@context' => 'https://schema.org', '@type' => 'Article', 'headline' => 'Publishing an Ebook on Kindle and Other Platforms', 'url' => '{{home}}/insights/kindle-and-ebook-platform-guide', 'dateModified' => '2026-08-27', 'publisher' => array( '@type' => 'Organization', 'name' => 'The Ebook Edit' ) ) ),
		),
	);
}
