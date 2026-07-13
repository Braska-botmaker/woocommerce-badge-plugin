# CX Product Badges

[![CI](https://github.com/crystalex/cx-product-badges/actions/workflows/ci.yml/badge.svg)](https://github.com/crystalex/cx-product-badges/actions/workflows/ci.yml)
[![License: GPL v2+](https://img.shields.io/badge/License-GPLv2%2B-blue.svg)](LICENSE)
[![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-21759b.svg)](https://wordpress.org)
[![WooCommerce](https://img.shields.io/badge/WooCommerce-7.0%2B-96588a.svg)](https://woocommerce.com)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777bb4.svg)](https://php.net)

A lightweight WooCommerce plugin that shows a single, priority-ranked badge on each product based on its tags, and automatically keeps `Sale` / `Out of stock` tags in sync with product state.

No page builder lock-in, no bloated settings screens: assign a priority to a WooCommerce product tag and the plugin renders the highest-priority one as a clean, unstyled, CSS-hookable badge — on the shop archive, the single product page, or anywhere via `[cx_product_badges]`.

## Two language variants, same plugin

This repository ships **two functionally identical, independently installable copies** of the plugin — pick the one that matches the language of the store you're deploying to. Install only one per WordPress site; both variants use the same PHP function names and will fatal-error if active at the same time.

| Variant | Folder | UI language | Auto-generated tags | Docs |
| --- | --- | --- | --- | --- |
| 🇨🇿 Czech | [`cz/`](cz) | Czech | `Sleva`, `Vyprodáno` | [cz/README.md](cz/README.md) |
| 🇬🇧 English | [`en/`](en) | English | `Sale`, `Out of stock` | [en/README.md](en/README.md) |

Each folder is a self-contained, single-file WordPress plugin (`crystalex-product-badges.php`) plus an optional example stylesheet (`example.css`) with ready-made badge styling for Bricks Builder. To install, zip the folder's contents and upload it via **Plugins → Add New → Upload Plugin**.

## Highlights

- **Priority-based display** — only the single highest-priority badge is rendered per product (priority 0–999, set per tag)
- **Automatic status tags** — `Sale` / `Out of stock` (or `Sleva` / `Vyprodáno` in the Czech variant) are added and removed automatically as product state changes, each independently toggleable in the admin
- **Zero inline styles** — the plugin outputs bare, semantic HTML (`.crystalex-badges-wrapper > a.badge.badge-{slug}`); all visual styling is yours to define
- **Bricks Builder ready** — `[cx_product_badges]` shortcode for use as a Dynamic Data field
- **WPML-aware** — badges follow WPML's taxonomy translations automatically, no extra config
- **HPOS / Cart-Checkout Blocks compatible** — declares WooCommerce feature compatibility on `before_woocommerce_init`
- **Bulk re-sync** — one-click admin action to recompute automatic tags across the whole catalog (useful after imports)

See either variant's README for full usage, styling, WPML setup, and troubleshooting docs.

## Repository layout

```text
.
├── cz/                        Czech plugin variant (source + README + example.css)
├── en/                        English plugin variant (source + README + example.css)
├── .github/
│   ├── workflows/ci.yml       PHP lint + WordPress Coding Standards (PHPCS)
│   ├── ISSUE_TEMPLATE/        Bug report / feature request templates
│   └── pull_request_template.md
├── composer.json              Dev dependency: WordPress Coding Standards
├── phpcs.xml                  PHPCS ruleset used by CI and local linting
├── CHANGELOG.md               Version history (both variants ship in lockstep)
├── CONTRIBUTING.md            How to propose changes
└── LICENSE                    GPL v2 or later
```

## Requirements

- WordPress 6.0+
- WooCommerce 7.0+ (tested up to 9.5)
- PHP 7.4+

## Development

Both variants are intentionally dependency-free, single-file plugins — there's no build step to run the code. Tooling here exists purely to catch mistakes before they ship:

```bash
# Install dev dependencies (WordPress Coding Standards via PHPCS)
composer install

# Lint PHP syntax
php -l cz/crystalex-product-badges.php
php -l en/crystalex-product-badges.php

# Check coding standards
composer run lint
```

The same checks run automatically in CI on every push and pull request (see [`.github/workflows/ci.yml`](.github/workflows/ci.yml)).

When changing behaviour, keep the two variants in sync: a fix or feature almost always needs to land in both `cz/crystalex-product-badges.php` and `en/crystalex-product-badges.php`. See [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Releases

Pushing a tag matching `v*.*.*` (e.g. `v3.0.2`) triggers [`.github/workflows/release.yml`](.github/workflows/release.yml), which builds a ready-to-upload ZIP for each variant (`crystalex-product-badges-cz-<version>.zip`, `crystalex-product-badges-en-<version>.zip`) and attaches them to a GitHub Release. Grab the latest ones from the [Releases page](../../releases) instead of zipping the folders by hand.

## Versioning & changelog

Both variants are versioned and released together (identical `Version:` header). See [CHANGELOG.md](CHANGELOG.md) for the full history.

## Credits

Built by [Matěj Horák](https://crystalexcz.com), originally developed for the [Crystalex](https://crystalexcz.com) e-shop.

## License

GPL v2 or later — see [LICENSE](LICENSE).
