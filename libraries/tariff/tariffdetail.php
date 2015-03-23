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
 * Tariff Detail handler class
 * @package 	Solidres
 * @subpackage	Tariff
 */
class SR_Tariff_Detail{

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	/**
	 * Get a list of tariff detail by tariff id
	 *
	 * @param $tariff_id
	 *
	 * @return mixed
	 */
	public function load_by_tariff_id( $tariff_id = 0 ) {
		return $this->wpdb->get_results( $this->wpdb->prepare( "SELECT id, tariff_id, price, w_day, guest_type, from_age, to_age FROM {$this->wpdb->prefix}sr_tariff_details WHERE tariff_id = %d ORDER BY w_day ASC", $tariff_id ) );
	}
}