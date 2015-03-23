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

class SR_Form_Handler {

	public static function init() {
		add_action( 'init', array( __CLASS__, 'save_reservation' ), 20 );
	}

	public static function save_reservation() {
		if ( ! empty( $_POST['save_reservation'] ) ) {
			$solidres_reservation = new SR_Reservation();

			$room_data = solidres()->session[ 'sr_room' ]->toArray();
			$reservation_data = array();
			if ( is_array( $room_data ) ) {
				$reservation_data = array_merge( $reservation_data, $room_data );
			}

			$guest_data = solidres()->session['sr_guest']->toArray();
			if ( is_array( $guest_data ) ) {
				$reservation_data = array_merge( $reservation_data, $guest_data );
			}

			$cost_data = solidres()->session['sr_cost']->toArray();
			if ( is_array( $cost_data ) ) {
				$reservation_data = array_merge( $reservation_data, $cost_data );
			}

			$discount_data = isset( solidres()->session[ 'sr_discount' ] ) ? solidres()->session[ 'sr_discount' ]->toArray() : NULL;
			if ( is_array( $discount_data ) ) {
				$reservation_data = array_merge( $reservation_data, $discount_data );
			}

			$coupon_data = isset( solidres()->session[ 'sr_coupon' ] ) ? solidres()->session[ 'sr_coupon' ]->toArray() : NULL;
			if ( is_array( $coupon_data ) ) {
				$reservation_data = array_merge( $reservation_data, $coupon_data );
			}

			$deposit_data = isset( solidres()->session[ 'sr_deposit' ] ) ? solidres()->session[ 'sr_deposit' ]->toArray() : NULL;
			if ( is_array( $deposit_data ) ) {
				$reservation_data = array_merge( $reservation_data, $deposit_data );
			}

			$reservation_data['total_extra_price']          = $reservation_data['total_extra_price_per_room'] + $reservation_data['total_extra_price_per_booking'];
			$reservation_data['total_extra_price_tax_incl'] = $reservation_data['total_extra_price_tax_incl_per_room'] + $reservation_data['total_extra_price_tax_incl_per_booking'];
			$reservation_data['total_extra_price_tax_excl'] = $reservation_data['total_extra_price_tax_excl_per_room'] + $reservation_data['total_extra_price_tax_excl_per_booking'];

			$solidres_asset = new SR_Asset();
			$asset = $solidres_asset->load( $reservation_data['raid'] );
			$reservation_data['reservation_asset_name'] = $asset->name;
			$reservation_data['reservation_asset_id'] = $reservation_data['raid'];
			$reservation_data['currency_id'] = solidres()->session[ 'sr_currency_id' ];
			$reservation_data['currency_code'] = solidres()->session[ 'sr_currency_code' ];
			$reservation_data['deposit_required'] = $asset->deposit_required;
			$reservation_data['deposit_is_percentage'] = $asset->deposit_is_percentage;
			$reservation_data['deposit_amount'] = $asset->deposit_amount;
			//$reservation_data['tax_id'] = $asset->tax_id;

			$reservation_data['checkin'] = solidres()->session[ 'sr_checkin' ];

			$reservation_data['checkout'] = solidres()->session[ 'sr_checkout' ];
			$reservation_data['coupon_id'] = NULL;
			$reservation_data['created_date'] = date( 'Y-m-d H:i:s', time());
			$reservation_data['modified_date'] = date( 'Y-m-d H:i:s', time());
			$reservation_data['created_by'] = 0;
			$reservation_data['modified_by'] = 0;

			$result = $solidres_reservation->save( $reservation_data );

			if ( false == $result ) {

			} else {

				// Send confirmation emails
				$solidres_reservation = new SR_Reservation();
				$solidres_room_type = new SR_Room_Type();
				$solidres_asset = new SR_Asset();
				$subject = array();
				$body = array();
				//$emailFormat = $this->solidresConfig->get('email_format', 'text/html');
				$tzoffset = get_option( 'timezone_string' );
				$tzoffset = $tzoffset == '' ? 'UTC' : $tzoffset;
				$timezone = new DateTimeZone( $tzoffset );
				$dateFormat = get_option( 'date_format' );;
				$saved_reservation_id = solidres()->session[ 'sr_saved_reservation_id' ];
				$saved_reservation_data = $solidres_reservation->load( $saved_reservation_id );
				$reserved_room_details = $solidres_reservation->load_reserved_rooms( $saved_reservation_id );
				$reserved_extras = $solidres_reservation->load_extras( $saved_reservation_id );
				$number_of_nights = $solidres_room_type->calculateDateDiff( $saved_reservation_data->checkin, $saved_reservation_data->checkout );
				$asset = $solidres_asset->load( $saved_reservation_data->reservation_asset_id );
				$asset_custom_fields = $solidres_asset->load_custom_fields( $saved_reservation_data->reservation_asset_id );

				$hotelEmail = $asset->email;
				$hotelName = $asset->name;
				$customerEmail = $saved_reservation_data->customer_email;
				$hotelEmailList[] = $hotelEmail;
				// If User plugin is installed and enabled
				/*if (SR_PLUGIN_USER_ENABLED && !is_null($asset->partner_id))
				{
					$modelCustomer = $this->getModel('Customer', 'SolidresModel', array('ignore_request' => true));
					$customer = $modelCustomer->getItem($asset->partner_id);
					if (!empty($customer->email) && $customer->email != $hotelEmail )
					{
						$hotelEmailList[] = $customer->email;
					}
				}*/

				$subject[ $customerEmail ] = __( 'Your reservation is completed', 'solidres' );
				$subject[ $hotelEmail ] = __( 'New reservation', 'solidres' );

				$bankWireInstructions = array();
				if ($saved_reservation_data->payment_method_id == 'bankwire')
				{
					$solidres_payment_config_data = new SR_Config( array( 'scope_id' => $saved_reservation_data->reservation_asset_id ) );
					$bankWireInstructions['account_name'] = $solidres_payment_config_data->get('payments/bankwire/bankwire_accountname');
					$bankWireInstructions['account_details'] = $solidres_payment_config_data->get('payments/bankwire/bankwire_accountdetails');
				}

				// We are free to choose between the inliner version and noninliner version
				// Inliner version is hard to maintain but it displays well in gmail (web).
				$reservationCompleteCustomerEmailTemplate = WP_PLUGIN_DIR . '/solidres/libraries/layouts/emails/reservation_complete_customer_html_inliner.php';
				$reservationCompleteOwnerEmailTemplate = WP_PLUGIN_DIR . '/solidres/libraries/layouts/emails/reservation_complete_owner_html_inliner.php';

				// Prepare some currency data to be showed
				$baseCurrency = new SR_Currency(0, $saved_reservation_data->currency_id);
				$subTotal = clone $baseCurrency;
				$subTotal->setValue($saved_reservation_data->total_price_tax_excl);
				$tax = clone $baseCurrency;
				$tax->setValue($saved_reservation_data->total_price_tax_incl - $saved_reservation_data->total_price_tax_excl);
				$totalExtraPriceTaxExcl = clone $baseCurrency;
				$totalExtraPriceTaxExcl->setValue($saved_reservation_data->total_extra_price_tax_excl);
				$extraTax = clone $baseCurrency;
				$extraTax->setValue($saved_reservation_data->total_extra_price_tax_incl - $saved_reservation_data->total_extra_price_tax_excl);
				$grandTotal = clone $baseCurrency;
				$grandTotal->setValue($saved_reservation_data->total_price_tax_incl + $saved_reservation_data->total_extra_price);
				$depositAmount = clone $baseCurrency;
				$depositAmount->setValue(isset($saved_reservation_data->deposit_amount) ? $saved_reservation_data->deposit_amount : 0);

				$displayData = array(
					'reservation' => $saved_reservation_data,
					'reserved_room_details' => $reserved_room_details,
					'reserved_extras' => $reserved_extras,
					'sub_total' => $subTotal->format(),
					'tax' => $tax->format(),
					'total_extra_price_tax_excl' => $totalExtraPriceTaxExcl->format(),
					'extra_tax' => $extraTax->format(),
					'grand_total' => $grandTotal->format(),
					'number_of_nights' => $number_of_nights,
					'deposit_amount' => $depositAmount->format(),
					'bankwire_instructions' => $bankWireInstructions,
					'asset' => $asset,
					'asset_custom_fields' => $asset_custom_fields->create_array_group(),
					'date_format' => $dateFormat,
					'timezone' => $timezone,
					'base_currency' => $baseCurrency
				);

				if ( file_exists( $reservationCompleteCustomerEmailTemplate ) ) {
					ob_start();
					include $reservationCompleteCustomerEmailTemplate;
					$body[$customerEmail] = ob_get_contents();
					ob_end_clean();
				}

				$headers[] = "From: {$asset->name}  <{$asset->email}>";
				add_filter( 'wp_mail_content_type', 'solidres_set_html_content_type' );
				wp_mail( $customerEmail, $subject[ $customerEmail ], $body[$customerEmail], $headers );
				remove_filter( 'wp_mail_content_type', 'solidres_set_html_content_type' );

				/*if(SR_PLUGIN_INVOICE_ENABLED)
				{
					// This is a workaroud for this Joomla's bug  https://github.com/joomla/joomla-cms/issues/3451
					// When it is fixed, update this logic
					if (file_exists(JPATH_BASE . '/templates/' . JFactory::getApplication()->getTemplate() . '/html/layouts/com_solidres/emails/reservation_complete_customer_pdf.php' ))
					{
						$reservationCompleteCustomerPdfTemplate = new JLayoutFile('emails.reservation_complete_customer_pdf');
					}
					else
					{
						$reservationCompleteCustomerPdfTemplate = new JLayoutFile(
							'emails.reservation_complete_customer_pdf',
							JPATH_ROOT . '/plugins/solidres/invoice/layouts'
						);
					}

					$pdf = NULL;
					$pdf = $reservationCompleteCustomerPdfTemplate->render($displayData);

					if($this->solidresConfig->get('enable_pdf_attachment',1) == 1)
					{
						$this->getPDFAttachment($mail, $pdf, $savedReservationId, $savedReservationData->code);
					}
				}*/

				if ( file_exists( $reservationCompleteOwnerEmailTemplate ) ) {
					ob_start();
					include $reservationCompleteOwnerEmailTemplate;
					$body[ $hotelEmail ] = ob_get_contents();
					ob_end_clean();
				}

				$headers[] = "From: {$asset->name} <{$asset->email}>";
				add_filter( 'wp_mail_content_type', 'solidres_set_html_content_type' );
				wp_mail( $hotelEmailList, $subject[ $hotelEmail ], $body[ $hotelEmail ], $headers );
				remove_filter( 'wp_mail_content_type', 'solidres_set_html_content_type' );


				$options = get_option( 'solidres_pages' );
				$return_url = get_permalink( $options[ 'reservationcompleted' ] );
				wp_redirect(
					apply_filters( 'solidres_get_final_page', $return_url )
				);
				exit;
			}
		}
	}
}

SR_Form_Handler::init();