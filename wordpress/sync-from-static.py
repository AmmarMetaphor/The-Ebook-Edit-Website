#!/usr/bin/env python3
"""Generate the Insights templates from the static site.

The static files in the repository root were once the source of truth for the
whole website. They no longer are. The approved design
(theebookeditcompletewebsite-refined (4).html) replaced the website's pages,
and those templates — front-page.php, page-services.php and the rest — are
hand-maintained from it. This script must never overwrite them, so they are
not in PAGES below.

What it still generates is the Insights library: the hub and its four
articles, which the approved design does not cover and which keep the earlier
book presentation, its published URLs and its content. Run it after any change
to insights.html or insights/*.html, then rebuild the release ZIP.

What it does per page:
  * takes the markup inside <main id="main"> … </main>
  * rewrites internal links to home_url() and asset paths to get_theme_file_uri()
  * writes the matching template
  * collects <head> metadata into inc/seo-data.php's data map, where
    inc/site.php then overrides the entries for the redesigned pages

Everything else in the theme is hand-maintained and is not touched here: the
website (page-*.php, front-page.php, 404.php, header.php, footer.php,
inc/site.php, assets/css/site.css, assets/js/site.js), the Meta Ads landing
page (template-landing-meta-ads.php, inc/landing.php, assets/css/landing.css,
assets/js/landing.js, assets/images/landing/), analytics (inc/analytics.php,
assets/js/analytics.js), the HighLevel forms (assets/js/ghl-forms.js and
their definitions in inc/site.php), and the setup utility.

Note that the asset step replaces assets/images/brand wholesale, which is why
the approved artwork shared by the website and the landing page lives in
assets/images/landing/.

Usage:  python3 wordpress/sync-from-static.py
"""

from __future__ import annotations

import json
import re
import shutil
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
THEME = ROOT / "wordpress" / "the-ebook-edit"

# static file -> (template file, seo key)
PAGES = [
    ("insights.html", "page-insights.php", "insights"),
    ("insights/turn-expertise-into-an-ebook.html",
     "template-insight-turn-expertise.php", "turn-expertise-into-an-ebook"),
    ("insights/editing-levels-explained.html",
     "template-insight-editing-levels.php", "editing-levels-explained"),
    ("insights/pre-publishing-checklist.html",
     "template-insight-pre-publishing.php", "pre-publishing-checklist"),
    ("insights/kindle-and-ebook-platform-guide.html",
     "template-insight-kindle-platforms.php", "kindle-and-ebook-platform-guide"),
]

# "Template Name" headers the setup utility assigns to the article pages.
TEMPLATE_NAMES = {
    "template-insight-turn-expertise.php": "Insight — Turn Expertise Into an Ebook",
    "template-insight-editing-levels.php": "Insight — Editing Levels Explained",
    "template-insight-pre-publishing.php": "Insight — Pre-Publishing Checklist",
    "template-insight-kindle-platforms.php": "Insight — Kindle and Platforms",
}

ASSET_DIRS = [
    ("assets/css", ("styles.css", "book.css")),
    ("assets/js", ("book.js",)),
]
# assets/images/portfolio is no longer copied: the approved website uses the
# artwork in assets/images/landing/, and nothing in the theme referenced the
# portfolio directory any more. Note that this step replaces the trees it does
# copy wholesale, which is why the approved artwork does not live in one.
ASSET_TREES = ["assets/images/brand"]

PHP_OPEN = "<?php\n"


def php_home(path: str) -> str:
    return "<?php echo esc_url( home_url( '%s' ) ); ?>" % path


def php_asset(rel: str) -> str:
    return "<?php echo esc_url( get_theme_file_uri( '%s' ) ); ?>" % rel


def rewrite_urls(html: str) -> str:
    """Point every repository-relative URL at WordPress."""
    # assets first, so /assets/... is not caught by the route rule below
    html = re.sub(
        r'(src|href)="/(assets/[^"]+?)(\?v=[^"]*)?"',
        lambda m: '%s="%s"' % (m.group(1), php_asset(m.group(2))),
        html,
    )
    # the home link
    html = html.replace('href="/"', 'href="%s"' % php_home("/"))
    # form endpoints
    html = html.replace('action="/thank-you"', 'action="%s"' % php_home("/thank-you/"))
    # internal routes, including query strings (/contact?service=…) and
    # in-page anchors (/publishing#formatting)
    def route(m: re.Match) -> str:
        path, query, frag = m.group(1), m.group(2) or "", m.group(3) or ""
        return 'href="%s%s%s"' % (php_home("/%s/" % path.strip("/")), query, frag)

    html = re.sub(
        r'href="/([a-z0-9\-/]+?)(\?[^"#]*)?(#[a-z0-9\-]+)?"', route, html
    )
    return html


def drop_netlify_comments(html: str) -> str:
    """Remove build notes that only apply to the Netlify deployment."""
    return re.sub(
        r"[ \t]*<!--(?:(?!-->).)*?Netlify.*?-->\n?", "", html, flags=re.S
    )


def extract_main(html: str) -> str:
    m = re.search(r'<main id="main">(.*?)</main>', html, re.S)
    if not m:
        raise SystemExit("no <main> found")
    return m.group(1).strip("\n")


STATIC_ORIGIN = "https://theebookedit.com"


def tokenize(value):
    """Swap the static site's origin for a token the theme resolves at render.

    The published WordPress site may live on any domain, so no absolute URL is
    baked into the theme: inc/seo-meta.php replaces {{home}} with home_url().
    """
    if isinstance(value, str):
        if value.startswith(STATIC_ORIGIN):
            return "{{home}}" + value[len(STATIC_ORIGIN):]
        return value
    if isinstance(value, list):
        return [tokenize(v) for v in value]
    if isinstance(value, dict):
        return {k: tokenize(v) for k, v in value.items()}
    return value


def head_meta(html: str) -> dict:
    def meta(pattern: str) -> str:
        m = re.search(pattern, html)
        return m.group(1) if m else ""

    entry = {
        "title": meta(r"<title>(.*?)</title>"),
        "description": meta(r'<meta name="description" content="([^"]*)"'),
        "og_type": meta(r'<meta property="og:type" content="([^"]*)"') or "website",
    }
    if 'name="robots" content="noindex' in html:
        entry["noindex"] = True

    body = re.search(r'<body class="([^"]*)"', html)
    entry["body_class"] = body.group(1) if body else "book-home"
    # cinematic pages carry the full pre-paint boot script
    entry["cinematic"] = "book-cinematic" in html
    # only the homepage preloads the cover logo
    entry["preload_logo"] = 'rel="preload"' in html

    schemas = []
    for m in re.finditer(
        r'<script type="application/ld\+json">(.*?)</script>', html, re.S
    ):
        raw = m.group(1).strip()
        try:
            schemas.append(tokenize(json.loads(raw)))
        except json.JSONDecodeError:
            continue
    entry["schema"] = schemas
    return entry


def php_value(value) -> str:
    """Render a Python value as PHP source."""
    if isinstance(value, bool):
        return "true" if value else "false"
    if isinstance(value, (int, float)):
        return str(value)
    if isinstance(value, str):
        return "'" + value.replace("\\", "\\\\").replace("'", "\\'") + "'"
    if isinstance(value, list):
        inner = ", ".join(php_value(v) for v in value)
        return "array( %s )" % inner
    if isinstance(value, dict):
        inner = ", ".join(
            "%s => %s" % (php_value(k), php_value(v)) for k, v in value.items()
        )
        return "array( %s )" % inner
    raise TypeError(type(value))


def write_template(target: Path, key: str, body: str) -> None:
    header = PHP_OPEN
    if target.name in TEMPLATE_NAMES:
        header += "/**\n * Template Name: %s\n *\n * @package the-ebook-edit\n */\n\n" % TEMPLATE_NAMES[target.name]
    else:
        header += "/**\n * %s — generated from the static site by wordpress/sync-from-static.py.\n * Edit the static page and re-run the script; do not hand-edit this file.\n *\n * @package the-ebook-edit\n */\n\n" % key
    header += "get_header( 'book' );\n?>\n\n"
    footer = "\n\n<?php\nget_footer( 'book' );\n"
    target.write_text(header + body + footer, encoding="utf-8")


def main() -> None:
    seo: dict[str, dict] = {}

    for source_name, template_name, key in PAGES:
        source = ROOT / source_name
        html = source.read_text(encoding="utf-8")
        entry = head_meta(html)

        if source_name.startswith("insights/"):
            entry["path"] = "/insights/%s/" % key
        else:
            entry["path"] = "/%s/" % key
        seo[key] = entry

        body = extract_main(html)
        body = drop_netlify_comments(body)
        body = rewrite_urls(body)
        write_template(THEME / template_name, key, body)
        print("wrote", template_name)

    # ---- assets -------------------------------------------------------
    for rel_dir, names in ASSET_DIRS:
        dest = THEME / rel_dir
        dest.mkdir(parents=True, exist_ok=True)
        for name in names:
            shutil.copy2(ROOT / rel_dir / name, dest / name)
    for tree in ASSET_TREES:
        dest = THEME / tree
        if dest.exists():
            shutil.rmtree(dest)
        shutil.copytree(ROOT / tree, dest)
    shutil.copy2(ROOT / "favicon.ico", THEME / "assets/images/favicon.ico")
    print("assets copied")

    # ---- SEO data map -------------------------------------------------
    lines = [
        "<?php",
        "/**",
        " * Insights page metadata, generated from the static site by",
        " * wordpress/sync-from-static.py. Do not hand-edit: change the static",
        " * page's <head> and re-run the script.",
        " *",
        " * The approved website's own pages are not here. Their metadata is",
        " * hand-maintained in inc/site.php, which replaces any entry in this",
        " * map for a slug the website now serves.",
        " *",
        " * @package the-ebook-edit",
        " */",
        "",
        "if ( ! defined( 'ABSPATH' ) ) {",
        "\texit;",
        "}",
        "",
        "/**",
        " * Metadata for the Insights pages, keyed by slug.",
        " *",
        " * @return array<string, array<string, mixed>>",
        " */",
        "function teebe_seo_data() {",
        "\treturn array(",
    ]
    for key, entry in seo.items():
        lines.append("\t\t%s => array(" % php_value(key))
        for field in ("title", "description", "path", "og_type", "noindex",
                      "body_class", "cinematic", "preload_logo", "schema"):
            if field in entry and entry[field] not in ("", [], None):
                lines.append("\t\t\t%s => %s," % (php_value(field), php_value(entry[field])))
        lines.append("\t\t),")
    lines += ["\t);", "}", ""]
    (THEME / "inc" / "seo-data.php").write_text("\n".join(lines), encoding="utf-8")
    print("wrote inc/seo-data.php")


if __name__ == "__main__":
    main()
