<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Evince_Custom_Related_Products
 * @subpackage Evince_Custom_Related_Products/admin
 */
class Evince_Custom_Related_Products_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/evince-custom-related-products-admin.css', array(), $this->version, 'all' );

	}

	 /**
         * Add relatd products selector to edit product section
         */
        function ecrp_select_related_products() {
            global $post, $woocommerce;
            $product_ids = array_filter(array_map('absint', (array) get_post_meta($post->ID, '_ecrp_related_ids', true)));
            ?>
            <div class="options_group">
                <?php if ($woocommerce->version >= '2.3' && $woocommerce->version < '3.0') : ?>
                    <p class="form-field"><label for="related_ids"><?php _e('Custom Related Products', 'woocommerce'); ?></label>
                        <input type="hidden" class="wc-product-search" style="width: 50%;" id="ecrp_related_ids" name="ecrp_related_ids" data-placeholder="<?php _e('Search for a product&hellip;', 'woocommerce'); ?>" data-action="woocommerce_json_search_products" data-multiple="true" data-selected="<?php
                        $json_ids = array();
                        foreach ($product_ids as $product_id) {
                            $product = wc_get_product($product_id);
                            if (is_object($product) && is_callable(array($product, 'get_formatted_name'))) {
                                $json_ids[$product_id] = wp_kses_post($product->get_formatted_name());
                            }
                        }
                        echo esc_attr(json_encode($json_ids));
                        ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" /> <img class="help_tip" data-tip='<?php _e('Related products are displayed on the product detail page.', 'woocommerce') ?>' src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />
                    </p>
                <?php else: ?>
                    <p class="form-field"><label for="related_ids"><?php _e('Custom Related Products', 'woocommerce'); ?></label>
                         <select id="ecrp_related_ids" 
                        class="wc-product-search" 
                        name="ecrp_related_ids[]" 
                        multiple="multiple" 
                        style="width: 400px;" 
                       data-placeholder="<?php _e('Search for a product&hellip;', 'woocommerce'); ?>" 
                       data-action="woocommerce_json_search_products_and_variations">
                            <?php
                            foreach ($product_ids as $product_id) {
                                $product = wc_get_product($product_id);
                                if ( is_object( $product ) )
                                    echo '<option value="' . esc_attr($product_id) . '" selected="selected">' . wp_kses_post($product->get_formatted_name()) . '</option>';
                            }
                            ?>
                        </select> <img class="help_tip" data-tip='<?php _e('Related products are displayed on the product detail page.', 'woocommerce') ?>' src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />
                    </p>
                <?php endif; ?>
            </div>
            <?php
        }
	
	 
        /**
         * Save related products on product edit screen
         */
        function ecrp_save_related_products($post_id, $post) {
            global $woocommerce;
            if (isset($_POST['ecrp_related_ids'])) {
                 if ($woocommerce->version >= '2.3' && $woocommerce->version < '3.0') {
                    $related = isset($_POST['ecrp_related_ids']) ? array_filter(array_map('intval', explode(',', $_POST['ecrp_related_ids']))) : array();
                } else {
                    $related = array();
                  
					$ids =  array_map( 'sanitize_text_field', wp_unslash( $_POST['ecrp_related_ids'] ) );
                    foreach ($ids as $id) {
                        if ($id && $id > 0) {
                            $related[] = $id;
                        }
                    }
                }
                update_post_meta($post_id, '_ecrp_related_ids', $related);
            } else {
                delete_post_meta($post_id, '_ecrp_related_ids');
            }
        }
		
		public function ecrp_do_activation_redirect() {
        // Return if no activation redirect
        if (!get_transient('_ecrp_screen_activation_redirect')) {
            return;
        }

        // Delete the redirect transient
        delete_transient('_ecrp_screen_activation_redirect');

        // Return if activating from network, or bulk
        if (is_network_admin() || isset($_GET['activate-multi'])) {
            return;
        }
    }

    public function ecrp_screen_remove_menus() {
        remove_submenu_page('index.php', 'ecrp-about');
    }

}