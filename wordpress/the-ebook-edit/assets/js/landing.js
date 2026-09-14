/* The Ebook Edit — Meta Ads landing page.
   ------------------------------------------------------------------
   The approved landing page's own scripts, ported for WordPress. The
   carousel (including its 2000ms autoplay) and the hash router are
   copied verbatim from the approved file. Two things changed, both
   because the approved file was a standalone prototype:

     1. the WhatsApp number is no longer a blank constant to fill in by
        hand — it comes from PHP in window.teebeLanding, defaulting to
        the number already published across theebookedit.com;

     2. the lead form no longer fakes its own success. Contact Form 7
        performs the submission, and only when the plugin confirms the
        mail was actually sent does the visitor move on to the thank-you
        page where the consultation is booked. The approved
        field-by-field validation is unchanged and still runs in the
        browser first, so the visitor sees exactly the same messages in
        exactly the same places.

   The header's navigation links and its mobile menu were removed from
   this page so the only paths onward are a call to action or the form,
   so the code that drove them is gone with them. The floating WhatsApp
   button is now a plain link rendered by PHP and needs no script at all.
   ------------------------------------------------------------------ */

/* ========= configuration supplied by the theme ========= */
var TEEBE_LANDING = window.teebeLanding || {};

/* Every call to action on this page is a real anchor, so navigation works
   without JavaScript, with the keyboard, and in Meta's in-app browser.
   The service cards in the grid carry no call to action of their own and
   are purely informational, so nothing here makes them clickable. */

// Lead form: six required fields with readable validation messages.
// Contact Form 7 delivers the submission; this keeps the approved
// in-page validation and, once the plugin reports that the mail was
// really sent, moves the visitor on to the thank-you page.
(() => {
  const form = document.getElementById("leadForm");
  // Contact Form 7 is not configured yet: the template renders a notice
  // instead of the form, and there is nothing to wire up.
  if (!form) return;

  const FIELDS = [
    {name:"full_name", label:"Name", message:"Please enter your full name.",
     valid: v => v.trim().length >= 2},
    {name:"email", label:"Email", message:"Please enter a valid email address.",
     valid: (v, el) => v.trim() !== "" && el.checkValidity()},
    {name:"mobile_whatsapp", label:"Mobile / WhatsApp", message:"Please enter your Mobile / WhatsApp number.",
     // International-friendly: allows + spaces ( ) - . and requires 7–15 digits.
     valid: v => /^[+()\s.\-\d]+$/.test(v.trim()) && (v.match(/\d/g) || []).length >= 7 && (v.match(/\d/g) || []).length <= 15},
    {name:"book_type", label:"Book Type", message:"Please select the type of book you want to create.",
     valid: v => v !== ""},
    {name:"book_stage", label:"Book Stage", message:"Please select how far along you are with your book.",
     valid: v => v !== ""},
    {name:"expected_budget", label:"Expected Budget", message:"Please select your expected budget.",
     valid: v => v !== ""}
  ];
  const present = FIELDS.filter(def => form.elements[def.name]);
  if (!present.length) return;

  const wrap = el => el.closest(".field");
  const setError = (el, msg) => {
    const f = wrap(el);
    if (!f) return;
    const out = f.querySelector(".field-error");
    f.classList.toggle("is-invalid", !!msg);
    el.setAttribute("aria-invalid", msg ? "true" : "false");
    if (out) out.textContent = msg || "";
  };
  const clearErrors = () => present.forEach(def => setError(form.elements[def.name], ""));

  /* Contact Form 7 builds the controls from the form body bundled with
     the theme, which cannot carry arbitrary attributes. These are the
     attributes the approved markup had; restoring them here keeps both
     the behaviour and the approved styling (the greyed placeholder
     comes from a :required:invalid rule) exactly as approved.

     noValidate is set for the same reason the approved form carried it:
     the browser's own bubbles would otherwise pre-empt the page's own
     validation messages. */
  form.noValidate = true;
  present.forEach(def => {
    const el = form.elements[def.name];
    el.required = true;
    el.setAttribute("aria-describedby", "err-" + def.name);
    if (el.tagName === "SELECT") {
      const placeholder = el.querySelector('option[value=""]');
      if (placeholder) placeholder.disabled = true;
    }
    if (def.name === "mobile_whatsapp") el.setAttribute("inputmode", "tel");
    el.addEventListener("input", () => { if (wrap(el).classList.contains("is-invalid") && def.valid(el.value, el)) setError(el, ""); });
    el.addEventListener("change", () => { if (def.valid(el.value, el)) setError(el, ""); });
  });

  /* Capture phase, so this runs before Contact Form 7's own submit
     handler on the same element regardless of which was registered
     first. An invalid form is stopped here and never reaches the
     plugin. */
  form.addEventListener("submit", e => {
    let firstInvalid = null;
    present.forEach(def => {
      const el = form.elements[def.name];
      const ok = def.valid(el.value, el);
      setError(el, ok ? "" : def.message);
      if (!ok && !firstInvalid) firstInvalid = el;
    });
    if (firstInvalid) {
      e.preventDefault();
      e.stopImmediatePropagation();
      firstInvalid.focus();
    }
  }, true);

  /* The server rejected something the browser accepted. Its messages are
     printed by the plugin next to each control, so the page's own copies
     are cleared to avoid showing two messages for one field. None of
     these outcomes leaves the landing page. */
  ["wpcf7invalid", "wpcf7spam", "wpcf7mailfailed"].forEach(type => {
    form.addEventListener(type, clearErrors);
  });

  /* Delivered — and only delivered. wpcf7mailsent fires once Contact
     Form 7 has actually sent the mail; wpcf7submit, wpcf7invalid,
     wpcf7spam and wpcf7mailfailed do not reach this handler, so a
     rejected, failed or abandoned submission never leaves the page.

     The listener is bound to this form element, so no other Contact
     Form 7 form on the website can trigger the redirect.

     The URL comes from the theme (home_url('/thank-you/') by default);
     if it is ever missing, the visitor stays here and Contact Form 7's
     own confirmation is shown rather than being sent nowhere. */
  form.addEventListener("wpcf7mailsent", () => {
    const next = TEEBE_LANDING.thankYouUrl;
    if (next) window.location.assign(next);
  });
})();

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
