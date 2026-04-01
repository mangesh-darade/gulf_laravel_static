# Gulf Laravel Static — Project Documentation

This document explains the **gulf_laravel_static** codebase for developers who are new to Laravel or to this repository. It reflects the state of the project as audited from the repository files.

---

## 1. Project overview

### What the project does

This is a **Laravel 5.4** application that serves a **single marketing / e‑commerce style landing page** for **Gulf Pharmacy**. The homepage promotes mobility, home health, beauty, and wellness products. Product data is **not loaded from the database**; it is defined in a PHP config file and rendered into HTML by Blade. **Filtering, search, sort, cart counter, and modals** run in the browser with **vanilla JavaScript** (`public/js/gulf-landing.js`).

### Main purpose

- Present a polished **static-style landing experience** inside Laravel (asset URLs, optional `.env` for links, shared hosting–friendly structure).
- Showcase a **filterable product catalog** backed by a **curated feed** (`config/gulf_catalog.php`).
- Provide a **“Chat with a Pharmacist”** call-to-action whose URL is configurable (e.g. WhatsApp or phone).

### Key modules / features

| Feature | Implementation style |
|--------|----------------------|
| Homepage | `routes/web.php` → closure → `resources/views/landing.blade.php` |
| Product catalog data | `config/gulf_catalog.php` → `config('gulf_catalog.landing_products')` in Blade |
| Catalog UI behavior | `public/js/gulf-landing.js` + `public/css/gulf-landing.css` |
| Pharmacist chat link | `config/gulf_landing.php` ← `GULF_PHARMACIST_CHAT_URL` in `.env` |
| Design reference (standalone HTML) | `gulf-figma-static/` (optional; not routed by Laravel) |
| Default Laravel auth scaffolding | Controllers under `app/Http/Controllers/Auth/` (**no routes registered** in this project) |

---

## 2. Tech stack

### Backend

| Item | Version / note |
|------|----------------|
| **Laravel** | `5.4.*` (`composer.json`) |
| **PHP** | `>= 5.6.4` per `composer.json` (repo scripts reference PHP **5.6.40** on WAMP) |
| **Composer packages (runtime)** | `laravel/framework`, `laravel/tinker` |
| **Composer packages (dev)** | `fzaninotto/faker`, `mockery/mockery`, `phpunit/phpunit` |

### Frontend (as used by the landing page)

| Technology | Role |
|------------|------|
| **Blade** | Server-rendered HTML for the landing page |
| **Plain CSS** | `public/css/gulf-landing.css` (not compiled from Mix for this page) |
| **Plain JavaScript** | `public/js/gulf-landing.js` (no Vue/React on the landing page) |
| **Google Fonts** | Montserrat (linked from `landing.blade.php`) |

### Frontend (Laravel Mix scaffold — default app)

| Technology | Role |
|------------|------|
| **Laravel Mix 1.x** | Webpack wrapper (`webpack.mix.js`) |
| **Vue 2** | Example component in `resources/assets/js/components/Example.vue` |
| **jQuery / Bootstrap Sass** | Present in `package.json`; compiled to `public/js/app.js` and `public/css/app.css` if you run Mix |
| **Axios, Lodash** | Bundled with default `resources/assets/js/app.js` pipeline |

The **landing page does not use** the compiled `app.js` / `app.css` by default; it loads `gulf-landing.css` and `gulf-landing.js` directly.

---

## 3. Folder structure explanation

Below is the **meaning** of each important folder at the project root. Folders like `vendor/` and `node_modules/` are third-party dependencies (not documented line-by-line).

| Path | Purpose (simple words) |
|------|-------------------------|
| **`app/`** | Your PHP application code: models, HTTP kernel, middleware, controllers, console kernel, exception handler, service providers. |
| **`bootstrap/`** | Bootstraps the framework (`app.php`, autoload). |
| **`config/`** | PHP configuration arrays: database, mail, session, and **custom** `gulf_*.php` files. |
| **`database/`** | Migrations, seeds, factories for the database. |
| **`public/`** | **Web root** — `index.php`, CSS/JS/images served to browsers. Point Apache/Nginx here. |
| **`resources/`** | Blade views, raw assets (Sass/JS/Vue) before Mix compiles them, language files. |
| **`routes/`** | Defines URLs: `web.php` (browser), `api.php` (JSON API prefix), `console.php`, `channels.php`. |
| **`storage/`** | Logs, compiled Blade views, file sessions, cache (writable by the web server). |
| **`tests/`** | PHPUnit tests. |
| **`gulf-figma-static/`** | Standalone static HTML/CSS copy of the design (Figma reference); useful for design comparison, not part of Laravel routing. |
| **`tools/`** | Empty in this repo; reserved for scripts or helpers. |

