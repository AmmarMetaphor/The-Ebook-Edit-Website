# The Ebook Edit — theme reference

Step-by-step installation is in `wordpress/README-WORDPRESS.md`. This file is
the reference: what is in the theme, how the pieces fit together, and the
details you need when something has to be changed by hand.

---

## 1. What is in the theme

```
the-ebook-edit/
  style.css                 theme header only — the real styles are below
  functions.php             chooses a presentation and enqueues its assets
  header.php  footer.php    the website's shell: header, navigation, footer,
                            WhatsApp button
  header-book.php  footer-book.php
                            the Insights library's shell (the book)
  front-page.php            the homepage
  page-*.php                one template per page of the website
  template-insight-*.php    the four Insights articles
  template-landing-meta-ads.php
                            the Meta Ads landing page
  index.php  page.php       fallbacks for anything added later
  404.php                   not found
  inc/site.php              the website: routing, assets, metadata, forms,
                            booking calendar, WhatsApp
  inc/landing.php           the Meta Ads landing page's equivalent
  inc/analytics.php         Google Analytics 4, Microsoft Clarity, the events
  inc/attribution.php       campaign attribution on every lead
  inc/seo-data.php          Insights page metadata, generated
  inc/seo-meta.php          prints title, description, canonical, social, JSON-LD
  inc/setup.php             the Appearance → The Ebook Edit Setup screen
  cf7/*.txt                 the three Contact Form 7 form bodies
  assets/css/site.css       the approved website's design system
  assets/css/landing.css    the landing page's design system
  assets/css/styles.css     design tokens and prose, Insights      (generated)
  assets/css/book.css       the book presentation, Insights        (generated)
  assets/css/wordpress.css  Contact Form 7 integration, Insights
  assets/js/site.js         the website's carousel, reveals, filters, forms
  assets/js/landing.js      the landing page's carousel, router and form
  assets/js/analytics.js    teebeTrack() and the funnel events
  assets/js/attribution.js  campaign capture for the lead forms
  assets/js/book.js         the book engine, Insights              (generated)
  assets/images/landing/    the approved artwork, shared by the website and
                            the landing page
  assets/images/brand/      logo, icons, Open Graph card           (generated)
```

Only the files marked *generated* come from `wordpress/sync-from-static.py`,
which now reads `insights.html` and `insights/*.html` and nothing else.
Everything else is hand-maintained. The website's templates were ported once
from the approved design (`theebookeditcompletewebsite-refined (4).html`) and
are ordinary theme source from here on — the script will not overwrite them.

## 2. Three presentations, never mixed

| Presentation | Pages | Shell | Stylesheet | Script |
|---|---|---|---|---|
| The website | everything except the two below | `header.php` / `footer.php` | `site.css` | `site.js` |
| Meta Ads landing page | the page assigned that template | its own document | `landing.css` | `landing.js` |
| Insights library | `/insights/` and its four articles | `header-book.php` / `footer-book.php` | `styles.css`, `book.css`, `wordpress.css` | `book.js` |

`teebe_assets()` in `functions.php` picks one and only one. Analytics and
attribution load on all three.

The Insights library keeps the earlier book presentation deliberately: the
approved design does not cover it, its pages are indexed, and nothing in this
release changes their content or their URLs.

## 3. How a page is rendered

WordPress's template hierarchy does the routing. A page whose slug is
`services` is served by `page-services.php`, the homepage by `front-page.php`,
the four articles by the `template-insight-*.php` file assigned to each. A page
can also be pointed at a named template by hand, which is how the Meta Ads
landing page in §7 is published.

The design and the words are in those templates, **not** in the WordPress
editor, and no page stores a shortcode: every form is found by title. Page
records exist only so WordPress has a URL to serve, and their content stays
empty.

Every URL comes from `home_url()` and every asset from `get_theme_file_uri()`,
so the theme carries no hard-coded domain and works unchanged on a staging
address, a subdirectory install, and the live domain.

### Pages and URLs

