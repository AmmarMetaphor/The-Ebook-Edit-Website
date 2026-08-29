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
  index.php  page.php       fallbacks for anything added later
  404.php                   not found
  inc/seo-data.php          page metadata, generated from the website
  inc/seo-meta.php          prints title, description, canonical, social, JSON-LD
  inc/setup.php             the Appearance → The Ebook Edit Setup screen
  cf7/*.txt                 the two Contact Form 7 form bodies
  assets/css/styles.css     design tokens, typography, buttons, forms  (generated)
  assets/css/book.css       the book presentation                      (generated)
  assets/css/wordpress.css  Contact Form 7 integration            (hand-maintained)
  assets/js/book.js         the book engine                            (generated)
  assets/images/            logo, icons, portfolio covers, favicon      (generated)
```

Generated files come from `wordpress/sync-from-static.py`, which reads the HTML
in the repository root. Do not hand-edit them: change the website and re-run
the script.

## 2. How a page is rendered

WordPress's template hierarchy does the routing. A page whose slug is
`services` is served by `page-services.php`; the homepage by `front-page.php`;
the four articles by the `template-insight-*.php` file assigned to each of
them.

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

## 4. The two enquiry forms

The published site posts to Netlify Forms, which WordPress has no equivalent
of, so Contact Form 7 renders the forms instead.

| Form title | Page | Form class | Body |
|---|---|---|---|
| Project Inquiry | Start a Project (`/contact/`) | `start-form` | `cf7/project-inquiry.txt` |
| Publishing Journey | Home (`/`) | `page-form`, id `enquiry` | `cf7/publishing-journey.txt` |

The setup screen creates both from those files, with every field, label,
dropdown option and page-splitting wrapper the published site uses, and writes
the matching shortcode into the page — for example:

```
[contact-form-7 id="12" title="Project Inquiry" html_class="start-form"]
```

`html_class` matters: it is what makes the form look like part of the book.

**Fields — Project Inquiry:** `name`*, `email`*, `service`*, `stage`*,
`word-count`, `referral`, `contact-method`, `timeline`, `message`, plus the
hidden honeypot `hp-field`.

**Fields — Publishing Journey:** `name`*, `email`*, `journey`*, `support`,
`message`, plus `hp-field`. (* = required.)

`functions.php` registers a `wpcf7_spam` filter that rejects any submission
where `hp-field` is filled in, which is how the honeypot works without a
plugin. It also disables Contact Form 7's automatic paragraph wrapping, because
the forms supply their own grid markup.

To rebuild a form by hand: Contact → Add New, set the title exactly as above,
paste the contents of the matching `cf7/*.txt` file into the **Form** tab
(replacing `{{home}}` with your site address), save, and put its shortcode on
the page with the right `html_class`.

## 5. Mail

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

## 6. Metadata and search engines

`inc/seo-meta.php` prints, per page: the title, meta description, canonical
URL, Open Graph and Twitter tags, the brand icons, and the JSON-LD the
published site publishes — all read from `inc/seo-data.php` and all resolved
against `home_url()`.

* Privacy and Terms carry `noindex, follow`, as on the published site.
* `robots.txt` points at WordPress's own `/wp-sitemap.xml`.
* Setting a Site Icon in the Customizer replaces the bundled icons.

## 7. Security headers (optional, host-dependent)

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

## 8. What the setup screen changes

Under Appearance → The Ebook Edit Setup, and only when you click the button:

* creates missing page records (`get_page_by_path` first, so nothing is ever
  duplicated);
* creates the two Contact Form 7 forms if forms with those titles do not
  already exist;
* writes a shortcode into the Home and Contact pages **only when their content
  is empty**;
* sets Home as the static front page;
* flushes rewrite rules.

It never edits or deletes content you have written. The only removal is
opt-in: a tick-box that moves WordPress's own default "Sample Page" to Trash,
and only when that page is still the untouched default.

## 9. Still outstanding before launch

* Privacy and Terms need professional legal review before publishing. Their
  current text also names Netlify as the host and form processor.
* Confirm the enquiry notification address on both forms.
* Confirm mail delivery from the live host.
