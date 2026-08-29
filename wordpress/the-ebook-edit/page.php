<?php
/**
 * Fallback template for pages an administrator adds after installation.
 *
 * Every page of the designed website has its own page-{slug}.php template.
 * Anything new is rendered as a static book page — the same presentation the
 * legal pages use — so it still reads as part of the book.
 *
 * @package the-ebook-edit
 */

get_header();
?>

<div class="book-experience book-static">
  <div class="book-stage">
	<?php teebe_book_tabs(); ?>
    <div class="book-block">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
      <div class="title-page">
        <h1><?php the_title(); ?></h1>
        <div class="page-ornament" aria-hidden="true"></div>
      </div>
      <div class="prose-page">
        <div class="prose"><?php the_content(); ?></div>
        <p class="micro-colophon">&copy; <span data-year></span> The Ebook Edit &middot; <a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>">Privacy</a> &middot; <a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>">Terms</a></p>
      </div>
			<?php
		endwhile;
		?>
    </div>
  </div>
  <div class="book-endcap" aria-hidden="true"></div>
</div>

<?php
get_footer();
