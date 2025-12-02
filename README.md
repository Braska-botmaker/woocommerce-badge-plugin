# CX Product Badges

WordPress plugin pro WooCommerce, který automaticky zobrazuje badge podle tagů produktu. Žádné přednastavené badge, žádné inline styly - čistě dynamický systém!

## 📋 Popis

Plugin automaticky zobrazuje každý tag produktu jako badge. Vytvoříte tag v WooCommerce, přiřadíte ho k produktu a badge se zobrazí pomocí hooků nebo shortcode.

**Každý tag = badge** - maximální flexibilita, stylování plně pod vaší kontrolou!

## ✨ Funkce

- ✅ **100% dynamický systém** - žádné přednastavené badge
- ✅ **Čistý HTML výstup** - BEZ inline stylů, stylujte si badge zcela podle sebe
- ✅ **Neomezený počet badge** - vytvořte kolik tagů chcete
- ✅ **Bricks Builder kompatibilní** - shortcode `[cx_product_badges]`
- ✅ **Plná podpora WPML překladů** - tagy i badge se automaticky překládají
- ✅ **Prevence duplicit** - badge se renderují pouze jednou
- ✅ **Robustní detekce produktu** - funguje i bez global $product

## 📦 Požadavky

- WordPress 5.0 nebo novější
- WooCommerce 4.0 nebo novější
- PHP 7.4 nebo novější

## 🚀 Instalace

1. Stáhněte ZIP soubor pluginu
2. V administraci WordPress: **Pluginy → Přidat nový → Nahrát plugin**
3. Vyberte ZIP soubor a klikněte na **Instalovat**
4. **Aktivujte** plugin
5. Plugin funguje okamžitě - žádné nastavení!

## 📖 Použití

### Varianta A: Standardní WooCommerce (automaticky)

Plugin automaticky zobrazí badge na těchto místech:
- **Výpis produktů** (shop, archiv, kategorie) - před názvem
- **Detail produktu** - nad popisem

### Varianta B: Bricks Builder (shortcode)

**DŮLEŽITÉ:** Pro použití v Bricks Builderu musíte vložit shortcode **mezi Dynamic Data fields**.

1. V Bricks editoru otevřete template produktu
2. Přidejte element **Shortcode**
3. Do pole "Shortcode" vložte: `[cx_product_badges]`
4. Badge se zobrazí přesně tam, kde máte shortcode element

### Přidání tagů k produktům

1. **Produkty → Štítky produktu** - vytvořte tagy (např. "Novinka", "Akce", "Bestseller")
2. V editaci produktu přiřaďte tagy v sekci **"Štítky produktu"**
3. Uložte produkt
4. Badge se automaticky zobrazí!

## 💡 Příklady použití

| Vytvoříte tag | Zobrazí se badge |
|---------------|------------------|
| "Novinka" | `<span class="badge badge-novinka">Novinka</span>` |
| "AKCE -50%" | `<span class="badge badge-akce-50">AKCE -50%</span>` |
| "Bestseller" | `<span class="badge badge-bestseller">Bestseller</span>` |
| "Limitovaná edice" | `<span class="badge badge-limitovana-edice">Limitovaná edice</span>` |

**Výhody tohoto přístupu:**
- ✅ Neomezený počet různých badge
- ✅ Snadná správa přes standardní WooCommerce rozhraní
- ✅ Jeden tag můžete použít na více produktů najednou
- ✅ Změna názvu tagu se projeví na všech produktech

## 🎨 Styling

**DŮLEŽITÉ:** Plugin vykresluje **čistý HTML bez jakýchkoliv inline stylů**!

Výstup vypadá takto:
```html
<div class="crystalex-badges-wrapper">
    <span class="badge badge-novinka">Novinka</span>
    <span class="badge badge-akce">Akce</span>
</div>
```

### CSS třídy pro stylování

Každá badge má tyto CSS třídy:
- `.crystalex-badges-wrapper` - obal všech badge
- `.badge` - společná třída pro všechny badge
- `.badge-{slug}` - specifická třída podle slugu tagu (např. `.badge-novinka`, `.badge-akce`)

### Příklad stylování

Přidejte si do svého CSS (v tématu, Bricks CSS editoru, nebo Additional CSS):

```css
/* Wrapper badge */
.crystalex-badges-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 12px;
}

/* Základní styl všech badge */
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

/* Specifické barvy pro jednotlivé tagy */
.badge-novinka {
    background: #4CAF50;
    color: #fff;
}

.badge-akce {
    background: #FF5722;
    color: #fff;
}

.badge-bestseller {
    background: #2196F3;
    color: #fff;
}

.badge-limitka {
    background: #FF9800;
    color: #000;
}

.badge-exkluzivni {
    background: #9C27B0;
    color: #fff;
}
```

**V Bricks Builderu:** CSS můžete přidat do **Bricks → Settings → Custom Code → CSS**

## 🌍 WPML Podpora

Plugin má **plnou automatickou podporu WPML překladů**!

### Jak fungují překlady

WPML automaticky překládá názvy tagů, takže badge se zobrazují v jazyce aktuální stránky.

**Postup nastavení:**

1. V administraci přejděte do **WPML → Překlad taxonomií**
2. Ujistěte se, že taxonomie **"Štítky produktu" (product_tag)** je nastavená jako **"Přeložitelná"**
3. Vytvořte tag v hlavním jazyce (např. "Novinka")
4. V WPML editoru tagů přidejte překlad tagu:
   - 🇨🇿 Češ tina: "Novinka"
   - 🇬🇧 Angličtina: "New"
   - 🇩🇪 Němčina: "Neuheit"

### Automatické zobrazení

