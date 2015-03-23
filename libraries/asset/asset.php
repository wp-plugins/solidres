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
 * Reservation Asset handler class
 * @package 	Solidres
 * @subpackage	Reservation Asset
 * @since 		0.1.0
 */
class SR_Asset {
	/**
	 * The database object
	 * @var object
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	/**
	 * Update states for listview
	 *
	 * @param $action
	 * @param $asset_id
	 * @param $ids
	 */
	public function update_states( $action, $asset_id, $ids ) {
		$states = array(
			'draft' => array( 'state' => 0, 'action' => 'moved', 'title' => 'Draft' ),
			'publish' => array( 'state' => 1, 'action' => 'moved', 'title' => 'Publish' ),
			'trash' => array( 'state' => -2, 'action' => 'moved', 'title' => 'Trash' ),
			'untrash' => array( 'state' => 0, 'action' => 'restored', 'title' => 'Trash' ),
		);

		if ( isset( $action ) && array_key_exists( $action, $states ) &&  isset( $asset_id ) && $asset_id != null ) {
			foreach ( $ids as $id ) {
				$this->wpdb->update($this->wpdb->prefix . 'sr_reservation_assets', array('state' => $states[$action]['state'] ), array( 'id' => $id ) );
			}
			if ( count( $ids ) == 1 ) {
				$message = __( '1 asset ' . $states[$action]['action'] . ' to the ' . $states[$action]['title'], 'solidres' );
				SR_Helper::update_message( $message );
			} else {
				$message = __( count( $ids ).' assets ' . $states[$action]['action'] . ' to the ' . $states[$action]['title'], 'solidres' );
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
		$count_room_type = $this->wpdb->get_var( "SELECT COUNT(*) FROM {$this->wpdb->prefix}sr_room_types WHERE reservation_asset_id = $id" );
		if ( $count_room_type > 0 ) {
			return false;
		} else {
			add_filter( 'query', 'solidres_wp_db_null_value' );
			$this->wpdb->update( $this->wpdb->prefix.'sr_reservations', array( 'reservation_asset_id' => 'NULL' ), array( 'reservation_asset_id' => $id ) );
			$this->wpdb->delete( $this->wpdb->prefix.'sr_media_reservation_assets_xref', array( 'reservation_asset_id' => $id ) );
			$this->wpdb->delete( $this->wpdb->prefix.'sr_reservation_asset_fields', array( 'reservation_asset_id' => $id ) );
			$this->wpdb->delete( $this->wpdb->prefix.'sr_reservation_assets', array( 'id' => $id ) );
			remove_filter( 'query', 'solidres_wp_db_null_value' );
		}
	}

	/**
	 * Get a single asset by id
	 *
	 * @param $id
	 * @param $output
	 *
	 * @return mixed
	 */
	public function load( $id, $output = OBJECT ) {
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$this->wpdb->prefix}sr_reservation_assets WHERE id = %d", $id ), $output );
	}

	/**
	 * Get a single asset by default
	 *
	 * @param $default
	 *
	 * @return mixed
	 */
	public function load_by_default( $default ) {
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$this->wpdb->prefix}sr_reservation_assets WHERE `default` = %d", $default ) );
	}

	public function load_params( $params ) {
		return json_decode( $params, true );
	}

	/**
	 * Get a single asset by reservation id
	 *
	 * @param $reservation_id
	 *
	 * @return mixed
	 */
	/*public function load_by_reservation_id( $reservation_id ) {
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$this->wpdb->prefix}sr_reservation_assets WHERE re = %s", $alias ) );
	}*/
	/**
	 * Get a single asset by alias (slug)
	 *
	 * @param $alias
	 *
	 * @return mixed
	 */
	public function load_by_alias( $alias ) {
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$this->wpdb->prefix}sr_reservation_assets WHERE alias = %s", $alias ) );
	}

	/**
	 * Get the availability calendar
	 *
	 * The number of months to be displayed in configured in component's options
	 *
	 * @param $roomtypeid
	 *
	 * @return string
	 */
	public function get_availability_calendar( $roomtypeid ) {
		$weekStartDay = ( get_option( 'start_of_week', 1 ) == 1 ) ? 'monday' : 'sunday' ;
		$html = '';
		$html .= '<span class="legend-busy"></span> '.__( 'Not available', 'solidres' );
		$options = get_option( 'solidres_plugin' );
		$period = empty( $options['availability_calendar_month_number'] ) ? 6 : $options['availability_calendar_month_number'];
		$calendar = new SR_Calendar( array( 'start_day' => $weekStartDay ) );
		for ( $i = 0; $i < $period; $i++ ) {
			if ( $i % 3 == 0 && $i == 0 ) {
				$html .= '<div class="row-fluid">';
			}
			else if ( $i % 3 == 0 ) {
				$html .= '</div><div class="row-fluid">';
			}
			$year = date( 'Y', strtotime( 'first day of this month +' . $i . ' month' ) );
			$month = date( 'n', strtotime( 'first day of this month +' . $i . ' month' ) );
			$html .= '<div class="span4">' . $calendar->generate( $year, $month, $roomtypeid ) . '</div>';
		}
		return $html;
	}

	/**
	 * Get check in out form
	 *
	 * @param $tariffId
	 * @param $roomtypeId
	 * @param $assetId
	 * @param $itemId
	 * @return string
	 */
	public function get_check_in_out_form( $tariffId, $roomtypeId, $assetId, $itemId ) {
		$solidresConfig = get_option( 'solidres_plugin' );
		$solidres_tariff = new SR_Tariff;
		$tariff = $solidres_tariff->load( $tariffId );
		//$context = 'solidres.reservation';
		//$_SESSION[$context]['reservationDetails'] = array();
		$tzoffset = get_option( 'timezone_string' );
		$tzoffset = $tzoffset == '' ? 'UTC' : $tzoffset;
		$timezone = new DateTimeZone( $tzoffset );
		$checkin = solidres()->session[ 'sr_checkin' ];
		$checkout = solidres()->session[ 'sr_checkout' ];
		$datePickerMonthNum = empty( $solidresConfig['datepicker_month_number'] ) ? 3 : $solidresConfig['datepicker_month_number'];
		$weekStartDay = empty( $solidresConfig['week_start_day'] ) ? 1 : $solidresConfig['week_start_day'];
		$currentSelectedTariffs = solidres()->session[ 'current_selected_tariffs' ];
		$currentSelectedTariffs[$roomtypeId][] = $tariffId;
		$solidresUtilities = new SR_Utilities();
		$dateFormat = empty( $solidresConfig['date_format'] ) ? 'd-m-Y' : $solidresConfig['week_start_day'];
		$jsDateFormat = $solidresUtilities::convertDateFormatPattern( $dateFormat );

		$displayData = array(
			'tariff' => $tariff,
			'assetId' => $assetId,
			'roomTypeId' => $roomtypeId,
			'checkin' => $checkin,
			'checkout' => $checkout,
			'minDaysBookInAdvance' => empty( $solidresConfig['min_days_book_in_advance'] ) ? 0 : $solidresConfig['min_days_book_in_advance'],
			'maxDaysBookInAdvance' => empty( $solidresConfig['max_days_book_in_advance'] ) ? 0 : $solidresConfig['max_days_book_in_advance'],
			'minLengthOfStay' => empty( $solidresConfig['min_length_of_stay'] ) ? 1 : $solidresConfig['min_length_of_stay'],
			'timezone' => $timezone,
			'itemId' => $itemId,
			'datePickerMonthNum' => $datePickerMonthNum,
			'weekStartDay' => $weekStartDay,
			'dateFormat' => $dateFormat, // default format d-m-y
			'jsDateFormat' => $jsDateFormat,
		);


		$html = '';
		$path = WP_PLUGIN_DIR . '/solidres/libraries/layouts/asset/checkinoutform.php';
		if ( file_exists( $path ) ) {
			ob_start();
			include $path;
			$html = ob_get_contents();
			ob_end_clean();
		}

		return $html;
	}

	/**
	 * Get the html output according to the room type quantity selection
	 *
	 * This output contains room specific form like adults and children's quantity (including children's ages) as well
	 * as some other information like room preferences like smoking and room's extra items
	 *
	 * @param $asset_id
	 * @param $room_type_id
	 * @param $tariff_id
	 * @param $quantity
	 *
	 * @return string
	 */
	public function get_room_type_form( $asset_id, $room_type_id, $tariff_id, $quantity )
	{
		$solidres_options = get_option( 'solidres_plugin' );
		$show_price_with_tax = $solidres_options['show_price_with_tax'];

		$solidres_extra = new SR_Extra;

		$extras = $solidres_extra->load_by_room_type_id( $room_type_id, 1, $show_price_with_tax );

		$solidres_room_type = new SR_Room_Type();
		$room_type = $solidres_room_type->load( $room_type_id );
		$room_type_params = $solidres_room_type->load_params( $room_type->params );
		$reservation_details_room = solidres()->session[ 'sr_room' ];
		$child_max_age = $solidres_options['child_max_age_limit'];

		$displayData = array(
			'assetId' => $asset_id,
			'roomTypeId' => $room_type_id,
			'tariffId' => $tariff_id,
			'quantity' => $quantity,
			'roomType' => $room_type,
			'room_type_params' => $room_type_params,
			'reservation_details_room' => $reservation_details_room,
			'extras' => $extras,
			'childMaxAge' => $child_max_age,
		);

		$html = '';
		$path = WP_PLUGIN_DIR . '/solidres/libraries/layouts/asset/roomtypeform.php';
		if ( file_exists( $path ) ) {
			ob_start();
			include $path;
			$html = ob_get_contents();
			ob_end_clean();
		}

		return $html;
	}

	/**
	 * Calculate tariff
	 *
	 * @param $data
	 * @return array
	 */
	public function calculate_tariff( $data ) {
		$solidres_currency  = new SR_Currency( 0, $data['currency_id'] );
		$solidres_room_type = new SR_Room_Type();
		$day_mapping = array(
			'0' => __( 'Sun', 'solidres' ),
			'1' => __( 'Mon', 'solidres' ),
			'2' => __( 'Tue', 'solidres' ),
			'3' => __( 'Wed', 'solidres' ),
			'4' => __( 'Thu', 'solidres' ),
			'5' => __( 'Fri', 'solidres' ),
			'6' => __( 'Sat', 'solidres' )
		);
		$imposed_tax_types = array();
		if ( ! empty( $data['tax_id'] ) ) {
			$solidres_tax = new SR_Tax;
			$imposed_tax_types[] = $solidres_tax->load( $data['tax_id'] );
		}
		$number_of_nights = (int) $solidres_room_type->calculateDateDiff( $data['checkin'], $data['checkout'] );
		$child_ages = array();
		for ( $i = 0; $i < $data['child_number']; $i ++ ) {
			$child_ages[] = $_GET[ 'child_age_' . $data['room_type_id'] . '_' . $data['tariff_id'] . '_' . $data['room_index'] . '_' . $i ];
		}

		// Search for complex tariff first, if no complex tariff found, we will search for Standard Tariff
		if ( SR_PLUGIN_COMPLEXTARIFF_ENABLED ) {
			$tariff = $solidres_room_type->getPrice( $data['room_type_id'], $data['customer_group_id'], $imposed_tax_types, false, true, $data['checkin'], $data['checkout'], $solidres_currency, $data['coupon'], $data['adult_number'], $data['child_number'], $data['child_ages'], $number_of_nights, ( isset( $data['tariff_id'] ) && $data['tariff_id'] > 0 ? $data['tariff_id'] : null ) );
		} else {
			$tariff = $solidres_room_type->getPrice( $data['room_type_id'], $data['customer_group_id'], $imposed_tax_types, true, false, $data['checkin'], $data['checkout'], $solidres_currency, $data['coupon'], 0, 0, array(), 0, $data['tariff_id'] );
		}

		// Prepare tariff break down, since JSON is not able to handle PHP object correctly, we should prepare a simple array
		$tariff_break_down     = array();
		$tariff_break_down_html = '';
		if ( $tariff['type'] == 0 || $tariff['type'] == 1 ) {
			$tariff_break_down     = array();
			$tariff_break_down_html = '';
			$temp_key_week_day     = null;
			$tariff_break_down_html .= '<table class="tariff-break-down">';
			foreach ( $tariff['tariff_break_down'] as $key => $price_of_day_details ) {
				if ( $key % 7 == 0 && $key == 0 ) :
					$tariff_break_down_html .= '<tr>';
				elseif ( $key % 7 == 0 ) :
					$tariff_break_down_html .= '</tr><tr>';
				endif;
				$temp_key_week_day = key( $price_of_day_details );
				$tariff_break_down_html .= '<td><p>' . $day_mapping[ $temp_key_week_day ] . '</p><span class="' . $data['tariff_breakdown_net_or_gross'] . '">' . $price_of_day_details[ $temp_key_week_day ][ $data['tariff_breakdown_net_or_gross'] ]->format() . '</span>';
				$tariff_break_down[][ $temp_key_week_day ] = array(
					'wday' => $temp_key_week_day,
					'priceOfDay' => $price_of_day_details[ $temp_key_week_day ]['gross']->format()
				);
			}
			$tariff_break_down_html .= '</tr></table>';
		}

		$shown_tariff = $tariff['total_price_tax_excl_formatted'];
		if ( $data['show_price_with_tax'] ) {
			$shown_tariff = $tariff['total_price_tax_incl_formatted'];
		}

		return array(
			'room_index' => $data['room_index'],
			'room_index_tariff' => array(
				'id'        => ! empty( $shown_tariff ) ? $shown_tariff->getId() : null,
				'activeId'  => ! empty( $shown_tariff ) ? $shown_tariff->getActiveId() : null,
				'code'      => ! empty( $shown_tariff ) ? $shown_tariff->getCode() : null,
				'sign'      => ! empty( $shown_tariff ) ? $shown_tariff->getSign() : null,
				'name'      => ! empty( $shown_tariff ) ? $shown_tariff->getName() : null,
				'rate'      => ! empty( $shown_tariff ) ? $shown_tariff->getRate() : null,
				'value'     => ! empty( $shown_tariff ) ? $shown_tariff->getValue() : null,
				'formatted' => ! empty( $shown_tariff ) ? $shown_tariff->format() : null
			),
			'room_index_tariff_breakdown' => $tariff_break_down,
			'room_index_tariff_breakdown_html' => $tariff_break_down_html,
		);
	}


	/**
	 * Prepares the document like adding meta tags/site name per ReservationAsset
	 * @return void
	 */
	protected function _prepareDocument() {
		if ( $this->item->name ) {
			$this->document->setTitle( $this->item->name );
		}

		if ( $this->item->metadesc ) {
			$this->document->setDescription( $this->item->metadesc );
		}

		if ($this->item->metakey) {
			$this->document->setMetadata( 'keywords', $this->item->metakey );
		}

		if ( $this->item->metadata ) {
			foreach ( $this->item->metadata as $k => $v ) {
				if ( $v ) {
					$this->document->setMetadata( $k, $v );
				}
			}
		}
	}

	/**
	 * Load all asset's custom fields
	 *
	 * @param $id
	 *
	 * @return string
	 *
	 */
	public function load_custom_fields( $id = 0 ) {
		return new SR_Custom_Field( array( 'id' => (int) $id, 'type' => 'asset' ) );
	}
}