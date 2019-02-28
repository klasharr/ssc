<?php

namespace SSC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \OpenClub\Factory;
use \OpenClub\CSV_Util;
use \WP_CLI;


Class Command {

	/**
	 * Get a list of empty house duties for upload into Dutyman from a sailing programme file.
	 *
	 * ## EXAMPLES
	 *
	 *     wp ssc dutyman_format_empty_house_duties <sailing_programe_id>
	 *
	 * @throws \Exception
	 */
	public function dutyman_format_empty_house_duties( $args ){

		// 1. Get the sailing events

		if ( empty( $args[0] ) || (int) $args[0] === 0 ) {
			throw new \Exception( 'The first argument must be a non zero integer value.' );
		}

		$input = \OpenClub\Factory::get_data_input_object(
			array(
				'post_id' => $args[0],
				'filter'  => 'SSC_House_Duty',
				'fields'  => 'Date,Time,Event,Team,Day',
				'display' => 'default', // @todo, if a value is missing, this throws an error.
			)
		);

		/**
		 * @var $events \OpenClub\Output_Data
		 */
		$events = \OpenClub\Factory::get_output_data( $input );

		if ( ! $events->exists() ) {
			throw new \Exception( 'No data for $input' );
		}

		// 2. Get house duties list from the sailing events

		$raw_house_duties = array();

		foreach( $events->get_rows() as $event ) {
			$raw_house_duties = array_merge( $raw_house_duties, get_house_duties_for_single_event( $event['data'] ) );
		}

		// 3. Get CSV key => value pairs ready to write to CSV file

		$duties = array();

		foreach( $raw_house_duties as $event ) {
			$duties[] = get_house_duty_dutyman_format_csv_row_data_for_single_event( $event );
		}

		// 4.  Write CSV duties

		WP_CLI::log( get_duties_csv_header_row_as_string() );

		foreach( $duties as $single_duty_array ){
			WP_CLI::log(  get_single_duty_as_csv_row_string( $single_duty_array ) );
		}
	}

	/**
	 * Get a list of safety teams
	 *
	 * ## EXAMPLES
	 *
	 *      wp ssc safety_teams <safety_teams_list_id>
	 *      wp ssc safety_teams <safety_teams_list_id> <display_control_var>
	 *      wp ssc safety_teams <safety_teams_list_id> <display_control_var>
	 *
	 * ## SYNOPSIS
	 *
	 * @return array|void
	 * @throws \Exception
	 */
	public function safety_teams( $args ){

		if ( empty( $args[0] ) || (int) $args[0] === 0 ) {
			throw new \Exception( 'The first argument must be a non zero integer value.' );
		}

		$return_type = null;

		if( !empty( $args[1] ) && in_array( $args[1], array( 'list', 'array' ) ) ) {
			$return_type = $args[1];
		} elseif( !empty( $args[1] ) && !in_array( $args[1], array( 'list', 'array' ) ) ) {
			throw new \Exception( 'Second arg must be empty or list or array' );
		}

		$out = $this->get_safety_teams_data( $args[0], $return_type );
		WP_CLI::log( print_r( $out,1 ) );
		return $out;
	}


	/**
	 * @param $safety_teams_id
	 * @param $return_type null/string (array or list)
	 *
	 * @return array|void
	 * @throws \Exception
	 */
	private function get_safety_teams_data( $safety_teams_id, $return_type = null ){

		if ( empty( $safety_teams_id ) || (int) $safety_teams_id === 0 ) {
			throw new \Exception( 'The first argument must be a non zero integer value.' );
		}

		/**
		 * @var $input \OpenClub\Data_Set_Input
		 */
		$input = \OpenClub\Factory::get_data_input_object(
			array(
				'post_id'        => $safety_teams_id,
				'context'        => 'ssc_safety_teams_shortcode',
				'group_by_field' => 'Team',
				'display'        => 'default',
			)
		);

		$safety_teams = \OpenClub\Factory::get_output_data( $input );

		if ( ! $safety_teams->exists() ) {
			throw new \Exception( 'No data for $input' );
		}

		$out = array();
		foreach( $safety_teams->get_rows() as $team => $members ) {
			foreach($members as $member){
				$out[$team][] = get_safety_team_member_simple_data($member);
			}
		}

		if( empty( $return_type)) {
			return $out;
		}
		
		if( $return_type == 'array') {
			print_r( $out );
			return;
		} elseif( $return_type == 'list'){
			WP_CLI::log('POST ID: '. $safety_teams_id);
			foreach( $out as $team => $members ){
				WP_CLI::log("----------------------");
				WP_CLI::log( $team );
				foreach( $members as $member ) {
					WP_CLI::log( get_safety_team_member_debug_row( $member ) );
				}
			}
		}
	}


	/**
	 * Get a list of safety duties, populated with assignees.
	 *
	 * ## EXAMPLES
	 *
	 *      wp ssc safety_duties <sailing_programme_id> <safety_teams_list_id>
	 *
	 * ## SYNOPSIS
	 *
	 * @return array|void
	 * @throws \Exception
	 */
	public function safety_duties( $args ) {

		try {

			if ( empty( $args[0] ) || (int) $args[0] === 0 ) {
				throw new \Exception( 'The first argument must be a non zero integer value.' );
			}

			if ( empty( $args[1] ) || (int) $args[1] === 0 ) {
				throw new \Exception( 'The second argument must be a non zero integer value.' );
			}

			$events_post_id  = $args[0];
			$safety_teams_id = $args[1];

			$events = $this->get_events_with_safety_teams_only( $events_post_id );
			$teams = $this->get_safety_teams_data( $safety_teams_id );

			$raw_safety_duties = array();

			foreach( $events->get_rows() as $event ) {

				$team_id = $event['data']['Team']['value'];
				if( empty( $team_id ) ) {
					throw new \Exception( 'Event has no team value, ', print_r( $event, 1 ) );
				}

				$raw_safety_duties = array_merge(
					$raw_safety_duties,
					get_safety_duties_for_single_event( $event['data'], $teams[ $team_id ] )
				);
			}

			$duties = array();

			foreach( $raw_safety_duties as $single_duty ) {
				$duties[] = get_safety_duty_dutyman_format_csv_row_data_for_single_duty( $single_duty );
			}

			// 4.  Write CSV duties

			WP_CLI::log( get_duties_csv_header_row_as_string() );

			foreach( $duties as $single_duty_array ){
				WP_CLI::log(  get_single_duty_as_csv_row_string( $single_duty_array ) );
			}
			
			WP_CLI::success( '====== Success! !! ====== ' );

		} catch ( \Exception $e ) {
			\WP_CLI::error( $e->getMessage() );
		}

	}

	/**
	 * @param $events_post_id int openclub_csv id
	 *
	 * @return \OpenClub\Output_Data
	 */
	private function get_events_with_safety_teams_only( $events_post_id ) {

		if ( empty( $events_post_id ) || (int) $events_post_id === 0 ) {
			throw new \Exception( '$events_post_id must be a non zero integer value.' );
		}

		$input = \OpenClub\Factory::get_data_input_object(
			array(
				'post_id' => $events_post_id,
				'filter'  => 'SSC_Safety_Team',
				'fields'  => 'Date,Time,Event,Team,Day',
				'display' => 'csv_rows',
				'context' => 'ssc_safety_teams_shortcode',
			)
		);

		/**
		 * @var $events \OpenClub\Output_Data
		 */
		$events = \OpenClub\Factory::get_output_data( $input );

		if ( ! $events->exists() ) {
			throw new \Exception( 'No data for $input' );
		}

		return $events;
	}

}


