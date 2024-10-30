<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('CRPQV_admin_menu')) {

    class CRPQV_admin_menu {

        protected static $CRPQV_instance;

        function CRPQV_submenu_page() {
           add_submenu_page('woocommerce', 'Custom Related Products Setting', 'Custom Related Products Setting', 'manage_options', 'evince-quick-view-settings', array($this, 'CRPQV_callback'));
        }


        function CRPQV_callback() {
            ?>    
                <div class="wrap">
                    <h2><u>Quick View</u></h2>
                    <?php if(isset($_REQUEST['message']) && $_REQUEST['message'] == 'success'){ ?>
                        <div class="notice notice-success is-dismissible">
                            <p><strong>Settings Saved Successfully.</strong></p>
                        </div>
                    <?php } ?>
                </div>
                <div class="evince-container">
                    <form method="post" >
                      <?php wp_nonce_field( 'evince_nonce_action', 'evince_nonce_field' ); ?>   
                        <div id="wfc-tab-general" class="tab-content current">
                            <div class="cover_div">
                              <h2>General Settings</h2>
                                <div class="evince_cover_div">
                                    <table class="evince_data_table">
                                        <tr>
                                            <th>Enable Quick View Button</th>
                                            <td>
                                                <input type="checkbox" name="evince_quk_btn" value="yes" <?php if (esc_attr(get_option( 'evince_quk_btn', 'yes' ) == "yes" )) { echo 'checked="checked"'; } ?>>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Enable Quick View Button on Mobile</th>
                                            <td>
                                                <input type="checkbox" name="evince_mbl_btn" value="yes" <?php if (esc_attr(get_option( 'evince_mbl_btn', 'yes' ) == "yes" )) { echo 'checked="checked"'; } ?>>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Show (in quick view popup)</th>
                                            <td class="evince_show_td">
                                                <input type="checkbox" name="evince_show_images" value="yes" <?php if (esc_attr(get_option( 'evince_show_images', 'yes' )) == "yes" ) { echo 'checked="checked"'; } ?>>
                                                <label>Images</label>

                                                <input type="checkbox" name="evince_show_title" value="yes" <?php if (esc_attr(get_option( 'evince_show_title', 'yes' ) == "yes" )) { echo 'checked="checked"'; } ?>>
                                                <label>Title</label>

                                                <input type="checkbox" name="evince_show_ratings" value="yes" <?php if (esc_attr(get_option( 'evince_show_ratings', 'yes' ) == "yes" )) { echo 'checked="checked"'; } ?>>
                                                <label>Ratings</label>

                                                <input type="checkbox" name="evince_show_price" value="yes" <?php if (esc_attr(get_option( 'evince_show_price', 'yes' ) == "yes" )) { echo 'checked="checked"'; } ?>>
                                                <label>Price</label>

                                                <input type="checkbox" name="evince_show_description" value="yes" <?php if (esc_attr(get_option( 'evince_show_description', 'yes' )) == "yes" ) { echo 'checked="checked"'; } ?>>
                                                <label>Description</label>

                                                <input type="checkbox" name="evince_show_atc" value="yes" <?php if (esc_attr(get_option( 'evince_show_atc', 'yes' ) == "yes" )) { echo 'checked="checked"'; } ?>>
                                                <label>Add to Cart</label>

                                                <input type="checkbox" name="evince_show_meta" value="yes" <?php if (esc_attr(get_option( 'evince_show_meta', 'yes' ) == "yes" )) { echo 'checked="checked"'; } ?>>
                                                <label>Meta</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Enable View Details Button</th>
                                            <td>
                                                <input type="checkbox" name="evince_viewdetails_btn" value="yes" <?php if (esc_attr(get_option( 'evince_viewdetails_btn', 'yes' ) == "yes" )) { echo 'checked="checked"'; } ?>>
                                                <label>View Details button with product link within popup</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Enable Lightbox</th>
                                            <td>
                                                <input type="checkbox" name="evince_lightbox_enable" value="yes" <?php if (esc_attr(get_option( 'evince_lightbox_enable', 'yes' ) == "yes") ) {echo 'checked="checked"';} ?>>
                                                <label>Product Images will open in lightbox.</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ajax Add to Cart</th>
                                            <td>
                                                <input type="checkbox" name="evince_ajax_atc" value="yes" <?php if (esc_attr(get_option( 'evince_ajax_atc' ) == "yes" )) { echo 'checked="checked"'; } ?>>
                                                <label>Add items to cart, without refreshing page.</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Quick View Popup Background Color</th>
                                            <td>
                                               <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr(get_option( 'evince_qw_popup_bg', 'rgba(0,0,0,0.4)' )); ?>" name="evince_qw_popup_bg" value="<?php echo esc_attr(get_option( 'evince_qw_popup_bg', 'rgba(0,0,0,0.4)' )); ?>"/> 
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Quick View Popup Loader</th>
                                            <td class="evince_icon_choice">
                                                <input type="radio" name="evince_qw_popup_loader" value="loader-1.gif" <?php if (get_option( 'evince_qw_popup_loader' ) == "loader-1.gif" || empty(get_option( 'evince_qw_popup_loader' ))) {echo 'checked="checked"';} ?>>
                                                <label>
                                                    <img src="<?php echo CRPQV_PLUGIN_URL . '/images/loader-1.gif' ?>" alt="">
                                                </label>
                                                <input type="radio" name="evince_qw_popup_loader" value="loader-2.gif" <?php if (get_option( 'evince_qw_popup_loader' ) == "loader-2.gif" ) {echo 'checked="checked"';} ?>>
                                                <label>
                                                    <img src="<?php echo CRPQV_PLUGIN_URL . '/images/loader-2.gif' ?>" alt="">
                                                </label>
                                                <input type="radio" name="evince_qw_popup_loader" value="loader-3.gif" <?php if (get_option( 'evince_qw_popup_loader' ) == "loader-3.gif" ) {echo 'checked="checked"';} ?>>
                                                <label>
                                                    <img src="<?php echo CRPQV_PLUGIN_URL . '/images/loader-3.gif' ?>" alt="">
                                                </label>
                                                <input type="radio" name="evince_qw_popup_loader" value="loader-4.gif" <?php if (get_option( 'evince_qw_popup_loader' ) == "loader-4.gif" ) {echo 'checked="checked"';} ?>>
                                                <label>
                                                    <img src="<?php echo CRPQV_PLUGIN_URL . '/images/loader-4.gif' ?>" alt="">
                                                </label>
                                                <input type="radio" name="evince_qw_popup_loader" value="loader-5.gif" <?php if (get_option( 'evince_qw_popup_loader' ) == "loader-5.gif" ) {echo 'checked="checked"';} ?>>
                                                <label>
                                                    <img src="<?php echo CRPQV_PLUGIN_URL . '/images/loader-5.gif' ?>" alt="">
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="cover_div">
                                <table class="evince_data_table">
                                            <h2>Quick View Button Style</h2>
                                    <tr>
                                        <th>Button Title</th>
                                        <td>
                                            <input type="text" name="evince_head_title" value="<?php echo esc_attr(get_option( 'evince_head_title', 'Quick View' )); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Font Size</th>
                                        <td>
                                            <input type="number" name="evince_font_size" value="<?php echo esc_attr(get_option( 'evince_font_size', '15' )); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Font Color</th>
                                        <td>
                                            <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr(get_option( 'evince_font_clr', '#ffffff' )); ?>" name="evince_font_clr" value="<?php echo esc_attr(get_option( 'evince_font_clr', '#ffffff' )); ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Background Color</th>
                                        <td>
                                            <input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo esc_attr(get_option( 'evince_btn_bg_clr', '#000000' )); ?>" name="evince_btn_bg_clr" value="<?php echo esc_attr(get_option( 'evince_btn_bg_clr', '#000000' )); ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Button Position</th>
                                        <td>
                                            <select name="evince_rd_btn_pos">
                                                <option value="after_add_cart" <?php if (esc_attr(get_option( 'evince_rd_btn_pos' )) == "after_add_cart" || empty(esc_attr(get_option( 'evince_rd_btn_pos' )))) {echo 'selected';} ?>>After Add To Cart</option>
                                                <option value="before_add_cart" <?php if (esc_attr(get_option( 'evince_rd_btn_pos' )) == "before_add_cart" ) {echo 'selected';} ?>>Before Add To Cart</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Button Padding</th>
                                        <td>
                                            <input type="text" name="evince_btn_padding" value="<?php echo esc_attr(get_option( 'evince_btn_padding', '8px 10px' )); ?>">
                                            <span>insert value in px(ex. 6px 8px)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Quick View button Icon</th>
                                        <td>
                                            <input type="checkbox" name="evince_qvicon_enable" value="yes" <?php if (esc_attr(get_option( 'evince_qvicon_enable') ) == "yes" || empty(esc_attr(get_option( 'evince_qvicon_enable' )))) {echo 'checked="checked"';} ?>>
                                            <label>Enable Quick View Button Icon</label>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="cover_div">
                             <h2>Quick View Button Using Shortcode</h2>
                                <div class="evince_cover_div">
                                    <div class="evincescode">
                                        <p>You can create custom quick view button using shortcode, you can add button to any spot of the page or post to allow visitor to see the quick view of any specific product in your shop.</p>

                                        <p><strong>[evince product_id="{product id}" name="{button name}"]</strong></p>
                                        
                                        <p><em>eg. [evince product_id="15" name="Discover Now"] for the product with ID is 15.</em></p>
                                    </div>
                                </div>
                            </div>
                        <input type="hidden" name="action" value="evince_save_option">
                        <input type="submit" value="Save Changes" name="submit" class="button-primary" id="wfc-btn-space">
                    </form>
                </div>
            <?php
        }


        function CRPQV_save_options() {
            if( current_user_can('administrator') ) { 
                if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'evince_save_option'){
                    if(!isset( $_POST['evince_nonce_field'] ) || !wp_verify_nonce( $_POST['evince_nonce_field'], 'evince_nonce_action' )) {
                        
                        echo 'Sorry, your nonce did not verify.';
                        exit;

                    } else {

                        if(isset($_REQUEST['evince_mbl_btn']) && !empty($_REQUEST['evince_mbl_btn'])) {
                            $mbl_btn = sanitize_text_field( $_REQUEST['evince_mbl_btn'] );
                        } else {
                            $mbl_btn = 'no';
                        }

                        if(isset($_REQUEST['evince_quk_btn']) && !empty($_REQUEST['evince_quk_btn'])) {
                            $quk_btn = sanitize_text_field( $_REQUEST['evince_quk_btn'] );
                        } else {
                            $quk_btn = 'no';
                        }

                        if(isset($_REQUEST['evince_show_images']) && !empty($_REQUEST['evince_show_images'])) {
                            $evince_show_images = sanitize_text_field( $_REQUEST['evince_show_images'] );
                        } else {
                            $evince_show_images = 'no';
                        }

                        if(isset($_REQUEST['evince_show_title']) && !empty($_REQUEST['evince_show_title'])) {
                            $evince_show_title = sanitize_text_field( $_REQUEST['evince_show_title'] );
                        } else {
                            $evince_show_title = 'no';
                        }

                        if(isset($_REQUEST['evince_show_ratings']) && !empty($_REQUEST['evince_show_ratings'])) {
                            $evince_show_ratings = sanitize_text_field( $_REQUEST['evince_show_ratings'] );
                        } else {
                            $evince_show_ratings = 'no';
                        }

                        if(isset($_REQUEST['evince_show_price']) && !empty($_REQUEST['evince_show_price'])) {
                            $evince_show_price = sanitize_text_field( $_REQUEST['evince_show_price'] );
                        } else {
                            $evince_show_price = 'no';
                        }

                        if(isset($_REQUEST['evince_show_description']) && !empty($_REQUEST['evince_show_description'])) {
                            $evince_show_description = sanitize_text_field( $_REQUEST['evince_show_description'] );
                        } else {
                            $evince_show_description = 'no';
                        }

                        if(isset($_REQUEST['evince_show_atc']) && !empty($_REQUEST['evince_show_atc'])) {
                            $evince_show_atc = sanitize_text_field( $_REQUEST['evince_show_atc'] );
                        } else {
                            $evince_show_atc = 'no';
                        }

                        if(isset($_REQUEST['evince_show_meta']) && !empty($_REQUEST['evince_show_meta'])) {
                            $evince_show_meta = sanitize_text_field( $_REQUEST['evince_show_meta'] );
                        } else {
                            $evince_show_meta = 'no';
                        }

                        if(isset($_REQUEST['evince_viewdetails_btn']) && !empty($_REQUEST['evince_viewdetails_btn'])) {
                            $evince_viewdetails_btn = sanitize_text_field( $_REQUEST['evince_viewdetails_btn'] );
                        } else {
                            $evince_viewdetails_btn = 'no';
                        }

                        if(isset($_REQUEST['evince_ajax_atc']) && !empty($_REQUEST['evince_ajax_atc'])) {
                            $evince_ajax_atc = sanitize_text_field( $_REQUEST['evince_ajax_atc'] );
                        } else {
                            $evince_ajax_atc = 'no';
                        }

                        if(isset($_REQUEST['evince_shpg_cat']) && !empty($_REQUEST['evince_shpg_cat'])) {
                            $evince_shpg_cat = sanitize_text_field( $_REQUEST['evince_shpg_cat'] );
                        } else {
                            $evince_shpg_cat = 'no';
                        }

                        if(isset($_REQUEST['evince_shpg_tag']) && !empty($_REQUEST['evince_shpg_tag'])) {
                            $evince_shpg_tag = sanitize_text_field( $_REQUEST['evince_shpg_tag'] );
                        } else {
                            $evince_shpg_tag = 'no';
                        }

                        if(isset($_REQUEST['evince_qvicon_enable']) && !empty($_REQUEST['evince_qvicon_enable'])) {
                            $evince_qvicon_enable = sanitize_text_field( $_REQUEST['evince_qvicon_enable'] );
                        } else {
                            $evince_qvicon_enable = 'no';
                        }

                        if(isset($_REQUEST['evince_lightbox_enable']) && !empty($_REQUEST['evince_lightbox_enable'])) 
                        {
                            $evince_lightbox_enable = sanitize_text_field( $_REQUEST['evince_lightbox_enable'] );
                        } else {
                            $evince_lightbox_enable = 'no';
                        }

                        update_option('evince_mbl_btn', $mbl_btn, 'yes');
                        update_option('evince_quk_btn', $quk_btn, 'yes');
                        update_option('evince_show_images', $evince_show_images, 'yes');
                        update_option('evince_show_title', $evince_show_title, 'yes');
                        update_option('evince_show_ratings', $evince_show_ratings, 'yes');
                        update_option('evince_show_price', $evince_show_price, 'yes');
                        update_option('evince_show_description', $evince_show_description, 'yes');
                        update_option('evince_show_atc', $evince_show_atc, 'yes');
                        update_option('evince_show_meta', $evince_show_meta, 'yes');
                        update_option('evince_viewdetails_btn', $evince_viewdetails_btn, 'yes');
                        update_option('evince_ajax_atc', $evince_ajax_atc, 'yes');
                        update_option('evince_shpg_cat', $evince_shpg_cat, 'yes');
                        update_option('evince_shpg_tag', $evince_shpg_tag, 'yes');
                        update_option('evince_qvicon_enable', $evince_qvicon_enable, 'yes');
                        update_option('evince_lightbox_enable', $evince_lightbox_enable, 'yes');                        
                        update_option('evince_qw_popup_loader', sanitize_text_field( $_REQUEST['evince_qw_popup_loader'] ), 'yes');
                        update_option('evince_head_title', sanitize_text_field( $_REQUEST['evince_head_title'] ), 'yes');
                        update_option('evince_font_clr',  sanitize_text_field( $_REQUEST['evince_font_clr'] ), 'yes');
                        update_option('evince_font_size', sanitize_text_field( $_REQUEST['evince_font_size'] ), 'yes');
                        update_option('evince_btn_bg_clr',sanitize_text_field( $_REQUEST['evince_btn_bg_clr'] ), 'yes');
                        update_option('evince_rd_btn_pos', sanitize_text_field( $_REQUEST['evince_rd_btn_pos'] ),'yes');
                        update_option('evince_btn_padding',sanitize_text_field( $_REQUEST['evince_btn_padding']),'yes');
                        update_option('evince_qw_popup_bg',sanitize_text_field( $_REQUEST['evince_qw_popup_bg']),'yes');
                    }
                }
            }
        }


        function init() {
            add_action( 'admin_menu',  array($this, 'CRPQV_submenu_page'));
            add_action( 'init',  array($this, 'CRPQV_save_options'));
        }


        public static function CRPQV_instance() {
            if (!isset(self::$CRPQV_instance)) {
                self::$CRPQV_instance = new self();
                self::$CRPQV_instance->init();
            }
            return self::$CRPQV_instance;
        }
    }
    CRPQV_admin_menu::CRPQV_instance();
}