# CX Product Badges

WordPress plugin pro WooCommerce s automatickým řízením badge podle tagů produktu + inteligentní přidávání tagů SLEVA a VYPRODÁNO.

## 📋 Popis

Plugin automaticky zobrazuje **pouze 1 badge s nejvyšší prioritou** podle tagů produktu. Navíc automaticky přidává a odebírá tagy SLEVA a VYPRODÁNO podle stavu produktu.

**Prioritní zobrazení + automatické tagy** - maximální flexibilita a automatizace!

## ✨ Funkce

- ✅ **Prioritní zobrazení** - zobrazí se pouze 1 badge s nejvyšší prioritou
- ✅ **Nastavitelná priorita v UI** - každý tag má prioritu 0-999
- ✅ **Automatické tagy** - SLEVA a VYPRODÁNO se přidávají/odebírají automaticky
- ✅ **Čistý HTML výstup** - BEZ inline stylů, stylujte si badge podle sebe
- ✅ **Bricks Builder kompatibilní** - shortcode `[cx_product_badges]`
- ✅ **Plná podpora WPML překladů** - tagy i badge se automaticky překládají
- ✅ **Prevence duplicit** - badge se renderují pouze jednou
- ✅ **Robustní detekce produktu** - funguje i bez global $product
- ✅ **Hromadné zpracování** - admin rozhraní pro aktualizaci všech produktů

## 📦 Požadavky

- WordPress 6.0 nebo novější
- WooCommerce 7.0 nebo novější
- PHP 7.4 nebo novější

## 🚀 Instalace

1. Stáhněte ZIP soubor pluginu
2. V administraci WordPress: **Pluginy → Přidat nový → Nahrát plugin**
3. Vyberte ZIP soubor a klikněte na **Instalovat**
4. **Aktivujte** plugin
5. Jděte do **CX Badge** v levém menu a klikněte **"Zpracovat všechny produkty"**
6. Nastavte priority tagů v **Produkty → Štítky produktu**

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

### Nastavení priority tagů

1. **Produkty → Štítky produktu** - vytvořte nebo editujte tag
2. Nastavte **Prioritu badge** (0-999, čím vyšší číslo, tím dříve se zobrazí)
3. Uložte tag
4. Zobrazí se pouze badge s **nejvyšší prioritou**

### Automatické tagy

Plugin automaticky přidává/odebírá tyto tagy:

| Tag | Priorita | Kdy se přidá | Kdy se odebere |
|-----|----------|--------------|----------------|
| **Sleva** | 80 | Produkt má nastavenou akční cenu | Akční cena odebrána |
| **Vyprodáno** | 30 | Produkt není skladem | Produkt je zpět skladem |

**Automatika funguje:**
- ✅ Při uložení produktu v administraci
- ✅ Při změně stavu skladu
- ✅ Po kliknutí na "Zpracovat všechny produkty" v **CX Badge**

### Přidání vlastních tagů k produktům

1. **Produkty → Štítky produktu** - vytvořte vlastní tagy (např. "Novinka", "Bestseller")
2. Nastavte **Prioritu** (např. Novinka: 100, Bestseller: 60)
3. V editaci produktu přiřaďte tagy v sekci **"Štítky produktu"**
4. Uložte produkt
5. Zobrazí se badge s **nejvyšší prioritou**

## 💡 Příklad priority

Produkt má tyto tagy:
- **Novinka** (priorita: 100)
- **Sleva** (priorita: 80, automatický)
- **Bestseller** (priorita: 50)

→ **Zobrazí se pouze "Novinka"** (má nejvyšší prioritu)

## 🎯 Příklady použití

| Tag | Priorita | Kdy použít |
|-----|----------|------------|
| **AKCE** | 100 | Speciální akce, má přednost před vším |
| **Sleva** | 80 | Automatický, běžná sleva |
| **Novinka** | 70 | Nové produkty |
| **Bestseller** | 50 | Nejprodávanější produkty |
| **Vyprodáno** | 30 | Automatický, není skladem |
| **Limitka** | 20 | Omezená edice |

**HTML výstup:**
```html
<div class="crystalex-badges-wrapper">
    <span class="badge badge-akce">AKCE</span>
</div>
```

