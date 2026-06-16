# Changelog

All notable changes to the **Anesidora** project will be documented in this file.

## [1.2.0] - Unreleased
### Added
- Migrated the application back to **Laravel Blade** templates from Vue.js.
- Restored traditional server-side routing and Blade-based views.
- Implemented **Telegram API** integration for secure file storage and retrieval.
- Added support for file **password protection** on downloads.
- Added file **expiration limits** (1h, 24h, 7d, 30d) for shared gifts.
- Added **download counter** to track file popularity.
- Implemented **User Registration and Login** with session management.
- Added API endpoints for retrieving **top files** and **public files**.
- Added file sorting functionality based on download counts.
- Created `FileHandling` model and migration for managing file metadata.

### Changed
- Reverted Single Page Application (SPA) architecture to multi-page Blade architecture.
- Updated `UploadController` to handle Telegram uploads and secure link generation.
- Updated database schema to include download tracking and enhanced file metadata.

### Removed
- Removed **Vue.js 3**, **Vue Router**, and associated frontend dependencies.
- Removed SPA-specific catch-all routes in favor of explicit Blade routes.

---

## [1.1.0] - 2026-06-06 - Unreleased
### Added
- Integrated **Vue.js 3** (Composition API) as the primary frontend framework.
- Added **Vue Router** for client-side navigation.
- Created root `App.vue` and `router.js` configuration.
- Created reusable Vue components: `Navbar.vue` and `Card.vue`.
- Created Vue views: `Home.vue`, `Login.vue`, `Register.vue`, `Upload.vue`, and `Drive.vue`.
- Added `@vitejs/plugin-vue` for Vite compilation support.

### Changed
- Migrated existing Blade-based UI to **Single Page Application (SPA)** architecture.
- Updated `resources/views/app.blade.php` to serve as the SPA entry point.
- Refactored `routes/web.php` to handle all frontend routes through a single catch-all route.
- Updated Tailwind CSS configuration to scan `.vue` files in `resources/js/`.
- Optimized `app.js` to initialize the Vue application instance.

### Removed
- Removed legacy Blade views in `resources/views/routes/` and `resources/views/components/` (replaced by Vue equivalents).
- Removed direct Blade-to-Route mapping in favor of Vue Router.

---

## [1.0.0] - 2026-06-05
### Added
- Initial project setup with Laravel.
- Database schema for Users, File Handling, and Sessions.
- Basic Blade templates and Tailwind CSS v4 styling.
- Core File Upload functionality.
