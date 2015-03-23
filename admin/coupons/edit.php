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

function sr_edit_coupon_item( $id ) {
	global $wpdb;
	$message = isset( $_GET['message'] ) ? $_GET['message'] : '';
	if ( ! isset( $_POST['edit_coupon'] ) ) {
		$sr_form_data = $wpdb->get_row( $wpdb->prepare( "SELECT c.*, r.name as assetname FROM {$wpdb->prefix}sr_coupons c LEFT JOIN {$wpdb->prefix}sr_reservation_assets r ON c.reservation_asset_id = r.id WHERE c.id = %d", $id ) );
	} else {
		$sr_form_data     = (object) $_POST['srform'];
		$get_current_code = $wpdb->get_row( $wpdb->prepare( "SELECT coupon_code FROM {$wpdb->prefix}sr_coupons WHERE id = %d", $id ) );
		$check_coupon     = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}sr_coupons WHERE coupon_code = '%s' AND coupon_code != '%s'", $sr_form_data->coupon_code, $get_current_code->coupon_code ) );
		if ( $check_coupon > 0 ) {
			$message = ''; ?>
			<div id="message" class="error below-h2">
				<p><?php _e( 'Your coupon code is duplicated, please enter another coupon code', 'solidres' ); ?></p>
			</div>
		<?php } else {
			$params_json_data = json_encode( $sr_form_data->params );
			$columns          = array(
				'coupon_name'          => $sr_form_data->coupon_name,
				'coupon_code'          => $sr_form_data->coupon_code,
				'amount'               => $sr_form_data->amount,
				'reservation_asset_id' => $sr_form_data->reservation_asset_id,
				'is_percent'           => $sr_form_data->is_percent,
				'valid_from'           => solidres_valid_date_format( '-', $sr_form_data->valid_from ),
				'valid_to'             => solidres_valid_date_format( '-', $sr_form_data->valid_to ),
				'valid_from_checkin'   => solidres_valid_date_format( '-', $sr_form_data->valid_from_checkin ),
				'valid_to_checkin'     => solidres_valid_date_format( '-', $sr_form_data->valid_to_checkin ),
				'quantity'             => $sr_form_data->quantity,
				'state'                => $sr_form_data->state,
				'params'               => $params_json_data,
			);
			if ( $sr_form_data->customer_group_id != null ) {
				$columns['customer_group_id'] = $sr_form_data->customer_group_id;
			} else {
				$columns['customer_group_id'] = 'NULL';
			}
			$update_coupon = $wpdb->update( $wpdb->prefix . 'sr_coupons', $columns, array( 'id' => $id ) );
			if ( $update_coupon == true ) {
				$message = 2;
				wp_redirect( admin_url( 'admin.php?page=sr-coupons&action=edit&id=' . $id . '&message=' . $message ) );
				exit;
			} else {
				$message = __( 'Update coupon failed', 'solidres' );
				SR_Helper::error_message( $message );
			}
		}
	} ?>

	<div id="wpbody">
		<div id="wpbody-content" aria-label="Main content" tabindex="0" style="overflow: hidden;">
			<div class="wrap srform_wrapper">
				<div id="message" class="updated below-h2 <?php echo $message == 1 ? '' : 'nodisplay'; ?>">
					<p><?php _e( 'Coupon published.', 'solidres' ); ?></p></div>
				<div id="message" class="updated below-h2 <?php echo $message == 2 ? '' : 'nodisplay'; ?>">
					<p><?php _e( 'Coupon updated.', 'solidres' ); ?></p></div>
				<h2><?php _e( 'Edit coupon', 'solidres' ); ?> <a
						href="<?php echo admin_url( 'admin.php?page=sr-add-new-coupon' ); ?>"
						class="add-new-h2"><?php _e( 'Add New', 'solidres' ); ?></a></h2>

				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
						<form name="srform_edit_coupon" action="" method="post" id="srform">
							<div id="postbox-container-1" class="postbox-container">
								<div id="side-sortables" class="meta-box-sortables ui-sortable">
									<?php require( 'layouts/publishing.php' ); ?>
								</div>
							</div>
							<div id="postbox-container-2" class="postbox-container">
								<div id="normal-sortables" class="meta-box-sortables ui-sortable">
									<?php require( 'layouts/general-infomation.php' ); ?>
								</div>
							</div>
							<input type="submit" name="edit_coupon" value="<?php _e( 'Save', 'solidres' ); ?>"
							       class="srform_button button button-primary button-large edit_coupon">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }