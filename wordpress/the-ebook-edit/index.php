<?php
/**
 * Required fallback template.
 *
 * The designed website is made entirely of pages, so this template is only
 * reached by archive and search requests. It renders as a static book page.
 *
 * @package the-ebook-edit
 */

get_header();
?>

<div class="book-experience book-static">
  <div class="book-stage">
	<?php teebe_book_tabs(); ?>
    <div class="book-block">
      <div class="title-page">
        <h1><?php echo esc_html( wp_get_document_title() ); ?></h1>
        <div class="page-ornament" aria-hidden="true"></div>
      </div>
      <div class="prose-page">
        <div class="prose">
		<?php
		if ( have_posts() ) :
			echo '<ul>';

			while ( have_posts() ) :
				the_post();
				printf(
					'<li><a href="%1$s">%2$s</a></li>',
					esc_url( get_permalink() ),
					esc_html( get_the_title() )
				);
			endwhile;

			echo '</ul>';
		else :
			?>
          <p class="lead"><?php esc_html_e( 'Nothing has been published here yet.', 'the-ebook-edit' ); ?></p>
			<?php
		endif;
		?>
        </div>
        <div class="page-actions">
          <a class="button button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return to the Book', 'the-ebook-edit' ); ?></a>
        </div>
        <p class="micro-colophon">&copy; <span data-year></span> The Ebook Edit &middot; <a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>">Privacy</a> &middot; <a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>">Terms</a></p>
      </div>
    </div>
  </div>
  <div class="book-endcap" aria-hidden="true"></div>
</div>

<?php
get_footer();
