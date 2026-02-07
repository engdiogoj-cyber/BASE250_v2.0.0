# Public Directory Structure

## Overview
This frontend uses a `public_html` directory for static assets, with a symlink at `public` for Next.js compatibility.

## Why public_html?
- Many traditional hosting providers (cPanel, shared hosting) require static assets in `public_html`
- Next.js requires assets in a directory named `public` (hardcoded since v9.1)
- Solution: Use `public_html` as the actual directory, with `public` as a symlink

## Directory Structure
```
frontend/
  ├── public -> public_html  # Symlink for Next.js
  ├── public_html/           # Actual static assets
  │   ├── manifest.json      # PWA manifest
  │   ├── icon-192x192.png   # TODO: Add PWA icon
  │   └── icon-512x512.png   # TODO: Add PWA icon
```

## Missing Assets
The following assets are referenced in `manifest.json` but not yet added:
- `/icon-192x192.png` - 192x192 PWA icon
- `/icon-512x512.png` - 512x512 PWA icon

These should be added to `public_html/` directory.

## Deployment
For hosting providers that require `public_html`:
1. Use the `deploy-to-public-html.sh` script
2. Or manually copy files: `cp -r public_html/* /path/to/hosting/public_html/`

## Development
During development, Next.js will automatically serve files from the `public` symlink, which points to `public_html`.
