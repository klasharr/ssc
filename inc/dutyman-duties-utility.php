<?php

function get_house_duties_for_single_event( array $event ) {

	$duty_instructions = "If you are sailing, please start as soon as you can. Swap on Dutyman if you can't make this duty. In case of problems contact team lead Roger Condie 07707 671692";

	$basic_event = array(
		'Event'             => $event['Event']['formatted_value'],
		'Duty Date'         => $event['Date']['formatted_value'],
		'Duty Time'         => get_house_duty_time( $event['Time']['formatted_value'] ),
		'Duty Instructions' => $duty_instructions,
	);

	// There are four house duties currently

	$out[] = array_merge( $basic_event, array( 'Duty Type' => 'Galley', ));
	$out[] = array_merge( $basic_event, array( 'Duty Type' => 'Galley', ));
	$out[] = array_merge( $basic_event, array( 'Duty Type' => 'Bar', ));
	$out[] = array_merge( $basic_event, array( 'Duty Type' => 'Bar', ));

	return $out;
}

function get_safety_duty_dutyman_format_csv_row_data_for_single_duty( array $data ) {

	$dutyman_duty_csv_fields = get_dutyman_format_csv_column_headings();

	$dutyman_duty_csv_fields['Duty Date'] = $data[ 'Duty Date' ];
	$dutyman_duty_csv_fields['Event']     = $data['Event'];
	$dutyman_duty_csv_fields['Duty Time'] = $data[ 'Duty Time' ];
	$dutyman_duty_csv_fields['Duty Type'] = $data[ 'Duty Type' ];
	$dutyman_duty_csv_fields['Duty Instructions'] = $data[ 'Duty Instructions' ];
	$dutyman_duty_csv_fields['Duty Notify'] = $data[ 'Duty Notify' ];
	$dutyman_duty_csv_fields['First Name'] = $data[ 'First Name' ];
	$dutyman_duty_csv_fields['Last Name'] = $data[ 'Last Name' ];
	$dutyman_duty_csv_fields['Last Name'] = $data[ 'Last Name' ];

	return $dutyman_duty_csv_fields;
}



function get_house_duty_dutyman_format_csv_row_data_for_single_event( array $event ) {

	$dutyman_duty_csv_fields = get_dutyman_format_csv_column_headings();

	$dutyman_duty_csv_fields['Duty Date'] = $event[ 'Duty Date' ];
	$dutyman_duty_csv_fields['Event']     = $event['Event'];
	$dutyman_duty_csv_fields['Duty Time'] = (string) $event[ 'Duty Time' ];
	$dutyman_duty_csv_fields['Duty Type'] = $event[ 'Duty Type' ];
	$dutyman_duty_csv_fields['Duty Instructions'] = $event[ 'Duty Instructions' ];

	return $dutyman_duty_csv_fields;
}


function get_safety_duty_start_time( $event_start_time ) {

	switch ( $event_start_time ) {

		case '1400';
			return '1230';
			break;

		case '1030';
			return '0900';
			break;

		case '1100';
			return '0930';
			break;

		case '1830';
			return '1700';
			break;

		case '1900':
			return '1730';
			break;
		default:
			if ( class_exists( 'WP_CLI' ) ) {
				\WP_CLI::warning( 'No switch case for time: ' . $event_start_time );
			}
			return $event_start_time . '[no switch case!!]';
	}
}

function get_house_duty_time( $event_start_time ) {

	switch ( $event_start_time ) {
		case '1830';
			return '1930';
			break;

		case '1900';
			return '2000';
			break;

		case '1030':
		case '1100':
			return '1145';
			break;
		default:
			if ( class_exists( 'WP_CLI' ) ) {
				\WP_CLI::warning( 'No switch case for time: ' . $event_start_time );
			}
			return $event_start_time . '[no switch case!!]';
	}
}

/**
 * See dutyman upload format here: https://dutyman.biz/dmahelp/duties1_1.aspx
 *
 * @return array
 */
