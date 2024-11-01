<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/**
 * Shortcode
 */

class WPB_PSC_Shortcode_Handler {

    public function __construct() {
        add_shortcode( 'wpb-product-size-chart', array( $this, 'get_the_shortcode' ) );
        add_shortcode( 'wpb-product-size-chart-hook', array( $this, 'get_the_hook_shortcode' ) );
    }

    /**
     * Shortcode handler
     *
     * @param  array  $atts
     * @param  string  $content
     *
     * @return string
     */
    public function get_the_shortcode( $atts, $content = '' ) {

        ob_start();
        self::the_shortcode( $atts );
        $content .= ob_get_clean();

        return $content;
    }

    /**
     * Hook ShortCode for Elementor Pro
     */

    public function get_the_hook_shortcode( $atts, $content = '' ) {
        if( is_product() ){
            ob_start();

            do_action( 'wpb_psc_woocommerce_single_product_summary' );

            return ob_get_clean();
        }
    }

    /**
     * Generic function for displaying docs
     *
     * @param  array   $args
     *
     * @return void
     */
    public static function the_shortcode( $args = array() ) {
        $defaults = array(
            'size_id'       => '',
            'post_id'       => get_the_ID(),
            'class'         => '',
            'text'          => esc_html__( 'Size Chart', 'product-size-chart-for-woocommerce' ),
            'btn_type'      => wpb_psc_get_option( 'wpb_psc_btn_type', 'wpb_psc_btn_style', 'plain_text' ),
            'btn_size'      => wpb_psc_get_option( 'wpb_psc_btn_size', 'wpb_psc_btn_style', 'large' ),
            'popup_style'   => ( wpb_psc_get_option( 'wpb_psc_popup_style', 'wpb_psc_popup_style', 'on' ) == 'on' ? true : false ),
            'width'         => wpb_psc_get_option( 'wpb_psc_popup_width', 'wpb_psc_popup_style', 960 ) . wpb_psc_get_option( 'wpb_psc_popup_width_unit', 'wpb_psc_popup_style', 'px' ),
        );

        $args = wp_parse_args( $args, $defaults );

        if( $args['size_id'] ){
            echo apply_filters('wpb_psc_button_html', sprintf( '<button data-id="%s" data-post_id="%s" data-popup_style="%s" data-width="%s" class="wpb-psc-table-fire wpb-psc-btn-type-%s wpb-psc-btn-%s wpb-psc-btn wpb-psc-btn-default%s">%s</button>', esc_attr($args['size_id']), esc_attr($args['post_id']), esc_attr($args['popup_style']), esc_attr($args['width']), esc_attr($args['btn_type']), esc_attr($args['btn_size']) ,( $args['class'] ? esc_attr( ' ' . $args['class']) : '' ), esc_html( $args['text'] ) ), $args);
        }else{
            printf( '<div class="wpb-psc-alert wpb-psc-alert-inline wpb-psc-alert-error">%s</div>', esc_html__( 'Size Chart ID required.', 'product-size-chart-for-woocommerce' ) );
        }
        
    }
}
