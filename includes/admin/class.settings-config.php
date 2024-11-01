<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

if ( !class_exists('WPB_PSC_Plugin_Settings' ) ):
class WPB_PSC_Plugin_Settings {

    private $settings_api;
    private $settings_name = 'product-size-chart-for-woocommerce';
    private $textdomain = 'product-size-chart-for-woocommerce';

    function __construct() {
        $this->settings_api = new WPB_PSC_WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_enqueue_scripts() {
        $screen = get_current_screen();

        if( $screen->id == 'wpb_psc_size_chart_page_' . $this->settings_name ){
            $this->settings_api->admin_enqueue_scripts();
        }
    }

    function admin_menu() {

        add_submenu_page(
            'edit.php?post_type=wpb_psc_size_chart', 
            esc_html__( 'WPB Size Charts Settings', $this->textdomain ),
            esc_html__( 'Settings', $this->textdomain ),
            'delete_posts',
            $this->settings_name,
            array($this, 'plugin_page')
        );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'wpb_psc_general_settings',
                'title' => esc_html__( 'General Settings', $this->textdomain )
            ),
            array(
                'id'    => 'wpb_psc_table_style',
                'title' => esc_html__( 'Table Style (Pro Only)', $this->textdomain )
            ),
            array(
                'id'    => 'wpb_psc_btn_style',
                'title' => esc_html__( 'Button Style (Pro Only)', $this->textdomain )
            ),
            array(
                'id'    => 'wpb_psc_popup_style',
                'title' => esc_html__( 'Popup Style (Pro Only)', $this->textdomain )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'wpb_psc_general_settings' => array(
                array(
                    'name'    => 'wpb_psc_chart_as',
                    'label'   => esc_html__( 'Show Chart as', $this->textdomain ),
                    'desc'    => esc_html__( 'Choose how you want to show the size chart. Default: Button, the size chart will be shown in a popup.', $this->textdomain ),
                    'type'    => 'select',
                    'size'    => 'wpb-select-buttons',
                    'default' => 'button',
                    'options' => array(
                        'button'  => esc_html__( 'Button', $this->textdomain ),
                        'tab'     => esc_html__( 'Tab', $this->textdomain ),
                    )
                ),
                array(
                    'name'    => 'wpb_psc_button_place',
                    'label'   => esc_html__( 'Button Place', $this->textdomain ),
                    'desc'    => esc_html__( 'Choose a position where you want to show the button.', $this->textdomain ),
                    'type'    => 'select',
                    'default' => 40,
                    'options' => array(
                        4   => esc_html__( 'Before product title', $this->textdomain ),
                        5   => esc_html__( 'After product title', $this->textdomain ),
                        10  => esc_html__( 'After product price', $this->textdomain ),
                        20  => esc_html__( 'After product excerpt', $this->textdomain ),
                        30  => esc_html__( 'After product cart button', $this->textdomain ),
                        40  => esc_html__( 'After product meta', $this->textdomain ),
                        50  => esc_html__( 'After product sharing', $this->textdomain ),
                        'wpb_psc_hook'  => esc_html__( 'Use Hook ShortCode', $this->textdomain ),
                    )
                ),
                array(
                    'name'    => 'wpb_psc_content_place',
                    'label'   => esc_html__( 'Content Place', $this->textdomain ),
                    'desc'    => esc_html__( 'Choose where you want to show the size content. Default: Before the site chart table.', $this->textdomain ),
                    'type'    => 'select',
                    'size'    => 'wpb-select-buttons',
                    'default' => 'before',
                    'options' => array(
                        'before'  => esc_html__( 'Before Chart Table', $this->textdomain ),
                        'after'   => esc_html__( 'After Chart Table', $this->textdomain ),
                    )
                ),
                array(
                    'name'    => 'wpb_psc_content_elements',
                    'label'   => esc_html__( 'Size Chart Content', $this->textdomain ),
                    'desc'    => esc_html__( 'Choose the content elements.', $this->textdomain ),
                    'type'    => 'multicheck',
                    'default' => array('content' => 'content', 'table' => 'table'),
                    'options' => array(
                        'title'     => esc_html__( 'Title', $this->textdomain ),
                        'content'   => esc_html__( 'Content', $this->textdomain ),
                        'table'     => esc_html__( 'Chart Table', $this->textdomain ),
                    )
                ),
            ),
            'wpb_psc_table_style' => array(
                array(
                    'name'    => 'wpb_psc_table_type',
                    'label'   => esc_html__( 'Table Type', $this->textdomain ),
                    'desc'    => esc_html__( 'Choose a table type.', $this->textdomain ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => array(
                        'default'  => esc_html__( 'Default', $this->textdomain ),
                        'wpb-psc-table-dark'  => esc_html__( 'Dark', $this->textdomain ),
                        'wpb-psc-table-striped'  => esc_html__( 'Striped', $this->textdomain ),
                        'wpb-psc-table-striped wpb-psc-table-dark'  => esc_html__( 'Striped Dark', $this->textdomain ),
                        'wpb-psc-table-bordered'  => esc_html__( 'Bordered', $this->textdomain ),
                        'wpb-psc-table-bordered wpb-psc-table-dark'  => esc_html__( 'Bordered Dark', $this->textdomain ),
                        'wpb-psc-table-hover'  => esc_html__( 'Hover', $this->textdomain ),
                        'wpb-psc-table-hover wpb-psc-table-dark'  => esc_html__( 'Hover Dark', $this->textdomain ),
                        'no_style'  => esc_html__( 'No Style', $this->textdomain ),
                    )
                ),
                array(
                    'name'    => 'wpb_psc_table_head_type',
                    'label'   => esc_html__( 'Table Head Type', $this->textdomain ),
                    'desc'    => esc_html__( 'Choose a table head type. Only works with table type default.', $this->textdomain ),
                    'type'    => 'select',
                    'default' => 'default',
                    'options' => array(
                        'wpb-psc-thead-default'   => esc_html__( 'Default', $this->textdomain ),
                        'wpb-psc-thead-dark'      => esc_html__( 'Dark', $this->textdomain ),
                        'wpb-psc-thead-light'     => esc_html__( 'Light', $this->textdomain ),
                    )
                ),
                array(
                    'name'    => 'wpb_psc_table_align',
                    'label'   => esc_html__( 'Table Align', $this->textdomain ),
                    'desc'    => esc_html__( 'Choose table alignment', $this->textdomain ),
                    'type'    => 'select',
                    'size'    => 'wpb-select-buttons',
                    'default' => 'default',
                    'options' => array(
                        'default'  => esc_html__( 'Default', $this->textdomain ),
                        'left'  => esc_html__( 'Left', $this->textdomain ),
                        'right'  => esc_html__( 'Right', $this->textdomain ),
                        'center'  => esc_html__( 'Center', $this->textdomain ),
                    )
                ),
            ),
            'wpb_psc_btn_style' => array(
                array(
                    'name'    => 'wpb_psc_btn_type',
                    'label'   => esc_html__( 'Button Type', $this->textdomain ),
                    'desc'    => esc_html__( 'Choose a button type. Default: plain text.', $this->textdomain ),
                    'type'    => 'select',
                    'size'    => 'wpb-select-buttons',
                    'default' => 'button',
                    'options' => array(
                        'plain_text'    => esc_html__( 'Plain Text', $this->textdomain ),
                        'button'        => esc_html__( 'Normal Button', $this->textdomain ),
                    )
                ),
                array(
                    'name'    => 'wpb_psc_btn_size',
                    'label'   => esc_html__( 'Button Size', $this->textdomain ),
                    'desc'    => esc_html__( 'Select button size. Default: Medium.', $this->textdomain ),
                    'type'    => 'select',
                    'size'    => 'wpb-select-buttons',
                    'default' => 'large',
                    'options' => array(
                        'small'     => esc_html__( 'Small', $this->textdomain ),
                        'medium'    => esc_html__( 'Medium', $this->textdomain ),
                        'large'     => esc_html__( 'Large', $this->textdomain ),
                    )
                ),
                array(
                    'name'              => 'wpb_psc_btn_margin_top',
                    'label'             => esc_html__( 'Button margin top', $this->textdomain ),
                    'type'              => 'number',
                    'default'           => 15,
                    'sanitize_callback' => 'floatval'
                ),
                array(
                    'name'              => 'wpb_psc_btn_margin_bottom',
                    'label'             => esc_html__( 'Button margin bottom', $this->textdomain ),
                    'type'              => 'number',
                    'default'           => 15,
                    'sanitize_callback' => 'floatval'
                ),
                array(
                    'name'    => 'wpb_psc_btn_color',
                    'label'   => esc_html__( 'Button Color', $this->textdomain ),
                    'desc'    => esc_html__( 'Choose button color.', $this->textdomain ),
                    'type'    => 'color',
                    'default' => '#ffffff'
                ),
                array(
                    'name'    => 'wpb_psc_btn_bg_color',
                    'label'   => esc_html__( 'Button Background', $this->textdomain ),
                    'desc'    => esc_html__( 'Choose button background color.', $this->textdomain ),
                    'type'    => 'color',
                    'default' => '#17a2b8'
                ),
                array(
                    'name'    => 'wpb_psc_btn_hover_color',
                    'label'   => esc_html__( 'Button Hover Color', $this->textdomain ),
                    'desc'    => esc_html__( 'Choose button hover color.', $this->textdomain ),
                    'type'    => 'color',
                    'default' => '#ffffff'
                ),
                array(
                    'name'    => 'wpb_psc_btn_bg_hover_color',
                    'label'   => esc_html__( 'Button Hover Background', $this->textdomain ),
                    'desc'    => esc_html__( 'Choose button hover background color.', $this->textdomain ),
                    'type'    => 'color',
                    'default' => '#138496'
                ),
                array(
                    'name'              => 'wpb_psc_plaintext_font_size',
                    'label'             => esc_html__( 'Font Size', $this->textdomain ),
                    'type'              => 'number',
                    'sanitize_callback' => 'floatval'
                ),
                array(
                    'name'              => 'wpb_psc_plaintext_color',
                    'label'             => esc_html__( 'Text Color', $this->textdomain ),
                    'type'              => 'color',
                    'default'           => '#212529'
                ),
                array(
                    'name'              => 'wpb_psc_plaintext_hover_color',
                    'label'             => esc_html__( 'Text Color Hover', $this->textdomain ),
                    'type'              => 'color',
                    'default'           => '#5e5e5e'
                ),
            ),
            'wpb_psc_popup_style' => array(
                array(
                    'name'      => 'wpb_psc_popup_style',
                    'label'     => esc_html__( 'Enable Popup Style', $this->textdomain ),
                    'desc'      => esc_html__( 'Check this to enable the popup style.', $this->textdomain ),
                    'type'      => 'checkbox',
                    'default'   => 'on',
                ),
                array(
                    'name'              => 'wpb_psc_popup_width',
                    'label'             => esc_html__( 'Popup Width', $this->textdomain ),
                    'desc'              => esc_html__( 'Popup window width, Can be in px or %. The default width is 960px.', $this->textdomain ),
                    'type'              => 'numberunit',
                    'default'           => 960,
                    'default_unit'      => 'px',
                    'sanitize_callback' => 'floatval',
                    'options' => array(
                        'px'   => esc_html__( 'Px', $this->textdomain ),
                        '%'    => esc_html__( '%', $this->textdomain ),
                    )
                ),
                array(
                    'name'              => 'wpb_psc_popup_bg',
                    'label'             => esc_html__( 'Popup background', $this->textdomain ),
                    'type'              => 'color',
                    'default'           => '#ffffff'
                ),
                array(
                    'name'              => 'wpb_psc_popup_color',
                    'label'             => esc_html__( 'Popup content color', $this->textdomain ),
                    'type'              => 'color',
                ),
                array(
                    'name'              => 'wpb_psc_popup_close_color',
                    'label'             => esc_html__( 'Popup close icon color', $this->textdomain ),
                    'type'              => 'color',
                ),
            ),
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div id="wpb-psc-settings" class="wrap wpb-plugin-settings-wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';

        do_action( 'wpb_psc_lite_after_settings_page' );
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;