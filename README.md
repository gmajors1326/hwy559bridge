# Bridge (v1.0.0)

React-powered inventory management plugin for equipment dealers.

## Features

- **React Admin Interface**: Modern single-page inventory management suite.
- **WP REST API Integration**: Custom endpoints for real-time inventory synchronization.
- **Demo Seed Generator**: Automated seed engine populating 14 realistic demo units across 8 generic equipment categories out-of-the-box.
- **Category Tree Engine**: Decoupled, generic 8-category hierarchy.
- **Gutenberg Block Support**: Native editor block (`bridge-editor`) to embed inventory grid onto any page.
- **Meta / Facebook Catalog Sync**: Automatic CSV catalog generation & background scheduling.
- **Cookie Consent Manager**: Integrated GDPR/CCPA consent manager.

## Project Structure

```
hwy559bridge/
├── bridge-plugin/       # WordPress plugin source & assets
│   ├── blocks/          # Gutenberg block templates
│   ├── dist/            # Vite production bundle
│   ├── bridge-plugin.php
│   ├── bridge-backend.php
│   ├── bridge-category-tree.php
│   ├── bridge-demo-seed.php
│   └── rest-api.php
├── src/                 # React source code
├── build.ps1            # Unified build & packaging script
├── tailwind.config.js   # Tailwind CSS configuration
└── vite.config.js       # Vite build configuration
```

## Quick Start

### Build & Package

```powershell
./build.ps1
```

This compiles the React application into `bridge-plugin/dist/`, auto-bumps versioning, and packages `bridge-plugin.zip` for WP Admin upload.

### WP-CLI Seed Command

```bash
wp bridge seed           # Seed demo inventory
wp bridge seed --fresh   # Wipe & re-seed
```
