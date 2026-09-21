/* The Ebook Edit — the approved website.
   ------------------------------------------------------------------
   The approved design's own scripts, ported for WordPress. The book
   carousel is copied verbatim from the approved file, guard aside.
   Three things changed, all because the approved file was a
   single-document prototype:

     1. the hash router is gone. Every view is a real WordPress page at
        a real URL, so the browser does the routing, the navigation's
        current-page state is rendered by PHP, and Google Analytics
        records ordinary page views with no virtual ones to invent;

     2. the Base64 asset registry is gone. Images are theme files with
        real sources, so nothing has to be copied between elements;

     3. the lead form is gone from this file entirely. HighLevel renders
        it, validates it, stores the lead and performs the redirect, all
        inside its own cross-origin frame. Nothing here reads into that
        frame or tries to guess when a submission succeeded: the site
        learns of a lead only when HighLevel returns the visitor to
        /thank-you/?conversion=lead. See assets/js/ghl-forms.js, which
        only sizes the card around it.

   The WhatsApp links are real wa.me links rendered by PHP, so the
   prototype's placeholder toast is gone with the placeholder number.
   ------------------------------------------------------------------ */

/* ========= mobile menu ========= */
(() => {
  const menuBtn = document.getElementById("menuBtn");
  const nav = document.getElementById("nav");
  if (!menuBtn || !nav) return;
  menuBtn.addEventListener("click", () => {
    const open = nav.classList.toggle("open");
    menuBtn.setAttribute("aria-expanded", String(open));
  });
  nav.querySelectorAll("a").forEach(a => a.addEventListener("click", () => nav.classList.remove("open")));
})();

/* ========= the home page's book carousel ========= */
(() => {
  const root = document.getElementById('home-work-preview');
  // Only the homepage carries the carousel now that every view is a real page.
  if (!root) return;
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

/* ========= reveals, portfolio filters, process illumination and the
   Contact form's service context ========= */
(() => {
  const $ = (q, r = document) => r.querySelector(q);
  const $$ = (q, r = document) => [...r.querySelectorAll(q)];
  const reduced = matchMedia('(prefers-reduced-motion: reduce)');

  /* ---- Reveals ---- */
  const items = $$('.v-reveal, .v-reveal-cover');
  if (items.length) {
    if (reduced.matches || !('IntersectionObserver' in window)) {
      items.forEach(i => i.classList.add('is-in'));
    } else {
      const io = new IntersectionObserver(entries => entries.forEach(en => {
        if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
      }), { threshold: .12, rootMargin: '0px 0px -6% 0px' });
      items.forEach(i => io.observe(i));
    }
  }

  /* ---- Contact form service context ----
     The value itself is rendered by PHP from a sanitised ?service=
     parameter; this only lets the visitor take it off again. */
  const clearBtn = document.getElementById('serviceContextClear');
  if (clearBtn) clearBtn.addEventListener('click', () => {
    const wrap = document.getElementById('serviceContext');
    const out = document.getElementById('serviceContextName');
    const hidden = document.getElementById('service_interest');
    if (wrap) wrap.hidden = true;
    if (out) out.textContent = '';
    if (hidden) hidden.value = '';
    try {
      const url = new URL(window.location.href);
      url.searchParams.delete('service');
      history.replaceState(null, '', url.pathname + url.search + url.hash);
    } catch (e) { /* The chip is gone either way. */ }
  });

  /* ---- Portfolio filters ---- */
  const filters = $$('.filters button'), projects = $$('.project');
  filters.forEach(b => b.addEventListener('click', () => {
    filters.forEach(x => x.setAttribute('aria-pressed', String(x === b)));
    const f = b.dataset.filter; projects.forEach(p => { p.hidden = !(f === 'all' || (p.dataset.tags || '').split(' ').includes(f)); });
    const st = document.getElementById('filterStatus'); if (st) st.textContent = `${projects.filter(p => !p.hidden).length} of ${projects.length} books shown`;
  }));

  /* ---- Process illumination ---- */
  const steps = $$('.tl-item'), progress = $('.timeline-progress'), timeline = $('.timeline');
  if (steps.length || timeline) {
    const light = () => {
      steps.forEach(st => { if (st.getBoundingClientRect().top < innerHeight * .8) st.classList.add('is-lit'); });
      if (progress && timeline) { const r = timeline.getBoundingClientRect(); progress.style.height = (Math.min(1, Math.max(0, (innerHeight * .6 - r.top) / r.height)) * 100) + '%'; }
    };
    addEventListener('scroll', light, { passive: true });
    light();
  }
})();
