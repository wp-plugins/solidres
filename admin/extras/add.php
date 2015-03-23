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

function sr_add_new_extra() {
	global $wpdb;
	$current_user = wp_get_current_user();
	$author_id    = $current_user->ID;
	$today        = date( 'd-m-Y' );
	if ( isset( $_POST['add_new_extra'] ) ) {
		$sr_form_data     = (object) $_POST['srform'];
		$params_json_data = json_encode( $sr_form_data->params );
		$columns          = array(
			'name'                 => $sr_form_data->name,
			'state'                => $sr_form_data->state,
			'description'          => $sr_form_data->description,
			'created_date'         => $today,
			'modified_date'        => '0000-00-00 00:00:00.000000',
			'created_by'           => $author_id,
			'modified_by'          => 1,
			'price'                => $sr_form_data->price,
			'ordering'             => 1,
			'max_quantity'         => $sr_form_data->max_quantity,
			'daily_chargable'      => $sr_form_data->daily_chargable,
			'reservation_asset_id' => $sr_form_data->reservation_asset_id,
			'mandatory'            => $sr_form_data->mandatory,
			'charge_type'          => $sr_form_data->charge_type,
			'params'               => $params_json_data,

		);
		if ( $sr_form_data->tax_id != null ) {
			$columns['tax_id'] = $sr_form_data->tax_id;
		}
		$add_extra = $wpdb->insert( $wpdb->prefix . 'sr_extras', $columns );
		if ( $add_extra == true ) {
			$last_id = $wpdb->insert_id;
			$message = 1;
			wp_redirect( admin_url( 'admin.php?page=sr-extras&action=edit&id=' . $last_id . '&message=' . $message ) );
			exit;
		} else {
			$message = __( 'Add new extra failed', 'solidres' );
			SR_Helper::error_message( $message );
		}
	} ?>

	<div id="wpbody">
		<div id="wpbody-content" aria-label="Main content" tabindex="0" style="overflow: hidden;">
			<div class="wrap srform_wrapper">
				<h2><?php _e( 'Add new extra', 'solidres' ); ?></h2>

				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
						<form name="srform_add_new_extra" action="" method="post" id="srform">
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
							<input type="submit" name="add_new_extra" value="<?php _e( 'Save', 'solidres' ); ?>"
							       class="srform_button button button-primary button-large add_new_extra">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }