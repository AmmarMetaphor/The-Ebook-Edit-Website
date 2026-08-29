# The Ebook Edit — WordPress deployment guide

This folder holds the WordPress version of the website. It is the **same
website** as the one in this repository, not a rebuild: the theme's templates
are generated from the very same HTML files that Netlify publishes, so the two
stay in step.

**You never have to re-type the website into WordPress.** Every page — the
homepage book, Services, Ebook Writing, Editing, Publishing, Process,
Portfolio, About, Insights and its four articles, Start a Project, Privacy,
Terms and Thank You — already lives inside the theme. The setup screen only
creates the WordPress page *records* so those pages answer at the right web
addresses.

| What | Where |
|---|---|
| Installable theme file | `wordpress/the-ebook-edit-wordpress-theme.zip` |
| Checksum for that file | `wordpress/the-ebook-edit-wordpress-theme.zip.sha256` |
| Theme source | `wordpress/the-ebook-edit/` |
| Detailed reference (form fields, mail, security headers) | `wordpress/the-ebook-edit/DEPLOYMENT.md` |
| Script that regenerates the theme from the website | `wordpress/sync-from-static.py` |

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
| Ebook Writing | `/writing/` |
| Editing | `/editing/` |
| Publishing | `/publishing/` |
| Process | `/process/` |
| Portfolio | `/portfolio/` |
| About | `/about/` |
| Insights | `/insights/` |
| Insights articles | `/insights/turn-expertise-into-an-ebook/` and the three others |
| Start a Project | `/contact/` |
| Thank You | `/thank-you/` |
| Privacy / Terms | `/privacy/` and `/terms/` (drafts — preview them while logged in) |

Check one page on a phone as well as on a computer. On a wide screen the book
opens as a two-page spread; on a phone it becomes a single portrait page you
scroll through; if a visitor has "reduce motion" switched on, it becomes a
plain readable column. All three are correct.

### 6. Test the enquiry forms

Fill in the Start a Project form on `/contact/` and submit it. You should see
Contact Form 7's confirmation, and an email should arrive.

If no email arrives, that is a mail-delivery question, not a theme problem:
most hosts need an SMTP plugin (WP Mail SMTP is free) pointed at a mailbox you
control. **Enter those mailbox details in the plugin's own settings screen on
the live site. Never write a password, app password or API key into this
repository, into a theme file, or into any file you commit.** See
`DEPLOYMENT.md` §5 for where to change the address the enquiries are sent to
(it starts as your WordPress administrator email).

### 7. Point the domain at it

Only once every page above passes, connect `theebookedit.com`. Then, in
**Settings → Reading**, make sure **Discourage search engines** is *unticked*,
and re-check that Privacy and Terms are still drafts if their wording has not
been reviewed yet.

---

## Before you go live

* **Have the legal pages reviewed.** Privacy and Terms are starter wording, not
  reviewed legal text, and the theme deliberately leaves them unpublished. The
  privacy page also names **Netlify** as the host and form processor — true of
  the current published site, and something to update as part of that review if
  WordPress becomes the live site.
* **Set the site icon** (Appearance → Customize → Site Identity → Site Icon).
  Until you do, the theme uses the brand favicon bundled with it.
* **Check the notification address** on both forms under Contact → Contact
  Forms → *(form)* → Mail.

---

## Keeping WordPress and the published site in step

The theme is generated, not hand-written. When the website in this repository
changes, run:

```
python3 wordpress/sync-from-static.py
```

That regenerates every `page-*.php` template, the page metadata, the two form
bodies and the stylesheets from the current HTML, then rebuild the ZIP:

```
cd wordpress && rm -f the-ebook-edit-wordpress-theme.zip \
  && zip -r -X the-ebook-edit-wordpress-theme.zip the-ebook-edit \
  && sha256sum the-ebook-edit-wordpress-theme.zip > the-ebook-edit-wordpress-theme.zip.sha256
```

Two files in the theme are hand-maintained and are **not** overwritten by the
script: `functions.php` / `header.php` / `footer.php` / `inc/setup.php` (the
WordPress plumbing) and `assets/css/wordpress.css` (which makes Contact Form
7's markup match the design). Everything else is regenerated.

To check the result without a WordPress install:

```
php wordpress/verify-theme.php /tmp/preview
```

This renders every template to plain HTML files you can open or diff against
the matching page in the repository root.

---

## What the theme does and does not do

**It does:**

* Reproduce the published website exactly — the same markup, the same
  stylesheets, the same book engine, the same page metadata and structured
  data.
* Derive every address from your WordPress site address, so it works on a
  staging domain and on the live domain with no edits.
* Bundle all of its own images, fonts-free CSS and JavaScript, so it needs no
  external service at page-render time.
* Work with Contact Form 7 for the two enquiry forms.

**It does not:**

* Store the website's design or words in the WordPress editor. Editing a page
  in WordPress will not change what visitors see — change the HTML in this
  repository and re-run the sync script instead.
* Install or require any paid plugin, paid host feature, or page builder.
* Send email itself, or store any mail credentials.
* Delete or rewrite content you have created.
