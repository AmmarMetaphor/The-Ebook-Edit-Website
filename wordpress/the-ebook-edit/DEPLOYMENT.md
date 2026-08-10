# The Ebook Edit — WordPress setup guide

> **Steps 3, 4, and 7 below (creating the pages, setting the homepage, and the
> Primary Navigation menu) are now automated.** Install the theme, set
> permalinks, then go to **Appearance → The Ebook Edit Setup** and click
> **Set up The Ebook Edit website**. See `../README-WORDPRESS.md` for the full
> quick-start. This file remains as background on what that automation does,
> and for the Contact Form 7 field markup if you want to build the form by
> hand.

Follow these steps once, in order, on the Namecheap EasyWP site.

---

## 1. Install the theme

**Appearance → Themes → Add New → Upload Theme** → choose `the-ebook-edit.zip` → **Install Now** → **Activate**.

## 2. Set permalinks

**Settings → Permalinks → Post name → Save Changes.**

This must be done before creating pages, or the URLs below will not work.

## 3. Create the pages

**Pages → Add New** for each row. Leave the body empty (the design lives in the theme) except where noted. The **slug** must match exactly — it is what selects the design and the SEO metadata.

| Page title | Slug | Parent | Page template |
|---|---|---|---|
| Home | `home` | — | (default) |
| Services | `services` | — | (default) |
| Ebook writing | `writing` | — | (default) |
| Editing | `editing` | — | (default) |
| Publishing | `publishing` | — | (default) |
| Process | `process` | — | (default) |
| Portfolio | `portfolio` | — | (default) |
| About | `about` | — | (default) |
| Insights | `insights` | — | (default) |
| Contact | `contact` | — | (default) |
| Privacy | `privacy` | — | (default) |
| Terms | `terms` | — | (default) |
| Thank you | `thank-you` | — | (default) |

Then the four articles, each with **Insights** as its parent (set under **Page Attributes → Parent**) and a template chosen from **Page Attributes → Template**:

| Page title | Slug | Parent | Page template |
|---|---|---|---|
| How to turn your expertise into an ebook | `turn-expertise-into-an-ebook` | Insights | Insight — Turn Expertise Into an Ebook |
| Editing levels explained | `editing-levels-explained` | Insights | Insight — Editing Levels Explained |
| Pre-publishing checklist | `pre-publishing-checklist` | Insights | Insight — Pre-Publishing Checklist |
| Publishing an ebook on Kindle and other platforms | `kindle-and-ebook-platform-guide` | Insights | Insight — Kindle and Platform Guide |

The articles then live at `/insights/turn-expertise-into-an-ebook/` and so on, matching the old addresses.

## 4. Set the homepage

**Settings → Reading → Your homepage displays → A static page → Homepage: Home.** Leave "Posts page" unset.

## 5. Set the site icon

**Appearance → Customize → Site Identity → Site Icon** → upload `favicon.ico` (in the theme's `assets/images/` folder). Until this is set, the theme falls back to the bundled icon automatically.

## 6. Contact form (Contact Form 7)

### 6a. Install

**Plugins → Add New** → search "Contact Form 7" → **Install** → **Activate**.

### 6b. Create the form

**Contact → Add New**, name it `Project inquiry`, and replace the contents of the **Form** tab with exactly this:

```
<p class="hidden"><label>Do not fill this out: [text hp-field]</label></p>
<div class="form-grid">
  <div class="field"><label for="name">Name *</label>[text* your-name id:name autocomplete:name]</div>
  <div class="field"><label for="email">Email *</label>[email* your-email id:email autocomplete:email]</div>
  <div class="field"><label for="service">Primary service *</label>[select* service id:service first_as_label "Choose one" "Ebook writing" "Developmental editing" "Copy editing" "Proofreading" "Formatting" "Publishing support" "End-to-end project" "Not sure yet"]</div>
  <div class="field"><label for="word-count">Approximate word count</label>[select word-count id:word-count first_as_label "Choose a range" "Idea only / no draft" "Under 10,000 words" "10,000–25,000 words" "25,000–50,000 words" "50,000–80,000 words" "More than 80,000 words"]</div>
  <div class="field field-full"><label for="timeline">Target timeline</label>[text timeline id:timeline placeholder "For example: launch in November"]</div>
  <div class="field field-full"><label for="message">Project details *</label>[textarea* message id:message placeholder "Describe the book, intended reader, current stage, desired support, and any important deadline."]</div>
  <div class="field field-full">[submit class:button class:button-primary "Send project inquiry"]<p class="form-note">By submitting, you agree that The Ebook Edit may use this information to respond to your inquiry. Do not send confidential manuscripts until a suitable file-sharing method has been agreed.</p></div>
</div>
```

The `hp-field` at the top is a spam trap. It is hidden from people by CSS; the theme automatically rejects any submission that fills it in.

### 6c. Mail tab

- **To:** `info@theebookedit.com`
- **From:** `The Ebook Edit <wordpress@yourdomain.com>` — replace `yourdomain.com` with the real site domain. Do **not** put the visitor's address here; some hosts refuse to send mail that claims to come from another domain.
- **Subject:** `New project inquiry from [your-name]`
- **Additional headers:** `Reply-To: [your-email]`
- **Message body:**

```
Name: [your-name]
Email: [your-email]
Primary service: [service]
Approximate word count: [word-count]
Target timeline: [timeline]

Project details:
[message]
```

### 6d. Put the form on the page

Save the form and copy the shortcode Contact Form 7 shows at the top (it looks like `[contact-form-7 id="123" title="Project inquiry"]`). Edit the **Contact** page, paste that shortcode as the entire page body, and update. The theme wraps it in the correct panel automatically.

After a successful send, visitors are sent to the **Thank you** page automatically — no extra plugin or setting is needed.

### 6e. Test it

Submit a real inquiry from the live site and confirm the email arrives at `info@theebookedit.com` and the browser lands on `/thank-you/`. If no email arrives, the host is likely blocking PHP mail — install an SMTP plugin (for example WP Mail SMTP) and configure it with the mailbox credentials.

## 7. Menus

The header navigation works immediately with no setup. To make it editable instead, go to **Appearance → Menus**, create a menu named `Primary Navigation`, add Services / Process / Portfolio / About / Insights and then Contact (labelled "Start a project"), and tick the **Primary Navigation** display location.

Do **not** add a Home item — the header logo is the link to the homepage.

To style the "Start a project" item as the gold button: in **Menus**, open **Screen Options** at the top right, tick **CSS Classes**, then type `nav-cta` into that item's CSS Classes box.

Footer social links come from a second menu named `Social Links`, assigned to the **Social Links** location. Add Custom Links only for accounts that really exist. Until that menu is assigned, the footer shows no social heading and no empty space.

Step-by-step instructions for both menus, and for adding approved publishing-platform logos, are in `README-WORDPRESS.md` alongside this theme folder in the project repository.

## 8. Security headers

The old host applied security headers through a file WordPress does not read. Add these to the `.htaccess` file in the WordPress root instead, **above** the `# BEGIN WordPress` line:

```apache
<IfModule mod_headers.c>
  Header set X-Frame-Options "DENY"
  Header set X-Content-Type-Options "nosniff"
  Header set Referrer-Policy "strict-origin-when-cross-origin"
  Header set Permissions-Policy "camera=(), microphone=(), geolocation=()"
</IfModule>
```

## 9. Search engines

WordPress publishes a sitemap at `/wp-sitemap.xml` on its own, and the theme points `robots.txt` at it. Once the final domain is live, submit `https://yourdomain.com/wp-sitemap.xml` in Google Search Console.

Check that **Settings → Reading → Search engine visibility** is **unticked**, or nothing will be indexed.

---

## Still outstanding before launch

These were flagged on the old site and remain unresolved — they are content decisions, not technical ones.

- **Privacy and Terms** are unreviewed placeholder text and are set to `noindex`. Have them reviewed for the real business, and add the registered business address.
- **Portfolio** entries are labelled "Representative project type" and must stay that way until replaced with real, permissioned client work.
- The **thank-you page** promises a reply within 24 hours. Confirm that is accurate.
