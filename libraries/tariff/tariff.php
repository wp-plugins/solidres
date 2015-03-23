<?php
/*------------------------------------------------------------------------
  Solidres - Hotel booking plugin for WordPress
  ------------------------------------------------------------------------
  @Author    Solidres Team
  @Website   http://www.solidres.com
  @Copyright Copyright (C) 2013 - 2015 Solidres. All Rights Reserved.
  @License   GNU General Public License version 3, or later
------------------------------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * RoomType handler class
 * @package 	Solidres
 * @subpackage	RoomType
 * @since 		0.1.0
 */
class SR_Tariff {

	public $type_name_mapping = array();

	const PER_ROOM_PER_NIGHT = 0;

	const PER_PERSON_PER_NIGHT = 1;

	const PACKAGE_PER_ROOM = 2;

	const PACKAGE_PER_PERSON = 3;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->type_name_mapping = array(
			0 => __( 'Rate per room per night', 'solidres' ),
			1 => __( 'Rate per person per night', 'solidres' ),
			2 => __( 'Package per room', 'solidres' ),
			3 => __( 'Package per person', 'solidres' ),
		);
	}

	/**
	 * Delete a single tariff
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function delete( $id ) {
		return $this->wpdb->get_results( "DELETE FROM {$this->wpdb->prefix}sr_tariffs WHERE id = $id" );
	}

	/**
	 * Get a single tariff by id
	 *
	 * @param $id
	 * @param $standard
	 *
	 * @return mixed
	 */
	public function load( $id, $standard = true ) {

		if ( $standard ) {
			$tariff = $this->wpdb->get_row( "SELECT * FROM {$this->wpdb->prefix}sr_tariffs WHERE valid_from = '0000-00-00' AND valid_to = '0000-00-00' AND id = $id" );
		} else {
			$tariff = $this->wpdb->get_row( "SELECT * FROM {$this->wpdb->prefix}sr_tariffs WHERE valid_from != '0000-00-00' AND valid_to != '0000-00-00' AND id = $id" );
		}

		$tariff->type_name         = $this->type_name_mapping[ $tariff->type ];
		$tariff->valid_from        = $tariff->valid_from != '0000-00-00' ? date( 'd-m-Y', strtotime( $tariff->valid_from ) ) : '00-00-0000';
		$tariff->valid_to          = $tariff->valid_to != '0000-00-00' ? date( 'd-m-Y', strtotime( $tariff->valid_to ) ) : '00-00-0000';
		$tariff->customer_group_id = is_null( $tariff->customer_group_id ) ? '' : $tariff->customer_group_id;
		$tariff->limit_checkin     = isset( $tariff->limit_checkin ) ? json_decode( $tariff->limit_checkin ) : null;

		if ( (int) $tariff->type == self::PER_ROOM_PER_NIGHT ) {
			$results = $this->load_details( $tariff->id );

			if ( ! empty( $results ) ) {
				$tariff->details['per_room'] = $results;
			} else {
				$tariff->details['per_room'] = $this->get_tariff_details_scaffoldings( array(
					'tariff_id'  => $tariff->id,
					'guest_type' => null,
					'type'       => $tariff->type,
				) );
			}

			$tariff->details['per_room'] = SR_Utilities::translateDayWeekName( $tariff->details['per_room'] );
		} else if ( (int) $tariff->type == self::PER_PERSON_PER_NIGHT ) {
			// Query to get tariff details for each guest type
			// First we need to get the occupancy number
			$solidres_room_type = new SR_Room_Type();
			$room_type          = $solidres_room_type->load( $tariff->room_type_id );
			$occupancy_adult    = $room_type->occupancy_adult;
			$occupancy_child    = $room_type->occupancy_child;

			// Get tariff details for all adults
			for ( $i = 1; $i <= $occupancy_adult; $i ++ ) {
				$results = $this->load_details( $tariff->id, 'adult' . $i );

				if ( ! empty( $results ) ) {
					$tariff->details[ 'adult' . $i ] = $results;
				} else {
					$tariff->details[ 'adult' . $i ] = $this->get_tariff_details_scaffoldings( array(
						'tariff_id'  => $tariff->id,
						'guest_type' => 'adult' . $i,
						'type'       => $tariff->type,
					) );
				}

				$tariff->details[ 'adult' . $i ] = SR_Utilities::translateDayWeekName( $tariff->details[ 'adult' . $i ] );
			}

			// Get tariff details for all children
			for ( $i = 1; $i <= $occupancy_child; $i ++ ) {
				$results = $this->load_details( $tariff->id, 'child' . $i );

				if ( ! empty( $results ) ) {
					$tariff->details[ 'child' . $i ] = $results;
				} else {
					$tariff->details[ 'child' . $i ] = $this->get_tariff_details_scaffoldings( array(
						'tariff_id'  => $tariff->id,
						'guest_type' => 'child' . $i,
						'type'       => $tariff->type,
					) );
				}

				$tariff->details[ 'child' . $i ] = SR_Utilities::translateDayWeekName( $tariff->details[ 'child' . $i ] );
			}
		} else if ( (int) $tariff->type == self::PACKAGE_PER_ROOM ) {
			$results = $this->load_details( $tariff->id );

			if ( ! empty( $results ) ) {
				$tariff->details['per_room'] = $results;
			} else {
				$tariff->details['per_room'] = $this->get_tariff_details_scaffoldings( array(
					'tariff_id'  => $tariff->id,
					'guest_type' => null,
					'type'       => $tariff->type,
				) );
			}
		} else if ( (int) $tariff->type == self::PACKAGE_PER_PERSON ) {
			// Query to get tariff details for each guest type
			// First we need to get the occupancy number
			$solidres_room_type = new SR_Room_Type();
			$room_type          = $solidres_room_type->load( $tariff->room_type_id );
			$occupancy_adult    = $room_type->occupancy_adult;
			$occupancy_child    = $room_type->occupancy_child;

			// Get tariff details for all adults
			for ( $i = 1; $i <= $occupancy_adult; $i ++ ) {
				$results = $this->load_details( $tariff->id, 'adult' . $i );

				if ( ! empty( $results ) ) {
					$tariff->details[ 'adult' . $i ] = $results;
				} else {
					$tariff->details[ 'adult' . $i ] = $this->get_tariff_details_scaffoldings( array(
						'tariff_id'  => $tariff->id,
						'guest_type' => 'adult' . $i,
						'type'       => $tariff->type,
					) );
				}
			}

			// Get tariff details for all children
			for ( $i = 1; $i <= $occupancy_child; $i ++ ) {
				$results = $this->load_details( $tariff->id, 'child' . $i );

				if ( ! empty( $results ) ) {
					$tariff->details[ 'child' . $i ] = $results;
				} else {
					$tariff->details[ 'child' . $i ] = $this->get_tariff_details_scaffoldings( array(
						'tariff_id'  => $tariff->id,
						'guest_type' => 'child' . $i,
						'type'       => $tariff->type,
					) );
				}
			}
		}

		return $tariff;
	}

	/**
	 * Get a single tariff by room type id
	 *
	 * @param $room_type_id
	 * @param $standard
	 * @param $output
	 *
	 * @return mixed
	 */
	public function load_by_room_type_id( $room_type_id, $standard = true, $output = OBJECT ) {
		if ( $standard ) {
			$query = $this->wpdb->prepare( "SELECT * FROM {$this->wpdb->prefix}sr_tariffs WHERE valid_from = '0000-00-00' AND valid_to = '0000-00-00' AND room_type_id = %d", $room_type_id );
		} else {
			$query = $this->wpdb->prepare( "SELECT * FROM {$this->wpdb->prefix}sr_tariffs WHERE valid_from != '0000-00-00' AND valid_to != '0000-00-00' AND room_type_id = %d", $room_type_id );
		}

		return $this->wpdb->get_results( $query, $output );
	}

	public function load_details( $id, $guest_type = NULL ) {
		if ( isset($guest_type) ) {
			$query = $this->wpdb->prepare(
				"SELECT * FROM {$this->wpdb->prefix}sr_tariff_details
				WHERE tariff_id = %d AND guest_type = %s
				ORDER BY w_day ASC",
				array( $id, $guest_type )
			);
		} else {
			$query = $this->wpdb->prepare(
				"SELECT * FROM {$this->wpdb->prefix}sr_tariff_details
				WHERE tariff_id = %d
				ORDER BY w_day ASC",
				array( $id )
			);
		}

		return $this->wpdb->get_results( $query );
	}

	public function get_tariff_details_scaffoldings($config = array())
	{
		$scaffoldings = array();

		// If this is package per person or package per room
		if ($config['type'] == 2 || $config['type'] == 3 )
		{
			$scaffoldings[0] = new stdClass();
			$scaffoldings[0]->id = null;
			$scaffoldings[0]->tariff_id = $config['tariff_id'];
			$scaffoldings[0]->price = null;
			$scaffoldings[0]->w_day = 8;
			$scaffoldings[0]->guest_type = $config['guest_type'];
			$scaffoldings[0]->from_age = null;
			$scaffoldings[0]->to_age = null;
		}
		else // For normal complex tariff
		{
			for ($i = 0; $i < 7; $i++)
			{
				$scaffoldings[$i] = new stdClass();
				$scaffoldings[$i]->id = null;
				$scaffoldings[$i]->tariff_id = $config['tariff_id'];
				$scaffoldings[$i]->price = null;
				$scaffoldings[$i]->w_day = $i;
				$scaffoldings[$i]->guest_type = $config['guest_type'];
				$scaffoldings[$i]->from_age = null;
				$scaffoldings[$i]->to_age = null;
			}
		}

		return $scaffoldings;
	}
}