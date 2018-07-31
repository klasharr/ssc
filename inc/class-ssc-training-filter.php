<?php

namespace OpenClub;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class SSC_Training implements Filter {

	/**
	 * @param DTO $dto
	 *
	 * @return bool
	 */
	public function is_filtered_out( DTO $dto ) {

		if ( ! preg_match( "/Training/i", $dto->get_value( 'Event' ) ) ) {
			return true;
		}
	}

}