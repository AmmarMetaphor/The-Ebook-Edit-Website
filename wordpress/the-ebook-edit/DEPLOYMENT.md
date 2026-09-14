# The Ebook Edit — theme reference

Step-by-step installation is in `wordpress/README-WORDPRESS.md`. This file is
the reference: what is in the theme, how the pieces fit together, and the
details you need when something has to be changed by hand.

---

## 1. What is in the theme

```
the-ebook-edit/
  style.css                 theme header only — the real styles are below
  functions.php             assets, body classes, the book boot script, form slots
  header.php  footer.php    the document shell (no navigation bar: the book has tabs)
  front-page.php            the homepage
  page-*.php                one template per page of the website
  template-insight-*.php    the four Insights articles
  template-landing-meta-ads.php
                            the Meta Ads landing page      (hand-maintained)
  template-landing-thank-you.php
                            the consultation thank-you page(hand-maintained)
  index.php  page.php       fallbacks for anything added later
  404.php                   not found
  inc/seo-data.php          page metadata, generated from the website
  inc/seo-meta.php          prints title, description, canonical, social, JSON-LD
  inc/setup.php             the Appearance → The Ebook Edit Setup screen
  inc/landing.php           the landing page's assets, metadata and form
  cf7/*.txt                 the three Contact Form 7 form bodies
  assets/css/styles.css     design tokens, typography, buttons, forms  (generated)
  assets/css/book.css       the book presentation                      (generated)
  assets/css/wordpress.css  Contact Form 7 integration            (hand-maintained)
  assets/css/landing.css    the landing page's own design system  (hand-maintained)
  assets/js/book.js         the book engine                            (generated)
  assets/js/landing.js      the landing page's own scripts        (hand-maintained)
  assets/images/            logo, icons, portfolio covers, favicon      (generated)
  assets/images/landing/    the landing page's own images         (hand-maintained)
```

Generated files come from `wordpress/sync-from-static.py`, which reads the HTML
in the repository root. Do not hand-edit them: change the website and re-run
the script.

## 2. How a page is rendered

WordPress's template hierarchy does the routing. A page whose slug is
`services` is served by `page-services.php`; the homepage by `front-page.php`;
the four articles by the `template-insight-*.php` file assigned to each of
them. A page can also be pointed at a named template by hand, which is how the
Meta Ads landing page in §5 is published.

The design and the words are in those templates, **not** in the WordPress
editor. Page records exist only so WordPress has a URL to serve. The one thing
this theme stores in a page's content is a Contact Form 7 shortcode, on the
Home and Contact pages.

Every URL comes from `home_url()` and every asset from `get_theme_file_uri()`,
so the theme carries no hard-coded domain and works unchanged on a staging
address, a subdirectory install, and the live domain.

## 3. The book, in four presentations

`assets/js/book.js` drives one experience with four presentations, chosen from
the viewport and the visitor's motion preference:

| Presentation | When |
|---|---|
| Desktop spread, scroll-scrubbed page turns | width ≥ 1000px and height ≥ 640px |
| Portrait book, one page at a time | width 360–900px and height ≥ 740px |
| Plain readable column | reduced motion, no JavaScript, or any other size |
| Static book page (articles, legal, thank-you) | always the plain column |

`functions.php` prints the same pre-paint script the published site uses, so
the right presentation renders on the first frame with no flash. If the script
ever fails to load, a timer clears the classes and all content stays reachable.

Nothing hijacks scrolling. Everything animates with transform and opacity only.

## 4. The three enquiry forms

The published site posts to Netlify Forms, which WordPress has no equivalent
of, so Contact Form 7 renders the forms instead.

| Form title | Page | Form class | Body |
|---|---|---|---|
| Project Inquiry | Start a Project (`/contact/`) | `start-form` | `cf7/project-inquiry.txt` |
| Publishing Journey | Home (`/`) | `page-form`, id `enquiry` | `cf7/publishing-journey.txt` |
| Start Your Book | the Meta Ads landing page | `lead-form`, id `leadForm` | `cf7/landing-enquiry.txt` |

The setup screen creates all three from those files, with every field, label,
dropdown option and page-splitting wrapper the published site uses. For the two
that belong to a fixed page it also writes the matching shortcode into that
page — for example:

```
[contact-form-7 id="12" title="Project Inquiry" html_class="start-form"]
```

`html_class` matters: it is what makes the form look like part of the book.

**Fields — Project Inquiry:** `name`*, `email`*, `service`*, `stage`*,
`word-count`, `referral`, `contact-method`, `timeline`, `message`, plus the
hidden honeypot `hp-field`.

**Fields — Publishing Journey:** `name`*, `email`*, `journey`*, `support`,
`message`, plus `hp-field`. (* = required.)

