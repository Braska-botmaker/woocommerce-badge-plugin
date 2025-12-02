# Crystalex Product Badges

WordPress plugin pro WooCommerce, který automaticky zobrazuje badge podle tagů produktu. Žádné přednastavené badge, žádný meta box - čistě dynamický systém!

## 📋 Popis

Plugin automaticky zobrazuje každý tag produktu jako badge. Jednoduše vytvoříte tag v WooCommerce, přiřadíte ho k produktu a badge se objeví na frontendu.

**Každý tag = badge** - maximální flexibilita bez omezení!

## ✨ Funkce

- ✅ **100% dynamický systém** - žádné přednastavené badge
- ✅ **Automatické zobrazení** - každý tag produktu = badge
- ✅ **Neomezený počet badge** - vytvořte kolik tagů chcete
- ✅ **Plná podpora WPML překladů** - tagy i badge se automaticky překládají
- ✅ **Čistý HTML výstup** - bez vlastních stylů, plná kontrola přes CSS
- ✅ **Jednoduché použití** - funguje okamžitě po aktivaci
- ✅ **Kompatibilní s WooCommerce** - používá standardní hooky a taxonomie

## 📦 Požadavky

- WordPress 5.0 nebo novější
- WooCommerce 4.0 nebo novější
- PHP 7.4 nebo novější

## 🚀 Instalace

1. Stáhněte soubor `crystalex-product-badges.php`
2. Nahrajte ho do složky `/wp-content/plugins/crystalex-product-badges/`
3. Aktivujte plugin v administraci WordPress přes menu **Pluginy**
4. Plugin funguje okamžitě - žádné nastavení není potřeba!

## 📖 Použití

### Krok 1: Vytvoření tagů

1. V administraci WordPress přejděte do **Produkty → Štítky produktu**
2. Vytvořte nový tag (např. "Novinka", "Akce", "Bestseller", "Limitovaná edice")
3. Tag se automaticky stane dostupným jako badge

### Krok 2: Přiřazení tagů k produktům

1. Otevřete editaci produktu
2. V sekci **"Štítky produktu"** vpravo přidejte tagy
3. Uložte produkt

### Krok 3: Hotovo!

Badge se automaticky zobrazí na frontendu před názvem produktu.

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

Plugin vykresluje badge jako HTML elementy bez vlastních stylů:

```html
<div class="crystalex-badges-wrapper">
    <span class="badge badge-novinka">Novinka</span>
    <span class="badge badge-limitka">Limitka</span>
</div>
```

### CSS třídy

Každá badge má dvě CSS třídy:
- `badge` - společná třída pro všechny badge
- `badge-{slug}` - specifická třída podle typu badge

**Příklad vlastního stylingu:**

```css
/* Základní styl */
.crystalex-badges-wrapper {
    display: flex;
    gap: 8px;
    margin-bottom: 10px;
}

.badge {
    display: inline-block;
    padding: 4px 10px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    border-radius: 4px;
    background: #000;
    color: #fff;
}

/* Specifické barvy */
.badge-novinka {
    background: #4CAF50;
}

.badge-limitka {
    background: #FF9800;
}

.badge-bestseller {
    background: #2196F3;
}

.badge-exkluzivni {
    background: #9C27B0;
}
```

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

## 🔧 Přizpůsobení

### Změna pozice badge

Badge jsou standardně zavěšené na hook:
```php
woocommerce_before_shop_loop_item_title
```

Pro změnu pozice upravte v souboru `crystalex-product-badges.php`:

```php
// Příklad: Badge za cenou produktu
add_action( 'woocommerce_after_shop_loop_item', 'crystalex_render_badges', 10 );
```

### Přidání vlastních badge

Jednoduše vytvořte nový tag v **Produkty → Štítky produktu**! Není potřeba žádný kód měnit.

**Příklad:** Chcete přidat badge "Výroba v ČR"
1. Vytvořte tag "Výroba v ČR"
2. Přiřaďte ho k produktům
3. Badge se automaticky zobrazí ✓

### Hromadná úprava tagů

Můžete použít standardní WordPress funkce:

1. **Produkty → Hromadné úpravy** - přidejte tag více produktům najednou
2. **Import/Export** - importujte produkty s tagy pomocí WooCommerce CSV
3. **Rychlá úprava** - v seznamu produktů klikněte na "Rychlá úprava" a přidejte tagy

## 🔌 WooCommerce Hooky

Plugin používá následující WooCommerce hooky:

| Hook | Použití |
|------|---------|
| `add_meta_boxes` | Přidání meta boxu do admin rozhraní |
| `save_post` | Uložení dat při ukládání produktu |
| `woocommerce_before_shop_loop_item_title` | Vykreslení badge na frontendu |

## 📝 Uložení dat

Plugin **neukládá žádná vlastní data**. Používá standardní WooCommerce taxonomii `product_tag`.

Veškerá data jsou uložená standardním WordPress způsobem v tabulce `wp_terms` a `wp_term_relationships`.

## 🐛 Řešení problémů

### Badge se nezobrazují na frontendu

1. Zkontrolujte, zda je produkt publikovaný
2. Ověřte, že produkt má přiřazené tagy (v editaci produktu)
3. Zkontrolujte, zda vaše téma podporuje hook `woocommerce_before_shop_loop_item_title`
4. Zkuste vymazat cache (pokud používáte caching plugin)

### Badge se zobrazují i u produktů, kde je nechci

Odeberte příslušné tagy z produktu. Plugin zobrazuje badge pouze pro produkty s tagy.

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

## 🎯 Proč tento plugin?

**Jednoduchost** - Žádné složité nastavení, žádné meta boxy. Vytvořte tag, přiřaďte ho k produktu a máte badge.

**Flexibilita** - Neomezený počet badge, žádné přednastavené typy. Vytvořte si badge jaké potřebujete.

**Standardní přístup** - Používá WooCommerce tagy, které už znáte. Žádné nové koncepty k učení.

**WPML ready** - Plná podpora vícejazyčných webů bez extra konfigurace.

---