| Page | URL | Template |
|---|---|---|
| Home | `/` | `front-page.php` |
| Services | `/services/` | `page-services.php` |
| Book Writing | `/writing/` | `page-writing.php` |
| Book Editing | `/editing/` | `page-editing.php` |
| Book Publishing | `/publishing/` | `page-publishing.php` |
| Our Process | `/process/` | `page-process.php` |
| Portfolio | `/portfolio/` | `page-portfolio.php` |
| About | `/about/` | `page-about.php` |
| Contact | `/contact/` | `page-contact.php` |
| Book a Free Consultation | `/book-consultation/` | `page-book-consultation.php` |
| Thank You | `/thank-you/` | `page-thank-you.php` |
| Privacy Policy | `/privacy-policy/` | `page-privacy-policy.php` |
| Terms & Conditions | `/terms-and-conditions/` | `page-terms-and-conditions.php` |
| Insights | `/insights/` | `page-insights.php` |
| Insights articles | `/insights/<slug>/` | `template-insight-*.php` |
| Meta Ads landing page | the slug you advertise | `template-landing-meta-ads.php` |

The legal pages were at `/privacy/` and `/terms/` before this release. Those
addresses now redirect permanently to the new ones, and only once the new page
exists, so a site that has not run setup yet is never sent somewhere empty.

## 4. The three enquiry forms

Contact Form 7 renders all three. The setup screen creates them from the
bundled bodies, with every field, label and dropdown option the approved design
uses.

| Form title | Where | Form id | Body |
|---|---|---|---|
| Home Page Enquiry | Home (`/`) | `leadForm` | `cf7/site-home-enquiry.txt` |
| Contact Page Enquiry | Contact (`/contact/`) | `contactForm` | `cf7/site-contact-enquiry.txt` |
| Start Your Book | the Meta Ads landing page | `leadForm` | `cf7/landing-enquiry.txt` |

All three carry the class `lead-form`, which is what the approved stylesheets
style, and all three are addressed to **support@theebookedit.com**.

Keeping the landing page's form separate from the website's is deliberate: it
is what lets a lead be attributed to the page it came from without reading
anything a visitor typed.

**Fields (all three):** `full_name`\*, `email`\*, `mobile_whatsapp`\*,
`book_type`\*, `book_stage`\*, `expected_budget`\*, plus the hidden honeypot
`hp-field`. (\* = required.) The Contact form also carries `service_interest`,
filled in when the visitor arrived from a service-specific link such as
`/contact/?service=Book+Editing`.

No shortcode is stored on any page: each template looks its form up by title,
so a form can be renamed or restyled without touching page content.

`functions.php` registers a `wpcf7_spam` filter that rejects any submission
where `hp-field` is filled in, which is how the honeypot works without a
plugin. It also disables Contact Form 7's automatic paragraph wrapping,
because the forms supply their own grid markup.

The submit control is a real `<button type="submit">` rather than Contact Form
7's `[submit]` tag. `[submit]` renders an `<input>`, whose label cannot wrap,
and "Get Your Free Book Consultation" wraps onto two lines on a phone in the
approved design. Contact Form 7 binds to the form's `submit` event rather than
to a particular control, so this submits exactly as `[submit]` does.

To rebuild a form by hand: Contact → Add New, set the title exactly as above,
paste the contents of the matching `cf7/*.txt` file into the **Form** tab
(replacing `{{home}}` with your site address), and save.

### What happens on submit

`assets/js/site.js` keeps the approved in-browser validation — six required
fields, with the approved message under each — and blocks an invalid form
before it reaches the plugin. Once Contact Form 7 reports `wpcf7mailsent`, and
only then, the lead is recorded in Google Analytics and the visitor is sent to
`/thank-you/`.

`wpcf7invalid`, `wpcf7spam` and `wpcf7mailfailed` record nothing and go
nowhere: the visitor stays on the page with the plugin's message in front of
them. There is no simulated success anywhere in the theme.

The destination comes from PHP. To change it without editing the theme:

```php
add_filter( 'teebe_site_thank_you_url', fn() => home_url( '/booked/' ) );
add_filter( 'teebe_landing_thank_you_url', fn() => home_url( '/booked/' ) );
```

## 5. Analytics

One authoritative implementation, in `inc/analytics.php`, printed through
`wp_head` at priority 1 — which every presentation calls, including the
landing page that renders its own document. No template carries a copy, and no
page can end up with two.

| | |
|---|---|
| Google Analytics 4 | `G-EQFMTN2WJF` |
| Microsoft Clarity | `yl7loe6vel` |
| Meta Pixel | **not installed** — no Pixel ID was supplied |

Both tags are the exact snippets supplied, and both IDs are filterable:

```php
add_filter( 'teebe_analytics_ga4_id',     fn() => 'G-XXXXXXX' );
add_filter( 'teebe_analytics_clarity_id', fn() => '' );   // '' turns it off
add_filter( 'teebe_analytics_enabled',    '__return_false' );  // e.g. on staging
```

Pages are real WordPress URLs, so GA4's own page-load tracking handles page
views. The theme sends no manual `page_view` events and there are no virtual
ones to invent.

### Events

`window.teebeTrack( name, params, callback )` in `assets/js/analytics.js` is
the only place the theme talks to gtag. It no-ops safely when analytics is
unavailable, blocked or switched off, and still runs its callback, so a form
submission or a link behaves exactly as it would without it.

| Event | When |
|---|---|
| `form_start` | the first keystroke or selection in a lead form, once per form |
| `generate_lead` | a genuine `wpcf7mailsent`, before the redirect |
| `consultation_cta_click` | a link into the booking funnel is clicked |
| `consultation_booking_view` | `/book-consultation/` is viewed |
| `whatsapp_click` | the floating button or a WhatsApp text link |
| `email_click` | a `mailto:` link |
| `appointment_booked` | HighLevel returns a confirmed booking (see §6) |

Parameters are limited to `page_path`, `form_name`, `lead_origin`, `cta_text`,
`cta_location` and `route`. **No name, email address, telephone or WhatsApp
number, manuscript text or free-text description is sent to Google Analytics or
Microsoft Clarity, or put in a URL.** Lead details stay in the Contact Form 7
submission and its notification email.

Ordinary navigation is not a conversion: `consultation_cta_click` fires only
for links whose destination is the booking page or the shared Thank You page.

### Consent — action required before production

This is a UK-facing site and **no consent-management plugin is installed**.
The theme does not add a cookie banner of its own, because a second banner on
a site that already has one is worse than none. Instead:

* if a plugin implementing the **WordPress Consent API** is active, the
  visitor's *statistics* consent decides whether either tag loads — this is
  what CookieYes, Complianz and Real Cookie Banner all expose;
* if none is active, **both tags load on every visit**;
* `teebe_analytics_enabled` has the final say either way.

**To complete consent configuration:** install a consent-management plugin
that supports the WordPress Consent API, enable its Consent API integration,
and categorise Google Analytics 4 and Microsoft Clarity as *statistics*. The
theme then honours the visitor's choice with no further change. Until that is
done, consent is **not** configured, whatever this theme does.

## 6. The booking calendar and the appointment conversion

The HighLevel calendar is embedded exactly as supplied — the booking URL,
calendar id, iframe attributes and script are untouched, and nothing is
injected into the cross-origin frame. `teebe_render_booking_calendar()` in
`inc/site.php` is the single source, used by both `/book-consultation/` and
`/thank-you/`, so there is one calendar configuration rather than two copies
drifting apart.

A calendar load, a date click or a call-to-action click is **not** a booking.
The only signal that an appointment exists is HighLevel's own post-booking
redirect.

### Owner action required — HighLevel redirect

In HighLevel, set the calendar's post-booking redirect to:

```
https://theebookedit.com/thank-you/?conversion=appointment_booked
```

`page-thank-you.php` then fires `appointment_booked` once, guarded by
`sessionStorage` so a refresh, a back-navigation or a restored tab cannot count
the same appointment twice, and removes the parameter from the address bar with
`history.replaceState()` without reloading. The parameter is sanitised before
use, server-side and in the browser, and is only ever compared against that one
value.