**Výhody:**
- ✅ Vždy se zobrazí **pouze 1 nejdůležitější badge**
- ✅ Prioritu kontrolujete v UI
- ✅ Automatické tagy Sleva a Vyprodáno
- ✅ Jeden tag můžete použít na více produktů
- ✅ Změna názvu nebo priority se projeví na všech produktech

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

### Hromadné zpracování produktů

**CX Badge → Zpracovat všechny produkty**

Použijte tuto funkci:
- ✅ Po první aktivaci pluginu
- ✅ Po hromadném importu produktů přes FTP
- ✅ Když chcete přepočítat automatické badge
- ✅ Pro aktualizaci existujících produktů

Tlačítko projde všechny produkty a přidá/odebere automatické tagy SLEVA a VYPRODÁNO.

### Hromadná úprava tagů

- **Produkty → Hromadné úpravy** - přidejte tagy více produktům najednou
- **Import/Export** - importujte produkty s tagy pomocí WooCommerce CSV
- **Rychlá úprava** - v seznamu produktů klikněte "Rychlá úprava"

## 🔌 Technické detaily

### Prioritní zobrazení

- Zobrazuje se **pouze 1 badge** - ten s nejvyšší prioritou
- Priorita: 0-999 (čím vyšší, tím důležitější)
- Nastavitelná v UI u každého tagu

### Automatické tagy

| Tag | Priorita | Podmínka | Akce |
|-----|----------|----------|------|
| Sleva | 80 | `is_on_sale()` | Přidá/odebere automaticky |
| Vyprodáno | 30 | `!is_in_stock()` | Přidá/odebere automaticky |

**Kdy se spustí:**
- ✅ Při uložení produktu (`woocommerce_update_product`)
- ✅ Při vytvoření produktu (`woocommerce_new_product`)
- ✅ Při změně stavu skladu (`woocommerce_product_set_stock_status`)
- ✅ Manuálně přes admin rozhraní

### WooCommerce Hooky

Plugin používá tyto hooky pro zobrazení:

| Hook | Kdy se použije |
|------|----------------|
| `woocommerce_before_shop_loop_item_title` | Výpis produktů (shop, archiv, kategorie) |
| `woocommerce_before_single_product_summary` | Detail produktu |

### Shortcode

| Shortcode | Použití |
|-----------|---------|
| `[cx_product_badges]` | Pro Bricks Builder a custom templates |

### Admin rozhraní

**Umístění:** CX Badge (levé menu) nebo WooCommerce → Aktualizovat Badge

**Funkce:**
- Hromadné zpracování všech produktů
- Přehled automatických badge
- Statistiky zpracovaných produktů

## 📝 Uložení dat

Plugin **neukládá žádná vlastní data**. Používá standardní WooCommerce taxonomii `product_tag`.

Veškerá data jsou uložená standardním WordPress způsobem v tabulce `wp_terms` a `wp_term_relationships`.

## 🐛 Řešení problémů

### Badge se nezobrazují

**1. Zkontrolujte tagy:**
- Produkt má přiřazené tagy v **Produkty → Edit → Štítky produktu**?
- Tagy mají nastavenou prioritu v **Produkty → Štítky produktu**?

**2. Zpracujte produkty:**
- Jděte do **CX Badge** v menu
- Klikněte **"Zpracovat všechny produkty"**
- Automatické tagy (Sleva, Vyprodáno) se přidají

**3. Cache:**
- Vyčistěte cache (pokud používáte caching plugin)
- Obnovte stránku (Ctrl+F5)

**V Bricks Builderu:**
1. **DŮLEŽITÉ:** Shortcode `[cx_product_badges]` musí být jako **Dynamic Data field**
2. Použijte element **Shortcode** a vložte `[cx_product_badges]`
3. Ujistěte se, že je shortcode uvnitř product loop

### Automatické tagy nefungují

**Po importu produktů:**
1. Jděte do **CX Badge**
2. Klikněte **"Zpracovat všechny produkty"**
3. Tagy se přidají/odeberou podle stavu

