#!/usr/bin/env python3
"""Generate the WordPress theme templates from the approved static site.

The static files in the repository root are the single source of truth for the
website's design and copy. This script ports them into the classic theme in
wordpress/the-ebook-edit/ so the WordPress build and the Netlify build never
drift apart: run it after any change to the static pages, then rebuild the
release ZIP.

What it does per page:
  * takes the markup inside <main id="main"> … </main>
  * rewrites internal links to home_url() and asset paths to get_theme_file_uri()
  * swaps the two Netlify forms for the theme's Contact Form 7 slot
  * writes the matching page-*.php / front-page.php / 404.php template
  * collects <head> metadata into inc/seo-meta.php's data map

Everything else in the theme (functions.php, header.php, footer.php, the setup
utility) is hand-maintained and is not touched by this script.

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
    ("index.html", "front-page.php", "front"),
    ("services.html", "page-services.php", "services"),
    ("writing.html", "page-writing.php", "writing"),
    ("editing.html", "page-editing.php", "editing"),
    ("publishing.html", "page-publishing.php", "publishing"),
    ("process.html", "page-process.php", "process"),
    ("portfolio.html", "page-portfolio.php", "portfolio"),
    ("about.html", "page-about.php", "about"),
    ("insights.html", "page-insights.php", "insights"),
    ("contact.html", "page-contact.php", "contact"),
    ("thank-you.html", "page-thank-you.php", "thank-you"),
    ("privacy.html", "page-privacy.php", "privacy"),
    ("terms.html", "page-terms.php", "terms"),
    ("404.html", "404.php", "404"),
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
ASSET_TREES = ["assets/images/brand", "assets/images/portfolio"]

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


CF7_SLOT = """<?php
                  /*
                   * Contact Form 7 renders the enquiry form here. The static
                   * site posts to Netlify Forms, which WordPress has no
                   * equivalent of, so the form body — including its book page
                   * classes — is supplied by a CF7 form. Paste the markup from
                   * DEPLOYMENT.md into a form named "%s" and the page renders
                   * exactly as the static site does.
                   */
                  teebe_render_enquiry_form( '%s' );
                  ?>"""


def swap_forms(html: str) -> str:
    """Replace the Netlify forms with the theme's Contact Form 7 slot."""
    pattern = re.compile(r'<form class="(?:start|page)-form".*?</form>', re.S)

    def slot(m: re.Match) -> str:
        name = re.search(r'name="([a-z\-]+)"', m.group(0))
        key = name.group(1) if name else "project-inquiry"
        return CF7_SLOT % (key, key)

    return pattern.sub(slot, html)


# ---------------------------------------------------------------- CF7 forms
#
# The static site posts to Netlify Forms. WordPress has no equivalent, so each
# form is reproduced as a Contact Form 7 form whose body is the static form's
# own markup with the inputs swapped for CF7 tags. Everything around the
# controls — the .m-pg mobile page groups, .form-grid, .field wrappers and
# labels — is preserved verbatim, so the book page lays out identically.

CF7_FORMS = {
    "project-inquiry": {
        "title": "Project Inquiry",
        "html_class": "start-form",
        "html_id": "",
        "source": "contact.html",
    },
    "publishing-journey": {
        "title": "Publishing Journey",
        "html_class": "page-form",
        "html_id": "enquiry",
        "source": "index.html",
    },
}


def attrs_of(tag: str) -> dict:
    return dict(re.findall(r'([a-z\-]+)(?:="([^"]*)")?', tag))


def cf7_input(tag: str) -> str:
    a = attrs_of(tag)
    kind = "email" if a.get("type") == "email" else "text"
    star = "*" if "required" in tag else ""
    parts = ["%s%s %s" % (kind, star, a["name"])]
    if a.get("id"):
        parts.append("id:%s" % a["id"])
    if a.get("autocomplete"):
        parts.append("autocomplete:%s" % a["autocomplete"])
    return "[%s]" % " ".join(parts)


def cf7_select(block: str) -> str:
    open_tag = re.match(r"<select[^>]*>", block).group(0)
    a = attrs_of(open_tag)
    star = "*" if "required" in open_tag else ""
    options = re.findall(r"<option[^>]*>(.*?)</option>", block, re.S)
    parts = ["select%s %s" % (star, a["name"])]
    if a.get("id"):
        parts.append("id:%s" % a["id"])
    # The static markup's first option is an empty-valued prompt; CF7's
    # first_as_label reproduces exactly that.
    parts.append("first_as_label")
    parts += ['"%s"' % o.strip().replace('"', "&quot;") for o in options]
    return "[%s]" % " ".join(parts)


def cf7_textarea(block: str) -> str:
    open_tag = re.match(r"<textarea[^>]*>", block).group(0)
    a = attrs_of(open_tag)
    star = "*" if "required" in open_tag else ""
    # CF7 defaults a textarea to 10 rows, which is far taller than the book
    # page allows, so the row count from the static markup is always stated.
    rows = a.get("rows") or "2"
    parts = ["textarea%s %s 40x%s" % (star, a["name"], rows)]
    if a.get("id"):
        parts.append("id:%s" % a["id"])
    if a.get("placeholder"):
        parts.append('placeholder "%s"' % a["placeholder"].replace('"', "&quot;"))
    return "[%s]" % " ".join(parts)


def cf7_submit(block: str) -> str:
    open_tag = re.match(r"<button[^>]*>", block).group(0)
    a = attrs_of(open_tag)
    label = re.search(r">(.*?)</button>", block, re.S).group(1).strip()
    classes = " ".join("class:%s" % c for c in a.get("class", "").split())
    return '[submit %s "%s"]' % (classes, label)


def cf7_body(form_html: str) -> str:
    """Turn one static form into a Contact Form 7 form body."""
    body = re.sub(r"^<form[^>]*>|</form>$", "", form_html.strip())

    # Netlify-only plumbing.
    body = re.sub(r'\s*<input type="hidden" name="form-name"[^>]*>', "", body)
    body = re.sub(r'\s*<p class="hidden" aria-live="polite" id="form-status"></p>', "", body)

    # The honeypot the theme's wpcf7_spam filter checks.
    body = re.sub(
        r'<input name="bot-field"[^>]*>',
        "[text hp-field autocomplete:off]",
        body,
    )

    body = re.sub(r"<select\b.*?</select>", lambda m: cf7_select(m.group(0)), body, flags=re.S)
    body = re.sub(r"<textarea\b.*?</textarea>", lambda m: cf7_textarea(m.group(0)), body, flags=re.S)
    body = re.sub(r"<button\b.*?</button>", lambda m: cf7_submit(m.group(0)), body, flags=re.S)
    body = re.sub(r"<input\b[^>]*>", lambda m: cf7_input(m.group(0)), body)

    # A Contact Form 7 body cannot contain PHP, so internal links carry the
    # same {{home}} token the metadata uses; inc/setup.php resolves it against
    # home_url() when it creates the form.
    body = re.sub(r'href="/([a-z0-9\-/]*)"', r'href="{{home}}/\1"', body)

    # Re-indent: the body sits inside Contact Form 7's own <form> element.
    lines = [line for line in body.split("\n") if line.strip()]
    indent = min(
        (len(line) - len(line.lstrip()) for line in lines[1:] if line.strip()),
        default=0,
    )
    out = [lines[0].strip()]
    out += [line[indent:].rstrip() if len(line) > indent else line.strip() for line in lines[1:]]
    return "\n".join(out) + "\n"


def write_cf7_forms() -> None:
    dest = THEME / "cf7"
    dest.mkdir(parents=True, exist_ok=True)

    for key, form in CF7_FORMS.items():
        html = (ROOT / form["source"]).read_text(encoding="utf-8")
        match = re.search(
            r'<form class="%s".*?</form>' % re.escape(form["html_class"]), html, re.S
        )
        if not match:
            raise SystemExit("no %s form in %s" % (key, form["source"]))
        (dest / ("%s.txt" % key)).write_text(cf7_body(match.group(0)), encoding="utf-8")
        print("wrote cf7/%s.txt" % key)


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
    header += "get_header();\n?>\n\n"
    footer = "\n\n<?php\nget_footer();\n"
    target.write_text(header + body + footer, encoding="utf-8")


def main() -> None:
    seo: dict[str, dict] = {}
    canonical_paths = {
        "front": "/",
        "404": "",
    }

    for source_name, template_name, key in PAGES:
        source = ROOT / source_name
        html = source.read_text(encoding="utf-8")
        entry = head_meta(html)

        if key in canonical_paths:
            entry["path"] = canonical_paths[key]
        elif source_name.startswith("insights/"):
            entry["path"] = "/insights/%s/" % key
        else:
            entry["path"] = "/%s/" % key
        seo[key] = entry

        body = extract_main(html)
        body = swap_forms(body)
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

    write_cf7_forms()

    # ---- SEO data map -------------------------------------------------
    lines = [
        "<?php",
        "/**",
        " * Page metadata generated from the static site by",
        " * wordpress/sync-from-static.py. Do not hand-edit: change the static",
        " * page's <head> and re-run the script.",
        " *",
        " * @package the-ebook-edit",
        " */",
        "",
        "if ( ! defined( 'ABSPATH' ) ) {",
        "\texit;",
        "}",
        "",
        "/**",
        " * Metadata for every page, keyed by slug ('front' for the homepage,",
        " * '404' for the not-found template).",
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
