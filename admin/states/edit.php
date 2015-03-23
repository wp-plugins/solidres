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

function sr_edit_state_item( $id ) {
	$message = isset( $_GET['message'] ) ? $_GET['message'] : '';
	global $wpdb;
	$states = new SR_State();
	if ( ! isset( $_POST['edit_state'] ) ) {
		$sr_form_data = $states->load( $id );
	} else {
		$sr_form_data = (object) $_POST['srform'];
		$update_state = $wpdb->update( $wpdb->prefix . 'sr_geo_states',
			array(
				'country_id' => $sr_form_data->country_id,
				'name'       => $sr_form_data->name,
				'code_2'     => $sr_form_data->code_2,
				'code_3'     => $sr_form_data->code_3,
				'state'      => $sr_form_data->state,
			),
			array(
				'id' => $id,
			)
		);
		if ( $update_state == true ) {
			$message = 2;
			wp_redirect( admin_url( 'admin.php?page=sr-states&action=edit&id=' . $id . '&message=' . $message ) );
			exit;
		} else {
			$message = __( 'Update state failed', 'solidres' );
			SR_Helper::error_message( $message );
		}
	} ?>

	<div class="wrap">
		<div id="wpbody">
			<div id="message" class="updated below-h2 <?php echo $message == 1 ? '' : 'nodisplay'; ?>">
				<p><?php _e( 'State published.', 'solidres' ); ?></p></div>
			<div id="message" class="updated below-h2 <?php echo $message == 2 ? '' : 'nodisplay'; ?>">
				<p><?php _e( 'State updated.', 'solidres' ); ?></p></div>
			<h2><?php _e( 'Edit state', 'solidres' ); ?><a
					href="<?php echo admin_url( 'admin.php?page=sr-add-new-state' ); ?>"
					class="add-new-h2"><?php _e( 'Add New', 'solidres' ); ?></a></h2>

			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content" class="edit-form-section">
					<div id="namediv" class="stuffbox">
						<h3><label for="name"><?php _e( 'General infomartion', 'solidres' ); ?></label></h3>

						<div class="inside">
							<form name="srform_edit_state" action="" method="post" id="srform">
								<?php require( 'layouts/general-infomation.php' ); ?>
								<input type="submit" name="edit_state" value="<?php _e( 'Save', 'solidres' ); ?>"
								       class="srform_button button button-primary button-large edit_state">
							</form>
							<br>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }