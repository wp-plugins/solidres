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

$options = get_option( 'solidres_plugin' );

$solidres_asset = new SR_Asset();
$solidres_room_type = new SR_Room_Type();
$solidres_media = new SR_Media();
$solidres_tariff = new SR_Tariff();
$solidres_currency = new SR_Currency();
$solidres_reservation = new SR_Reservation();
$currency = $solidres_currency->load( $asset->currency_id );
$asset_params = $solidres_asset->load_params( $asset->params );

solidres()->session[ 'sr_asset_params' ] = $asset_params;

wp_localize_script( 'solidres_site_script', 'solidres_text', array(
	'can_not_remove_coupon' => __( 'Can not remove coupon', 'solidres' ),
	'select_at_least_one_roomtype' => __( 'Please select at least one room type to proceed.', 'solidres' ),
	'error_child_max_age' => __( 'Ages must be between', 'solidres' ),
	'and' => __( 'and', 'solidres' ),
	'tariff_break_down' => __( 'Tariff break down', 'solidres' ),
	'sun' => __( 'Sun', 'solidres' ),
	'mon' => __( 'Mon', 'solidres' ),
	'tue' => __( 'Tue', 'solidres' ),
	'wed' => __( 'Wed', 'solidres' ),
	'thu' => __( 'Thu', 'solidres' ),
	'fri' => __( 'Fri', 'solidres' ),
	'sat' => __( 'Sat', 'solidres' ),
	'next' => __( 'Next', 'solidres' ),
	'back' => __( 'Back', 'solidres' ),
	'processing' => __( 'Processing...', 'solidres' ),
	'child' => __( 'Child', 'solidres' ),
	'child_age_selection_js' => __( 'years old', 'solidres' ),
	'child_age_selection_1_js' => __( 'year old', 'solidres' ),
	'only_1_left' => __( 'Last chance! Only 1 room left', 'solidres' ),
	'only_2_left' => __( 'Only 2 rooms left', 'solidres' ),
	'only_3_left' => __( 'Only 3 rooms left', 'solidres' ),
	'only_4_left' => __( 'Only 4 rooms left', 'solidres' ),
	'only_5_left' => __( 'Only 5 rooms left', 'solidres' ),
	'only_6_left' => __( 'Only 6 rooms left', 'solidres' ),
	'only_7_left' => __( 'Only 7 rooms left', 'solidres' ),
	'only_8_left' => __( 'Only 8 rooms left', 'solidres' ),
	'only_9_left' => __( 'Only 9 rooms left', 'solidres' ),
	'only_10_left' => __( 'Only 10 rooms left', 'solidres' ),
	'only_11_left' => __( 'Only 11 rooms left', 'solidres' ),
	'only_12_left' => __( 'Only 12 rooms left', 'solidres' ),
	'only_13_left' => __( 'Only 13 rooms left', 'solidres' ),
	'only_14_left' => __( 'Only 14 rooms left', 'solidres' ),
	'only_15_left' => __( 'Only 15 rooms left', 'solidres' ),
	'only_16_left' => __( 'Only 16 rooms left', 'solidres' ),
	'only_17_left' => __( 'Only 17 rooms left', 'solidres' ),
	'only_18_left' => __( 'Only 18 rooms left', 'solidres' ),
	'only_19_left' => __( 'Only 19 rooms left', 'solidres' ),
	'only_20_left' => __( 'Only 20 rooms left', 'solidres' ),
	'show_more_info' => __( 'More info', 'solidres' ),
	'hide_more_info' => __( 'Hide info', 'solidres' ),
	'availability_calendar_close' => __( 'Close calendar', 'solidres' ),
	'availability_calendar_view' => __( 'View calendar', 'solidres' ),
	'username_exists' => __( 'Username exists. Please choose another one.', 'solidres' ),
) );
?>

<div class="row-fluid">
	<div id="solidres" class="span12">
		<div class="reservation_asset_item clearfix">

			<?php require( 'header.php' ); ?>

			<?php require( 'roomtype.php' ); ?>

			<?php require( 'infomation.php' ); ?>

			<?php require( 'map.php' ); ?>

		</div>
	</div>
</div>