### `app/` (deeper)

| Path | Purpose |
|------|---------|
| `app/User.php` | Eloquent model for users table (default Laravel). |
| `app/Http/Kernel.php` | Registers global middleware and `web` / `api` groups. |
| `app/Http/Middleware/` | Cookie encryption, CSRF, trim strings, guest redirect, etc. |
| `app/Http/Controllers/` | Base `Controller.php` + **Auth** controllers (unused without routes). |
| `app/Providers/` | Service providers (routes, events, auth). |
| `app/Console/Kernel.php` | Scheduled tasks and Artisan command registration. |
| `app/Exceptions/Handler.php` | Global exception reporting and rendering. |

### `resources/`

| Path | Purpose |
|------|---------|
| `resources/views/` | Blade templates (`landing.blade.php`, `welcome.blade.php`). |
| `resources/assets/js/` | Default Laravel + Vue entry (`app.js`, `bootstrap.js`). |
| `resources/assets/sass/` | Default app styles (`app.scss`). |
| `resources/lang/` | Translation strings (English by default). |

### `database/`

| Path | Purpose |
|------|---------|
| `migrations/` | Creates `users` and `password_resets` tables (stock Laravel). |
| `seeds/DatabaseSeeder.php` | Empty of custom seeds. |
| `factories/ModelFactory.php` | Model factories for testing. |

### `storage/`

| Path | Purpose |
|------|---------|
| `framework/views/` | Compiled Blade templates (generated). |
| `framework/sessions/` | File-based sessions if configured. |
| `logs/` | Application log files. |

---

## 4. Routes analysis

### `routes/web.php`

| Method | URI | Handler | What it does |
|--------|-----|---------|----------------|
| `GET` | `/` | Closure | Returns the `landing` Blade view (`view('landing')`). |

**There is no `POST` route for `/`.** The newsletter form in `landing.blade.php` uses `method="post"` and `action="{{ url('/') }}#newsletter"` — see **Security notes**.

### `routes/api.php`

All routes in this file are prefixed with **`/api`** (see `RouteServiceProvider`).

| Method | URI (full) | Middleware | Handler | What it does |
|--------|------------|------------|---------|----------------|
| `GET` | `/api/user` | `api` + `auth:api` | Closure | Returns the authenticated API user (`$request->user()`). |

There are **no custom API endpoints** for the landing catalog.

### Other route files

| File | Role |
|------|------|
| `routes/console.php` | Defines an Artisan `inspire` demo command. |
| `routes/channels.php` | Broadcast channel authorization (default `App.User.{id}`). |

### Route → controller map

| Route | Controller |
|-------|------------|
| `GET /` | **None** (closure in `web.php`) |
| `GET /api/user` | **None** (closure in `api.php`) |

The **Auth controllers** in `app/Http/Controllers/Auth/` are **not mapped** because `Auth::routes()` (or equivalent manual routes) are **not** present in `web.php`.

---

## 5. Controllers breakdown

### `app/Http/Controllers/Controller.php`

| Purpose | Base controller for the app. |
|--------|-------------------------------|
| **Traits** | `AuthorizesRequests`, `DispatchesJobs`, `ValidatesRequests` |

No custom public methods beyond what the base class provides.

### Auth controllers (present but **unrouted**)

These are **stock Laravel 5.4** scaffolding. They rely on **traits** from `Illuminate\Foundation\Auth` for most behavior.

#### `LoginController.php`

| Purpose | User login / logout. |
|--------|----------------------|
| **Trait** | `AuthenticatesUsers` |
| **Notable** | `$redirectTo = '/home'`; `guest` middleware except `logout`. |
| **Typical actions** (from trait) | Show login form, process login, logout. |

#### `RegisterController.php`

| Purpose | User registration. |
|--------|---------------------|
| **Trait** | `RegistersUsers` |
| **Notable** | `$redirectTo = '/home'`; defines `validator()` and `create()` for new `User` records. |

#### `ForgotPasswordController.php`

| Purpose | Email reset link. |
|--------|-------------------|
| **Trait** | `SendsPasswordResetEmails` |

#### `ResetPasswordController.php`

| Purpose | Password reset form submission. |
|--------|----------------------------------|
| **Trait** | `ResetsPasswords` |

**Handover note:** To use these controllers, you must **register routes** (e.g. `Auth::routes();` in `web.php` or explicit `Route::get/post(...)`) and add views such as `auth/login.blade.php` and a **home** route/view for `$redirectTo`.

---

## 6. Models and database

### Models

| Model file | Table (expected) | Notes |
|------------|------------------|--------|
| `app/User.php` | `users` | Standard `Authenticatable`; fillable: `name`, `email`, `password`. |

**No other Eloquent models** exist in `app/`.

### Relationships

The `User` model has **no custom relationships** defined. The landing catalog does **not** use Eloquent.

### Migrations and schema (simple terms)

| Migration | Creates | Main columns |
|-----------|---------|----------------|
| `2014_10_12_000000_create_users_table.php` | `users` | `id`, `name`, `email` (unique), `password`, `remember_token`, timestamps |
| `2014_10_12_100000_create_password_resets_table.php` | `password_resets` | `email`, `token`, `created_at` |

**Product catalog data** is **not** in the database; it lives in **`config/gulf_catalog.php`** as PHP arrays, then exposed as `config('gulf_catalog.products')` and `config('gulf_catalog.landing_products')`.

---

## 7. Views (UI flow)

### Blade files

| File | Role |
|------|------|
| `resources/views/landing.blade.php` | **Entire** Gulf landing: hero, stats, categories, brands, catalog, promo, newsletter, footer, product modal. **No** `@extends` layout — standalone HTML document. |
| `resources/views/welcome.blade.php` | Default Laravel welcome page (**not** used by current `web.php`). |

### Layouts, components, includes

- The landing page is **self-contained** (no shared `layouts/app.blade.php` in this project).
- Optional duplicate/reference files may exist (e.g. copies for design iteration); the **canonical** template is `landing.blade.php`.

### Page flow (user journey)

1. User opens **`/`** → sees hero, trust stats, category cards, brand marquee, orthopedic section.
2. User scrolls to **`#catalog`** or follows links with query `?cats=mobility,orthopedic` etc. → JavaScript pre-checks category filters from the URL.
3. User filters by brand, category, price, rating; searches; sorts → **client-side** only (DOM filtering).
4. User clicks a product → **modal** opens (details from `data-*` attributes).
5. **Add to cart** → increments a **client-side** counter only (no checkout API in this repo).
6. **Newsletter form** → POSTs to `/` (see security / routing gap).

### Config used inside the view

- `config('gulf_landing.pharmacist_chat_url')` for the hero CTA.
- `config('gulf_catalog.landing_products', …)` for catalog rows and dynamic filter labels.

---

## 8. Business logic flow

### Actual flow for this project

```
HTTP GET /
    → routes/web.php (closure)
    → return view('landing')
    → Blade renders HTML using config('gulf_catalog.*') and config('gulf_landing.*')
    → Browser loads gulf-landing.js / gulf-landing.css
    → All catalog interactions happen in the browser (no Controller → Model for products)
```

### What is *not* implemented

- No server-side cart, checkout, or orders.
- No admin panel for products.
- No API that returns JSON catalog data.

### If you add classic Laravel flows later

A typical pattern would be: **Route → Controller → Model (Eloquent) → View**, plus **validation** and **authentication** middleware. This repo is intentionally minimal on the server side for the storefront portion.

---

## 9. Configuration

### Important config files

| File | Why it matters |
|------|----------------|
| `config/app.php` | App name, env, debug, timezone, locale, providers, aliases. |
| `config/database.php` | DB connections (MySQL, etc.). |
| `config/gulf_catalog.php` | **Product feed**, helper functions, computed `$products` / `$landingProducts` arrays returned to `config()`. |
| `config/gulf_landing.php` | **Pharmacist chat URL** from env. |
| `config/auth.php` | Guards, providers (for future auth). |
| `config/session.php` | Session driver (often `file` in `.env`). |
| `config/filesystems.php` | Disk paths for storage. |
| `config/mail.php` | SMTP / mail driver. |

### `.env` usage

Copy `.env.example` to `.env`, then run `php artisan key:generate`.

| Variable (examples) | Purpose |
|---------------------|---------|
| `APP_KEY` | Encryption and signed cookies (required). |
| `APP_URL` | Base URL for URL generation (`asset()`, etc.). |
| `APP_DEBUG` | Verbose errors when `true` (**turn off in production**). |
| `GULF_PHARMACIST_CHAT_URL` | WhatsApp (`https://wa.me/...`), `tel:+971...`, or chat URL. |
| `DB_*` | Database connection if you use migrations / `User` auth. |

**Note:** `composer.json` **post-install** scripts in this repo reference a **hard-coded Windows path** to `php.exe`. Other machines may need to adjust scripts or run `php artisan` manually.

---

## 10. Custom implementations

### `config/gulf_catalog.php`

- **`$catalogFeedRows`**: Raw rows (code, name, RSP, final+VAT, promo, categories, brand, image URL, rating).
- **`gulf_catalog_brand_slug($brandName)`**: Maps ERP-style brand names to URL/filter slugs.
- **`gulf_catalog_landing_category($subCat)`** / **`gulf_catalog_row_landing_category(...)`**: Derives landing filter categories (mobility, beauty, nutrition, etc.).
- **`gulf_catalog_price_parts($price)`**: Splits price into whole and decimal parts for display.
- **Loop at bottom**: Builds **`products`** and **`landing_products`** arrays consumed by Blade.

### `resources/views/landing.blade.php`

- Large `@php` block: builds brand/category filter lists from the config data.
- Product cards: `data-*` attributes for JS filtering and modal content.

### Middleware (usage)

| Middleware | Where | Role |
|------------|-------|------|
| `web` group | All `web.php` routes | Sessions, CSRF, cookies, errors, bindings |
| `api` group | All `api.php` routes | Throttle, bindings |
| `auth`, `guest`, etc. | Available for future routes | Defined in `Http/Kernel.php` |

### Helpers

No `app/helpers.php` or global helper file was found. Logic is in **config** and **Blade `@php`** blocks.

---

## 11. Third-party integrations

| Integration | How it appears |
|-------------|----------------|
| **Product images** | Many URLs point to **`uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com`** (read-only asset hosting). |
| **Google Fonts** | Loaded from `fonts.googleapis.com` / `fonts.gstatic.com`. |
| **Figma** | Referenced in comments in `gulf-figma-static/index.html` (design source). |

**No** payment gateways, OAuth providers, or custom HTTP APIs are wired in application code for the landing page.

---

## 12. Assets and frontend

### Landing-specific (primary)

| Asset | Path |
|-------|------|
| Styles | `public/css/gulf-landing.css` |
| Script | `public/js/gulf-landing.js` |
| Images | `public/images/` (PNGs, WebPs, logos, categories, brands) |

### Default Mix pipeline

| Source | Output (after `npm run dev` / `prod`) |
|--------|----------------------------------------|
| `resources/assets/js/app.js` | `public/js/app.js` |
| `resources/assets/sass/app.scss` | `public/css/app.css` |

**Build tool:** **Laravel Mix 1** (`webpack.mix.js`), not Vite (Vite is used in newer Laravel versions).

---

## 13. Security notes

| Topic | Status |
|-------|--------|
| **CSRF** | Enabled for `web` routes. The newsletter `<form>` in `landing.blade.php` **does not include** `@csrf` / `csrf_field()`, so a legitimate POST will **fail** CSRF verification unless you exclude the URI or switch to GET/AJAX with token. |
| **POST `/`** | Only `GET /` is defined. POST to `/` is **not handled** as a successful form endpoint. |
| **Authentication** | Auth controllers exist but **routes are not registered**; no login surface in `web.php`. |
| **API user route** | `/api/user` requires `auth:api` (token guard); no token setup in this static landing scope. |
| **Dependencies** | Laravel **5.4** and PHP **5.6** are **end-of-life**. Plan upgrades for production internet exposure. |
| **Secrets** | Never commit `.env`. Rotate keys if exposed. |

---

## 14. Performance notes

| Area | Suggestion |
|------|------------|
| **Single large Blade file** | Consider splitting into `@include` partials for maintainability (not required for raw performance). |
| **Many external images** | S3 URLs load from another host; use `loading="lazy"` where missing, CDN caching, and appropriately sized images. |
| **Inline catalog** | Large HTML for ~70+ products; acceptable for static marketing; for scale, move to pagination or API + JS fetch. |
| **HTTP/2 / compression** | Configure on the web server (gzip/brotli). |
| **Upgrade path** | Newer PHP/Laravel versions improve performance and security. |

---

## 15. How to set up the project

These steps assume a Windows + WAMP-style environment similar to this repo’s `composer.json` scripts.

### 1. Clone the repository

```bash
git clone <repository-url> gulf_laravel_static
cd gulf_laravel_static
```

### 2. Install PHP dependencies

```bash
composer install
```

If Composer scripts fail due to the hard-coded PHP path, run Artisan with your local PHP binary explicitly, e.g.:

```bash
php artisan key:generate
```

### 3. Install Node dependencies (optional — for Mix default assets)

```bash
npm install
npm run dev
```

Not strictly required to view the landing page if you only use prebuilt `gulf-landing.*` files.

### 4. Environment file

```bash
copy .env.example .env
php artisan key:generate
```

Edit `.env`:

- Set `APP_URL` to your local URL (e.g. `http://localhost/gulf_laravel_static/public` depending on vhost).
- Set `GULF_PHARMACIST_CHAT_URL` if needed.
- Configure `DB_*` if you plan to run migrations or use auth.

### 5. Database migrations (optional)

```bash
php artisan migrate
```

Creates `users` and `password_resets` only. **The landing catalog does not need MySQL** to display.

### 6. Permissions (Linux/macOS)

Ensure `storage/` and `bootstrap/cache/` are writable by the web user.

### 7. Start the application

**Option A — PHP built-in server** (from project root):

```bash
php artisan serve
```

Then open the URL shown (default `http://127.0.0.1:8000`).

**Option B — Apache / WAMP**

- Point the virtual host document root to the **`public/`** directory.

---

## 16. Key file mapping (feature → file)

Use this table to find where to change behavior.

| Feature / concern | Primary file(s) |
|-------------------|-----------------|
| **Homepage URL** | `routes/web.php` |
| **Landing HTML structure & catalog markup** | `resources/views/landing.blade.php` |
| **Catalog product list, prices, categories, images** | `config/gulf_catalog.php` |
| **Brand slug mapping** | `gulf_catalog_brand_slug()` in `config/gulf_catalog.php` |
| **Category mapping (mobility / beauty / nutrition)** | `gulf_catalog_landing_category()`, `gulf_catalog_row_landing_category()` in `config/gulf_catalog.php` |
| **Pharmacist chat / WhatsApp link** | `.env` (`GULF_PHARMACIST_CHAT_URL`), `config/gulf_landing.php` |
| **Catalog filters, search, sort, cart count, modal** | `public/js/gulf-landing.js` |
| **Landing look & feel** | `public/css/gulf-landing.css` |
| **Logos, hero, category images** | `public/images/` |
| **Default Laravel + Vue boilerplate assets** | `resources/assets/js/*`, `resources/assets/sass/*`, `webpack.mix.js` |
| **Compiled default assets (if built)** | `public/js/app.js`, `public/css/app.css` |
| **User model** | `app/User.php` |
| **Users table schema** | `database/migrations/2014_10_12_000000_create_users_table.php` |
| **Middleware / CSRF** | `app/Http/Kernel.php`, `app/Http/Middleware/VerifyCsrfToken.php` |
| **Static design prototype** | `gulf-figma-static/index.html`, `gulf-figma-static/assets/` |
| **Existing internal notes** | `PROJECT_FRAMEWORK_AND_CHANGES.md`, `STATIC_WEBSITE_STRUCTURE.md` |

---

## 17. Summary for handover

### How to understand this project quickly

1. **Start at `routes/web.php`** — there is only one web route: it returns the landing view.
2. **Read `resources/views/landing.blade.php`** — this is the whole user-visible page.
3. **Open `config/gulf_catalog.php`** — this is the “database” for products until you move to real persistence.
4. **Open `public/js/gulf-landing.js`** — all interactive catalog behavior is here.

### Where to start as a new developer

| Goal | First step |
|------|------------|
| Change copy or layout | Edit `landing.blade.php` and `gulf-landing.css`. |
| Add/remove products | Edit the `$catalogFeedRows` array in `config/gulf_catalog.php` (or refactor to read from DB/API). |
| Fix newsletter / forms | Add proper routes, controllers, validation, and **`@csrf`** in forms. |
| Add real login | Register auth routes and views; align `$redirectTo` with an existing `/home` route. |
| Modernize stack | Plan migration from Laravel 5.4 / PHP 5.6 to a supported LTS release. |

---

*End of documentation. Regenerate or amend this file when major architecture changes are introduced.*
