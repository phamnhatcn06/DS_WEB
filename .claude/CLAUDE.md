# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Static HTML/CSS/JS marketing website for **Đông Sơn Holdings (DSH)**, built from a Figma design (canvas `1920px` wide). No frontend framework, bundler, or package manager is used — this is plain HTML/CSS/JS served as static files.

Current state: the repo is a fresh scaffold. `index.html` and `assets/css/`, `assets/js/`, `assets/images/` exist but are empty — none of the pages/sections below have been implemented yet.

## Commands

There is no `package.json`, build tool, linter, or test runner in this repo. Development is:

- **Preview**: open `index.html` directly in a browser, or serve the directory with any static file server (e.g. `python -m http.server` from the project root) so relative asset paths resolve correctly.
- **Asset pipeline**: `download_and_convert_assets.py` pulls image assets referenced by an external Figma/Antigravity export (`http://localhost:3845/assets/...` URLs found in a local Antigravity IDE "steps" directory), converts raster images to `.webp`, and copies `.svg` files as-is into `assets/images/`. This only works when that local export tool is running and generating step files; it is not a general-purpose downloader. Run with `python download_and_convert_assets.py`.

## Architecture

The site is a single long-scrolling homepage (`OP1 3` in Figma) composed of 10 stacked sections, plus a shared Header and Footer. When implementing, build one section/component at a time as separate CSS partials (or clearly delimited blocks) rather than one monolithic stylesheet, since the page is very tall (~11,300px design height) and content-heavy.

**Planned file structure** (per the implementation plan below):
- `assets/css/variables.css` — design tokens (colors, fonts, spacing) as CSS custom properties
- `assets/css/` — component and section stylesheets
- `assets/js/` — carousel/slider controller, news filter tabs, scroll-triggered fade-in (IntersectionObserver)
- `assets/images/` — exported/converted image assets

**Section order on the homepage** (each is its own visual block, roughly in this sequence): Hero slider → BOT/infrastructure highlight → About/vision-mission → Business area pillars (3) → Featured projects → Company stats → Featured projects detail → Partners/shareholders → News with category filter tabs → Footer with CTA banner.

The Figma source is the **MOODBOARD** page. The chosen homepage is **OPTION 01** (Figma section node `12:11`, inside section "ĐỀ XUẤT GIAO DIỆN" `24:22`). Three other options exist (`17:759`, `23:3`, `24:17`) — ignore them. A separate "PHÂN TÍCH VÀ ĐỀ XUẤT" section (`13:46`) holds the rationale.

## Design Analysis (OPTION 01 — homepage `12:11`)

> **Figma extraction status (2026-07-23):** Live per-node token extraction from the Figma desktop MCP is currently **blocked**: every OPTION is a Figma *section* node and the MCP returns a "sparse" response for sections (it will not enumerate the child frame IDs, so `get_design_context` cannot be called on the inner frames); node screenshots render **pure black** because the design's image fills reference an offline `localhost:3845` asset server; and `get_variable_defs` returns `{}` (the file has **no bound Figma variables** — colors/sizes are hardcoded fills). To extract exact hex/px values, **open the Figma desktop app and select an inner frame** (not the OPTION 01 section), then re-run `get_design_context` / `get_variable_defs` on that frame. Until then, the tokens below are: brand colors = **confirmed**; the supporting palette, type scale, and spacing = **recommended defaults** to be reconciled against Figma once a frame is selectable.

### Design tokens

**Colors — confirmed brand palette:**
| Token | Hex | Use |
|-------|-----|-----|
| `--dsh-red` | `#9a1220` | Primary brand red — buttons, accents, active tab underline, CTA banner |
| `--dsh-gold` | `#c9a84c` | Gold accent — hairlines, stat numbers, decorative dividers, hover states |
| `--dsh-navy` | `#080f1d` | Dark navy — primary section backgrounds, footer |

**Colors — recommended supporting palette (reconcile with Figma):**
| Token | Hex (suggested) | Use |
|-------|-----|-----|
| `--dsh-navy-2` | `#0f1a2e` | Elevated cards/panels on navy |
| `--dsh-white` | `#ffffff` | Text/headings on navy |
| `--dsh-muted` | `#c7ccd6` | Body/secondary text on navy |
| `--dsh-line` | `rgba(201,168,76,.25)` | Gold hairline borders |

**Typography — recommended (Google Fonts):** a serif display for headings (e.g. **Playfair Display** or **Cormorant** for the corporate/premium feel) paired with a clean sans for body (**Be Vietnam Pro** — full Vietnamese diacritics support — or **Inter**). Fluid scale via `clamp()`:
| Role | `clamp()` (min → max) |
|------|-----------------------|
| Hero H1 | `clamp(2.5rem, 5vw, 4.5rem)` |
| Section H2 | `clamp(1.75rem, 3.5vw, 3rem)` |
| Card/H3 | `clamp(1.125rem, 2vw, 1.5rem)` |
| Body | `clamp(0.95rem, 1.1vw, 1.125rem)` |
| Eyebrow/label | `0.8125rem`, letter-spacing `.12em`, uppercase |
| Stat number | `clamp(2.5rem, 5vw, 4rem)` |

**Spacing scale (recommended, 8px base):** `4 / 8 / 16 / 24 / 32 / 48 / 64 / 96 / 128 px`. Section vertical padding: `clamp(64px, 8vw, 128px)`. Content max-width container ≈ `1320px` (Bootstrap `.container`), design canvas 1920px.

### Section-by-section breakdown (→ Bootstrap mapping)

| # | Section | Purpose | Layout | Bootstrap mapping |
|---|---------|---------|--------|-------------------|
| — | **Header** | Sticky nav, glassmorphism-on-scroll | Logo left, nav center/right, CTA + lang switch | `.navbar .navbar-expand-lg .fixed-top`, `.container`, collapse toggler on `< lg` |
| 1 | **Hero slider** | Brand statement + rotating feature slides | Full-bleed navy, headline + CTA over image, slide dots/arrows | `#carousel` (Bootstrap Carousel), `.container` overlay, `.btn` |
| 2 | **BOT / infrastructure highlight** | Flagship infrastructure story | Two-column: image + text | `.row`, `.col-lg-6` (stack on `< lg`) |
| 3 | **About / vision–mission** | Company intro, vision & mission | Heading + 2–3 value blocks | `.row .g-4`, `.col-md-6 / col-lg-4` |
| 4 | **Business area pillars** | 3 core business domains | 3 equal cards with icon/title/desc | `.row .row-cols-1 .row-cols-md-3`, `.card` |
| 5 | **Featured projects** | Showcase key projects | Grid or carousel of project cards | `.row .g-4`, `.col-md-6 .col-lg-4`, `.card` |
| 6 | **Company stats** | Key numbers (years, projects, capital…) | Horizontal band of 3–4 counters | `.row .text-center`, `.col-6 .col-md-3`, gold stat numbers |
| 7 | **Featured projects detail** | Deep-dive on a marquee project | Alternating image/text rows | repeated `.row` with `.flex-lg-row-reverse` |
| 8 | **Partners / shareholders** | Logos of partners & shareholders | Responsive logo grid | `.row .row-cols-2 .row-cols-md-4 / md-6`, grayscale logos |
| 9 | **News + category filter tabs** | Latest news with category filtering | Tab bar + card grid | `.nav .nav-pills` (filter), `.row .g-4`, `.card`; JS filter |
| — | **Footer** | CTA banner + 4-column links + copyright | Full-width red/navy CTA band above dark footer | `.container`, `.row`, `.col-lg-3` × 4 |

### Header & Footer specs

- **Header:** transparent over the hero at scroll top; on scroll adds a translucent navy + blur "glassmorphism" background (toggle a class via a small scroll listener or IntersectionObserver sentinel). Contains logo, primary nav links, a `--dsh-red` CTA button, and a VI/EN language switch. Collapses to a hamburger (`.navbar-toggler`) below `lg`.
- **Footer:** a prominent CTA banner (brand-red background, headline + button) sits directly above a 4-column dark-navy footer (company info/logo, quick links, business areas, contact). Bottom bar: copyright + social icons. Gold hairline (`--dsh-line`) separates banner from columns.

### Responsive behavior

- Desktop (`≥ lg` / 992px+): full multi-column grids as designed (3–4 cols).
- Tablet (`md` / 768–991px): 2-column grids; two-column image+text sections keep side-by-side or begin stacking; nav collapses to hamburger.
- Mobile (`< md` / < 768px): everything stacks to a single column (`col-12`); hero text scales down via `clamp()`; stats wrap to 2-per-row (`col-6`); partner logos 2-per-row; news tabs scroll horizontally if needed.
- Prefer fluid `clamp()` typography/spacing over hard breakpoint overrides; use Bootstrap's responsive column classes (`col-`, `col-md-`, `col-lg-`) for structural changes.

## Implementation Plan

The intended build order (see design doc for full detail per phase):

1. Design tokens & CSS reset (`variables.css`, base layout/grid utilities)
2. Reusable components (buttons, typography, cards, filter tabs, carousel controls)
3. Header (sticky, glassmorphism-on-scroll) and Footer (CTA banner + 4-column links)
4. All 10 homepage sections, section by section
5. JavaScript interactivity: carousel autoplay/controls, news filter tabs, scroll fade-in
6. Responsive pass (1920/1440/1024/768/375px) + SEO (meta tags, semantic heading hierarchy, alt text) + performance pass

## Key constraints to preserve when implementing

- **Implementation approach is Bootstrap 5.3 (CDN)** — see `.claude/rules/frontend-bootstrap.md` for the mandatory coding rules (grid/utilities/components first, minimal custom CSS, CDN `<head>` + end-of-body scripts, semantic HTML, FontAwesome/Bootstrap Icons).
- Colors, typography, and spacing must come from the design tokens, not ad-hoc values — brand red `#9a1220`, gold accent `#c9a84c`, dark navy `#080f1d` backgrounds are the confirmed core palette. Supporting palette/type/spacing values in the Design Analysis above are recommended defaults; reconcile against Figma once an inner frame is selectable (see extraction-status note).
- Responsive scaling uses `clamp()` for fluid typography/spacing rather than fixed breakpoint overrides where possible, layered on top of Bootstrap's responsive column classes.
- Section fade-in-on-scroll uses `IntersectionObserver`, not scroll-event polling.
