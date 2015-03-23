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
 * Reservation handler class
 * @package 	Solidres
 * @subpackage	Reservation
 */

class SR_Reservation {
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
	 * @param $reservation_id
	 * @param $ids
	 */
	public function update_states( $action, $reservation_id, $ids ){
		$states = array(
			'trash' => array( 'state' => -2, 'action' => 'moved', 'title' => 'Trash' ),
			'untrash' => array( 'state' => 0, 'action' => 'restored', 'title' => 'Trash' ),
		);

		if ( isset( $action ) && array_key_exists ( $action, $states ) &&  isset( $reservation_id ) && $reservation_id != null ) {
			foreach ( $ids as $id ) {
				$this->wpdb->update($this->wpdb->prefix . 'sr_reservations', array( 'state' => $states[$action]['state'] ), array( 'id' => $id ) );
			}
			if ( count( $ids ) == 1 ) {
				$message = __( '1 reservation ' . $states[$action]['action'] . ' to the ' . $states[$action]['title'], 'solidres' );
				SR_Helper::update_message( $message );
			}
			else {
				$message = __( count( $ids ).' reservations ' . $states[$action]['action'] . ' to the ' . $states[$action]['title'], 'solidres' );
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
		$reservation_room_xref_ids = $this->wpdb->get_results( "SELECT id FROM {$this->wpdb->prefix}sr_reservation_room_xref WHERE reservation_id = $id" );
		foreach ( $reservation_room_xref_ids as $reservation_room_xref_id ) {
			$this->wpdb->delete( $this->wpdb->prefix.'sr_reservation_room_details', array( 'reservation_room_id' => $reservation_room_xref_id->id ) );
		}
		$this->wpdb->delete( $this->wpdb->prefix.'sr_reservation_room_xref', array( 'reservation_id' => $id ) );
		$this->wpdb->delete( $this->wpdb->prefix.'sr_reservation_room_extra_xref', array( 'reservation_id' => $id ) );
		$this->wpdb->delete( $this->wpdb->prefix.'sr_reservation_notes', array( 'reservation_id' => $id ) );
		$this->wpdb->delete( $this->wpdb->prefix.'sr_reservation_extra_xref', array( 'reservation_id' => $id ) );
		//$this->wpdb->delete( $this->wpdb->prefix.'sr_invoices' , array( 'reservation_id' => $id ) );
		$this->wpdb->delete( $this->wpdb->prefix.'sr_reservations', array( 'id' => $id ) );
	}

	/**
	 * View status and code style item of listview
	 *
	 * @param $state
	 * @param $code
	 * @return array
	 */
	public static function view_status( $state, $code ) {
		switch( $state ){
			case 0:
				$reservationstatus = __( 'Pending arrival', 'solidres' );
				$codename = '<span class="pending_code">'.$code.'</span>';
				break;
			case 1:
				$reservationstatus = __( 'Checked-in', 'solidres' );
				$codename = '<span class="checkin_code">'.$code.'</span>';
				break;
			case 2:
				$reservationstatus = __( 'Checked-out', 'solidres' );
				$codename = '<span class="checkout_code">'.$code.'</span>';
				break;
			case 3:
				$reservationstatus = __( 'Closed', 'solidres' );
				$codename = '<span class="closed_code">'.$code.'</span>';
				break;
			case 4:
				$reservationstatus = __( 'Canceled', 'solidres' );
				$codename = '<span class="canceled_code">'.$code.'</span>';
				break;
			case 5:
				$reservationstatus = __( 'Confirmed', 'solidres' );
				$codename = '<span class="confirmed_code">'.$code.'</span>';
				break;
			case -2:
				$reservationstatus = __( 'Trashed', 'solidres' );
				$codename = '<span class="trashed_code">'.$code.'</span>';
				break;
		}
		return array( $reservationstatus, $codename );
	}

	/**
	 * View payment status
	 *
	 * @param $payment_status
	 * @return string|void
	 */
	public static function payment_status( $payment_status ) {
		switch ( $payment_status ) {
			case 0:
				$paymentstatus = __( 'Unpaid', 'solidres' );
				break;
			case 1:
				$paymentstatus = __( 'Completed', 'solidres' );
				break;
			case 2:
				$paymentstatus = __( 'Cancelled', 'solidres' );
				break;
			case 3:
				$paymentstatus = __( 'Pending', 'solidres' );
				break;
		}
		return $paymentstatus;
	}

	/**
	 * View ListView
	 *
	 * @param $action
	 * @param $string_search
	 * @param $status
	 * @param $ListTableData
	 */
	public function listview( $action, $string_search, $status, $ListTableData ) {
		$query_default = "SELECT COUNT(*) FROM {$this->wpdb->prefix}sr_reservations WHERE state";
		$count_publish = $this->wpdb->get_var( $query_default.' != -2' );
		$count_trash = $this->wpdb->get_var( $query_default.' = -2' );
		if ( $action != 'edit' ) { ?>
			<div class="srtable">
				<div class="wrap">
					<div id="icon-users" class="icon32"><br/></div>
					<h2><?php _e( 'Reservations', 'solidres' ); ?>
						<?php if ( $string_search != '' ){ ?>
							<span class="subtitle"><?php printf( __( 'Search results for "%s"', 'solidres' ), $string_search ); ?></span>
						<?php } ?>
					</h2>
					<ul class="subsubsub">
						<li class="publish">
							<a href="<?php echo admin_url( 'admin.php?page=sr-reservations' ); ?>" <?php echo $status == '' ? 'class="current"' : ''; ?>>
								<?php _e( 'Publish', 'solidres' ); ?>
								<span class="count">(<?php if ( $count_publish > 0 ) { echo $count_publish; } else { echo '0'; } ?>)</span>
							</a>
						</li>
						<?php if ( $count_trash > 0 ) { ?>
							| <li class="trash">
								<a href="<?php echo admin_url( 'admin.php?page=sr-reservations&status=trash' ); ?>" <?php echo $status == 'trash' ? 'class="current"' : ''; ?>>
									<?php _e( 'Trash', 'solidres' ); ?>
									<span class="count">(<?php echo $count_trash; ?>)</span>
								</a>
							</li>
						<?php } ?>
					</ul>
					<form id="plugins-filter" method="get">
						<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
						<?php
						$ListTableData->search_box( __( 'Search', 'solidres' ), 'search_reservations' );
						$ListTableData->display();
						?>
					</form>
				</div>
			</div>
		<?php }
	}


	/**
	 * Get a single reservation by id
	 *
	 * @param $id
	 * @param $output
	 * @param $userid
	 *
	 * @return mixed
	 */
	public function load( $id, $output = OBJECT, $userid = null ) {
		$by_user = '';
		if( $userid != null ){
			$by_user = ' AND customer_id = '.$userid;
		}
		return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$this->wpdb->prefix}sr_reservations WHERE id = %d".$by_user, $id ), $output );
	}

	public function load_reserved_rooms( $reservation_id ) {
		$reserved_room_details = $this->wpdb->get_results( $this->wpdb->prepare(
			"
			SELECT x.*, rtype.id as room_type_id, rtype.name as room_type_name, room.label as room_label
			FROM {$this->wpdb->prefix}sr_reservation_room_xref as x
			INNER JOIN {$this->wpdb->prefix}sr_rooms as room ON room.id = x.room_id
			INNER JOIN {$this->wpdb->prefix}sr_room_types as rtype ON rtype.id = room.room_type_id
			WHERE reservation_id = %d
			", $reservation_id ) );

		foreach ( $reserved_room_details as $reserved_room_detail ) {
			$result = $this->wpdb->get_results( $this->wpdb->prepare(
				"
				SELECT x.*, extra.id as extra_id, extra.name as extra_name
				FROM {$this->wpdb->prefix}sr_reservation_room_extra_xref as x
				INNER JOIN {$this->wpdb->prefix}sr_extras as extra ON extra.id = x.extra_id
				WHERE reservation_id = %d AND room_id = %d
				", $reservation_id, $reserved_room_detail->room_id ) );

			if ( !empty( $result ) ) {
				$reserved_room_detail->extras = $result;
			}

			$result = $this->wpdb->get_results( $this->wpdb->prepare(
				"
				SELECT * FROM {$this->wpdb->prefix}sr_reservation_room_details
				WHERE reservation_room_id = %d
				", $reserved_room_detail->id
			) );

			$reserved_room_detail->other_info = array();
			if ( !empty( $result ) ) {
				$reserved_room_detail->other_info = $result;
			}
		}

		return $reserved_room_details;
	}

	public function load_reserved_extras( $reservation_id ) {
		return $this->wpdb->get_results( $this->wpdb->prepare(
			"SELECT * FROM {$this->wpdb->prefix}sr_reservation_extra_xref WHERE reservation_id = %d", $reservation_id
		) );
	}

	/**
	 * Generate unique string for Reservation
	 * @param string $srcString The string that need to be calculate checksum
	 * @return string The unique string for each Reservation
	 */
	public function get_code( $srcString ) {
		return hash( 'crc32', (string) $srcString.uniqid() );
	}

	/**
	 * Check a room to see if it is allowed to be booked in the period from $checkin -> $checkout
	 * @param int       $roomId
	 * @param string    $checkin
	 * @param string    $checkout
	 * @return boolean  True if the room is ready to be booked, False otherwise
	 */
	public function isRoomAvailable( $roomId = 0, $checkin, $checkout ) {
		$checkin = strtotime( $checkin );
		$checkout = strtotime( $checkout );

		$result = $this->wpdb->get_results( "SELECT checkin, checkout FROM {$this->wpdb->prefix}sr_reservations as res INNER JOIN {$this->wpdb->prefix}sr_reservation_room_xref as room ON res.id = room.reservation_id AND room.room_id = $roomId WHERE res.checkout >= date( 'Y-m-d' ) AND res.state = 1 or res.state = 5 ORDER BY res.checkin" );

		if( is_array( $result ) ) {
			foreach( $result as $currentReservation ) {
				$currentCheckin = strtotime( $currentReservation->checkin );
				$currentCheckout = strtotime( $currentReservation->checkout );
				if (
					( $checkin <= $currentCheckin && $checkout > $currentCheckin ) ||
					( $checkin >= $currentCheckin && $checkout <= $currentCheckout ) ||
					( $checkin < $currentCheckout && $checkout >= $currentCheckout )
				)
				{
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * Store reservation data and related data like children ages or other room preferences
	 *
	 * @param   int 	$reservationId
	 * @param   array 	$room Room information
	 *
	 * @return void
	 */
	public function storeRoom( $reservationId, $room ) {
		$this->wpdb->insert( $this->wpdb->prefix.'sr_reservation_room_xref', array(
			'reservation_id' => (int) $reservationId,
			'room_id' => $room['room_id'],
			'room_label' => $room['room_label'],
			'adults_number' => $room['adults_number'],
			'children_number' => isset( $room['children_number'] ) ? $room['children_number'] : 0,
			'guest_fullname' => $room['guest_fullname'],
			'room_price' => $room['room_price'],
			'room_price_tax_incl' => $room['room_price_tax_incl'],
			'room_price_tax_excl' => $room['room_price_tax_excl'],
			'tariff_id' => $room['tariff_id'],
			'tariff_title' => $room['tariff_title'],
			'tariff_description' => $room['tariff_description']
		) );

		$recentInsertedId = $this->wpdb->insert_id;
		if ( isset ( $room['children_number'] ) ) {
			for ( $i = 0; $i < $room['children_number']; $i++ ) {
				$this->wpdb->insert( $this->wpdb->prefix.'sr_reservation_room_details', array( 'reservation_room_id' => (int) $recentInsertedId, 'key' => 'child'.($i + 1), 'value' => $room['children_ages'][$i] ) );
			}
		}

		if (isset($room['preferences'])) {
			foreach ($room['preferences'] as $key => $value) {
				$this->wpdb->insert( $this->wpdb->prefix.'sr_reservation_room_details', array( 'reservation_room_id' => (int) $recentInsertedId, 'key' => $key, 'value' => $value ) );
			}
		}
	}

	/**
	 * Store extra information
	 * @param  int      $reservationId
	 * @param  int      $roomId
	 * @param  string   $roomLabel
	 * @param  int      $extraId
	 * @param  string   $extraName
	 * @param  int      $extraQuantity The extra quantity or NULL if extra does not have quantity
	 * @param  int      $price
	 * @return void
	 */
	public function storeExtra( $reservationId, $roomId, $roomLabel, $extraId, $extraName, $extraQuantity = NULL, $price = 0 ) {
		$this->wpdb->insert( $this->wpdb->prefix.'sr_reservation_room_extra_xref', array( 'reservation_id' => $reservationId, 'room_id' => $roomId, 'room_label' => $roomLabel, 'extra_id' => $extraId, 'extra_name' => $extraName, 'extra_quantity' => ( $extraQuantity === NULL ? NULL : $extraQuantity), 'extra_price' => $price ) );
	}

	/**
	 * Check for the validity of check in and check out date
	 * Conditions
	 * + Number of days a booking must be made in advance
	 * + Maximum length of stay
	 * @param string $checkIn
	 * @param string $checkOut
	 * @param array $conditions
	 * @throws Exception
	 * @return Boolean
	 */
	public function isCheckInCheckOutValid( $checkIn, $checkOut, $conditions ) {

		$checkIn = new DateTime( $checkIn );
		$checkOut = new DateTime( $checkOut );
		$today = new DateTime( date( 'Y-m-d' ) );

		if ( $checkOut <= $checkIn ) {
			throw new Exception( 'Invalid. Check out date must be after check in date.', 50001 );
		}

		// Interval between check in and check out date
		$interval1 = $checkOut->diff( $checkIn )->format('%a');

		if ($conditions['min_length_of_stay'] > 0) {
			if ( $interval1 < $conditions['min_length_of_stay']) // count nights, not days
			{
				throw new Exception('Invalid. Minimum length of stay is %d nights.', 50002);
			}
		}

		// Interval between checkin and today
		$interval2 = $checkIn->diff($today)->format('%a');

		if ($conditions['min_days_book_in_advance'] > 0) {
			if ($interval2 < $conditions['min_days_book_in_advance']) {
				throw new Exception('Invalid. You have to book at least %d days in advance of your arrival.', 50003);
			}
		}

		if ($conditions['max_days_book_in_advance'] > 0) {
			if ($interval2 > $conditions['max_days_book_in_advance']) {
				throw new Exception('Invalid. You are not allowed to book more than %d days in advance of your arrival.', 50004);
			}
		}
		return true;
	}

	public function process_room( $data ) {
		// Get the extra price to display in the confirmmation screen
		$solidres_extra = new SR_Extra();
		$total_room_type_extra_cost_tax_excl = 0;
		$total_room_type_extra_cost_tax_incl = 0;

		foreach ( $data['room_types'] as $room_type_id => &$booked_tariffs ) {
			foreach ( $booked_tariffs as $tariffId => &$rooms ) {
				foreach ( $rooms as &$room ) {
					if ( isset( $room['extras'] ) ) {
						foreach ( $room['extras'] as $extra_id => &$extra_details ) {
							//$extra                          = $extraModel->getItem( $extra_id );
							$extra = $solidres_extra->load( $extra_id );
							$extra_details['price']          = $extra->price;
							$extra_details['price_tax_incl'] = $extra->price_tax_incl;
							$extra_details['price_tax_excl'] = $extra->price_tax_excl;
							$extra_details['name']           = $extra->name;

							if ( isset( $extra_details['quantity'] ) ) {
								$total_room_type_extra_cost_tax_incl += $extra_details['price_tax_incl'] * $extra_details['quantity'];
								$total_room_type_extra_cost_tax_excl += $extra_details['price_tax_excl'] * $extra_details['quantity'];
							} else {
								$total_room_type_extra_cost_tax_incl += $extra_details['price_tax_incl'];
								$total_room_type_extra_cost_tax_excl += $extra_details['price_tax_excl'];
							}
						}
					}
				}
			}
		}

		// manually unset those referenced instances
		unset( $rooms );
		unset( $room );
		unset( $extra_details );

		$data['total_extra_price_per_room']          = $total_room_type_extra_cost_tax_incl;
		$data['total_extra_price_tax_incl_per_room'] = $total_room_type_extra_cost_tax_incl;
		$data['total_extra_price_tax_excl_per_room'] = $total_room_type_extra_cost_tax_excl;

		solidres()->session[ 'sr_room' ] = $data ;

		solidres()->session[ 'sr_booking_conditions' ] = $data['bookingconditions'];
		solidres()->session[ 'sr_privacy_policy' ] = $data['privacypolicy'];

		// Store all selected tariffs
		solidres()->session[ 'sr_current_selected_tariffs' ] = $data['selected_tariffs'];

		// If error happened, output correct error message in json format so that we can handle in the front end
		$response = array( 'status' => 1, 'message' => '', 'next_step' => $data['next_step'] );

		wp_send_json( $response );
	}

	/**
	 * Process submitted guest information: guest personal information and their payment method
	 *
	 * @param $data
	 *
	 * @return json
	 */
	public function process_guest_info($data)
	{
		$solidres_country = new SR_Country();
		$solidres_state = new SR_State();
		$solidres_extra = new SR_Extra();
		$country = $solidres_country->load( $data['customer_country_id'] );
		$totalRoomTypeExtraCostTaxExcl = 0;
		$totalRoomTypeExtraCostTaxIncl = 0;

		// Query country and geo state name
		if ( !empty( $data['customer_geo_state_id'] ) ) {
			$geoState = $solidres_state->load( $data['customer_geo_state_id'] );
			$data['geo_state_name'] = $geoState->name;
		}
		$data['country_name'] = $country->name;

		// Process customer group
		$customerId = null;
		$data['customer_id'] = $customerId;

		// Process extra (Per booking)
		if ( isset( $data['extras'] ) ) {
			foreach ( $data['extras'] as $extraId => &$extraDetails ) {
				//$extra                          = $extraModel->getItem( $extraId );
				$extra = $solidres_extra->load( $extraId );
				$extraDetails['price'] = $extra->price;
				$extraDetails['price_tax_incl'] = $extra->price_tax_incl;
				$extraDetails['price_tax_excl'] = $extra->price_tax_excl;
				$extraDetails['name'] = $extra->name;

				if ( isset( $extraDetails['quantity'] ) ) {
					$totalRoomTypeExtraCostTaxIncl += $extraDetails['price_tax_incl'] * $extraDetails['quantity'];
					$totalRoomTypeExtraCostTaxExcl += $extraDetails['price_tax_excl'] * $extraDetails['quantity'];
				} else {
					$totalRoomTypeExtraCostTaxIncl += $extraDetails['price_tax_incl'];
					$totalRoomTypeExtraCostTaxExcl += $extraDetails['price_tax_excl'];
				}
			}
		}

		$data['total_extra_price_per_booking'] = $totalRoomTypeExtraCostTaxIncl;
		$data['total_extra_price_tax_incl_per_booking'] = $totalRoomTypeExtraCostTaxIncl;
		$data['total_extra_price_tax_excl_per_booking'] = $totalRoomTypeExtraCostTaxExcl;

		// Bind them to session
		solidres()->session[ 'sr_guest' ] = $data;

		// If error happened, output correct error message in json format so that we can handle in the front end
		$response = array( 'status' => 1, 'message' => '', 'next_step' => $data['next_step'] );

		wp_send_json( $response );
	}

	/**
	 * Return html to display guest info form in one-page reservation, data is retrieved from user session
	 *
	 * @return string $html The HTML output
	 */
	public function get_html_guest_info() {

		$options = get_option( 'solidres_plugin' );
		$reservation_details_room = solidres()->session[ 'sr_room' ];
		$reservation_details_guest = solidres()->session[ 'sr_guest' ];
		$show_price_with_tax = $options[ 'show_price_with_tax' ] ;
		$customer_titles = array(
			'' => '',
			__( 'Mr.', 'solidres' ) => __( 'Mr.', 'solidres' ),
			__( 'Mrs.', 'solidres' ) => __( 'Mrs.', 'solidres' ),
			__( 'Ms.', 'solidres' ) => __( 'Ms.', 'solidres' )
		);
		$asset_id = $reservation_details_room['raid'];

		$solidres_extra = new SR_Extra();
		$extras = $solidres_extra->load_by_reservation_asset_id( $asset_id, 1, $show_price_with_tax );

		// Try to get the customer information if he/she logged in
		$selected_country_id = $reservation_details_guest['customer_country_id'];
		/*if ( SR_PLUGIN_USER_ENABLED ) {
			$customerTable = JTable::getInstance( 'Customer', 'SolidresTable' );
			$user          = JFactory::getUser();
			$customerTable->load( array( 'user_id' => $user->get( 'id' ) ) );
			$guestFields = array(
				'customer_firstname',
				'customer_middlename',
				'customer_lastname',
				'customer_vat_number',
				'customer_company',
				'customer_phonenumber',
				'customer_address1',
				'customer_address2',
				'customer_city',
				'customer_zipcode',
				'customer_country_id',
				'customer_geo_state_id'
			);

			if ( ! empty( $customerTable->id ) ) {
				foreach ( $guestFields as $guestField ) {
					if ( ! isset( $this->reservationDetails->guest[ $guestField ] ) ) {
						$this->reservationDetails->guest[ $guestField ] = $customerTable->{substr( $guestField, 9 )};
					}
				}

				$this->reservationDetails->guest["customer_email"] = ! isset( $this->reservationDetails->guest["customer_email"] ) ? $user->get( 'email' ) : $this->reservationDetails->guest["customer_email"];
			}

			$selectedCountryId = isset( $this->reservationDetails->guest["customer_country_id"] ) ? $this->reservationDetails->guest["customer_country_id"] : 0;
		}*/
		$countries = SR_Helper::get_country_selected($selected_country_id);
		$geo_states = $selected_country_id > 0 ? SR_Helper::get_geo_state_selected( $selected_country_id, $reservation_details_guest[ 'customer_geo_state_id' ] ) : '';
		//$solidres_payment_plugins = SR_Helper::getPaymentPluginOptions(true);

		$displayData = array(
			'customer_titles' => $customer_titles,
			'reservation_details_guest' => $reservation_details_guest,
			'extras' => $extras,
			'assetId' => $asset_id,
			'countries' => $countries,
			'geo_states' => $geo_states,
			'solidresPaymentPlugins' => /*$solidres_payment_plugins*/ array(),
		);

		$html = '';
		$path = WP_PLUGIN_DIR . '/solidres/libraries/layouts/asset/guestform.php';

		if ( file_exists( $path ) ) {
			ob_start();
			include $path;
			$html = ob_get_contents();
			ob_end_clean ();
		}

		echo $html;
	}

	/**
	 * Return html to display confirmation form in one-page reservation, data is retrieved from user session
	 *
	 * @return string $html The HTML output
	 */
	public function get_html_confirmation()
	{
		$reservation_details_room = solidres()->session[ 'sr_room' ];
		$reservation_details_guest = solidres()->session[ 'sr_guest' ];
		$solidres_room_type = new SR_Room_Type();
		$solidres_utilities = new SR_Utilities();

		$solidresConfig = get_option( 'solidres_plugin' );
		$checkin = solidres()->session[ 'sr_checkin' ];
		$checkout = solidres()->session[ 'sr_checkout' ];
		$raId = $reservation_details_room['raid'];
		$currency = new SR_Currency(0, solidres()->session[ 'sr_currency_id' ] );
		$totalRoomTypeExtraCostTaxIncl = $reservation_details_room['total_extra_price_tax_incl_per_room'] + $reservation_details_guest['total_extra_price_tax_incl_per_booking'];
		$totalRoomTypeExtraCostTaxExcl = $reservation_details_room['total_extra_price_tax_excl_per_room'] + $reservation_details_guest['total_extra_price_tax_excl_per_booking'];
		$numberOfNights = $solidres_room_type->calculateDateDiff($checkin, $checkout);
		$dateFormat = get_option( 'date_format' );
		$jsDateFormat = $solidres_utilities::convertDateFormatPattern($dateFormat);
		$tzoffset = get_option( 'timezone_string' );
		$tzoffset = $tzoffset == '' ? 'UTC' : $tzoffset;
		$timezone = new DateTimeZone( $tzoffset );

		$task = 'reservation.save';

		// Query for room types data and their associated costs
		$booked_room_types = $reservation_details_room[ 'room_types' ];
		$roomTypes = $solidres_room_type->get_room_type( $raId, $booked_room_types, $checkin, $checkout );

		// Rebind the session data because it has been changed in the previous line
		$reservation_details_room = solidres()->session[ 'sr_room' ];
		$reservation_details_guest = solidres()->session[ 'sr_guest' ];
		$cost = solidres()->session[ 'sr_cost' ];

		$displayData = array(
			'roomTypes' => $roomTypes,
			'reservation_details_room' => $reservation_details_room,
			'reservation_details_guest' => $reservation_details_guest,
			'totalRoomTypeExtraCostTaxIncl' => $totalRoomTypeExtraCostTaxIncl,
			'totalRoomTypeExtraCostTaxExcl' => $totalRoomTypeExtraCostTaxExcl,
			'task' => $task,
			'assetId' => $raId,
			'cost' => $cost,
			'numberOfNights' => $numberOfNights,
			'currency' => $currency,
			'dateFormat' => $dateFormat, // default format d-m-y
			'jsDateFormat' => $jsDateFormat,
			'timezone' => $timezone,
			'checkin' => $checkin,
			'checkout' => $checkout,
			'currency_id' => solidres()->session[ 'sr_currency_id' ],
			'tax_id' => solidres()->session[ 'sr_tax_id' ],
			'deposit_required' => solidres()->session[ 'sr_deposit_required' ],
			'deposit_is_percentage' => solidres()->session[ 'sr_deposit_is_percentage' ],
			'deposit_amount' => solidres()->session[ 'sr_deposit_amount' ],
			'asset_params' => solidres()->session[ 'sr_asset_params' ],
			'page_id' => solidres()->session['sr_wp_page_id'],
		);

		$html = '';
		$path = WP_PLUGIN_DIR . '/solidres/libraries/layouts/asset/confirmationform.php';

		if ( file_exists( $path ) ) {
			ob_start();
			include $path;
			$html = ob_get_contents();
			ob_end_clean ();
		}

		echo $html;
	}

	public function save( $columns ) {

		$room_type_data = $columns[ 'room_types' ];
		$extra_data = isset( $columns[ 'extras' ] ) ?  $columns[ 'extras' ] : array() ;
		$is_new = empty( $columns[ 'id' ] );

		$reservation_fields = array(
			'id',
			'state',
			'customer_id',
			'created_date',
			'modified_date',
			'modified_by',
			'created_by',
			'payment_method_id',
			'payment_method_txn_id',
			'payment_status',
			'payment_data',
			'code',
			'coupon_id',
			'coupon_code',
			'customer_title',
			'customer_firstname',
			'customer_middlename',
			'customer_lastname',
			'customer_email',
			'customer_phonenumber',
			'customer_mobilephone',
			'customer_company',
			'customer_address1',
			'customer_address2',
			'customer_city',
			'customer_zipcode',
			'customer_country_id',
			'customer_geo_state_id',
			'customer_vat_number',
			'checkin',
			'checkout',
			'invoice_number',
			'currency_id',
			'currency_code',
			'total_price',
			'total_price_tax_incl',
			'total_price_tax_excl',
			'total_extra_price',
			'total_extra_price_tax_incl',
			'total_extra_price_tax_excl',
			'total_discount',
			'note',
			'reservation_asset_id',
			'reservation_asset_name',
			'deposit_amount',
			'total_paid',
		);

		foreach ( $columns as $key => $val ) {
			if ( ! in_array( $key, $reservation_fields) ) {
				unset( $columns[ $key ] );
			}

			if ( is_null( $val )) {
				$columns[ $key ] = 'NULL';
			}
		}

		$columns[ 'code' ] = $this->get_code( $columns[ 'created_date' ] );
		add_filter( 'query', 'solidres_wp_db_null_value' );
		$result = $this->wpdb->insert( $this->wpdb->prefix.'sr_reservations', $columns );
		remove_filter( 'query', 'solidres_wp_db_null_value' );

		//print_r($this->wpdb->queries);
		$saved_reservation_id = $this->wpdb->insert_id;


		//$this->wpdb->delete( $this->wpdb->prefix.'sr_reservation_room_xref', array( 'reservation_id' => $saved_reservation_id ) );

		// Insert new records
		$roomTypePricesMapping = solidres()->session[ 'sr_room_type_prices_mapping' ];
		$solidres_room_type = new SR_Room_Type();
		$solidres_tariff = new SR_Tariff();
		foreach ( $room_type_data as $roomTypeId => $bookedTariffs ) {
			// Find a list of available rooms
			$availableRoomList = $solidres_room_type->getListAvailableRoom( $roomTypeId, $columns['checkin'], $columns['checkout'] );

			foreach ( $bookedTariffs as $tariffId => $rooms ) {
				foreach ( $rooms as $roomIndex => $room ) {
					// Pick the first and assign it
					$pickedRoom = array_shift( $availableRoomList );

					// Get the tariff info
					$booked_tariff = $solidres_tariff->load( $tariffId );

					$room['room_id']             = $pickedRoom->id;
					$room['room_label']          = $pickedRoom->label;
					$room['room_price']          = $roomTypePricesMapping[ $roomTypeId ][ $tariffId ][ $roomIndex ]['total_price_tax_incl'];
					$room['room_price_tax_incl'] = $roomTypePricesMapping[ $roomTypeId ][ $tariffId ][ $roomIndex ]['total_price_tax_incl'];
					$room['room_price_tax_excl'] = $roomTypePricesMapping[ $roomTypeId ][ $tariffId ][ $roomIndex ]['total_price_tax_excl'];
					$room['tariff_id']           = $tariffId > 0 ? $tariffId : null;
					$room['tariff_title']        = ! empty( $booked_tariff->title ) ? $booked_tariff->title : '';
					$room['tariff_description']  = ! empty( $booked_tariff->description ) ? $booked_tariff->description : '';

					$this->storeRoom( $saved_reservation_id, $room );

					// Insert new records
					if (isset($room['extras']))
					{
						foreach ($room['extras'] as $extraId => $extraDetails)
						{
							if (isset($extraDetails['quantity']))
							{
								$this->storeExtra($saved_reservation_id, $room['room_id'], $room['room_label'], $extraId, $extraDetails['name'], $extraDetails['quantity'], $extraDetails['price_tax_incl']);
							}
							else
							{
								$this->storeExtra($saved_reservation_id, $room['room_id'], $room['room_label'], $extraId, $extraDetails['name'], NULL, $extraDetails['price_tax_incl']);
							}
						}
					}
				}
			}
		}

		// Store extra items (Per booking)
		if ( isset( $extra_data ) ) {
			foreach ( $extra_data as $extraId => $extraDetails ) {
				$reservationExtraData = array(
					'reservation_id' => $saved_reservation_id,
					'extra_id'       => $extraId,
					'extra_name'     => $extraDetails['name'],
					'extra_quantity' => ( isset( $extraDetails['quantity'] ) ? $extraDetails['quantity'] : null ),
					'extra_price'    => $extraDetails['price_tax_incl']
				);

				$this->wpdb->insert( $this->wpdb->prefix . 'sr_reservation_extra_xref', $reservationExtraData );
			}
		}

		// Update the quantity of coupon
		if ( $is_new ) {
			if ( isset( $columns['coupon_id'] ) && $columns['coupon_id'] > 0 ) {
				$solidres_coupon = new SR_Coupon();
				$coupon = $solidres_coupon->load( $columns['coupon_id'] );
				if ( ! is_null( $coupon->quantity ) && $coupon->quantity > 0 ) {
					$this->wpdb->update( $this->wpdb->prefix . 'sr_coupons', array( 'quantity' => (int) $coupon->quantity --), array( 'id' => $columns['coupon_id']) );
				}
			}
		}

		$stored_reservation_info = $this->load( $saved_reservation_id );

		solidres()->session[ 'sr_saved_reservation_id' ] = $saved_reservation_id;
		solidres()->session[ 'sr_code' ] = $stored_reservation_info->code;
		solidres()->session[ 'sr_payment_method_id' ] = $stored_reservation_info->payment_method_id;
		solidres()->session[ 'sr_customeremail' ] = $stored_reservation_info->customer_email;

		if ($stored_reservation_info->payment_method_id != 'paylater' && $stored_reservation_info->payment_method_id != 'bankwire')
		{
			// Run payment plugin here
			/*$responses = $this->app->triggerEvent('OnSolidresPaymentNew', array(	$resTable ));
			$document = JFactory::getDocument();
			$viewType = $document->getType();
			$viewName = 'Reservation';
			$viewLayout = 'payment';

			$view = $this->getView($viewName, $viewType, '', array('base_path' => $this->basePath, 'layout' => $viewLayout));
			if (!empty($responses))
			{
				foreach ($responses as $response)
				{
					if ($response === false) continue;
					$view->paymentForm = $response;
				}
			}

			$view->display();*/
		}
		else
		{
			/*$link = JRoute::_('index.php?option=com_solidres&task=reservation.finalize&reservation_id='.$savedReservationId, false);
			$this->setRedirect($link);*/
		}

		return $result;
	}

	/**
	 * Load all extras belong to this reservation $id
	 *
	 * @param $id
	 * @return array
	 */
	public function load_extras( $id ) {
		$results = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT * FROM {$this->wpdb->prefix}sr_reservation_extra_xref WHERE reservation_id = %d", $id ), OBJECT  );

		return $results;
	}
};
