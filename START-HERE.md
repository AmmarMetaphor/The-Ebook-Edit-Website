# START HERE — The Ebook Edit website

This folder contains a complete static website that can be edited with Claude Code and deployed to Netlify. It has no framework and no required build command.

## What is included
- Home page
- Services hub
- Writing, editing, and publishing service pages
- Process, portfolio, about, insights, contact, privacy, terms, thank-you, and 404 pages
- Three full starter insight articles
- Responsive navigation and accessible styling
- Netlify contact form
- Netlify configuration, security headers, robots file, and sitemap
- `CLAUDE.md` with permanent instructions for Claude Code

## Replace before launch
1. Add your real business email and service area on the Contact page and footer.
2. Replace representative portfolio examples with approved, verified work when available.
3. Add only real testimonials with written permission.
4. Review pricing/budget ranges in the contact form.
5. Have Privacy and Terms reviewed for the actual business and jurisdiction.
6. Replace every `https://www.theebookedit.com` URL in `sitemap.xml` if your final domain is different.
7. Add the final absolute sitemap URL to `robots.txt`.
8. Set a real response-time promise on `thank-you.html`.

## Preview locally
Open this folder in Claude Code and ask:

> Start a local preview server for this static website. Do not change any files. Tell me the exact local URL to open.

Alternatively, from a terminal in this folder run:

```bash
python -m http.server 8000
```

Then open `http://localhost:8000`. Stop the server with Ctrl+C.

## Recommended first Claude prompt

> Read CLAUDE.md and audit the entire website. Do not redesign it yet. First list every placeholder that must be replaced before launch, every broken link or accessibility problem, and every page where the content can be made more persuasive without inventing claims. Then wait for my approval.

## Netlify settings
- Build command: leave blank
- Publish directory: `.`
- Production branch: `main`
- Forms: enable automatic form detection after the first deploy

## Important
Never upload private client manuscripts, passwords, payment information, or API keys to a public GitHub repository.
