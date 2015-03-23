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

function sr_add_new_asset() {
	global $wpdb;
	$current_user = wp_get_current_user();
	$author_id    = $current_user->ID;
	$today        = date( 'Y-m-d H:i:s' );

	$hub              = 'solidres-hub/solidres-hub.php';
	$check_plugin_hub = solidres_check_plugin( $hub );

	if ( isset( $_POST['add_new_asset'] ) ) {
		$sr_form_data = (object) $_POST['srform'];
		$check_slug   = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}sr_reservation_assets WHERE alias = '%s'", $sr_form_data->alias ) );
		if ( $check_slug > 0 ) { ?>
			<div id="message" class="error below-h2"><p><?php _e( 'Asset slug already exists.', 'solidres' ); ?></p>
			</div>
		<?php } else {
			$metadata_json_data = json_encode( $sr_form_data->metadata );
			$param_json_data    = json_encode( $sr_form_data->params );
			$columns            = array(
				'category_id'           => $sr_form_data->category_id,
				'name'                  => $sr_form_data->name,
				'alias'                 => $sr_form_data->alias,
				'address_1'             => $sr_form_data->address_1,
				'address_2'             => $sr_form_data->address_2,
				'city'                  => $sr_form_data->city,
				'postcode'              => $sr_form_data->postcode,
				'phone'                 => $sr_form_data->phone,
				'description'           => $sr_form_data->description,
				'email'                 => $sr_form_data->email,
				'website'               => $sr_form_data->website,
				'featured'              => 0,
				'fax'                   => $sr_form_data->fax,
				'country_id'            => $sr_form_data->country_id,
				'currency_id'           => $sr_form_data->currency_id,
				'metakey'               => $sr_form_data->metakey,
				'metadesc'              => $sr_form_data->metadesc,
				'xreference'            => $sr_form_data->xreference,
				'metadata'              => $metadata_json_data,
				'state'                 => $sr_form_data->state,
				'default'               => $sr_form_data->default,
				'rating'                => $sr_form_data->rating,
				'deposit_required'      => $sr_form_data->deposit_required,
				'deposit_is_percentage' => $sr_form_data->deposit_is_percentage,
				'deposit_amount'        => $sr_form_data->deposit_amount,
				'created_by'            => $sr_form_data->created_by,
				'created_date'          => $today,
				'modified_date'         => '0000-00-00 00:00:00',
				'modified_by'           => $author_id,
				'params'                => $param_json_data,
			);

			if ( $sr_form_data->geo_state_id != null ) {
				$columns['geo_state_id'] = $sr_form_data->geo_state_id;
			}
			if ( $sr_form_data->partner_id != null ) {
				$columns['partner_id'] = $sr_form_data->partner_id;
			}
			if ( $sr_form_data->tax_id != null ) {
				$columns['tax_id'] = $sr_form_data->tax_id;
			}
			$add_asset = $wpdb->insert( $wpdb->prefix . 'sr_reservation_assets', $columns );
			if ( $add_asset == true ) {
				$last_id = $wpdb->insert_id;

				foreach ( $sr_form_data->mediaId as $key => $value ) {
					$wpdb->insert( $wpdb->prefix . 'sr_media_reservation_assets_xref',
						array(
							'media_id'             => $value,
							'reservation_asset_id' => $last_id,
							'weight'               => $key
						) );
				}

				if ( $check_plugin_hub['status'] == 1 ) {
					foreach ( $sr_form_data->facility_id as $key => $value ) {
						$wpdb->insert( $wpdb->prefix . 'sr_facility_reservation_asset_xref',
							array( 'facility_id' => $value, 'reservation_asset_id' => $last_id ) );
					}

					foreach ( $sr_form_data->theme_id as $key => $value ) {
						$wpdb->insert( $wpdb->prefix . 'sr_reservation_asset_theme_xref',
							array( 'theme_id' => $value, 'reservation_asset_id' => $last_id ) );
					}
				}

				$solidres_config = new SR_Config( array(
					'scope_id'       => $last_id,
					'data_namespace' => 'payments/paylater'
				) );
				$solidres_config->set( array(
					'paylater_enabled'          => $sr_form_data->payments['paylater_enabled'],
					'paylater_is_default'       => $sr_form_data->payments['paylater_is_default'],
					'paylater_frontend_message' => $sr_form_data->payments['paylater_frontend_message'],
				) );

				$solidres_config = new SR_Config( array(
					'scope_id'       => $last_id,
					'data_namespace' => 'payments/bankwire'
				) );
				$solidres_config->set( array(
					'bankwire_enabled'          => $sr_form_data->payments['bankwire_enabled'],
					'bankwire_is_default'       => $sr_form_data->payments['bankwire_is_default'],
					'bankwire_frontend_message' => $sr_form_data->payments['bankwire_frontend_message'],
					'bankwire_accountname'      => $sr_form_data->payments['bankwire_accountname'],
					'bankwire_accountdetails'   => $sr_form_data->payments['bankwire_accountdetails'],
				) );

				$custom_field_data_update = array();
				foreach ( $sr_form_data->customfields as $keys => $values ) {
					foreach ( $values as $key => $value ) {
						$field_key                = $keys . '.' . $key;
						$custom_field_data_update = array_merge( $custom_field_data_update, array( $field_key => $value ) );
					}
				}
				$solidres_custom_fields = new SR_Custom_Field( array(
					'id'              => $last_id,
					'group_namespace' => 'reservationasset_extra_fields'
				) );
				$solidres_custom_fields->set( $custom_field_data_update );

				$message = 1;
				wp_redirect( admin_url( 'admin.php?page=sr-assets&action=edit&id=' . $last_id . '&message=' . $message ) );
				exit;
			} else {
				$message = __( 'Add new asset failed', 'solidres' );
				SR_Helper::error_message( $message );
			}
		}
	} ?>

	<div id="wpbody">
		<div id="wpbody-content" aria-label="Main content" tabindex="0" style="overflow: hidden;">
			<div class="wrap srform_wrapper">
				<h2><?php _e( 'Add new asset', 'solidres' ); ?></h2>

				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
						<form name="srform_add_new_asset" action="" method="post" id="srform">
							<div id="postbox-container-1" class="postbox-container">
								<div id="side-sortables" class="meta-box-sortables ui-sortable">
									<?php require( 'layouts/roomtype.php' ); ?>
									<?php require( 'layouts/media.php' ); ?>
									<?php require( 'layouts/extra.php' ); ?>
								</div>
							</div>
							<div id="postbox-container-2" class="postbox-container">
								<div id="normal-sortables" class="meta-box-sortables ui-sortable">
									<?php require( 'layouts/general-infomation.php' ); ?>
									<?php require( 'layouts/publishing.php' ); ?>
									<?php require( 'layouts/custom-fields.php' ); ?>
									<?php require( 'layouts/metadata.php' ); ?>
									<?php require( 'layouts/payments.php' ); ?>
									<?php require( 'layouts/facility.php' ); ?>
									<?php require( 'layouts/theme.php' ); ?>
								</div>
							</div>
							<input type="submit" name="add_new_asset" value="<?php _e( 'Save', 'solidres' ); ?>"
							       class="srform_button button button-primary button-large add_new_asset">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }