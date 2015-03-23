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

function sr_add_new_tax() {
	global $wpdb;

	if ( isset( $_POST['add_new_tax'] ) ) {
		$sr_form_data = (object) $_POST['srform'];
		$columns      = array(
			'name'  => $sr_form_data->name,
			'rate'  => $sr_form_data->rate,
			'state' => $sr_form_data->state,
		);
		if ( $sr_form_data->country_id != null ) {
			$columns['country_id'] = $sr_form_data->country_id;
		}
		if ( $sr_form_data->geo_state_id != null ) {
			$columns['geo_state_id'] = $sr_form_data->geo_state_id;
		}
		$add_tax = $wpdb->insert( $wpdb->prefix . 'sr_taxes', $columns );
		if ( $add_tax == true ) {
			$last_id = $wpdb->insert_id;
			$message = 1;
			wp_redirect( admin_url( 'admin.php?page=sr-taxes&action=edit&id=' . $last_id . '&message=' . $message ) );
			exit;
		} else {
			$message = __( 'add new tax failed', 'solidres' );
			SR_Helper::error_message( $message );
		}
	} ?>

	<div id="wpbody">
		<h2><?php _e( 'Add new tax', 'solidres' ); ?></h2>

		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content" class="edit-form-section">
				<div id="namediv" class="stuffbox">
					<h3><label for="name"><?php _e( 'General infomartion', 'solidres' ); ?></label></h3>

					<div class="inside">
						<form name="srform_add_new_tax" action="" method="post" id="srform">
							<?php require( 'layouts/general-infomation.php' ); ?>
							<input type="submit" name="add_new_tax" value="<?php _e( 'Save', 'solidres' ); ?>"
							       class="srform_button button button-primary button-large add_new_tax">
						</form>
						<br>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }