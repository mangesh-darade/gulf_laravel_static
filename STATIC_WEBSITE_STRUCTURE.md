# Static website — directory structure & where to edit

Project root: `Gulf/`

---

## Images: single folder

**All landing images live in one place:** `public/images/`

- The **Laravel** site uses them via `{{ asset('images/...') }}` in `landing.blade.php` and via `url("../images/...")` in `public/css/gulf-landing.css` (paths are relative to the CSS file).
- The **static prototype** (`gulf-figma-static/`) loads the **same files** using relative URLs:
  - From `gulf-figma-static/index.html` → `../public/images/<filename>`
  - From `gulf-figma-static/assets/css/style.css` → `../../../public/images/<filename>`

There is **no** `gulf-figma-static/assets/images/` folder anymore (removed to avoid duplicates).

### Files referenced by the landing page (HTML)

| File | Used for |
|------|----------|
| `gulf-landing-logo.png` | Header logo (`landing.blade.php` + static `index.html`) |
| `gulf-landing-hero-subject.png` | Hero photo |
| `gulf-landing-category-recovery.png` | Category card |
| `gulf-landing-category-mobility.png` | Category card |
| `gulf-landing-category-elder-safety.png` | Category card |
| `gulf-landing-brand-caremax.png` | Brand row |
| `gulf-landing-brand-jmc.png` | Brand row |
| `gulf-landing-brand-apex.png` | Brand row |
| `gulf-landing-brand-futuro.png` | Brand row |
| `gulf-landing-brand-jobri.png` | Brand row |

### Files referenced by CSS only (`gulf-landing.css` / static `style.css`)

| File | Used for |
|------|----------|
| `gulf-landing-support-background.webp` | Support section background |
| `product-strip.png` | Product strip backgrounds |
| `promo-lifestyle.png` | Promo / lifestyle panels |

### Optional / design-only assets

Other files under `public/images/` (e.g. `gulf-category-section-reference.png`, `gulf-design-reference-desktop.png`, `.webp` variants) are for reference or future use; they are **not** required unless you wire them in HTML or CSS.

---

## 1. Standalone static prototype (`gulf-figma-static`)

Plain HTML/CSS/JS (e.g. Figma export). Open `gulf-figma-static/index.html` from the repo (same parent as `public/`) so `../public/images/` resolves correctly.

```
gulf-figma-static/
├── index.html
├── Desktop-1-mockup.png          # optional mockup at folder root (not loaded by page)
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── script.js
```

| Path | Edit for |
|------|----------|
| `gulf-figma-static/index.html` | Structure, copy, links |
| `gulf-figma-static/assets/css/style.css` | Styles (image URLs → `../../../public/images/`) |
| `gulf-figma-static/assets/js/script.js` | Client-side behavior |
| `public/images/` | **All** raster/SVG assets for this page and Laravel |

---

## 2. Laravel live site (`public` + Blade)

The app route `/` renders the `landing` view.

```
resources/views/
└── landing.blade.php             # markup, Blade, asset() URLs

public/
├── css/
│   └── gulf-landing.css          # loaded by landing.blade.php
├── js/
│   └── gulf-landing.js
└── images/                       # single image folder (see tables above)
```

| Path | Edit for |
|------|----------|
| `resources/views/landing.blade.php` | Page structure, text, `{{ asset('images/...') }}`, `{{ url('/') }}` |
| `public/css/gulf-landing.css` | Landing styles (`../images/...` in `url()`) |
| `public/js/gulf-landing.js` | Landing scripts |
| `public/images/` | Images |

**Routing:** `routes/web.php` — `Route::get('/', …)` returns `view('landing')`. Change only if you need a different URL or view name.

---

## Workflow

1. **Add or replace images** only under `public/images/`.
2. **Design / iterate** markup and copy in `gulf-figma-static/index.html`, then mirror into `resources/views/landing.blade.php`.
3. **Styles:** keep `gulf-figma-static/assets/css/style.css` and `public/css/gulf-landing.css` in sync (same rules; static CSS uses `../../../public/images/` for assets, Laravel CSS uses `../images/`).

Ensure every `asset('images/...')` in Blade has a matching file under `public/images/`.
