/* The Ebook Edit — interactive book homepage driver.
   Progressive enhancement over the flow layout in book-home.css:
   - Flow mode: gentle page reveals and chapter tabs that appear once the
     cover has been passed.
   - Cinematic mode (large, motion-ok viewports): maps natural scroll to a
     timeline that opens the cover and turns the pages. Transform/opacity
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

  const experience = doc.querySelector('.book-experience');
  if (!experience) return;

  const scene = experience.querySelector('.book-scene');
  const book = experience.querySelector('.book');
  const cover = experience.querySelector('.book-cover');
  const coverShade = experience.querySelector('.cover-shade');
  const boardClosed = experience.querySelector('.book-board-closed');
  const boardOpen = experience.querySelector('.book-board-open');
  const paperLeft = experience.querySelector('.paper-left');
  const leaf = experience.querySelector('.book-leaf');
  const turnShade = experience.querySelector('.turn-shade');
  const ribbon = experience.querySelector('.book-ribbon');
  const tabs = experience.querySelector('.book-tabs');
  const openLink = experience.querySelector('.cover-open');
  const spreads = Array.from(experience.querySelectorAll('.spread'));
  if (!scene || !book || !cover || !spreads.length) return;

  const mqMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const mqStage = window.matchMedia('(min-width: 1000px) and (min-height: 640px)');

  const clamp = (v, a, b) => Math.min(b, Math.max(a, v));
  const easeInOut = t => (t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2);
  const onMq = (mq, fn) => {
    if (typeof mq.addEventListener === 'function') mq.addEventListener('change', fn);
    else if (typeof mq.addListener === 'function') mq.addListener(fn);
  };

  /* ---------------------------------------------------- spread states */
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

  /* ------------------------------------------------- cinematic timeline */
  const HOLD = 0.3;    // pause on the closed cover
  const OPEN = 1.35;   // cover opening
  const DWELL = 0.9;   // reading time per spread
  const DWELL_END = 1.1;
  const TURN = 0.75;   // page turn
  let segs = [];
  let totalU = 0;

  function buildSegs() {
    segs = [];
    let u = 0;
    const push = (type, len, i) => { segs.push({ type, i, a: u, b: u + len }); u += len; };
    push('hold', HOLD);
    push('open', OPEN);
    spreads.forEach((sp, i) => {
      push('dwell', i === spreads.length - 1 ? DWELL_END : DWELL, i);
      if (i < spreads.length - 1) push('turn', TURN, i);
    });
    totalU = u;
  }
  buildSegs();

  let mode = null;
  let vh = window.innerHeight;
  let expTop = 0;
  let ticking = false;
  const last = { o: -1, turnT: -1, turnI: -1, leafOn: null, isOpen: null, flat: null, rp: -1 };

  function measure() {
    vh = Math.max(1, window.innerHeight);
    const rect = experience.getBoundingClientRect();
    expTop = rect.top + window.scrollY;
    if (mode === 'cinematic') {
      experience.style.height = Math.round((totalU + 1) * vh) + 'px';
    }
  }

  function applyOpen(o) {
    if (o !== last.o) {
      last.o = o;
      const e = easeInOut(o);
      cover.style.transform = 'perspective(3000px) rotateY(' + (-180 * e).toFixed(2) + 'deg)';
      book.style.transform = 'translate3d(' + (-25 * (1 - e)).toFixed(3) + '%,0,0)';
      if (boardClosed) boardClosed.style.opacity = (1 - clamp((o - 0.3) / 0.25, 0, 1)).toFixed(3);
      if (boardOpen) boardOpen.style.opacity = clamp((o - 0.42) / 0.33, 0, 1).toFixed(3);
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

      if (tabs) {
        coverObserver = new IntersectionObserver(entries => {
          entries.forEach(entry => {
            tabs.classList.toggle('tabs-on', entry.intersectionRatio < 0.3);
          });
        }, { threshold: [0, 0.3, 0.6] });
        coverObserver.observe(cover);
      }
    } else if (tabs) {
      // Reduced motion or no IntersectionObserver: static open book, nav available.
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

  /* ------------------------------------------------------ mode switching */
  function clearInline() {
    [book, cover, coverShade, boardClosed, boardOpen, paperLeft, leaf, turnShade, ribbon].forEach(el => {
      if (el) el.removeAttribute('style');
    });
    experience.style.height = '';
  }

  function resetState() {
    Object.assign(last, { o: -1, turnT: -1, turnI: -1, leafOn: null, isOpen: null, flat: null, rp: -1 });
    cover.classList.remove('cover-flat');
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
      measure();
      render();
    } else {
      spreads.forEach(sp => setSpreadState(sp, ''));
      measure();
      flowInit();
    }
  }

  /* ------------------------------------------------------- interactions */
  function openTarget() {
    return Math.round(expTop + (HOLD + OPEN + 0.08) * vh);
  }

  function openTheBook(event) {
    if (mode !== 'cinematic') return; // flow mode: let the anchor jump natively
    event.preventDefault();
    window.scrollTo({ top: openTarget(), behavior: 'smooth' });
  }

  if (openLink) openLink.addEventListener('click', openTheBook);
  cover.addEventListener('click', event => {
    if (mode !== 'cinematic' || cover.classList.contains('cover-flat')) return;
    if (event.target.closest('a')) return; // the button handles itself
    window.scrollTo({ top: openTarget(), behavior: 'smooth' });
  });

  // Deep links like /#chapter-3 map onto the timeline in cinematic mode.
  function jumpToHash() {
    const match = /^#chapter-(\d+)$/.exec(window.location.hash || '');
    if (!match || mode !== 'cinematic') return;
    const idx = clamp(parseInt(match[1], 10) - 1, 0, spreads.length - 1);
    const seg = segs.find(s => s.type === 'dwell' && s.i === idx);
    if (seg) window.scrollTo(0, Math.round(expTop + (seg.a + 0.02) * vh));
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
