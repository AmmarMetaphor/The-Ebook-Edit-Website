# The Ebook Edit — WordPress deployment guide

This folder holds the WordPress version of the website: the approved design,
the Meta Ads landing funnel, the enquiry forms, campaign attribution and the
site's analytics, all in one installable theme.

**You never have to re-type the website into WordPress.** Every page — Home,
Services, Book Writing, Book Editing, Book Publishing, Process, Portfolio,
About, Contact, Book a Free Consultation, Thank You, Privacy Policy, Terms &
Conditions, and the Insights library with its four articles — already lives
inside the theme. The setup screen only creates the WordPress page *records*
so those pages answer at the right web addresses.

| What | Where |
|---|---|
| Installable theme file | `wordpress/the-ebook-edit-wordpress-theme.zip` |
| Checksum for that file | `wordpress/the-ebook-edit-wordpress-theme.zip.sha256` |
| Theme source | `wordpress/the-ebook-edit/` |
| Detailed reference (form fields, mail, security headers) | `wordpress/the-ebook-edit/DEPLOYMENT.md` |
| Script that regenerates the Insights templates | `wordpress/sync-from-static.py` |
| Offline renderer for checking the theme | `wordpress/verify-theme.php` |

---

## Before you start

You need three things, all free:

1. A WordPress site you can log in to as an administrator (EasyWP, or any
   other host — nothing here is EasyWP-specific, and **no paid EasyWP add-on,
   including EasyWP Backup, is required**).
2. The file `the-ebook-edit-wordpress-theme.zip` from this folder. On GitHub,
   open the file and click **Download raw file**.
3. About twenty minutes.

**Take a copy of your site first.** If your host offers a free snapshot or
staging site, use it. If it does not, the free **All-in-One WP Migration**
plugin (Plugins → Add New → search for it → Install → Activate → All-in-One WP
Migration → Export → Export to File) makes a downloadable backup at no cost.
This is worth doing even though nothing in this theme deletes your content.

---

## Install it in seven steps

### 1. Upload the theme

Dashboard → **Appearance → Themes → Add New → Upload Theme** → **Choose File**
→ pick `the-ebook-edit-wordpress-theme.zip` → **Install Now** → **Activate**.

The site will look wrong until step 4 — that is expected.

### 2. Set the web address format

**Settings → Permalinks** → choose **Post name** → **Save Changes**.

Do this *before* step 4, or the page addresses below will not work.

### 3. Install the form plugin

**Plugins → Add New**, search for **Contact Form 7**, then **Install Now** →
**Activate**.

You do not need to create anything in it — step 4 builds both enquiry forms for
you, using the exact fields the published website uses.

### 4. Run the setup

**Appearance → The Ebook Edit Setup** → click **Set up The Ebook Edit
website**.

One click does all of this:

* Creates the page record for every page of the website, each with empty
  content, because the design and the words come from the theme.
* Creates the two Contact Form 7 forms — **Project Inquiry** (the Start a
  Project page) and **Publishing Journey** (the form on the homepage) — with
  every field, dropdown option and label from the published site, and connects
  each one to its page.
* Sets **Home** as the front page, so you do not need to visit Settings →
  Reading.
* Leaves **Privacy Policy** and **Website Terms** as *drafts*, because their
  wording still needs professional legal review. The results screen says so.

The results screen then lists exactly what was created, what already existed
and was left alone, and anything that still needs your attention.

**It is safe to press the button again.** Nothing is duplicated, and nothing
you have written is changed or deleted. The single optional removal is the
tick-box on that screen, which moves WordPress's own default "Sample Page" to
Trash — off unless you tick it, and only ever applied to the untouched default
page.

### 5. Check the pages

Visit each address and confirm it looks like the published site:

| Page | Address |
|---|---|
| Home | `/` |
| Services | `/services/` |
| Book Writing | `/writing/` |
| Book Editing | `/editing/` |
| Book Publishing | `/publishing/` |
| Process | `/process/` |
| Portfolio | `/portfolio/` |
| About | `/about/` |
| Contact | `/contact/` |
| Book a Free Consultation | `/book-consultation/` |
| Thank You | `/thank-you/` |
| Privacy Policy | `/privacy-policy/` |
| Terms & Conditions | `/terms-and-conditions/` |
| Insights | `/insights/` |
| Insights articles | `/insights/turn-expertise-into-an-ebook/` and the three others |

The older `/privacy/` and `/terms/` addresses redirect to the new ones, so any
link already out in the world still works.

The Meta Ads landing page is not in this list: it is not part of the website
and is published separately in step 6b below.

Check one page on a phone as well as on a computer. The Insights library keeps
the earlier book presentation: on a wide screen it opens as a two-page spread,
on a phone it becomes a single portrait page, and with "reduce motion" switched
on it becomes a plain readable column. All three are correct.

### 6. Test the enquiry forms

Fill in the form on `/contact/` and submit it, then do the same on the
homepage. Each should take you to `/thank-you/` **only after** the enquiry is
actually delivered, and an email should arrive at
`support@theebookedit.com`.

Try an incomplete form too: it should stay where it is and show the message
under the field, and it must **not** reach the Thank You page.

If no email arrives, that is a mail-delivery question, not a theme problem:
most hosts need an SMTP plugin (WP Mail SMTP is free) pointed at a mailbox you
control. **Enter those mailbox details in the plugin's own settings screen on
the live site. Never write a password, app password or API key into this
repository, into a theme file, or into any file you commit.** See
`DEPLOYMENT.md` §9.

### 6b. Publish the Meta Ads landing page (optional)

The advertising landing page is **not** part of the website and is not created
by the setup screen, so nothing about your existing pages changes if you never
publish it. When you want it live:

1. **Pages → Add New.**
2. Title it **Start Your Book**.
3. Set the address you want to advertise. In the editor sidebar, open **Page**
   → **URL** and set the slug to `start-your-book`.
4. In the sidebar under **Page Attributes → Template**, choose
   **The Ebook Edit — Meta Ads Landing Page**.
5. **Publish.**
6. Open `https://theebookedit.com/start-your-book/`.

It needs no thank-you page of its own: a delivered enquiry and every call to
action on it both go to `/thank-you/`, the website's own Thank You page, which
carries the booking calendar. That is deliberate — there is one Thank You
experience for the whole site.

Its enquiry form, **Start Your Book**, is created by the setup in step 4 and
goes to `support@theebookedit.com`. The page finds it on its own — there is no
shortcode to paste. If you see "Enquiry form not configured yet" on the page,
Contact Form 7 is missing or the setup has not been run; do steps 3 and 4 and
reload.

### 7. Point the domain at it

Only once every page above passes, connect `theebookedit.com`. Then, in
**Settings → Reading**, make sure **Discourage search engines** is *unticked*.

---

## Before you go live

Five of these are outside WordPress and none of them is done by installing the
theme. `DEPLOYMENT.md` §13 has the same list with the exact steps.

* **Cookie consent.** This is a UK-facing site and no consent plugin is
  installed. Install one that supports the WordPress Consent API, then
  categorise Google Analytics 4 (`G-EQFMTN2WJF`) and Microsoft Clarity
  (`yl7loe6vel`) as **Statistics**, and the Meta Pixel (`1492057326110606`)
  as **Marketing** — it is advertising technology, not measurement, and the
  theme asks for the two consents separately. The theme then honours the
  visitor's choice automatically. Until then all three load on every visit.
  (`DEPLOYMENT.md` §5.)
* **HighLevel post-booking redirect.** In the calendar settings, set the
  redirect after a confirmed booking to
  `https://theebookedit.com/thank-you/?conversion=appointment_booked`.
  Without it, no booked consultation is ever recorded as a conversion.
  (`DEPLOYMENT.md` §6.)
* **HighLevel meeting location and reminders.** Set the Zoom meeting as the
  calendar's meeting location and turn on the 24-hour and 1-hour reminders.
  The meeting link is private and is deliberately not in this repository.
  (`DEPLOYMENT.md` §6.)
* **Mail delivery.** Configure SMTP and send a test through all three forms.
* **Have the legal pages reviewed.** The Privacy Policy and Terms are the
  approved copy from the design, and the Privacy Policy now names the
  analytics actually in use. A solicitor should still read both before launch.
* **Set the site icon** (Appearance → Customize → Site Identity → Site Icon).
  Until you do, the theme uses the brand favicon bundled with it.

---

## Keeping the theme in step

The website's templates were ported once from the approved design and are
ordinary theme source: edit them directly. Only the Insights library is still
generated. When `insights.html` or `insights/*.html` changes, run:

```
python3 wordpress/sync-from-static.py
```

That regenerates the Insights templates and their metadata, then rebuild the
ZIP:

```
cd wordpress && rm -f the-ebook-edit-wordpress-theme.zip \
  && zip -r -X the-ebook-edit-wordpress-theme.zip the-ebook-edit \
  && sha256sum the-ebook-edit-wordpress-theme.zip > the-ebook-edit-wordpress-theme.zip.sha256
```

The script touches only the Insights templates, `inc/seo-data.php` and the
book assets. Everything else is hand-maintained and is **not** overwritten:

* the website — `front-page.php`, `page-*.php`, `404.php`, `header.php`,
  `footer.php`, `inc/site.php`, `assets/css/site.css`, `assets/js/site.js`;
* the Meta Ads landing page — `template-landing-meta-ads.php`,
  `inc/landing.php`, `assets/css/landing.css`, `assets/js/landing.js`;
* analytics and attribution — `inc/analytics.php`, `inc/attribution.php`,
  `assets/js/analytics.js`, `assets/js/attribution.js`;
* the WordPress plumbing — `functions.php`, `inc/setup.php`, `cf7/*.txt`;
* the approved artwork in `assets/images/landing/`.

To check the result without a WordPress install:

```
php wordpress/verify-theme.php /tmp/preview
```

This renders every template to plain HTML files you can open in a browser, or
diff against the previous release to see exactly what a change did.

---

## What the theme does and does not do

**It does:**

* Reproduce the approved design exactly — the same markup, the same
  stylesheet, the same behaviour, on real WordPress pages at real URLs.
* Carry the approved Meta Ads landing page as a page template you can assign
  to a page of your choosing, with its own design and its own enquiry form,
  without touching any existing page.
* Measure the funnel with Google Analytics 4, Microsoft Clarity and the Meta
  Pixel, and record which campaign produced each enquiry — without sending
  anything personal to any of them.
* Derive every address from your WordPress site address, so it works on a
  staging domain and on the live domain with no edits.
* Bundle all of its own images, fonts-free CSS and JavaScript, so it needs no
  external service at page-render time.
* Work with Contact Form 7 for all three enquiry forms, and with Flamingo and
  WP Mail SMTP when they are installed.

**It does not:**

* Store the website's design or words in the WordPress editor. Editing a page
  in WordPress will not change what visitors see — edit the template instead.
* Install or require any paid plugin, paid host feature, or page builder.
* Send email itself, or store any mail credentials, API key or meeting link.
* Add a cookie banner, or override one you already have.
* Delete or rewrite content you have created.
