<?php

/**
 * ===== Examples =====
 *
 * Vanilla output
 * [ssc_programme post_id=102]
 *
 * Sailing programme with safety teams only grouped events on date to allow the future events only filter
 * [ssc_programme post_id=102 group_by_field="Date" future_events_only="yes"  display="grouped_date_table" filter="SSC_Safety_Team"]
 *
 *
 * Training events only
 * [ssc_programme post_id="1361" filter="SSC_Training" field="Date,Event,Time"]
 *
 * Sailing programme with reduced fields, in the future only
 * [ssc_programme post_id=102 group_by_field="Date" display="grouped_date_table" future_events_only="yes" fields="Day,Date,Event,Time,Team,Junior"]
 *
 * Sailing programme, future events only showing hide/show links to show all or future events only.
 * [ssc_programme post_id=102 group_by_field="Date" display="grouped_date_table" future_events_only="yes" show_future_past_toggle=1]
 *
 * [openclub_display_csv post_id=1365 error_lines="yes" error_messages="yes"  display="safety_teams" plugin_template_dir="SSC_PLUGIN_DIR" context="ssc_safety_teams_shortcode" group_by_field="Team"]
 *
 */

/**
 * Example
 *
 * [ssc_empty_house_duties post_id=134 limit=10]
 */
add_shortcode( 'ssc_empty_house_duties', function ( $config ) {

	$config = shortcode_atts(
		OpenClub\CSV_Display::get_config(
			array(
				'display'            => 'ssc_empty_house_duties',
				'filter'             => 'SSC_Empty_Duties',
				'group_by_field'     => 'Duty Date',
				'future_events_only' => 'yes',
				'error_lines'        => 'yes',
				'error_messages'     => "yes",
			) ),
		$config
	);

	return OpenClub\CSV_Display::get_html( $config, SSC_PLUGIN_DIR );
} );