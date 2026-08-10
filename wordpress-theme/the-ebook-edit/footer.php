<?php
/**
 * Site footer.
 *
 * @package the-ebook-edit
 */

?>
</main>
<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/the-ebook-edit-logo.png' ) ); ?>" alt="The Ebook Edit">
        <p>Thoughtful ebook writing, editing, formatting, and publishing support—shaped around your voice and your reader.</p>
        <p class="footer-contact">Email <a href="mailto:info@theebookedit.com">info@theebookedit.com</a> · UK and US · We aim to reply within 24 hours</p>
      </div>
      <div>
        <h2 class="footer-title">Services</h2>
        <div class="footer-links">
          <a href="<?php echo esc_url( home_url( '/writing/' ) ); ?>">Ebook writing</a>
          <a href="<?php echo esc_url( home_url( '/editing/' ) ); ?>">Editing</a>
          <a href="<?php echo esc_url( home_url( '/publishing/' ) ); ?>">Publishing support</a>
          <a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">All services</a>
        </div>
      </div>
      <div>
        <h2 class="footer-title">Explore</h2>
        <div class="footer-links">
          <a href="<?php echo esc_url( home_url( '/process/' ) ); ?>">Our process</a>
          <a href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>">Portfolio</a>
          <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a>
          <a href="<?php echo esc_url( home_url( '/insights/' ) ); ?>">Insights</a>
        </div>
      </div>
      <div>
        <h2 class="footer-title">Start here</h2>
        <p>Tell us about your idea, manuscript, timeline, and publishing goals.</p>
        <a class="button button-gold" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Get in touch</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© <span data-year></span> The Ebook Edit. All rights reserved.</span>
      <span><a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>">Privacy</a> · <a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>">Terms</a></span>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
