# CLAUDE.md — The Ebook Edit website

## Project goal
Maintain a polished, fast, accessible, multi-page static website for The Ebook Edit, an ebook writing, editing, formatting, and publishing-support brand.

## Non-negotiable brand rules
- Preserve the approved logo master at assets/images/brand/the-ebook-edit-logo.png (transparent). Never redraw, regenerate, or recolour it; derivatives (WebP, favicon, OG card) are produced from it.
- Core colors (derived from the approved logo, defined in styles.css :root): deep blue #003190, royal blue #0047B9, bright blue #0068F6 (sparing), cyan #0090C8 (sparing), gold #F5A70A, cream paper #FDF9EE, warm white #FFFDF8. Blue and cream stay dominant; gold is the main accent.
- Keep the visual style elegant, vibrant, spacious, and editorial—not childish or template-like.
- Use plain HTML, CSS, and JavaScript. Do not add a framework or build dependency unless explicitly requested.
- Keep the site deployable on Netlify with publish directory `.` and no build command.

## Content and trust rules
- Do not invent clients, testimonials, awards, years in business, sales figures, rankings, or bestseller claims.
- Portfolio examples must remain labeled “Representative project type” until replaced with verified work.
- Do not guarantee sales, platform acceptance, or bestseller status.
- Preserve the author’s voice and position the brand as thoughtful, clear, and rigorous.
- Legal pages are placeholders and must stay clearly labeled until reviewed.

## Technical rules
- Preserve semantic HTML, labels, alt text, keyboard navigation, visible focus styles, and reduced-motion support.
- Test every internal link after changes.
- Keep contact form attributes required by Netlify Forms.
- Do not put passwords, API keys, private manuscripts, or secrets in the repository.
- Update sitemap URLs after the final domain is confirmed.

## Before reporting completion
1. Check all HTML files for missing local links and images.
2. Check mobile layout at approximately 390px width.
3. Check desktop layout at approximately 1440px width.
4. Confirm the form still contains `data-netlify="true"`, a unique form name, and the hidden `form-name` field.
5. Summarize every file changed and any remaining placeholders.
