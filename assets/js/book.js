/* The Ebook Edit — shared interactive book engine.
   One engine serves three kinds of page:
   1. The homepage: closed cover, scroll-driven opening, page turns.
   2. Coverless volumes (services, process, …): the book starts open on its
      first spread (.book-open-start) and scroll turns the pages.
   3. Static book pages (.book-static: articles, contact, legal, notes):
      normal document flow on book paper — only gentle reveals and the year.
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

  let mode = null;
  let vh = window.innerHeight;
  let expTop = 0;
  let ticking = false;
  const last = { o: -1, turnT: -1, leafOn: null, isOpen: null, flat: null, rp: -1 };

  function measure() {
    vh = Math.max(1, window.innerHeight);
    const rect = experience.getBoundingClientRect();
    expTop = rect.top + window.scrollY;
    if (mode === 'cinematic') {
      experience.style.height = Math.round((totalU + 1) * vh) + 'px';
    }
  }

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

  function render() {
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

  function onScroll() {
    if (mode !== 'cinematic' || ticking) return;
    ticking = true;
    requestAnimationFrame(() => {
      ticking = false;
      render();
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

  function setMode() {
    const target = mqStage.matches && !mqMotion.matches ? 'cinematic' : 'flow';
    if (target === mode) return;

    if (mode === 'flow') flowTeardown();
    if (mode === 'cinematic') { clearInline(); resetState(); }

    mode = target;
    root.classList.toggle('book-cinematic', mode === 'cinematic');

    if (mode === 'cinematic') {
      resetState();
      experience.classList.add('bk-live');
      if (!hasCover) {
        scene.classList.add('is-open');
        if (tabs) tabs.classList.add('tabs-on');
      }
      measure();
      render();
    } else {
      spreads.forEach(sp => setSpreadState(sp, ''));
      measure();
      flowInit();
    }
  }

  /* ------------------------------------------------------- interactions */
  function dwellTop(idx) {
    const seg = segs.find(s => s.type === 'dwell' && s.i === idx);
    return seg ? Math.round(expTop + (seg.a + 0.03) * vh) : null;
  }

  // In-book chapter anchors (the cover's "Open the book" link, contents
  // pages) map onto the cinematic timeline; in flow mode they jump natively.
  experience.addEventListener('click', event => {
    if (mode !== 'cinematic') return;
    const link = event.target.closest('a[href^="#chapter-"]');
    if (!link) return;
    const match = /^#chapter-(\d+)$/.exec(link.getAttribute('href') || '');
    if (!match) return;
    const top = dwellTop(clamp(parseInt(match[1], 10) - 1, 0, spreads.length - 1));
    if (top === null) return;
    event.preventDefault();
    window.scrollTo({ top, behavior: 'smooth' });
  });

  if (cover) {
    cover.addEventListener('click', event => {
      if (mode !== 'cinematic' || cover.classList.contains('cover-flat')) return;
      if (event.target.closest('a')) return; // the open link handles itself
      const top = dwellTop(0);
      if (top !== null) window.scrollTo({ top, behavior: 'smooth' });
    });
  }

  // Deep links like /services#chapter-3 map onto the timeline in cinematic mode.
  function jumpToHash() {
    const match = /^#chapter-(\d+)$/.exec(window.location.hash || '');
    if (!match || mode !== 'cinematic') return;
    const top = dwellTop(clamp(parseInt(match[1], 10) - 1, 0, spreads.length - 1));
    if (top !== null) window.scrollTo(0, top);
  }

  let resizeTimer = 0;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      measure();
      if (mode === 'cinematic') render();
    }, 120);
  });

  window.addEventListener('scroll', onScroll, { passive: true });
  onMq(mqStage, setMode);
  onMq(mqMotion, setMode);

  setMode();
  jumpToHash();
})();
