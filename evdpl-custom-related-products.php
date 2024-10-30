<?php
/**
 * Plugin Name: Custom Related Products and Quickview for WC
 * Plugin URI: https://wordpress.org/plugins/custom-related-products-wc
 * Description: Custom Related Products and Quickview for WC allows you to choose related products for the particular products and allows you to enable quick view button in related products section and it also provides shortcode to for quickview of any products to add it anywhere
 * Version: 2.1.0
 * Author: Evincedev
 * Text Domain: custom-related-products-wc
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Author URI: https://evincedev.com/
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!defined('CRPQV_PLUGIN_DIR_URL'))
    define('CRPQV_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));

if (!defined('CRPQV_PLUGIN_DIR')) {
    define('CRPQV_PLUGIN_DIR', dirname(__FILE__));
}
if (!defined('CRPQV_PLUGIN_DIR_PATH')) {
    define('CRPQV_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
}

if (!defined('CRPQV_PLUGIN_FILE')) {
    define('CRPQV_PLUGIN_FILE', __FILE__);
}
if (!defined('CRPQV_PLUGIN_URL')) {
    define('CRPQV_PLUGIN_URL', plugins_url('', __FILE__));
}
if (!defined('CRPQV_BASE_NAME')) {
    define('CRPQV_BASE_NAME', plugin_basename(CRPQV_PLUGIN_FILE));
}

/**
 * Plugin Activation function
 */
function activate_evince_custom_related_products() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-evince-custom-related-products-activator.php';
    Evince_Custom_Related_Products_Activator::activate();
}

/**
 * Plugin Deactivation function
 */
function deactivate_evince_custom_related_products() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-evince-custom-related-products-deactivator.php';
    Evince_Custom_Related_Products_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_evince_custom_related_products');
register_deactivation_hook(__FILE__, 'deactivate_evince_custom_related_products');

/**
 * The core plugin class
 */
require plugin_dir_path(__FILE__) . 'includes/class-evince-custom-related-products.php';

/**
 * Begins execution of the plugin.
 * @since    1.5.0
 */
function run_evince_custom_related_products() {

    $plugin = new Evince_Custom_Related_Products();
    $plugin->run();
}

run_evince_custom_related_products();

if (!class_exists('CRPQV')) {

    class CRPQV {

        protected static $CRPQV_instance;

        function __construct() {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            add_action('admin_init', array($this, 'CRPQV_check_plugin_state'));
        }

        function CRPQV_load_admin_script_style() {
            wp_enqueue_style('CRPQV_admin_css', CRPQV_PLUGIN_URL . '/public/css/admin_style.css', false, '1.0.0');
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker-alpha', CRPQV_PLUGIN_URL . '/public/js/wp-color-picker-alpha.js', array('wp-color-picker'), '1.0.0', true);
        }

        function CRPQV_load_script_style() {
            global $woocommerce;
            $translation_array_img = CRPQV_PLUGIN_URL;
            $evince_qw_popup_loader = get_option('evince_qw_popup_loader', 'loader-1.gif');
            $evince_ajax_atc = get_option('evince_ajax_atc');

            wp_enqueue_script('wc-add-to-cart-variation');

            wp_enqueue_style('CRPQV_front_css', CRPQV_PLUGIN_URL . '/public/css/style.css', false, '1.0.0');

            wp_enqueue_script('CRPQV_front_js', CRPQV_PLUGIN_URL . '/public/js/front.js', false, '1.0.0');
            wp_localize_script('CRPQV_front_js', 'ajax_url', admin_url('admin-ajax.php'));
            wp_localize_script('CRPQV_front_js', 'object_name', $translation_array_img);
            wp_localize_script('CRPQV_front_js', 'evince_qw_popup_loader', $evince_qw_popup_loader);

            if ($evince_ajax_atc == 'yes') {
                wp_enqueue_script('CRPQV_ajax_atc_js', CRPQV_PLUGIN_URL . '/public/js/ajax-add-to-cart.js', false, '1.0.0');
            }
        }

        function CRPQV_check_plugin_state() {
            if (!( is_plugin_active('woocommerce/woocommerce.php') )) {
                set_transient(get_current_user_id() . 'evinceerror', 'message');
            }
        }

        function CRPQV_footer() {
            wp_enqueue_script('wc-add-to-cart-variation');
            wp_enqueue_script('wc-single-product');
            wp_enqueue_style('evince-custom-related-products-footer-style', CRPQV_PLUGIN_URL . '/public/css/evince-custom-related-products-public.css', false, '1.5.0', 'all');
        }

        function init() {
            add_action('admin_enqueue_scripts', array($this, 'CRPQV_load_admin_script_style'));
            add_action('wp_enqueue_scripts', array($this, 'CRPQV_load_script_style'));
            add_filter('wp_footer', array($this, 'CRPQV_footer'), 10, 2);
        }

        function includes() {
            include_once('includes/oc-evince-backend.php');
            include_once('includes/oc-evince-front.php');
        }

        public static function CRPQV_instance() {
            if (!isset(self::$CRPQV_instance)) {
                self::$CRPQV_instance = new self();
                self::$CRPQV_instance->init();
                self::$CRPQV_instance->includes();
            }
            return self::$CRPQV_instance;
        }

        /**
         * Add a link to the settings on the Plugins screen.
         */
        public static function add_settings_link( $links, $file ) {
            if ( $file === 'evdpl-custom-related-products/evdpl-custom-related-products.php' && current_user_can( 'manage_options' ) ) {
                if ( current_filter() === 'plugin_action_links' ) {
                    $url = esc_url( add_query_arg(
                            'page',
                            'evince-quick-view-settings',
                            get_admin_url() . 'admin.php'
                        ) );
                } 

                // Prevent warnings in PHP 7.0+ when a plugin uses this filter incorrectly.
                $links = (array) $links;
                $links[] = sprintf( '<a href="%s">%s</a>', $url, 'Settings' );
            }

            return $links;
        }

    }

    add_action('plugins_loaded', array('CRPQV', 'CRPQV_instance'));
    add_filter( 'plugin_action_links', array( 'CRPQV', 'add_settings_link' ), 10, 2 );
}