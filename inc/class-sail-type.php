<?php


class SSC_Sail_Type {

	const
		ADULT_TRAINING = 13,
		CRUISE = 4,
		CUP_RACE = 2,
		FUN_SERIES = 11,
		JUNIOR_TRAINING = 14,
		OPEN_EVENT = 10,
		OTHER = 17,
		RACE_SERIES = 1,
		SPECIAL_RACE = 5,
		TGIF = 3,
		ADULT_SOCIAL_SAILING = 18,
		CLUBHOUSE_MEETING = 19,
		DAYLIGHTSAVING = 20,
		BANK_HOLIDAY = 21,
		JUNIOR_WEEK = 22;

	private $type_mapping = array(

		self::RACE_SERIES    => "Race series",
		self::CUP_RACE       => "Cup race",
		self::TGIF           => "TGIF",
		self::CRUISE         => "Cruise",
		self::SPECIAL_RACE   => "Special race",
		self::ADULT_TRAINING => "Adult Training",

		self::FUN_SERIES      => "Fun Series",
		self::JUNIOR_TRAINING => "Junior Training",
		self::OPEN_EVENT      => "Open event",
		self::OTHER           => "Other",

		self::ADULT_SOCIAL_SAILING => "Adult Social Sailing",
		self::CLUBHOUSE_MEETING    => "Clubhouse meeting",
		self::DAYLIGHTSAVING       => 'Daylight saving change',
		self::BANK_HOLIDAY         => 'Bank holiday',
		self::JUNIOR_WEEK          => 'Junior week start/end',
	);

	private $cup_races = array(
		'The Opener',
		'1974 Cup',
		'Commodores Cup',
		'James Day Cup',
		'Owerdale Cup',
		'Coronation Cup',
		'Elizabeth Cup',
		'Wessex Shield',
		'Vikki Thornhill Cup',
		'Chellingworth Cup',
		'Fleming Trophy',
		'RNLI Pennant',
		'Rees Cup',
		'Knoll Cup',
		'Bent Cup',
		'Macdona',
		'Summer Evening Carnival Race',
		'Cup Reserve',
	);

	private $meetings = array(
		'AGM',
		'Active Sailors Meeting',
		'Guest speaker talk',
		'RYA Race Procedures Course',
		'Beach Clean and boat move',
		'First Aid Course',
		'Boat move and Beach Clean',
		'Winter Berthing starts',
		'Members Induction',
		'Family Watersports and Summer Disco',
	);

	/*
	 * Get the name for this type of sailing event.
	 *
	 * @param $event string
	 * @return string
	 */
	public function get( $event ) {

		if ( empty( $event ) ) {
			throw new Exception( 'Event is empty.' );
		}

		if ( preg_match( "/Junior Training/i", $event ) ) {
			return self::JUNIOR_TRAINING;
		}

		if ( preg_match( "/Bank holiday/i", $event )
		) {
			return self::BANK_HOLIDAY;
		}

		if ( preg_match( "/Junior week/i", $event )
		) {
			return self::JUNIOR_WEEK;
		}

		if ( preg_match( "/BRITISH SUMMER TIME/i", $event ) ||
		     preg_match( "/CLOCKS/i", $event )
		) {
			return self::DAYLIGHTSAVING;
		}

		if ( preg_match( "/Adult Training/i", $event ) ||
		     preg_match( "/Powerboat Practice/i", $event ) ||
		     preg_match( "/start Racing/i", $event )
		) {
			return self::ADULT_TRAINING;
		}

		if ( preg_match( "/Winter Fun/i", $event ) ) {
			return self::FUN_SERIES;
		}

		if ( preg_match( "/Series/i", $event ) ) {
			return self::RACE_SERIES;
		}

		if ( preg_match( "/cruise/i", $event ) ) {
			return self::CRUISE;
		}

		if ( preg_match( "/TGIF/i", $event ) ) {
			return self::TGIF;
		}

		if ( preg_match( "/regatta/i", $event ) ) {
			return self::OPEN_EVENT;
		}

		if ( $this->is_club_house_meeting( $event ) ) {
			return self::CLUBHOUSE_MEETING;
		}

		if ( $this->is_cup_race( $event ) ) {
			return self::CUP_RACE;
		}

		if ( preg_match( "/Adult Social Sailing/i", $event ) ) {
			return self::FUN_SERIES;
		}

		if ( preg_match( "/RNLI Challenge Event/i", $event ) ) {
			return self::OTHER;
		}


		throw new Exception( $event . ' is not a recognised event' );
	}

	/**
	 * @param $event
	 *
	 * @return boolean
	 */
	private function is_club_house_meeting( $event ) {

		foreach ( $this->meetings as $meeting ) {

			$pattern = "/" . $meeting . "/i";

			if ( preg_match( $pattern, $event ) ) {
				return true;
			}
		}
	}

	/**
	 * @param $event
	 *
	 * @return int
	 */
	private function is_cup_race( $event ) {

		foreach ( $this->cup_races as $race ) {

			$pattern = "/" . $race . "/i";

			if ( preg_match( $pattern, $event ) ) {
				return self::CUP_RACE;
			}
		}
	}

	/**
	 * Get the sailing event type name.
	 *
	 * @param $event
	 *
	 * @return string
	 */
	public function get_type_name( $event ) {
		$type = $this->get( $dto );

		return $this->type_mapping[ $type ];
	}

	/**
	 * @return array
	 */
	public function get_sailing_types() {
		return $this->type_mapping;
	}

	/**
	 * @todo tidy up
	 *
	 * @param $idOrIDArray mixed int/array
	 */
	public function is_valid( $idOrIDArray ) {

		if ( is_array( $idOrIDArray ) && ! empty( $idOrIDArray ) ) {
			foreach ( $idOrIDArray as $id ) {
				if ( ! array_key_exists( (int) $id, $this->type_mapping ) ) {
					return false;
				}
			}
		} elseif ( ! empty( $idOrIDArray ) && ! array_key_exists( (int) $id, $this->type_mapping ) ) {
			return false;
		}

		return true;
	}
}