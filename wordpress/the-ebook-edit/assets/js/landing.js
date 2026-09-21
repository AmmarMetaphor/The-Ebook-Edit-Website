/* The Ebook Edit — Meta Ads landing page.
   ------------------------------------------------------------------
   The approved landing page's own scripts, ported for WordPress. The
   carousel (including its 2000ms autoplay) and the hash router are
   copied verbatim from the approved file. Two things changed, both
   because the approved file was a standalone prototype:

     1. the WhatsApp number is no longer a blank constant to fill in by
        hand — PHP renders the wa.me link, using the number already
        published across theebookedit.com;

     2. the lead form is not here at all. HighLevel renders it,
        validates it, stores the lead and performs the redirect, inside
        its own cross-origin frame. Nothing in this file reads into that
        frame or tries to guess when a submission succeeded.

   The header's navigation links and its mobile menu were removed from
   this page so the only paths onward are a call to action or the form,
   so the code that drove them is gone with them. The floating WhatsApp
   button is now a plain link rendered by PHP and needs no script at all.
   ------------------------------------------------------------------ */

/* Every call to action on this page is a real anchor, so navigation works
   without JavaScript, with the keyboard, and in Meta's in-app browser.
   The service cards in the grid carry no call to action of their own and
   are purely informational, so nothing here makes them clickable. */

(() => {
  const root = document.getElementById('work');
  const slides = [...root.querySelectorAll('.book-slide')];
  const dots = [...root.querySelectorAll('[data-book-index]')];
  const pause = root.querySelector('[data-book-pause]');
  const reduced = matchMedia('(prefers-reduced-motion: reduce)');
  // Autoplay waiting time between books (ms). Transition durations are set separately in show().
  const BOOK_AUTOPLAY_DELAY = 2000;
  let active = 0, busy = false, timer, paused = false, focused = false, visible = false;
  const syncTimer = () => {
    clearTimeout(timer);
    if (!paused && !focused && visible && !document.hidden && !busy)
      timer = setTimeout(() => show(active + 1, 1, false), BOOK_AUTOPLAY_DELAY);
  };
  const syncPause = () => {
    pause.textContent = paused ? 'Play' : 'Pause';
    pause.setAttribute('aria-label', (paused ? 'Start' : 'Pause') + ' automatic book transitions');
  };
  async function show(index, direction = 1, manual = true) {
    const next = (index + slides.length) % slides.length;
    if (busy || next === active) return;
    clearTimeout(timer);
    busy = true;
    const outgoing = slides[active], incoming = slides[next];
    outgoing.inert = true;
    outgoing.setAttribute('aria-hidden', 'true');
    // Finish and hide the whole outgoing slide before revealing the next one.
    const distance = reduced.matches ? 0 : Math.min(24, root.clientWidth * .03);
    const easing = 'cubic-bezier(.4,0,.2,1)';
    const animateSlide = async (slide, frames, duration) => {
      if (!slide.animate) return;
      slide.style.willChange = 'transform, opacity';
      const animation = slide.animate(frames, {duration, easing, fill:'both'});
      await animation.finished.catch(() => {});
      return animation;
    };
    const exitAnimation = await animateSlide(outgoing, [
      {transform:'translate3d(0,0,0)', opacity:1},
      {transform:`translate3d(${direction * distance}px,0,0)`, opacity:0}
    ], reduced.matches ? 200 : 500);
    outgoing.classList.remove('is-active');
    // CSS now keeps the old slide and its cover hidden, even after cancellation.
    if (exitAnimation) exitAnimation.cancel();
    outgoing.style.willChange = '';
    incoming.classList.add('is-active');
    incoming.inert = false;
    incoming.removeAttribute('aria-hidden');
    const enterAnimation = await animateSlide(incoming, [
      {transform:`translate3d(${-direction * distance}px,0,0)`, opacity:0},
      {transform:'translate3d(0,0,0)', opacity:1}
    ], reduced.matches ? 300 : 800);
    if (enterAnimation) enterAnimation.cancel();
    incoming.style.willChange = '';
    active = next;
    dots.forEach((dot, i) => dot.setAttribute('aria-current', String(i === active)));
    root.querySelector('.book-count').textContent = `${String(active + 1).padStart(2, '0')} / ${String(slides.length).padStart(2, '0')}`;
    if (manual) root.querySelector('.book-sr-status').textContent = incoming.getAttribute('aria-label');
    busy = false;
    syncTimer();
  }
  root.querySelector('[data-book-next]').addEventListener('click', () => show(active + 1));
  root.querySelector('[data-book-prev]').addEventListener('click', () => show(active - 1, -1));
  dots.forEach((dot,i) => dot.addEventListener('click', () => show(i)));
  pause.addEventListener('click', () => {paused = !paused;if (!paused) focused = false;syncPause();syncTimer();});
  // Keyboard users can read and navigate without a slide moving focus away.
  let keyboardNavigation = false;
  document.addEventListener('keydown', () => {keyboardNavigation = true;});
  document.addEventListener('pointerdown', () => {keyboardNavigation = false;focused = false;syncTimer();});
  root.addEventListener('focusin', () => {focused = keyboardNavigation;syncTimer();});
  root.addEventListener('focusout', () => {setTimeout(() => {focused = keyboardNavigation && root.contains(document.activeElement);syncTimer();},0);});
  root.querySelector('.book-controls').addEventListener('keydown', e => {
    if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {e.preventDefault();show(active + (e.key === 'ArrowRight' ? 1 : -1),e.key === 'ArrowRight' ? 1 : -1);}
  });
  document.addEventListener('visibilitychange', syncTimer);
  reduced.addEventListener('change', syncTimer);
  new IntersectionObserver(entries => {visible = entries[0].isIntersecting;syncTimer();}, {threshold:.15}).observe(root);
  // Decode every cover before its first transition to avoid an image-loading hitch.
  root.querySelectorAll('.featured-book').forEach(img => {if (img.decode) img.decode().catch(() => {});});
  syncPause();
})();

