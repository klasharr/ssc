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
 *
 *
 */
add_shortcode( 'ssc_programme', function ( $config ) {

	$config = shortcode_atts(
		OpenClub\CSV_Display::get_config(),
		$config
	);

	$config = openclub_csv_get_future_events_only_query_value( $config );

	return OpenClub\CSV_Display::get_html( $config, SSC_PLUGIN_DIR );
} );


/**
 * Examples
 *
 * Vanilla
 * [ssc_safety_teams post_id=311]
 *
 * Grouping by team using the safety team template
 * [ssc_safety_teams post_id=311 error_lines="yes" error_messages="yes" display="safety_teams" group_by_field="Team"]
 */
add_shortcode( 'ssc_safety_teams', function ( $config ) {

	$config = shortcode_atts(
		OpenClub\CSV_Display::get_config(
			array(
				'context' => 'ssc_safety_teams_shortcode',
			) ),
		$config
	);

	return OpenClub\CSV_Display::get_html( $config, SSC_PLUGIN_DIR );
} );

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