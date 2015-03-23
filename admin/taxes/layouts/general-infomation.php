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

?>

<table class="form-table">
	<tbody>
	<tr>
		<td class="first"><label for="srform_name"
		                         title="<?php _e( 'Enter the name of your tax, for example: VAT', 'solidres' ); ?>"><?php _e( 'Tax name', 'solidres' ); ?>
				<span class="required">*</span></label></td>
		<td><input type="text" name="srform[name]" size="30"
		           value="<?php echo isset( $sr_form_data->name ) ? $sr_form_data->name : '' ?>" id="srform_name"
		           required></td>
	</tr>
	<tr>
		<td class="first"><label for="srform_rate"
		                         title="<?php _e( 'Enter the rate of your tax, eg if your tax rate is 10% then enter 0.1', 'solidres' ); ?>"><?php _e( 'Tax rate', 'solidres' ); ?>
				<span class="required">*</span></label></td>
		<td><input type="number" name="srform[rate]" size="30"
		           value="<?php echo isset( $sr_form_data->rate ) ? $sr_form_data->rate : '' ?>" id="srform_rate"
		           required></td>
	</tr>
	<tr>
		<td class="first"><label for="srform_country"
		                         title="<?php _e( 'The country which this tax rate will be applied to', 'solidres' ); ?>"><?php _e( 'Country', 'solidres' ); ?></label>
		</td>
		<td>
			<select id="srform_country" name="srform[country_id]" class="srform_selected srform_select_country">
				<?php echo isset( $sr_form_data->country_id ) ? SR_Helper::get_country_selected( $sr_form_data->country_id ) : SR_Helper::get_country_selected(); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="first"><label for="srform_geo_state"
		                         title="<?php _e( 'State/County of the country in the field above', 'solidres' ); ?>"><?php _e( 'State', 'solidres' ); ?></label>
		</td>
		<td>
			<select name="srform[geo_state_id]" class="srform_selected srform_select_state">
				<?php echo isset( $sr_form_data->geo_state_id ) ? SR_Helper::get_geo_state_selected( $sr_form_data->country_id, $sr_form_data->geo_state_id ) : SR_Helper::get_geo_state_selected(); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="first"><label for="srform_state"
		                         title="<?php _e( 'Disable or enable this tax', 'solidres' ); ?>"><?php _e( 'Status', 'solidres' ); ?></label>
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
	</tbody>
</table>