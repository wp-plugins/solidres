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

function sr_add_new_coupon() {
	global $wpdb;
	if ( isset( $_POST['add_new_coupon'] ) ) {
		$sr_form_data = (object) $_POST['srform'];
		$check_coupon = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}sr_coupons WHERE coupon_code = %s", $sr_form_data['coupon_code'] ) );
		if ( $check_coupon > 0 ) { ?>
			<div id="message" class="error below-h2">
				<p><?php _e( 'Your coupon code is duplicated, please enter another coupon code', 'solidres' ); ?></p>
			</div>
		<?php } else {
			$params_json_data = json_encode( $sr_form_data->params );
			add_filter( 'query', 'solidres_wp_db_null_value' );
			$add_coupon = $wpdb->insert( $wpdb->prefix . 'sr_coupons',
				array(
					'coupon_name'          => $sr_form_data->coupon_name,
					'coupon_code'          => $sr_form_data->coupon_code,
					'amount'               => $sr_form_data->amount,
					'reservation_asset_id' => $sr_form_data->reservation_asset_id,
					'is_percent'           => $sr_form_data->is_percent,
					'valid_from'           => solidres_valid_date_format( '-', $sr_form_data->valid_from ),
					'valid_to'             => solidres_valid_date_format( '-', $sr_form_data->valid_to ),
					'valid_from_checkin'   => solidres_valid_date_format( '-', $sr_form_data->valid_from_checkin ),
					'valid_to_checkin'     => solidres_valid_date_format( '-', $sr_form_data->valid_to_checkin ),
					'customer_group_id'    => $sr_form_data->customer_group_id == null ? 'NULL' : $sr_form_data->customer_group_id,
					'quantity'             => $sr_form_data->quantity,
					'state'                => $sr_form_data->state,
					'params'               => $params_json_data,
				)
			);
			remove_filter( 'query', 'solidres_wp_db_null_value' );
			if ( $add_coupon == true ) {
				$last_id = $wpdb->insert_id;
				$message = 1;
				wp_redirect( admin_url( 'admin.php?page=sr-coupons&action=edit&id=' . $last_id . '&message=' . $message ) );
				exit;
			} else {
				$message = __( 'Add coupon failed', 'solidres' );
				SR_Helper::error_message( $message );
			}
		}
	} ?>

	<div id="wpbody">
		<div id="wpbody-content" aria-label="Main content" tabindex="0" style="overflow: hidden;">
			<div class="wrap srform_wrapper">
				<h2><?php _e( 'Add new Coupon', 'solidres' ); ?></h2>

				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
						<form name="srform_add_new_coupon" action="" method="post" id="srform">
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
							<input type="submit" name="add_new_coupon" value="<?php _e( 'Save', 'solidres' ); ?>"
							       class="srform_button button button-primary button-large add_new_coupon">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }