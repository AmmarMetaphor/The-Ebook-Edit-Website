/*
  The Ebook Edit — funnel measurement.

  Three helpers — teebeTrack() for Google Analytics, teebeMetaTrack() and
  teebeMetaTrackCustom() for the Meta Pixel — and the events every
  presentation shares. Raw gtag() and fbq() calls appear nowhere else in the
  theme.

  Nothing personal reaches Google Analytics, Microsoft Clarity or Meta from
  here. Every parameter below is a page path, a route name, a form name, a
  call-to-action label, or a place in the layout. The fields a visitor fills
  in are read by nothing in this file, and no Advanced Matching is
  configured.

  If a tag fails to load, is blocked, or is refused by a consent manager,
  every helper is a no-op that still runs its callback, so a link or a form
  behaves exactly as it would without it.

  The three lead forms and the booking calendar are HighLevel's, in
  cross-origin frames. Nothing here reads into them, listens for their
  submit, or guesses when one succeeded: HighLevel's redirect back to the
  Thank You page is the only conversion signal the site uses.

  The Meta Pixel's base code already sends one PageView per page load, and
  every page here is a real WordPress URL, so nothing in this file sends a
  second one. The landing page's internal #about-us, #privacy-policy and
  #terms-and-conditions views are deliberately not counted as page views
  either: they are legal and company reading inside one advertising landing,
  not funnel steps, and counting them would inflate the landing page's own
  PageView count against which its ad spend is measured. Google Analytics
  treats them the same way.

  inc/analytics.php supplies window.teebeAnalytics.
*/
(() => {
  "use strict";

  const CFG = Object.assign(
    {
      route: "",
      pagePath: "/",
      isBookingPage: false,
      isThankYouPage: false,
      conversion: "",
      conversionSource: ""
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

  /*
    Send one Meta Pixel event. `standard` picks fbq('track', …) for Meta's
    own standard events and fbq('trackCustom', …) for ours.

    fbq() queues synchronously — the base snippet defines it before
    fbevents.js arrives — so there is nothing to wait for and no callback to
    take. It no-ops when the Pixel was never printed, which is what happens
    when a visitor refuses marketing cookies.
  */
  const meta = (standard, name, params) => {
    if (typeof window.fbq !== "function") return false;
    try {
      window.fbq(standard ? "track" : "trackCustom", name, Object.assign(base(), params || {}));
      return true;
    } catch (e) {
      return false;
    }
  };

  window.teebeMetaTrack = (name, params) => meta(true, name, params);
  window.teebeMetaTrackCustom = (name, params) => meta(false, name, params);

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

  /* ---- A + B. form_start and generate_lead ----

     Both are gone from this file. The lead forms are HighLevel's now, in
     cross-origin frames, so there is no keystroke to notice and no
     submission to confirm from here. A captured enquiry reaches the site
     as HighLevel's redirect to /thank-you/?conversion=lead, which section
     G below records — once. ---- */

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
        const where = locationOf(link);
        track("email_click", { cta_location: where });
        meta(false, "EmailClick", { cta_location: where });
        return;
      }

      if (link.id === "whatsapp" || link.hasAttribute("data-whatsapp") || href.indexOf("https://wa.me/") === 0) {
        const where = locationOf(link);
        track("whatsapp_click", { cta_location: where });
        meta(false, "WhatsAppClick", { cta_location: where });
        // Nothing is prevented or delayed: the link opens the chat as it
        // would with no measurement at all.
        return;
      }

      if (leadsToBooking(link)) {
        const params = { cta_text: label(link), cta_location: locationOf(link) };
        track("consultation_cta_click", params);
        // A click is interest, not an appointment. Meta's Schedule event is
        // reserved for a booking HighLevel has actually confirmed, below.
        meta(false, "ConsultationCTAClick", params);
      }
    },
    true
  );

  /* ---- D. consultation_booking_view ----
     Google Analytics only. For Meta the base code's PageView already
     records that this page was seen, and viewing a calendar is not a
     booking, so nothing standard applies here. ---- */

  if (CFG.isBookingPage) {
    ready(() => track("consultation_booking_view", { route: CFG.route }));
  }

  /* ---- G. the two conversions HighLevel reports ----

     Both the lead forms and the booking calendar are cross-origin frames,
     and neither is ever inspected. The one signal either produces is
     HighLevel's own redirect back to the Thank You page:

       ?conversion=lead&source=home|contact|landing   an enquiry captured
       ?conversion=appointment_booked                 an appointment confirmed

     They are different things and are never conflated: a captured enquiry
     is a lead, a confirmed appointment is a booking, and clicking a call to
     action is neither. A plain visit to the Thank You page — which several
     journeys pass through — records nothing at all.

     Each is guarded per browsing session, so a refresh, a back-navigation
     or a restored tab cannot count the same conversion twice. The guard for
     a lead includes the source, so someone who genuinely enquires from two
     different forms in one session is counted twice and someone who
     reloads is not. The parameters are then removed from the address bar
     without reloading the page.
  */

  const CONVERSIONS = {
    lead: {
      key: () => "teebe.lead." + (source() || "unknown"),
      fire: () => {
        const params = { route: CFG.route };
        const from = source();
        if (from) params.lead_source = from;
        track("generate_lead", params);
        meta(true, "Lead", params);
      }
    },
    appointment_booked: {
      key: () => "teebe.appointment_booked",
      fire: () => {
        track("appointment_booked", { route: CFG.route });
        meta(true, "Schedule", { route: CFG.route });
      }
    }
  };

  // Only the three forms the theme embeds; anything else in the parameter
  // is discarded rather than reported.
  const SOURCES = ["home", "contact", "landing"];

  const param = name => {
    try {
      return new URLSearchParams(window.location.search).get(name) || "";
    } catch (e) {
      return "";
    }
  };

  // PHP has already sanitised both, but a page cache may serve
  // /thank-you/ without ever having seen the query string, so the address
  // bar is read here too — and checked just as strictly.
  const conversion = () => {
    const value = CFG.conversion || param("conversion");
    return Object.prototype.hasOwnProperty.call(CONVERSIONS, value) ? value : "";
  };
  const source = () => {
    const value = CFG.conversionSource || param("source");
    return SOURCES.indexOf(value) === -1 ? "" : value;
  };

  if (CFG.isThankYouPage) {
    ready(() => {
      const kind = conversion();
      if (!kind) return;

      const entry = CONVERSIONS[kind];
      const guard = entry.key();
      let alreadyCounted = false;

      try {
        alreadyCounted = window.sessionStorage.getItem(guard) === "1";
      } catch (e) {
        alreadyCounted = false;
      }

      if (!alreadyCounted) {
        try {
          window.sessionStorage.setItem(guard, "1");
        } catch (e) {
          /* Storage unavailable; the parameters are still cleared below. */
        }
        entry.fire();
      }

      try {
        const url = new URL(window.location.href);
        url.searchParams.delete("conversion");
        url.searchParams.delete("source");
        window.history.replaceState(null, "", url.pathname + url.search + url.hash);
      } catch (e) {
        /* Leaving the parameters in the address bar is harmless. */
      }
    });
  }
})();