**Při změně stavu:**
- Automatické tagy se přidávají/odebírají **při uložení produktu**
- Uložte produkt znovu pro aktualizaci tagů

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

### Zobrazuje se špatný badge

**Zkontrolujte priority:**
1. Jděte do **Produkty → Štítky produktu**
2. Sloupec **"Priorita"** ukazuje hodnoty
3. Zobrazí se badge s **nejvyšší prioritou**

**Příklad:**
- AKCE (priorita 100) ← zobrazí se
- Sleva (priorita 80)
- Novinka (priorita 50)

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

Plugin obsahuje (~550 řádků):

1. **Základní nastavení** - kontrola WooCommerce, WPML, HPOS kompatibilita
2. **Správa priority** - UI pole pro nastavení priority u tagů
3. **Automatické tagy** - systém pro přidávání/odebírání tagů SLEVA a VYPRODÁNO
4. **Hromadné zpracování** - admin rozhraní pro aktualizaci všech produktů
5. **Frontend vykreslení** - zobrazení pouze 1 badge s nejvyšší prioritou

### Funkce pro vývojáře

```php
// Získání tagů seřazených podle priority
$tags = crystalex_get_product_badge_tags( $product_id );
// Vrací: array s objekty seřazenými podle priority (nejvyšší první)

// Každý tag obsahuje:
// - $tag->slug (pro CSS třídy)
// - $tag->name (zobrazený text)
// - $tag->term_id (ID tagu)
// - $tag->priority (priorita 0-999)

// Hromadné zpracování všech produktů
$count = crystalex_process_all_products();
// Vrací: počet zpracovaných produktů

// Automatická správa tagů jednoho produktu
crystalex_auto_manage_product_tags( $product_id );
// Přidá/odebere automatické tagy podle stavu
```

### Hooky

```php
// Automatické zpracování při uložení produktu
add_action( 'woocommerce_update_product', 'crystalex_auto_manage_product_tags' );
add_action( 'woocommerce_new_product', 'crystalex_auto_manage_product_tags' );
add_action( 'woocommerce_product_set_stock_status', 'crystalex_auto_manage_product_tags' );

// Frontend zobrazení
add_action( 'woocommerce_before_shop_loop_item_title', 'crystalex_render_badges_action', 10 );
add_action( 'woocommerce_before_single_product_summary', 'crystalex_render_badges_action', 5 );

// Shortcode
add_shortcode( 'cx_product_badges', 'cx_product_badges_shortcode' );
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

## 📌 Rychlý přehled (Verze 2.2.0)

| Vlastnost | Popis |
|-----------|-------|
| **Zobrazení** | Pouze 1 badge s nejvyšší prioritou |
| **Priorita** | 0-999, nastavitelná v UI u každého tagu |
| **Automatické tagy** | SLEVA (priorita 80), VYPRODÁNO (priorita 30) |
| **Admin rozhraní** | CX Badge v hlavním menu |
| **Hromadné zpracování** | Tlačítko pro aktualizaci všech produktů |
| **Bricks Builder** | Shortcode `[cx_product_badges]` |
| **WPML** | Plná podpora překladů |
| **HTML výstup** | Čistý, bez inline stylů |
| **Kompatibilita** | WordPress 6.0+, WooCommerce 7.0+, PHP 7.4+ |

---

## 📌 Rychlý start

1. **Aktivujte plugin**
2. **Jděte do CX Badge** (levé menu) → **Zpracovat všechny produkty**
3. **Nastavte priority tagů** v Produkty → Štítky produktu
4. **Přidejte CSS** pro styling badge
5. **V Bricks:** Použijte shortcode `[cx_product_badges]` v product loop

---

## 📌 Rychlý přehled (starší verze)

| Vlastnost | Popis |
|-----------|-------|
| **HTML výstup** | Čistý HTML bez inline stylů |
| **Bricks Builder** | Shortcode `[cx_product_badges]` v Dynamic Data |
| **Styling** | 100% pod vaší kontrolou přes CSS |
| **Tagy → Badge** | Každý WooCommerce tag = automatický badge |
| **WPML** | Plná podpora překladů |
| **Duplicity** | Automatická prevence |

---

