<?php

/*
 Plugin Name: SSC
 Plugin URI: 
 Description: Collection of functionality for Swanage Sailing Club. Depends on the openclub-csv plugin being active.
 Author: Klaus Harris
 Version: 1
 Author URI: https://klaus.blog
 Text Domain: ssc

 Tested up to WordPress 5.1 and Jetpack 7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ======================== Dependency check =======================
require_once( 'inc/class-openclub-csv-dependency.php' );
if(! Openclub_CSV_Dependency::check( __FILE__ ) ){
	return;
}
// =================================================================

define( 'SSC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( 'inc/class-ssc-safety-team-filter.php' );
require_once( 'inc/class-ssc-empty-duties-filter.php' );
require_once( 'inc/class-ssc-training-filter.php' );
require_once( 'inc/class-ssc-house-duty-filter.php' );
require_once( 'inc/class-ssc-results.php' );
require_once( 'inc/data.php' );
require_once( 'inc/head.php' );
require_once( 'inc/dutyman-duties-utility.php' );

if ( class_exists( 'WP_CLI' ) ) {

	require_once( SSC_PLUGIN_DIR . 'cli/class-command.php' );
	$command = new \SSC\Command;
	WP_CLI::add_command( 'ssc', $command );

}

add_filter( 'openclub_csv_display_data', 'ssc_prep_safety_teams_shortcode_data', 10, 2 );

add_shortcode( 'ssc_results', array( new SSC_Results, 'display_short_code' ) );