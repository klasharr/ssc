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
	 * @todo named vars
	 *
	 * @param $args array(
	 *                  0 => openclub_csv id for events,
	 *                  1 => openclub_csv id for safety teams,
	 *              )
	 *
	 * @throws \Exception
	 * @throws \OpenClub\Exception
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

			$events = $this->get_events_with_safety_teams( $events_post_id );

			foreach ( $events as $data ) {
				WP_CLI::log( serialize( $data ) );
			}

			$safety_teams = $this->get_safety_teams( $safety_teams_id );

			print_r( $safety_teams->get_rows() );

			WP_CLI::success( '====== Success! !! ====== ' );

		} catch ( \Exception $e ) {
			\WP_CLI::error( $e->getMessage() );
		}

	}

	/**
	 * @todo make $input write itself neatly
	 *
	 * @param $events_post_id int openclub_csv id
	 *
	 * @return \OpenClub\Output_Data
	 */
	private function get_events_with_safety_teams( $events_post_id ) {

		if ( empty( $events_post_id ) || ! is_numeric( $events_post_id ) ) {
			throw new \Exception( '$events_post_id ' . $events_post_id . ' must be an integer' );
		}

		/**
		 * @var $input \OpenClub\Data_Set_Input
		 */
		$input = \OpenClub\Factory::get_data_input_object(
			array(
				'post_id' => $events_post_id,
				'filter'  => 'SSC_Safety_Team',
				'fields'  => 'Date,Time,Event,Team',
			)
		);

		$events = \OpenClub\Factory::get_output_data( $input );

		if ( ! $events->exists() ) {
			throw new \Exception( 'No data for $input' );
		}

		$out = array();
		foreach ( $events->get_rows() as $row ) {
			foreach ( $row['data'] as $fieldname => $values ) {
				$tmp[ $fieldname ] = $values['formatted_value'];
			}
			$out[] = $tmp;
		}

		return $out;
	}


	/**
	 * @todo make $input write itself neatly
	 *
	 * @param $safety_teams_post_id int openclub_csv id
	 *
	 * @return \OpenClub\Output_Data
	 */
	private function get_safety_teams( $safety_teams_post_id ) {

		if ( empty( $safety_teams_post_id ) || ! is_numeric( $safety_teams_post_id ) ) {
			throw new \Exception( '$safety_teams_post_id ' . $safety_teams_post_id . ' must be an integer' );
		}

		/**
		 * @var $input \OpenClub\Data_Set_Input
		 */
		$input = \OpenClub\Factory::get_data_input_object(
			array(
				'post_id'        => $safety_teams_post_id,
				'context'        => 'ssc_safety_teams_shortcode',
				'group_by_field' => 'Team',
			)
		);

		$safety_teams = \OpenClub\Factory::get_output_data( $input );

		if ( ! $safety_teams->exists() ) {
			throw new \Exception( 'No data for $input' );
		}

		return $safety_teams;
	}

}