**Fields — Start Your Book:** `full_name`*, `email`*, `mobile_whatsapp`*,
`book_type`*, `book_stage`*, `expected_budget`*, plus `hp-field`. All six
visitor-facing fields are required, and the dropdown values are exactly those
in the approved landing page. This form is addressed to
`support@theebookedit.com` rather than the WordPress administrator email.

Unlike the other two, its shortcode is not stored on a page. The landing page
template can be assigned to a page with any slug, so it looks the form up by
its title and renders it itself — there is nothing to paste.

`functions.php` registers a `wpcf7_spam` filter that rejects any submission
where `hp-field` is filled in, which is how the honeypot works without a
plugin. It also disables Contact Form 7's automatic paragraph wrapping, because
the forms supply their own grid markup.

To rebuild a form by hand: Contact → Add New, set the title exactly as above,
paste the contents of the matching `cf7/*.txt` file into the **Form** tab
(replacing `{{home}}` with your site address), save, and put its shortcode on
the page with the right `html_class`.

## 5. The Meta Ads landing page

A separate advertising landing page, added as a page template rather than as
part of the website. It is not linked from the book, does not appear in the
chapter tabs, and changes nothing about any existing page.

| | |
|---|---|
| Template file | `template-landing-meta-ads.php` |
| Name shown in WordPress | **The Ebook Edit — Meta Ads Landing Page** |
| Integration | `inc/landing.php` |
| Design | `assets/css/landing.css`, `assets/js/landing.js`, `assets/images/landing/` |
| Thank-you template | `template-landing-thank-you.php` — **The Ebook Edit — Consultation Thank You** |
| Images | WebP, ~1.9 MB total (see below) |
| Form | Contact Form 7, "Start Your Book" → support@theebookedit.com |

**To publish it:** Pages → Add New → title it (for example *Start Your Book*),
set the slug you want to advertise (for example `start-your-book`), choose the
template above under **Page Attributes → Template**, and Publish. The page then
answers at that address, for example `/start-your-book/`.

**Why it does not use header.php and footer.php.** The website's shell opens a
`<main id="main">` landmark and loads the book stylesheets and the book engine.
The landing page carries its own complete design system, its own `<header>` and
`<footer>`, and its own `<main id="landing-view">`. Reusing the shell would
produce two headers, two main landmarks and two competing stylesheets. The
template therefore renders its own document — but still calls
`language_attributes()`, `wp_head()`, `body_class()`, `wp_body_open()` and
`wp_footer()`, so plugins behave exactly as they do on any other page.

`teebe_assets()` swaps the book assets for the landing page's own on this
template only, which is why nothing about the rest of the website changes.
Everything on the page is scoped to the `teebe-landing` body class.

**The page is a single document with four views.** The footer's About Us,
Privacy Policy and Terms & Conditions links switch to in-page views through
`#about-us`, `#privacy-policy` and `#terms-and-conditions`. These are the
landing page's own copies and are separate from the website's `/privacy/` and
`/terms/` pages, which are unchanged.

**The funnel.** The landing page is the first of two pages:

    ad -> landing page -> lead form -> delivered -> thank-you page -> booking

`assets/js/landing.js` listens for Contact Form 7's `wpcf7mailsent` on the
landing form itself and sends the visitor to the thank-you page. That event
fires only once the plugin has actually sent the mail, so an invalid, spam,
failed or abandoned submission keeps the visitor on the landing page with the
error in front of them. The listener is bound to that one form element, so no
other Contact Form 7 form on the website can trigger it.

The destination comes from PHP as `window.teebeLanding.thankYouUrl`, built
from `home_url( '/thank-you/' )`. If the thank-you template is published at a
different address, point the redirect at it without editing the theme:

```php
add_filter( 'teebe_landing_thank_you_url', fn() => home_url( '/book-a-call/' ) );
```

Returning an empty string turns the redirect off; the visitor then stays on
the landing page and sees Contact Form 7's own confirmation.

**The header** is the logo and one button, "Speak with our Consultant", which
scrolls to the enquiry form. The landing page carries no navigation links and
no mobile menu: the only path through it is the form. This is the landing
template only — the website's own navigation is the book's chapter tabs and is
untouched.

**The thank-you page** (`template-landing-thank-you.php`, shown in WordPress as
*The Ebook Edit — Consultation Thank You*) is where a delivered enquiry lands
and where the consultation is booked, through the HighLevel calendar embedded
exactly as supplied. It shares this stylesheet and the WhatsApp script with the
landing page and loads neither the carousel nor the lead form. It is marked
`noindex, nofollow` through WordPress's own `wp_robots` filter, because it is a
conversion endpoint rather than a page anyone should find in search; nothing
else on the site is affected.

Publish it the same way as the landing page: Pages -> Add New, set the slug,
choose that template, Publish. **Note that the website already has a page at
`/thank-you/`** (`page-thank-you.php`, created by the setup screen). Either
assign this template to that existing page — which changes what `/thank-you/`
shows for the whole site — or publish the consultation thank-you at its own
slug and point the redirect there with the filter above.

**Images.** The approved page carried its artwork as inline base64, 16.8 MB of
it. The same images are shipped as WebP files in `assets/images/landing/`,
totalling about 1.9 MB:

* the seven images with transparency — the logo and the six platform logos —
  are **lossless** WebP, pixel-for-pixel identical to the approved PNGs;
* the five large book covers and the hero background are lossy WebP at quality
  88, measured at a mean error of 1-2 levels out of 255, which is not visible;
* the first book cover is still the approved JPEG, because WebP was no smaller.

Dimensions are unchanged, so the layout is identical. WebP is supported by
every browser released since 2020 (Chrome 32+, Firefox 65+, Safari 14+,
Edge 18+); a visitor on something older would not see these images, which is
worth knowing but affects a very small share of ad traffic.

**Calls to action.** Every conversion button on the landing page — the header,
the hero, the six carousel buttons, the services buttons and cards, the final
call to action and the one in the About view — is a real anchor pointing at
`teebe_landing_cta_href()`, which is the thank-you page. There are two routes
to the same place and both are intended: a visitor who is ready books straight
away, and a visitor who would rather describe the book first fills in the form
and is taken there once Contact Form 7 confirms delivery.

The carousel controls, the legal links, the email links and the WhatsApp button
are not calls to action and keep their own destinations.

**The WhatsApp button** on both pages is a plain link to
`teebe_landing_whatsapp_url()` — `https://wa.me/<digits>?text=<message>` —
opened in a new tab with `rel="noopener noreferrer"`. It needs no JavaScript,
so it works in Meta's in-app browser and with the keyboard, and there is no
placeholder or fallback behaviour left. The number is defined in one place and
both pages use it. To change it without editing the theme:

```php
add_filter( 'teebe_landing_whatsapp_number', fn() => '441234567890' );
add_filter( 'teebe_landing_whatsapp_message', fn() => 'Hello…' );
```

Returning an empty number renders the button with no destination, which is the
only case in which it should be removed from the templates.

## 6. Mail

Setup gives each form a mail template addressed to your **WordPress
administrator email**, sent from `wordpress@yourdomain` with the visitor's
address in Reply-To — the pattern that passes SPF and DMARC checks. Change the
recipient under **Contact → Contact Forms → *(form)* → Mail**; for example, to
`support@theebookedit.com`.

WordPress sends through PHP mail by default, which many hosts deliver poorly.
The usual fix is a free SMTP plugin such as WP Mail SMTP, pointed at a mailbox
you control.

**Credentials go in that plugin's settings screen on the live site and nowhere
else.** No password, app password, API key or mailbox secret belongs in this
repository, in a theme file, in `wp-config.php` committed to version control,
or in any file that leaves the server.

## 7. Metadata and search engines

`inc/seo-meta.php` prints, per page: the title, meta description, canonical
URL, Open Graph and Twitter tags, the brand icons, and the JSON-LD the
published site publishes — all read from `inc/seo-data.php` and all resolved
against `home_url()`.

* Privacy and Terms carry `noindex, follow`, as on the published site.
* `robots.txt` points at WordPress's own `/wp-sitemap.xml`.
* Setting a Site Icon in the Customizer replaces the bundled icons.

## 8. Security headers (optional, host-dependent)

The theme sets no HTTP headers. If your host lets you add them, these are the
ones the static site uses:

```
X-Content-Type-Options: nosniff
Referrer-Policy: strict-origin-when-cross-origin
X-Frame-Options: SAMEORIGIN
Permissions-Policy: geolocation=(), microphone=(), camera=()
```

Add a Content-Security-Policy only after testing: WordPress and its plugins
load inline scripts and styles that a strict policy will block.

## 9. What the setup screen changes

Under Appearance → The Ebook Edit Setup, and only when you click the button:

* creates missing page records (`get_page_by_path` first, so nothing is ever
  duplicated);
* creates the three Contact Form 7 forms if forms with those titles do not
  already exist;
* writes a shortcode into the Home and Contact pages **only when their content
  is empty**;
* sets Home as the static front page;
* flushes rewrite rules.

It never edits or deletes content you have written. The only removal is
opt-in: a tick-box that moves WordPress's own default "Sample Page" to Trash,
and only when that page is still the untouched default.

## 10. Still outstanding before launch

* Privacy and Terms need professional legal review before publishing. Their
  current text also names Netlify as the host and form processor. The Meta Ads
  landing page carries its own Privacy Policy and Terms views, which are
  separate wording and need the same review.
* Nothing outstanding for the landing page's images: they were converted to
  WebP (see §5).
* Confirm the enquiry notification address on both forms.
* Confirm mail delivery from the live host.
