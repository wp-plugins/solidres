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

function solidres_uninstall(){
	global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS
		{$wpdb->prefix}sr_categories,
		{$wpdb->prefix}sr_reservation_extra_xref,
		{$wpdb->prefix}sr_reservation_room_details,
		{$wpdb->prefix}sr_config_data,
		{$wpdb->prefix}sr_reservation_notes,
		{$wpdb->prefix}sr_room_type_fields,
		{$wpdb->prefix}sr_reservation_room_extra_xref,
		{$wpdb->prefix}sr_room_type_extra_xref,
		{$wpdb->prefix}sr_room_type_coupon_xref,
		{$wpdb->prefix}sr_customer_fields,
		{$wpdb->prefix}sr_reservation_asset_fields,
		{$wpdb->prefix}sr_media_roomtype_xref,
		{$wpdb->prefix}sr_media_reservation_assets_xref,
		{$wpdb->prefix}sr_reservation_room_xref,
		{$wpdb->prefix}sr_rooms,
		{$wpdb->prefix}sr_extras,
		{$wpdb->prefix}sr_media,
		{$wpdb->prefix}sr_reservations,
		{$wpdb->prefix}sr_tariff_details,
		{$wpdb->prefix}sr_tariffs,
		{$wpdb->prefix}sr_coupons,
		{$wpdb->prefix}sr_room_types,
		{$wpdb->prefix}sr_reservation_assets,
		{$wpdb->prefix}sr_taxes,
		{$wpdb->prefix}sr_currencies,
		{$wpdb->prefix}sr_customer_groups,
		{$wpdb->prefix}sr_geo_states,
		{$wpdb->prefix}sr_countries"
	);

	delete_option( 'solidres_db_version' );
	delete_option( 'solidres_plugin' );
	delete_option( 'solidres_currency' );
	delete_option( 'solidres_invoice' );
	delete_option( 'solidres_pages' );
	delete_option( 'solidres_tools' );
}
