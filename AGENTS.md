# Bridge (hwy559bridge_v1.0.0) — Project Memory

## What this is
React-powered inventory management plugin for equipment dealers.
Brand: HWY 559 Bridge (hwy559bridge.com / hwy559.com).

## STRICT BRANDING RULE
NEVER reference or use "Varner" or "Varner Equipment" in this project codebase, scripts, documentation, or themes. All branding, namespaces, and references MUST be HWY 559 Bridge / Bridge OS.
Current version on `main`: **1.0.4** (build gracefully bumps this).

## Build / Run
- Dev server: `npm run dev` (vite; may grab port 5174 if 5173 busy).
- Full build + package: `./build.ps1` ← compiles React → `bridge-plugin/dist/`, **auto-bumps** plugin+readme version, zips `bridge-plugin.zip`.
- Local vue only: `npx vite build`.
- Mock backend activates automatically when hostname is `localhost`/`127.0.0.1`.

## Stack
- React 18 + Vite 5 + Tailwind 3; deps: @dnd-kit, jszip, lucide-react, react-quill, recharts.
- WP backend PHP: `bridge-plugin/*.php` (backend, category-tree, demo-seed, rest-api).

## Mock backend (`src/utils/api.js`) — IMPORTANT
`apiFetch`/`uploadFile` switch to a **localStorage-backed mock** on localhost so the
whole app works without WordPress. Full test coverage written on first build — see
chat history for details. Handled endpoints:
- `inventory` CRUD + `deleted`/`restore`/`permanent` + `:id/marketplace-posted`
- `category-tree` + `category-tree/node` (POST add / DELETE del) + `/rename`
  (mutates a **persisted** tree AND migrates assigned units)
- `me`, `brands`, `years`, `categories`, `subcategories` (from live tree)
- `settings` + `preview`, `pages`, `page-templates`
- `videos`, `video-categories` (with `mockCollection` helper)
- `staff`, `mobile/token`, `meta-sync` (logs/health), `ledger`, `session`

**Pattern**: to add a new endpoint, add a case to `handleMockApi` in `src/utils/api.js`.
Use `mockCollection(key)` for CRUD lists; `mockLoad`/`mockSave` for persistence; `mockId()` for ids.

## Image compression (`src/utils/imageCompress.js`)
`compressImage(file)` — client-side canvas: downscales to ≤1600px, flattens to JPEG
(~82% q, 0.6 fallback), skips GIF/SVG/videos and files <1.5MB.
Wired into `handleAddImages` and `handleImplementImageUpload` (`src/hooks/useInventory.js`).

## Demo images
14 units in `bridge-plugin/assets/demo/units/`. Filename MUST match the unit slug exactly
or `bridge-demo-seed.php` silently skips it (seed grants: `wp bridge seed`, `--fresh`).
Images sideload into WP media; tagged `_bridge_demo_seed=1`.

## Skills / extensions installed (see `~/.claude/skills`, `~/.agents/skills`, `~/.config/opencode`)
- WP: `wp-plugin-directory-guidelines`, `wordpress-plugin-development`
- React testing: `react-testing`, `react-testing-library`
- Full `seo-*` suite (audit, schema, images, GEO, etc.), `playwright-cli/trace`, `caveman`, `brooks-*`.

## Image generation (TBD / gotcha)
- `seo-image-gen` skill needs the banana MCP or a **valid Google AI (AIza...) key**.
- Dev/user token **`AQ.Ab8...`** authenticates but has **0 free-tier image quota**
  → cannot REST-generate. Real images made manually in Gemini/Antigravity, saved as JPGs.
- Stored `~/.config/claude-seo/google-api.json` key is the placeholder `"YOUR_API_KEY"` — not real.
- If I detect a hemisphere: downscale/convert PNG->progressive JPEG (match ~300-450KB siblings).

## Status / next
- ✅ Mock backend complete — every tab button works on localhost.
- ✅ All 14 demo unit images present & committed.
- ✅ Image compress-on-upload shipped.
- ✅ v1.0.4 built & zipped.
- ⏭️ **NEXT (pending): deploy v1.0.4 to the live site** — method TBD
  (manual WP-Admin zip upload, WP-CLI/SSH, or other). Ask the user how.

## Gotchas
- I sometimes typo the project path — real root is
  `C:\Users\Greg\Desktop\hwy559bridge_v1.0.0` (NOT the misspelled variant).
- Dev server may already be running (port in-use). Don't blindly start second.
- Don't commit dist/= unless a real rebuild changed it; use `./build.ps1`.
- Version bumps in `build.ps1` are committed separately.