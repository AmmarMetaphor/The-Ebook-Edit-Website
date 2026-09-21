/*
  Marketing attribution for the lead forms.

  Records where a visitor came from, so the enquiry that arrives in the
  inbox says which campaign produced it. Everything is first-party and
  campaign-level: query parameters from the link that was clicked, the
  referring site, and the page the form was submitted from. No name, email
  address, telephone number or anything typed into a form is read, stored or
  transmitted by this file, and nothing is sent to an analytics service.

  Two touches are kept:

    first  the campaign that introduced the visitor, held for 90 days so an
           ad click still gets the credit when the enquiry arrives days
           later;
    latest the campaign that brought them to this session.

  Storage is first-party and best effort. A browser in private mode, with
  site data blocked, or a consent manager setting

      window.teebeAttributionAllowStorage = false

  before this script runs, all reduce it to the current page — the form
  still submits and the attribution it does have is still delivered.

  Contact Form 7 renders the hidden fields; inc/attribution.php sanitises
  whatever arrives and never trusts it.
*/
(() => {
  "use strict";

  const FIRST_KEY = "teebe.attribution.first";
  const LATEST_KEY = "teebe.attribution.latest";
  const FIRST_MAX_AGE = 90 * 24 * 60 * 60 * 1000;
  const MAX_LENGTH = 400;

  const CAMPAIGN_PARAMS = ["utm_source", "utm_medium", "utm_campaign", "utm_term", "utm_content"];
  const CLICK_IDS = ["gclid", "gbraid", "wbraid", "fbclid", "msclkid"];

  const storageAllowed = window.teebeAttributionAllowStorage !== false;

  const read = (store, key) => {
    if (!storageAllowed) return null;
    try {
      const raw = window[store].getItem(key);
      return raw ? JSON.parse(raw) : null;
    } catch (e) {
      return null;
    }
  };

  const write = (store, key, value) => {
    if (!storageAllowed) return;
    try {
      window[store].setItem(key, JSON.stringify(value));
    } catch (e) {
      /* Private mode, blocked site data, or a full quota. Not worth breaking a form over. */
    }
  };

  const clip = value => String(value == null ? "" : value).trim().slice(0, MAX_LENGTH);

  /* ---- the touch this page represents ---- */

  const params = new URLSearchParams(window.location.search);
  const touch = { at: Date.now(), landing_page: clip(window.location.href) };

  CAMPAIGN_PARAMS.concat(CLICK_IDS).forEach(name => {
    const value = params.get(name);
    if (value) touch[name] = clip(value);
  });

  // Only a referrer from somewhere else says anything; an internal one is
  // just the previous page of this visit.
  let referrer = "";
  try {
    if (document.referrer && new URL(document.referrer).host !== window.location.host) {
      referrer = clip(document.referrer);
    }
  } catch (e) {
    referrer = "";
  }
  if (referrer) touch.original_referrer = referrer;

  const hasCampaign = CAMPAIGN_PARAMS.concat(CLICK_IDS).some(name => touch[name]);

  /*
    A readable one-line summary: the campaign source and medium when the
    link carried them, otherwise the referring site, otherwise a direct
    visit. Click identifiers imply their own platform.
  */
  const describe = t => {
    if (!t) return "";
    if (t.utm_source) return t.utm_medium ? t.utm_source + " / " + t.utm_medium : t.utm_source;
    if (t.gclid || t.gbraid || t.wbraid) return "google / cpc";
    if (t.fbclid) return "facebook / paid-social";
    if (t.msclkid) return "bing / cpc";
    if (t.original_referrer) {
      try {
        return new URL(t.original_referrer).host + " / referral";
      } catch (e) {
        return "referral";
      }
    }
    return "direct";
  };

  /* ---- first touch: kept until it expires, then replaced ---- */

  let first = read("localStorage", FIRST_KEY);
  if (first && (typeof first.at !== "number" || Date.now() - first.at > FIRST_MAX_AGE)) {
    first = null;
  }
  if (!first) {
    first = touch;
    write("localStorage", FIRST_KEY, first);
  }

  /* ---- latest touch: this session, refreshed by any new campaign link ---- */

  let latest = read("sessionStorage", LATEST_KEY);
  if (!latest || hasCampaign) {
    latest = touch;
    write("sessionStorage", LATEST_KEY, latest);
  }

  /* ---- fill the hidden fields Contact Form 7 rendered ---- */

  const values = () => {
    const out = {
      landing_page: latest.landing_page || touch.landing_page,
      original_referrer: latest.original_referrer || "",
      submission_page: clip(window.location.href),
      first_touch_source: describe(first),
      latest_touch_source: describe(latest)
    };
    CAMPAIGN_PARAMS.concat(CLICK_IDS).forEach(name => {
      out[name] = latest[name] || "";
    });
    return out;
  };

  const fill = () => {
    const current = values();
    document.querySelectorAll("form.wpcf7-form").forEach(form => {
      Object.keys(current).forEach(name => {
        const field = form.elements[name];
        if (field && field.type === "hidden") field.value = current[name];
      });
    });
  };

  fill();

  // Contact Form 7 re-renders the form after an invalid, spam or failed
  // submission, which empties the hidden fields again.
  ["wpcf7invalid", "wpcf7spam", "wpcf7mailfailed", "wpcf7submit"].forEach(type => {
    document.addEventListener(type, fill, false);
  });
})();
