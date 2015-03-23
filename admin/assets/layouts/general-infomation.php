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
$users = 'solidres-users/solidres-users.php';
$check_plugin_users = solidres_check_plugin( $users );
?>

<div id="asset_general_infomation" class="postbox">
	<div class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e( 'General infomartion', 'solidres' ); ?></span></h3>

	<div class="inside">
		<table class="form-table">
			<tbody>
			<tr>
				<td class="first"><label for="srform_name"
				                         title="<?php _e( 'For example: Sunny Hotel', 'solidres' ); ?>"><?php _e( 'Asset name', 'solidres' ); ?>
						<span class="required">*</span></label></td>
				<td><input type="text" name="srform[name]" size="30"
				           value="<?php echo isset( $sr_form_data->name ) ? $sr_form_data->name : '' ?>"
				           id="srform_name" required></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_alias"
				                         title="<?php _e( 'Alias is used in Search Engine Friendly URL.', 'solidres' ); ?>"><?php _e( 'Alias', 'solidres' ); ?>
						<span class="required">*</span></label></td>
				<td><input type="text" name="srform[alias]" size="30"
				           value="<?php echo isset( $sr_form_data->alias ) ? $sr_form_data->alias : '' ?>"
				           id="srform_alias" required></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_category"
				                         title="<?php _e( 'Specify the type of your asset.', 'solidres' ); ?>"><?php _e( 'Category', 'solidres' ); ?>
						<span class="required">*</span></label></td>
				<td>
					<select name="srform[category_id]" id="srform_category" class="srform_selected" required>
						<option value=""><?php _e( 'Select asset category', 'solidres' ); ?></option>
						<?php echo isset( $sr_form_data->category_id ) ? SR_Helper::get_asset_caterogy_selected( $sr_form_data->category_id ) : SR_Helper::get_asset_caterogy_selected(); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_partner"
				                         title="<?php _e( 'Note: this field is enabled for subscribers only. Specify the customer who manages this reservation asset in front end. This field support auto complete, just type either email address or user name or customer code to find.', 'solidres' ); ?>"><?php _e( 'Partner', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[partner_id]" class="srform_selected"
					        id="srform_partner" <?php echo ( $check_plugin_users['status'] != 1 ) ? 'disabled' : ''; ?> >
						<option value=""><?php _e( 'Select partner', 'solidres' ); ?></option>
						<?php if ( $check_plugin_users['status'] == 1 ) {
							echo isset( $sr_form_data->partner_id ) ? SR_Partner::load_partner_selected( $sr_form_data->partner_id ) : SR_Partner::load_partner_selected();
						} ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_address1"
				                         title="<?php _e( 'The first address', 'solidres' ); ?>"><?php _e( 'Address 1', 'solidres' ); ?></label>
				</td>
				<td><input type="text" name="srform[address_1]" size="30"
				           value="<?php echo isset( $sr_form_data->address_1 ) ? $sr_form_data->address_1 : '' ?>"
				           id="srform_address1"></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_address2"
				                         title="<?php _e( 'The second address (optional)', 'solidres' ); ?>"><?php _e( 'Address 2', 'solidres' ); ?></label>
				</td>
				<td><input type="text" name="srform[address_2]" size="30"
				           value="<?php echo isset( $sr_form_data->address_2 ) ? $sr_form_data->address_2 : '' ?>"
				           id="srform_address2"></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_city"
				                         title="<?php _e( 'The city name of your reservation asset', 'solidres' ); ?>"><?php _e( 'City', 'solidres' ); ?></label>
				</td>
				<td><input type="text" name="srform[city]" size="30"
				           value="<?php echo isset( $sr_form_data->city ) ? $sr_form_data->city : '' ?>"
				           id="srform_city"></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_postcode"
				                         title="<?php _e( "The post code of your reservation asset's city", 'solidres' ); ?>"><?php _e( 'Post code', 'solidres' ); ?></label>
				</td>
				<td><input type="text" name="srform[postcode]" size="30"
				           value="<?php echo isset( $sr_form_data->postcode ) ? $sr_form_data->postcode : '' ?>"
				           id="srform_postcode"></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_email"
				                         title="<?php _e( 'This email will be used in From field of automated emails, for example: emails send to customer when they complete their reservations', 'solidres' ); ?>"><?php _e( 'Email', 'solidres' ); ?>
						<span class="required">*</span></label></td>
				<td><input type="email" name="srform[email]" size="30"
				           value="<?php echo isset( $sr_form_data->email ) ? $sr_form_data->email : '' ?>"
				           id="srform_email" required></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_website"
				                         title="<?php _e( "Your reservation asset's website", 'solidres' ); ?>"><?php _e( 'Website', 'solidres' ); ?></label>
				</td>
				<td><input type="url" name="srform[website]" size="30"
				           value="<?php echo isset( $sr_form_data->website ) ? $sr_form_data->website : '' ?>"
				           id="srform_website"></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_phone"
				                         title="<?php _e( 'Phone description', 'solidres' ); ?>"><?php _e( 'Phone', 'solidres' ); ?></label>
				</td>
				<td><input type="text" name="srform[phone]" size="30"
				           value="<?php echo isset( $sr_form_data->phone ) ? $sr_form_data->phone : '' ?>"
				           id="srform_phone"></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_fax"
				                         title="<?php _e( 'Fax description', 'solidres' ); ?>"><?php _e( 'Fax', 'solidres' ); ?></label>
				</td>
				<td><input type="text" name="srform[fax]" size="30"
				           value="<?php echo isset( $sr_form_data->fax ) ? $sr_form_data->fax : '' ?>" id="srform_fax">
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_country"
				                         title="<?php _e( "Your reservation asset's country", 'solidres' ); ?>"> <?php _e( 'Country', 'solidres' ); ?>
						<span class="required">*</span></label></td>
				<td>
					<select name="srform[country_id]" class="srform_selected srform_select_country" id="srform_country"
					        required>
						<?php echo isset( $sr_form_data->country_id ) ? SR_Helper::get_country_selected( $sr_form_data->country_id ) : SR_Helper::get_country_selected(); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_geo_state"
				                         title="<?php _e( "Your reservation asset's state (optional)", 'solidres' ); ?>"><?php _e( 'State', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[geo_state_id]" class="srform_selected srform_select_state"
					        id="srform_geo_state">
						<?php echo isset( $sr_form_data->geo_state_id ) ? SR_Helper::get_geo_state_selected( $sr_form_data->country_id, $sr_form_data->geo_state_id ) : SR_Helper::get_geo_state_selected(); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_currency"
				                         title="<?php _e( 'Select the currency for this reservation asset', 'solidres' ); ?>"><?php _e( 'Currency', 'solidres' ); ?>
						<span class="required">*</span></label></td>
				<td>
					<select name="srform[currency_id]" class="srform_selected" id="srform_currency" required>
						<option value=""><?php _e( 'Select asset currency', 'solidres' ); ?></option>
						<?php echo isset( $sr_form_data->currency_id ) ? SR_Helper::get_currency_selected( $sr_form_data->currency_id ) : SR_Helper::get_currency_selected(); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_tax"
				                         title="<?php _e( 'Select the tax that will apply to this asset. The tax list is depend on the selected country above. Please select a country first and the tax list will be displayed accordingly.', 'solidres' ); ?>"><?php _e( 'Tax', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[tax_id]" class="srform_selected" id="srform_tax">
						<?php echo isset( $sr_form_data->tax_id ) ? SR_Helper::get_tax_selected_by_country( $sr_form_data->country_id, $sr_form_data->tax_id ) : SR_Helper::get_tax_selected_by_country(); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_description"
				                         title="<?php _e( 'Describe your reservation asset', 'solidres' ); ?>"><?php _e( 'Description', 'solidres' ); ?></label>
				</td>
				<td><textarea class="srform_textarea" rows="5" name="srform[description]"
				              id="srform_description"><?php echo isset( $sr_form_data->description ) ? $sr_form_data->description : '' ?></textarea>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>