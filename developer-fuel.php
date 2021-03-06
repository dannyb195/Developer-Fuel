<?php
/**
 * Plugin Name: Developer Fuel
 * Plugin URI:
 * Description: Developer Fuel
 * Author: dan-gaia, danalleyinteractive
 * Author URI:
 * Version: 0.1
 */


/**
 * Developer Fuel
 *
 * @package Developer Fuel
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

define( 'DF_PATH', plugin_dir_path( __FILE__ ) );

// Scripts
function developer_fuel_scripts() {
	wp_enqueue_script( 'moment-script', plugins_url( 'scripts/moment.min.js', __FILE__ ), 'jquery', '0.1', true );
	wp_enqueue_script( 'df-script', plugins_url( 'scripts/df-scripts.js', __FILE__ ), 'jquery', '0.1', true );
}

add_action( 'admin_enqueue_scripts', 'developer_fuel_scripts' );

// Required Files
require_once DF_PATH . 'inc/df-dash-widget.php';
