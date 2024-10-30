<?php

/**
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Evince_Custom_Related_Products
 * @subpackage Evince_Custom_Related_Products/includes
 */
class Evince_Custom_Related_Products_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {

        set_transient('_ecrp_screen_activation_redirect', true, 30);
        add_option('ecrp_version', Evince_Custom_Related_Products::VERSION);
    }

}
