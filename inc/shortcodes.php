<?php

/**
 * Examples
 *
 * [ssc_programme post_id=102]
 *
 * [ssc_programme post_id=102 error_lines="yes" error_messages="yes" group_by_field="Date" future_events_only="yes"  display="grouped_date_table" filter="SSC_Safety_Team"]
 *
 */
add_shortcode( 'ssc_programme', function( $config ){

	$config = shortcode_atts(
		OpenClub\CSV_Display::get_config(),
		$config
	);

	return OpenClub\CSV_Display::get_html( $config, SSC_PLUGIN_DIR );
} );


/**
 * Examples
 * 
 * [ssc_safety_teams post_id=311]
 *
 * [ssc_safety_teams post_id=311 error_lines="yes" error_messages="yes" display="safety_teams" group_by_field="Team"]
 */
add_shortcode( 'ssc_safety_teams', function( $config ){

	$config = shortcode_atts(
		OpenClub\CSV_Display::get_config(
			array(
				'context' => 'ssc_safety_teams_shortcode',
			)),
		$config
	);

	return OpenClub\CSV_Display::get_html( $config, SSC_PLUGIN_DIR );
} );

/**
 * Example
 *
 * [ssc_empty_house_duties post_id=134 limit=10]
 */
add_shortcode( 'ssc_empty_house_duties', function( $config ){

	$config = shortcode_atts(
		OpenClub\CSV_Display::get_config(
			array(
				'display' => 'ssc_empty_house_duties',
				'filter' => 'SSC_Empty_Duties',
				'group_by_field' => 'Duty Date',
				'future_events_only' => 'yes',
				'error_lines' => 'yes',
				'error_messages' => "yes",
			)),
		$config
	);

	return OpenClub\CSV_Display::get_html( $config, SSC_PLUGIN_DIR );
} );