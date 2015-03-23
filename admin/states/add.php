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

function sr_add_new_state() {
	global $wpdb;
	if ( isset( $_POST['add_new_state'] ) ) {
		$sr_form_data = (object) $_POST['srform'];
		$add_state    = $wpdb->insert( $wpdb->prefix . 'sr_geo_states',
			array(
				'country_id' => $sr_form_data->country_id,
				'name'       => $sr_form_data->name,
				'code_2'     => $sr_form_data->code_2,
				'code_3'     => $sr_form_data->code_3,
				'state'      => $sr_form_data->state,
			)
		);
		if ( $add_state == true ) {
			$last_id = $wpdb->insert_id;
			$message = 1;
			wp_redirect( admin_url( 'admin.php?page=sr-states&action=edit&id=' . $last_id . '&message=' . $message ) );
			exit;
		} else {
			$message = __( 'Add new state failed', 'solidres' );
			SR_Helper::error_message( $message );
		}
	}
	?>
	<div id="wpbody">
		<h2><?php _e( 'Add new state', 'solidres' ); ?></h2>

		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content" class="edit-form-section">
				<div id="namediv" class="stuffbox">
					<h3><label for="name"><?php _e( 'General infomartion', 'solidres' ); ?></label></h3>

					<div class="inside">
						<form name="srform_add_new_state" action="" method="post" id="srform">
							<?php require( 'layouts/general-infomation.php' ); ?>
							<input type="submit" name="add_new_state" value="<?php _e( 'Save', 'solidres' ); ?>"
							       class="srform_button button button-primary button-large add_new_state">
						</form>
						<br>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }