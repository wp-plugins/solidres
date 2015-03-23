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
$get_currency_id_asset = '';
$get_currency_code     = '';
if ( isset( $sr_form_data->reservation_asset_id ) ) {
	$get_currency_id_asset = $wpdb->get_var( $wpdb->prepare( "SELECT currency_id FROM {$wpdb->prefix}sr_reservation_assets WHERE id = %d", $sr_form_data->reservation_asset_id ) );
	$get_currency_code     = $wpdb->get_var( $wpdb->prepare( "SELECT currency_code FROM {$wpdb->prefix}sr_currencies WHERE id = %d", $get_currency_id_asset ) );
}
if ( isset( $id ) ) {
	$solidres_tariff = new SR_Tariff();
	$standard_tariff = $solidres_tariff->load_by_room_type_id( $id, true, ARRAY_A );
}
?>
<div id="roomtype_general_infomation" class="postbox">
	<div class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e( 'General infomartion', 'solidres' ); ?></span></h3>

	<div class="inside">
		<table class="form-table">
			<tbody>
			<tr>
				<td class="first"><label for="srform_name"
				                         title="<?php _e( 'The name of your room type. For example: "Double Room" or "Queen Room"', 'solidres' ); ?>"><?php _e( 'Name', 'solidres' ); ?>
						<span class="required">*</span></label></td>
				<td><input type="text" name="srform[name]" size="30"
				           value="<?php echo isset( $sr_form_data->name ) ? $sr_form_data->name : '' ?>"
				           id="srform_name" placeholder="Enter room types name" required></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_alias"
				                         title="<?php _e( 'Alias is used in Search Engine Friendly URL.', 'solidres' ); ?>"><?php _e( 'Slug', 'solidres' ); ?></label>
				</td>
				<td><input type="text" name="srform[alias]" size="30"
				           value="<?php echo isset( $sr_form_data->alias ) ? $sr_form_data->alias : '' ?>"
				           id="srform_alias" placeholder="Enter room types slug"></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_asset"
				                         title="<?php _e( 'Select the reservation asset that this room type belongs to.', 'solidres' ); ?>"><?php _e( 'Asset', 'solidres' ); ?>
						<span class="required">*</span></label></td>
				<td>
					<select id="srform_asset" name="srform[reservation_asset_id]"
					        class="srform_selected select_reservation_asset_id" required>
						<option value=""><?php _e( 'Select Asset', 'solidres' ); ?></option>
						<?php echo isset( $sr_form_data->reservation_asset_id ) ? SR_Helper::get_reservation_asset_selected( $sr_form_data->reservation_asset_id ) : SR_Helper::get_reservation_asset_selected(); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label
						title="<?php _e( 'Set the occupancy of your room type. (number of adults and children)', 'solidres' ); ?>"><?php _e( 'Room occupancy', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[occupancy_adult]" class="srform_selected">
						<?php echo isset( $sr_form_data->occupancy_adult ) ? SR_Helper::get_occupancy_adult_selected( $sr_form_data->occupancy_adult ) : SR_Helper::get_occupancy_adult_selected(); ?>
					</select> <?php _e( 'Adult', 'solidres' ); ?>
					<select name="srform[occupancy_child]" class="srform_selected">
						<?php echo isset( $sr_form_data->occupancy_child ) ? SR_Helper::get_occupancy_child_selected( $sr_form_data->occupancy_child ) : SR_Helper::get_occupancy_child_selected(); ?>
					</select> <?php _e( 'Child(ren)', 'solidres' ); ?>
				</td>
			</tr>
			<tr>
				<td class="first"><label
						title="<?php _e( 'The standard tariff of your room type, it is set for each day of week. For more flexible tariff like per person per night tariff, use Complex Tariff plugin', 'solidres' ); ?>"><?php _e( 'Standard tariff (' . $get_currency_code . ')', 'solidres' ); ?>
						<span class="required">*</span></label></td>
				<td><?php echo isset( $id ) ? SR_Helper::get_standard_tariff( $id ) : SR_Helper::get_standard_tariff(); ?></td>
				<div class="clr"></div>
			</tr>
			<tr>
				<td class="first"><label for="srform_standard_tariff_title"
				                         title="<?php _e( 'Enter the Standard tariff title which will be shown in front end.', 'solidres' ); ?>"><?php _e( 'Standard tariff title', 'solidres' ); ?></label>
				</td>
				<td><input type="text" name="srform[standard_tariff_title]" size="30"
				           value="<?php echo isset( $standard_tariff[0]['title'] ) ? $standard_tariff[0]['title'] : '' ?>"
				           id="srform_standard_tariff_title" placeholder="Enter standard tariff title"></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_standard_tariff_description"
				                         title="<?php _e( 'Enter Standard tariff description which will be shown in the front end.', 'solidres' ); ?>"><?php _e( 'Standard tariff description', 'solidres' ); ?></label>
				</td>
				<td><input type="text" name="srform[standard_tariff_description]" size="30"
				           value="<?php echo isset( $standard_tariff[0]['description'] ) ? $standard_tariff[0]['description'] : '' ?>"
				           id="srform_standard_tariff_description" placeholder="Enter standard tariff description"></td>
			</tr>
			<tr>
				<td class="first"><label
						title="<?php _e( 'Select coupons that apply to this room type.', 'solidres' ); ?>"><?php _e( 'Coupons', 'solidres' ); ?></label>
				</td>
				<td>
					<div
						class="srform_coupon_id"><?php echo isset( $sr_form_data->reservation_asset_id ) ? SR_Helper::get_coupons_group_selected( $sr_form_data->reservation_asset_id, $id ) : SR_Helper::get_coupons_group_selected(); ?></div>
				</td>
			</tr>
			<tr>
				<td class="first"><label
						title="<?php _e( 'Select extras that apply to this room type.', 'solidres' ); ?>"><?php _e( 'Extra', 'solidres' ); ?></label>
				</td>
				<td>
					<div
						class="srform_extra_id"><?php echo isset( $sr_form_data->reservation_asset_id ) ? SR_Helper::get_extras_group_selected( $sr_form_data->reservation_asset_id, $id ) : SR_Helper::get_extras_group_selected(); ?></div>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_state"
				                         title="<?php _e( 'The state of room type', 'solidres' ); ?>"><?php _e( 'State', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[state]" class="srform_selected" id="srform_state">
						<option value="0" <?php if ( isset( $sr_form_data->state ) ) {
							echo $sr_form_data->state == 0 ? 'selected' : '';
						} ?> ><?php _e( 'Unpublished', 'solidres' ); ?></option>
						<option value="1" <?php if ( isset( $sr_form_data->state ) ) {
							echo $sr_form_data->state == 1 ? 'selected' : '';
						} ?> ><?php _e( 'Published', 'solidres' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><?php _e( 'Description', 'solidres' ); ?></td>
				<td><textarea class="srform_textarea" rows="5" name="srform[description]"
				              id="srform_description"><?php echo isset( $sr_form_data->description ) ? $sr_form_data->description : '' ?></textarea>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>