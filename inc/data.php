<?php


/**
 * @param \OpenClub\Output_Data $data
 * @param \OpenClub\Data_Set_Input $input
 *
 * @return \OpenClub\Output_Data
 */
function ssc_prep_safety_teams_shortcode_data( \OpenClub\Output_Data $data, \OpenClub\Data_Set_Input $input ){

	return $data;

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
