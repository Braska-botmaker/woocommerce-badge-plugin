# Contributing

Thanks for considering a contribution to CX Product Badges.

## Project structure

This repository contains **two independent, single-file WordPress plugins** that are meant to stay behaviourally identical:

- [`cz/crystalex-product-badges.php`](cz/crystalex-product-badges.php) — Czech UI strings and auto-generated tag names (`Sleva`, `Vyprodáno`)
- [`en/crystalex-product-badges.php`](en/crystalex-product-badges.php) — English UI strings and auto-generated tag names (`Sale`, `Out of stock`)

Both files share the same function names, hooks, and logic — only user-facing strings and the option names tied to those strings (`crystalex_auto_tag_sleva_enabled` vs. `crystalex_auto_tag_sale_enabled`, etc.) differ.

**Any behavioural change (bug fix, new hook, new feature) must be applied to both files.** Pure copy/wording changes only need to touch the relevant variant.

## Making a change

1. Fork the repository and create a branch off `main`.
2. Implement your change in `cz/crystalex-product-badges.php` and, if it's behavioural, mirror it in `en/crystalex-product-badges.php`.
3. Keep the two `Version:` headers and `CHANGELOG.md` in sync — bump both plugin headers together and add a changelog entry under a new version heading.
4. Update the relevant `README.md` (root, `cz/`, and/or `en/`) if the change affects usage, hooks, or the shortcode.
5. Run the checks described below before opening a PR.

## Coding standards

- Follow [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/) — enforced via PHPCS (see below).
- Escape all output (`esc_html()`, `esc_attr()`, `esc_url()`) and use nonces for any state-changing admin action, matching the existing patterns in the codebase.
- All user-facing strings must go through `__()` / `esc_html_e()` / `esc_html__()` with the `crystalex-badges` text domain, so the plugin stays translation-ready (WPML, Loco Translate, etc.).
- Prefer small, focused functions over adding parameters/branches to existing ones — the plugin is intentionally simple and dependency-free.

## Local checks

```bash
composer install

# PHP syntax check
php -l cz/crystalex-product-badges.php
php -l en/crystalex-product-badges.php

# WordPress Coding Standards
composer run lint
```

These same checks run in CI on every push and pull request via [`.github/workflows/ci.yml`](.github/workflows/ci.yml); PRs won't merge with a failing build.

## Reporting bugs / requesting features

Please use the issue templates under **Issues → New Issue** — they ask for the information (WordPress/WooCommerce/PHP versions, steps to reproduce, expected vs. actual behaviour) needed to act on a report quickly.

## Commit messages

Keep commit messages short and descriptive of the *why*, not just the *what* (e.g. `Fix badge priority sort for tags without meta` rather than `update code`).
