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

function sr_edit_extra_item( $id ) {
	global $wpdb;
	$message      = isset( $_GET['message'] ) ? $_GET['message'] : '';
	$current_user = wp_get_current_user();
	$author_id    = $current_user->ID;
	$today        = date( 'd-m-Y' );
	if ( ! isset( $_POST['edit_extra'] ) ) {
		$sr_form_data = $wpdb->get_row( $wpdb->prepare( "SELECT e.*, r.name as assetname FROM {$wpdb->prefix}sr_extras e LEFT JOIN {$wpdb->prefix}sr_reservation_assets r ON e.reservation_asset_id = r.id WHERE e.id = %d", $id ) );
	} else {
		$sr_form_data     = (object) $_POST['srform'];
		$params_json_data = json_encode( $sr_form_data->params );
		$columns          = array(
			'name'                 => $sr_form_data->name,
			'state'                => $sr_form_data->state,
			'description'          => $sr_form_data->description,
			'modified_date'        => $today,
			'modified_by'          => $author_id,
			'price'                => $sr_form_data->price,
			'max_quantity'         => $sr_form_data->max_quantity,
			'daily_chargable'      => $sr_form_data->daily_chargable,
			'reservation_asset_id' => $sr_form_data->reservation_asset_id,
			'mandatory'            => $sr_form_data->mandatory,
			'charge_type'          => $sr_form_data->charge_type,
			'params'               => $params_json_data,
		);
		if ( $sr_form_data->tax_id != null ) {
			$columns['tax_id'] = $sr_form_data->tax_id;
		} else {
			$columns['tax_id'] = 'NULL';
		}
		add_filter( 'query', 'solidres_wp_db_null_value' );
		$updat_extra = $wpdb->update( $wpdb->prefix . 'sr_extras', $columns, array( 'id' => $id ) );
		remove_filter( 'query', 'solidres_wp_db_null_value' );

		if ( $updat_extra == true ) {
			$message = 2;
			wp_redirect( admin_url( 'admin.php?page=sr-extras&action=edit&id=' . $id . '&message=' . $message ) );
			exit;
		} else {
			$message = __( 'Update extra failed', 'solidres' );
			SR_Helper::error_message( $message );
		}
	} ?>

	<div id="wpbody">
		<div id="wpbody-content" aria-label="Main content" tabindex="0" style="overflow: hidden;">
			<div class="wrap srform_wrapper">
				<div id="message" class="updated below-h2 <?php echo $message == 1 ? '' : 'nodisplay'; ?>">
					<p><?php _e( 'Extra published.', 'solidres' ); ?></p></div>
				<div id="message" class="updated below-h2 <?php echo $message == 2 ? '' : 'nodisplay'; ?>">
					<p><?php _e( 'Extra updated.', 'solidres' ); ?></p></div>
				<h2><?php _e( 'Edit extra', 'solidres' ); ?> <a
						href="<?php echo admin_url( 'admin.php?page=sr-add-new-extra' ); ?>"
						class="add-new-h2"><?php _e( 'Add New', 'solidres' ); ?></a></h2>

				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
						<form name="srform_edit_extra" action="" method="post" id="srform">
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
							<input type="submit" name="edit_extra" value="<?php _e( 'Save', 'solidres' ); ?>"
							       class="srform_button button button-primary button-large edit_extra">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }