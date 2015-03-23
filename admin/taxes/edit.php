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

function sr_edit_tax_item( $id ) {
	$message = isset( $_GET['message'] ) ? $_GET['message'] : '';
	global $wpdb;
	$taxes = new SR_Tax();

	if ( ! isset( $_POST['edit_tax'] ) ) {
		$sr_form_data = $taxes->load( $id );
	} else {
		$sr_form_data = (object) $_POST['srform'];
		$columns      = array(
			'name'  => $sr_form_data->name,
			'rate'  => $sr_form_data->rate,
			'state' => $sr_form_data->state,
		);
		if ( $sr_form_data->country_id != null ) {
			$columns['country_id'] = $sr_form_data->country_id;
		} else {
			$columns['country_id'] = 'NULL';
		}
		if ( $sr_form_data->geo_state_id != null ) {
			$columns['geo_state_id'] = $sr_form_data->geo_state_id;
		} else {
			$columns['geo_state_id'] = 'NULL';
		}
		add_filter( 'query', 'solidres_wp_db_null_value' );
		$update_tax = $wpdb->update( $wpdb->prefix . 'sr_taxes', $columns, array( 'id' => $id ) );
		remove_filter( 'query', 'solidres_wp_db_null_value' );

		if ( $update_tax == true ) {
			$message = 2;
			wp_redirect( admin_url( 'admin.php?page=sr-taxes&action=edit&id=' . $id . '&message=' . $message ) );
			exit;
		} else {
			$message = __( 'Update tax failed', 'solidres' );
			SR_Helper::error_message( $message );
		}
	} ?>

	<div class="wrap">
		<div id="wpbody">
			<div id="message" class="updated below-h2 <?php echo $message == 1 ? '' : 'nodisplay'; ?>">
				<p><?php _e( 'Tax published.', 'solidres' ); ?></p></div>
			<div id="message" class="updated below-h2 <?php echo $message == 2 ? '' : 'nodisplay'; ?>">
				<p><?php _e( 'Tax updated.', 'solidres' ); ?></p></div>
			<h2><?php _e( 'Edit tax', 'solidres' ); ?><a
					href="<?php echo admin_url( 'admin.php?page=sr-add-new-tax' ); ?>"
					class="add-new-h2"><?php _e( 'Add New', 'solidres' ); ?></a></h2>

			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content" class="edit-form-section">
					<div id="namediv" class="stuffbox">
						<h3><label for="name"><?php _e( 'General infomartion', 'solidres' ); ?></label></h3>

						<div class="inside">
							<form name="srform_edit_tax" action="" method="post" id="srform">
								<?php require( 'layouts/general-infomation.php' ); ?>
								<input type="submit" name="edit_tax" value="<?php _e( 'Save', 'solidres' ); ?>"
								       class="srform_button button button-primary button-large edit_tax">
							</form>
							<br>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }