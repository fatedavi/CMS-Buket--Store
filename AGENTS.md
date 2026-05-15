# CMS Toko Buket — Agent Guide

## Stack
Laravel 12 + Livewire 3, Tailwind CSS 3, Alpine.js 3, Vite, MySQL

## Setup
```bash
composer install && npm install
copy .env.example .env
php artisan key:generate
php artisan migrate && php artisan db:seed
npm run build
php artisan storage:link
```

## Dev
```bash
composer run dev    # serve + queue + pail (logs) + Vite HMR
```
Or: `php artisan serve`, `npm run dev`, `php artisan queue:listen --tries=1`, `php artisan pail`.

## Tests & Lint
```bash
./vendor/bin/phpunit
./vendor/bin/pint         # auto-fix style
```
Envs: `CACHE_STORE=array`, `QUEUE_CONNECTION=sync`, `SESSION_DRIVER=array`.

## Architecture

| Layer | Detail |
|-------|--------|
| Public routes | `/`, `/katalog`, `/blog`, `/kontak` |
| Admin routes | `/admin/*` — protected by `auth` + `is_admin` middleware |
| Auth | Custom (no Breeze) — `/login`, `/register` |
| Models | `Product`, `Article`, `Setting` (key-value), `ChatConversation`, `ChatMessage`, `User` (`is_admin` boolean) |
| Views | Blade with `@extends('layouts.app')` or `layouts.admin` |
| Frontend | Tailwind custom colors (`linen`, `cream`, `sand`, `dark-oak`, `sage-green`, `terracotta`, `blush`), fonts (`font-playfair` headings, `font-dm` body) |

## Key conventions
- **All data is hardcoded arrays** in public controllers (HomeController, ProductController, BlogController, ContactController). Only AdminController uses Eloquent.
- **Settings** stored in `settings` DB table (key-value). Access via `setting('key')` helper — cached 1 hour.
- **Admin panels use `request()->routeIs(...)`** for active nav highlighting.
- **Vite entry**: `resources/css/app.css` + `resources/js/app.js`. Alpine.js + AOS loaded globally.
- **WhatsApp**: number stored in settings as `whatsapp_link` (format `62856...`) and `whatsapp` (format `0...`).
- **Branding placeholder**: `[Nama Toko]` is replaced by `setting('store_name')` helper.

## Chat system
- Chat widget on public pages uses Alpine.js + AJAX (`POST /chat/send`, `GET /chat/messages`).
- Rate-limited: `throttle:5,1` (5 pesan/menit per IP).
- Image upload hanya untuk user login (disimpan di `storage/app/public/chat/`).
- Admin chat panel at `/admin/chat` — lihat, balas, tutup percakapan.
- Backup otomatis tiap 14 hari via `chat:backup --prune` (hapus closed > 30 hari). Jadwal di `routes/console.php`.
- Guest tracking via `session_id` (localStorage).

## Admin
| Path | Function |
|------|----------|
| `/admin` | Dashboard (stats from DB) |
| `/admin/produk` | CRUD produk |
| `/admin/artikel` | CRUD artikel |
| `/admin/chat` | Lihat/balas chat |
| `/admin/pengguna` | CRUD user |
| `/admin/pengaturan` | Settings (nama toko, WA, alamat, IG, jam) |

## SQLite vs MySQL
Default `.env.example` uses SQLite. Current project uses MySQL. If switching, uncomment MySQL vars in `.env` and comment `DB_CONNECTION=sqlite`.

## Tooling
- `php artisan make:model -m` — model + migration
- `php artisan make:middleware` + register in `bootstrap/app.php`
- `php artisan storage:link` — needed for uploaded files
- `php artisan schedule:list` — check scheduled tasks
- `php artisan chat:backup --prune` — manual backup + cleanup
