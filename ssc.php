<?php

/*
 Plugin Name: SSC
 Plugin URI: 
 Description:
 Author: Klaus Harris
 Version: -1
 Author URI: https://klaus.blog
 Text Domain: ssc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SSC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( 'inc/class-ssc-safety-team-filter.php' );
require_once( 'inc/class-ssc-empty-duties-filter.php' );
require_once( 'inc/class-ssc-training-filter.php' );
require_once( 'inc/data.php' );
require_once( 'inc/shortcodes.php' );
require_once( 'inc/head.php' );

/**
 * Openclub CSV Dependency Check
 */
add_action( 'admin_init', function() {
	if( !class_exists( '\OpenClub\Factory' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		if( !empty( $_GET['activate'] ) ) {
			unset($_GET['activate']);
		}
		add_action( 'admin_notices', function(){
			echo '<div class="notice notice-warning is-dismissible">
             <p>Plugin OpenClub CSV must exist and be activated. ' . plugin_dir_path( __FILE__ ) . 'has been deactivated</p>
         </div>';
		});
	}
});

if ( class_exists( 'WP_CLI' ) ) {

	require_once( SSC_PLUGIN_DIR . 'cli/class-command.php' );
	$command = new \SSC\Command;
	WP_CLI::add_command( 'ssc', $command );

}

add_filter( 'openclub_csv_display_data', 'ssc_prep_safety_teams_shortcode_data', 10, 2 );





