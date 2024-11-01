<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/**
 * Ajax Class
 */
class WPB_PSC_Ajax {

    /**
     * Bind actions
     */
    function __construct() {

        add_action( 'wp_ajax_fire_wpb_product_size_chart', array( $this, 'fire_wpb_product_size_chart' ) );
        add_action( 'wp_ajax_nopriv_fire_wpb_product_size_chart', array( $this, 'fire_wpb_product_size_chart' ) );
    }

    /**
     * Form Content
     */

    public function fire_wpb_product_size_chart() {
        $output     = '';
        $size_id    = isset( $_POST['size_id'] ) ? sanitize_text_field( $_POST['size_id'] ) : '';
        $error      = sprintf( '<div class="wpb-psc-alert wpb-psc-alert-inline wpb-psc-alert-error">%s</div>', esc_html__( 'No content to show!', 'product-size-chart-for-woocommerce' ) );

        if( $size_id ){
            $output .= wpb_psc_get_size_content($size_id);
        }

        if( $output && $output != '' ){
            wp_send_json_success($output);
        }else{
            wp_send_json_success($error);
        }
    }
}
