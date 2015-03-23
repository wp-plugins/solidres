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
 * Room class
 * @package 	Solidres
 * @subpackage	Room
 */
class SR_Room{

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	/**
	 * Get a list of room by room type id
	 *
	 * @param $room_type_id
	 *
	 * @return mixed
	 */
	public function load_by_room_type_id( $room_type_id = 0 ) {
		return $this->wpdb->get_results( $this->wpdb->prepare( "SELECT id, label FROM {$this->wpdb->prefix}sr_rooms WHERE room_type_id = %d", $room_type_id ) );
	}
}