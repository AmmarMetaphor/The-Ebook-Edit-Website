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

     3. the lead forms no longer fake their own success. Contact Form 7
        performs the submission, and only when the plugin confirms the
        mail was actually sent is the lead recorded and the visitor
        moved on to the thank-you page. The approved field-by-field
        validation is unchanged and still runs in the browser first, so
        the visitor sees the same messages in the same places.

   The WhatsApp links are real wa.me links rendered by PHP, so the
   prototype's placeholder toast is gone with the placeholder number.

   inc/site.php supplies window.teebeSite.
   ------------------------------------------------------------------ */

/* ========= configuration supplied by the theme ========= */
var TEEBE_SITE = window.teebeSite || {};

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

/* ========= lead forms: six required fields, readable validation =========
   Contact Form 7 delivers the submission. This keeps the approved in-page
   validation, records the lead only once the plugin reports that the mail
   was really sent, and only then moves the visitor on to the thank-you
   page. An invalid, spam, aborted or failed submission never gets past
   this point. */
(() => {
  const FIELDS = [
    {name:"full_name", label:"Name", message:"Please enter your full name.",
     valid: v => v.trim().length >= 2},
    {name:"email", label:"Email", message:"Please enter a valid email address.",
     valid: (v, el) => v.trim() !== "" && el.checkValidity()},
    {name:"mobile_whatsapp", label:"Mobile / WhatsApp", message:"Please enter your Mobile / WhatsApp number.",
     // International-friendly: allows + spaces ( ) - . and requires 7-15 digits.
     valid: v => /^[+()\s.\-\d]+$/.test(v.trim()) && (v.match(/\d/g) || []).length >= 7 && (v.match(/\d/g) || []).length <= 15},
    {name:"book_type", label:"Book Type", message:"Please select the type of book you want to create.",
     valid: v => v !== ""},
    {name:"book_stage", label:"Book Stage", message:"Please select how far along you are with your book.",
     valid: v => v !== ""},
    {name:"expected_budget", label:"Expected Budget", message:"Please select your expected budget.",
     valid: v => v !== ""}
  ];

  // assets/js/analytics.js owns every measurement call. If it is missing,
  // blocked, or refused by a consent manager, the form still submits and
  // the visitor still moves on.
  const trackLead = (params, done) => {
    if (typeof window.teebeTrackLead === "function") window.teebeTrackLead(params, done);
    else if (typeof done === "function") done();
  };

  // Contact Form 7 may not be configured yet, in which case the template
  // renders a notice instead of a form and there is nothing to wire up.
  document.querySelectorAll("form.lead-form").forEach(form => {
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
    const errorId = def => (form.elements[def.name].id ? "err-" + form.elements[def.name].id : "err-" + def.name);
    present.forEach(def => {
      const el = form.elements[def.name];
      el.required = true;
      el.setAttribute("aria-describedby", errorId(def));
      if (el.tagName === "SELECT") {
        const placeholder = el.querySelector('option[value=""]');
        if (placeholder) placeholder.disabled = true;
      }
      if (def.name === "mobile_whatsapp") el.setAttribute("inputmode", "tel");
      el.addEventListener("input", () => { if (wrap(el) && wrap(el).classList.contains("is-invalid") && def.valid(el.value, el)) setError(el, ""); });
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
       these outcomes records a lead and none of them leaves the page. */
    ["wpcf7invalid", "wpcf7spam", "wpcf7mailfailed"].forEach(type => {
      form.addEventListener(type, clearErrors);
    });

    /* Delivered — and only delivered. wpcf7mailsent fires once Contact
       Form 7 has actually sent the mail, so this is the one place a lead
       is recorded: Google Analytics generate_lead, then the Meta Pixel's
       Lead, then the redirect. Both events carry nothing personal — only
       which form it was and which page it was on.

       The redirect waits for the events to be queued, so the conversion is
       never lost to the navigation, and never waits longer than the
       timeout in analytics.js for it. */
    form.addEventListener("wpcf7mailsent", () => {
      const next = TEEBE_SITE.thankYouUrl;
      const go = () => { if (next) window.location.assign(next); };
      trackLead({
        form_name: (window.teebeAnalytics && window.teebeAnalytics.forms && window.teebeAnalytics.forms[form.id]) || form.id || "lead_form",
        lead_origin: (window.teebeAnalytics && window.teebeAnalytics.leadOrigin) || "website"
      }, go);
    });
  });
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