A successful **form** submission redirects to `/thank-you/` **without** that
parameter, because `generate_lead` has already been recorded. A plain visit to
`/thank-you/` records nothing at all, so website leads and booked consultations
never get mixed up.

### Owner action required — HighLevel Zoom location

The repository has no access to the HighLevel account, so **changing WordPress
has not configured the calendar**. In HighLevel:

1. **Calendars → *(the consultation calendar)* → Edit.**
2. **Meeting Details → Meeting Location → Custom** (or *Zoom*, if the Zoom
   integration is connected to the account).
3. Paste the approved Zoom meeting URL as the location.
4. Save, then book a test appointment and confirm the location appears in the
   confirmation email and in the calendar invitation.
5. **Notifications & Additional Options:** enable the confirmation message and
   reminders at **24 hours** and **1 hour** before, and confirm each includes
   the date, the time, the timezone and the meeting location.

The Zoom URL is deliberately **not** in this repository, in the theme, or in
any rendered page: it is a private meeting link and belongs in the booking
system, which only sends it to someone who has actually booked.

## 7. The Meta Ads landing page

A separate advertising landing page, added as a page template rather than as
part of the website. It is not linked from the website's navigation and
changes nothing about any other page.

| | |
|---|---|
| Template file | `template-landing-meta-ads.php` |
| Name shown in WordPress | **The Ebook Edit — Meta Ads Landing Page** |
| Integration | `inc/landing.php` |
| Design | `assets/css/landing.css`, `assets/js/landing.js` |
| Form | Contact Form 7, "Start Your Book" → support@theebookedit.com |

**To publish it:** Pages → Add New → title it (for example *Start Your Book*),
set the slug you want to advertise, choose the template above under **Page
Attributes → Template**, and Publish.

**Why it does not use header.php and footer.php.** It carries its own complete
design system, its own `<header>` and `<footer>` and its own
`<main id="landing-view">`. Reusing the shell would produce two headers, two
main landmarks and two competing stylesheets. The template therefore renders
its own document — but still calls `language_attributes()`, `wp_head()`,
`body_class()`, `wp_body_open()` and `wp_footer()`, so plugins, analytics and
attribution behave exactly as they do on any other page. Everything on it is
scoped to the `teebe-landing` body class.

**The funnel** is unchanged by this release:

    ad -> landing page -> lead form -> delivered -> /thank-you/ -> booking

Its calls to action and its delivered enquiries both arrive at `/thank-you/`,
which is now the website's own Thank You page, in the approved design. The
funnel no longer has a thank-you template of its own: the approved design has
**one** Thank You experience, at one URL, shared by the landing page and the
website.

**The WhatsApp button** on every page of the site and the landing page is a
plain link to `https://wa.me/<digits>?text=<message>`, opened in a new tab with
`rel="noopener noreferrer"`. It needs no JavaScript, so it works in Meta's
in-app browser and with the keyboard. The number is defined in one place and
every page uses it:

```php
add_filter( 'teebe_landing_whatsapp_number', fn() => '441234567890' );
add_filter( 'teebe_landing_whatsapp_message', fn() => 'Hello…' );
```

Returning an empty number removes the button.

## 8. Marketing attribution

Because the site takes paid traffic, every enquiry carries the campaign that
produced it. `assets/js/attribution.js` reads the link the visitor arrived on
and the referring site; `inc/attribution.php` sanitises whatever is posted and
never trusts it.

**Captured:** `utm_source`, `utm_medium`, `utm_campaign`, `utm_term`,
`utm_content`, `gclid`, `gbraid`, `wbraid`, `fbclid`, `msclkid`,
`landing_page`, `original_referrer`, `submission_page`, `first_touch_source`,
`latest_touch_source`.

Contact Form 7's own `wpcf7_form_hidden_fields` hook renders the fields, so
they are part of the form and post with it. First touch is kept for 90 days in
first-party `localStorage`, latest touch for the session — enough to credit an
ad click when the enquiry arrives days later, and nothing more. A consent
manager can reduce it to the current page with
`window.teebeAttributionAllowStorage = false`.

