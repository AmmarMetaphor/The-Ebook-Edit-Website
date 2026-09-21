/*
  The Ebook Edit — funnel measurement.

  One helper, teebeTrack(), and the events every presentation shares. Raw
  gtag calls appear nowhere else in the theme.

  Nothing personal reaches Google Analytics or Microsoft Clarity from here.
  Every parameter below is a page path, a route name, a form name, a
  call-to-action label, or a place in the layout. The fields a visitor fills
  in are read by nothing in this file.

  If analytics fails to load, is blocked, or is switched off, teebeTrack()
  is a no-op that still runs its callback, so a form submission or a link
  behaves exactly as it would without it.

  inc/analytics.php supplies window.teebeAnalytics.
*/
(() => {
  "use strict";

  const CFG = Object.assign(
    {
      route: "",
      pagePath: "/",
      leadOrigin: "other",
      forms: {},
      isBookingPage: false,
      isThankYouPage: false,
      bookingConfirmed: false
    },
    window.teebeAnalytics || {}
  );

  const base = () => ({ page_path: CFG.pagePath });

  /*
    Send one event.

    `done`, when given, runs once the event has been acknowledged — or
    immediately if analytics is unavailable, and after a short timeout if
    the network is slow. That is what lets a successful form submission
    record the lead and then redirect, without the redirect racing the
    measurement or waiting on it indefinitely.
  */
  const track = (name, params, done) => {
    const payload = Object.assign(base(), params || {});
    let finished = false;
    const finish = () => {
      if (finished) return;
      finished = true;
      if (typeof done === "function") done();
    };

    if (typeof window.gtag !== "function") {
      finish();
      return;
    }

    try {
      if (typeof done === "function") {
        payload.event_callback = finish;
        payload.event_timeout = 600;
        // gtag only calls event_callback once the hit has been sent. When
        // gtag.js itself is blocked the inline snippet still defines gtag(),
        // so the callback never comes; this keeps the visitor moving.
        window.setTimeout(finish, 700);
      }
      window.gtag("event", name, payload);
    } catch (e) {
      finish();
      return;
    }

    if (typeof done !== "function") return;
  };

  window.teebeTrack = track;

  const ready = fn => {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", fn, { once: true });
    } else {
      fn();
    }
  };

  /* ---- where in the layout something was clicked ---- */

  const LOCATIONS = [
    ["header", "header"],
    ["nav", "nav"],
    [".site-footer", "footer"],
    [".hero", "hero"],
    [".page-hero", "page-hero"],
    [".cta-band", "cta-band"],
    [".final-cta", "final-cta"],
    [".featured", "featured-carousel"],
    [".services", "services"],
    [".svc-map", "service-map"],
    [".detail", "service-detail"],
    [".project", "portfolio-project"],
    [".example", "work-example"],
    [".consult-card", "booking-card"],
    [".lead-card", "lead-form"],
    [".contact-ways", "contact-ways"],
    [".whatsapp", "floating-button"]
  ];

  const locationOf = el => {
    const declared = el.closest("[data-cta-location]");
    if (declared) return declared.getAttribute("data-cta-location");
    for (let i = 0; i < LOCATIONS.length; i++) {
      if (el.closest(LOCATIONS[i][0])) return LOCATIONS[i][1];
    }
    return "page";
  };

  const label = el => (el.textContent || "").replace(/\s+/g, " ").trim().slice(0, 100);

  /* ---- A. form_start: the first real interaction with a lead form ---- */

  const started = new WeakSet();

  const formName = form => CFG.forms[form.id] || form.id || "lead_form";

  const onFormInteraction = event => {
    const form = event.target && event.target.closest ? event.target.closest("form.lead-form") : null;
    if (!form || started.has(form)) return;
    // Focus alone is not intent; a keystroke or a chosen option is.
    if (event.type === "focusin") return;
    started.add(form);
    track("form_start", { form_name: formName(form) });
  };

  document.addEventListener("input", onFormInteraction, true);
  document.addEventListener("change", onFormInteraction, true);

  /* ---- B. generate_lead is fired by the presentation that owns the form,
       on a genuine wpcf7mailsent and before its redirect, so that a lead is
       never recorded for an invalid, spam, aborted or failed submission.
       See assets/js/site.js and assets/js/landing.js. ---- */

  /* ---- C. consultation_cta_click ---- */

  const bookingPath = CFG.bookingPath || "/book-consultation/";
  const thankYouPath = (() => {
    try {
      return new URL(CFG.thankYouUrl || "/thank-you/", window.location.origin).pathname;
    } catch (e) {
      return "/thank-you/";
    }
  })();

  const leadsToBooking = link => {
    if (link.hasAttribute("data-cta-consult")) return true;
    const href = link.getAttribute("href") || "";
    if (!href || href.charAt(0) === "#") return false;
    let url;
    try {
      url = new URL(href, window.location.href);
    } catch (e) {
      return false;
    }
    if (url.origin !== window.location.origin) return false;
    // The booking page, and the shared conversion page the Meta Ads landing
    // page sends its calls to action to. Ordinary navigation is not a
    // conversion event and is deliberately excluded.
    return url.pathname === bookingPath || url.pathname === thankYouPath;
  };

  document.addEventListener(
    "click",
    event => {
      const link = event.target && event.target.closest ? event.target.closest("a[href]") : null;
      if (!link) return;

      const href = link.getAttribute("href") || "";

      if (href.indexOf("mailto:") === 0) {
        track("email_click", { cta_location: locationOf(link) });
        return;
      }

      if (link.id === "whatsapp" || link.hasAttribute("data-whatsapp") || href.indexOf("https://wa.me/") === 0) {
        track("whatsapp_click", { cta_location: locationOf(link) });
        return;
      }

      if (leadsToBooking(link)) {
        track("consultation_cta_click", {
          cta_text: label(link),
          cta_location: locationOf(link)
        });
      }
    },
    true
  );

  /* ---- D. consultation_booking_view ---- */

  if (CFG.isBookingPage) {
    ready(() => track("consultation_booking_view", { route: CFG.route }));
  }

  /* ---- G. appointment_booked ----

     A booking exists only when HighLevel says so. The calendar is a
     cross-origin frame and is never inspected; the single signal is
     HighLevel's own post-booking redirect back to the Thank You page
     carrying ?conversion=appointment_booked.

     The session guard means a refresh, a back-navigation or a restored tab
     cannot count the same appointment twice, and the parameter is removed
     from the address bar afterwards without reloading the page.
  */

  if (CFG.isThankYouPage) {
    ready(() => {
      let confirmed = CFG.bookingConfirmed === true;

      if (!confirmed) {
        // Read it here too: a page cache may serve /thank-you/ without
        // having seen the query string.
        try {
          confirmed = new URLSearchParams(window.location.search).get("conversion") === "appointment_booked";
        } catch (e) {
          confirmed = false;
        }
      }

      if (!confirmed) return;

      let alreadyCounted = false;
      try {
        alreadyCounted = window.sessionStorage.getItem("teebe.appointment_booked") === "1";
      } catch (e) {
        alreadyCounted = false;
      }

      if (!alreadyCounted) {
        try {
          window.sessionStorage.setItem("teebe.appointment_booked", "1");
        } catch (e) {
          /* Storage unavailable; the parameter is still cleared below. */
        }
        track("appointment_booked", { route: CFG.route });
      }

      try {
        const url = new URL(window.location.href);
        url.searchParams.delete("conversion");
        window.history.replaceState(null, "", url.pathname + url.search + url.hash);
      } catch (e) {
        /* Leaving the parameter in the address bar is harmless. */
      }
    });
  }
})();