- Na české verzi webu se zobrazí: `<span class="badge badge-novinka">Novinka</span>`
- Na anglické verzi: `<span class="badge badge-new">New</span>`
- Na německé verzi: `<span class="badge badge-neuheit">Neuheit</span>`

**WPML se stará o vše automaticky - žádná další konfigurace není potřeba!**

## 🔧 Pokročilé použití

### Shortcode v Bricks Builderu

**KRITICKÉ:** Shortcode `[cx_product_badges]` musíte vložit **jako Dynamic Data field**, ne jako běžný text!

**Správný postup:**
1. V Bricks editoru přidejte element **Shortcode**
2. Do pole vložte: `[cx_product_badges]`
3. Badge se zobrazí v product loopu

**Nebo použijte v textu s Dynamic Data:**
1. Přidejte Text element
2. Klikněte na ikonu **{x}** (Dynamic Data)
3. Vyberte **Shortcode**
4. Vložte: `[cx_product_badges]`

### Přidání nových badge

Jednoduše vytvořte nový tag v **Produkty → Štítky produktu** - není potřeba měnit žádný kód!

**Příklad:** Badge "Česká výroba"
1. Vytvořte tag "Česká výroba" (slug bude automaticky `ceska-vyroba`)
2. Přiřaďte tag k produktům
3. Přidejte CSS pro styling:
```css
.badge-ceska-vyroba {
    background: #D32F2F;
    color: #fff;
}
```

### Hromadná úprava tagů

- **Produkty → Hromadné úpravy** - přidejte tagy více produktům najednou
- **Import/Export** - importujte produkty s tagy pomocí WooCommerce CSV
- **Rychlá úprava** - v seznamu produktů klikněte "Rychlá úprava"

## 🔌 Technické detaily

### WooCommerce Hooky

Plugin používá tyto hooky pro automatické zobrazení:

| Hook | Kdy se použije |
|------|----------------|
| `woocommerce_before_shop_loop_item_title` | Výpis produktů (shop, archiv, kategorie) |
| `woocommerce_before_single_product_summary` | Detail produktu |

### Shortcode

| Shortcode | Použití |
|-----------|---------|
| `[cx_product_badges]` | Pro Bricks Builder a custom templates |

## 📝 Uložení dat

Plugin **neukládá žádná vlastní data**. Používá standardní WooCommerce taxonomii `product_tag`.

Veškerá data jsou uložená standardním WordPress způsobem v tabulce `wp_terms` a `wp_term_relationships`.

## 🐛 Řešení problémů

### Badge se nezobrazují

**Standardní WooCommerce:**
1. Zkontrolujte, že produkt má přiřazené tagy
2. Vyčistěte cache (pokud používáte caching plugin)
3. Ověřte, že je plugin aktivní

**V Bricks Builderu:**
1. **DŮLEŽITÉ:** Shortcode `[cx_product_badges]` musí být vložen jako **Dynamic Data field**, ne jako prostý text
2. Použijte element **Shortcode** a vložte `[cx_product_badges]`
3. Ujistěte se, že je shortcode uvnitř product loop

### Badge nemají styling

Plugin **záměrně** nevkládá žádné inline styly! Musíte si přidat vlastní CSS:
```css
.badge {
    display: inline-block;
    padding: 5px 10px;
    background: #333;
    color: #fff;
}
```

### Badge se zobrazují víckrát

Plugin má zabudovanou prevenci duplicit. Pokud se badge opakují, může to být způsobeno:
- Máte více elementů se shortcode
- Cache problém - vyčistěte cache

### WPML překlady nefungují

1. Ověřte, že je WPML aktivní
2. V **WPML → Nastavení** zkontrolujte, že taxonomie "Štítky produktu" je nastavená jako přeložitelná
3. Přeložte jednotlivé tagy v **WPML → Překlad taxonomií**
4. Vyčistěte cache

## 🔄 Aktualizace

Plugin je navržený jako standalone soubor, takže:
1. Zazálohujte si původní soubor před aktualizací
2. Nahrajte novou verzi
3. Vaše data zůstanou zachována (jsou uložená v databázi)

## 👨‍💻 Vývoj

### Struktura kódu

Plugin má velmi jednoduchou strukturu (pouze ~110 řádků):

1. **Základní nastavení** - kontrola WooCommerce, načtení WPML překladů
2. **Získání tagů** - funkce pro načtení tagů produktu
3. **Frontend vykreslení** - zobrazení badge na základě tagů

### Funkce pro vývojáře

```php
// Získání všech tagů produktu jako objektů
$tags = crystalex_get_product_badge_tags( $product_id );
// Vrací: array s objekty (slug, name, term_id...)

// Každý tag obsahuje:
// - $tag->slug (pro CSS třídy)
// - $tag->name (zobrazený text)
// - $tag->term_id (ID tagu)
```

## 📄 Licence

Tento plugin je licencovaný pod **GPL v2 nebo novější**.

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

Plné znění licence: https://www.gnu.org/licenses/gpl-2.0.html



## 🙏 Poděkování

Plugin vytvořen pro Crystalex s použitím moderních WordPress a WooCommerce standardů.

---

---

## 📌 Rychlý přehled

| Vlastnost | Popis |
|-----------|-------|
| **HTML výstup** | Čistý HTML bez inline stylů |
| **Bricks Builder** | Shortcode `[cx_product_badges]` v Dynamic Data |
| **Styling** | 100% pod vaší kontrolou přes CSS |
| **Tagy → Badge** | Každý WooCommerce tag = automatický badge |
| **WPML** | Plná podpora překladů |
| **Duplicity** | Automatická prevence |

---

