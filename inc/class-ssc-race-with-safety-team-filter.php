<?php

namespace OpenClub;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class SSC_Race_With_Safety_Team_Filter implements Filter {

	/**
	 * @param DTO $dto
	 *
	 * @return bool
	 */
	public function is_filtered_out( DTO $dto ) {

		$o = new \SSC_Sail_Type();

		$data = $dto->get_data();

		if( !in_array( $o->get($data['Event']), array(1,2) ) ) {
			return true;
		}
	}

}