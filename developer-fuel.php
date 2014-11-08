<?php

/**
 * Developer Fuel
 *
 * @package Developer Fuel
 */

/**
 * Plugin Name: Developer Fuel
 * Plugin URI:
 * Description: Developer Fuel
 * Author: dan-gaia
 * Author URI:
 * Version: 0.1
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

define( 'DF_PATH', plugin_dir_path( __FILE__ ) );

// Scripts
function developer_fuel_scripts() {

	echo DF_PATH . 'scripts/df-scripts.js';

	wp_enqueue_script( 'moment-script', plugins_url() . '/developer-fuel/scripts/moment.js', 'jquery', '0.1', true );
	// wp_enqueue_script( 'df-script', plugins_url() . '/developer-fuel/scripts/moment.min.js', 'jquery', '0.1', true );

	wp_enqueue_script( 'df-script', plugins_url() . '/developer-fuel/scripts/df-scripts.js', 'jquery', '0.1', true );



}
add_action( 'admin_enqueue_scripts', 'developer_fuel_scripts' );

// Required Files
require_once DF_PATH . 'inc/df-dash-widget.php';

?>