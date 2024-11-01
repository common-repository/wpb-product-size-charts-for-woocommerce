<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/**
 * WooCommerce Configuration
 */
class WPB_PSC_WooCommerce_Handler {

    public function __construct() {

        $show_chart_as      = wpb_psc_get_option( 'wpb_psc_chart_as', 'wpb_psc_general_settings', 'button' );
        $button_place       = wpb_psc_get_option( 'wpb_psc_button_place', 'wpb_psc_general_settings', 40 );
        $size_charts        = get_posts( array( 'post_type' => 'wpb_psc_size_chart', 'numberposts' => -1 ) );

        $hook_to_use        = 'woocommerce_single_product_summary';
        if( $button_place == 'wpb_psc_hook' ){
            $hook_to_use = 'wpb_psc_woocommerce_single_product_summary';
        }


        
        if( isset($size_charts) && !empty($size_charts) ){

            if( $show_chart_as == 'button' ){

                foreach ($size_charts as $key => $size_chart) {
                    
                    add_action( apply_filters( 'wpb_psc_woo_single_position', $hook_to_use ), function() use ( $size_chart, $key ) {

                        global $product;
                        $size_id = $size_chart->ID;
                        $wpb_psc_disable = get_post_meta( $product->get_id(), '_wpb_psc_disable', true );
                        $product_size_chart = get_post_meta( $product->get_id(), '_wpb_psc_size_chart', true );
                        $set_for_all_products = get_post_meta( $size_id, '_wpb_psc_set_for_all_products', true );

                        $text = get_post_meta( $size_id, '_wpb_psc__btn_text', true );
                        $products = get_post_meta( $size_id, '_wpb_psc_products', true );
                        $product_categories = get_post_meta( $size_id, '_wpb_psc__product_categories', true );

                        if($product_size_chart){
                            $size_id    = $product_size_chart;
                            $text       = get_the_title( $size_id );
                        }

                        if ( isset($product_size_chart) && $product_size_chart != '' && $key != 1 ) {
                            return false;
                        }

                        if( $wpb_psc_disable == 'yes' ){
                            return false;
                        }

                        if( $set_for_all_products == '' ){

                            if( isset($products) && !empty($products) ){
                                $products = explode(',', $products);
                                if( !is_single( $products ) ){
                                    return false;
                                }
                            }

                            if( isset($product_categories) && !empty($product_categories) ){
                                $product_categories = explode(',', $product_categories);

                                if( !has_term( $product_categories, 'product_cat') ){
                                    return false;
                                }
                            }
                        }

                        echo do_shortcode('[wpb-product-size-chart size_id="'. esc_attr($size_id) .'" text="'. esc_html($text) .'"]');

                    }, apply_filters( 'wpb_psc_woo_single_priority', $button_place ) );

                }
            }elseif( $show_chart_as == 'tab' ){

                add_filter( 'woocommerce_product_tabs', function( $tabs ) use ( $size_charts ){
                    foreach ($size_charts as $key => $size_chart) {

                        global $product;
                        $size_id = $size_chart->ID;
                        $psc_tabs = array();
                        $wpb_psc_disable = get_post_meta( $product->get_id(), '_wpb_psc_disable', true );
                        $product_size_chart = get_post_meta( $product->get_id(), '_wpb_psc_size_chart', true );
                        $set_for_all_products = get_post_meta( $size_id, '_wpb_psc_set_for_all_products', true );
                        
                        $text = get_post_meta( $size_id, '_wpb_psc__btn_text', true );
                        $tab_priority = get_post_meta( $size_id, '_wpb_psc__tab_priority', true );
                        $products = get_post_meta( $size_id, '_wpb_psc_products', true );
                        $product_categories = get_post_meta( $size_id, '_wpb_psc__product_categories', true );


                        if($product_size_chart){
                            $size_id    = $product_size_chart;
                            $text       = get_the_title( $size_id );
                        }

                        $psc_tabs['wpb_psc_tab_' . $size_id] = array(
                            'title'     => esc_html($text),
                            'priority'  => $tab_priority,
                            'callback'  => 'wpb_psc_product_tab_content',
                            'size_id'   => $size_id,
                        );

                        if ( isset($product_size_chart) && $product_size_chart != '' && $key != 1 ) {
                            $psc_tabs = [];
                        }

                        if( $wpb_psc_disable == 'yes' ){
                            $psc_tabs = [];
                        }

                        if( $set_for_all_products == '' ){

                            if( isset($products) && !empty($products) ){
                                $products = explode(',', $products);
                                if( !is_single( $products ) ){
                                    $psc_tabs = [];
                                }
                            }

                            if( isset($product_categories) && !empty($product_categories) ){
                                $product_categories = explode(',', $product_categories);

                                if( !has_term( $product_categories, 'product_cat') ){
                                    $psc_tabs = [];
                                }
                            }
                        }

                        if( isset($psc_tabs) && !empty($psc_tabs) ){
                            $tabs = array_merge($tabs, $psc_tabs);
                        }
                    }

                    return $tabs;
                });
            }
        

        }

        add_action( 'woocommerce_product_data_panels', array( $this, 'woo_add_meta_fields' ) );
        add_action( 'woocommerce_process_product_meta', array( $this, 'woo_save_product_meta' ), 10, 2 );

        add_filter( 'woocommerce_product_data_tabs', function($tabs){

            $tabs['wpb_psc_size_chart'] = array(
                'label'    => esc_html__( 'Size Chart', 'product-size-chart-for-woocommerce' ),
                'target'   => 'wpb_psc_size_chart_data',
                'class'    => array(),
                'priority' => 40,
            );

            return $tabs;
        });
    }



    /**
     * Add meta box to the WooCommerce product
     */
    public function woo_add_meta_fields() {
        ?>  
            <div id="wpb_psc_size_chart_data" class="panel woocommerce_options_panel hidden">
                <div class="options_group">
                    <?php
                        woocommerce_wp_checkbox(
                            array(
                                'id'            => '_wpb_psc_disable',
                                'wrapper_class' => 'show_if_simple show_if_variable wpb_psc_disable',
                                'label'         => esc_html__( 'Disable Size Chart?', 'product-size-chart-for-woocommerce' ),
                                'description'   => esc_html__( 'Disable size chart for this product', 'product-size-chart-for-woocommerce' ),
                            )
                        );
                    ?>
                </div>
            </div>
        <?php
    }

    /**
     * Save meta box to the WooCommerce product
     */
    public function woo_save_product_meta( $post_id, $post ) {
        $wpb_psc_disable = isset( $_POST['_wpb_psc_disable'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_wpb_psc_disable', $wpb_psc_disable );
    }
}