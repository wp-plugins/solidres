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

function sr_add_new_room_type() {
	global $wpdb;
	$current_user = wp_get_current_user();
	$author_id    = $current_user->ID;
	$today        = date( 'Y-m-d H:i:s' );

	$hub = 'solidres-hub/solidres-hub.php';
	$check_plugin_hub = solidres_check_plugin( $hub );

	if ( isset( $_POST['add_new_room_type'] ) ) {
		$sr_form_data = (object) $_POST['srform'];
		$check_slug   = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}sr_room_types WHERE alias = %s", $sr_form_data->alias ) );
		if ( $check_slug > 0 ) { ?>
			<div id="message" class="error below-h2">
				<p><?php _e( 'Room types slug already exists.', 'solidres' ); ?></p></div>
		<?php } else {
			$params_json_data = json_encode( $sr_form_data->params );
			$add_roomtype     = $wpdb->insert( $wpdb->prefix . 'sr_room_types',
				array(
					'name'                 => $sr_form_data->name,
					'alias'                => $sr_form_data->alias,
					'reservation_asset_id' => $sr_form_data->reservation_asset_id,
					'occupancy_adult'      => $sr_form_data->occupancy_adult,
					'occupancy_child'      => $sr_form_data->occupancy_child,
					'state'                => $sr_form_data->state,
					'description'          => $sr_form_data->standard_tariff_description,
					'checked_out'          => 0,
					'checked_out_time'     => '0000-00-00 00:00:00',
					'created_by'           => $author_id,
					'created_date'         => $today,
					'modified_by'          => 0,
					'modified_date'        => '0000-00-00 00:00:00',
					'language'             => '*',
					'featured'             => $sr_form_data->featured,
					'ordering'             => $sr_form_data->ordering,
					'smoking'              => 0,
					'params'               => $params_json_data,
				)
			);

			if ( $add_roomtype == true ) {
				$get_last_id     = $wpdb->insert_id;
				$get_currency_id = $wpdb->get_var( $wpdb->prepare( "SELECT currency_id FROM {$wpdb->prefix}sr_reservation_assets WHERE id = %d", $sr_form_data->reservation_asset_id ) );

				if ( $check_plugin_hub['status'] == 1 ) {
					foreach ( $sr_form_data->facility_id as $key => $value ) {
						$wpdb->insert( $wpdb->prefix . 'sr_facility_room_type_xref', array( 'facility_id' => $value, 'room_type_id' => $get_last_id ) );
					}
				}

				if ( ! empty( $sr_form_data->coupons ) ) {
					foreach ( $sr_form_data->coupons as $srform_coupon ) {
						$wpdb->insert( $wpdb->prefix . 'sr_room_type_coupon_xref', array(
							'room_type_id' => $get_last_id,
							'coupon_id'    => $srform_coupon
						) );
					}
				}
				if ( ! empty( $sr_form_data->extras ) ) {
					foreach ( $sr_form_data->extras as $srform_extra ) {
						$wpdb->insert( $wpdb->prefix . 'sr_room_type_extra_xref', array(
							'room_type_id' => $get_last_id,
							'extra_id'     => $srform_extra
						) );
					}
				}
				$wpdb->insert( $wpdb->prefix . 'sr_tariffs',
					array(
						'currency_id'   => $get_currency_id,
						'valid_from'    => '0000-00-00',
						'valid_to'      => '0000-00-00',
						'room_type_id'  => $get_last_id,
						'title'         => $sr_form_data->standard_tariff_title,
						'description'   => $sr_form_data->standard_tariff_description,
						'type'          => 0,
						'limit_checkin' => '',
					)
				);
				$get_last_tariff_id = $wpdb->insert_id;
				$insert_values      = array();
				foreach ( $sr_form_data->default_tariff as $day => $price ) {
					$insert_values[] = "('$get_last_tariff_id', $price, $day, NULL, NULL, NULL)";
				}
				$values_convert       = implode( ',', $insert_values );
				$query_tariff_details = "INSERT INTO {$wpdb->prefix}sr_tariff_details (`tariff_id`, `price`, `w_day`, `guest_type`, `from_age`, `to_age`) VALUES $values_convert";
				$wpdb->query( $wpdb->prepare( $query_tariff_details, 10 ) );
				$custom_field_data_update = array();
				foreach ( $sr_form_data->customfields as $keys => $values ) {
					foreach ( $values as $key => $value ) {
						$field_key                = $keys . '.' . $key;
						$custom_field_data_update = array_merge( $custom_field_data_update, array( $field_key => $value ) );
					}
				}
				$solidres_custom_fields = new SR_Custom_Field( array(
					'id'              => $get_last_id,
					'group_namespace' => 'roomtype_custom_fields',
					'type'            => 'room_type'
				) );
				$solidres_custom_fields->set( $custom_field_data_update );
				foreach ( $sr_form_data->mediaId as $key => $value ) {
					$wpdb->insert( $wpdb->prefix . 'sr_media_roomtype_xref', array(
						'media_id'     => $value,
						'room_type_id' => $get_last_id,
						'weight'       => $key
					) );
				}
				foreach ( $sr_form_data->roomsnew as $key => $value ) {
					$wpdb->insert( $wpdb->prefix . 'sr_rooms', array(
						'label'        => $value,
						'room_type_id' => $get_last_id
					) );
				}
				$message = 1;
				wp_redirect( admin_url( 'admin.php?page=sr-room-types&action=edit&id=' . $get_last_id . '&message=' . $message ) );
				exit;
			} else {
				$message = __( 'Add roomtype failed', 'solidres' );
				SR_Helper::error_message( $message );
			}
		}
	} ?>

	<div id="wpbody">
		<div id="wpbody-content" aria-label="Main content" tabindex="0" style="overflow: hidden;">
			<div class="wrap srform_wrapper">
				<h2><?php _e( 'Add new room type', 'solidres' ); ?></h2>

				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
						<form name="srform_add_new_roomtype" action="" method="post" id="srform">
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
							<input type="submit" name="add_new_room_type" value="<?php _e( 'Save', 'solidres' ); ?>"
							       class="srform_button button button-primary button-large add_new_roomtype">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }