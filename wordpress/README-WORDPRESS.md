# The Ebook Edit — WordPress guide

This folder holds the WordPress version of the site.

| What | Where |
|---|---|
| Theme source | `wordpress/the-ebook-edit/` |
| Installable theme file | `wordpress/the-ebook-edit-wordpress-theme.zip` |
| Full install steps (pages, permalinks, Contact Form 7, headers) | `wordpress/the-ebook-edit/DEPLOYMENT.md` |

Install the theme with **Appearance → Themes → Add New → Upload Theme**, choose the
ZIP, then **Install Now → Activate**. Work through `DEPLOYMENT.md` once, in order,
then come back here for the two menus.

---

## 1. The primary menu

The header logo is the link back to the homepage, so the menu deliberately has
**no Home item**.

The navigation works the moment the theme is activated — the theme has a built-in
fallback list. Create a real menu when you want to be able to edit it yourself:

1. Go to **Appearance → Menus**.
2. Next to **Menu Name**, type: `Primary Navigation`
3. Click **Create Menu**.
4. Add these items, in this order, using **Pages** (or **Custom Links**) on the left:

   1. Services
   2. Process
   3. Portfolio
   4. About
   5. Insights
   6. Start a project → the **Contact** page

   Rename the Contact item to `Start a project`: open the item in the menu editor
   and type it into **Navigation Label**.

5. **Do not add Home.** The logo already links to the homepage, so a Home item
   would duplicate it.
6. Under **Menu Settings → Display location**, tick **Primary Navigation**.
7. Click **Save Menu**.

### Make "Start a project" the gold button

1. In **Appearance → Menus**, open **Screen Options** (top right).
2. Tick **CSS Classes**.
3. Open the **Start a project** item and type `nav-cta` into **CSS Classes**.
4. **Save Menu**.

### What stays automatic

- The current page is marked for screen readers (`aria-current`), including the
  service detail pages, which highlight **Services**, and the articles, which
  highlight **Insights**.
- The mobile **Menu** button, its open/closed state, and closing with the
  `Esc` key all keep working with a custom menu.
- Only the top level of the menu is displayed. Sub-items are ignored.

---

## 2. The social links menu

Social links only appear in the footer once you create this menu. Until then the
footer shows no social heading and no empty space — nothing is hard-coded.

1. Go to **Appearance → Menus → create a new menu**.
2. Name it exactly: `Social Links`
3. Click **Create Menu**.
4. Open **Custom Links** on the left and add **one entry per account you really
   have**. For each one:
   - **URL:** the full address, for example `https://www.linkedin.com/company/your-page`
   - **Link Text:** the platform name, for example `LinkedIn`
5. Typical entries: LinkedIn, Instagram, Facebook, YouTube, X.
6. Under **Menu Settings → Display location**, tick **Social Links**.
7. Click **Save Menu**.

Notes:

- Add only accounts that exist. Do not add placeholder or example URLs.
- Remove an item from the menu to remove it from the footer.
- If the menu is deleted or unassigned, the footer's social block disappears
  completely — it never leaves an empty heading or gap behind.
- The links are text labels in the site's own styling. No icon font, external
  script, or tracking library is loaded.

---

## 3. Publishing platform logos (optional)

The homepage has a **Publishing platforms** section listing:

Amazon Kindle Direct Publishing · Apple Books · Kobo Writing Life ·
Google Play Books · Barnes & Noble Press / NOOK · Draft2Digital

It ships as text tiles, because no approved logo files are included.

To use real logos later, put approved image files in:

```
wordpress/the-ebook-edit/assets/images/platforms/
```

Name each file after its platform slug, using `.svg`, `.png`, or `.webp`:

| File name | Tile |
|---|---|
| `amazon-kdp.svg` | Amazon Kindle Direct Publishing |
| `apple-books.svg` | Apple Books |
| `kobo-writing-life.svg` | Kobo Writing Life |
| `google-play-books.svg` | Google Play Books |
| `barnes-noble-press.svg` | Barnes & Noble Press / NOOK |
| `draft2digital.svg` | Draft2Digital |

The template picks up any file it finds and swaps that tile from text to an image
automatically — the tile keeps its size, so the layout does not move. Tiles with
no matching file stay as text, so a partial set is fine. Logos are lazy-loaded and
each one gets the platform name as its alt text.

**Before adding any logo:** check that platform's current brand or press asset
guidelines and use only files they publish for this purpose. Do not download or
recreate a logo from a search result. If in doubt, leave the text tiles in place —
they are accurate and carry no trademark risk.

The section also carries this note, which should stay: *"Platform names are shown
to describe compatible publishing-support services. The Ebook Edit is an
independent editorial service."* The Ebook Edit is not affiliated with, endorsed
by, or a partner of any of these companies.

The static reference site (`/index.html`) has the same section with a comment
showing the equivalent `<img>` markup.

---

## 4. What the theme does and does not do

Does:

- Header, footer, navigation, and every page layout from the original site.
- Page titles, meta descriptions, canonical URLs, Open Graph and Twitter tags,
  and structured data, all built from your WordPress site address.
- `robots.txt` pointing at the sitemap WordPress generates at `/wp-sitemap.xml`.
- A honeypot spam check for the Contact Form 7 form.
- Sticky header that stays clear of the WordPress admin toolbar when you are
  logged in.

Does not:

- Send email itself. Contact Form 7 handles the contact form; see `DEPLOYMENT.md`.
- Include Netlify files (`netlify.toml`, `_headers`). Security headers go in
  `.htaccess` — see `DEPLOYMENT.md` step 8.
- Require a page builder, block plugin, or JavaScript framework.
