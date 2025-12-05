<?php
/**
 * Plugin Name: CX Product Badges
 * Description: Automaticky přidává tagy SLEVA a VYPRODÁNO podle stavu produktu + prioritní zobrazení
 * Version: 2.2.0
 * Author: Crystalex
 * Author URI: https://crystalex.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: crystalex-badges
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * WC requires at least: 7.0
 * WC tested up to: 9.5
 * Tested up to: 6.7
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
 * Deklarace kompatibility s WooCommerce features (HPOS, Cart/Checkout blocks).
 */
function crystalex_declare_woocommerce_compatibility() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
	}
}
add_action( 'before_woocommerce_init', 'crystalex_declare_woocommerce_compatibility' );

/**
 * Přidá pole "Priorita" do formuláře pro vytvoření nového tagu.
 */
function crystalex_add_tag_priority_field_create() {
	?>
	<div class="form-field">
		<label for="tag-priority"><?php esc_html_e( 'Priorita badge', 'crystalex-badges' ); ?></label>
		<input type="number" name="tag_priority" id="tag-priority" value="0" min="0" max="999" step="1">
		<p class="description">
			<?php esc_html_e( 'Čím vyšší číslo, tím dříve se badge zobrazí (0 = nejnižší priorita)', 'crystalex-badges' ); ?>
		</p>
	</div>
	<?php
}
add_action( 'product_tag_add_form_fields', 'crystalex_add_tag_priority_field_create' );

/**
 * Přidá pole "Priorita" do formuláře pro editaci existujícího tagu.
 *
 * @param WP_Term $term Aktuální term objekt.
 */
function crystalex_add_tag_priority_field_edit( $term ) {
	$priority = get_term_meta( $term->term_id, 'badge_priority', true );
	$priority = $priority !== '' ? (int) $priority : 0;
	?>
	<tr class="form-field">
		<th scope="row">
			<label for="tag-priority"><?php esc_html_e( 'Priorita badge', 'crystalex-badges' ); ?></label>
		</th>
		<td>
			<input type="number" name="tag_priority" id="tag-priority" value="<?php echo esc_attr( $priority ); ?>" min="0" max="999" step="1">
			<p class="description">
				<?php esc_html_e( 'Čím vyšší číslo, tím dříve se badge zobrazí (0 = nejnižší priorita)', 'crystalex-badges' ); ?>
			</p>
		</td>
	</tr>
	<?php
}
add_action( 'product_tag_edit_form_fields', 'crystalex_add_tag_priority_field_edit' );

/**
 * Uloží hodnotu priority při vytvoření nového tagu.
 *
 * @param int $term_id Term ID.
 */
function crystalex_save_tag_priority_create( $term_id ) {
	if ( isset( $_POST['tag_priority'] ) ) {
		$priority = (int) $_POST['tag_priority'];
		update_term_meta( $term_id, 'badge_priority', $priority );
	}
}
add_action( 'created_product_tag', 'crystalex_save_tag_priority_create' );

/**
 * Uloží hodnotu priority při editaci existujícího tagu.
 *
 * @param int $term_id Term ID.
 */
function crystalex_save_tag_priority_edit( $term_id ) {
	if ( isset( $_POST['tag_priority'] ) ) {
		$priority = (int) $_POST['tag_priority'];
		update_term_meta( $term_id, 'badge_priority', $priority );
	}
}
add_action( 'edited_product_tag', 'crystalex_save_tag_priority_edit' );

/**
 * Přidá sloupec "Priorita" do tabulky tagů.
 *
 * @param array $columns Existující sloupce.
 * @return array Upravené sloupce.
 */
function crystalex_add_priority_column( $columns ) {
	$columns['badge_priority'] = __( 'Priorita', 'crystalex-badges' );
	return $columns;
}
add_filter( 'manage_edit-product_tag_columns', 'crystalex_add_priority_column' );

/**
 * Zobrazí hodnotu priority ve sloupci tabulky tagů.
 *
 * @param string $content Obsah sloupce.
 * @param string $column_name Název sloupce.
 * @param int $term_id Term ID.
 * @return string Upravený obsah.
 */
function crystalex_display_priority_column( $content, $column_name, $term_id ) {
	if ( 'badge_priority' === $column_name ) {
		$priority = get_term_meta( $term_id, 'badge_priority', true );
		$priority = $priority !== '' ? (int) $priority : 0;
		return '<strong>' . esc_html( $priority ) . '</strong>';
	}
	return $content;
}
add_filter( 'manage_product_tag_custom_column', 'crystalex_display_priority_column', 10, 3 );

