<?php

namespace OpenClub;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class SSC_House_Duty implements Filter {

	/**
	 * @param DTO $dto
	 *
	 * @return bool
	 */
	public function is_filtered_out( DTO $dto ) {
		
		if( !in_array( $dto->get_value( 'Day' ), array( 'Thu', 'Sun' ) ) ) {
			return true;
		}
	}

}