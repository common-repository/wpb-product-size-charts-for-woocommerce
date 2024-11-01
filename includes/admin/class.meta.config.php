<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/**
 * Size Meta
 */


if ( !class_exists('WPB_PSC_Meta_Box_Handler' ) ):
class WPB_PSC_Meta_Box_Handler {

    private $textdomain = 'product-size-chart-for-woocommerce';
    private $prefix     = '_wpb_psc_';

    public function __construct() {
        add_action( 'admin_init', array( $this, 'wpb_psc_size_table_meta' ), 0 );
        add_action( 'admin_init', array( $this, 'wpb_psc_size_meta_boxes' ), 0 );
        add_action( 'rest_api_init', array( $this, 'add_wpb_psc_size_chart_meta_fields_to_rest_api' ), 0 );
    }


    /**
     * Add Size Table Meta 
     */

    public function wpb_psc_size_table_meta() {
        $args = array(
            'meta_box_id'   =>  $this->prefix . 'size_chart_table',
            'label'         =>  esc_html__( 'Size Chart Table', $this->textdomain ),
            'post_type'     =>  array( 'wpb_psc_size_chart' ),
            'context'       =>  'normal',
            'priority'      =>  'high',
            'hook_priority'  =>  10,
            'fields'        =>  array(
                array(
                    'name'      =>  $this->prefix . 'size_table',
                    'label'     =>  esc_html__( 'Size Chart Table', $this->textdomain ),
                    'type'      =>  'table',
                ),
            )
        );

        wpb_psc_meta_box( $args );
    }



    /**
     * Add Size Meta 
     */

    public function wpb_psc_size_meta_boxes() {
        $args = array(
            'meta_box_id'   =>  $this->prefix . 'size_chart_setup',
            'label'         =>  esc_html__( 'Size Chart Setup', $this->textdomain ),
            'post_type'     =>  array( 'wpb_psc_size_chart' ),
            'context'       =>  'normal',
            'priority'      =>  'high',
            'hook_priority'  =>  10,
            'fields'        =>  array(
                array(
                    'name'      =>  $this->prefix . 'set_for_all_products',
                    'label'     =>  esc_html__( 'Apply on All Products', $this->textdomain ),
                    'type'      =>  'checkbox',
                    'desc'      =>  esc_html__( 'Check this if you want to set this size chart to all the products.', $this->textdomain ),
                    'disabled'  =>  false, // true|false
                ),
                array(
                    'name'      =>  $this->prefix . 'products',
                    'label'     =>  esc_html__( 'Choose Products', $this->textdomain ),
                    'type'      =>  'posts',
                    'class'     =>  'wpb-psc-meta-field-select2',
                    'post_type' =>  'products',
                ),
                array(
                    'name'      =>  $this->prefix . '_product_categories',
                    'label'     =>  esc_html__( 'Select Product Categories', $this->textdomain ),
                    'type'      =>  'categories',
                    'class'     =>  'wpb-psc-meta-field-select2',
                    'taxonomy'  =>  'product_cat',
                ),
                array(
                    'name'      =>  $this->prefix . '_btn_text',
                    'label'     =>  esc_html__( 'Tab/Button Title', $this->textdomain ),
                    'type'      =>  'text',
                    'default'   =>  esc_html__( 'Size Chart', $this->textdomain ),
                ),
                array(
                    'name'      =>  $this->prefix . '_tab_priority',
                    'label'     =>  esc_html__( 'Tab Priority', $this->textdomain ),
                    'type'      =>  'number',
                    'default'   =>  50,
                ),
            )
        );

        wpb_psc_meta_box( $args );
    }

    /**
     * Add post meta to the rest api
     */

    function add_wpb_psc_size_chart_meta_fields_to_rest_api(){

        register_rest_field( 'wpb_psc_size_chart', '_wpb_psc_size_table', array(
            'get_callback' => function( $post_arr ) {
                return json_decode( get_post_meta( $post_arr['id'], '_wpb_psc_size_table', true ) );
            },
        ) );

    }
    
}
endif;