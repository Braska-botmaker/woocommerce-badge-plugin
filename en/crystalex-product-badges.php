<?php
/**
 * Plugin Name: CX Product Badges
 * Description: Automatically displays a badge based on product tags, and automatically adds the "Sale" and "Out of stock" tags based on product status. Lets you configure badge display priority via tag metadata.
 * Version: 3.0.1
 * Author: Matěj Horák
 * Author URI: https://crystalexcz.com
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
 * Checks whether WooCommerce is active.
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
 * Admin notice shown when WooCommerce is not active.
 */
function crystalex_woocommerce_missing_notice() {
	?>
	<div class="notice notice-error">
		<p><?php esc_html_e( 'CX Product Badges requires an active WooCommerce plugin.', 'crystalex-badges' ); ?></p>
	</div>
	<?php
}

/**
 * Loads the text domain for translations.
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
 * Declares compatibility with WooCommerce features (HPOS, Cart/Checkout blocks).
 */
function crystalex_declare_woocommerce_compatibility() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
	}
}
add_action( 'before_woocommerce_init', 'crystalex_declare_woocommerce_compatibility' );

/**
 * Adds the "Priority" field to the new tag form.
 */
function crystalex_add_tag_priority_field_create() {
	?>
	<div class="form-field">
		<label for="tag-priority"><?php esc_html_e( 'Badge priority', 'crystalex-badges' ); ?></label>
		<input type="number" name="tag_priority" id="tag-priority" value="0" min="0" max="999" step="1">
		<p class="description">
			<?php esc_html_e( 'The higher the number, the higher this badge ranks (0 = lowest priority)', 'crystalex-badges' ); ?>
		</p>
	</div>
	<?php
}
add_action( 'product_tag_add_form_fields', 'crystalex_add_tag_priority_field_create' );

/**
 * Adds the "Priority" field to the edit tag form.
 *
 * @param WP_Term $term Current term object.
 */
function crystalex_add_tag_priority_field_edit( $term ) {
	$priority = get_term_meta( $term->term_id, 'badge_priority', true );
	$priority = $priority !== '' ? (int) $priority : 0;
	?>
	<tr class="form-field">
		<th scope="row">
			<label for="tag-priority"><?php esc_html_e( 'Badge priority', 'crystalex-badges' ); ?></label>
		</th>
		<td>
			<input type="number" name="tag_priority" id="tag-priority" value="<?php echo esc_attr( $priority ); ?>" min="0" max="999" step="1">
			<p class="description">
				<?php esc_html_e( 'The higher the number, the higher this badge ranks (0 = lowest priority)', 'crystalex-badges' ); ?>
			</p>
		</td>
	</tr>
	<?php
}
add_action( 'product_tag_edit_form_fields', 'crystalex_add_tag_priority_field_edit' );

/**
 * Saves the priority value when a new tag is created.
 *
 * @param int $term_id Term ID.
 */
function crystalex_save_tag_priority_create( $term_id ) {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- runs only from WP core's own nonce-protected "add tag" form submission.
	if ( isset( $_POST['tag_priority'] ) ) {
		$priority = (int) $_POST['tag_priority'];
		update_term_meta( $term_id, 'badge_priority', $priority );
	}
	// phpcs:enable WordPress.Security.NonceVerification.Missing
}
add_action( 'created_product_tag', 'crystalex_save_tag_priority_create' );

/**
 * Saves the priority value when an existing tag is edited.
 *
 * @param int $term_id Term ID.
 */
function crystalex_save_tag_priority_edit( $term_id ) {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- runs only from WP core's own nonce-protected "edit tag" form submission.
	if ( isset( $_POST['tag_priority'] ) ) {
		$priority = (int) $_POST['tag_priority'];
		update_term_meta( $term_id, 'badge_priority', $priority );
	}
	// phpcs:enable WordPress.Security.NonceVerification.Missing
}
add_action( 'edited_product_tag', 'crystalex_save_tag_priority_edit' );

/**
 * Adds a "Priority" column to the tag list table.
 *
 * @param array $columns Existing columns.
 * @return array Modified columns.
 */
function crystalex_add_priority_column( $columns ) {
	$columns['badge_priority'] = __( 'Priority', 'crystalex-badges' );
	return $columns;
}
add_filter( 'manage_edit-product_tag_columns', 'crystalex_add_priority_column' );

/**
 * Renders the priority value in the tag list table column.
 *
 * @param string $content     Column content.
 * @param string $column_name Column name.
 * @param int    $term_id     Term ID.
 * @return string Modified content.
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
 * Ensures an automatic tag exists. Creates it if it doesn't.
 *
 * @param string $tag_name Tag name.
 * @param int    $priority Tag priority.
 * @return int|WP_Error Term ID or WP_Error.
 */
function crystalex_ensure_automatic_tag( $tag_name, $priority = 0 ) {
	// Check whether the tag already exists.
	$term = get_term_by( 'name', $tag_name, 'product_tag' );

	if ( $term ) {
		// Tag exists - set the priority if it isn't set yet.
		$existing_priority = get_term_meta( $term->term_id, 'badge_priority', true );
		if ( $existing_priority === '' ) {
			update_term_meta( $term->term_id, 'badge_priority', $priority );
		}
		return $term->term_id;
	}

	// Tag doesn't exist - create it.
	$result = wp_insert_term( $tag_name, 'product_tag' );

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	$term_id = $result['term_id'];
	update_term_meta( $term_id, 'badge_priority', $priority );

	return $term_id;
}

/**
 * Checks whether automatic tagging is enabled for the given badge type.
 *
 * @param string $type 'sale' or 'out_of_stock'.
 * @return bool
 */
function crystalex_is_auto_tag_enabled( $type ) {
	$option_name = 'crystalex_auto_tag_' . $type . '_enabled';
	// Default is enabled (preserves the plugin's original behaviour).
	return (bool) get_option( $option_name, '1' );
}

/**
 * Automatically manages a product's tags based on its status (SALE, OUT OF STOCK).
 * Runs when a product is saved.
 *
 * @param int $product_id Product ID.
 */
function crystalex_auto_manage_product_tags( $product_id ) {
	$product = wc_get_product( $product_id );

	if ( ! $product ) {
		return;
	}

	// Get the product's current tags.
	$current_tags = wp_get_post_terms( $product_id, 'product_tag', array( 'fields' => 'ids' ) );
	if ( is_wp_error( $current_tags ) ) {
		$current_tags = array();
	}

	// Definition of automatic tags (only if enabled in settings).
	$auto_tags = array();

	if ( crystalex_is_auto_tag_enabled( 'sale' ) ) {
		$auto_tags['sale'] = array(
			'name'      => __( 'Sale', 'crystalex-badges' ),
			'priority'  => 80,
			'condition' => $product->is_on_sale(),
		);
	}

	if ( crystalex_is_auto_tag_enabled( 'out_of_stock' ) ) {
		$auto_tags['out_of_stock'] = array(
			'name'      => __( 'Out of stock', 'crystalex-badges' ),
			'priority'  => 30,
			'condition' => ! $product->is_in_stock(),
		);
	}

	// Loop through the automatic tags and add/remove them based on their conditions.
	foreach ( $auto_tags as $slug => $data ) {
		$term_id = crystalex_ensure_automatic_tag( $data['name'], $data['priority'] );

		if ( is_wp_error( $term_id ) ) {
			continue;
		}

		$has_tag = in_array( $term_id, $current_tags, true );

		if ( $data['condition'] && ! $has_tag ) {
			// Condition met, tag missing -> add it.
			$current_tags[] = $term_id;
		} elseif ( ! $data['condition'] && $has_tag ) {
			// Condition not met, tag present -> remove it.
			$current_tags = array_diff( $current_tags, array( $term_id ) );
		}
	}

	// Update the product's tags.
	wp_set_object_terms( $product_id, array_map( 'intval', $current_tags ), 'product_tag' );
}
add_action( 'woocommerce_update_product', 'crystalex_auto_manage_product_tags' );
add_action( 'woocommerce_new_product', 'crystalex_auto_manage_product_tags' );
add_action( 'woocommerce_product_set_stock_status', 'crystalex_auto_manage_product_tags' );

/**
 * Bulk-processes all products - run manually or after activation.
 * Used to refresh existing products.
 */
function crystalex_process_all_products() {
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'fields'         => 'ids',
	);

	$product_ids = get_posts( $args );

	foreach ( $product_ids as $product_id ) {
		crystalex_auto_manage_product_tags( $product_id );
	}

	return count( $product_ids );
}

/**
 * Registers the admin menu for processing products.
 */
function crystalex_add_admin_menu() {
	// Add under WooCommerce.
	add_submenu_page(
		'woocommerce',
		__( 'Update Badges', 'crystalex-badges' ),
		__( 'Update Badges', 'crystalex-badges' ),
		'manage_options',
		'cx-update-badges',
		'crystalex_admin_page'
	);

	// Also add as a top-level menu item for easier access.
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
 * Renders the admin page for processing products.
 */
function crystalex_admin_page() {
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		return;
	}

	$processed        = 0;
	$message          = '';
	$settings_message = '';

	if ( isset( $_POST['cx_process_products'] ) && check_admin_referer( 'cx_process_products' ) ) {
		$processed = crystalex_process_all_products();
		$message = sprintf(
			/* translators: %d: number of products processed. */
			__( 'Processed %d products! Automatic badges (Sale, Out of stock) have been updated.', 'crystalex-badges' ),
			$processed
		);
	}

	if ( isset( $_POST['cx_save_settings'] ) && check_admin_referer( 'cx_save_settings' ) ) {
		update_option( 'crystalex_auto_tag_sale_enabled', isset( $_POST['cx_auto_tag_sale'] ) ? '1' : '0' );
		update_option( 'crystalex_auto_tag_out_of_stock_enabled', isset( $_POST['cx_auto_tag_out_of_stock'] ) ? '1' : '0' );
		$settings_message = __( 'Settings saved.', 'crystalex-badges' );
	}

	$sale_enabled         = crystalex_is_auto_tag_enabled( 'sale' );
	$out_of_stock_enabled = crystalex_is_auto_tag_enabled( 'out_of_stock' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'CX Product Badges - Update', 'crystalex-badges' ); ?></h1>

		<?php if ( $message ) : ?>
			<div class="notice notice-success">
				<p><?php echo esc_html( $message ); ?></p>
			</div>
		<?php endif; ?>

		<?php if ( $settings_message ) : ?>
			<div class="notice notice-success">
				<p><?php echo esc_html( $settings_message ); ?></p>
			</div>
		<?php endif; ?>

		<div class="card">
			<h2><?php esc_html_e( 'Automatic tagging settings', 'crystalex-badges' ); ?></h2>
			<p><?php esc_html_e( 'Independently enable or disable automatic badge tagging based on product status.', 'crystalex-badges' ); ?></p>
			<form method="post">
				<?php wp_nonce_field( 'cx_save_settings' ); ?>
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( '"Sale" badge', 'crystalex-badges' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="cx_auto_tag_sale" value="1" <?php checked( $sale_enabled ); ?>>
								<?php esc_html_e( 'Automatically add/remove the "Sale" tag based on the product\'s sale price', 'crystalex-badges' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( '"Out of stock" badge', 'crystalex-badges' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="cx_auto_tag_out_of_stock" value="1" <?php checked( $out_of_stock_enabled ); ?>>
								<?php esc_html_e( 'Automatically add/remove the "Out of stock" tag based on the product\'s stock status', 'crystalex-badges' ); ?>
							</label>
						</td>
					</tr>
				</table>
				<p>
					<button type="submit" name="cx_save_settings" class="button button-primary">
						<?php esc_html_e( 'Save settings', 'crystalex-badges' ); ?>
					</button>
				</p>
			</form>
		</div>

		<div class="card" style="margin-top: 20px;">
			<h2><?php esc_html_e( 'Bulk badge update', 'crystalex-badges' ); ?></h2>
			<p><?php esc_html_e( 'Click the button to process all products and add/remove automatic badges (Sale, Out of stock) based on the current product status.', 'crystalex-badges' ); ?></p>
			<p><strong><?php esc_html_e( 'Use this feature:', 'crystalex-badges' ); ?></strong></p>
			<ul style="list-style: disc; margin-left: 20px;">
				<li><?php esc_html_e( 'After first activating the plugin', 'crystalex-badges' ); ?></li>
				<li><?php esc_html_e( 'After a bulk product import', 'crystalex-badges' ); ?></li>
				<li><?php esc_html_e( 'Whenever you want to recalculate all automatic badges', 'crystalex-badges' ); ?></li>
			</ul>
			<form method="post">
				<?php wp_nonce_field( 'cx_process_products' ); ?>
				<p>
					<button type="submit" name="cx_process_products" class="button button-primary button-hero">
						<?php esc_html_e( 'Process all products', 'crystalex-badges' ); ?>
					</button>
				</p>
			</form>
		</div>

		<div class="card" style="margin-top: 20px;">
			<h2><?php esc_html_e( 'Automatic badges', 'crystalex-badges' ); ?></h2>
			<table class="widefat">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Badge', 'crystalex-badges' ); ?></th>
						<th><?php esc_html_e( 'Condition', 'crystalex-badges' ); ?></th>
						<th><?php esc_html_e( 'Priority', 'crystalex-badges' ); ?></th>
						<th><?php esc_html_e( 'Status', 'crystalex-badges' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><strong>Sale</strong></td>
						<td>Product has a sale price set</td>
						<td>80</td>
						<td>
							<?php if ( $sale_enabled ) : ?>
								<span style="color:green;"><?php esc_html_e( 'Enabled', 'crystalex-badges' ); ?></span>
							<?php else : ?>
								<span style="color:red;"><?php esc_html_e( 'Disabled', 'crystalex-badges' ); ?></span>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td><strong>Out of stock</strong></td>
						<td>Product is out of stock</td>
						<td>30</td>
						<td>
							<?php if ( $out_of_stock_enabled ) : ?>
								<span style="color:green;"><?php esc_html_e( 'Enabled', 'crystalex-badges' ); ?></span>
							<?php else : ?>
								<span style="color:red;"><?php esc_html_e( 'Disabled', 'crystalex-badges' ); ?></span>
							<?php endif; ?>
						</td>
					</tr>
				</tbody>
			</table>
			<p style="margin-top: 15px;">
				<em><?php esc_html_e( 'These badges are automatically added/removed when a product is saved.', 'crystalex-badges' ); ?></em>
			</p>
		</div>
	</div>
	<?php
}

/**
 * Reliably resolves the current product ID across different contexts.
 *
 * @return int
 */
function crystalex_get_current_product_id() {
	global $product;

	// Try the global $product first.
	if ( $product instanceof WC_Product ) {
		return (int) $product->get_id();
	}

	// Otherwise fall back to get_the_ID() + wc_get_product().
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
 * Gets all of a product's tags (product_tag), sorted by priority.
 *
 * @param int $product_id Product ID.
 * @return array Array of term objects, sorted by priority (highest first), or an empty array.
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

	// Attach the priority to each tag.
	foreach ( $terms as $term ) {
		$priority       = get_term_meta( $term->term_id, 'badge_priority', true );
		$term->priority = $priority !== '' ? (int) $priority : 0;
	}

	// Sort by priority (highest first).
	usort(
		$terms,
		function ( $a, $b ) {
			return $b->priority - $a->priority;
		}
	);

	return $terms;
}

/**
 * Renders the HTML badge for a given product.
 * CLEAN, UNSTYLED HTML OUTPUT: a div.crystalex-badges-wrapper wrapping a single
 * link or span with classes "badge badge-{slug}" and the tag name as its text.
 *
 * DISPLAY: Only the single highest-priority badge (configurable in the UI).
 * The badge links to the archive of all products with that tag – BUT only
 * where it's safe to render a link at all, see $as_link below.
 *
 * @param int  $product_id Product ID.
 * @param bool $as_link    Render the badge as a clickable <a>? On product listings
 *                          (shop/archive) the default WooCommerce template wraps the
 *                          entire product card (thumbnail + title) in a single <a>,
 *                          and this hook fires inside it – nesting another <a> is
 *                          invalid HTML, and the browser will close the outer link
 *                          early because of it, making the product card unclickable.
 *                          So the badge must render as a plain, non-linked <span>
 *                          there instead.
 */
function crystalex_render_badges_for_product( $product_id, $as_link = true ) {
	$product_id = (int) $product_id;

	if ( ! $product_id ) {
		return;
	}

	// Get the tags (already sorted by priority).
	$tags = crystalex_get_product_badge_tags( $product_id );

	if ( empty( $tags ) ) {
		return;
	}

	// SHOW ONLY THE FIRST BADGE (highest priority).
	$top_badge = $tags[0];

	$slug = isset( $top_badge->slug ) ? $top_badge->slug : '';
	$name = isset( $top_badge->name ) ? $top_badge->name : '';

	if ( ! $slug || ! $name ) {
		return;
	}

	echo '<div class="crystalex-badges-wrapper">';

	if ( $as_link ) {
		// Get the URL to the product archive for this tag.
		$term_link = get_term_link( $top_badge->term_id, 'product_tag' );
		if ( is_wp_error( $term_link ) ) {
			$term_link = '#';
		}

		printf(
			'<a href="%1$s" class="badge badge-%2$s">%3$s</a>',
			esc_url( $term_link ),
			esc_attr( $slug ),
			esc_html( $name )
		);
	} else {
		printf(
			'<span class="badge badge-%1$s">%2$s</span>',
			esc_attr( $slug ),
			esc_html( $name )
		);
	}

	echo '</div>';
}

/**
 * Hook wrapper for classic WooCommerce templates.
 * Prevents rendering the same product's badge more than once per request.
 *
 * @param bool $as_link Passed through to crystalex_render_badges_for_product(), see there.
 */
function crystalex_render_badges_action( $as_link = true ) {
	if ( ! crystalex_check_woocommerce() ) {
		return;
	}

	$product_id = crystalex_get_current_product_id();

	if ( ! $product_id ) {
		return;
	}

	static $rendered = array();

	// Badges were already rendered for this product.
	if ( isset( $rendered[ $product_id ] ) ) {
		return;
	}

	$rendered[ $product_id ] = true;

	crystalex_render_badges_for_product( $product_id, $as_link );
}

/**
 * ARCHIVE / SHOP - before the product title. The default WooCommerce template
 * wraps the entire product card in a single <a> here, so the badge renders
 * as a non-linked span to avoid an invalid, link-breaking nested anchor.
 */
function crystalex_render_badges_action_archive() {
	crystalex_render_badges_action( false );
}
add_action( 'woocommerce_before_shop_loop_item_title', 'crystalex_render_badges_action_archive', 10 );

/**
 * SINGLE PRODUCT - above the summary (above the title). There is no wrapping
 * link here, so the badge can safely be a clickable link.
 */
function crystalex_render_badges_action_single() {
	crystalex_render_badges_action( true );
}
add_action( 'woocommerce_before_single_product_summary', 'crystalex_render_badges_action_single', 5 );

/**
 * Shortcode for use in Bricks Builder (and anywhere else):
 *
 * [cx_product_badges]
 */
function crystalex_product_badges_shortcode( $atts = array() ) {
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
add_shortcode( 'cx_product_badges', 'crystalex_product_badges_shortcode' );
