<?php
/**
 * Required fallback template.
 *
 * The website is made entirely of pages, so this template is only reached
 * by archive and search requests. It renders in the website's shell.
 *
 * @package the-ebook-edit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="site-view">
  <section class="page-hero">
    <div class="flow-wrap" aria-hidden="true"><div class="flow-line one"></div><div class="flow-line two"></div></div>
    <div class="container" style="position:relative;z-index:1">
      <div class="v-reveal">
        <h1 class="v-h1" tabindex="-1"><?php echo esc_html( wp_get_document_title() ); ?></h1>
      </div>
    </div>
  </section>
  <section class="v-section tight">
    <div class="container">
      <div class="legal v-reveal">
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
        <p><?php esc_html_e( 'Nothing has been published here yet.', 'the-ebook-edit' ); ?></p>
			<?php
		endif;
		?>
      </div>
      <div class="actions v-reveal">
        <a class="btn btn-gold" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to homepage', 'the-ebook-edit' ); ?></a>
      </div>
    </div>
  </section>
</section>

<?php
get_footer();
