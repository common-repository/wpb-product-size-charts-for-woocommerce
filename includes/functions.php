<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/**
 * Get settings option
 */


if( !function_exists('wpb_psc_get_option') ){
    function wpb_psc_get_option( $option, $section, $default = '' ) {
 
        $options = get_option( $section );
     
        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }
     
        return $default;
    }
}


/**
 * Include a template by precedance
 *
 * Looks at the theme directory first
 *
 * @param  string  $template_name
 * @param  array   $args
 *
 * @return void
 */

if( !function_exists('wpb_psc_pro_get_template') ){
    function wpb_psc_pro_get_template( $template_name, $args = array() ) {
        $size_chart = WPB_Product_Size_Charts::init();

        if ( $args && is_array($args) ) {
            extract( $args );
        }

        $template = locate_template( array(
            $size_chart->theme_dir_path . $template_name,
            $template_name
        ) );

        if ( ! $template ) {
            $template = $size_chart->template_path() . $template_name;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }
}


/**
 * Get size table
 *
 * @param  int  $size_id
 *
 * @return string
 */


if( !function_exists('wpb_psc_get_size_chart_table') ){
    function wpb_psc_get_size_chart_table( $size_id ) {

    	if( isset($size_id) && $size_id != '' )
 
	    $table = json_decode( get_post_meta( $size_id, '_wpb_psc_size_table', true ) );

        

		if( isset($table->thead) && isset($table->tbody) )

		$table = (array) $table;

		if( isset($table['thead']) && isset($table['tbody']) )

        if( isset( $table['tbody'] ) && !empty($table['tbody']) ){
            ob_start();
            wpb_psc_pro_get_template('table-frontend.php', array( 'table' => $table ));
            return ob_get_clean();
        }else{
            return null;
        }
    }
}


if( !function_exists('wpb_psc_size_chart_table') ){
    function wpb_psc_size_chart_table( $size_id ) {

        if( isset($size_id) && $size_id != '' ){
            echo wpb_psc_get_size_chart_table($size_id);
        }
    }
}



/**
 * Size Content
 */

if( !function_exists('wpb_psc_size_content') ){
    function wpb_psc_size_content( $size_id ) {

        if( isset($size_id) && $size_id != '' ){
            wpb_psc_pro_get_template('size-chart-content.php', array( 'size_id' => $size_id ));
        }
    }
}

if( !function_exists('wpb_psc_get_size_content') ){
    function wpb_psc_get_size_content( $size_id ) {

        if( isset($size_id) && $size_id != '' ){
            ob_start();
            wpb_psc_size_content($size_id);
            return ob_get_clean();
        }
    }
}

/**
 * Woo Tab content 
 */
if( !function_exists('wpb_psc_product_tab_content') ){
    function wpb_psc_product_tab_content( $name, $tab_attr ){
        wpb_psc_size_content($tab_attr['size_id']);
    }
}

/**
 * Pro features
 */

add_action( 'wpb_psc_lite_after_settings_page', 'wpb_psc_lite_after_settings_page_add_pro_features' );

function wpb_psc_lite_after_settings_page_add_pro_features(){
    ?>
    <div class="wpb-psc-pro-features wrap">
        <h3>PRO FEATURES</h3>
        <p>The premium version of this plugin comes with a few amazing features. Lifetime purchase, no yearly renewal required. Lifetime free update and support.</p>
        <ul>
            <li>Create unlimited numbers of rows and columns for the size chart.</li>
            <li>Choose size chart direct from the product page.</li>
            <li>Multiple table style presets.</li>
            <li>Advanced button style customization options.</li>
            <li>Advanced popup style customization options.</li>
            <li>Elementor widget for size chart.</li>
            <li>Lifetime free update and support.</li>
            <li>No yearly renewal required.</li>
        </ul>
        <a class="button button-pro" href="https://wpbean.com/?p=32752" target="_blank">Get Pro Version</a>
    </div>
    <?php
}


/**
 * Pro Features on metabox
 */

function wpb_psc_lite_register_pro_features_meta_boxes() {
    add_meta_box( 'wpb-psc-lite-pro-features', esc_html__( 'PRO FEATURES', 'product-size-chart-for-woocommerce' ), 'wpb_psc_lite_after_settings_page_add_pro_features', 'wpb_psc_size_chart' );
}
add_action( 'add_meta_boxes', 'wpb_psc_lite_register_pro_features_meta_boxes' );
 



/**
 * Size chart admin head
 */

add_action( 'admin_notices', 'wpb_psc_lite_after_settings_page_add_pro_link' );
add_action( 'admin_init', 'wpb_psc_pro_discount_admin_notice_dismissed' );

function wpb_psc_lite_after_settings_page_add_pro_link(){

    $user_id = get_current_user_id();

    if ( !get_user_meta( $user_id, 'wpb_psc_pro_discount_dismissed' ) ){
        printf('<div class="wpb-psc-pro-features wpb-psc-pro-features-notice updated"><h3>%s</h3><p style="font-size: 18px;line-height: 32px">%s <a target="_blank" href="%s">%s</a>! %s <b>%s</b></p><a class="button button-pro" href="https://wpbean.com/?p=32752" target="_blank">Get Pro Version</a><a class="notice-dismiss" href="%s"></a></div>', esc_html__( 'PRO Discount', 'product-size-chart-for-woocommerce' ), esc_html__( 'Get a 10% exclusive discount on the premium version of the', 'product-size-chart-for-woocommerce' ), 'https://wpbean.com/?p=32752', esc_html__( 'WPB Product Size Charts for WooCommerce', 'product-size-chart-for-woocommerce' ), esc_html__( 'Use discount code - ', 'product-size-chart-for-woocommerce' ), '10PERCENTOFF', esc_url( add_query_arg( 'wpb-psc-pro-discount-admin-notice-dismissed', 'true' ) ));
    }
}

function wpb_psc_pro_discount_admin_notice_dismissed() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['wpb-psc-pro-discount-admin-notice-dismissed'] ) ){
      add_user_meta( $user_id, 'wpb_psc_pro_discount_dismissed', 'true', true );
    }
}


