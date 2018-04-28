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


add_shortcode( 'ssc_safety_teams', function( $config ){

	$config = shortcode_atts(
		OpenClub\CSV_Display::get_config(),
		$config
	);

	return OpenClub\CSV_Display::get_html( $config, SSC_PLUGIN_DIR );
} );