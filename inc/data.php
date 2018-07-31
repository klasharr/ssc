<?php


/**
 * @param \OpenClub\Output_Data $data
 * @param \OpenClub\Data_Set_Input $input
 *
 * @see templates/safety_teams.php
 *
 * @return \OpenClub\Output_Data
 */
function ssc_prep_safety_teams_shortcode_data( \OpenClub\Output_Data $data, \OpenClub\Data_Set_Input $input ) {

	if ( $input->get_context() == 'ssc_safety_teams_shortcode' ) {

		$race_officers = array();
		$tmp           = array();

		foreach ( $data->get_rows() as $team => $safety_team_members ) {
			$array_index = 0;
			foreach ( $safety_team_members as $member ) {

				$type = 'Crew';

				if ( 'yes' == $data->rows[ $team ][ $array_index ]['data']['Beach Master']['value'] ) {
					$type = 'Beach Master';
				}

				if ( 'yes' == $data->rows[ $team ][ $array_index ]['data']['Rib Driver']['value'] ) {
					$type = 'Rib driver';
				}

				if ( 'yes' == $data->rows[ $team ][ $array_index ]['data']['Deputy RO']['value'] ) {
					$type .= '/ Deputy RO';
				}

				if ( 'yes' == $data->rows[ $team ][ $array_index ]['data']['RO']['value'] ) {
					$type = 'Race Officer';
				}

				$data->rows[ $team ][ $array_index ]['data']['type']['value']           = $type;
				$data->rows[ $team ][ $array_index ]['data']['type']['formatted_value'] = $type;

				// Weekend or Thursday team
				$team_day                                                                    = is_numeric( $team ) ? 'Weekend' : 'Thursday';
				$data->rows[ $team ][ $array_index ]['data']['team_type']['value']           = $team_day;
				$data->rows[ $team ][ $array_index ]['data']['team_type']['formatted_value'] = $team_day;


				if ( 'Race Officer' === $type && $data->rows[ $team ][ $array_index ]['error'] == 0 ) {
					$data->rows[ $team ][ $array_index ]['class'] = 'ssc-safety-teams-ro';
					$race_officers[ $team_day ][]                 = $data->rows[ $team ][ $array_index ];
				}

				$tmp['teams'][ $team_day ][ $team ][ $array_index ]['data']  = $data->rows[ $team ][ $array_index ]['data'];
				$tmp['teams'][ $team_day ][ $team ][ $array_index ]['error'] = $data->rows[ $team ][ $array_index ]['error'];
				$tmp['teams'][ $team_day ][ $team ][ $array_index ]['class'] = $data->rows[ $team ][ $array_index ]['class'];

				$array_index ++;
			}
		}

		$tmp['race_officers'] = $race_officers;

		$data->set_rows( $tmp );
	}

	return $data;
}
