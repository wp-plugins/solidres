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
} ?>

<div id=extra_general_infomation" class="postbox">
	<div class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e( 'General infomartion', 'solidres' ); ?></span></h3>

	<div class="inside">
		<table class="form-table">
			<tbody>
			<tr>
				<td class="first"><label for="srform_name" title=""><?php _e( 'Extra name', 'solidres' ); ?> <span
							class="required">*</span></label></td>
				<td><input type="text" name="srform[name]" size="30"
				           value="<?php echo isset( $sr_form_data->name ) ? $sr_form_data->name : ''; ?>"
				           id="srform_name" required></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_asset"
				                         title="<?php _e( 'Enter the name of your Extra/Services. For example: Airport pickup or Spa.', 'solidres' ); ?>"><?php _e( 'Reservation Asset', 'solidres' ); ?>
						<span class="required">*</span></label></td>
				<td>
					<select name="srform[reservation_asset_id]"
					        class="srform_selected srform_select_extra_reservation_asset_id" id="srform_asset" required>
						<option value=""><?php _e( 'Reservation Asset', 'solidres' ); ?></option>
						<?php echo isset( $sr_form_data->reservation_asset_id ) ? SR_Helper::get_reservation_asset_selected( $sr_form_data->reservation_asset_id ) : SR_Helper::get_reservation_asset_selected(); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_tax"
				                         title="<?php _e( 'Select the tax that will apply to this extra item. The tax list is depend on the selected reservation asset above. Please select a reservation asset first and the tax list will be displayed accordingly.', 'solidres' ); ?>"><?php _e( 'Tax', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[tax_id]" class="srform_selected" id="srform_tax">
						<option value=""><?php _e( 'Select Tax', 'solidres' ); ?></option>
						<?php echo isset( $sr_form_data->tax_id ) ? SR_Helper::get_tax_selected( $sr_form_data->tax_id ) : SR_Helper::get_tax_selected(); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_state"
				                         title="<?php _e( 'Select the state of this Extra/Service, only published Extra/Service can be used.', 'solidres' ); ?>"><?php _e( 'State', 'solidres' ); ?></label>
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
				<td class="first"><label for="srform_mandatory"
				                         title="<?php _e( 'Mandatory extra item will be always selected and the guests are not able to change it', 'solidres' ); ?>"><?php _e( 'Mandatory', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[mandatory]" class="srform_selected" id="srform_mandatory">
						<option value="0" <?php if ( isset( $sr_form_data->mandatory ) ) {
							echo $sr_form_data->mandatory == 0 ? 'selected' : '';
						} ?> ><?php _e( 'No', 'solidres' ); ?></option>
						<option value="1" <?php if ( isset( $sr_form_data->mandatory ) ) {
							echo $sr_form_data->mandatory == 1 ? 'selected' : '';
						} ?> ><?php _e( 'Yes', 'solidres' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_charge_type"
				                         title="<?php _e( 'Select the charge type of this extra item. The FREE version supports 2 basic charge types: Per booking and Per Room. With Advanced Extra plugin, 5 more charge types will be available for choosing. If you choose Per person charge type, you need to enter values into fields Price Adult and Price Children.', 'solidres' ); ?>"><?php _e( 'Charge type', 'solidres' ); ?></label>
				</td>
				<td>
					<?php
					$advancedextra = 'solidres-advancedextra/solidres-advancedextra.php';
					$check_plugin_advancedextra = solidres_check_plugin( $advancedextra );
					?>
					<select name="srform[charge_type]" class="srform_selected" id="srform_charge_type">
						<option value="0" <?php if ( isset( $sr_form_data->charge_type ) ) {
							echo $sr_form_data->charge_type == 0 ? 'selected' : '';
						} ?> ><?php _e( 'Per room', 'solidres' ); ?></option>
						<option value="1" <?php if ( isset( $sr_form_data->charge_type ) ) {
							echo $sr_form_data->charge_type == 1 ? 'selected' : '';
						} ?> ><?php _e( 'Per booking', 'solidres' ); ?></option>

						<?php if ( $check_plugin_advancedextra['status'] == 1 ) { ?>
							<option value="2" <?php if ( isset( $sr_form_data->charge_type ) ) {
								echo $sr_form_data->charge_type == 2 ? 'selected' : '';
							} ?> ><?php _e( 'Per booking per night', 'solidres' ); ?></option>
							<option value="3" <?php if ( isset( $sr_form_data->charge_type ) ) {
								echo $sr_form_data->charge_type == 3 ? 'selected' : '';
							} ?> ><?php _e( 'Per booking per person', 'solidres' ); ?></option>
							<option value="4" <?php if ( isset( $sr_form_data->charge_type ) ) {
								echo $sr_form_data->charge_type == 4 ? 'selected' : '';
							} ?> ><?php _e( 'Per room per night', 'solidres' ); ?></option>
							<option value="5" <?php if ( isset( $sr_form_data->charge_type ) ) {
								echo $sr_form_data->charge_type == 5 ? 'selected' : '';
							} ?> ><?php _e( 'Per room per person', 'solidres' ); ?></option>
							<option value="6" <?php if ( isset( $sr_form_data->charge_type ) ) {
								echo $sr_form_data->charge_type == 6 ? 'selected' : '';
							} ?> ><?php _e( 'Per room per person per night', 'solidres' ); ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_max_quantity"
				                         title="<?php _e( 'Enter the maximum extra quantity that can be selected in the reservation process.', 'solidres' ); ?>"><?php _e( 'Max Quantity', 'solidres' ); ?></label>
				</td>
				<td><input type="number" name="srform[max_quantity]" size="30"
				           value="<?php echo isset( $sr_form_data->max_quantity ) ? $sr_form_data->max_quantity : ''; ?>"
				           id="srform_max_quantity"></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_daily_chargable"
				                         title="<?php _e( 'Select No if this Extra/Service is free.', 'solidres' ); ?>"><?php _e( 'Chargable', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[daily_chargable]" class="srform_selected" id="srform_daily_chargable">
						<option value="0" <?php if ( isset( $sr_form_data->daily_chargable ) ) {
							echo $sr_form_data->daily_chargable == 0 ? 'selected' : '';
						} ?> ><?php _e( 'No', 'solidres' ); ?></option>
						<option value="1" <?php if ( isset( $sr_form_data->daily_chargable ) ) {
							echo $sr_form_data->daily_chargable == 1 ? 'selected' : '';
						} ?> ><?php _e( 'Yes', 'solidres' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_price"
				                         title="<?php _e( 'Enter the price of this Extra/Service. The currency of Reservation Asset will apply here.', 'solidres' ); ?>"><?php _e( 'Price', 'solidres' ); ?></label>
				</td>
				<td><input type="number" name="srform[price]" size="30"
				           value="<?php echo isset( $sr_form_data->price ) ? $sr_form_data->price : ''; ?>"
				           id="srform_price"></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_description"
				                         title="<?php _e( 'Enter some text to decribe your Extra/Service', 'solidres' ); ?>"><?php _e( 'Description', 'solidres' ); ?></label>
				</td>
				<td><textarea class="srform_textarea" rows="5" name="srform[description]"
				              id="srform_description"><?php echo isset( $sr_form_data->description ) ? $sr_form_data->description : ''; ?></textarea>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>