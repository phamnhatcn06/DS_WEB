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

**Design system** (colors, typography scale, spacing, breakpoints, component specs for buttons/cards/tabs/carousel, and hover/scroll animation behavior) was originally specified in a Figma design-analysis document that no longer exists in this repo (it was deleted from disk outside of any Claude Code action, and there is no git history to recover it — the repo is not a git repository). Before implementing sections, ask the user for that document or the Figma file rather than inventing brand colors, spacing, or typography sizes — only the few values captured under "Key constraints" below are confirmed.

## Implementation Plan

The intended build order (see design doc for full detail per phase):

1. Design tokens & CSS reset (`variables.css`, base layout/grid utilities)
2. Reusable components (buttons, typography, cards, filter tabs, carousel controls)
3. Header (sticky, glassmorphism-on-scroll) and Footer (CTA banner + 4-column links)
4. All 10 homepage sections, section by section
5. JavaScript interactivity: carousel autoplay/controls, news filter tabs, scroll fade-in
6. Responsive pass (1920/1440/1024/768/375px) + SEO (meta tags, semantic heading hierarchy, alt text) + performance pass

## Key constraints to preserve when implementing

- Colors, typography, and spacing must come from the design tokens, not ad-hoc values — brand red `#9a1220`, gold accent `#c9a84c`, dark navy `#080f1d` backgrounds are the core palette.
- Responsive scaling uses `clamp()` for fluid typography/spacing rather than fixed breakpoint overrides where possible.
- Section fade-in-on-scroll uses `IntersectionObserver`, not scroll-event polling.
