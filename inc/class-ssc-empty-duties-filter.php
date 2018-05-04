<?php

namespace OpenClub;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class SSC_Empty_Duties implements Filter {

	/**
	 * @param DTO $dto
	 *
	 * @return bool
	 */
	public function is_filtered_out( DTO $dto ) {

		if( !empty( $dto->get_value('Member Name') ) ) {
			return true;
		}
	}

}