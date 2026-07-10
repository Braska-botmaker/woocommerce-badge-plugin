# CX Product Badges (EN)

A WordPress/WooCommerce plugin for priority-based badge display driven by product tags, plus smart automatic "Sale" and "Out of stock" tagging.

> This is the **English** variant of the plugin — UI labels, generated tag names (`Sale`, `Out of stock`) and all source comments are in English. A separate, functionally identical **Czech** variant lives in [`../cz`](../cz). See the [repository root README](../README.md) for how the two variants relate. Install only one variant per site — both define the same PHP function names and cannot be active at the same time.

## Description

The plugin displays **only the single highest-priority badge** based on a product's tags. It also automatically adds and removes the `Sale` and `Out of stock` tags based on product status.

**Priority-based display + automatic tagging** — maximum flexibility with minimum effort.

## Features

- **Priority-based display** — only the single highest-priority badge is shown
- **Configurable priority in the UI** — every tag has a priority from 0–999
- **Automatic tags** — `Sale` and `Out of stock` are added/removed automatically
- **Independent on/off switches** — Sale and Out-of-stock automation can be toggled independently in the admin
- **Clickable badge** — on the single product page and via the shortcode, the badge links to the archive of products with that tag; on product listings (shop/archive) it renders as a non-linked `<span>` instead, since the default WooCommerce template already wraps the whole product card in its own link and a nested `<a>` would break it
- **Clean HTML output** — no inline styles, style the badge however you like
- **Bricks Builder compatible** — `[cx_product_badges]` shortcode
- **Full WPML translation support** — tags and badges are translated automatically
- **Duplicate-render prevention** — badges are only rendered once per product/request
- **Robust product detection** — works even without a global `$product`
- **Bulk processing** — admin screen to refresh badges for all products at once

## Requirements

- WordPress 6.0 or newer
- WooCommerce 7.0 or newer
- PHP 7.4 or newer

## Installation

1. Download this folder as a ZIP (or clone the repo and zip the `en/` folder)
2. In WP Admin: **Plugins -> Add New -> Upload Plugin**
3. Choose the ZIP and click **Install Now**
4. **Activate** the plugin
5. Go to **CX Badge** in the left-hand menu and click **"Process all products"**
6. Set tag priorities under **Products -> Tags**

## Usage

### Option A: Standard WooCommerce (automatic)

The plugin automatically renders the badge in these places:
- **Product listings** (shop, archive, category pages) — before the title
- **Single product page** — above the summary

### Option B: Bricks Builder (shortcode)

**IMPORTANT:** In Bricks Builder the shortcode must be inserted **as a Dynamic Data field**.

1. Open the product template in the Bricks editor
2. Add a **Shortcode** element
3. Enter `[cx_product_badges]` in the "Shortcode" field
4. The badge renders exactly where you placed the shortcode element

### Setting tag priority

1. Go to **Products -> Tags** and create or edit a tag
2. Set the **badge priority** (0–999; higher numbers rank first)
3. Save the tag
4. Only the badge with the **highest priority** is shown

### Automatic tags

The plugin automatically adds/removes these tags:

| Tag              | Priority | Added when                       | Removed when                |
|------------------|----------|-----------------------------------|------------------------------|
| **Sale**         | 80       | Product has a sale price set     | Sale price is removed        |
| **Out of stock** | 30       | Product is out of stock          | Product is back in stock     |

**Automation runs:**
- On product save in wp-admin
- On stock status change
- After clicking "Process all products" in **CX Badge**

### Enabling/disabling automatic tagging

Automatic tagging for `Sale` and `Out of stock` can be toggled independently under **CX Badge -> Automatic tagging settings**:

