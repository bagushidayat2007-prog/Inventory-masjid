# AGENTS.md

## What this is
**Sistem Informasi Inventory dan Keuangan Masjid** — a Laravel 10 app (PHP 8.1+) for managing mosque inventory and finances. Session-based auth (no Sanctum/API tokens), plain Blade + vanilla CSS/JS assets (no Vite build required at runtime).

Runs on Laragon. Database is MariaDB/MySQL, database name **`inventory_masjid`**.

## Setup
- `cp .env.example .env` then `php artisan key:generate`.
- Create the DB once: `mysql -u root -e "CREATE DATABASE IF NOT EXISTS inventory_masjid CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"`.
- `composer install`, then `php artisan migrate --seed`.
- Seeded logins (password in parentheses): `admin` (admin123), `bendahara` (bendahara123), `petugas` (petugas123).
- `composer install` needs `composer config audit.block-insecure false` first — Laravel 10 has open security advisories that block install otherwise.
- Frontend assets are plain files in `public/css/app.css` and `public/js/app.js`, loaded with `asset()`. There is **no build step**; ignore `npm run dev`/`vite`. `package.json` is a leftover from the skeleton.

## Architecture facts an agent will get wrong
- The user model is `App\Models\User` but maps to table **`pengguna`** with primary key **`id_pengguna`**. Every foreign key to users is `id_pengguna`.
- All models set `$timestamps = false`; tables use custom column names (`id_barang`, `id_masuk`, etc.). Do not assume Laravel's `id`/`created_at`.
- Route-model binding in controllers relies on each model's `$primaryKey` (e.g. `Barang $barang` binds `id_barang`). Keep this consistent.
- Role access is enforced by the `role` middleware (`app/Http/Middleware/RoleMiddleware.php`), aliased in `app/Http/Kernel.php`. Usage: `->middleware('role:admin,bendahara')`. Roles: `admin`, `bendahara`, `petugas_inventory`.
- Helpers `rupiah()` and `has_role()` live in `app/Support/helpers.php`, registered via `composer.json` `autoload.files`. Run `composer dump-autoload` after changing it.
- Sanctum's migrations are disabled via `Sanctum::ignoreMigrations()` in `AppServiceProvider::register()` so only the 10 required tables are created. Do not remove it.
- Every important action writes to `audit_log` through `App\Support\AuditLogger::log()`.
- CSRF failures (419) are caught in `app/Exceptions/Handler.php` and redirected to login with an `error` flash message. Laravel converts `TokenMismatchException` to `HttpException(419)` **before** render callbacks, so the handler matches on status 419, not the exception type. Note: CSRF is bypassed in the test env, so `CsrfHandlingTest` calls the handler directly.

## Inventory stock rules (critical)
- Barang masuk **increments** `barang.stok`; barang keluar **decrements** it. Both run inside `DB::transaction()`.
- Barang keluar rejects quantities exceeding stock with the message "Stok tidak mencukupi." and throws `ValidationException`.
- Editing/deleting a transaction reverses the old stock movement first, then applies the new one. Preserve this ordering.

## Commands
- Run tests: `php artisan test` (PHPUnit, not Pest). Single: `php artisan test --filter=TestName`.
- Format: `./vendor/bin/pint`.
- Reset DB: `php artisan migrate:fresh --seed`.
- `phpunit.xml` uses SQLite `:memory:` (with `RefreshDatabase`), so tests never touch the `inventory_masjid` dev database.

## Conventions
- Indonesian naming throughout (models, routes, views, UI text). Keep new code consistent.
- Views: `resources/views/layouts/app.blade.php` + partials in `layouts/partials/`. Delete confirmation uses `data-confirm="..."` on a button inside the form (handled in `public/js/app.js`); forms that should show a loading state use `data-loading`.
- Pagination view is overridden at `resources/views/vendor/pagination/simple.blade.php` and set in `AppServiceProvider`.
