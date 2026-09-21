<?php
/**
 * Closes the <main> landmark and prints the website footer and the floating
 * WhatsApp button, shared by every page of the approved website design.
 *
 * @package the-ebook-edit
 */

$teebe_whatsapp = teebe_site_whatsapp_url();
?>
</main>

<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand"><img data-asset="logo" src="<?php echo esc_url( teebe_site_image( 'logo' ) ); ?>" alt="The Ebook Edit"><p>Writing, editing, design and publishing support for books at every stage.</p><p style="margin-top:12px"><a href="mailto:support@theebookedit.com">support@theebookedit.com</a></p>
        <div class="social" aria-label="Social media">
          <a class="social-link" href="https://web.facebook.com/profile.php?id=61593659892347" target="_blank" rel="noopener noreferrer" aria-label="Follow The Ebook Edit on Facebook (opens in a new tab)"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 8h3V4h-3c-2.8 0-4 1.7-4 4v2H8v4h2v6h4v-6h3l1-4h-4V8.5c0-.3.2-.5.5-.5Z" fill="currentColor"/></svg></a>
          <a class="social-link" href="https://www.instagram.com/ebookedit8" target="_blank" rel="noopener noreferrer" aria-label="Follow The Ebook Edit on Instagram (opens in a new tab)"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1.2" fill="currentColor"/></svg></a>
        </div></div>
      <div class="footer-col"><h4>Explore</h4><ul><li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Services</a></li><li><a href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>">Portfolio</a></li><li><a href="<?php echo esc_url( home_url( '/process/' ) ); ?>">Process</a></li><li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li><li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></li></ul></div>
      <div class="footer-col"><h4>Services</h4><ul><li><a href="<?php echo esc_url( home_url( '/writing/' ) ); ?>">Book Writing</a></li><li><a href="<?php echo esc_url( home_url( '/editing/' ) ); ?>">Book Editing</a></li><li><a href="<?php echo esc_url( home_url( '/publishing/' ) ); ?>">Book Publishing</a></li><li><a href="<?php echo esc_url( home_url( '/services/' ) . '#detail-cover-design' ); ?>">Book Cover Design</a></li><li><a href="<?php echo esc_url( home_url( '/services/' ) . '#detail-illustrations' ); ?>">Book Illustrations</a></li><li><a href="<?php echo esc_url( home_url( '/portfolio/' ) . '#pf-mila-and-the-gentle-dino' ); ?>">Children's Books</a></li><li><a href="<?php echo esc_url( home_url( '/services/' ) . '#detail-formatting' ); ?>">Book Formatting</a></li><li><a href="<?php echo esc_url( home_url( '/services/' ) . '#detail-marketing' ); ?>">Book Marketing</a></li></ul></div>
      <div class="footer-col"><h4>Contact &amp; Legal</h4><ul><li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact Us</a></li><li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a></li><li><a href="<?php echo esc_url( home_url( '/terms-and-conditions/' ) ); ?>">Terms &amp; Conditions</a></li></ul></div>
    </div>
    <div class="footer-bottom"><p>The Ebook Edit is an independent service provider. Publishing platform names and logos are trademarks of their respective owners; their appearance does not imply affiliation, partnership or endorsement.</p><p>© The Ebook Edit. All rights reserved.</p></div>
  </div>
</footer>

<?php if ( '' !== $teebe_whatsapp ) : ?>
<a class="whatsapp" id="whatsapp" href="<?php echo esc_url( $teebe_whatsapp ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Chat with The Ebook Edit on WhatsApp', 'the-ebook-edit' ); ?>">
  <svg viewBox="0 0 32 32" fill="currentColor" aria-hidden="true"><path d="M16.1 3.2A12.6 12.6 0 0 0 5.4 22.4L3.7 28.8l6.6-1.7A12.6 12.6 0 1 0 16.1 3.2Zm0 22.9c-1.8 0-3.5-.5-5-1.3l-.4-.2-3.9 1 1-3.8-.3-.4A10.3 10.3 0 1 1 16.1 26Zm5.6-7.7c-.3-.2-1.8-.9-2.1-1-.3-.1-.5-.2-.7.2-.2.3-.8 1-.9 1.2-.2.2-.3.2-.7.1-1.8-.9-3-1.6-4.2-3.7-.3-.5.3-.5.9-1.6.1-.2 0-.4 0-.6l-1-2.4c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.2 1.2-1.2 2.9s1.3 3.4 1.4 3.6c.2.2 2.5 3.8 6 5.3 2.3 1 3.2 1.1 4.3.9.7-.1 1.8-.8 2.1-1.5.3-.7.3-1.3.2-1.5-.1-.2-.4-.3-.7-.4Z"/></svg>
  <span class="whatsapp-tip"><?php esc_html_e( 'Chat on WhatsApp', 'the-ebook-edit' ); ?></span>
</a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
