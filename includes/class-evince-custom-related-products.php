<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Evince_Custom_Related_Products
 * @subpackage Evince_Custom_Related_Products/includes
 */
class Evince_Custom_Related_Products {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Evince_Custom_Related_Products_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    const VERSION = '1.0.0';

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->plugin_name = 'evince-custom-related-products';
        $this->VERSION = '1.0.0';
        //const VERSION = '1.0.0';

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Evince_Custom_Related_Products_Loader. Orchestrates the hooks of the plugin.
     * - Evince_Custom_Related_Products_i18n. Defines internationalization functionality.
     * - Evince_Custom_Related_Products_Admin. Defines all hooks for the admin area.
     * - Evince_Custom_Related_Products_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-evince-custom-related-products-loader.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-evince-custom-related-products-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-evince-custom-related-products-public.php';

        $this->loader = new Evince_Custom_Related_Products_Loader();
    }


    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Evince_Custom_Related_Products_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');

        $this->loader->add_action('woocommerce_process_product_meta', $plugin_admin, 'ecrp_save_related_products', 10, 2);
        $this->loader->add_action('woocommerce_product_options_related', $plugin_admin, 'ecrp_select_related_products');

        $this->loader->add_action('admin_init', $plugin_admin, 'ecrp_do_activation_redirect');
        $this->loader->add_action('admin_head', $plugin_admin, 'ecrp_screen_remove_menus');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        $wcversion = get_option('woocommerce_version', true);
        $plugin_public = new Evince_Custom_Related_Products_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');


        if (isset($wcversion) && !empty($wcversion)) {
            if ($wcversion >= '2.3' && $wcversion < '3.0') {
                $this->loader->add_filter('woocommerce_related_products_args', $plugin_public, 'ecrp_filter_related_products');
            } else if ($wcversion >= '3.0') {
                $this->loader->add_filter('woocommerce_locate_template', $plugin_public, 'ecrp_woocommerce_locate_template', 10, 3);
                
                // version 1.3 included
                $this->loader->add_filter( 'woocommerce_product_related_posts_force_display', $plugin_public,'ecrp_display_ids_lite', 10, 2 );
                $this->loader->add_filter( 'woocommerce_product_related_posts_relate_by_category', $plugin_public, 'ecrp_remove_texonomy_lite', 10, 2 );
                $this->loader->add_filter( 'woocommerce_product_related_posts_relate_by_tag', $plugin_public,'ecrp_remove_texonomy_lite', 10, 2 );
                $this->loader->add_filter( 'woocommerce_product_related_posts_query', $plugin_public, 'ecrp_related_products_query_lite', 20, 2 );
                
            } else {
                $this->loader->add_filter('woocommerce_related_products_args', $plugin_public, 'ecrp_filter_related_products');
            }
        }

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Evince_Custom_Related_Products_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}
