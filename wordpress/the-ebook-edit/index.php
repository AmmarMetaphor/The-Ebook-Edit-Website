<?php
/**
 * Required fallback template.
 *
 * @package the-ebook-edit
 */

get_header();
?>

<section class="page-hero"><div class="container"><h1><?php echo esc_html( wp_get_document_title() ); ?></h1></div></section>
<section class="section"><div class="container prose">
<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		?>
	<article class="card reveal">
		<h2 class="article-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php the_excerpt(); ?>
	</article>
		<?php
	}
} else {
	?>
	<p>Nothing has been published here yet.</p>
	<?php
}
?>
</div></section>

<?php
get_footer();
