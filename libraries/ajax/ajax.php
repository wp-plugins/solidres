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

class SR_Ajax {

	public static function init() {
		$events = array(
			'load_availability_calendar' => true,
			'load_checkinoutform' => true,
			'load_roomtypeform' => true,
			'load_states' => true,
			'load_taxes' => false,
			'load_coupons' => false,
			'load_extras' => false,
			'edit_reservation_field' => false,
			'save_reservation_note' => false,
			'cancel_reservation' => false,
			'delete_room' => false,
			'confirm_delete_room' => false,
			'calculate_tariff' => true,
			'reservation_process' => true,
			'reservation_progress' => true,
			'set_currency' => true,
		);

		foreach ( $events as $event => $nopriv ) {
			add_action( 'wp_ajax_solidres_' . $event, array( __CLASS__, $event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_solidres_' . $event, array( __CLASS__, $event ) );
			}
		}
	}

	public static function load_availability_calendar() {
		check_ajax_referer( 'load-calendar', 'security' );
		$room_type_id = (int)$_REQUEST['id'];
		$calendar = new SR_Asset();
		echo $calendar->get_availability_calendar( $room_type_id );
		wp_die();
	}

	public static function set_currency() {
		check_ajax_referer( 'set-currency', 'security' );
		$currency_id = (int)$_REQUEST['currency_id'];
		solidres()->session[ 'current_currency_id' ] = $currency_id ;
		wp_die();
	}

	public static function load_checkinoutform() {

		check_ajax_referer( 'load-date-form', 'security' );

		$Itemid = (int)$_REQUEST['Itemid'];
		$id = (int)$_REQUEST['id'];
		$roomtype_id = (int)$_REQUEST['roomtype_id'];
		$tariff_id = (int)$_REQUEST['tariff_id'];
		$asset = new SR_Asset();

		echo $asset->get_check_in_out_form( $tariff_id, $roomtype_id, $id, $Itemid );
		wp_die();
	}

	public static function load_roomtypeform() {

		check_ajax_referer( 'load-room-form', 'security' );

		$asset_id = (int)$_GET[ 'raid' ];
		$room_type_id = (int)$_GET[ 'rtid' ];
		$tariff_id = (int)$_GET[ 'tariffid' ];
		$quantity = (int)$_GET[ 'quantity' ];

		$asset = new SR_Asset();
		echo $asset->get_room_type_form( $asset_id, $room_type_id, $tariff_id, $quantity );
		wp_die();
	}

	public static function load_states() {

		check_ajax_referer( 'load-states', 'security' );

		global $wpdb;

		$statedata = '';
		$country_id = (int)$_REQUEST[ 'country_id' ];
		$states = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sr_geo_states WHERE country_id = %d", $country_id ) );
		$statedata .= '<option value="">Select state</option>';
		foreach ( $states as $state ) {
			$statedata .= '<option value="'.$state->id.'">'.$state->name.'</option>';
		}
		echo $statedata;
		wp_die();
	}

	public static function load_taxes() {

		check_ajax_referer( 'load-taxes', 'security' );

		global $wpdb;

		$taxdata = '';
		$country_id = (int)$_REQUEST['country_id'];
		$taxes = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sr_taxes WHERE country_id = %d", $country_id ) );
		$taxdata .= '<option value="">Select tax</option>';
		foreach ( $taxes as $tax ) {
			$taxdata .= '<option value="'.$tax->id.'">'.$tax->name.'</option>';
		}
		echo $taxdata;
		wp_die();
	}

	public static function load_coupons() {

		check_ajax_referer( 'load-coupons', 'security' );

		global $wpdb;

		$coupons_data = '';
		$reservation_asset_id = (int)$_REQUEST['reservation_asset_id'];
		$coupons = $wpdb->get_results( $wpdb->prepare( "SELECT id, coupon_name FROM {$wpdb->prefix}sr_coupons WHERE reservation_asset_id = %d", $reservation_asset_id ) );
		foreach ( $coupons as $coupon ) {
			$coupons_data .= '<input type="checkbox" name="srform[coupons][]" value="'.$coupon->id.'"/>'.$coupon->coupon_name.'<br>';
		}

		echo $coupons_data;
		wp_die();
	}

	public static function load_extras() {

		check_ajax_referer( 'load-extras', 'security' );

		global $wpdb;

		$extras_data = '';
		$reservation_asset_id = (int)$_REQUEST['reservation_asset_id'];
		$extras = $wpdb->get_results( $wpdb->prepare( "SELECT id, name FROM {$wpdb->prefix}sr_extras WHERE reservation_asset_id = %d", $reservation_asset_id ) );
		foreach ( $extras as $extra ) {
			$extras_data .= '<input type="checkbox" name="srform[extras][]" value="'.$extra->id.'"/>'.$extra->name.'<br>';
		}

		echo $extras_data;
		wp_die();
	}

	public static function save_reservation_note() {

		check_ajax_referer( 'save-note', 'security' );

		global $wpdb;

		$notes_data = '';
		$current_user = wp_get_current_user();
		$author_id = $current_user->ID;
		$note_text = $_REQUEST['note_text'];
		$notify_check = $_REQUEST['notify_check'];
		$notify_check = $notify_check == 'true' ? 1 : 0;
		$visible_in_frontend_check = $_REQUEST['visible_in_frontend_check'];
		$visible_in_frontend_check = $visible_in_frontend_check == 'true' ? 1 : 0;
		$reservation_id = $_REQUEST['reservation_id'];
		$today = date( 'Y-m-d H:i:s' );

		$data = array();
		$data['reservation_id'] = ! empty( $reservation_id ) ? $reservation_id : 0;
		$data['text'] = ! empty( $note_text ) ? $note_text : '';
		$data['created_date'] = $today;
		$data['created_by'] = $author_id;
		$data['notify_customer'] = $notify_check;
		$data['visible_in_frontend'] = $visible_in_frontend_check;

		$wpdb->insert( $wpdb->prefix.'sr_reservation_notes', array( 'reservation_id' => $reservation_id, 'text' => $note_text, 'created_date' => $today, 'created_by' => $author_id, 'notify_customer' => $notify_check, 'visible_in_frontend' => $visible_in_frontend_check ) );
		$get_reservation_notes = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sr_reservation_notes WHERE reservation_id = %d", $reservation_id ) );

		foreach ( $get_reservation_notes as $get_reservation_note ) {

			$created_name = get_the_author_meta('display_name', $get_reservation_note->created_by);

			$notify_customer =  $get_reservation_note->notify_customer == 1 ? 'Yes' : 'No';
			$visible_frontend = $get_reservation_note->visible_in_frontend == 1 ? 'Yes' : 'No';

			$notes_data .= '<div class="reservation_note_item">';
			$notes_data .= '<p class="info">'.$get_reservation_note->created_date.' by '.$created_name.'</p>';
			$notes_data .= '<p>Notify customer via email: '.$notify_customer.' | Display in frontend: '.$visible_frontend.'</p>';
			$notes_data .= '<p>'.$get_reservation_note->text.'</p>';
			$notes_data .= '</div>';
		}

		if( $notify_check == 1 ){
			$reservations = new SR_Reservation();
			$resTable = $reservations->load( $reservation_id );
			$assets = new SR_Asset();
			$asset = $assets->load( $resTable->reservation_asset_id );

			$asset_custom_fields = new SR_Custom_Field( array( 'id' => (int) $resTable->reservation_asset_id, 'type' => 'asset' ) );
			$custom_field_data = $asset_custom_fields->create_array_group();
			$social_networks = $custom_field_data['social_networks'];
			$social_network = array();
			if( ! empty ( $social_networks ) ) {
				foreach ( $social_networks as $keys => $values ) {
					$fiel_name = $asset_custom_fields->split_field_name( $values[0] );
					$field_value = $values[1];
					$social_network[$fiel_name] = $field_value;
				}
			}

			$displayData = array(
				'reservation' => $resTable,
				'asset' => $asset,
				'social_network' => $social_network,
				'text' => $data['text']
			);

			$options = get_option( 'solidres_plugin' );
			$emailFormat = ! empty ( $options['email_format'] ) ? $options['email_format'] : 'text/html';
			$messageTemplateExt = ( $emailFormat == 'text/html' ) ? 'html' : 'txt';

			ob_start();
			include_once WP_PLUGIN_DIR . '/solidres/libraries/layouts/emails/reservation_note_notification_customer_'.$messageTemplateExt.'_inliner.php';
			$emailTemplate = ob_get_contents();
			ob_end_clean ();

			$to = $resTable->customer_email;
			$subject = __( 'Reservation notification from ', 'solidres' ).$asset->name;
			$header = 'From: ' .$asset->name.' <'.$asset->email.'>';
			add_filter( 'wp_mail_content_type', 'solidres_set_html_content_type' );
			wp_mail( $to, $subject, $emailTemplate, $header );
			remove_filter( 'wp_mail_content_type', 'solidres_set_html_content_type' );
		}
		echo $notes_data;
		wp_die();
	}

	public static function cancel_reservation() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], "cancel_reservation_nonce")) {
			exit( "Can't cancel this reservation error" );
		}

		global $wpdb;

		$reservation_id = (int)$_REQUEST['reservation_id'];
		$customer_id = (int)$_REQUEST['customer_id'];

		$return = $wpdb->update( $wpdb->prefix.'sr_reservations', array( 'state' => 4 ), array( 'id' => $reservation_id, 'customer_id' => $customer_id ) );
		if ( $return ){
			echo 1;
		} else {
			echo 0;
		}
		wp_die();
	}

	public static function delete_room() {

		check_ajax_referer( 'delete-room', 'security' );

		global $wpdb;

		$room_id = (int)$_REQUEST['room_id'];
		$room_exist = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}sr_reservation_room_xref WHERE room_id = %d", $room_id ) );
		$room_extra_exist = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}sr_reservation_room_extra_xref WHERE room_id = %d", $room_id ) );
		if ( SR_PLUGIN_LIMITBOOKING_ENABLED ) {
			$room_limitbooking_exist = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}sr_limit_booking_details WHERE room_id = %d", $room_id ) );
		}

		if( $room_exist > 0 ){
			$result['type'] = "error";
		} else {
			if ( $room_extra_exist > 0 ){
				$result['type'] = "error";
			} else {
				if( $room_limitbooking_exist > 0 ){
					$result['type'] = "error";
				} else {
					$result['type'] = "success";
					$result['room_id'] = $room_id;
					$wpdb->delete( $wpdb->prefix.'sr_rooms', array( 'id' => $room_id ) );
				}
			}
		}

		$result = json_encode( $result );
		echo $result;
		wp_die();
	}

	public static function confirm_delete_room() {

		check_ajax_referer( 'confirm-delete-room', 'security' );

		global $wpdb;

		$room_id = (int)$_REQUEST['room_id'];

		$result['type'] = "success";
		$result['room_id'] = $room_id;

		add_filter( 'query', 'solidres_wp_db_null_value' );
		$wpdb->update( $wpdb->prefix.'sr_reservation_room_xref', array( 'room_id' => 'NULL' ), array( 'room_id' => $room_id ) );
		$wpdb->update( $wpdb->prefix.'sr_reservation_room_extra_xref', array( 'room_id' => 'NULL' ), array( 'room_id' => $room_id ) );
		$wpdb->delete( $wpdb->prefix.'sr_limit_booking_details', array( 'room_id' => $room_id ) );
		$wpdb->delete( $wpdb->prefix.'sr_rooms', array( 'id' => $room_id ) );
		remove_filter( 'query', 'solidres_wp_db_null_value' );

		$result = json_encode( $result );
		echo $result;
		wp_die();
	}

	public static function edit_reservation_field() {

		check_ajax_referer( 'edit-reservation', 'security' );

		global $wpdb;

		// POSTED MY_STATUS
		if( ( isset( $_REQUEST['name'] ) && 'state' == $_REQUEST['name'] ) || ( isset( $_REQUEST['name'] ) && 'payment_status' == $_REQUEST['name'] ) || ( isset( $_REQUEST['name'] ) && 'total_paid' == $_REQUEST['name'] ) )

			$pk = $_REQUEST['pk'];
		$name = $_REQUEST['name'];
		$value = $_REQUEST['value'];
		$return = $wpdb->update( $wpdb->prefix.'sr_reservations', array( $name => $value ), array( 'id' => $pk ) );

		// RESPONSE
		if( ! $return )
			wp_send_json_error( array( 'error' => __( 'Ajax ERROR: could not save data.' ) ) );
		else
			wp_send_json_success( $return );
	}

	public static function calculate_tariff() {
		check_ajax_referer( 'cal-tariff', 'security' );
		$adult_number = (int) isset( $_GET[ 'adult_number' ] ) ? $_GET[ 'adult_number' ] : 0 ;
		$child_number = (int) isset( $_GET[ 'child_number' ] ) ? $_GET[ 'child_number' ] : 0 ;
		$room_type_id = (int) isset( $_GET[ 'room_type_id' ] ) ? $_GET[ 'room_type_id' ] : 0 ;
		$room_index = (int) isset( $_GET[ 'room_index' ] ) ? $_GET[ 'room_index' ] : 0 ;
		$asset_id = (int) isset( $_GET[ 'raid' ] ) ? $_GET[ 'raid' ] : 0 ;
		$tariff_id = (int) isset( $_GET[ 'tariff_id' ] ) ? $_GET[ 'tariff_id' ] : 0 ;
		$currency_id = solidres()->session[ 'sr_currency_id' ];
		$tax_id = solidres()->session['sr_tax_id'] ;

		$checkin = solidres()->session[ 'sr_checkin' ] ;
		$checkout = solidres()->session['sr_checkout'];
		$coupon = solidres()->session['sr_coupon'] ;

		$options = get_option( 'solidres_plugin' );
		$show_price_with_tax = $options[ 'show_price_with_tax' ]  ;
		$tariff_breakdown_net_or_gross = $show_price_with_tax == 1 ? 'net' : 'gross';

		// Get customer information
		//$user              = JFactory::getUser();
		$customer_group_id = null;
		/*if (SR_PLUGIN_USER_ENABLED)
		{
			$customerTable = JTable::getInstance('Customer', 'SolidresTable');
			$customerTable->load(array('user_id' => $user->id));
			$customer_group_id = $customerTable->customer_group_id;
		}*/

		$data = array(
			'asset_id' => $asset_id,
			'room_type_id' => $room_type_id,
			'tariff_id' => $tariff_id,
			'room_index' => $room_index,
			'adult_number' => $adult_number,
			'child_number' => $child_number,
			'show_price_with_tax' => $show_price_with_tax,
			'coupon' => $coupon,
			'tariff_breakdown_net_or_gross' => $tariff_breakdown_net_or_gross,
			'checkin' => $checkin,
			'checkout' => $checkout,
			'currency_id' => $currency_id,
			'tax_id' => $tax_id,
			'customer_group_id' => $customer_group_id,
		);
		$asset = new SR_Asset();
		wp_send_json( $asset->calculate_tariff( $data ) );
	}

	public static function reservation_process() {
		check_ajax_referer( 'process-reservation', 'security' );
		$data = $_POST[ 'srform' ];
		$step = $_POST[ 'step' ];
		$solidres_reservation = new SR_Reservation();

		switch ( $step ) {
			case 'room':
				$solidres_reservation->process_room( $data );
				break;
			case 'guestinfo':
				$solidres_reservation->process_guest_info( $data );
				break;
			default:
				break;
		}
	}

	public static function reservation_progress() {
		check_ajax_referer( 'process-reservation', 'security' );
		$next = $_GET[ 'next_step' ];
		$solidres_reservation = new SR_Reservation();
		if ( ! empty( $next ) ) {
			switch ( $next ) {
				case 'guestinfo':
					$solidres_reservation->get_html_guest_info();
					break;
				case 'confirmation':
					$solidres_reservation->get_html_confirmation();
					break;
				default:
					$response = array( 'status' => 1, 'message' => '', 'next' => '' );
					echo json_encode( $response );
					break;
			}
		}
		wp_die();
	}
}

SR_Ajax::init();