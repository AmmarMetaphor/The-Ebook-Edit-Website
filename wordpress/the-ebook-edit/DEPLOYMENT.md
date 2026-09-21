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
  inc/analytics.php         Google Analytics 4, Microsoft Clarity, the Meta
                            Pixel and the funnel events
  inc/seo-data.php          Insights page metadata, generated
  inc/seo-meta.php          prints title, description, canonical, social, JSON-LD
  inc/setup.php             the Appearance → The Ebook Edit Setup screen
  assets/css/site.css       the approved website's design system
  assets/css/landing.css    the landing page's design system
  assets/css/styles.css     design tokens and prose, Insights      (generated)
  assets/css/book.css       the book presentation, Insights        (generated)
  assets/js/site.js         the website's carousel, reveals and filters
  assets/js/landing.js      the landing page's carousel and router
  assets/js/analytics.js    teebeTrack(), teebeMetaTrack() and the events
  assets/js/ghl-forms.js    sizes the card around a HighLevel form
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
| Insights library | `/insights/` and its four articles | `header-book.php` / `footer-book.php` | `styles.css`, `book.css` | `book.js` |

`teebe_assets()` in `functions.php` picks one and only one. Analytics loads on
all three; the HighLevel form assets load only on the three pages that carry a
form.

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

All three are **HighLevel forms**, embedded as the approved iframes. There is
no form plugin to install and nothing to create in WordPress: the fields, the
validation, the lead storage and the redirect after submission all live in
HighLevel.

| Form | Where | HighLevel form ID | Declared height |
|---|---|---|---|
| Homepage Lead Form | Home (`/`) | `bZam6l0zBSf4yrcD6XSY` | 699 |
| Contact Page Enquiry | Contact (`/contact/`) | `vC0z1TGPPq8K7al5gSS4` | 697 |
| Landing Page Lead Form | the Meta Ads landing page | `jKHEEoy6GmtlAxv4fy1p` | 699 |

`teebe_render_ghl_form()` in `inc/site.php` is the single place any of them is
printed, and `teebe_ghl_forms()` above it is the single place the IDs appear.
Each form sits inside the approved card, under the approved heading, and the
theme adds nothing but the wrapper that gives the card its height.

**The theme recreates none of the fields.** Name, email, telephone, book type,
stage and budget live in HighLevel. Nothing in the theme reads into the
cross-origin frame, listens for its submit, or guesses when a submission
succeeded.

### The embed library

`https://link.msgsndr.com/js/form_embed.js` drives both the forms and the
booking calendar. It is loaded once per page, and only where it is needed:

* on the three form pages, `teebe_ghl_form_assets()` enqueues it as
  `teebe-ghl-form-embed`;
* on `/book-consultation/` and `/thank-you/`, it arrives inside the approved
  calendar embed, which is left exactly as supplied.

No page carries both a form and the calendar, so there is never a second copy.
The library is never versioned, bundled or hosted locally.

### Sizing the card

The approved embed carries `height:100%` and a declared `data-height`.
`assets/css/site.css` and `assets/css/landing.css` give `.ghl-form-wrap` that
declared height, so the card is the right size from the first frame and the
page does not jump while HighLevel starts up. `assets/js/ghl-forms.js` then
watches the iframe element's `style` attribute — its own element, on its own
page — and hands control to HighLevel the moment the real height is written.

A shorter form therefore leaves no dead space, and a taller one, which is what
a narrow screen produces, is never clipped. Nothing in the chain sets
`overflow:hidden` in a way that cuts the form off; verified at thirteen widths
from 1920px to 360px.

### Where a submission goes

HighLevel performs the redirect, from each form's own settings. The production
configuration is:

| Form | Redirect after submission |
|---|---|
| Homepage Lead Form | `https://theebookedit.com/thank-you/?conversion=lead&source=home` |
| Contact Page Enquiry | `https://theebookedit.com/thank-you/?conversion=lead&source=contact` |
| Landing Page Lead Form | `https://theebookedit.com/thank-you/?conversion=lead&source=landing` |

That redirect is the only signal WordPress gets, and the only one it acts on.
See §5.

## 5. Analytics and advertising measurement

One authoritative implementation, in `inc/analytics.php`, printed through
`wp_head` at priority 1 — which every presentation calls, including the
landing page that renders its own document. No template carries a copy, and no
page can end up with two.

| | | Consent category |
|---|---|---|
| Google Analytics 4 | `G-EQFMTN2WJF` | statistics |
| Microsoft Clarity | `yl7loe6vel` | statistics |
| Meta Pixel | `1492057326110606` | **marketing** |

All three are the exact snippets supplied, and every ID is filterable:

```php
add_filter( 'teebe_analytics_ga4_id',       fn() => 'G-XXXXXXX' );
add_filter( 'teebe_analytics_clarity_id',   fn() => '' );   // '' turns it off
add_filter( 'teebe_analytics_meta_pixel_id', fn() => '' );
add_filter( 'teebe_analytics_enabled',           '__return_false' );  // GA4 + Clarity
add_filter( 'teebe_analytics_marketing_enabled', '__return_false' );  // Meta Pixel
```

Pages are real WordPress URLs, so GA4's own page-load tracking and the Meta
Pixel's own `fbq('track', 'PageView')` each fire once per page load. The theme
sends no manual `page_view` or second `PageView` and there are no virtual ones
to invent.

**Where each part is printed.** `teebe_analytics_head()` on `wp_head` priority
1 prints all three script tags; `teebe_analytics_body_open()` on
`wp_body_open` priority 1 prints the Meta Pixel's `<noscript>` image, because
an image is body markup. Both are guarded, so a document can only ever get
one of each. Every template — including the landing page, which renders its
own document — calls both hooks, which is how one implementation covers
every surface.

**The landing page's internal views** (`#about-us`, `#privacy-policy`,
`#terms-and-conditions`) are deliberately **not** counted as page views by
either service. They are legal and company reading inside one advertising
landing, not funnel steps, and counting them would inflate the PageView
number the landing page's ad spend is measured against.

### Events

`window.teebeTrack( name, params, callback )` in `assets/js/analytics.js` is
the only place the theme talks to gtag. It no-ops safely when analytics is
unavailable, blocked or switched off, and still runs its callback, so a form
submission or a link behaves exactly as it would without it.

`window.teebeMetaTrack()` and `window.teebeMetaTrackCustom()` do the same for
the Meta Pixel, and `window.teebeTrackLead()` sends both sides of a delivered
enquiry in the required order. All of them no-op safely.

| When | Google Analytics 4 | Meta Pixel |
|---|---|---|
| page load | automatic `page_view` | `PageView` (base code) |
| HighLevel captures an enquiry | `generate_lead` | `Lead` |
| HighLevel confirms a booking | `appointment_booked` | `Schedule` |
| a link into the booking funnel | `consultation_cta_click` | `ConsultationCTAClick` *(custom)* |
| `/book-consultation/` viewed | `consultation_booking_view` | — (the base `PageView` already covers it) |
| WhatsApp button or text link | `whatsapp_click` | `WhatsAppClick` *(custom)* |
| a `mailto:` link | `email_click` | `EmailClick` *(custom)* |

There is no `form_start` and no submit handler. The forms are cross-origin,
so a keystroke inside one is not observable and neither is its submission —
and the theme does not pretend otherwise.

**A lead is recorded on the Thank You page**, when HighLevel returns the
visitor with `?conversion=lead`, and the `source` parameter says which form
it came from (`home`, `contact` or `landing`; anything else is discarded).
`generate_lead` goes first, then Meta's `Lead`.

Parameters are limited to `page_path`, `form_name`, `lead_origin`, `cta_text`,
`cta_location` and `route`. **No name, email address, telephone or WhatsApp
number, manuscript text or free-text description is sent to Google Analytics,
Microsoft Clarity or Meta, or put in a URL.** No Meta Advanced Matching is
configured. Lead details stay in HighLevel, which is where the lead lives.

Ordinary navigation is not a conversion: `consultation_cta_click` and
`ConsultationCTAClick` fire only for links whose destination is the booking
page or the shared Thank You page. **A call-to-action click is never
`Schedule`** — that is reserved for a booking HighLevel has confirmed.

### Consent — action required before production

This is a UK-facing site and **no consent-management plugin is installed**.
The theme does not add a cookie banner of its own, because a second banner on
a site that already has one is worse than none. Instead:

* if a plugin implementing the **WordPress Consent API** is active, the
  visitor's own choice decides — *statistics* for Google Analytics and
  Clarity, *marketing* for the Meta Pixel. This is what CookieYes, Complianz
  and Real Cookie Banner all expose;
* the two decisions are separate, so a visitor who accepts measurement but
  refuses advertising gets Google and Clarity and no Pixel;
* if no such plugin is active, **all three load on every visit**;
* `teebe_analytics_enabled` and `teebe_analytics_marketing_enabled` have the
  final say either way.

The Meta Pixel's `<noscript>` image is behind exactly the same marketing
decision as its script, so the fallback cannot become a quiet way round the
consent manager. When marketing consent is refused, neither is printed at all.

**To complete consent configuration:** install a consent-management plugin
that supports the WordPress Consent API, enable its Consent API integration,
and categorise:

| Tool | Category |
|---|---|
| Google Analytics 4 — `G-EQFMTN2WJF` | Statistics / Analytics |
| Microsoft Clarity — `yl7loe6vel` | Statistics / Analytics |
| **Meta Pixel — `1492057326110606`** | **Marketing / Advertising** |

The theme then honours the visitor's choice with no further change. Until that
is done, consent is **not** configured, whatever this theme does. If your
plugin blocks tags by script URL rather than through the Consent API, block
`connect.facebook.net/en_US/fbevents.js` under Marketing and
`googletagmanager.com` and `clarity.ms` under Statistics — but prefer the
Consent API, which the theme reads directly.

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
| Form | HighLevel, "Landing Page Lead Form" (`jKHEEoy6GmtlAxv4fy1p`) |

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

The theme no longer carries any. It used to add hidden UTM, click-identifier
and referrer fields to the Contact Form 7 forms and append a Marketing
Attribution block to the notification email; with the forms inside
cross-origin HighLevel frames there is nowhere for WordPress to put them, so
that code has been removed rather than left in place doing nothing.

**Attribution is HighLevel's now.** HighLevel's embed script reads the page's
query string, so a visitor who arrives on
`/?utm_source=meta&utm_medium=paid-social` and submits the homepage form can
have those values recorded against the contact — *provided the form and the
location are configured to capture them*. That is an owner action; see §13.

Campaign measurement in Google Analytics and Meta is unaffected: both read the
campaign parameters from the page URL themselves.

## 9. Mail

**The theme sends no email and needs no mail configuration.** Enquiries go to
HighLevel, which notifies you however its own workflow is set up. There is no
Contact Form 7 mail template, no recipient stored in the theme, no call to PHP
`mail()`, and no SMTP settings.

No password, app password, API key or mailbox secret belongs in this
repository, in a theme file, in `wp-config.php` committed to version control,
or in any file that leaves the server.

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
* sets Home as the static front page;
* flushes rewrite rules.

It creates no forms: the three lead forms and the booking calendar are
HighLevel's, embedded by the templates themselves.

It never edits or deletes content you have written. The only removal is
opt-in: a tick-box that moves WordPress's own default "Sample Page" to Trash,
and only when that page is still the untouched default. Running it twice
produces exactly the same site as running it once.

## 13. Still outstanding before launch

Every one of these is outside WordPress. Installing the theme does none of
them.

1. **HighLevel form redirects.** Each form must redirect, on successful
   submission, to the URL in §4. Without them no enquiry is ever recorded as
   a conversion in Google Analytics or Meta — the redirect is the only signal
   WordPress gets.
2. **HighLevel post-booking redirect** → `https://theebookedit.com/thank-you/?conversion=appointment_booked`,
   for the calendar (§6). Same reasoning.
3. **HighLevel Zoom meeting location and reminders** (§6). The repository has
   no HighLevel access, so nothing here configured the calendar, and the
   meeting link is deliberately not committed.
4. **HighLevel campaign capture** (§8). Confirm each form records the UTM and
   click-identifier parameters from the page URL, or campaign attribution on
   the lead itself is lost — the theme no longer supplies it.
5. **Cookie consent.** No consent plugin is installed. Install one supporting
   the WordPress Consent API and categorise Google Analytics 4
   (`G-EQFMTN2WJF`) and Microsoft Clarity (`yl7loe6vel`) as **Statistics**
   and the Meta Pixel (`1492057326110606`) as **Marketing**. Until then all
   three load on every visit. The HighLevel embeds carry
   `data-cookie-consent="true"` and `data-cookie-consent-provider="auto"`, so
   they defer to whichever consent provider is present. (§5.)
6. **Where enquiry notifications go.** The theme sends no email; set up
   whatever notification or workflow you want in HighLevel and send a test
   through all three forms.
7. **Legal review** of the Privacy Policy and Terms. The Privacy Policy now
   names Google Analytics 4, Microsoft Clarity, the Meta Pixel and HighLevel,
   and says what each is for. Section 1 lists name, email, project
   information and what the forms collect; a solicitor should still read both
   pages before launch.