function get_dutyman_format_csv_column_headings() {

	return array(
		'Duty Date'         => '', // Yes dd/mm/yy
		'Duty Time'         => '', // Yes
		'Event'             => '', // Yes A description of what is taking place
		'Duty Type'         => '', // Yes A brief description of the duty, for example Race Officer, Results, Bar
		'Swappable'         => '', // Default Yes
		'Reminders'         => '', // Default Yes
		'Confirmed'         => '', // Default No
		'Duty Notify'       => '',  // One or more email addresses of a people associated with
									// this duty to be notified when a member swaps this duty.
									//  Multiple email addresses must be separated by semi-colons,
									// Example: john@xxx.com; sue@yyy.org
		'Duty Instructions' => '',  // Instructions to be included in reminder emails for this duty. Semi colon separator 255
		'Duty DBID'         => '',  // Optional internal ID
		'First Name'        => '',  // First and Second name must correspond to a member in the Members List
		'Last Name'         => '',  // First and Second name must correspond to a member in the Members List
		'Member Name'       => '', // Must be in Dutyman Members list
		'Alloc'             => '', // Default No
		'Notes'             => ''  // Any additional information.  Notes do not appear on the web. 255
	);
}


function get_duties_csv_header_row_as_string() {
	return implode( ',', array_keys(
		get_dutyman_format_csv_column_headings()
	) );
}

function get_single_duty_as_csv_row_string( $a ) {
	return implode( ',', array_values( $a ) );
}


function get_safety_duties_for_single_event( array $event, array $team ) {

	$duty_instructions = "Please only swap like for like duties. If you have any questions please contact your Team Leader.";

	$basic_event = array(
		'Event'             => $event['Event']['formatted_value'],
		'Duty Date'         => $event['Date']['formatted_value'],
		'Duty Time'         => get_safety_duty_start_time( $event['Time']['formatted_value'] ),
		'Duty Instructions' => $duty_instructions,
	);

	$out = array();

	foreach( $team as $member ){

		$single_member_duty = array(
			'First Name'        => $member['First Name'],
			'Last Name'         => $member['Last Name'],
			'Duty Notify'       => get_race_officer_email_from_team_members_list($team).'; safety-teams@swanagesailingclub.org.uk',
			'Duty Type'         => $member['Duty Type'],
		);

		$out[] = array_merge( $basic_event, $single_member_duty );
	}

	return $out;
}

/*
 * @return string
 *      example: Graham Bobbit, gkbobbit@icloud.com, 7, Race Officer
 */
function get_safety_team_member_debug_row( $member ) {
	return sprintf( '%s %s, %s, %s, %s',
		$member['First Name'],
		$member['Last Name'],
		$member['Email Address'],
		$member['Team'],
		$member['Duty Type']
	);
}

function get_safety_team_member_simple_data( $member ) {

	$out = array(
		'Email Address' => $member['data']['Email Address']['value'],
		'First Name' => $member['data']['First Name']['value'],
		'Last Name' => $member['data']['Second name']['value'],
		'Team' => $member['data']['Team']['value'],
		'Duty Type' => get_safety_team_role( $member ),
	);
	return $out;
}

function get_safety_team_role( $member ){

	if( $member['data']['RO']['value'] == 'yes' ) {
		return "Race Officer";
	}
	if( $member['data']['Deputy RO']['value'] == 'yes' ) {
		return "Rib Driver";
	}
	if( $member['data']['Rib Driver']['value'] == 'yes' ) {
		return "Rib Driver";
	}
	if( $member['data']['Beach Master']['value'] == 'yes') {
		return "Beach Master";
	}

	return "Crew";

}

function get_race_officer_email_from_team_members_list( $team ) {

	foreach( $team as $member ) {

		if( $member[ 'Duty Type' ] == "Race Officer" ) {
			return $member[ 'Email Address' ];
		}
	}

	if ( class_exists( 'WP_CLI' ) ) {
		\WP_CLI::warning( 'No race officer in : ' . print_r( $team, 1 ) );
	}

}