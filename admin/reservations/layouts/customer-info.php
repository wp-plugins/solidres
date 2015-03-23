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

$get_country_name = '';
$get_state_name   = '';
if ( $sr_form_data->customer_country_id != null ) {
	$get_country_name = $wpdb->get_var( $wpdb->prepare( "SELECT c.name as countryname FROM {$wpdb->prefix}sr_reservations r LEFT JOIN {$wpdb->prefix}sr_countries c ON r.customer_country_id = c.id WHERE r.id = %d", $id ) );
}
if ( $sr_form_data->customer_geo_state_id != null ) {
	$get_state_name = $wpdb->get_var( $wpdb->prepare( "SELECT s.name as statename FROM {$wpdb->prefix}sr_reservations r LEFT JOIN {$wpdb->prefix}sr_geo_states s ON r.customer_geo_state_id = s.id WHERE r.id = %d", $id ) );
}
?>

<div id="reservation_customer_info" class="postbox">
	<div class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e( 'Customer info', 'solidres' ); ?></span></h3>

	<div class="inside">
		<table class="form-table">
			<tbody>
			<tr>
				<td class="reservation_label"><?php _e( 'Customer title', 'solidres' ); ?></td>
				<td><?php echo isset( $sr_form_data->customer_title ) ? $sr_form_data->customer_title : ''; ?></td>
				<td class="reservation_label"><?php _e( 'Company', 'solidres' ); ?></td>
				<td><?php echo isset( $sr_form_data->customer_company ) ? $sr_form_data->customer_company : ''; ?></td>
			</tr>
			<tr>
				<td class="reservation_label"><?php _e( 'Firstname', 'solidres' ); ?></td>
				<td><?php echo isset( $sr_form_data->customer_firstname ) ? $sr_form_data->customer_firstname : ''; ?></td>
				<td class="reservation_label"><?php _e( 'Address 1', 'solidres' ); ?></td>
				<td><?php echo isset( $sr_form_data->customer_address1 ) ? $sr_form_data->customer_address1 : ''; ?></td>
			</tr>
			<tr>
				<td class="reservation_label"><?php _e( 'Lastname', 'solidres' ); ?></td>
				<td><?php echo isset( $sr_form_data->customer_lastname ) ? $sr_form_data->customer_lastname : ''; ?></td>
				<td class="reservation_label"><?php _e( 'Address 2', 'solidres' ); ?></td>
				<td><?php echo isset( $sr_form_data->customer_address2 ) ? $sr_form_data->customer_address2 : ''; ?></td>
			</tr>
			<tr>
				<td class="reservation_label"><?php _e( 'Email', 'solidres' ); ?></td>
				<td><?php echo isset( $sr_form_data->customer_email ) ? $sr_form_data->customer_email : ''; ?></td>
				<td class="reservation_label"><?php _e( 'City', 'solidres' ); ?></td>
				<td><?php echo isset( $sr_form_data->customer_city ) ? $sr_form_data->customer_city : ''; ?></td>
			</tr>
			<tr>
				<td class="reservation_label"><?php _e( 'Phone', 'solidres' ); ?></td>
				<td><?php echo isset( $sr_form_data->customer_phonenumber ) ? $sr_form_data->customer_phonenumber : ''; ?></td>
				<td class="reservation_label"><?php _e( 'Zip code', 'solidres' ); ?></td>
				<td><?php echo isset( $sr_form_data->customer_zipcode ) ? $sr_form_data->customer_zipcode : ''; ?></td>
			</tr>
			<tr>
				<td class="reservation_label"><?php _e( 'Mobile phone', 'solidres' ); ?></td>
				<td><?php echo isset( $sr_form_data->customer_phonenumber ) ? $sr_form_data->customer_phonenumber : ''; ?></td>
				<td class="reservation_label"><?php _e( 'Country', 'solidres' ); ?></td>
				<td><?php echo $get_country_name; ?></td>
			</tr>
			<tr>
				<td class="reservation_label"><?php _e( 'VAT Number', 'solidres' ); ?></td>
				<td><?php echo isset( $sr_form_data->customer_VAT_number ) ? $sr_form_data->customer_VAT_number : ''; ?></td>
				<td class="reservation_label"><?php _e( 'State/Country', 'solidres' ); ?></td>
				<td><?php echo $get_state_name; ?></td>
			</tr>
			</tbody>
		</table>
	</div>
</div>