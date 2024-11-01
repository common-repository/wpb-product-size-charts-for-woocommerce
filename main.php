<?php
/**
 * Plugin Name:       WPB Product Size Charts for WooCommerce
 * Plugin URI:        https://wpbean.com/plugins/
 * Description:       Show your product size chart table in a nice popup.
 * Version:           1.07
 * Author:            wpbean
 * Author URI:        https://wpbean.com
 * Text Domain:       product-size-chart-for-woocommerce
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


if ( ! function_exists( 'is_plugin_active' ) ) {
  require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/**
 * Define constants
 */

if ( ! defined( 'WPB_PSC_FREE_INIT' ) ) {
  define( 'WPB_PSC_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( !defined( 'WPB_PSC_LITE_VERSION' ) ) {
    define( 'WPB_PSC_LITE_VERSION', '1.07' );
}

/**
 * This version can't be activate if premium version is active
 */

if ( defined( 'WPB_PSC_PREMIUM' ) ) {
    function wpb_psc_install_free_admin_notice() {
        ?>
	        <div class="error">
	            <p><?php esc_html_e( 'You can\'t activate the free version of WPB Product Size Charts for WooCommerce while you are using the premium one.', 'product-size-chart-for-woocommerce' ); ?></p>
	        </div>
    	<?php
    }

    add_action( 'admin_notices', 'wpb_psc_install_free_admin_notice' );
    deactivate_plugins( plugin_basename( __FILE__ ) );
    return;
}


/* -------------------------------------------------------------------------- */
/*                                Plugin Class                                */
/* -------------------------------------------------------------------------- */

class WPB_Product_Size_Charts {

	//  Plugin version
	public $version = WPB_PSC_LITE_VERSION;

	// The plugin url
	public $plugin_url;
	
	/**
     * The plugin path
     *
     * @var string
     */
    public $plugin_path;


    /**
     * The theme directory path
     *
     * @var string
     */
    public $theme_dir_path;


	// Initializes the WPB_Product_Size_Charts() class
	public static function init(){
		static $instance = false;

		if( !$instance ){
			$instance = new WPB_Product_Size_Charts();

			add_action('after_setup_theme', array($instance, 'plugin_init'));
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $instance, 'plugin_action_links' ) );
            register_activation_hook( __FILE__, array($instance, 'activate' ) );
            register_deactivation_hook( plugin_basename( __FILE__ ), array($instance, 'deactivation' ) );
		}

		return $instance;
	}

	//Initialize the plugin
	function plugin_init(){

		if ( class_exists( 'WooCommerce' ) ) {
			$this->theme_dir_path = apply_filters( 'wpb_psc_pro_dir_path', 'product-size-chart-for-woocommerce/' );
			$this->file_includes();
			$this->init_classes();

			add_action( 'init', array( $this, 'localization_setup' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

	}




	// The plugin activation function
	public function activate(){
		update_option( 'wpb_psc_installed', time() );
		update_option( 'wpb_psc_lite_version', $this->version );
	}

	// The plugin deactivation function
	public function deactivation(){
		$user_id = get_current_user_id();
		if ( get_user_meta( $user_id, 'wpb_psc_pro_discount_dismissed' ) ){
			delete_user_meta( $user_id, 'wpb_psc_pro_discount_dismissed' );
		}

		flush_rewrite_rules();
	}


	function plugin_action_links( $links ) {

		if ( class_exists( 'WooCommerce' ) ) {
			$links[] = '<a href="'. admin_url( 'edit.php?post_type=wpb_psc_size_chart&page=product-size-chart-for-woocommerce' ) .'">'. esc_html__('Settings', 'product-size-chart-for-woocommerce') .'</a>';
		}

		$links[] = '<a style="color: #93003c;text-shadow: 1px 1px 1px #eee;font-weight: bold" href="https://wpbean.com/?p=32752" target="_blank">'. esc_html__('Go Pro', 'product-size-chart-for-woocommerce') .'</a>';

		return $links;
	}


	// Load the required files
	function file_includes() {
		include_once dirname( __FILE__ ) . '/includes/functions.php';
		include_once dirname( __FILE__ ) . '/includes/admin/class.post-type.php';
		include_once dirname( __FILE__ ) . '/includes/admin/meta-box-sanitize.php';
		include_once dirname( __FILE__ ) . '/includes/admin/class.mdc-meta-box.php';
		include_once dirname( __FILE__ ) . '/includes/admin/class.meta.config.php';

		if ( is_admin() ) {
			include_once dirname( __FILE__ ) . '/includes/admin/class.settings-api.php';
			include_once dirname( __FILE__ ) . '/includes/admin/class.settings-config.php';
		} else {
			include_once dirname( __FILE__ ) . '/includes/class-shortcode.php';
		}

		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			include_once dirname( __FILE__ ) . '/includes/class-woocommerce.php';
		}
		
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            include_once dirname( __FILE__ ) . '/includes/class-ajax.php';
        }
	}

	// Initialize the classes
    public function init_classes() {
    	
    	new WPB_PSC_Post_Type_Handler();
    	new WPB_PSC_Meta_Box_Handler();

		if ( is_admin() ) {
            new WPB_PSC_Plugin_Settings();
        }else{
			new WPB_PSC_Shortcode_Handler();
		}

		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			new WPB_PSC_WooCommerce_Handler();
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            new WPB_PSC_Ajax();
        }
	}

	// Initialize plugin for localization
    public function localization_setup() {
        load_plugin_textdomain( 'product-size-chart-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	// Add admin scripts and styles
    public function admin_enqueue_scripts() {
    	wp_enqueue_style( 'wpb-psc-admin', plugins_url( 'includes/admin/assets/css/admin.css', __FILE__ ), array(), '1.0' );
    }
	
	// Add frontend scripts and styles
    public function enqueue_scripts() {
    	$show_chart_as 		= wpb_psc_get_option( 'wpb_psc_chart_as', 'wpb_psc_general_settings', 'button' );

		if($show_chart_as == 'button'){
			wp_enqueue_style( 'wpb-psc-sweetalert2', plugins_url( 'assets/css/sweetalert2.min.css', __FILE__ ), array(), $this->version );
			wp_enqueue_script( 'wpb-psc-sweetalert2', plugins_url( 'assets/js/sweetalert2.all.min.js', __FILE__ ), array( 'jquery' ), $this->version, true );

			wp_enqueue_script( 'wpb-psc-scripts', plugins_url( 'assets/js/frontend.js', __FILE__ ), array( 'jquery', 'wp-util' ), $this->version, true );
			wp_localize_script( 'wpb-psc-scripts', 'WPB_PSC_Vars', array(
	            'ajaxurl' 		=> admin_url( 'admin-ajax.php' ),
	            'nonce'   		=> wp_create_nonce( 'wpb-psc-ajax' ),
			) );
		}

		wp_enqueue_style( 'wpb-psc-styles', plugins_url( 'assets/css/frontend.css', __FILE__ ), array(), $this->version );
		

		$btn_color       		= wpb_psc_get_option( 'wpb_psc_btn_color', 'wpb_psc_btn_style', '#ffffff' );
		$bg_color       		= wpb_psc_get_option( 'wpb_psc_btn_bg_color', 'wpb_psc_btn_style', '#17a2b8' );
		$btn_hover_color       	= wpb_psc_get_option( 'wpb_psc_btn_hover_color', 'wpb_psc_btn_style', '#ffffff' );
		$btn_bg_hover_color     = wpb_psc_get_option( 'wpb_psc_btn_bg_hover_color', 'wpb_psc_btn_style', '#138496' );
		$plaintext_color     	= wpb_psc_get_option( 'wpb_psc_plaintext_color', 'wpb_psc_btn_style', '#212529' );
		$plaintext_hover_color  = wpb_psc_get_option( 'wpb_psc_plaintext_hover_color', 'wpb_psc_btn_style', '#5e5e5e' );
		$plaintext_font_size    = wpb_psc_get_option( 'wpb_psc_plaintext_font_size', 'wpb_psc_btn_style' );
		$btn_margin_top 		= wpb_psc_get_option( 'wpb_psc_btn_margin_top', 'wpb_psc_btn_style', 15 );
    	$btn_margin_bottom 		= wpb_psc_get_option( 'wpb_psc_btn_margin_bottom', 'wpb_psc_btn_style', 15 );


		$custom_css = "
		.wpb-psc-btn-type-button.wpb-psc-btn.wpb-psc-btn-default,
		.wpb-psc-table-style-true input[type=submit],
		.wpb-psc-table-style-true input[type=button],
		.wpb-psc-table-style-true input[type=submit],
		.wpb-psc-table-style-true input[type=button]{
			color: {$btn_color};
			background: {$bg_color};
		}
		.wpb-psc-btn-type-plain_text.wpb-psc-btn.wpb-psc-btn-default {
			color: {$plaintext_color};
		}
		.wpb-psc-btn-type-plain_text.wpb-psc-btn.wpb-psc-btn-default:hover, .wpb-psc-btn-type-plain_text.wpb-psc-btn.wpb-psc-btn-default:focus {
			color: {$plaintext_hover_color};
		}
		.wpb-psc-btn-type-button.wpb-psc-btn.wpb-psc-btn-default:hover, .wpb-psc-btn-type-button.wpb-psc-btn.wpb-psc-btn-default:focus,
		.wpb-psc-table-style-true input[type=submit]:hover, .wpb-psc-table-style-true input[type=submit]:focus,
		.wpb-psc-table-style-true input[type=button]:hover, .wpb-psc-table-style-true input[type=button]:focus,
		.wpb-psc-table-style-true input[type=submit]:hover,
		.wpb-psc-table-style-true input[type=button]:hover,
		.wpb-psc-table-style-true input[type=submit]:focus,
		.wpb-psc-table-style-true input[type=button]:focus {
			color: {$btn_hover_color};
			background: {$btn_bg_hover_color};
		}
		body .wpb-psc-btn, body .summary .wpb-psc-btn {
			margin-top: {$btn_margin_top}px;
			margin-bottom: {$btn_margin_bottom}px;
		}
		.wpb-psc-btn-type-plain_text.wpb-psc-btn-loading:before {
    		border: 2px solid {$plaintext_color};
    		border-top-color: #fff;
		}
		";

		if( $plaintext_font_size ){
			$custom_css .= ".wpb-psc-btn-type-plain_text.wpb-psc-btn.wpb-psc-btn-default { font-size: {$plaintext_font_size}px }";
		}
				
		wp_add_inline_style( 'wpb-psc-styles', $custom_css );
	}

	// plugin admin notices
    public function admin_notices() {

		if ( !class_exists( 'WooCommerce' ) ) {
			?>
			<div class="notice notice-error is-dismissible">
				<p><b><?php esc_html_e( 'WPB Product Size Charts for WooCommerce Pro', 'product-size-chart-for-woocommerce' ); ?></b><?php esc_html_e( ' required ', 'product-size-chart-for-woocommerce' ); ?><b><a href="https://wordpress.org/plugins/woocommerce/" target="_blank"><?php esc_html_e( 'WooCommerce', 'product-size-chart-for-woocommerce' ); ?></a></b><?php esc_html_e( ' plugin to work with.', 'product-size-chart-for-woocommerce' ); ?></p>
			</div>
			<?php
		}
	}


	/**
     * Get the plugin path.
     *
     * @return string
     */
    public function plugin_path() {
        if ( $this->plugin_path ) return $this->plugin_path;

        return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    public function template_path() {
        return $this->plugin_path() . '/templates/';
    }
}


/* -------------------------------------------------------------------------- */
/*                            Initialize the plugin                           */
/* -------------------------------------------------------------------------- */

function wpb_get_product_size_chart() {
    return WPB_Product_Size_Charts::init();
}


/**
 * Plugin Init
 */

function wpb_get_product_size_chart_lite_init(){
    wpb_get_product_size_chart();
}
add_action( 'plugins_loaded', 'wpb_get_product_size_chart_lite_init' );