The values join the Contact Form 7 submission data, so **Flamingo** stores them
alongside the enquiry when it is installed — and delivery never depends on
Flamingo being there. A compact **Marketing Attribution** block is appended to
the internal notification email, and nothing is appended when a submission
carries no attribution, so a direct visit produces the same clean email it
always did.

Every value is sanitised for its kind, collapsed to one line and clipped to
400 characters. Nothing captured here is sent to an analytics service.

## 9. Mail

Setup gives each form a mail template addressed to **support@theebookedit.com**,
sent from `wordpress@yourdomain` with the visitor's address in Reply-To — the
pattern that passes SPF and DMARC checks. Change the recipient under
**Contact → Contact Forms → *(form)* → Mail**.

WordPress sends through PHP mail by default, which many hosts deliver poorly.
The usual fix is a free SMTP plugin such as WP Mail SMTP, pointed at a mailbox
you control. **SMTP and mailbox configuration is an environment and admin
setting; it is not stored in this theme.**

**Credentials go in that plugin's settings screen on the live site and nowhere
else.** No password, app password, API key or mailbox secret belongs in this
repository, in a theme file, in `wp-config.php` committed to version control,
or in any file that leaves the server. The theme never calls PHP `mail()`
directly and contains no mail system of its own.

## 10. Metadata and search engines

`inc/seo-meta.php` prints, per page: the title, meta description, canonical
URL, Open Graph and Twitter tags, the brand icons and the JSON-LD — resolved
against `home_url()`. The website's entries are hand-maintained in
`inc/site.php`; the Insights entries are generated into `inc/seo-data.php`.

* `/thank-you/` carries `noindex, follow`: it is a conversion endpoint.
  Nothing else on the site is noindexed.
* `robots.txt` points at WordPress's own `/wp-sitemap.xml`.
* Setting a Site Icon in the Customizer replaces the bundled icons.

## 11. Security headers (optional, host-dependent)

The theme sets no HTTP headers. If your host lets you add them, these are the
ones the static site uses:

```
X-Content-Type-Options: nosniff
Referrer-Policy: strict-origin-when-cross-origin
X-Frame-Options: SAMEORIGIN
Permissions-Policy: geolocation=(), microphone=(), camera=()
```

Add a Content-Security-Policy only after testing: WordPress, its plugins, the
Google tag, Clarity and the HighLevel calendar all load third-party and inline
scripts that a strict policy will block.

## 12. What the setup screen changes

Under Appearance → The Ebook Edit Setup, and only when you click the button:

* creates missing page records (`get_page_by_path` first, so nothing is ever
  duplicated) and leaves their content empty;
* creates the three Contact Form 7 forms if forms with those titles do not
  already exist;
* sets Home as the static front page;
* flushes rewrite rules.

It never edits or deletes content you have written. The only removal is
opt-in: a tick-box that moves WordPress's own default "Sample Page" to Trash,
and only when that page is still the untouched default. Running it twice
produces exactly the same site as running it once.

## 13. Still outstanding before launch

1. **Consent configuration** (§5) — install a WordPress Consent API plugin and
   categorise both analytics tools as *statistics*. Not done by this release.
2. **HighLevel post-booking redirect** (§6) — set it to
   `https://theebookedit.com/thank-you/?conversion=appointment_booked`, or no
   booking will ever be recorded as a conversion.
3. **HighLevel Zoom meeting location and reminders** (§6).
4. **SMTP** (§9) — configure a mailbox and send a test enquiry through each of
   the three forms.
5. **Legal review.** The Privacy Policy and Terms & Conditions are the approved
   copy from the design; section 3 of the Privacy Policy has been updated to
   name Google Analytics 4, Microsoft Clarity, the campaign parameters and the
   cookies actually in use. Section 1 still lists only name, email and project
   information: the forms also collect a mobile or WhatsApp number and a budget
   range, which is worth adding at legal review.
6. **Meta Pixel** — not installed, because no Pixel ID was supplied. The event
   layer is ready for one to be added in `inc/analytics.php` and
   `assets/js/analytics.js`.
