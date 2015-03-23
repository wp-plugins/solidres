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

<table class="form-table">
	<tbody>
	<tr>
		<td class="first"><label for="srform_name"
		                         title="<?php _e( 'Name desc', 'solidres' ); ?>"><?php _e( 'Country Name', 'solidres' ); ?>
				<span class="required">*</span></label></td>
		<td><input type="text" name="srform[name]" size="30"
		           value="<?php echo isset( $sr_form_data->name ) ? $sr_form_data->name : '' ?>" id="srform_name"
		           required></td>
	</tr>
	<tr>
		<td class="first"><label for="srform_code_2"
		                         title="<?php _e( 'ISO country code 2', 'solidres' ); ?>"><?php _e( 'Code 2', 'solidres' ); ?>
				<span class="required">*</span></label></td>
		<td><input type="text" name="srform[code_2]" size="30"
		           value="<?php echo isset( $sr_form_data->code_2 ) ? $sr_form_data->code_2 : '' ?>" id="srform_code_2"
		           required></td>
	</tr>
	<tr>
		<td class="first"><label for="srform_code_3"
		                         title="<?php _e( 'ISO country code 3', 'solidres' ); ?>"><?php _e( 'Code 3', 'solidres' ); ?>
				<span class="required">*</span></label></td>
		<td><input type="text" name="srform[code_3]" size="30"
		           value="<?php echo isset( $sr_form_data->code_3 ) ? $sr_form_data->code_3 : '' ?>" id="srform_code_3"
		           required></td>
	</tr>
	<tr>
		<td class="first"><label for="srform_state"
		                         title="<?php _e( 'The status of this item.', 'solidres' ); ?>"><?php _e( 'State', 'solidres' ); ?></label>
		</td>
		<td>
			<select name="srform[state]" class="solidres_select">
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