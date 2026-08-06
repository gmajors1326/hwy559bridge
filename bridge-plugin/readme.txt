=== Bridge OS Plugin ===
Contributors: HWY 559 Team
Requires at least: 6.0
Tested up to: 6.5
Requires PHP: 7.4
Version: 1.0.8
License: Proprietary

== Description ==
React-powered inventory management for equipment dealers. Provides custom Equipment CPT, ACF fields, admin dashboard (Bridge OS), REST API endpoints, and asset loading for the PWA.

== Key Features ==
- Equipment custom post type with ACF fields and gallery/implements.
- React admin app mount in wp-admin and Gutenberg block.
- Public showroom shortcode `[bridge_showroom]`.
- REST API namespace `bridge/v1` for inventory CRUD, media upload, brands, categories, auth helpers, and per-unit ledger.
- Session and ledger tables provisioned on activation (audit trail foundation, with field-diff logging wired).

== Installation ==
1. Upload plugin files or install the zip via wp-admin.
2. Activate the plugin. Activation runs dbDelta to create `wp_bridge_user_sessions` and `wp_bridge_inventory_ledger`.
3. Ensure ACF Pro is active (fields are auto-registered; optional `acf-json/` for sync).
4. Place built assets under `dist/assets/` (already bundled here).

== Shortcodes ==
- `[bridge_showroom]` renders the public React showroom container.

== REST Endpoints (namespace bridge/v1) ==
- `GET /inventory` — list equipment (public, filtered for visibility if not editor).
- `POST /inventory` — create equipment (auth: edit_posts).
- `PATCH /inventory/{id}` — update equipment (auth).
- `DELETE /inventory/{id}` — soft delete equipment (auth).
- `POST /inventory/{id}/restore` — restore soft-deleted equipment (auth).
- `DELETE /inventory/{id}/permanent` — hard delete (auth).
- `POST /media` — upload media (auth).
- `GET/POST /brands` — list/save brands (auth for POST).
- `GET/POST /categories` — list/save categories (auth for POST).
- `GET /me` — current user profile + initials (auth).
- `POST /logout` — logout and record session end (auth).
- `GET /inventory/{id}/ledger` — paginated ledger entries for a unit (auth).
- GET /pages — list pages (auth: edit_pages).
- POST /pages — create page (auth: publish_pages).
- PATCH /pages/{id} — update page (auth: per-object edit_page).
- DELETE /pages/{id} — trash page (auth: per-object delete_page).
- GET /page-templates — list page templates (auth: edit_pages).

== Admin Mounts ==
- Bridge OS dashboard under wp-admin menu.
- Gutenberg block `bridge-editor` mounts the React app.
- Equipment CPT hidden from main menu (managed via Bridge OS).

== Asset Loading ==
- Picks latest `dist/assets/*.js|*.css`, preferring `main.*`, versioned by `filemtime`.

== Audit Foundations ==
- Activation creates tables for sessions and inventory ledger (append-only), with detailed REST-based CRUD and field-diff logging fully wired.

== Notes ==
- Storefront theme pages remain separate; this plugin focuses on inventory admin and REST.

== Changelog ==

= 1.0.7 =
- Cleaned branding and upgraded deployment automation for Hostinger SSH integration.
- Full parity across mock API and live WordPress REST API endpoints.
