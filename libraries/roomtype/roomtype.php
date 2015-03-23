<?php
/*------------------------------------------------------------------------
  Solidres - Hotel booking plugin for WordPress
  ------------------------------------------------------------------------
  @Author    Solidres Team
  @Website   http://www.solidres.com
  @Copyright Copyright (C) 2013 - 2015 Solidres. All Rights Reserved.
  @License   GNU General Public License version 3, or later
------------------------------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RoomType handler class
 * @package 	Solidres
 * @subpackage	RoomType
 */
class SR_Room_Type {
	const PER_ROOM_PER_NIGHT = 0;
	const PER_PERSON_PER_NIGHT = 1;
	const PACKAGE_PER_ROOM = 2;
	const PACKAGE_PER_PERSON = 3;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	/**
	 * Update states for listview
	 *
	 * @param $action
	 * @param $room_type_id
	 * @param $ids
	 */
	public function update_states( $action, $room_type_id, $ids ){
		$states = array(
			'draft' => array( 'state' => 0, 'action' => 'moved', 'title' => 'Draft' ),
			'publish' => array( 'state' => 1, 'action' => 'moved', 'title' => 'Publish' ),
			'trash' => array( 'state' => -2, 'action' => 'moved', 'title' => 'Trash' ),
			'untrash' => array( 'state' => 0, 'action' => 'restored', 'title' => 'Trash' ),
		);

		if ( isset( $action ) && array_key_exists ( $action, $states ) &&  isset( $room_type_id ) && $room_type_id != null ) {
			foreach ( $ids as $id ) {
				$this->wpdb->update( $this->wpdb->prefix . 'sr_room_types', array( 'state' => $states[$action]['state'] ), array( 'id' => $id ) );
			}
			if ( count( $ids ) == 1 ) {
				$message = __( '1 room types ' . $states[$action]['action'] . ' to the ' . $states[$action]['title'], 'solidres' );
				SR_Helper::update_message( $message );
			}
			else {
				$message = __( count( $ids ).' rooms types ' . $states[$action]['action'] . ' to the ' . $states[$action]['title'], 'solidres' );
				SR_Helper::update_message( $message );
			}
		}
	}

	/**
	 * Delete permanently action
	 *
	 * @param $id
	 * @return bool
	 */
	public function delete( $id ){
		//Get tariff ID
		$tariff_id = $this->wpdb->get_var( "SELECT id FROM {$this->wpdb->prefix}sr_tariffs WHERE valid_from = '0000-00-00' AND valid_to = '0000-00-00' AND room_type_id = $id" );
		//Get all room Id via room type id
		$room_id_array = $this->wpdb->get_results( "SELECT id FROM {$this->wpdb->prefix}sr_rooms WHERE room_type_id = $id" );
		foreach ( $room_id_array as $room_id ) {
			add_filter( 'query', 'solidres_wp_db_null_value' );
			$this->wpdb->update( $this->wpdb->prefix.'sr_reservation_room_xref', array( 'room_id' => 'NULL' ), array( 'room_id' => $room_id->id ) );
			$this->wpdb->update( $this->wpdb->prefix.'sr_reservation_room_extra_xref', array( 'room_id' => 'NULL' ), array( 'room_id' => $room_id->id ) );
			remove_filter( 'query', 'solidres_wp_db_null_value' );
		}
		$this->wpdb->delete( $this->wpdb->prefix.'sr_rooms', array( 'room_type_id' => $id ) );
		$this->wpdb->delete( $this->wpdb->prefix.'sr_room_type_coupon_xref', array( 'room_type_id' => $id ) );
		$this->wpdb->delete( $this->wpdb->prefix.'sr_room_type_extra_xref', array( 'room_type_id' => $id ) );
		$this->wpdb->delete( $this->wpdb->prefix.'sr_room_type_fields', array( 'room_type_id' => $id ) );
		$this->wpdb->delete( $this->wpdb->prefix.'sr_media_roomtype_xref', array( 'room_type_id' => $id ) );
		$this->wpdb->delete( $this->wpdb->prefix.'sr_tariff_details', array( 'tariff_id' => $tariff_id ) );
		$this->wpdb->delete( $this->wpdb->prefix.'sr_tariffs', array( 'id' => $tariff_id ) );
		$this->wpdb->delete( $this->wpdb->prefix.'sr_room_types', array( 'id' => $id ) );
	}

	/**
	 * Get list of Room is reserved and belong to a RoomType.
	 * @param int $roomTypeId
	 * @param int $reservationId
	 * @return array An array of room object
	 */
	public function getListReservedRoom( $roomTypeId, $reservationId ) {
		$results = $this->wpdb->get_results( "SELECT r1.id, r1.label, r2.adults_number, r2.children_number FROM {$this->wpdb->prefix}sr_rooms as r1 INNER JOIN {$this->wpdb->prefix}sr_reservation_room_xref as r2 ON r1.id = r2.room_id WHERE r1.room_type_id = $roomTypeId AND r2.reservation_id = $reservationId" );
		return $results;
	}

	/**
	 * Get list rooms belong to a RoomType
	 * @param int $roomtypeId
	 * @return array object
	 */
	public function getListRooms( $roomtypeId ) {
		$result = $this->wpdb->get_results( "SELECT id, label, room_type_id FROM {$this->wpdb->prefix}sr_rooms WHERE room_type_id = $roomtypeId" );
		if(empty($result)) {
			return false;
		}
		return $result;
	}

