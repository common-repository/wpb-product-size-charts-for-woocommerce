<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.


/**
 * Text sanitize
 */

if( ! function_exists( 'wpb_psc_sanitize_text' ) ) {
  function wpb_psc_sanitize_text( $value, $field ) {
    return wp_filter_nohtml_kses( $value );
  }
  add_filter( 'wpb_psc_sanitize_text', 'wpb_psc_sanitize_text', 10, 2 );
}


/**
 * Number sanitize
 */

if( ! function_exists( 'wpb_psc_sanitize_number' ) ) {
  function wpb_psc_sanitize_number( $value, $field ) {
    return intval( $value );
  }
  add_filter( 'wpb_psc_sanitize_number', 'wpb_psc_sanitize_number', 10, 2 );
}


/**
 * Checkbox sanitize
 */


if( ! function_exists( 'wpb_psc_sanitize_checkbox' ) ) {
  function wpb_psc_sanitize_checkbox( $value, $field ) {

    if( ! empty( $value ) && $value == 'on' ) {
      $value = true;
    }

    if( empty( $value ) ) {
      $value = false;
    }

    return $value;

  }
  add_filter( 'wpb_psc_sanitize_checkbox', 'wpb_psc_sanitize_checkbox', 10, 2 );
}


/**
 * posts & categories sanitize
 */


if( ! function_exists( 'wpb_psc_sanitize_posts' ) ) {
  function wpb_psc_sanitize_posts( $value ) {
    return wp_filter_nohtml_kses( $value );
  }
  add_filter( 'wpb_psc_sanitize_posts', 'wpb_psc_sanitize_posts' );
  add_filter( 'wpb_psc_sanitize_categories', 'wpb_psc_sanitize_posts' );
}



/**
 * table sanitize
 */


if( ! function_exists( 'wpb_psc_sanitize_table' ) ) {
  function wpb_psc_sanitize_table( $value ) {
    return wp_filter_nohtml_kses( $value );
  }
  add_filter( 'wpb_psc_sanitize_table', 'wpb_psc_sanitize_table' );
}