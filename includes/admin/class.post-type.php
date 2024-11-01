<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


/**
 * Size Chart Post Type
 */


if ( !class_exists('WPB_PSC_Post_Type_Handler' ) ):
class WPB_PSC_Post_Type_Handler {

    private $textdomain = 'product-size-chart-for-woocommerce';

    public function __construct() {
        add_action( 'init', array( $this, 'wpb_psc_post_type' ), 0 );

        if ( version_compare($GLOBALS['wp_version'], '5.0-beta', '>') ) {
            // WP > 5 beta
            add_filter( 'use_block_editor_for_post_type', array( $this, 'wpb_psc_disable_gutenberg_for_post_type' ), 10, 2 );
        } else {
            // WP < 5 beta
            add_filter( 'gutenberg_can_edit_post_type', array( $this, 'wpb_psc_disable_gutenberg_for_post_type' ), 10, 2 );
        }
    }


    /**
     * Add Size Post Type
     */

    public function wpb_psc_post_type() {

        $labels = array(
            'name'                  => esc_html_x( 'Size Charts', 'Post Type General Name', $this->textdomain ),
            'singular_name'         => esc_html_x( 'Size Chart', 'Post Type Singular Name', $this->textdomain ),
            'menu_name'             => esc_html__( 'Size Charts', $this->textdomain ),
            'name_admin_bar'        => esc_html__( 'Size Chart', $this->textdomain ),
            'archives'              => esc_html__( 'Size Archives', $this->textdomain ),
            'attributes'            => esc_html__( 'Size Attributes', $this->textdomain ),
            'parent_item_colon'     => esc_html__( 'Parent Size:', $this->textdomain ),
            'all_items'             => esc_html__( 'All Size Charts', $this->textdomain ),
            'add_new_item'          => esc_html__( 'Add New Size Chart', $this->textdomain ),
            'add_new'               => esc_html__( 'Add New', $this->textdomain ),
            'new_item'              => esc_html__( 'New Size Chart', $this->textdomain ),
            'edit_item'             => esc_html__( 'Edit Size Chart', $this->textdomain ),
            'update_item'           => esc_html__( 'Update Size Chart', $this->textdomain ),
            'view_item'             => esc_html__( 'View Size Chart', $this->textdomain ),
            'view_items'            => esc_html__( 'View Size Charts', $this->textdomain ),
            'search_items'          => esc_html__( 'Search Size Chart', $this->textdomain ),
            'not_found'             => esc_html__( 'Not found', $this->textdomain ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', $this->textdomain ),
            'featured_image'        => esc_html__( 'Featured Image', $this->textdomain ),
            'set_featured_image'    => esc_html__( 'Set featured image', $this->textdomain ),
            'remove_featured_image' => esc_html__( 'Remove featured image', $this->textdomain ),
            'use_featured_image'    => esc_html__( 'Use as featured image', $this->textdomain ),
            'insert_into_item'      => esc_html__( 'Insert into size chart', $this->textdomain ),
            'uploaded_to_this_item' => esc_html__( 'Uploaded to this size chart', $this->textdomain ),
            'items_list'            => esc_html__( 'Size chart list', $this->textdomain ),
            'items_list_navigation' => esc_html__( 'Size charts list navigation', $this->textdomain ),
            'filter_items_list'     => esc_html__( 'Filter size chart list', $this->textdomain ),
        );

        if( function_exists('dokan') ){
            $role = 'seller';
        }else{
            $role = 'manage_woocommerce';
        }

        $capabilities = apply_filters( 'wpb_psc_size_post_type_capabilities', array(
            'edit_post'             => $role,
            'read_post'             => $role,
            'delete_post'           => $role,
            'edit_posts'            => $role,
            'edit_others_posts'     => $role,
            'publish_posts'         => $role,
            'read_private_posts'    => $role,
        ));

        $args = array(
            'label'                 => esc_html__( 'Size Chart', $this->textdomain ),
            'description'           => esc_html__( 'Product Size Charts for WooCommerce', $this->textdomain ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor' ),
            'hierarchical'          => false,
            'public'                => false,
            'show_in_rest'          => true,
            'rest_base'             => '',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 80,
            'menu_icon'             => plugins_url( 'assets/images/icon.png', __FILE__ ),
            'show_in_admin_bar'     => false,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'capability_type'       => 'page',
            'capabilities'          => $capabilities,
        );
        register_post_type( 'wpb_psc_size_chart', $args );
    }

    /**
     * Disable gutenberg
     */

    function wpb_psc_disable_gutenberg_for_post_type( $is_enabled, $post_type ) {
        if ( 'wpb_psc_size_chart' == $post_type ) {
            return false;
        }

        return $is_enabled;
    }
    
}
endif;