// ========= HASH ROUTER: single-document views =========
// #home (and every existing landing anchor) -> landing view; #about-us / #privacy-policy / #terms-and-conditions -> internal views.
(() => {
  const ROUTES = {
    'about-us': {view: 'about-us-view', title: 'About The Ebook Edit | Writing, Editing & Publishing Support'},
    'privacy-policy': {view: 'privacy-policy-view', title: 'Privacy Policy | The Ebook Edit'},
    'terms-and-conditions': {view: 'terms-and-conditions-view', title: 'Terms & Conditions | The Ebook Edit'}
  };
  const HOME_TITLE = document.title;
  const views = [...document.querySelectorAll('.page-view')];
  const landing = document.getElementById('landing-view');
  const reduced = matchMedia('(prefers-reduced-motion: reduce)');
  let current = null;

  function activate(view) {
    views.forEach(v => { const on = v === view; v.hidden = !on; v.classList.toggle('is-active', on); });
  }

  function renderRoute() {
    const hash = decodeURIComponent(location.hash.replace(/^#/, ''));
    const route = ROUTES[hash] ? hash : 'home';
    const view = ROUTES[hash] ? document.getElementById(ROUTES[hash].view) : landing;
    const switched = route !== current;
    if (switched) {
      activate(view);
      document.title = ROUTES[hash] ? ROUTES[hash].title : HOME_TITLE;
      current = route;
    }
    if (route === 'home') {
      // Existing landing anchors (#services, #work, #process, #contact, #home) keep working from any view.
      const target = hash && hash !== 'home' ? document.getElementById(hash) : null;
      if (switched) window.scrollTo({top: 0, behavior: 'instant'}); // arrive at the top of the landing page first
      if (target) {
        requestAnimationFrame(() => target.scrollIntoView({behavior: reduced.matches ? 'auto' : 'smooth', block: 'start'}));
      }
    } else {
      // Instant: the page's CSS scroll-behavior would otherwise animate the jump to the top of the new view.
      window.scrollTo({top: 0, behavior: 'instant'});
      const heading = view.querySelector('.ip-title');
      if (heading && switched) heading.focus({preventScroll: true});
    }
  }

  window.addEventListener('hashchange', renderRoute);
  renderRoute();
})();
