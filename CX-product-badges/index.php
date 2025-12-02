<?php
/**
 * Plugin Name: CX Product Badges
 * Description: Automaticky zobrazuje badge podle tagů produktu - každý tag = badge
 * Version: 1.4.0
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Zkontroluje, zda je WooCommerce aktivní.
 *
 * @return bool
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
 * Admin notice pokud není WooCommerce aktivní.
 */
function crystalex_woocommerce_missing_notice() {
	?>
	<div class="notice notice-error">
		<p><?php esc_html_e( 'CX Product Badges vyžaduje aktivní WooCommerce plugin.', 'crystalex-badges' ); ?></p>
	</div>
	<?php
}

/**
 * Načtení textové domény pro překlady.
 */
function crystalex_load_textdomain() {
	load_plugin_textdomain(
		'crystalex-badges',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}
add_action( 'plugins_loaded', 'crystalex_load_textdomain' );

/**
 * Bezpečné zjištění aktuálního product ID v různých kontextech.
 *
 * @return int
 */
function crystalex_get_current_product_id() {
	global $product;

	// 1) global $product
	if ( $product instanceof WC_Product ) {
		return (int) $product->get_id();
	}

	// 2) get_the_ID + wc_get_product
	$post_id = get_the_ID();
	if ( $post_id ) {
		$maybe_product = wc_get_product( $post_id );
		if ( $maybe_product instanceof WC_Product ) {
			return (int) $maybe_product->get_id();
		}
	}

	return 0;
}

/**
 * Získá všechny tagy produktu (product_tag).
 *
 * @param int $product_id Product ID.
 * @return array Pole term objektů nebo prázdné pole.
 */
function crystalex_get_product_badge_tags( $product_id ) {
	$product_id = (int) $product_id;

	if ( ! $product_id ) {
		return array();
	}

	$terms = wp_get_post_terms(
		$product_id,
		'product_tag',
		array(
			'fields' => 'all',
		)
	);

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return array();
	}

	return $terms;
}

/**
 * Vykreslí HTML badge pro daný produkt.
 * ČISTÝ, NESTYLOVANÝ HTML VÝSTUP:
 *
 * <div class="crystalex-badges-wrapper">
 *   <span class="badge badge-slug">Název tagu</span>
 * </div>
 *
 * @param int $product_id Product ID.
 */
function crystalex_render_badges_for_product( $product_id ) {
	$product_id = (int) $product_id;

	if ( ! $product_id ) {
		return;
	}

	$tags = crystalex_get_product_badge_tags( $product_id );

	if ( empty( $tags ) ) {
		return;
	}

	echo '<div class="crystalex-badges-wrapper">';

	foreach ( $tags as $tag ) {
		$slug = isset( $tag->slug ) ? $tag->slug : '';
		$name = isset( $tag->name ) ? $tag->name : '';

		if ( ! $slug || ! $name ) {
			continue;
		}

		printf(
			'<span class="badge badge-%1$s">%2$s</span>',
			esc_attr( $slug ),
			esc_html( $name )
		);
	}

	echo '</div>';
}

/**
 * Hook varianta – pro klasické WooCommerce templaty.
 * Zamezí vícenásobnému renderu pro stejný produkt v jednom requestu.
 */
function crystalex_render_badges_action() {
	if ( ! crystalex_check_woocommerce() ) {
		return;
	}

	$product_id = crystalex_get_current_product_id();

	if ( ! $product_id ) {
		return;
	}

	static $rendered = array();

	// Už na tenhle produkt byly badge vypsané
	if ( isset( $rendered[ $product_id ] ) ) {
		return;
	}

	$rendered[ $product_id ] = true;

	crystalex_render_badges_for_product( $product_id );
}

// ARCHIV / SHOP – před titlem produktu.
add_action( 'woocommerce_before_shop_loop_item_title', 'crystalex_render_badges_action', 10 );

// SINGLE PRODUCT – nad summary (nad názvem).
add_action( 'woocommerce_before_single_product_summary', 'crystalex_render_badges_action', 5 );

/**
 * Shortcode pro použití v Bricksu (a kdekoliv jinde):
 *
 * [cx_product_badges]
 */
function cx_product_badges_shortcode( $atts = array() ) {
	if ( ! crystalex_check_woocommerce() ) {
		return '';
	}

	$product_id = crystalex_get_current_product_id();

	if ( ! $product_id ) {
		return '';
	}

	ob_start();
	crystalex_render_badges_for_product( $product_id );
	return ob_get_clean();
}
add_shortcode( 'cx_product_badges', 'cx_product_badges_shortcode' );
