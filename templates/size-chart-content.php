<?php
/**
 * The Template for displaying size chart content
 *
 * This template can be overridden by copying it to yourtheme/product-size-chart-for-woocommerce/size-chart-content.php.
 *
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$content_elements  	= wpb_psc_get_option( 'wpb_psc_content_elements', 'wpb_psc_general_settings', array('content' => 'content', 'table' => 'table') );
$content_place   	= wpb_psc_get_option( 'wpb_psc_content_place', 'wpb_psc_general_settings', 'before' );


if( isset($content_elements) && !empty($content_elements) && $content_elements != ''){

	echo '<div class="wpb-psc-content-wrapper">';

	if( array_key_exists('title', $content_elements) ){
		printf('<h3>%s</h3>', esc_html( get_the_title( $size_id ) ));
	}

	if( array_key_exists('content', $content_elements) && $content_place == 'before'){
		echo '<div class="wpb-psc-size-content entry-content wpb-psc-size-content-before">';
			echo wpautop( get_the_content( null, false, $size_id ) );
		echo '</div>';
	}

	if( array_key_exists('table', $content_elements) ){
		echo wpb_psc_get_size_chart_table($size_id);
	}

	if( array_key_exists('content', $content_elements) && $content_place == 'after'){
		echo '<div class="wpb-psc-size-content entry-content wpb-psc-size-content-after">';
			echo wpautop( get_the_content( null, false, $size_id ) );
		echo '</div>';
	}

	echo '</div>';
}

