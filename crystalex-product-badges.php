<?php
/**
 * Plugin Name: Crystalex Product Badges
 * Description: Automaticky zobrazuje badge podle tagů produktu - každý tag = badge
 * Version: 2.0.0
 * Author: Crystalex
 * Author URI: https://crystalex.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: crystalex-badges
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * WC requires at least: 4.0
 * WC tested up to: 8.0
 */

// Zabránit přímému přístupu k souboru
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Kontrola, zda je WooCommerce aktivní
 */
function crystalex_check_woocommerce() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'crystalex_woocommerce_missing_notice' );
		return false;
	}
	return true;
}
add_action( 'plugins_loaded', 'crystalex_check_woocommerce' );

/**
 * Načtení textové domény pro překlady (WPML)
 */
function crystalex_load_textdomain() {
	load_plugin_textdomain( 'crystalex-badges', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'crystalex_load_textdomain' );

/**
 * Zobrazení upozornění, pokud není WooCommerce aktivní
 */
function crystalex_woocommerce_missing_notice() {
	?>
	<div class="notice notice-error">
		<p><?php esc_html_e( 'Crystalex Product Badges vyžaduje aktivní WooCommerce plugin.', 'crystalex-badges' ); ?></p>
	</div>
	<?php
}

/**
 * Získání všech tagů produktu pro vykreslení badge
 * 
 * @param int $product_id ID produktu
 * @return array Pole s objekty tagů (slug, name)
 */
function crystalex_get_product_badge_tags( $product_id ) {
	// Získat tagy produktu (WooCommerce používá taxonomii 'product_tag')
	$product_tags = wp_get_post_terms( $product_id, 'product_tag', array( 'fields' => 'all' ) );
	
	if ( is_wp_error( $product_tags ) || empty( $product_tags ) ) {
		return array();
	}
	
	return $product_tags;
}

/**
 * Vykreslení badge na frontendu
 * Každý tag produktu se zobrazí jako badge
 */
function crystalex_render_badges() {
	global $product;
	
	// Kontrola, zda máme produkt
	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		return;
	}
	
	$product_id = $product->get_id();
	
	// Získání tagů produktu
	$product_tags = crystalex_get_product_badge_tags( $product_id );
	
	// Pokud produkt nemá žádné tagy, nic nevykresluj
	if ( empty( $product_tags ) ) {
		return;
	}
	
	// Vykreslení badge
	echo '<div class="crystalex-badges-wrapper" style="margin: 10px 0;">';
	
	foreach ( $product_tags as $tag ) {
		// Vykreslení badge jako span element
		// Slug tagu = CSS třída, Name tagu = zobrazený text
		printf(
			'<span class="badge badge-%s" style="display: inline-block; padding: 4px 10px; margin: 0 5px 5px 0; font-size: 12px; font-weight: 600; background: #333; color: #fff; border-radius: 3px;">%s</span>',
			esc_attr( $tag->slug ),
			esc_html( $tag->name )
		);
	}
	
	echo '</div>';
}

// Zkusit více hooků pro různé typy zobrazení produktů
add_action( 'woocommerce_before_shop_loop_item_title', 'crystalex_render_badges', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'crystalex_render_badges', 5 );
add_action( 'woocommerce_before_single_product_summary', 'crystalex_render_badges', 5 );
add_action( 'woocommerce_single_product_summary', 'crystalex_render_badges', 5 );
