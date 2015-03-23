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

if ( ! empty( $sr_form_data->params ) ) {
	$json_param = $solidres_asset->load_params( $sr_form_data->params );
} ?>

<div id="asset_publishing" class="postbox closed open">
	<div class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e( 'Publishing', 'solidres' ); ?></span></h3>

	<div class="inside">
		<table class="form-table">
			<tbody>
			<tr>
				<td class="first"><label for="srform_state"
				                         title="<?php _e( 'The status of this item.', 'solidres' ); ?>"><?php _e( 'Status', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[state]" class="srform_selected" id="srform_state">
						<option value="0" <?php if ( isset( $sr_form_data->state ) ) {
							echo $sr_form_data->state == 0 ? 'selected' : '';
						} ?> ><?php _e( 'Unpublished', 'solidres' ); ?></option>
						<option value="1" <?php if ( isset( $sr_form_data->state ) ) {
							echo $sr_form_data->state == 1 ? 'selected' : '';
						} ?> ><?php _e( 'Published', 'solidres' ); ?></option>
						<option value="-2" <?php if ( isset( $sr_form_data->state ) ) {
							echo $sr_form_data->state == - 2 ? 'selected' : '';
						} ?> ><?php _e( 'Trashed', 'solidres' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_default"
				                         title="<?php _e( 'The front-end only checks room availability against the default reservation asset.', 'solidres' ); ?>"><?php _e( 'Default', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[default]" class="srform_selected" id="srform_default">
						<option value="0" <?php if ( isset( $sr_form_data->default ) ) {
							echo $sr_form_data->default == 0 ? 'selected' : '';
						} ?> ><?php _e( 'No', 'solidres' ); ?></option>
						<option value="1" <?php if ( isset( $sr_form_data->default ) ) {
							echo $sr_form_data->default == 1 ? 'selected' : '';
						} ?> ><?php _e( 'Yes', 'solidres' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_rating"
				                         title="<?php _e( 'Select a rating for your asset', 'solidres' ); ?>"><?php _e( 'Rating', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[rating]" class="srform_selected" id="srform_rating">
						<?php echo isset( $sr_form_data->rating ) ? SR_Helper::get_rating_selected( $sr_form_data->rating ) : SR_Helper::get_rating_selected(); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_deposit_required"
				                         title="<?php _e( 'If select YES, deposit amount will be calculated and the guest will be charged.', 'solidres' ); ?>"><?php _e( 'Deposit required', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[deposit_required]" class="srform_selected" id="srform_deposit_required">
						<option value="0" <?php if ( isset( $sr_form_data->deposit_required ) ) {
							echo $sr_form_data->deposit_required == 0 ? 'selected' : '';
						} ?> ><?php _e( 'No', 'solidres' ); ?></option>
						<option value="1" <?php if ( isset( $sr_form_data->deposit_required ) ) {
							echo $sr_form_data->deposit_required == 1 ? 'selected' : '';
						} ?> ><?php _e( 'Yes', 'solidres' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_deposit_is_percentage"
				                         title="<?php _e( 'The deposit amount is a flat number or percentage of total booking cost.', 'solidres' ); ?>"><?php _e( 'Deposit is percentage', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[deposit_is_percentage]" class="srform_selected"
					        id="srform_deposit_is_percentage">
						<option value="0" <?php if ( isset( $sr_form_data->deposit_is_percentage ) ) {
							echo $sr_form_data->deposit_is_percentage == 0 ? 'selected' : '';
						} ?> ><?php _e( 'No', 'solidres' ); ?></option>
						<option value="1" <?php if ( isset( $sr_form_data->deposit_is_percentage ) ) {
							echo $sr_form_data->deposit_is_percentage == 1 ? 'selected' : '';
						} ?> ><?php _e( 'Yes', 'solidres' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_deposit_amount"
				                         title="<?php _e( 'The amount that will be applied to guest. It could be a flat number or percentage.', 'solidres' ); ?>"><?php _e( 'Deposit amount', 'solidres' ); ?></label>
				</td>
				<td><input type="text" name="srform[deposit_amount]" size="30"
				           value="<?php echo isset( $sr_form_data->deposit_amount ) ? $sr_form_data->deposit_amount : '' ?>"
				           id="srform_deposit_amount"></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_id"
				                         title="<?php _e( 'Id desc', 'solidres' ); ?>"><?php _e( 'Id', 'solidres' ); ?></label>
				</td>
				<td><input type="text" name="srform[id]" size="30" value="<?php echo isset( $id ) ? $id : ''; ?>"
				           id="srform_id" disabled></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_created_by"
				                         title="<?php _e( 'The user who created this', 'solidres' ); ?>"><?php _e( 'Created by', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[created_by]" class="srform_selected" id="srform_created_by">
						<option value=""><?php _e( 'Selected user', 'solidres' ); ?></option>
						<?php echo isset( $sr_form_data->created_by ) ? SR_Helper::get_created_by_selected( $sr_form_data->created_by ) : SR_Helper::get_created_by_selected(); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_created_date"
				                         title="<?php _e( 'Created Date', 'solidres' ); ?>"><?php _e( 'Created Date', 'solidres' ); ?></label>
				</td>
				<td><input type="text" name="srform[created_date]"
				           value="<?php echo isset( $sr_form_data->created_date ) ? $sr_form_data->created_date : ''; ?>"
				           id="srform_created_date" class="srform_datepicker" disabled></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_modified_date"
				                         title="<?php _e( 'Modified Date', 'solidres' ); ?>"><?php _e( 'Modified Date', 'solidres' ); ?></label>
				</td>
				<td><input type="text" name="srform[modified_date]"
				           value="<?php echo isset( $sr_form_data->modified_date ) ? $sr_form_data->modified_date : ''; ?>"
				           id="srform_modified_date" class="srform_datepicker" disabled></td>
			</tr>
			<tr>
				<td class="first"><label
						title="<?php _e( 'Select an article that contains your Terms and Condition', 'solidres' ); ?>"><?php _e( 'Terms of Use', 'solidres' ); ?></label>
				</td>
				<td>
					<?php echo wp_dropdown_pages( array(
						'name'             => 'srform[params][termsofuse]',
						'echo'             => false,
						'show_option_none' => __( 'Select an Article', 'solidres' ),
						'selected'         => ! empty( $json_param['termsofuse'] ) ? $json_param['termsofuse'] : false
					) ); ?>
				</td>
			</tr>
			<tr>
				<td class="first"><label
						title="<?php _e( 'Select an article that contains your Privacy Policy', 'solidres' ); ?>"><?php _e( 'Privacy Policy', 'solidres' ); ?></label>
				</td>
				<td>
					<?php echo wp_dropdown_pages( array(
						'name'             => 'srform[params][privacypolicy]',
						'echo'             => false,
						'show_option_none' => __( 'Select an Article', 'solidres' ),
						'selected'         => ! empty( $json_param['privacypolicy'] ) ? $json_param['privacypolicy'] : false
					) ); ?>
				</td>
			</tr>
			<tr>
				<td class="first"><label
						title="<?php _e( 'Select an article that contains your Disclaimer', 'solidres' ); ?>"><?php _e( 'Disclaimer', 'solidres' ); ?></label>
				</td>
				<td>
					<?php echo wp_dropdown_pages( array(
						'name'             => 'srform[params][disclaimer]',
						'echo'             => false,
						'show_option_none' => __( 'Select an Article', 'solidres' ),
						'selected'         => ! empty( $json_param['disclaimer'] ) ? $json_param['disclaimer'] : false
					) ); ?>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_only_show_reservation_form"
				                         title="<?php _e( "If enabled, show only reservation form in the front end, other reservation asset's info (like name, address, description, gallery) will be hidden.", 'solidres' ); ?>"><?php _e( 'Show only reservation form', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[params][only_show_reservation_form]" class="srform_selected"
					        id="srform_only_show_reservation_form">
						<option value="0" <?php if ( isset( $json_param['only_show_reservation_form'] ) ) {
							echo $json_param['only_show_reservation_form'] == 0 ? 'selected' : '';
						} ?> ><?php _e( 'No', 'solidres' ); ?></option>
						<option value="1" <?php if ( isset( $json_param['only_show_reservation_form'] ) ) {
							echo $json_param['only_show_reservation_form'] == 1 ? 'selected' : '';
						} ?> ><?php _e( 'Yes', 'solidres' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_enable_coupon"
				                         title="<?php _e( 'Select whether to enable coupon for this asset.', 'solidres' ); ?>"><?php _e( 'Enable coupon', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[params][enable_coupon]" class="srform_selected" id="srform_enable_coupon">
						<option value="0" <?php if ( isset( $json_param['enable_coupon'] ) ) {
							echo $json_param['enable_coupon'] == 0 ? 'selected' : '';
						} ?> ><?php _e( 'No', 'solidres' ); ?></option>
						<option value="1" <?php if ( isset( $json_param['enable_coupon'] ) ) {
							echo $json_param['enable_coupon'] == 1 ? 'selected' : '';
						} ?> ><?php _e( 'Yes', 'solidres' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_image"
				                         title="<?php _e( 'Enter the logo file name into this field. This logo will be used in front end display and email templates. Before enter the logo file name here, it must be uploaded it first using Media Manager.', 'solidres' ); ?>"><?php _e( 'Logo', 'solidres' ); ?></label>
				</td>
				<td>
					<input type="text" name="srform[params][logo]" size="30"
					       value="<?php echo isset( $json_param['logo'] ) ? $json_param['logo'] : ''; ?>"
					       id="srform_image" readonly="true">
					<input type="button" name="upload_srform_image" class="botton upload_srform_image" value="Upload"/>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>