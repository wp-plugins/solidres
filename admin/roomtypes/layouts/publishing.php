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
if ( isset( $sr_form_data->params ) ) {
	if ( isset( $_POST['edit_room_types'] ) ){
		$sr_form_data->metadata = json_encode( $sr_form_data->metadata );
	}
	$json_param = json_decode( $sr_form_data->params, true );
}
?>

<div id="rootype_publishing" class="postbox closed open">
	<div class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e( 'Publishing', 'solidres' ); ?></span></h3>

	<div class="inside">
		<table class="form-table">
			<tbody>
			<tr>
				<td class="first"><label for="srform_featured"
				                         title="<?php _e( 'Set a room type as Featured', 'solidres' ); ?>"><?php _e( 'Featured', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[featured]" class="srform_selected" id="srform_featured">
						<option value="0" <?php if ( isset( $sr_form_data->featured ) ) {
							echo $sr_form_data->featured == 0 ? 'selected' : '';
						} ?> ><?php _e( 'Off', 'solidres' ); ?></option>
						<option value="1" <?php if ( isset( $sr_form_data->featured ) ) {
							echo $sr_form_data->featured == 1 ? 'selected' : '';
						} ?> ><?php _e( 'On', 'solidres' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_created_by"
				                         title="<?php _e( 'The user who created this', 'solidres' ); ?>"><?php _e( 'Created by', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[created_by]" class="srform_selected" id="srform_created_by">
						<option value=""><?php _e( 'Selected creted by', 'solidres' ); ?></option>
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
				<td class="first"><label for="srform_ordering"
				                         title="<?php _e( 'Select the ordering', 'solidres' ); ?>"><?php _e( 'Ordering', 'solidres' ); ?></label>
				</td>
				<td><input type="number" name="srform[ordering]" size="30"
				           value="<?php echo isset( $sr_form_data->ordering ) ? $sr_form_data->ordering : '' ?>"
				           id="srform_ordering"></td>
			</tr>
			<tr>
				<td class="first"><label for="srform_id"
				                         title="<?php _e( 'Id desc', 'solidres' ); ?>"><?php _e( 'Id', 'solidres' ); ?></label>
				</td>
				<td><input type="text" name="srform[id]" size="30" value="<?php echo isset( $id ) ? $id : ''; ?>"
				           id="srform_id" disabled></td>
			</tr>
			<tr>
				<td class="first"><label for="srfor_show_smoking_option"
				                         title="<?php _e( 'Specify wheter to show smoking option in front end for this room type.', 'solidres' ); ?>"><?php _e( 'Show smoking option', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[params][show_smoking_option]" class="srform_selected"
					        id="srfor_show_smoking_option">
						<option value="0" <?php if ( isset( $json_param['show_smoking_option'] ) ) {
							echo $json_param['show_smoking_option'] == 0 ? 'selected' : '';
						} ?> ><?php _e( 'No', 'solidres' ); ?></option>
						<option value="1" <?php if ( isset( $json_param['show_smoking_option'] ) ) {
							echo $json_param['show_smoking_option'] == 1 ? 'selected' : '';
						} ?> ><?php _e( 'Yes', 'solidres' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_show_child_option"
				                         title="<?php _e( 'Specify whether to show child option in front end for this room type.', 'solidres' ); ?>"><?php _e( 'Show child option', 'solidres' ); ?></label>
				</td>
				<td>
					<select name="srform[params][show_child_option]" class="srform_selected"
					        id="srform_show_child_option">
						<option value="0" <?php if ( isset( $json_param['show_child_option'] ) ) {
							echo $json_param['show_child_option'] == 0 ? 'selected' : '';
						} ?> ><?php _e( 'No', 'solidres' ); ?></option>
						<option value="1" <?php if ( isset( $json_param['show_child_option'] ) ) {
							echo $json_param['show_child_option'] == 1 ? 'selected' : '';
						} ?> ><?php _e( 'Yes', 'solidres' ); ?></option>
					</select>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>