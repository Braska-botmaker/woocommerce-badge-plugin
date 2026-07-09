# Changelog

Všechny významné změny tohoto pluginu jsou zaznamenány v tomto souboru.

## [3.0.0] - 2026-07-09

### Přidáno
- Samostatné zapínání/vypínání automatického tagování badge "Sleva" a "Vyprodáno" — nová karta **"Nastavení automatického tagování"** v administraci (WooCommerce → Aktualizovat Badge / CX Badge).
- Sloupec "Stav" (Zapnuto/Vypnuto) v přehledové tabulce automatických badge.
- Badge je nyní klikatelný odkaz (`<a>`) vedoucí na archiv produktů s daným tagem, místo neaktivního `<span>`.

### Změněno
- Když je automatické tagování pro daný typ (Sleva/Vyprodáno) vypnuté, plugin daný tag u produktu vůbec nespravuje (nepřidává ani neodebírá) — ruční úpravy tagů zůstávají zachovány.

## [2.2.0] - 2025-12-05

### Přidáno
- Automatické přidávání/odebírání tagů "Sleva" (podle akční ceny) a "Vyprodáno" (podle skladové dostupnosti).
- Prioritní zobrazení badge — pole "Priorita" u tagů produktu (0–999), zobrazuje se vždy jen badge s nejvyšší prioritou.
- Sloupec "Priorita" v přehledu štítků produktu.
- Admin rozhraní **CX Badge** pro hromadné přepočítání badge u všech produktů.
- Shortcode `[cx_product_badges]` pro použití v Bricks Builderu a jinde.

## [1.5.0] - 2025-12-05

### Přidáno
- Deklarace kompatibility s WooCommerce HPOS (custom order tables) a Cart/Checkout bloky.

### Změněno
- Aktualizovány požadavky na kompatibilitu: WordPress 6.0+, WooCommerce 7.0+ (tested up to 9.5), WordPress tested up to 6.7.

## [1.4.0] - 2025-12-02

### Změněno
- Odstraněny inline styly z vykreslování badge — čistý, nestylovaný HTML výstup pro plnou kontrolu přes vlastní CSS.
- Badge se nově vykreslují na více místech (archiv produktů, detail produktu) pomocí dalších WooCommerce hooků.

## [1.0.0] - 2025-12-02

### Přidáno
- První verze pluginu: automatické zobrazení badge podle tagů produktu (`product_tag`) — každý tag produktu se zobrazí jako badge před názvem na archivu produktů.
- Kontrola aktivního WooCommerce a načtení textové domény pro WPML/překlady.
