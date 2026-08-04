# The Ebook Edit — Non-Technical Website Launch Guide

## The method

Use this workflow:

**Website folder → Claude Code → local preview → GitHub → Netlify Deploy Preview → merge to production → custom domain**

The website is a plain static site. It does not require React, npm packages, a database, or a build command.

---

## Phase 1 — Download and extract the website

1. Download `the-ebook-edit-website.zip`.
2. Open your Windows **Downloads** folder.
3. Right-click the ZIP file.
4. Click **Extract All**.
5. Choose a simple location, such as:
   `Documents\The Ebook Edit\`
6. Click **Extract**.
7. Open the extracted `the-ebook-edit-website` folder.
8. Confirm that you can see `index.html`, `CLAUDE.md`, `START-HERE.md`, `netlify.toml`, and the `assets` folder.

Do not move only `index.html`. The entire folder must stay together.

---

## Phase 2 — Open the project in Claude Code

### Easiest Windows method

1. Open the extracted website folder in File Explorer.
2. Click once in the address bar at the top of File Explorer.
3. Type `powershell`.
4. Press **Enter**.
5. A PowerShell window will open already pointed at the correct folder.
6. Type:

```powershell
claude
```

7. Press **Enter**.
8. If asked to sign in, follow the browser sign-in steps.
9. Confirm that the working directory shown by Claude is the extracted website folder.

### First prompt to paste into Claude Code

```text
Read CLAUDE.md and inspect the entire project. Do not change files yet. Explain the folder structure in plain English, list every page included, and list every placeholder that must be replaced before launch. Then wait for my approval.
```

---

## Phase 3 — Add your real business information

Prepare the following information before asking Claude to edit:

- Business contact email
- Country or service area
- Normal response time
- Final domain name, if purchased
- Services you definitely offer
- Services you do not offer
- Budget ranges you want on the form, or whether to remove them
- Real founder or team information for the About page
- Real portfolio projects you have permission to show
- Real testimonials with written permission
- Links to social media profiles

Paste this prompt after gathering the information:

```text
Read CLAUDE.md. Replace the launch placeholders with the business details below. Do not invent any missing facts, clients, testimonials, results, awards, experience, or pricing. Keep the existing blue, gold, coral, cyan, and white brand system. After editing, list every changed file and every placeholder that still remains.

BUSINESS DETAILS:
[Paste the information here]
```

Review Claude's proposed edits before approving them.

---

## Phase 4 — Review the website content page by page

Review in this order:

1. `index.html` — homepage
2. `services.html` — service overview
3. `writing.html` — writing and ghostwriting
4. `editing.html` — editing levels
5. `publishing.html` — formatting and publishing support
6. `process.html` — working process
7. `portfolio.html` — work examples
8. `about.html` — brand story
9. `insights.html` and the three article pages
10. `contact.html` — inquiry form
11. `privacy.html` and `terms.html`
12. `thank-you.html`

Use this content-review prompt:

```text
Read CLAUDE.md and review all website copy as a senior conversion copywriter and book editor. Keep the tone classy, warm, credible, and clear. Improve weak headings, repetition, vague claims, and calls to action. Do not invent proof, testimonials, results, or credentials. Show me a page-by-page change plan before editing.
```

After approving the plan, tell Claude:

```text
Apply the approved content plan. Preserve the existing design, accessibility, navigation, Netlify form attributes, and all factual limitations in CLAUDE.md. Then summarize every change.
```

---

## Phase 5 — Start a local preview

In Claude Code, paste:

```text
Start a local preview server for this static website. Do not modify any files. Tell me the exact local URL to open and how to stop the server.
```

Claude will normally provide a URL such as:

```text
http://localhost:8000
```

1. Hold **Ctrl** and click the local URL, or copy it into Chrome.
2. Check the homepage.
3. Click every menu item.
4. Reduce the browser width to check the mobile menu.
5. Do not expect the Netlify contact form to submit correctly from a local preview. Form handling is tested after deployment.
6. Return to the terminal and use **Ctrl+C** when you want to stop the preview server.

---

## Phase 6 — Ask Claude to perform a technical audit

Paste:

```text
Read CLAUDE.md. Audit the complete site before deployment. Check all internal links, missing images, duplicate IDs, form labels, alt text, keyboard navigation, mobile layout, page titles, meta descriptions, 404 behavior, robots.txt, sitemap.xml, Netlify configuration, and contact-form markup. Fix only confirmed problems. Do not redesign the site. Report every test and result.
```

Then preview again.

---

## Phase 7 — Create an empty GitHub repository

1. Sign in to GitHub.
2. Click the **+** button in the upper-right area.
3. Click **New repository**.
4. Repository name: `the-ebook-edit-website`
5. Description: `Official website for The Ebook Edit`
6. Choose **Private** while the site is being prepared.
7. Do not add a README, `.gitignore`, or license because the project already contains files.
8. Click **Create repository**.
9. Keep the GitHub page open. You will need the repository address.

Never place passwords, API keys, private client manuscripts, identity documents, or payment details in the repository.

---

## Phase 8 — Push the project to GitHub with Claude Code

Return to Claude Code and paste:

```text
Prepare this project for its first GitHub commit. First run git status and explain what will be included. Do not push anything until I approve. Do not include secrets, private manuscripts, temporary files, or preview screenshots.
```

After reviewing the file list, paste:

```text
Create the first commit with the message: Initial The Ebook Edit website. Then tell me exactly what you need from the empty GitHub repository before pushing.
```

Claude may ask for the repository URL. Copy it from GitHub and paste it.

A typical repository URL looks like:

```text
https://github.com/YOUR-USERNAME/the-ebook-edit-website.git
```

Then ask:

```text
Connect this local project to that GitHub repository and push the main branch. Stop and explain any authentication request rather than guessing.
```

Refresh GitHub after Claude confirms the push. The files should appear in the repository.

---

## Phase 9 — Connect GitHub to Netlify

1. Sign in to Netlify.
2. Open the team or project dashboard.
3. Click **Add new project**.
4. Click **Import an existing project**.
5. Choose **GitHub**.
6. Authorize Netlify if asked.
7. Select `the-ebook-edit-website`.
8. Production branch: `main`
9. Build command: leave blank.
10. Publish directory: `.`
11. Click **Publish** or **Deploy**.
12. Wait for the deploy status to become successful.
13. Open the generated `.netlify.app` address.

The included `netlify.toml` already tells Netlify to publish the project root.

---

## Phase 10 — Enable and test the contact form

1. Open the Netlify project.
2. Click **Forms**.
3. Click **Enable form detection**.
4. Trigger a new deploy. A small commit or **Retry deploy** can be used if needed.
5. Open the live Netlify website.
6. Go to **Contact**.
7. Submit a test inquiry using your own email address.
8. Confirm that the website opens `thank-you.html`.
9. Return to Netlify → **Forms**.
10. Confirm that the `project-inquiry` submission appears.
11. Configure an email notification for form submissions in the Forms settings.

Do not ask visitors to upload confidential manuscripts through the public form unless you deliberately add and secure a suitable file workflow.

---

## Phase 11 — Use Deploy Previews for future edits

Do not make major changes directly on the production branch.

Ask Claude Code:

```text
Create a new branch named improve-homepage-copy. Make only the approved homepage copy changes. Run the project checks, commit the changes, push the branch, and create a pull request. Do not merge it.
```

Then:

1. Open the pull request on GitHub.
2. Wait for Netlify to create a Deploy Preview.
3. Open the Deploy Preview URL.
4. Review desktop and mobile pages.
5. Ask Claude to correct any issues on the same branch.
6. Refresh the Deploy Preview after each push.
7. When satisfied, merge the pull request into `main`.
8. Netlify will deploy the merged version to the production website.

A Deploy Preview is not the live production site. The production site changes only after the branch is merged into `main`.

---

## Phase 12 — Connect the custom domain

1. Purchase the final domain from your preferred registrar or through Netlify.
2. In Netlify, open the project.
3. Go to **Domain management**.
4. Open **Production domains**.
5. Click **Add a domain**.
6. Choose to buy a domain or add a domain you already own.
7. Follow Netlify's DNS instructions exactly.
8. Wait for DNS verification.
9. Check that both the main domain and the desired `www` version lead to one canonical website.
10. Open **Domain management → HTTPS** and confirm that the certificate is active.

After the domain is confirmed, ask Claude:

```text
My final production domain is https://YOUR-DOMAIN.com. Replace the placeholder domain in sitemap.xml, add the correct absolute Sitemap line to robots.txt, and check whether any other production URL needs updating. Do not change page copy or design.
```

Commit, push, review the Deploy Preview, and merge.

---

## Phase 13 — Final launch checks

Use `LAUNCH-CHECKLIST.txt` and complete every item.

Also test these manually:

- Homepage loads with the correct logo
- Mobile menu opens and closes
- Every navigation item works
- Every service-page button works
- All insight articles open
- Contact form submits on the live site
- Thank-you page opens
- No placeholder business text remains accidentally
- Portfolio claims are real and approved
- Privacy and Terms match the actual business
- Domain and HTTPS work
- The production site is the merged `main` branch version

---

## Phase 14 — Submit the website to search engines

After the custom domain is live:

1. Create a Google Search Console property for the final domain.
2. Complete ownership verification.
3. Submit the sitemap URL, normally:
   `https://YOUR-DOMAIN.com/sitemap.xml`
4. Request indexing for the homepage and main service pages.
5. Add the final website URL to your social profiles and business listings.
6. Publish new, original insight articles consistently rather than creating many thin pages.

---

## Safe prompt for any future change

```text
Read CLAUDE.md before doing anything. Create a new branch for this task. Explain the change plan first. Preserve the brand colors, responsive design, accessibility, SEO metadata, Netlify Forms markup, and factual trust rules. Do not invent claims. After editing, test all affected pages and provide the Deploy Preview workflow. Do not merge to main without my instruction.
```

## Emergency rollback

If a merged change breaks the live site:

1. Open Netlify.
2. Open **Deploys**.
3. Find the last known good production deploy.
4. Use Netlify's restore/publish option for that deploy.
5. Create a new GitHub branch to fix the issue properly.
6. Review the fix in a Deploy Preview before merging again.
