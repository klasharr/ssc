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

require_once( 'inc/class-ssc-race-with-safety-team-filter.php' );
require_once( 'inc/class-sail-type.php' );

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
		OpenClub\CSV_Display::get_config(
			array(
				'context' => 'ssc_safety_teams_shortcode',
			)
		),
		$config
	);
	return OpenClub\CSV_Display::get_html( $config, SSC_PLUGIN_DIR );
} );

add_filter( 'openclub_csv_display_data', 'ssc_prep_safety_teams_shortcode_data', 10, 2 );


add_action( 'wp_head', function() {
	?>
	<style>

		table.ssc-safety-team-table th {
			background-color: #EFEFEF;
			font-size: 1.1em;
			padding: 0.5em 0.5em 0.5em 0.5em;
		}

		table.ssc-safety-team-table {
			width: 400px;
			margin: 1em;
			float: left;
			margin-bottom: 1.5em;
		}

		table.ssc-safety-team-table tr.ssc-safety-teams-ro td{
			font-weight: bold;
			font-size: 0.9em;
			padding: 0.2em 0.2em 0.2em 0.5em;
		}

		table.ssc-safety-team-table td{
			font-size: 0.9em;
			padding: 0.2em 0.2em 0.2em 0.5em;
		}

		p.ssc_safety_teams_team_link {
			font-size: 1.2em;
		}
	</style>
	<?php
} );




/**
 * @todo perhaps there is a better way to alter the data late before passing to the template, perhaps passing
 * in an overridden data output object. But this is nice and simple.
 *
 * @param \OpenClub\Output_Data $data
 * @param \OpenClub\Data_Set_Input $input
 *
 * @return \OpenClub\Output_Data
 */
function ssc_prep_safety_teams_shortcode_data( \OpenClub\Output_Data $data, \OpenClub\Data_Set_Input $input ){

	if( $input->get_context() == 'ssc_safety_teams_shortcode' ) {
		foreach($data->get_rows() as $team => $safety_team_members ){
			$array_index = 0;
			foreach( $safety_team_members as $member ){

				$type = 'Crew';

				if( 'yes' == $data->rows[$team][$array_index]['data']['Beach Master']['value'] ){
					$type = 'Beach Master';
				}

				if( 'yes' == $data->rows[$team][$array_index]['data']['Rib Driver']['value'] ){
					$type = 'Rib driver';
				}

				if( 'yes' == $data->rows[$team][$array_index]['data']['Deputy RO']['value'] ){
					$type .= '/ Deputy RO';
				}

				if( 'yes' == $data->rows[$team][$array_index]['data']['RO']['value'] ){
					$type = 'Race Officer';
				}

				$data->rows[$team][$array_index]['data']['type']['value'] = $type;
				$data->rows[$team][$array_index]['data']['type']['formatted_value'] = $type;

				if($type == 'Race Officer' && $data->rows[$team][$array_index]['error'] == 0 ) {
					$data->rows[$team][$array_index]['class'] = 'ssc-safety-teams-ro';
				}
				$array_index++;
			}
		}

		ksort($data->rows, SORT_STRING);
	}
	return $data;
}

add_shortcode( 'ssc_programme', function( $config ){

	$config = shortcode_atts(
		OpenClub\CSV_Display::get_config(
			array(
			'context' => 'ssc_programme',
			'filter' =>  new \OpenClub\SSC_Race_With_Safety_Team_Filter()
		)),
		$config
	);

	return OpenClub\CSV_Display::get_html( $config, OPENCLUB_PROGRAMME_PLUGIN_DIR );
} );