	/**
	 * Method to get a list of available rooms of a RoomType based on check in and check out date
	 * @param   int     $roomtypeId
	 * @param   int     $checkin
	 * @param   int     $checkout
	 * @return  mixed   An array of room object if successfully
	 *                  otherwise return false
	 */
	public function getListAvailableRoom( $roomtypeId = 0, $checkin, $checkout ) {
		$srReservation = new SR_Reservation();
		$availableRooms = array();
		$query_default = "SELECT id, label FROM {$this->wpdb->prefix}sr_rooms";
		$query_filter = array();

		if ( $roomtypeId > 0 ) {
			$query_filter[] = ' room_type_id = '.$roomtypeId;
		}
		if ( SR_PLUGIN_LIMITBOOKING_ENABLED ) {

			$checkinMySQLFormat = date( 'Y-m-d', strtotime( $checkin ) );
			$checkoutMySQLFormat = date( 'Y-m-d', strtotime( $checkout ) );

			$query_filter[] = ' id NOT IN (SELECT room_id FROM '.$this->wpdb->prefix.'sr_limit_booking_details
											WHERE limit_booking_id IN ( SELECT id FROM '.$this->wpdb->prefix.'sr_limit_bookings
											WHERE
											(
												(\''.$checkinMySQLFormat.'\' <= start_date AND \''.$checkoutMySQLFormat.'\' > start_date )
												OR
												(\''.$checkinMySQLFormat.'\' >= start_date AND \''.$checkoutMySQLFormat.'\' <= end_date )
												OR
												(\''.$checkinMySQLFormat.'\' < end_date AND \''.$checkoutMySQLFormat.'\' >= end_date )
											)
											AND state = 1
											) )';
		}
		$query_default = $query_default . ' WHERE '.implode( ' AND', $query_filter );
		$rooms = $this->wpdb->get_results( $query_default );

		if ( empty( $rooms ) ) {
			return false;
		}

		foreach ( $rooms as $room ) {
			// If this room is available, add it to the returned list
			if ( $srReservation->isRoomAvailable( $room->id, $checkin, $checkout ) ) {
				$availableRooms[] = $room;
			}
		}
		return $availableRooms;
	}

	/**
	 * Check a room to determine whether it can be deleted or not, if yes then delete it
	 * When delete a room, we will need to make sure that all related
	 * Reservation of that room must be removed first
	 * @param 	int 	    $roomId
	 * @return 	boolean     True if a room is safe to be deleted
	 *                      False otherwise
	 */
	public function canDeleteRoom( $roomId = 0 ) {
		$result = (int)$this->wpdb->get_var( "SELECT COUNT(*) FROM {$this->wpdb->prefix}sr_reservation_room_xref WHERE room_id = $roomId" );
		if ( $result > 0 ) {
			return false;
		}
		$result = $this->wpdb->delete( $this->wpdb->prefix.'sr_rooms', array( 'id' => $roomId ) );
		if ( ! $result ) {
			return false;
		}
		return true;
	}

	/**
	 * @param  int $roomtypeId
	 * @param  int $couponId
	 * @return bool|mixed
	 */
	public function storeCoupon( $roomtypeId = 0, $couponId = 0 ) {
		if( $roomtypeId <= 0 && $couponId <= 0 ) {
			return false;
		}
		return $this->wpdb->insert( $this->wpdb->prefix.'sr_room_type_coupon_xref', array( 'room_type_id' => (int)$roomtypeId, 'coupon_id' => (int)$couponId ) );
	}


	/**
	 * @param  int $roomtypeId
	 * @param  int $extraId
	 * @return bool|mixed
	 */
	public function storeExtra($roomtypeId = 0, $extraId = 0) {
		global $wpdb;
		if( $roomtypeId <= 0 && $extraId <= 0 ) {
			return false;
		}
		return $wpdb->insert( $wpdb->prefix.'sr_room_type_extra_xref', array( 'room_type_id' => (int)$roomtypeId, 'extra_id' => (int)$extraId ) );
	}

	/**
	 * Method to store Room information
	 * TODO move this function to corresponding model/table
	 * @param   int     $roomTypeId
	 * @param   string  $roomLabel
	 * @return  boolean
	 */
	public function storeRoom( $roomTypeId = 0, $roomLabel = '' ){
		global $wpdb;
		return $wpdb->insert( $wpdb->prefix.'sr_rooms', array( 'room_type_id' => $roomTypeId, 'label' => $roomLabel ) );
	}

	/**
	 * Find room type by room id
	 * TODO move this function to corresponding model/table
	 * @param  int $roomId
	 * @return mixed
	 */
	public function findByRoomId( $roomId ){
		return $this->wpdb->get_results( "SELECT * FROM {$this->wpdb->prefix}sr_room_types WHERE id IN ( SELECT room_type_id FROM {$this->wpdb->prefix}sr_rooms WHERE id = $roomId )" );

	}

	/**
	 * Get list coupon id belong to $roomtypeId
	 * @param   int $roomtypeId
	 * @return  array
	 */
	public function getCoupon( $roomtypeId ) {
		return $this->wpdb->get_results( "SELECT coupon_id FROM {$this->wpdb->prefix}sr_room_type_coupon_xref WHERE room_type_id = $roomtypeId" );
	}

	/**
	 * Get list extra id belong to $roomtypeId
	 * @param   int $roomtypeId
	 * @return  array
	 */
	public function getExtra( $roomtypeId ) {
		return $this->wpdb->get_results( "SELECT extra_id FROM {$this->wpdb->prefix}sr_room_type_extra_xref WHERE room_type_id = $roomtypeId" );
	}

	/**
	 * Get price of a room type from a list of room type's tariff that matches the conditions:
	 *        Customer group
	 *        Checkin && Checkout date
	 *        Adult number
	 *        Child number & ages
	 *        Min & Max number of nights
	 *
*@param   int $room_type_id
	 * @param   $customer_group_id
	 * @param   $imposed_tax_types
	 * @param   bool $default_tariff
	 * @param   bool $date_constraint @deprecated
	 * @param   string $checkin
	 * @param   string $checkout
	 * @param   SR_Currency $solidres_currency The currency object
	 * @param   array $coupon An array of coupon information
	 * @param   int $adult_number Number of adult, default is 0
	 * @param   int $child_number Number of child, default is 0
	 * @param   array $child_ages An array of children age, it is associated with the $childNumber
	 * @param   int $number_of_nights 0 means ignore this condition
	 * @param   int $tariff_id Search for specific tariff id
	 *
*@return  array    An array of SR_Currency for Tax and Without Tax
	 */
	public function getPrice( $room_type_id, $customer_group_id, $imposed_tax_types, $default_tariff = false, $date_constraint = false, $checkin = '', $checkout = '', SR_Currency $solidres_currency, $coupon = NULL, $adult_number = 0, $child_number = 0, $child_ages = array(), $number_of_nights = 0, $tariff_id = NULL ) {

		$solidres_tariff   = new SR_Tariff();
		$tariff_with_details = null;

		// This is package type, do not need to calculate per day
		if ( isset ( $tariff_id ) ) {
			$tariff_with_details = $solidres_tariff->load( $tariff_id );
		}

		if ( isset ( $tariff_with_details ) && ( $tariff_with_details->type == 2 || $tariff_with_details->type == 3 ) ) {
			$response = $this->getPricePackage( $tariff_with_details, $checkin, $checkout, $imposed_tax_types, $solidres_currency, $coupon, $adult_number, $child_number, $child_ages );
		} else // This is normal tariffs, need to calculate per day
		{
			$response = $this->getPriceDaily( $tariff_with_details, $room_type_id, $customer_group_id, $imposed_tax_types, $default_tariff, $date_constraint, $checkin, $checkout, $solidres_currency, $coupon, $adult_number, $child_number, $child_ages, $number_of_nights );
		}

		return $response;
	}

	/**
	 * Get price for Package tariff type: either Package per room or Package per person.
	 *
	 */
	public function getPricePackage( $tariffWithDetails, $checkin, $checkout, $imposedTaxTypes, $solidresCurrency, $coupon = NULL, $adultNumber, $childNumber, $childAges ) {
		$isAppliedCoupon = false;
		$tariffBreakDown = array();
		$totalBookingCost = 0;
		$totalBookingCostIncludedTaxFormatted = NULL;
		$totalBookingCostExcludedTaxedFormatted = NULL;
		$totalBookingCostTaxed = NULL;

		$checkinDay = new DateTime( $checkin );
		$checkoutDay = new DateTime( $checkout );
		$checkinDayInfo = getdate( $checkinDay->format( 'U' ) );
		$checkoutDay = getdate( $checkoutDay->format( 'U' ) );
		$nights = $this->calculateDateDiff( $checkin, $checkout );
		$isValid = false;

		// Check to see if the general checkin/out match this tariff's valid from and valid to
		// We also have to check if the checkin match the allowed checkin days.
		// We also have to check if the general nights number match this tariff's min nights and max nights
		if (
			strtotime( $tariffWithDetails->valid_from ) <= strtotime( $checkin ) &&
			strtotime( $tariffWithDetails->valid_to )  >= strtotime( $checkout ) &&
			in_array( $checkinDayInfo['wday'], $tariffWithDetails->limit_checkin ) &&
			( $nights >= $tariffWithDetails->d_min && $nights <= $tariffWithDetails->d_max )
		)
		{
			$isValid = true;
		}

		if ( $isValid ) {
			$cost = 0;
			if ( $tariffWithDetails->type == self::PACKAGE_PER_ROOM ) {
				$cost = $tariffWithDetails->details['per_room'][0]->price;
			}
			else if ( $tariffWithDetails->type == self::PACKAGE_PER_PERSON ) {
				for ( $i = 1; $i <= $adultNumber; $i++ ) {
					$cost += $tariffWithDetails->details['adult'.$i][0]->price;
				}

				for ( $i = 0; $i < count( $childAges ); $i++ ) {
					foreach ( $tariffWithDetails->details as $guestType => $guesTypeTariff ) {
						if ( substr( $guestType, 0, 5 ) == 'adult' ) {
							continue; // skip all adult's tariff
						}

						if
						(
							$childAges[$i] >= $tariffWithDetails->details[$guestType][0]->from_age
							&&
							$childAges[$i] <= $tariffWithDetails->details[$guestType][0]->to_age
						)
						{
							$cost += $tariffWithDetails->details[$guestType][0]->price;
						}
					}
				}
			}

			if ( isset( $coupon ) && is_array( $coupon ) ) {
				if ( $coupon['coupon_is_percent'] == 1 ) {
					$deductionAmount = $cost * ( $coupon['coupon_amount'] / 100 );
				} else {
					$deductionAmount = $coupon['coupon_amount'];
				}
				$cost -= $deductionAmount;
				$isAppliedCoupon = true;
			}

			// Calculate the imposed tax amount per day
			$totalImposedTaxAmountPerDay = 0;
			foreach ( $imposedTaxTypes as $taxType )
			{
				$totalImposedTaxAmountPerDay += $cost * $taxType->rate;
			}

			$totalBookingCost = $cost;
			$tariffBreakDown[8]['gross'] = $cost;
			$tariffBreakDown[8]['tax'] = $totalImposedTaxAmountPerDay;
			$tariffBreakDown[8]['net'] = $cost + $totalImposedTaxAmountPerDay;

			$result = array(
				'total_booking_cost' => $totalBookingCost,
				'tariff_break_down' => $tariffBreakDown,
				'is_applied_coupon' => $isAppliedCoupon
			);

			$totalBookingCost = $result['total_booking_cost'];
			$tempKeyWeekDay = key( $result['tariff_break_down'] );
			$tempSolidresCurrencyCostPerDayGross = clone $solidresCurrency;
			$tempSolidresCurrencyCostPerDayTax = clone $solidresCurrency;
			$tempSolidresCurrencyCostPerDayNet = clone $solidresCurrency;
			$tempSolidresCurrencyCostPerDayGross->setValue( $result['tariff_break_down'][$tempKeyWeekDay]['gross'] );
			$tempSolidresCurrencyCostPerDayTax->setValue( $result['tariff_break_down'][$tempKeyWeekDay]['tax'] );
			$tempSolidresCurrencyCostPerDayNet->setValue( $result['tariff_break_down'][$tempKeyWeekDay]['net'] );
			$tariffBreakDown[][$tempKeyWeekDay] = array(
				'gross' => $tempSolidresCurrencyCostPerDayGross,
				'tax' => $tempSolidresCurrencyCostPerDayTax,
				'net' => $tempSolidresCurrencyCostPerDayNet
			);

			unset( $tempSolidresCurrencyCostPerDayGross );
			unset( $tempSolidresCurrencyCostPerDayTax );
			unset( $tempSolidresCurrencyCostPerDayNet );
			unset( $tempKeyWeekDay );

			if ( $totalBookingCost > 0 ) {
				// Calculate the imposed tax amount
				$totalImposedTaxAmount = 0;
				foreach ( $imposedTaxTypes as $taxType ) {
					$totalImposedTaxAmount += $totalBookingCost * $taxType->rate;
				}

				$totalBookingCostTaxed = $totalBookingCost + $totalImposedTaxAmount;

				// Format the number with correct currency
				$totalBookingCostExcludedTaxedFormatted = clone $solidresCurrency;
				$totalBookingCostExcludedTaxedFormatted->setValue( $totalBookingCost );

				// Format the number with correct currency
				$totalBookingCostIncludedTaxFormatted = clone $solidresCurrency;
				$totalBookingCostIncludedTaxFormatted->setValue( $totalBookingCostTaxed );
			}
		}

		$response = array(
			'total_price_formatted' => $totalBookingCostIncludedTaxFormatted,
			'total_price_tax_incl_formatted' => $totalBookingCostIncludedTaxFormatted,
			'total_price_tax_excl_formatted' => $totalBookingCostExcludedTaxedFormatted,
			'total_price' => $totalBookingCostTaxed,
			'total_price_tax_incl' => $totalBookingCostTaxed,
			'total_price_tax_excl' => $totalBookingCost,
			'tariff_break_down' => $tariffBreakDown,
			'is_applied_coupon' => isset( $result['is_applied_coupon'] ) ? $result['is_applied_coupon'] : NULL,
			'type' => isset( $tariffWithDetails->type ) ? $tariffWithDetails->type : NULL,
			'id' => isset( $tariffWithDetails->id ) ? $tariffWithDetails->id : NULL,
			'title' => isset( $tariffWithDetails->title ) ? $tariffWithDetails->title : NULL,
			'description' => isset( $tariffWithDetails->description ) ? $tariffWithDetails->description : NULL,
		);
		return $response;
	}

	/**
	 * Get price for Rate tariff type: either Rate per room per night or Rate per person per night
	 */
	public function getPriceDaily( $tariff_with_details, $room_type_id, $customer_group_id, $imposed_tax_types, $default_tariff = false, $date_constraint = false, $checkin = '', $checkout = '', SR_Currency $solidres_currency, $coupon = NULL, $adult_number = 0, $child_number = 0, $child_ages = array(), $number_of_nights = 0 ) {
		$solidres_coupon = new SR_Coupon();
		$total_booking_cost = 0;
		$book_week_days = $this->calculateWeekDay( $checkin, $checkout );
		$is_coupon_applicable = false;
		if ( isset ( $coupon ) && is_array( $coupon ) ) {
			$is_coupon_applicable = $solidres_coupon->isApplicable( $coupon['coupon_id'], $room_type_id );
		}

		$night_count = 1;
		$tariff_break_down = array();
		$tmp_key_week_day = NULL;

		if ( isset( $tariff_with_details ) ) {
			foreach ( $book_week_days as $book_week_day ) {
				$the_day = new DateTime( $book_week_day );
				$dayInfo = getdate( $the_day->format( 'U' ) );
				// We calculate per nights, not per day, for example 2011-08-24 to 2012-08-29 is 6 days but only 5 nights
				if ( $night_count < count( $book_week_days ) ) {
					$result = array(
						'total_booking_cost' => 0,
						'tariff_break_down' => array(),
						'is_applied_coupon' => false
					);

					// Deal with Coupon
					if ( $is_coupon_applicable ) {
						$result = $this->calculateCostPerDay( $tariff_with_details, $dayInfo, $coupon, $adult_number, $child_number, $child_ages, $imposed_tax_types );
					} else {
						$result = $this->calculateCostPerDay( $tariff_with_details, $dayInfo, NULL, $adult_number, $child_number, $child_ages, $imposed_tax_types );
					}

					$total_booking_cost += $result['total_booking_cost'];
					$temp_key_week_day = key( $result['tariff_break_down'] );
					$temp_solidres_currency_cost_per_day_gross = clone $solidres_currency;
					$temp_solidres_currency_cost_per_day_tax = clone $solidres_currency;
					$temp_solidres_currency_cost_per_day_net = clone $solidres_currency;
					$temp_solidres_currency_cost_per_day_gross->setValue( $result['tariff_break_down'][$temp_key_week_day]['gross'] );
					$temp_solidres_currency_cost_per_day_tax->setValue( $result['tariff_break_down'][$temp_key_week_day]['tax'] );
					$temp_solidres_currency_cost_per_day_net->setValue( $result['tariff_break_down'][$temp_key_week_day]['net'] );
					$tariff_break_down[][$temp_key_week_day] = array(
						'gross' => $temp_solidres_currency_cost_per_day_gross,
						'tax' => $temp_solidres_currency_cost_per_day_tax,
						'net' => $temp_solidres_currency_cost_per_day_net
					);
				}
				$night_count ++;
			}
		}

		unset( $temp_solidres_currency_cost_per_day_gross );
		unset( $temp_solidres_currency_cost_per_day_tax );
		unset( $temp_solidres_currency_cost_per_day_net );
		unset( $temp_key_week_day );
		$total_booking_cost_included_tax_formatted = NULL;
		$total_booking_cost_excluded_taxed_formatted = NULL;
		$total_booking_cost_taxed = NULL;

		if ( $total_booking_cost > 0) {
			// Calculate the imposed tax amount
			$total_imposed_tax_amount = 0;
			foreach ( $imposed_tax_types as $taxType ) {
				$total_imposed_tax_amount += $total_booking_cost * $taxType->rate;
			}
			$total_booking_cost_taxed = $total_booking_cost + $total_imposed_tax_amount;
			// Format the number with correct currency
			$total_booking_cost_excluded_taxed_formatted = clone $solidres_currency;
			$total_booking_cost_excluded_taxed_formatted->setValue($total_booking_cost);
			// Format the number with correct currency
			$total_booking_cost_included_tax_formatted = clone $solidres_currency;
			$total_booking_cost_included_tax_formatted->setValue( $total_booking_cost_taxed );
		}
		$response = array(
			'total_price_formatted' => $total_booking_cost_included_tax_formatted,
			'total_price_tax_incl_formatted' => $total_booking_cost_included_tax_formatted,
			'total_price_tax_excl_formatted' => $total_booking_cost_excluded_taxed_formatted,
			'total_price' => $total_booking_cost_taxed,
			'total_price_tax_incl' => $total_booking_cost_taxed,
			'total_price_tax_excl' => $total_booking_cost,
			'tariff_break_down' => $tariff_break_down,
			'is_applied_coupon' => isset ( $result['is_applied_coupon'] ) ? $result['is_applied_coupon'] : false,
			'type' => isset ( $tariff_with_details->type ) ? $tariff_with_details->type : NULL,
			'id' => isset ( $tariff_with_details->id ) ? $tariff_with_details->id : NULL,
			'title' => isset ( $tariff_with_details->title ) ? $tariff_with_details->title : NULL,
			'description' => isset ( $tariff_with_details->description ) ? $tariff_with_details->description : NULL,
		);

		return $response;
	}

	/**
	 * Get price of a room type from a list of room type's tariff that matches the conditions:
	 *        Customer group
	 *        Checkin && Checkout date
	 *        Adult number
	 *        Child number & ages
	 *        Min & Max number of nights
	 *
	 * @param   int $roomTypeId
	 * @param   $customerGroupId
	 * @param   $imposedTaxTypes
	 * @param   bool $defaultTariff
	 * @param   bool $dateConstraint @deprecated
	 * @param   string $checkin
	 * @param   string $checkout
	 * @param   SR_Currency $solidresCurrency The currency object
	 * @param   array $coupon An array of coupon information
	 * @param   int $adultNumber Number of adult, default is 0
	 * @param   int $childNumber Number of child, default is 0
	 * @param   array $childAges An array of children age, it is associated with the $childNumber
	 * @param   int $numberOfNights 0 means ignore this condition
	 *
	 * @return  array    An array of SR_Currency for Tax and Without Tax
	 */
	public function getPriceLegacy($roomTypeId, $customerGroupId, $imposedTaxTypes, $defaultTariff = false, $dateConstraint = false, $checkin = '', $checkout = '', SR_Currency $solidresCurrency, $coupon = NULL, $adultNumber = 0, $childNumber = 0, $childAges = array(), $numberOfNights = 0 )
	{
		$modelTariffs = JModelLegacy::getInstance('Tariffs', 'SolidresModel', array('ignore_request' => true));
		$modelTariff = JModelLegacy::getInstance('Tariff', 'SolidresModel', array('ignore_request' => true));
		$srCoupon = SRFactory::get('solidres.coupon.coupon');

		$totalBookingCost = 0;

		$modelTariffs->setState('filter.room_type_id', $roomTypeId);
		$modelTariffs->setState('filter.customer_group_id', $customerGroupId);

		if ($defaultTariff)
		{
			$modelTariffs->setState('filter.default_tariff', 1);
			// If we need to get the default price, set customer group to -1, means we do not care about customer group
			$modelTariffs->setState('filter.customer_group_id', -1);
		}

		$bookWeekDays = $this->calculateWeekDay($checkin, $checkout);

		if ($dateConstraint)
		{
			$modelTariffs->setState('filter.date_constraint', 1);
		}

		$isCouponApplicable = false;
		if (isset($coupon) && is_array($coupon))
		{
			$isCouponApplicable = $srCoupon->isApplicable($coupon['coupon_id'], $roomTypeId);
		}

		$nightCount = 1;
		$tariffBreakDown = array();
		$tempTariffId = 0;
		$tmpKeyWeekDay = NULL;
		foreach ($bookWeekDays as $bookWeekDay)
		{
			$theDay = new DateTime($bookWeekDay);
			$dayInfo = getdate($theDay->format('U'));
			// We calculate per nights, not per day, for example 2011-08-24 to 2012-08-29 is 6 days but only 5 nights
			if ($nightCount < count($bookWeekDays))
			{
				// Find Complex Tariff
				if ($dateConstraint)
				{
					// Reset these state because we may override it in other steps
					$modelTariffs->setState('filter.date_constraint', 1);
					$modelTariffs->setState('filter.default_tariff', NULL);
					$modelTariffs->setState('filter.customer_group_id', $customerGroupId);
					$modelTariffs->setState('filter.bookday',  JFactory::getDate($bookWeekDay)->toSql());
					if ($numberOfNights > 0)
					{
						$modelTariffs->setState('filter.number_of_nights', $numberOfNights);
					}
					$tariff = $modelTariffs->getItems();
				}
				else // Or find Standard Tariff
				{
					$modelTariffs->setState('filter.date_constraint', NULL);
					$modelTariffs->setState('filter.default_tariff', 1);
					$modelTariffs->setState('filter.customer_group_id', -1);
					$tariff = $modelTariffs->getItems();
				}

				$result = array(
					'total_booking_cost' => 0,
					'tariff_break_down' => array(),
					'is_applied_coupon' => false
				);
				if (!empty($tariff))
				{
					// Then we load the tariff details: price for each week day
					// Caching stuff
					if ($tempTariffId != $tariff[0]->id)
					{
						$tariffWithDetails = $modelTariff->getItem($tariff[0]->id);
						$tempTariffId = $tariff[0]->id;
					}

					// Deal with Coupon
					if ($isCouponApplicable)
					{
						$result = $this->calculateCostPerDay($tariffWithDetails, $dayInfo, $coupon, $adultNumber, $childNumber, $childAges, $imposedTaxTypes);
					}
					else
					{
						$result = $this->calculateCostPerDay($tariffWithDetails, $dayInfo, NULL, $adultNumber, $childNumber, $childAges, $imposedTaxTypes);
					}

					$totalBookingCost += $result['total_booking_cost'];
					$tempKeyWeekDay = key($result['tariff_break_down']);
					$tempSolidresCurrencyCostPerDayGross = clone $solidresCurrency;
					$tempSolidresCurrencyCostPerDayTax = clone $solidresCurrency;
					$tempSolidresCurrencyCostPerDayNet = clone $solidresCurrency;
					$tempSolidresCurrencyCostPerDayGross->setValue($result['tariff_break_down'][$tempKeyWeekDay]['gross']);
					$tempSolidresCurrencyCostPerDayTax->setValue($result['tariff_break_down'][$tempKeyWeekDay]['tax']);
					$tempSolidresCurrencyCostPerDayNet->setValue($result['tariff_break_down'][$tempKeyWeekDay]['net']);
					$tariffBreakDown[][$tempKeyWeekDay] = array(
						'gross' => $tempSolidresCurrencyCostPerDayGross,
						'tax' => $tempSolidresCurrencyCostPerDayTax,
						'net' => $tempSolidresCurrencyCostPerDayNet
					);
				}
			}
			$nightCount ++;
		}

		unset($tempSolidresCurrencyCostPerDayGross);
		unset($tempSolidresCurrencyCostPerDayTax);
		unset($tempSolidresCurrencyCostPerDayNet);
		unset($tempKeyWeekDay);

		$totalBookingCostIncludedTaxFormatted = NULL;
		$totalBookingCostExcludedTaxedFormatted = NULL;
		$totalBookingCostTaxed = NULL;

		if ($totalBookingCost > 0)
		{
			// Calculate the imposed tax amount
			$totalImposedTaxAmount = 0;
			foreach ($imposedTaxTypes as $taxType)
			{
				$totalImposedTaxAmount += $totalBookingCost * $taxType->rate;
			}

			$totalBookingCostTaxed = $totalBookingCost + $totalImposedTaxAmount;

			// Format the number with correct currency
			$totalBookingCostExcludedTaxedFormatted = clone $solidresCurrency;
			$totalBookingCostExcludedTaxedFormatted->setValue($totalBookingCost);

			// Format the number with correct currency
			$totalBookingCostIncludedTaxFormatted = clone $solidresCurrency;
			$totalBookingCostIncludedTaxFormatted->setValue($totalBookingCostTaxed);
		}

		$response = array(
			'total_price_formatted' => $totalBookingCostIncludedTaxFormatted,
			'total_price_tax_incl_formatted' => $totalBookingCostIncludedTaxFormatted,
			'total_price_tax_excl_formatted' => $totalBookingCostExcludedTaxedFormatted,
			'total_price' => $totalBookingCostTaxed,
			'total_price_tax_incl' => $totalBookingCostTaxed,
			'total_price_tax_excl' => $totalBookingCost,
			'tariff_break_down' => $tariffBreakDown,
			'is_applied_coupon' => $result['is_applied_coupon'],
			'type' => isset($tariff[0]->type) ? $tariff[0]->type : NULL,
			'id' => 0, // special id for joined tariffs case
			'title' => NULL,
			'description' => NULL
		);

		return $response;
	}

	/**
	 * Get an array of week days in the period between $from and $to
	 * @param    string   From date
	 * @param    string   To date
	 * @return   array	  An array in format array(0 => 'Y-m-d', 1 => 'Y-m-d')
	 */
	private function calculateWeekDay( $from, $to ) {
		$datetime1 	= new DateTime( $from );
		$interval 	= $this->calculateDateDiff( $from, $to );
		$weekDays 	= array();
		$weekDays[] = $datetime1->format( 'Y-m-d' );
		for ( $i = 1; $i <= (int)$interval; $i++ ) {
			$weekDays[] = $datetime1->modify( '+1 day' )->format( 'Y-m-d' );
		}
		return $weekDays;
	}

	/**
	 * Calculate the number of day from a given range
	 * Note: DateTime is PHP 5.3 only
	 * @param  string  $from   Begin of date range
	 * @param  string  $to     End of date range
	 * @param  string  $format The format indicator
	 * @return string
	 */
	public function calculateDateDiff( $from, $to, $format = '%a' ) {
		$datetime1 = new DateTime( $from );
		$datetime2 = new DateTime( $to );
		$interval = $datetime1->diff( $datetime2 );
		return $interval->format( $format );
	}

	/**
	 * Calculate booking cost per day and apply the coupon if possible
	 * @param   array   $tariff   	An array of tariffs for searching
	 * @param   array   $dayInfo 	The date that we need to find tariff for it from above $tariff
	 * @param   array   $coupon 	An array of coupon information
	 * @param   int     $adultNumber Number of adult, only used for tariff Per person per room
	 * @param   int     $childNumber Number of child, only used for tariff Per person per room
	 * @param   array   $childAges   Children ages, it is associated with $childNumber
	 * @param   arrray  $imposedTaxTypes All imposed tax types
	 * @return  array
	 */
	private function calculateCostPerDay( $tariff, $dayInfo, $coupon = NULL, $adultNumber, $childNumber, $childAges, $imposedTaxTypes ) {
		$totalBookingCost = 0;
		$tariffBreakDown = array();
		$costPerDay = 0;
		$isAppliedCoupon = false;
		if ( $tariff->type == self::PER_ROOM_PER_NIGHT ) {
			for ( $i = 0, $count = count( $tariff->details['per_room'] ); $i < $count; $i ++ ) {
				if ( $tariff->details['per_room'][$i]->w_day == $dayInfo['wday']) {
					$costPerDay = $tariff->details['per_room'][$i]->price;
					break; // we found the tariff we need, get out of here
				}
			}
		}
		else if ($tariff->type == self::PER_PERSON_PER_NIGHT) {
			// Calculate cost per day for each adult
			for ($i = 1; $i <= $adultNumber; $i++) {
				$adultIndex = 'adult'.$i;
				for ($t = 0, $count = count($tariff->details[$adultIndex]); $t < $count; $t ++) {
					if ($tariff->details[$adultIndex][$t]->w_day == $dayInfo['wday']) {
						$costPerDay += $tariff->details[$adultIndex][$t]->price;
						break; // we found the tariff we need, get out of here
					}
				}
			}

			// Calculate cost per day for each child, take their ages into consideration
			for ( $i = 0; $i < count( $childAges ); $i++ ) {
				foreach ( $tariff->details as $guestType => $guesTypeTariff ) {
					if ( substr( $guestType, 0, 5 ) == 'adult' ) {
						continue; // skip all adult's tariff
					}

					for ( $t = 0, $count = count( $tariff->details[$guestType] ); $t < $count; $t ++) {
						if (
							$tariff->details[$guestType][$t]->w_day == $dayInfo['wday']
							&&
							( $childAges[$i] >= $tariff->details[$guestType][$t]->from_age && $childAges[$i] <= $tariff->details[$guestType][$t]->to_age )
						)
						{
							$costPerDay += $tariff->details[$guestType][$t]->price;
							break; // found it, get out of here
						}
					}
				}
			}
		}

		if ( isset ( $coupon ) && is_array( $coupon ) ) {
			if ( $coupon['coupon_is_percent'] == 1) {
				$deductionAmount = $costPerDay * ( $coupon['coupon_amount'] / 100 );
			} else {
				$deductionAmount = $coupon['coupon_amount'];
			}
			$costPerDay -= $deductionAmount;
			$isAppliedCoupon = true;
		}

		// Calculate the imposed tax amount per day
		$totalImposedTaxAmountPerDay = 0;
		foreach ( $imposedTaxTypes as $taxType ) {
			$totalImposedTaxAmountPerDay += $costPerDay * $taxType->rate;
		}
		$totalBookingCost += $costPerDay;
		$tariffBreakDown[$dayInfo['wday']]['gross'] = $costPerDay;
		$tariffBreakDown[$dayInfo['wday']]['tax'] = $totalImposedTaxAmountPerDay;
		$tariffBreakDown[$dayInfo['wday']]['net'] = $costPerDay + $totalImposedTaxAmountPerDay;
		return array(
			'total_booking_cost' => $totalBookingCost,
			'tariff_break_down' => $tariffBreakDown,
			'is_applied_coupon' => $isAppliedCoupon
		);
	}

	/**
	 * Get a room type by id
	 *
	 * @param $id
	 * @param $output
	 *
	 * @return mixed
	 */
	public function load( $id, $output = OBJECT ) {
		$item = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$this->wpdb->prefix}sr_room_types WHERE id = %d", $id ), $output );
		$assets = new SR_Asset();
		$tableRA = $assets->load( $item->reservation_asset_id );
		$currencies = new SR_Currency();

		if ( $item->id ) {
			$media = new SR_Media();
			$item->default_tariff = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT p.*, c.currency_code, c.currency_name FROM {$this->wpdb->prefix}sr_tariffs as p LEFT JOIN {$this->wpdb->prefix}sr_currencies as c ON c.id = p.currency_id WHERE room_type_id = %d AND valid_from = '0000-00-00' AND valid_to = '0000-00-00'", empty( $item->id ) ? 0 : (int) $item->id ) );

			if ( isset( $item->default_tariff ) ) {
				$item->default_tariff->details = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT id, tariff_id, price, w_day, guest_type, from_age, to_age FROM {$this->wpdb->prefix}sr_tariff_details WHERE tariff_id = %d ORDER BY w_day ASC", (int) $item->default_tariff->id ) );
			}

			$item->roomList = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT a.id, a.label FROM {$this->wpdb->prefix}sr_rooms a WHERE room_type_id = %d", empty( $item->id ) ? 0 : (int)$item->id  ) );
			// Load media
			$item->media = $media->load_by_room_type_id( $item->id );
		}

		// Load currency
		$currency = $currencies->load( $tableRA->currency_id );
		$item->currency = $currency;
		return $item;
	}

	/**
	 * Get a single room type by alias (slug)
	 *
	 * @param $alias
	 *
	 * @return mixed
	 */
	public function load_by_alias( $alias ) {
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$this->wpdb->prefix}sr_room_types WHERE alias = %s", $alias ) );
	}

	/**
	 * Get a list of room type by asset's id
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function load_by_asset_id ( $id ) {
		return $this->wpdb->get_results( $this->wpdb->prepare( "SELECT * FROM {$this->wpdb->prefix}sr_room_types
																WHERE reservation_asset_id = %d AND state = 1", $id ) );
	}

	/**
	 * Get a list of room type by asset's alias (slug)
	 *
	 * @param $alias
	 *
	 * @return mixed
	 */
	public function load_by_asset_alias ( $alias ) {
		return $this->wpdb->get_results( $this->wpdb->prepare( "SELECT * FROM {$this->wpdb->prefix}sr_room_types WHERE alias = %s", $alias ) );
	}

	/**
	 * Load all room type's custom fields
	 *
	 * @param $id
	 *
	 * @return string
	 *
	 */
	public static function load_custom_fields( $id = 0 ){
		//Get custom field data from sr_reservation_asset_fields table
		$custom_field_data = new SR_Custom_Field( array( 'id' => (int) $id, 'type' => 'room_type' ) );
		$load_data_custom_fields = $custom_field_data->create_array_group();
		$custom_field_view = '';
		$list_tab = '';
		$list_table_content = '';
		foreach ( $load_data_custom_fields as $group_key => $group_fields ) {
			$list_tab .= '<li class="'.$group_key.'">';
			$list_tab .= '<a href="#'.$group_key.'">'.solidres_convertslugtostring( $group_key ).'</a>';
			$list_tab .= '<a href="#" id="del_custom_field_group" title="Delete custom field group"><img src="'.solidres()->plugin_url().'/assets/images/close_btn.png" alt="Delete custom field group"/></a>';
			$list_tab .= '</li>';
			$list_table_content .= '<div id="'.$group_key.'" class="group_parent">';
			$list_table_content .= '<table class="form-table">';
			$list_table_content .= '<tbody>';
			$list_table_content .= '<tr class="add_new_field">';
			$list_table_content .= '<td class="first"><input type="text" name="" size="20" value="" class="new_custom_field_key" placeholder="Enter field name"></td>';
			$list_table_content .= '<td><textarea class="srform_textarea new_custom_field_value" rows="5" name="" placeholder="Enter field value"></textarea></td>';
			$list_table_content .= '<td><input type="button" id="add_new_field" value="Add New Field" /></td>';
			$list_table_content .= '</tr>';
			foreach ( $group_fields as $field ) {
				$list_table_content .= '<tr class="field_'.$custom_field_data->split_field_name( $field[0] ).'">';
				$list_table_content .= '<td class="first">'.ucfirst( $custom_field_data->split_field_name( solidres_convertslugtostring( $field[0] ) ) ).'</td>';
				$list_table_content .= '<td><textarea class="srform_textarea" rows="5" name="srform[customfields]['.$group_key.']['.$custom_field_data->split_field_name( $field[0] ).']" id="srform_customfields_'.$group_key.'_'.$custom_field_data->split_field_name( $field[0] ).'">'.$field[1].'</textarea></td>';
				$list_table_content .= '<td><a href="#" id="del_custom_field_element" title="Delete field"><img src="'.solidres()->plugin_url().'/assets/images/close_btn.png" alt="Delete field"/></a>';
				$list_table_content .= '</tr>';
			}
			$list_table_content .= '</tbody>';
			$list_table_content .= '</table>';
			$list_table_content .= '</div>';
		}
		$custom_field_view .= '<ul>';
		$custom_field_view .= $list_tab;
		$custom_field_view .= '</ul>';
		$custom_field_view .= $list_table_content;
		return $custom_field_view;
	}

	/**
	 * Get the min price from a given tariff and show the formatted result
	 *
	 * @param $tariff
	 * @param $solidres_currency
	 * @param $show_tax_incl
	 * @param $imposed_tax_types
	 *
	 * @return string
	 */
	public function get_min_price( $tariff, $solidres_currency, $show_tax_incl, $imposed_tax_types ) {
		$tariff_suffix = '';
		$min = NULL;
		$number_of_nights = 0;
		if ( $tariff->type == 0 || $tariff->type == 2 ) :
			$tariff_suffix .= __( '/ room ', 'solidres');
		else :
			$tariff_suffix .= __('/ person ', 'solidres' );
		endif;

		switch ( $tariff->type ) {
			case 0: // rate per room per night
				$min = array_reduce( $tariff->details['per_room'], function( $t1, $t2 ) {
					return $t1->price < $t2->price ? $t1 : $t2;
				}, array_shift( $tariff->details['per_room'] ) );
				$number_of_nights = 1;
				break;
			case 1: // rate per person per night
				$min = array_reduce( $tariff->details['adult1'], function( $t1, $t2 ) {
					return $t1->price < $t2->price ? $t1 : $t2;
				}, array_shift( $tariff->details['adult1'] ) );
				$number_of_nights = 1;
				break;
			case 2: // package per room
				$min = $tariff->details['per_room'][0];
				$number_of_nights = $tariff->d_min;
				break;
			case 3: // package per person
				$min = $tariff->details['adult1'][0];
				$number_of_nights = $tariff->d_min;
				break;
			default:
				break;

		}

		// Calculate tax amount
		$total_imposed_tax_amount = 0;
		if ( $show_tax_incl ) {
			if ( count( $imposed_tax_types ) > 0 ) {
				foreach ( $imposed_tax_types as $taxType ) {
					$total_imposed_tax_amount += $min->price * $taxType->rate;
				}
			}
		}
		$min_currency = clone $solidres_currency;
		$min_currency->setValue($min->price + $total_imposed_tax_amount);
		$tariff_suffix .= sprintf( _n( '/ %s night', '/ %s nights', $number_of_nights, 'solidres' ), $number_of_nights );

		return '<span class="starting_from">'.__( 'Starting from', 'solidres' ).'</span><span class="min_tariff">' . $min_currency->format() . '</span><span class="tariff_suffix">' . $tariff_suffix . '</span>';
	}

	/**
	 * Load the room type params in JSON format
	 *
	 * @param $params The params string
	 *
	 * @return array|mixed
	 *
	 */
	public function load_params( $params ) {
		return json_decode( (string) $params, true);
	}

	/**
	 * Get room type information to be display in the reservation confirmation screen
	 *
	 * This is intended to be used in the front end
	 *
	 * @return array $ret An array contain room type information
	 */
	public function get_room_type( $asset_id, $booked_room_types, $checkin, $checkout )
	{
		// Construct a simple array of room type ID and its price
		$roomTypePricesMapping = array();

		$solidres_room_type = new SR_Room_Type();
		$currencyId = solidres()->session[ 'sr_currency_id' ];
		$taxId = solidres()->session[ 'sr_tax_id' ];
		$solidresCurrency = new SR_Currency(0, $currencyId);
		$coupon = solidres()->session[ 'sr_coupon' ];

		// Get imposed taxes
		$imposedTaxTypes = array();
		if (!empty($taxId))
		{
			//$taxModel = JModelLegacy::getInstance('Tax', 'SolidresModel', array('ignore_request' => true));
			$taxModel = new SR_Tax();
			$imposedTaxTypes[] = $taxModel->load( $taxId );
		}

		// Get customer information
		//$user = JFactory::getUser();
		$customerGroupId = NULL;  // Non-registered/Public/Non-loggedin customer
		/*if (SR_PLUGIN_USER_ENABLED)
		{
			$customerTable = JTable::getInstance('Customer', 'SolidresTable');
			$customerTable->load(array('user_id' => $user->id));
			$customerGroupId = $customerTable->customer_group_id;
		}*/

		$numberOfNights = (int) $solidres_room_type->calculateDateDiff( $checkin, $checkout );

		$totalPriceTaxIncl = 0;
		$totalPriceTaxExcl = 0;
		$totalReservedRoom = 0;
		$ret = array();

		// Get a list of room type based on search conditions
		foreach ($booked_room_types as $roomTypeId => $bookedTariffs )
		{
			$bookedRoomTypeQuantity = count($booked_room_types[$roomTypeId]);

			foreach ($bookedTariffs as $tariffId => $roomTypeRoomDetails )
			{
				$r = $solidres_room_type->load( $roomTypeId );

				$ret[$roomTypeId]['name'] = $r->name;
				$ret[$roomTypeId]['description'] = $r->description;
				$ret[$roomTypeId]['occupancy_adult'] = $r->occupancy_adult;
				$ret[$roomTypeId]['occupancy_child'] = $r->occupancy_child;

				// Some data to query the correct tariff
				foreach ($roomTypeRoomDetails as $roomIndex => $roomDetails)
				{
					if (SR_PLUGIN_COMPLEXTARIFF_ENABLED)
					{
						$cost  = $solidres_room_type->getPrice(
							$roomTypeId,
							$customerGroupId,
							$imposedTaxTypes,
							false,
							true,
							$checkin,
							$checkout,
							$solidresCurrency,
							$coupon,
							$roomDetails['adults_number'],
							(isset($roomDetails['children_number']) ? $roomDetails['children_number'] : 0),
							(isset($roomDetails['children_ages']) ? $roomDetails['children_ages'] : array()),
							$numberOfNights,
							(isset($tariffId) && $tariffId > 0) ? $tariffId : NULL
						);
					}
					else
					{
						$cost = $solidres_room_type->getPrice(
							$roomTypeId,
							$customerGroupId,
							$imposedTaxTypes,
							true,
							false,
							$checkin,
							$checkout,
							$solidresCurrency,
							$coupon,
							0,
							0,
							array(),
							0,
							$tariffId
						);
					}

					$ret[$roomTypeId]['rooms'][$tariffId][$roomIndex]['currency'] 	= $cost;
					$totalPriceTaxIncl += $ret[$roomTypeId]['rooms'][$tariffId][$roomIndex]['currency']['total_price_tax_incl'];
					$totalPriceTaxExcl += $ret[$roomTypeId]['rooms'][$tariffId][$roomIndex]['currency']['total_price_tax_excl'];

					$roomTypePricesMapping[$roomTypeId][$tariffId][$roomIndex] = array(
						'total_price' => $ret[$roomTypeId]['rooms'][$tariffId][$roomIndex]['currency']['total_price'],
						'total_price_tax_incl' => $ret[$roomTypeId]['rooms'][$tariffId][$roomIndex]['currency']['total_price_tax_incl'],
						'total_price_tax_excl' => $ret[$roomTypeId]['rooms'][$tariffId][$roomIndex]['currency']['total_price_tax_excl']
					);
				}

				// Calculate number of available rooms
				$ret[$roomTypeId]['totalAvailableRoom'] = count( $solidres_room_type->getListAvailableRoom($roomTypeId, $checkin, $checkout) );
				$ret[$roomTypeId]['quantity'] = $bookedRoomTypeQuantity;

				// Only allow quantity within quota
				if ($bookedRoomTypeQuantity <= $ret[$roomTypeId]['totalAvailableRoom'])
				{
					$totalReservedRoom += $bookedRoomTypeQuantity;
				}
				else
				{
					return false;
				}
			} // end room type loop
		}

		solidres()->session[ 'sr_total_reserved_room' ] = $totalReservedRoom;
		solidres()->session[ 'sr_cost' ] = array(
				'total_price' => $totalPriceTaxIncl,
				'total_price_tax_incl' => $totalPriceTaxIncl,
				'total_price_tax_excl' => $totalPriceTaxExcl
		);

		solidres()->session[ 'sr_room_type_prices_mapping' ] = $roomTypePricesMapping;

		return $ret;
	}
}