/**
 * Post Type Columns
 */


add_filter( 'manage_wpb_psc_size_chart_posts_columns', 'wpb_psc_set_edit_size_chart_columns' );
add_action( 'manage_wpb_psc_size_chart_posts_custom_column' , 'wpb_psc_set_edit_size_chart_column_content', 10, 2 );

function wpb_psc_set_edit_size_chart_columns($columns) {
    unset( $columns['date'] );
    $columns['size_id']     = esc_html__( 'Size ID', 'product-size-chart-for-woocommerce' );
    $columns['btn_text']    = esc_html__( 'Button Text', 'product-size-chart-for-woocommerce' );
    $columns['show_on']     = esc_html__( 'Show On', 'product-size-chart-for-woocommerce' );
    $columns['shortcode']   = esc_html__( 'ShortCode', 'product-size-chart-for-woocommerce' );
    $columns['date']        = esc_html__( 'Date', 'product-size-chart-for-woocommerce' );

    return $columns;
}


function wpb_psc_set_edit_size_chart_column_content( $column, $post_id ) {
    switch ( $column ) {

        case 'size_id' :
            echo esc_html( $post_id ); 
            break;

        case 'btn_text' :
            echo esc_html( get_post_meta( $post_id, '_wpb_psc__btn_text', true ) ); 
            break;    

        case 'show_on' :
            $show_on = get_post_meta( $post_id, '_wpb_psc_set_for_all_products', true );

            if($show_on == 'on'){
                echo esc_html__( 'All Products', 'product-size-chart-for-woocommerce' );
            }else{
                echo esc_html__( 'Selected Products', 'product-size-chart-for-woocommerce' );
            }
            break;   

        case 'shortcode' :
            ?>
            <span class="shortcode">
                <input type="text" onfocus="this.select();" readonly="readonly" value="[wpb-product-size-chart size_id=&quot;<?php echo esc_attr( $post_id ); ?>&quot; post_id=&quot;<?php echo esc_html__( 'Product ID Here', 'product-size-chart-for-woocommerce' ); ?>&quot;]" class="large-text code">
            </span>
            <?php
            break;     

    }
}