/**
 * Zajistí existenci automatického tagu. Pokud neexistuje, vytvoří ho.
 *
 * @param string $tag_name Název tagu.
 * @param int $priority Priorita tagu.
 * @return int|WP_Error Term ID nebo WP_Error.
 */
function crystalex_ensure_automatic_tag( $tag_name, $priority = 0 ) {
	// Zkontroluj, jestli tag existuje
	$term = get_term_by( 'name', $tag_name, 'product_tag' );
	
	if ( $term ) {
		// Tag existuje - aktualizuj prioritu, pokud není nastavená
		$existing_priority = get_term_meta( $term->term_id, 'badge_priority', true );
		if ( $existing_priority === '' ) {
			update_term_meta( $term->term_id, 'badge_priority', $priority );
		}
		return $term->term_id;
	}
	
	// Tag neexistuje - vytvoř ho
	$result = wp_insert_term( $tag_name, 'product_tag' );
	
	if ( is_wp_error( $result ) ) {
		return $result;
	}
	
	$term_id = $result['term_id'];
	update_term_meta( $term_id, 'badge_priority', $priority );
	
	return $term_id;
}

/**
 * Automaticky spravuje tagy produktu podle stavu (SLEVA, VYPRODÁNO).
 * Volá se při uložení produktu.
 *
 * @param int $product_id Product ID.
 */
function crystalex_auto_manage_product_tags( $product_id ) {
	$product = wc_get_product( $product_id );
	
	if ( ! $product ) {
		return;
	}
	
	// Získej aktuální tagy produktu
	$current_tags = wp_get_post_terms( $product_id, 'product_tag', array( 'fields' => 'ids' ) );
	if ( is_wp_error( $current_tags ) ) {
		$current_tags = array();
	}
	
	// Definice automatických tagů
	$auto_tags = array(
		'sleva' => array(
			'name' => __( 'Sleva', 'crystalex-badges' ),
			'priority' => 80,
			'condition' => $product->is_on_sale(),
		),
		'vyprodano' => array(
			'name' => __( 'Vyprodáno', 'crystalex-badges' ),
			'priority' => 30,
			'condition' => ! $product->is_in_stock(),
		),
	);
	
	// Projdi automatické tagy a přidej/odeber je podle podmínek
	foreach ( $auto_tags as $slug => $data ) {
		$term_id = crystalex_ensure_automatic_tag( $data['name'], $data['priority'] );
		
		if ( is_wp_error( $term_id ) ) {
			continue;
		}
		
		$has_tag = in_array( $term_id, $current_tags, true );
		
		if ( $data['condition'] && ! $has_tag ) {
			// Podmínka splněna, tag nemá -> přidej
			$current_tags[] = $term_id;
		} elseif ( ! $data['condition'] && $has_tag ) {
			// Podmínka nesplněna, tag má -> odeber
			$current_tags = array_diff( $current_tags, array( $term_id ) );
		}
	}
	
	// Aktualizuj tagy produktu
	wp_set_object_terms( $product_id, array_map( 'intval', $current_tags ), 'product_tag' );
}
add_action( 'woocommerce_update_product', 'crystalex_auto_manage_product_tags' );
add_action( 'woocommerce_new_product', 'crystalex_auto_manage_product_tags' );
add_action( 'woocommerce_product_set_stock_status', 'crystalex_auto_manage_product_tags' );

/**
 * Hromadné zpracování všech produktů - spustí se manuálně nebo při aktivaci.
 * Pro aktualizaci existujících produktů.
 */
function crystalex_process_all_products() {
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'fields' => 'ids',
	);
	
	$product_ids = get_posts( $args );
	
	foreach ( $product_ids as $product_id ) {
		crystalex_auto_manage_product_tags( $product_id );
	}
	
	return count( $product_ids );
}

/**
 * Admin menu pro zpracování produktů.
 */
function crystalex_add_admin_menu() {
	// Přidej pod WooCommerce
	add_submenu_page(
		'woocommerce',
		__( 'Aktualizovat Badge', 'crystalex-badges' ),
		__( 'Aktualizovat Badge', 'crystalex-badges' ),
		'manage_options',
		'cx-update-badges',
		'crystalex_admin_page'
	);
	
	// Přidej i jako samostatnou položku v hlavním menu (pro jistotu)
	add_menu_page(
		__( 'CX Badge', 'crystalex-badges' ),
		__( 'CX Badge', 'crystalex-badges' ),
		'manage_options',
		'cx-badges-main',
		'crystalex_admin_page',
		'dashicons-tag',
		58
	);
}
add_action( 'admin_menu', 'crystalex_add_admin_menu', 99 );

/**
 * Admin stránka pro zpracování produktů.
 */
function crystalex_admin_page() {
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		return;
	}
	
	$processed = 0;
	$message = '';
	
	if ( isset( $_POST['cx_process_products'] ) && check_admin_referer( 'cx_process_products' ) ) {
		$processed = crystalex_process_all_products();
		$message = sprintf(
			__( 'Zpracováno %d produktů! Automatické badge (Sleva, Vyprodáno) byly aktualizovány.', 'crystalex-badges' ),
			$processed
		);
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'CX Product Badges - Aktualizace', 'crystalex-badges' ); ?></h1>
		
		<?php if ( $message ) : ?>
			<div class="notice notice-success">
				<p><?php echo esc_html( $message ); ?></p>
			</div>
		<?php endif; ?>
		
		<div class="card">
			<h2><?php esc_html_e( 'Hromadná aktualizace badge', 'crystalex-badges' ); ?></h2>
			<p><?php esc_html_e( 'Kliknutím na tlačítko zpracujete všechny produkty a přidáte/odeberete automatické badge (Sleva, Vyprodáno) podle aktuálního stavu produktů.', 'crystalex-badges' ); ?></p>
			<p><strong><?php esc_html_e( 'Použijte tuto funkci:', 'crystalex-badges' ); ?></strong></p>
			<ul style="list-style: disc; margin-left: 20px;">
				<li><?php esc_html_e( 'Po první aktivaci pluginu', 'crystalex-badges' ); ?></li>
				<li><?php esc_html_e( 'Po hromadném importu produktů', 'crystalex-badges' ); ?></li>
				<li><?php esc_html_e( 'Když chcete přepočítat všechny automatické badge', 'crystalex-badges' ); ?></li>
			</ul>
			<form method="post">
				<?php wp_nonce_field( 'cx_process_products' ); ?>
				<p>
					<button type="submit" name="cx_process_products" class="button button-primary button-hero">
						<?php esc_html_e( 'Zpracovat všechny produkty', 'crystalex-badges' ); ?>
					</button>
				</p>
			</form>
		</div>
		
		<div class="card" style="margin-top: 20px;">
			<h2><?php esc_html_e( 'Automatické badge', 'crystalex-badges' ); ?></h2>
			<table class="widefat">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Badge', 'crystalex-badges' ); ?></th>
						<th><?php esc_html_e( 'Podmínka', 'crystalex-badges' ); ?></th>
						<th><?php esc_html_e( 'Priorita', 'crystalex-badges' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><strong>Sleva</strong></td>
						<td>Produkt má nastavenou akční cenu (sale price)</td>
						<td>80</td>
					</tr>
					<tr>
						<td><strong>Vyprodáno</strong></td>
						<td>Produkt není skladem (out of stock)</td>
						<td>30</td>
					</tr>
				</tbody>
			</table>
			<p style="margin-top: 15px;">
				<em><?php esc_html_e( 'Tyto badge se automaticky přidávají/odebírají při uložení produktu.', 'crystalex-badges' ); ?></em>
			</p>
		</div>
	</div>
	<?php
}

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
 * Získá všechny tagy produktu (product_tag) seřazené podle priority.
 *
 * @param int $product_id Product ID.
 * @return array Pole term objektů nebo prázdné pole, seřazené podle priority (highest first).
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

	// Přidej prioritu k jednotlivým tagům
	foreach ( $terms as $term ) {
		$priority = get_term_meta( $term->term_id, 'badge_priority', true );
		$term->priority = $priority !== '' ? (int) $priority : 0;
	}

	// Seřaď podle priority (nejvyšší první)
	usort( $terms, function( $a, $b ) {
		return $b->priority - $a->priority;
	} );

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
 * ZOBRAZENÍ: Pouze 1 badge s nejvyšší prioritou (nastavitelnou v UI)
 *
 * @param int $product_id Product ID.
 */
function crystalex_render_badges_for_product( $product_id ) {
	$product_id = (int) $product_id;

	if ( ! $product_id ) {
		return;
	}

	// Získej tagy (už seřazené podle priority)
	$tags = crystalex_get_product_badge_tags( $product_id );

	if ( empty( $tags ) ) {
		return;
	}

	// ZOBRAZ POUZE PRVNÍ BADGE (nejvyšší priorita)
	$top_badge = $tags[0];

	$slug = isset( $top_badge->slug ) ? $top_badge->slug : '';
	$name = isset( $top_badge->name ) ? $top_badge->name : '';

	if ( ! $slug || ! $name ) {
		return;
	}

	echo '<div class="crystalex-badges-wrapper">';
	printf(
		'<span class="badge badge-%1$s">%2$s</span>',
		esc_attr( $slug ),
		esc_html( $name )
	);
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
