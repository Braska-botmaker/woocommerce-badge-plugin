# Changelog

All notable changes to this plugin are documented in this file. The Czech (`cz/`) and English (`en/`) variants are versioned and released together — a version number always refers to both.

The format is loosely based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [3.0.0] - 2026-07-09

### Added

- Independent on/off toggles for automatic "Sale" / "Out of stock" badge tagging — new **"Automatic tagging settings"** panel in the admin (WooCommerce → Update Badges / CX Badge).
- "Status" column (Enabled/Disabled) in the automatic badges overview table.
- The badge is now a clickable link (`<a>`) to the archive of products with that tag, instead of an inert `<span>`.

### Changed

- When automatic tagging for a given type (Sale/Out of stock) is disabled, the plugin no longer manages that tag on any product at all — it neither adds nor removes it. Manually assigned tags are preserved.

## [2.2.0] - 2025-12-05

### Added

- Automatic add/remove of the "Sale" (based on the sale price) and "Out of stock" (based on stock status) tags.
- Priority-based badge display — a "Priority" field on product tags (0–999); only the highest-priority badge is ever shown.
- "Priority" column in the product tags overview screen.
- **CX Badge** admin screen to bulk-recalculate badges for all products.
- `[cx_product_badges]` shortcode for use in Bricks Builder and elsewhere.

## [1.5.0] - 2025-12-05

### Added

- Declared compatibility with WooCommerce HPOS (custom order tables) and Cart/Checkout blocks.

### Changed

- Updated compatibility requirements: WordPress 6.0+, WooCommerce 7.0+ (tested up to 9.5), WordPress tested up to 6.7.

## [1.4.0] - 2025-12-02

### Changed

- Removed inline styles from badge rendering — clean, unstyled HTML output for full control via custom CSS.
- Badges now render in more places (product archive, product detail page) via additional WooCommerce hooks.

## [1.0.0] - 2025-12-02

### Added

- Initial release: automatic badge display based on product tags (`product_tag`) — every product tag renders as a badge before the title on product archives.
- WooCommerce active-plugin check and text domain loading for WPML/translations.
