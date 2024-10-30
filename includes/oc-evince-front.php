<?php
if (!defined('ABSPATH'))
    exit;

if (!class_exists('CRPQV_front')) {

    class CRPQV_front {

        protected static $instance;

        function evince_create_qv_btn() {
            $evince_shpg_cat = esc_attr(get_option('evince_shpg_cat', 'yes'));
            $evince_shpg_tag = esc_attr(get_option('evince_shpg_tag', 'yes'));

            if (is_product()) {
                if (wp_is_mobile()) {
                    if (esc_attr(get_option('evince_mbl_btn', 'yes')) == "yes") {
                        if (esc_attr(get_option('evince_rd_btn_pos', 'after_add_cart')) == "after_add_cart") {
                            add_action('woocommerce_after_shop_loop_item', array($this, 'evince_create_button_shop'), 11);
                        } elseif (esc_attr(get_option('evince_rd_btn_pos')) == "before_add_cart") {
                            add_action('woocommerce_after_shop_loop_item', array($this, 'evince_create_button_shop'), 9);
                        }
                    }
                } else {
                    if (esc_attr(get_option('evince_rd_btn_pos', 'after_add_cart')) == "after_add_cart") {
                        add_action('woocommerce_after_shop_loop_item', array($this, 'evince_create_button_shop'), 11);
                    } elseif (esc_attr(get_option('evince_rd_btn_pos')) == "before_add_cart") {
                        add_action('woocommerce_after_shop_loop_item', array($this, 'evince_create_button_shop'), 9);
                    }
                }
            }
        }

        function evince_create_button_shop() {
            $proID = get_the_ID();
            $product = wc_get_product($proID);
            $class = '';
            if (esc_attr(get_option('evince_quk_btn', 'yes')) == "yes" || esc_attr(get_option('evince_mbl_btn', 'yes')) == "yes") {
                if(esc_attr(get_option('evince_mbl_btn', 'yes')) == "yes"){
                    if (esc_attr(get_option('evince_quk_btn', 'yes')) != "yes"){
                        $class = 'show_only_in_mobile';
                    }
                }
                ?>
                <div class="evince_qview_btn_div <?php echo $class; ?>">
                    <button class="evince_ocqkvwbtn" data-id="<?php echo $proID; ?>" proname="<?php echo $product->get_title(); ?>" style="background-color: <?php echo esc_attr(get_option('evince_btn_bg_clr', '#000000')) ?>; color: <?php echo esc_attr(get_option('evince_font_clr', '#ffffff')) ?>; padding: <?php echo esc_attr(get_option('evince_btn_padding', '8px 10px')) ?>; font-size: <?php echo esc_attr(get_option('evince_font_size', '15')) . "px" ?>;">
                        <i class="fa fa-plus" style="color: <?php echo esc_attr(get_option('evince_qvicon_clr', '#ffffff')); ?>;" aria-hidden="true"></i>
                        <?php echo esc_attr(get_option('evince_head_title', 'Quick View')); ?>
                    </button>
                </div>
                <?php
            }
        }

        function evince_popup_div_footer() {
            $evince_qw_popup_bg = esc_attr(get_option('evince_qw_popup_bg', 'rgba(0,0,0,0.4)'));
            ?>
            <div id="evince_qview_popup" class="evince_qview_popup_class" style="background-color:<?php echo $evince_qw_popup_bg; ?>;">
            </div>
            <style type="text/css">
                .evince_qview_popup_body:after {
                    background-color: <?php echo $evince_qw_popup_bg; ?>;
                }
            </style>
            <?php
        }

        function evince_template_single_custom_ratings() {
            $product_id = sanitize_text_field($_REQUEST['popup_id_pro']);
            $product = wc_get_product($product_id);
            echo wp_kses_post(wc_get_rating_html($product->get_average_rating()));
        }

        function evince_product_image() {
            global $post, $product;
            ?>
            <div class="images">
                <?php
                if (has_post_thumbnail()) {
                    $attachment_count = count($product->get_gallery_attachment_ids());
                    $gallery = $attachment_count > 0 ? '[product-gallery]' : '';
                    $props = wc_get_product_attachment_props(get_post_thumbnail_id(), $post);
                    $image = get_the_post_thumbnail($post->ID, apply_filters('single_product_large_thumbnail_size', 'shop_single'), array(
                        'title' => $props['title'],
                        'alt' => $props['alt'],
                    ));
                    echo
                    sprintf(
                            '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto%s">%s</a>',
                            esc_url($props['url']),
                            esc_attr($props['caption']),
                            $gallery,
                            $image
                    );
                } else {
                    echo sprintf('<img src="%s" alt="%s" />', esc_url(wc_placeholder_img_src()), __('Placeholder', 'woocommerce'));
                }

                do_action('evince_after_product_image');
                ?>
            </div>
            <?php
        }

        function evince_product_thumbnails() {
            global $post, $product, $woocommerce;

            $attachment_ids = $product->get_gallery_attachment_ids();

            if ($attachment_ids) {
                $loop = 0;
                $columns = apply_filters('woocommerce_product_thumbnails_columns', 3);
                ?>
                <div class="thumbnails <?php echo 'columns-' . $columns; ?>"><?php
                    foreach ($attachment_ids as $attachment_id) {

                        $classes = array('zoom');

                        if ($loop === 0 || $loop % $columns === 0) {
                            $classes[] = 'first';
                        }

                        if (( $loop + 1 ) % $columns === 0) {
                            $classes[] = 'last';
                        }

                        $image_class = implode(' ', $classes);
                        $props = wc_get_product_attachment_props($attachment_id, $post);

                        if (!$props['url']) {
                            continue;
                        }

                        echo
                        sprintf(
                                '<a href="%s" class="%s" title="%s" data-rel="prettyPhoto[product-gallery]">%s</a>',
                                esc_url($props['url']),
                                esc_attr($image_class),
                                esc_attr($props['caption']),
                                wp_get_attachment_image($attachment_id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'), 0, $props)
                        );
                        $loop++;
                    }
                    ?></div>
                <?php
            }
        }

        function evince_view_details_button() {
            $product_id = sanitize_text_field($_REQUEST['popup_id_pro']);
            ?>
            <div class="evince_qview_btn_div">
                <button onclick='window.location.href = "<?php echo get_permalink($product_id);
            ;
            ?>"' class="evince_vdbtn" style="background-color: <?php echo esc_attr(get_option('evince_btn_bg_clr', '#000000')) ?>; color: <?php echo esc_attr(get_option('evince_font_clr', '#ffffff')) ?>; padding: <?php echo esc_attr(get_option('evince_btn_padding', '8px 10px')) ?>; font-size: <?php echo esc_attr(get_option('evince_font_size', '15')) . "px" ?>;">
                    View Details
                </button>
            </div>
            <?php
        }

        function evince_popup_open_quick() {
            $evince_show_images = esc_attr(get_option('evince_show_images', 'yes'));
            $evince_show_title = esc_attr(get_option('evince_show_title', 'yes'));
            $evince_show_ratings = esc_attr(get_option('evince_show_ratings', 'yes'));
            $evince_show_price = esc_attr(get_option('evince_show_price', 'yes'));
            $evince_show_description = esc_attr(get_option('evince_show_description', 'yes'));
            $evince_show_atc = esc_attr(get_option('evince_show_atc', 'yes'));
            $evince_viewdetails_btn = esc_attr(get_option('evince_viewdetails_btn', 'yes'));
            $evince_show_meta = esc_attr(get_option('evince_show_meta', 'yes'));
            $evince_ajax_atc = esc_attr(get_option('evince_ajax_atc'));

            if ($evince_show_title == 'yes') {
                add_action('yith_wcqv_product_summary', 'woocommerce_template_single_title', 5);
            }

            if ($evince_show_ratings == 'yes') {
                add_action('yith_wcqv_product_summary', array($this, 'evince_template_single_custom_ratings'), 10);
            }

            if ($evince_show_price == 'yes') {
                add_action('yith_wcqv_product_summary', 'woocommerce_template_single_price', 15);
            }

            if ($evince_show_description == 'yes') {
                add_action('yith_wcqv_product_summary', 'woocommerce_template_single_excerpt', 20);
            }

            if ($evince_show_atc == 'yes') {
                add_action('yith_wcqv_product_summary', 'woocommerce_template_single_add_to_cart', 25);
            }

            if ($evince_viewdetails_btn == 'yes') {
                add_action('yith_wcqv_product_summary', array($this, 'evince_view_details_button'), 25);
            }

            if ($evince_show_meta == 'yes') {
                add_action('yith_wcqv_product_summary', 'woocommerce_template_single_meta', 30);
            }

            add_action('evince_product_images_gallery', 'woocommerce_show_product_sale_flash', 10);
            add_action('evince_product_images_gallery', array($this, 'evince_product_image'), 20);
            add_action('evince_after_product_image', array($this, 'evince_product_thumbnails'), 5);

            $product_id = sanitize_text_field($_REQUEST['popup_id_pro']);
            $params = array(
                'p' => $product_id,
                'post_type' => array('product', 'product_variation')
            );

            $query = new WP_Query($params);

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    echo '<div class="evince_qview_modal-content">';
                    echo '<span class="evince_qview_close"><i class="fa fa-times" style="color: #ffffff; " aria-hidden="true"></i></span>';
                    echo '<div class="evince_qview_inner_div">';
                    if ($evince_show_images == 'yes') {
                        echo '<div class="evince_qview_image">';
                        do_action('evince_product_images_gallery');
                        echo '</div>';
                        echo '<div class="evince_qview_summaary">';
                    } else {
                        echo '<div class="evince_qview_summaary evince_summaary_full">';
                    }
                    do_action('yith_wcqv_product_summary');
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            }

            wp_reset_postdata();
            die();
        }

        function evince_custom_shortcode_button($atts = '') {
            $value = shortcode_atts(array(
                'product_id' => '',
                'name' => ''
                    ), $atts);

            $proID = $value['product_id'];
            $product = wc_get_product($proID);

            if (isset($value['name']) && $value['name'] != '') {
                $button_text = esc_attr($value['name']);
            } else {
                $button_text = esc_attr(get_option('evince_head_title', 'Quick View'));
            }

            ob_start();
            ?>

            <button class="evince_ocqkvwbtn" data-id="<?php echo $proID; ?>" proname="<?php echo $product->get_title(); ?>" style="background-color: <?php echo get_option('evince_btn_bg_clr', '#000000') ?>; color: <?php echo esc_attr(get_option('evince_font_clr', '#ffffff')) ?>; padding: <?php echo esc_attr(get_option('evince_btn_padding', '8px 10px')) ?>; font-size: <?php echo esc_attr(get_option('evince_font_size', '15')) . "px" ?>;">
                <i class="fa fa-plus" style="color: <?php echo esc_attr(get_option('evince_qvicon_clr', '#ffffff')); ?>;" aria-hidden="true"></i>
            <?php echo $button_text; ?>
            </button>

            <?php
            $content = ob_get_clean();

            return $content;
        }

        function evince_woocommerce_ajax_add_to_cart() {

            $ocprodid = sanitize_text_field($_POST['product_id']);
            $ocprodqty = sanitize_text_field($_POST['quantity']);
            $ocvarid = sanitize_text_field($_POST['variation_id']);

            $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($ocprodid));
            $quantity = empty($ocprodqty) ? 1 : wc_stock_amount($ocprodqty);
            $variation_id = absint($ocvarid);
            $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
            $product_status = get_post_status($product_id);

            if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

                do_action('woocommerce_ajax_added_to_cart', $product_id);

                if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
                    wc_add_to_cart_message(array($product_id => $quantity), true);
                }

                WC_AJAX :: get_refreshed_fragments();
            } else {

                $data = array(
                    'error' => true,
                    'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

                echo wp_send_json($data);
            }

            wp_die();
        }

        function init() {
            add_action('wp_head', array($this, 'evince_create_qv_btn'));
            add_action('wp_footer', array($this, 'evince_popup_div_footer'));
            add_action('wp_ajax_evince_productsquick', array($this, 'evince_popup_open_quick'));
            add_action('wp_ajax_nopriv_evince_productsquick', array($this, 'evince_popup_open_quick'));
            add_shortcode('evince', array($this, 'evince_custom_shortcode_button'));

            add_action('wp_ajax_evince_woocommerce_ajax_add_to_cart', array($this, 'evince_woocommerce_ajax_add_to_cart'));
            add_action('wp_ajax_nopriv_evince_woocommerce_ajax_add_to_cart', array($this, 'evince_woocommerce_ajax_add_to_cart'));
        }

        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
            }
            return self::$instance;
        }

    }

    CRPQV_front::instance();
}