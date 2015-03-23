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

add_action( 'admin_menu', 'add_menu_assets' );
function add_menu_assets() {
	add_menu_page( 'Solidres Assets', 'Assets', 'edit_users', 'sr-categories', 'sr_categories', 'dashicons-admin-home', '25.26' );
	add_submenu_page( 'sr-categories', "Categories", "Categories", 'edit_users', 'sr-categories', 'sr_categories' );
	add_submenu_page( 'sr-categories', 'Assets', 'Assets', 'edit_users', 'sr-assets', 'sr_assets' );
	add_submenu_page( 'sr-categories', 'Room types', 'Room types', 'edit_users', 'sr-room-types', 'sr_room_types' );
	add_submenu_page( 'sr-assets', 'Add new asset', 'Add new asset', 'edit_users', 'sr-add-new-asset', 'sr_add_new_asset' );
	add_submenu_page( 'sr-assets', 'Add new category', 'Add new category', 'edit_users', 'sr-add-new-category', 'sr_add_new_category' );
	add_submenu_page( 'sr-assets', 'Add new room type', 'Add new room type', 'edit_users', 'sr-add-new-room-type', 'sr_add_new_room_type' );
}

add_action( 'admin_menu', 'add_menu_customers' );
function add_menu_customers() {
	$users = 'solidres-users/solidres-users.php';
	$check_plugin_users = solidres_check_plugin( $users );
	if ( $check_plugin_users['status'] == 2 ) {
		add_menu_page( 'Solidres Customers', 'Customers', 'edit_users', 'sr-customers', 'sr_customers_notinstall', 'dashicons-groups', '25.27' );
	} else if ( $check_plugin_users['status'] == 0 ) {
		add_menu_page( 'Solidres Customers', 'Customers', 'edit_users', 'sr-customers', 'sr_customers_inactive', 'dashicons-groups', '25.27' );
	} else {
		add_users_page( 'User Groups', 'User Groups', 'edit_users', 'sr-user-groups', 'sr_user_groups' );
		add_submenu_page( 'sr-user-group', 'Add new user group', 'Add new user group', 'edit_users', 'sr-add-new-user-group', 'sr_add_new_user_group' );
	}
}

add_action( 'admin_menu', 'add_menu_reservations' );
function add_menu_reservations() {
	add_menu_page( 'Solidres Reservations', 'Reservations', 'edit_users', 'sr-reservations', 'sr_reservations', 'dashicons-admin-network', '25.28' );
}

add_action( 'admin_menu', 'add_menu_reservations_user' );
function add_menu_reservations_user() {
	$users = 'solidres-users/solidres-users.php';
	$check_plugin_users = solidres_check_plugin( $users );
	if ( $check_plugin_users['status'] == 1 ) {
		if ( current_user_can( 'solidres_user' ) ) {
			add_menu_page( 'My Reservations', 'My Reservations', 'solidres_user', 'my-reservations', 'my_reservations', 'dashicons-admin-network', '25.29' );
		}
	}
}

add_action( 'admin_menu', 'add_menu_coupons_extras' );
function add_menu_coupons_extras() {
	add_menu_page( 'Solidres Coupons & Extras', 'Coupons & Extras', 'edit_users', 'sr-coupons', 'sr_coupons', 'dashicons-list-view', '25.30' );
	add_submenu_page( 'sr-coupons', 'Coupons', 'Coupons', 'edit_users', 'sr-coupons', 'sr_coupons' );
	add_submenu_page( 'sr-coupons', 'Extras', 'Extras', 'edit_users', 'sr-extras', 'sr_extras' );
	add_submenu_page( 'sr-extras', 'Add new coupon', 'Add new coupon', 'edit_users', 'sr-add-new-coupon', 'sr_add_new_coupon' );
	add_submenu_page( 'sr-extras', 'Add new extra', 'Add new extra', 'edit_users', 'sr-add-new-extra', 'sr_add_new_extra' );
}

