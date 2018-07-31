<?php

namespace OpenClub;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class SSC_Safety_Team implements Filter {

	/**
	 * @param DTO $dto
	 *
	 * @return bool
	 */
	public function is_filtered_out( DTO $dto ) {

		if ( empty( $dto->get_value( 'Team' ) ) ) {
			return true;
		}
	}

}