- When a toggle is **off**, the plugin no longer manages that tag for any product — it won't add or remove it. Tags assigned manually are left untouched.
- Both toggles default to **on** (this preserves the plugin's original behaviour).

### Adding custom tags to products

1. Go to **Products -> Tags** and create your own tags (e.g. "New", "Bestseller")
2. Set a **priority** (e.g. New: 100, Bestseller: 60)
3. Assign the tags to a product in the **"Product tags"** panel
4. Save the product
5. The badge with the **highest priority** is shown

## Priority example

A product has these tags:
- **New** (priority: 100)
- **Sale** (priority: 80, automatic)
- **Bestseller** (priority: 50)

-> **Only "New" is shown** (highest priority)

## Example use cases

| Tag              | Priority | When to use                              |
|------------------|----------|--------------------------------------------|
| **PROMO**        | 100      | Special campaign, takes priority over all  |
| **Sale**         | 80       | Automatic, regular discount                |
| **New**          | 70       | New products                               |
| **Bestseller**   | 50       | Best-selling products                      |
| **Out of stock** | 30       | Automatic, not in stock                    |
| **Limited**      | 20       | Limited edition                            |

**HTML output:**
```html
<div class="crystalex-badges-wrapper">
    <a href="/product-tag/promo/" class="badge badge-promo">PROMO</a>
</div>
```

**Benefits:**
- Always shows **only the single most important badge**
- Priority is fully controlled from the UI
- Automatic `Sale` and `Out of stock` tags
- One tag can be reused across many products
- Renaming a tag or changing its priority updates every product at once

## Styling

**IMPORTANT:** The plugin renders **clean HTML with no inline styles whatsoever**.

On the single product page and via the shortcode, the output is a clickable link:
```html
<div class="crystalex-badges-wrapper">
    <a href="/product-tag/new/" class="badge badge-new">New</a>
</div>
```

On product listings (shop/archive) the badge is a non-linked `<span>` instead (see [Technical details](#technical-details)):
```html
<div class="crystalex-badges-wrapper">
    <span class="badge badge-new">New</span>
</div>
```

### CSS classes for styling

Every badge exposes these CSS classes, whether it renders as an `<a>` or a `<span>`:
- `.crystalex-badges-wrapper` — wrapper around the badge
- `.badge` — shared class for every badge
- `.badge-{slug}` — tag-specific class based on the tag's slug (e.g. `.badge-new`, `.badge-sale`)

### Styling example

Add this to your CSS (theme stylesheet, Bricks CSS editor, or Additional CSS):

```css
/* Badge wrapper */
.crystalex-badges-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 12px;
}

/* Base badge style */
.badge {
    display: inline-block;
    padding: 5px 12px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-radius: 3px;
    background: #333;
    color: #fff;
}

/* Tag-specific colors */
.badge-new {
    background: #4CAF50;
    color: #fff;
}

.badge-promo {
    background: #FF5722;
    color: #fff;
}

.badge-bestseller {
    background: #2196F3;
    color: #fff;
}

.badge-limited {
    background: #FF9800;
    color: #000;
}

.badge-exclusive {
    background: #9C27B0;
    color: #fff;
}
```

A ready-to-use, more polished stylesheet (Bricks Builder flavoured) is included in [`example.css`](example.css) in this folder.

**In Bricks Builder:** add CSS under **Bricks -> Settings -> Custom Code -> CSS**.

## WPML support

The plugin has **full, automatic WPML translation support**.

### How translation works

WPML automatically translates tag names, so badges render in the current page's language.

**Setup:**

1. Go to **WPML -> Taxonomy Translation**
2. Make sure the **"Product tags" (product_tag)** taxonomy is set to **"Translatable"**
3. Create a tag in the default language (e.g. "New")
4. Add translations for the tag in the WPML tag editor:
   - EN English: "New"
   - CZ Czech: "Novinka"
   - DE German: "Neuheit"

### Automatic display

- On the English site: `<a class="badge badge-new">New</a>`
- On the Czech site: `<a class="badge badge-novinka">Novinka</a>`
- On the German site: `<a class="badge badge-neuheit">Neuheit</a>`

**WPML handles everything automatically — no extra configuration required.**

## Advanced usage

### Shortcode in Bricks Builder

**CRITICAL:** the `[cx_product_badges]` shortcode must be inserted **as a Dynamic Data field**, not as plain text!

**Correct steps:**
1. In the Bricks editor, add a **Shortcode** element
2. Enter `[cx_product_badges]`
3. The badge renders inside the product loop

**Or use it inside a text element with Dynamic Data:**
1. Add a Text element
2. Click the **{x}** (Dynamic Data) icon
3. Select **Shortcode**
4. Enter `[cx_product_badges]`

### Adding new badges

Simply create a new tag under **Products -> Tags** — no code changes required.

**Example:** a "Handmade" badge
1. Create the tag "Handmade" (slug becomes `handmade` automatically)
2. Assign the tag to products
3. Add CSS for styling:
```css
.badge-handmade {
    background: #D32F2F;
    color: #fff;
}
```

### Bulk product processing

**CX Badge -> Process all products**

Use this feature:
- After first activating the plugin
- After a bulk product import via FTP
- Whenever you want to recalculate automatic badges
- To refresh badges on existing products

The button walks every product and adds/removes the automatic `Sale` and `Out of stock` tags.

### Bulk tag editing

- **Products -> Bulk edit** — assign tags to multiple products at once
- **Import/Export** — import products with tags via the WooCommerce CSV importer
- **Quick Edit** — edit tags inline from the product list

## Technical details

### Priority-based display

- **Only 1 badge is shown** — the one with the highest priority
- Priority range: 0–999 (higher = more important)
- Configurable in the UI per tag

### Link vs. span, by placement

| Placement | Element | Why |
| --- | --- | --- |
| Product listings (shop/archive/category) | `<span>` (not a link) | The default WooCommerce template wraps the entire product card in a single `<a>` here. Nesting another `<a>` inside it is invalid HTML — the browser would close the outer link early and break the product card's click-through. |
| Single product page | `<a>` (clickable, links to the tag archive) | No wrapping link exists here. |
| `[cx_product_badges]` shortcode | `<a>` (clickable, links to the tag archive) | Placement is entirely up to whoever inserts the shortcode (e.g. in Bricks Builder). |

### Automatic tags

| Tag           | Priority | Condition          | Behaviour                 |
|---------------|----------|---------------------|----------------------------|
| Sale          | 80       | `is_on_sale()`      | Added/removed automatically |
| Out of stock  | 30       | `!is_in_stock()`    | Added/removed automatically |

**Runs on:**
- Product save (`woocommerce_update_product`)
- Product creation (`woocommerce_new_product`)
- Stock status change (`woocommerce_product_set_stock_status`)
- Manually, via the admin screen

**Enable/disable:** each type (Sale/Out of stock) can be toggled independently under **CX Badge -> Automatic tagging settings** (stored as the WP options `crystalex_auto_tag_sale_enabled` / `crystalex_auto_tag_out_of_stock_enabled`).

### WooCommerce hooks

The plugin uses these hooks for rendering:

| Hook                                          | Used for                                      |
|------------------------------------------------|------------------------------------------------|
| `woocommerce_before_shop_loop_item_title`      | Product listings (shop, archive, category)     |
| `woocommerce_before_single_product_summary`    | Single product page                            |

### Shortcode

| Shortcode              | Use case                              |
|-------------------------|----------------------------------------|
| `[cx_product_badges]`   | Bricks Builder and custom templates    |

### Admin screen

**Location:** CX Badge (top-level menu) or WooCommerce -> Update Badges

**Features:**
- Bulk processing of all products
- Overview of automatic badges
- Processed-product count

## Data storage

The plugin **does not store any custom data**. It relies entirely on the standard WooCommerce `product_tag` taxonomy.

All data is stored the standard WordPress way, in the `wp_terms` and `wp_term_relationships` tables.

## Troubleshooting

### Badges aren't showing up

**1. Check tags:**
- Does the product have tags assigned under **Products -> Edit -> Product tags**?
- Do the tags have a priority set under **Products -> Tags**?

**2. Process products:**
- Go to **CX Badge** in the menu
- Click **"Process all products"**
- Automatic tags (Sale, Out of stock) will be added

**3. Cache:**
- Clear any caching plugin's cache
- Hard refresh the page (Ctrl+F5)

**In Bricks Builder:**
1. **IMPORTANT:** the `[cx_product_badges]` shortcode must be a **Dynamic Data field**
2. Use a **Shortcode** element with `[cx_product_badges]`
3. Make sure the shortcode sits inside the product loop

### Automatic tags aren't working

**After importing products:**
1. Go to **CX Badge**
2. Click **"Process all products"**
3. Tags will be added/removed based on current status

**On status change:**
- Automatic tags are added/removed **when a product is saved**
- Save the product again to refresh its tags

### Badges have no styling

The plugin **intentionally** ships without any inline styles. Add your own CSS:
```css
.badge {
    display: inline-block;
    padding: 5px 10px;
    background: #333;
    color: #fff;
}
```

### The wrong badge is showing

**Check the priorities:**
1. Go to **Products -> Tags**
2. The **"Priority"** column shows each tag's value
3. The badge with the **highest priority** wins

**Example:**
- PROMO (priority 100) <- shown
- Sale (priority 80)
- New (priority 50)

### WPML translations aren't working

1. Confirm WPML is active
2. Under **WPML -> Settings**, confirm the "Product tags" taxonomy is set to translatable
3. Translate individual tags under **WPML -> Taxonomy Translation**
4. Clear the cache

## Updating

The plugin ships as a single self-contained file, so updates are simple:
1. Back up the current file before updating
2. Upload the new version
3. Your data is preserved (it lives in the database)

## Development

### Code structure

The plugin is a single file (~550 lines) containing:

1. **Bootstrapping** — WooCommerce check, WPML/i18n, HPOS compatibility
2. **Priority management** — UI fields for setting a tag's priority
3. **Automatic tags** — logic for adding/removing the `Sale` and `Out of stock` tags
4. **Bulk processing** — admin screen to refresh all products
5. **Frontend rendering** — displays only the single highest-priority badge

### Developer-facing functions

```php
// Get a product's tags sorted by priority.
$tags = crystalex_get_product_badge_tags( $product_id );
// Returns: array of term objects sorted by priority (highest first)

// Each tag object exposes:
// - $tag->slug (for CSS classes)
// - $tag->name (display text)
// - $tag->term_id (tag ID)
// - $tag->priority (0-999)

// Bulk-process all products.
$count = crystalex_process_all_products();
// Returns: the number of products processed

// Manage automatic tags for a single product.
crystalex_auto_manage_product_tags( $product_id );
// Adds/removes automatic tags based on current status
```

### Hooks

```php
// Automatic processing on product save.
add_action( 'woocommerce_update_product', 'crystalex_auto_manage_product_tags' );
add_action( 'woocommerce_new_product', 'crystalex_auto_manage_product_tags' );
add_action( 'woocommerce_product_set_stock_status', 'crystalex_auto_manage_product_tags' );

// Frontend rendering.
add_action( 'woocommerce_before_shop_loop_item_title', 'crystalex_render_badges_action', 10 );
add_action( 'woocommerce_before_single_product_summary', 'crystalex_render_badges_action', 5 );

// Shortcode.
add_shortcode( 'cx_product_badges', 'crystalex_product_badges_shortcode' );
```

## License

This plugin is licensed under **GPL v2 or later**.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

Full license text: https://www.gnu.org/licenses/gpl-2.0.html

## Credits

Built by [Matěj Horák](https://crystalexcz.com), originally developed for the [Crystalex](https://crystalexcz.com) e-shop, using modern WordPress and WooCommerce standards.

## Quick overview (Version 3.0.0)

| Feature                  | Description                                                        |
|---------------------------|----------------------------------------------------------------------|
| **Display**               | Only the single highest-priority badge, clickable to the tag archive |
| **Priority**               | 0–999, configurable in the UI per tag                                |
| **Automatic tags**         | Sale (priority 80), Out of stock (priority 30)                       |
| **Automation on/off**      | Sale and Out-of-stock can be toggled independently                   |
| **Admin screen**           | CX Badge in the top-level menu                                       |
| **Bulk processing**        | Button to refresh badges for all products                            |
| **Bricks Builder**         | `[cx_product_badges]` shortcode                                      |
| **WPML**                   | Full translation support                                             |
| **HTML output**            | Clean, no inline styles                                              |
| **Compatibility**          | WordPress 6.0+, WooCommerce 7.0+, PHP 7.4+                           |

Full changelog: [../CHANGELOG.md](../CHANGELOG.md)

## Quick start

1. **Activate the plugin**
2. **Go to CX Badge** (left menu) -> **Process all products**
3. **Set tag priorities** under Products -> Tags
4. **Add CSS** to style the badges
5. **In Bricks:** use the `[cx_product_badges]` shortcode inside the product loop