add_action( 'admin_menu', 'add_menu_system' );
function add_menu_system() {
	$limit_booking = 'solidres-limitbooking/solidres-limitbooking.php';
	$discount      = 'solidres-discount/solidres-discount.php';
	$hub = 'solidres-hub/solidres-hub.php';
	add_menu_page( 'Solidres System', 'System', 'edit_users', 'sr-currencies', 'sr_currencies', 'dashicons-admin-generic', '25.31' );
	add_submenu_page( 'sr-currencies', 'Currencies', 'Currencies', 'edit_users', 'sr-currencies', 'sr_currencies' );
	add_submenu_page( 'sr-currencies', 'Install sample data', 'Install sample data', 'edit_users', 'sr-install-sample-data', 'sr_install_sample_data' );
	add_submenu_page( 'sr-currencies', 'Countries', 'Countries', 'edit_users', 'sr-countries', 'sr_countries' );
	add_submenu_page( 'sr-currencies', 'States', 'States', 'edit_users', 'sr-states', 'sr_states' );
	add_submenu_page( 'sr-currencies', 'Taxes', 'Taxes', 'edit_users', 'sr-taxes', 'sr_taxes' );
	add_submenu_page( 'sr-currencies', 'Employees', 'Employees', 'edit_users', 'users.php' );
	$check_plugin_limitbooking = solidres_check_plugin( $limit_booking );
	$check_plugin_discount = solidres_check_plugin( $discount );
	$check_plugin_hub = solidres_check_plugin( $hub );
	if ( $check_plugin_limitbooking['status'] == 2 ) {
		add_submenu_page( 'sr-currencies', 'Limit bookings', 'Limit bookings', 'edit_users', 'sr-limit-bookings', 'sr_limit_bookings_notinstall' );
	} else if ( $check_plugin_limitbooking['status'] == 0 ) {
		add_submenu_page( 'sr-currencies', 'Limit bookings', 'Limit bookings', 'edit_users', 'sr-limit-bookings', 'sr_limit_bookings_inactive' );
	} else {
		add_submenu_page( 'sr-currencies', 'Limit bookings', 'Limit bookings', 'edit_users', 'sr-limit-bookings', 'sr_limit_bookings' );
		add_submenu_page( 'sr-countries', 'Add new limit booking', 'Add new limit booking', 'edit_users', 'sr-add-new-limit-booking', 'sr_add_new_limit_booking' );
	}
	if ( $check_plugin_discount['status'] == 1 ) {
		add_submenu_page( 'sr-currencies', 'Discounts', 'Discounts', 'edit_users', 'sr-discounts', 'sr_discounts' );
		add_submenu_page( 'sr-countries', 'Add new discount', 'Add new discount', 'edit_users', 'sr-add-new-discount', 'sr_add_new_discount' );
	}
	if ( $check_plugin_hub['status'] == 2 ) {
		add_submenu_page( 'sr-currencies', 'Facilities', 'Facilities', 'edit_users', 'sr-facilities', 'sr_facilities_notinstall' );
		add_submenu_page( 'sr-currencies', 'Themes', 'Themes', 'edit_users', 'sr-themes', 'sr_themes_notinstall' );
	} else if ( $check_plugin_hub['status'] == 0 ) {
		add_submenu_page( 'sr-currencies', 'Facilities', 'Facilities', 'edit_users', 'sr-facilities', 'sr_facilities_inactive' );
		add_submenu_page( 'sr-currencies', 'Themes', 'Themes', 'edit_users', 'sr-themes', 'sr_themes_inactive' );
	} else {
		add_submenu_page( 'sr-currencies', 'Facilities', 'Facilities', 'edit_users', 'sr-facilities', 'sr_facilities' );
		add_submenu_page( 'sr-currencies', 'Themes', 'Themes', 'edit_users', 'sr-themes', 'sr_themes' );
		add_submenu_page( 'sr-countries', 'Add new facility', 'Add new facility', 'edit_users', 'sr-add-new-facility', 'sr_add_new_facility' );
		add_submenu_page( 'sr-countries', 'Add new theme', 'Add new theme', 'edit_users', 'sr-add-new-theme', 'sr_add_new_theme' );
	}
	add_submenu_page( 'sr-currencies', 'System', 'System', 'edit_users', 'sr-systems', 'sr_systems' );
	add_submenu_page( 'sr-currencies', 'Solidres Settings', 'Settings', 'edit_users', 'sr-options', 'sr_options' );
	add_submenu_page( 'sr-countries', 'Add new currency', 'Add new currency', 'edit_users', 'sr-add-new-currency', 'sr_add_new_currency' );
	add_submenu_page( 'sr-countries', 'Add new country', 'Add new country', 'edit_users', 'sr-add-new-country', 'sr_add_new_country' );
	add_submenu_page( 'sr-countries', 'Add new state', 'Add new state', 'edit_users', 'sr-add-new-state', 'sr_add_new_state' );
	add_submenu_page( 'sr-countries', 'Add new tax', 'Add new tax', 'edit_users', 'sr-add-new-tax', 'sr_add_new_tax' );
}

add_action( 'admin_init', 'add_admin_menu_separator' );
function add_admin_menu_separator() {
	global $menu;
	$sep_positions = array( '25.25' );
	foreach ( $sep_positions as $pos ) {
		$menu[ $pos ] = array(
			0 => '',
			1 => 'read',
			2 => 'separator' . $pos,
			3 => '',
			4 => 'wp-menu-separator',
		);
	}
	ksort( $menu );
}