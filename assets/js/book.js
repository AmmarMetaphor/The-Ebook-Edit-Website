/* The Ebook Edit — shared interactive book engine.
   One engine serves four kinds of presentation:
   1. Desktop cinematic (>=1000x640): closed cover / open spreads, horizontal
      page turns — the approved experience, unchanged.
   2. Mobile portrait book (html.book-mbook, <=900px wide and >=500px tall):
      one portrait page at a time in a sticky stage; scroll scrubs vertical
      page turns (the current page lifts from its lower edge and turns upward,
      revealing the next page beneath). Same no-hijack philosophy as desktop:
      native scrolling is the only input.
   3. Flow: the readable stacked-paper column — the reduced-motion, no-JS and
      odd-viewport fallback for the volumes.
   4. Static book pages (.book-static: articles, contact, legal, notes):
      always flow — long-form text and forms scroll normally.
   Progressive enhancement over the flow layout in book.css: transform/opacity
   only, one rAF-gated scroll handler, no scroll hijacking. */
(() => {
  'use strict';

  const doc = document;
  const root = doc.documentElement;

  if (window.__bookBoot) {
    clearTimeout(window.__bookBoot);
    window.__bookBoot = null;
  }

  doc.querySelectorAll('[data-year]').forEach(el => {
    el.textContent = new Date().getFullYear();
  });

  /* Global floating WhatsApp contact button.
     Injected once from this shared script so every page carries exactly one
     button anchored to the viewport (never to the book or its page turns).
     A plain link only — WhatsApp is contacted solely when the visitor clicks. */
  (() => {
    const WA_NUMBER = '447348954631'; // Inovantage WhatsApp, digits only
    const WA_MESSAGE = 'Hello The Ebook Edit, I would like to discuss an ebook project.';
    if (doc.querySelector('.wa-float')) return;
    const link = doc.createElement('a');
    link.className = 'wa-float';
    link.href = 'https://wa.me/' + WA_NUMBER + '?text=' + encodeURIComponent(WA_MESSAGE);
    link.target = '_blank';
    link.rel = 'noopener noreferrer';
    link.setAttribute('aria-label', 'Chat with The Ebook Edit on WhatsApp');
    link.innerHTML =
      '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="currentColor">' +
      '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>' +
      '</svg>' +
      '<span class="wa-tip" aria-hidden="true">Chat with us on WhatsApp</span>';
    doc.body.appendChild(link);
  })();

  const experience = doc.querySelector('.book-experience');
  if (!experience) return;

  const mqMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const mqStage = window.matchMedia('(min-width: 1000px) and (min-height: 640px)');
  const mqMobile = window.matchMedia('(max-width: 900px) and (min-height: 500px)');

  const tabs = experience.querySelector('.book-tabs');
  const cover = experience.querySelector('.book-cover');
  const spreads = Array.from(experience.querySelectorAll('.spread'));
  const isStatic = experience.classList.contains('book-static');

  const clamp = (v, a, b) => Math.min(b, Math.max(a, v));
  const easeInOut = t => (t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2);
  const onMq = (mq, fn) => {
    if (typeof mq.addEventListener === 'function') mq.addEventListener('change', fn);
    else if (typeof mq.addListener === 'function') mq.addListener(fn);
  };

  /* --------------------------------------------------------- flow mode */
  let pageObserver = null;
  let coverObserver = null;

  function flowInit() {
    const motionOK = !mqMotion.matches;
    if (motionOK && 'IntersectionObserver' in window) {
      pageObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('fx-on');
            pageObserver.unobserve(entry.target);
          }
        });
      }, { threshold: 0.12 });
      experience.querySelectorAll('.spread .page').forEach(page => {
        page.classList.add('fx');
        pageObserver.observe(page);
      });

      if (tabs && cover) {
        coverObserver = new IntersectionObserver(entries => {
          entries.forEach(entry => {
            tabs.classList.toggle('tabs-on', entry.intersectionRatio < 0.3);
          });
        }, { threshold: [0, 0.3, 0.6] });
        coverObserver.observe(cover);
      } else if (tabs) {
        tabs.classList.add('tabs-on');
      }
    } else if (tabs) {
      // Reduced motion or no IntersectionObserver: static book, nav available.
      tabs.classList.add('tabs-on');
    }
  }

  function flowTeardown() {
    if (pageObserver) { pageObserver.disconnect(); pageObserver = null; }
    if (coverObserver) { coverObserver.disconnect(); coverObserver = null; }
    experience.querySelectorAll('.spread .page').forEach(page => {
      page.classList.remove('fx', 'fx-on');
    });
  }

  // Static book pages need nothing beyond the flow niceties.
  if (isStatic) {
    flowInit();
    return;
  }

  /* --------------------------------------------------- cinematic engine */
  const scene = experience.querySelector('.book-scene');
  const book = experience.querySelector('.book');
  const coverShade = experience.querySelector('.cover-shade');
  const boardClosed = experience.querySelector('.book-board-closed');
  const boardOpen = experience.querySelector('.book-board-open');
  const paperLeft = experience.querySelector('.paper-left');
  const leaf = experience.querySelector('.book-leaf');
  const turnShade = experience.querySelector('.turn-shade');
  const ribbon = experience.querySelector('.book-ribbon');
  const stage = experience.querySelector('.book-stage');

  if (!scene || !book || !spreads.length) {
    flowInit();
    return;
  }

  const hasCover = !!cover;

  // '' hidden · 'sp-right' right page only · 'sp-left' left page only · 'sp-open' both
  function setSpreadState(el, state) {
    if (el._bhState === state) return;
    el._bhState = state;
    el.classList.remove('sp-left', 'sp-right', 'sp-open');
    if (state) el.classList.add(state);
  }
  const hideOtherSpreads = (...keep) => {
    spreads.forEach(sp => { if (!keep.includes(sp)) setSpreadState(sp, ''); });
  };

  const HOLD = 0.3;        // pause on the closed cover (homepage)
  const OPEN = 1.35;       // cover opening (homepage)
  const DWELL = 0.9;       // reading time per spread
  const DWELL_FIRST = 1.0; // opening spread of a coverless volume
  const DWELL_END = 1.1;
  const TURN = 0.75;       // page turn
  let segs = [];
  let totalU = 0;

  function buildSegs() {
    segs = [];
    let u = 0;
    const push = (type, len, i) => { segs.push({ type, i, a: u, b: u + len }); u += len; };
    if (hasCover) {
      push('hold', HOLD);
      push('open', OPEN);
    }
    spreads.forEach((sp, i) => {
      const len = i === spreads.length - 1 ? DWELL_END
        : (i === 0 && !hasCover ? DWELL_FIRST : DWELL);
      push('dwell', len, i);
      if (i < spreads.length - 1) push('turn', TURN, i);
    });
    totalU = u;
  }
  buildSegs();

  /* ------------------------------------------- mobile portrait book (mbook)
     Each desktop half-spread (or an authored .m-pg sub-group of one) becomes
     a portrait leaf. Scroll position scrubs vertical page turns: the current
     leaf rotates up around its top edge; the next leaf already lies beneath. */
  const mLeaves = []; // { el, spreadIdx }
  spreads.forEach((sp, si) => {
    sp.querySelectorAll(':scope > .page').forEach(pg => {
      const groups = pg.querySelectorAll(':scope > .page-inner > .m-pg');
      if (groups.length) {
        pg.classList.add('m-split');
        groups.forEach(g => { g.classList.add('m-leaf'); mLeaves.push({ el: g, spreadIdx: si }); });
      } else {
        pg.classList.add('m-leaf');
        mLeaves.push({ el: pg, spreadIdx: si });
      }
    });
  });

  const M_HOLD = 0.25, M_OPEN = 1.0, M_DWELL = 0.75, M_DWELL_FIRST = 0.9, M_DWELL_END = 1.0, M_TURN = 0.55;
  let msegs = [];
  let mTotalU = 0;

  function buildMsegs() {
    msegs = [];
    let u = 0;
    const push = (type, len, i) => { msegs.push({ type, i, a: u, b: u + len }); u += len; };
    if (hasCover) {
      push('hold', M_HOLD);
      push('open', M_OPEN);
    }
    mLeaves.forEach((l, i) => {
      const len = i === mLeaves.length - 1 ? M_DWELL_END
        : (i === 0 && !hasCover ? M_DWELL_FIRST : M_DWELL);
      push('dwell', len, i);
      if (i < mLeaves.length - 1) push('turn', M_TURN, i);
    });
    mTotalU = u;
  }
  buildMsegs();

  let mChrome = null, mShadeEl = null, mIndEl = null, mProgEl = null;
  function ensureMobileChrome() {
    if (mChrome || !stage) return;
    mChrome = doc.createElement('div');
    mChrome.className = 'm-chrome';
    mChrome.setAttribute('aria-hidden', 'true');
    mChrome.innerHTML = '<div class="m-board"></div><div class="m-shade"></div>' +
      '<div class="m-progress"><span></span></div><div class="m-indicator"></div>';
    stage.appendChild(mChrome);
    mShadeEl = mChrome.querySelector('.m-shade');
    mIndEl = mChrome.querySelector('.m-indicator');
    mProgEl = mChrome.querySelector('.m-progress span');
  }

  function setMLeafState(l, state) { // '' hidden · 'on' resting/incoming · 'top' turning above
    if (l._m === state) return;
    l._m = state;
    l.el.classList.remove('m-on', 'm-top');
    if (state) l.el.classList.add(state === 'on' ? 'm-on' : 'm-top');
    if (state !== 'top') {
      l.el.style.transform = '';
      l.el.style.opacity = '';
    }
  }
  const hideOtherMLeaves = (...keep) => {
    mLeaves.forEach(l => { if (!keep.includes(l)) setMLeafState(l, ''); });
  };

  let mode = null;
  let vh = window.innerHeight;
  let expTop = 0;
  let ticking = false;
  const last = { o: -1, turnT: -1, leafOn: null, isOpen: null, flat: null, rp: -1 };
  const mlast = { coverT: -1, turnT: -1, turnI: -1, opened: null, ind: '', prog: -1 };

  function measure() {
    vh = Math.max(1, window.innerHeight);
    const rect = experience.getBoundingClientRect();
    expTop = rect.top + window.scrollY;
    if (mode === 'cinematic') {
      experience.style.height = Math.round((totalU + 1) * vh) + 'px';
    } else if (mode === 'mbook') {
      experience.style.height = Math.round((mTotalU + 1) * vh) + 'px';
    }
  }

  /* ------------------------------------------------ cinematic rendering */
  function applyOpen(o) {
    if (!hasCover) return;
    if (o !== last.o) {
      last.o = o;
      const e = easeInOut(o);
      cover.style.transform = 'perspective(3000px) rotateY(' + (-180 * e).toFixed(2) + 'deg)';
      book.style.transform = 'translate3d(' + (-25 * (1 - e)).toFixed(3) + '%,0,0)';
      if (boardClosed) boardClosed.style.opacity = (1 - clamp((o - 0.3) / 0.25, 0, 1)).toFixed(3);
      if (boardOpen) boardOpen.style.transform = 'scaleX(' + (0.5 + 0.5 * e).toFixed(4) + ')';
      if (paperLeft) paperLeft.style.opacity = clamp((o - 0.78) / 0.14, 0, 1).toFixed(3);
      if (coverShade) coverShade.style.opacity = ((o < 0.5 ? o : 1 - o) * 0.8).toFixed(3);
    }
    const flat = o >= 0.96;
    if (flat !== last.flat) {
      last.flat = flat;
      cover.classList.toggle('cover-flat', flat);
    }
    const isOpen = o > 0.55;
    if (isOpen !== last.isOpen) {
      last.isOpen = isOpen;
      scene.classList.toggle('is-open', isOpen);
      if (tabs) tabs.classList.toggle('tabs-on', isOpen);
    }
  }

  function setLeaf(on, t) {
    if (on !== last.leafOn) {
      last.leafOn = on;
      if (leaf) leaf.classList.toggle('on', on);
      if (!on && turnShade) turnShade.style.opacity = '0';
    }
    if (on && t !== last.turnT) {
      last.turnT = t;
      const e = easeInOut(t);
      if (leaf) leaf.style.transform = 'perspective(3000px) rotateY(' + (-180 * e).toFixed(2) + 'deg)';
      if (turnShade) turnShade.style.opacity = (Math.sin(Math.PI * e) * 0.9).toFixed(3);
    }
  }

  function renderCinematic() {
    const u = clamp((window.scrollY - expTop) / vh, 0, totalU - 0.0001);

    if (ribbon) {
      const rp = Math.round((u / totalU) * 200) / 200;
      if (rp !== last.rp) {
        last.rp = rp;
        ribbon.style.setProperty('--rp', String(rp));
      }
    }

    let seg = segs[0];
    for (let s = 0; s < segs.length; s++) {
      if (u < segs[s].b) { seg = segs[s]; break; }
      seg = segs[s];
    }
    const t = clamp((u - seg.a) / (seg.b - seg.a), 0, 1);

    if (seg.type === 'hold') {
      applyOpen(0);
      setLeaf(false, 0);
      hideOtherSpreads();
    } else if (seg.type === 'open') {
      applyOpen(t);
      setLeaf(false, 0);
      const first = spreads[0];
      setSpreadState(first, t < 0.3 ? '' : t < 0.95 ? 'sp-right' : 'sp-open');
      hideOtherSpreads(first);
    } else if (seg.type === 'dwell') {
      applyOpen(1);
      setLeaf(false, 0);
      const sp = spreads[seg.i];
      setSpreadState(sp, 'sp-open');
      hideOtherSpreads(sp);
    } else if (seg.type === 'turn') {
      applyOpen(1);
      setLeaf(t > 0.002 && t < 0.998, t);
      const out = spreads[seg.i];
      const inc = spreads[seg.i + 1];
      setSpreadState(out, t < 0.12 ? 'sp-open' : t < 0.66 ? 'sp-left' : '');
      setSpreadState(inc, t < 0.4 ? '' : t < 0.82 ? 'sp-right' : 'sp-open');
      hideOtherSpreads(out, inc);
    }
  }

  /* --------------------------------------------------- mbook rendering */
  function mSetOpened(open) {
    if (open === mlast.opened) return;
    mlast.opened = open;
    if (stage) stage.classList.toggle('m-open', open);
    if (tabs && hasCover) tabs.classList.toggle('tabs-on', open);
    if (cover) cover.classList.toggle('m-off', open && mlast.coverT >= 1);
  }

  function mCoverTo(t) {
    if (!cover || t === mlast.coverT) return;
    mlast.coverT = t;
    if (t <= 0) {
      cover.style.transform = '';
      cover.style.opacity = '';
      cover.classList.remove('m-off');
    } else if (t >= 1) {
      cover.classList.add('m-off');
    } else {
      cover.classList.remove('m-off');
      const e = easeInOut(t);
      cover.style.transform = 'perspective(1400px) rotateX(' + (-100 * e).toFixed(2) + 'deg)';
      cover.style.opacity = String(1 - clamp((t - 0.85) / 0.15, 0, 1));
    }
  }

  function mIndicator(n) {
    const text = n < 1 ? '' : n + ' / ' + mLeaves.length;
    if (text !== mlast.ind) {
      mlast.ind = text;
      if (mIndEl) mIndEl.textContent = text;
    }
  }

  function renderMobile() {
    const u = clamp((window.scrollY - expTop) / vh, 0, mTotalU - 0.0001);

    if (mProgEl) {
      const p = Math.round((u / mTotalU) * 200) / 200;
      if (p !== mlast.prog) {
        mlast.prog = p;
        mProgEl.style.transform = 'scaleX(' + p + ')';
      }
    }

    let seg = msegs[0];
    for (let s = 0; s < msegs.length; s++) {
      if (u < msegs[s].b) { seg = msegs[s]; break; }
      seg = msegs[s];
    }
    const t = clamp((u - seg.a) / (seg.b - seg.a), 0, 1);

    if (seg.type === 'hold') {
      mCoverTo(0);
      mSetOpened(false);
      hideOtherMLeaves();
      if (mShadeEl) mShadeEl.style.opacity = '0';
      mIndicator(0);
    } else if (seg.type === 'open') {
      // The cover lifts up from its lower edge; page 1 already lies beneath.
      mCoverTo(t);
      mSetOpened(t > 0.55);
      const first = mLeaves[0];
      setMLeafState(first, 'on');
      hideOtherMLeaves(first);
      if (mShadeEl) mShadeEl.style.opacity = (Math.sin(Math.PI * easeInOut(t)) * 0.3).toFixed(3);
      mIndicator(t > 0.55 ? 1 : 0);
    } else if (seg.type === 'dwell') {
      mCoverTo(hasCover ? 1 : 0);
      mSetOpened(true);
      const l = mLeaves[seg.i];
      setMLeafState(l, 'on');
      hideOtherMLeaves(l);
      if (mShadeEl) mShadeEl.style.opacity = '0';
      mIndicator(seg.i + 1);
    } else if (seg.type === 'turn') {
      mCoverTo(hasCover ? 1 : 0);
      mSetOpened(true);
      const out = mLeaves[seg.i];
      const inc = mLeaves[seg.i + 1];
      setMLeafState(inc, 'on');
      if (t <= 0.002 || t >= 0.998) {
        setMLeafState(out, t < 0.5 ? 'on' : '');
        if (t < 0.5) setMLeafState(inc, '');
        if (mShadeEl) mShadeEl.style.opacity = '0';
      } else {
        setMLeafState(out, 'top');
        const e = easeInOut(t);
        out.el.style.transform = 'perspective(1400px) rotateX(' + (-96 * e).toFixed(2) + 'deg)';
        out.el.style.opacity = String(1 - clamp((t - 0.82) / 0.18, 0, 1));
        if (mShadeEl) mShadeEl.style.opacity = (Math.sin(Math.PI * e) * 0.32).toFixed(3);
      }
      hideOtherMLeaves(out, inc);
      mIndicator(t < 0.5 ? seg.i + 1 : seg.i + 2);
    }
  }

  function onScroll() {
    if ((mode !== 'cinematic' && mode !== 'mbook') || ticking) return;
    ticking = true;
    requestAnimationFrame(() => {
      ticking = false;
      if (mode === 'cinematic') renderCinematic();
      else if (mode === 'mbook') renderMobile();
    });
  }

  /* ------------------------------------------------------ mode switching */
  function clearInline() {
    [book, cover, coverShade, boardClosed, boardOpen, paperLeft, leaf, turnShade, ribbon].forEach(el => {
      if (el) el.removeAttribute('style');
    });
    experience.style.height = '';
  }

  function resetState() {
    Object.assign(last, { o: -1, turnT: -1, leafOn: null, isOpen: null, flat: null, rp: -1 });
    if (cover) cover.classList.remove('cover-flat');
    scene.classList.remove('is-open');
    if (tabs) tabs.classList.remove('tabs-on');
    if (leaf) leaf.classList.remove('on');
    spreads.forEach(sp => setSpreadState(sp, ''));
  }

  function resetMobile() {
    Object.assign(mlast, { coverT: -1, turnT: -1, turnI: -1, opened: null, ind: '', prog: -1 });
    if (cover) {
      cover.classList.remove('m-off');
      cover.removeAttribute('style');
    }
    if (stage) stage.classList.remove('m-open');
    if (tabs) tabs.classList.remove('tabs-on');
    mLeaves.forEach(l => setMLeafState(l, ''));
    experience.style.height = '';
  }

  function setMode() {
    let target = 'flow';
    if (mqStage.matches && !mqMotion.matches) target = 'cinematic';
    else if (mqMobile.matches && !mqMotion.matches) target = 'mbook';
    if (target === mode) return;

    if (mode === 'flow') flowTeardown();
    if (mode === 'cinematic') { clearInline(); resetState(); }
    if (mode === 'mbook') resetMobile();

    mode = target;
    root.classList.toggle('book-cinematic', mode === 'cinematic');
    root.classList.toggle('book-mbook', mode === 'mbook');

    if (mode === 'cinematic') {
      resetState();
      experience.classList.add('bk-live');
      if (!hasCover) {
        scene.classList.add('is-open');
        if (tabs) tabs.classList.add('tabs-on');
      }
      measure();
      renderCinematic();
    } else if (mode === 'mbook') {
      ensureMobileChrome();
      resetMobile();
      experience.classList.add('bk-live');
      if (!hasCover && tabs) tabs.classList.add('tabs-on');
      measure();
      renderMobile();
    } else {
      resetState();
      resetMobile();
      measure();
      flowInit();
    }
  }

  /* ------------------------------------------------------- interactions */
  function dwellTopForSpread(idx) {
    if (mode === 'mbook') {
      const li = mLeaves.findIndex(l => l.spreadIdx === idx);
      if (li < 0) return null;
      const seg = msegs.find(s => s.type === 'dwell' && s.i === li);
      return seg ? Math.round(expTop + (seg.a + 0.03) * vh) : null;
    }
    const seg = segs.find(s => s.type === 'dwell' && s.i === idx);
    return seg ? Math.round(expTop + (seg.a + 0.03) * vh) : null;
  }

  function spreadIndexForHash(hash) {
    const id = (hash || '').replace(/^#/, '');
    if (!id) return -1;
    const el = doc.getElementById(id);
    if (!el) return -1;
    return spreads.indexOf(el.closest('.spread'));
  }

  // In-book chapter anchors (the cover's "Open the book" link, contents
  // pages) map onto the active timeline; in flow mode they jump natively.
  experience.addEventListener('click', event => {
    if (mode !== 'cinematic' && mode !== 'mbook') return;
    const link = event.target.closest('a[href^="#"]');
    if (!link) return;
    const idx = spreadIndexForHash(link.getAttribute('href'));
    if (idx < 0) return;
    const top = dwellTopForSpread(idx);
    if (top === null) return;
    event.preventDefault();
    window.scrollTo({ top, behavior: 'smooth' });
  });

  if (cover) {
    cover.addEventListener('click', event => {
      if (mode !== 'cinematic' && mode !== 'mbook') return;
      if (cover.classList.contains('cover-flat') || cover.classList.contains('m-off')) return;
      if (event.target.closest('a')) return; // the open link handles itself
      const top = dwellTopForSpread(0);
      if (top !== null) window.scrollTo({ top, behavior: 'smooth' });
    });
  }

  // Deep links like /services#chapter-3 map onto the timeline.
  function jumpToHash() {
    if (mode !== 'cinematic' && mode !== 'mbook') return;
    const idx = spreadIndexForHash(window.location.hash);
    if (idx < 0) return;
    const top = dwellTopForSpread(idx);
    if (top !== null) window.scrollTo(0, top);
  }

  let resizeTimer = 0;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      measure();
      if (mode === 'cinematic') renderCinematic();
      else if (mode === 'mbook') renderMobile();
    }, 120);
  });

  window.addEventListener('scroll', onScroll, { passive: true });
  onMq(mqStage, setMode);
  onMq(mqMobile, setMode);
  onMq(mqMotion, setMode);

  setMode();
  jumpToHash();
})();
