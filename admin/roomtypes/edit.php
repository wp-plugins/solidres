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

function sr_edit_room_type_item( $id ) {
	global $wpdb;
	$current_user       = wp_get_current_user();
	$author_id          = $current_user->ID;
	$today              = date( 'Y-m-d H:i:s' );
	$message            = isset( $_GET['message'] ) ? $_GET['message'] : '';

	$hub = 'solidres-hub/solidres-hub.php';
	$check_plugin_hub = solidres_check_plugin( $hub );

	$solidres_room_type = new SR_Room_Type();
	if ( ! isset( $_POST['edit_room_types'] ) ) {
		$sr_form_data = $solidres_room_type->load( $id );
	} else {
		$sr_form_data     = (object) $_POST['srform'];
		$get_current_slug = $wpdb->get_row( $wpdb->prepare( "SELECT alias FROM {$wpdb->prefix}sr_room_types WHERE id = %d", $id ) );
		$check_slug       = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}sr_room_types WHERE alias = %s AND alias != %s", $sr_form_data->alias, $get_current_slug->alias ) );
		if ( $check_slug > 0 ) {
			$message = ''; ?>
			<div id="message" class="error below-h2">
				<p><?php _e( 'Room types slug already exists.', 'solidres' ); ?></p></div>
		<?php } else {
			$params_json_data = json_encode( $sr_form_data->params );
			$update_roomtype  = $wpdb->update( $wpdb->prefix . 'sr_room_types',
				array(
					'reservation_asset_id' => $sr_form_data->reservation_asset_id,
					'name'                 => $sr_form_data->name,
					'alias'                => $sr_form_data->alias,
					'description'          => $sr_form_data->description,
					'state'                => $sr_form_data->state,
					'modified_by'          => $author_id,
					'modified_date'        => $today,
					'occupancy_adult'      => $sr_form_data->occupancy_adult,
					'occupancy_child'      => $sr_form_data->occupancy_child,
					'params'               => $params_json_data,
				),
				array(
					'id' => $id,
				)
			);

			if ( $update_roomtype == true ) {
				$get_currency_id = $wpdb->get_var( $wpdb->prepare( "SELECT currency_id FROM {$wpdb->prefix}sr_reservation_assets WHERE id = %d", $sr_form_data->reservation_asset_id ) );
				if ( $check_plugin_hub['status'] == 1 ) {
					$wpdb->delete( $wpdb->prefix . 'sr_facility_room_type_xref', array( 'room_type_id' => $id ) );
					foreach ( $sr_form_data->facility_id as $key => $value ) {
						$wpdb->insert( $wpdb->prefix . 'sr_facility_room_type_xref', array( 'facility_id' => $value, 'room_type_id' => $id ) );
					}
				}
				$wpdb->delete( $wpdb->prefix . 'sr_room_type_coupon_xref', array( 'room_type_id' => $id ) );
				$wpdb->delete( $wpdb->prefix . 'sr_room_type_extra_xref', array( 'room_type_id' => $id ) );
				if ( ! empty( $sr_form_data->coupons ) ) {
					foreach ( $sr_form_data->coupons as $srform_coupon ) {
						$wpdb->insert( $wpdb->prefix . 'sr_room_type_coupon_xref', array(
							'room_type_id' => $id,
							'coupon_id'    => $srform_coupon
						) );
					}
				}
				if ( ! empty( $sr_form_data->extras ) ) {
					foreach ( $sr_form_data->extras as $srform_extra ) {
						$wpdb->insert( $wpdb->prefix . 'sr_room_type_extra_xref', array(
							'room_type_id' => $id,
							'extra_id'     => $srform_extra
						) );
					}
				}
				$solidres_tariff  = new SR_Tariff();
				$get_tariffs_info = $solidres_tariff->load_by_room_type_id( $id );
				$wpdb->update(
					$wpdb->prefix . 'sr_tariffs',
					array(
						'currency_id' => $get_currency_id,
						'title'       => $sr_form_data->standard_tariff_title,
						'description' => $sr_form_data->standard_tariff_description,
					),
					array(
						'id' => $get_tariffs_info[0]->id,
					),
					array(
						'%d',
						'%s',
						'%s',
					)
				);
				$update_values = array();
				foreach ( $sr_form_data->default_tariff as $day => $price ) {
					$update_values[] = "WHEN $day THEN $price";
				}
				$values_convert       = implode( ' ', $update_values );
				$query_tariff_details = 'UPDATE ' . $wpdb->prefix . 'sr_tariff_details
					SET price = CASE w_day ' .
				                        $values_convert . '
					END
					WHERE tariff_id = ' . $get_tariffs_info[0]->id;
				$wpdb->query( $wpdb->prepare( $query_tariff_details, 10 ) );
				$custom_field_data_update = array();
				foreach ( $sr_form_data->customfields as $keys => $values ) {
					foreach ( $values as $key => $value ) {
						$field_key                = $keys . '.' . $key;
						$custom_field_data_update = array_merge( $custom_field_data_update, array( $field_key => $value ) );
					}
				}
				$solidres_custom_fields = new SR_Custom_Field( array(
					'id'              => $id,
					'group_namespace' => 'roomtype_custom_fields',
					'type'            => 'room_type'
				) );
				$solidres_custom_fields->set( $custom_field_data_update );
				$wpdb->delete( $wpdb->prefix . 'sr_media_roomtype_xref', array( 'room_type_id' => $id ) );
				foreach ( $sr_form_data->mediaId as $key => $value ) {
					$wpdb->insert( $wpdb->prefix . 'sr_media_roomtype_xref', array(
						'media_id'     => $value,
						'room_type_id' => $id,
						'weight'       => $key
					) );
				}
				foreach ( $sr_form_data->rooms as $key => $value ) {
					$wpdb->update( $wpdb->prefix . 'sr_rooms', array( 'label' => $value ), array( 'id' => $key ) );
				}
				foreach ( $sr_form_data->roomsnew as $key => $value ) {
					$wpdb->insert( $wpdb->prefix . 'sr_rooms', array( 'label' => $value, 'room_type_id' => $id ) );
				}
				$message = 2;
				wp_redirect( admin_url( 'admin.php?page=sr-room-types&action=edit&id=' . $id . '&message=' . $message ) );
				exit;
			} else {
				$message = __( 'Update roomtype failed', 'solidres' );
				SR_Helper::error_message( $message );
			}
		}
	} ?>

	<div id="wpbody">
		<div id="wpbody-content" aria-label="Main content" tabindex="0" style="overflow: hidden;">
			<div class="wrap srform_wrapper">
				<div id="message" class="updated below-h2 <?php echo $message == 1 ? '' : 'nodisplay'; ?>"><p>Room type
						published.</p></div>
				<div id="message" class="updated below-h2 <?php echo $message == 2 ? '' : 'nodisplay'; ?>"><p>Room type
						updated.</p></div>
				<h2><?php _e( 'Edit room type', 'solidres' ); ?> <a
						href="<?php echo admin_url( 'admin.php?page=sr-add-new-room-type' ); ?>"
						class="add-new-h2"><?php _e( 'Add New', 'solidres' ); ?></a></h2>

				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
						<form name="srform_edit_roomtype" action="" method="post" id="srform">
							<div id="postbox-container-1" class="postbox-container">
								<div id="side-sortables" class="meta-box-sortables ui-sortable">
									<?php require( 'layouts/room.php' ); ?>
									<?php require( 'layouts/media.php' ); ?>
								</div>
							</div>
							<div id="postbox-container-2" class="postbox-container">
								<div id="normal-sortables" class="meta-box-sortables ui-sortable">
									<?php require( 'layouts/general-infomation.php' ); ?>
									<?php require( 'layouts/publishing.php' ); ?>
									<?php require( 'layouts/custom-fields.php' ); ?>
									<?php require( 'layouts/complex-tariff.php' ); ?>
									<?php require( 'layouts/facility.php' ); ?>
								</div>
							</div>
							<input type="submit" name="edit_room_types" value="<?php _e( 'Save', 'solidres' ); ?>"
							       class="srform_button button button-primary button-large edit_room_types">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }