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

function sr_edit_asset_item( $id ) {
	global $wpdb;
	$current_user = wp_get_current_user();
	$author_id    = $current_user->ID;
	$today        = date( 'Y-m-d H:i:s' );
	$message      = isset( $_GET['message'] ) ? $_GET['message'] : '';

	$hub              = 'solidres-hub/solidres-hub.php';
	$check_plugin_hub = solidres_check_plugin( $hub );

	$solidres_asset = new SR_Asset();
	if ( ! isset( $_POST['edit_asset'] ) ) {
		$sr_form_data = $solidres_asset->load( $id );
	} else {
		$sr_form_data     = (object) $_POST['srform'];
		$get_current_slug = $wpdb->get_row( $wpdb->prepare( "SELECT alias FROM {$wpdb->prefix}sr_reservation_assets WHERE id = %d", $id ) );
		$check_slug       = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}sr_reservation_assets WHERE alias = '%s' AND alias != '%s'", $sr_form_data->alias, $get_current_slug->alias ) );
		if ( $check_slug > 0 ) {
			$message = ''; ?>
			<div id="message" class="error below-h2"><p><?php _e( 'Asset slug already exists.', 'solidres' ); ?></p></div>
		<?php } else {
			$columns = array(
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
				'state'                 => $sr_form_data->state,
				'default'               => $sr_form_data->default,
				'rating'                => $sr_form_data->rating,
				'deposit_required'      => $sr_form_data->deposit_required,
				'deposit_is_percentage' => $sr_form_data->deposit_is_percentage,
				'deposit_amount'        => $sr_form_data->deposit_amount,
				'created_by'            => $sr_form_data->created_by,
				'modified_date'         => $today,
				'modified_by'           => $author_id,
			);

			if ( $sr_form_data->geo_state_id != null ) {
				$columns['geo_state_id'] = $sr_form_data->geo_state_id;
			} else {
				$columns['geo_state_id'] = 'NULL';
			}
			if ( $sr_form_data->partner_id != null ) {
				$columns['partner_id'] = $sr_form_data->partner_id;
			} else {
				$columns['partner_id'] = 'NULL';
			}
			if ( $sr_form_data->tax_id != null ) {
				$columns['tax_id'] = $sr_form_data->tax_id;
			} else {
				$columns['tax_id'] = 'NULL';
			}

			add_filter( 'query', 'solidres_wp_db_null_value' );
			$update_asset = $wpdb->update( $wpdb->prefix . 'sr_reservation_assets', $columns, array( 'id' => $id ) );
			remove_filter( 'query', 'solidres_wp_db_null_value' );

			if ( $update_asset == true ) {
				$wpdb->delete( $wpdb->prefix . 'sr_media_reservation_assets_xref', array( 'reservation_asset_id' => $id ) );
				foreach ( $sr_form_data->mediaId as $key => $value ) {
					$wpdb->insert( $wpdb->prefix . 'sr_media_reservation_assets_xref',
						array(
							'media_id'             => $value,
							'reservation_asset_id' => $id,
							'weight'               => $key
						) );
				}

				if ( $check_plugin_hub['status'] == 1 ) {
					$wpdb->delete( $wpdb->prefix . 'sr_facility_reservation_asset_xref', array( 'reservation_asset_id' => $id ) );
					foreach ( $sr_form_data->facility_id as $key => $value ) {
						$wpdb->insert( $wpdb->prefix . 'sr_facility_reservation_asset_xref',
							array(
								'facility_id'          => $value,
								'reservation_asset_id' => $id
							) );
					}

					$wpdb->delete( $wpdb->prefix . 'sr_reservation_asset_theme_xref', array( 'reservation_asset_id' => $id ) );
					foreach ( $sr_form_data->theme_id as $key => $value ) {
						$wpdb->insert( $wpdb->prefix . 'sr_reservation_asset_theme_xref',
							array(
								'theme_id'             => $value,
								'reservation_asset_id' => $id
							) );
					}
				}

				$metadata_json_data = json_encode( $sr_form_data->metadata );
				$wpdb->update( $wpdb->prefix . 'sr_reservation_assets', array( 'metadata' => '' ), array( 'id' => $id ) );
				$wpdb->update( $wpdb->prefix . 'sr_reservation_assets', array( 'metadata' => $metadata_json_data ), array( 'id' => $id ) );

				$param_json_data = json_encode( $sr_form_data->params );
				$wpdb->update( $wpdb->prefix . 'sr_reservation_assets', array( 'params' => '' ), array( 'id' => $id ) );
				$wpdb->update( $wpdb->prefix . 'sr_reservation_assets', array( 'params' => $param_json_data ), array( 'id' => $id ) );

				$solidres_config = new SR_Config( array( 'scope_id' => $id, 'data_namespace' => 'payments/paylater' ) );
				$solidres_config->set( array(
					'paylater_enabled'          => $sr_form_data->payments['paylater_enabled'],
					'paylater_is_default'       => $sr_form_data->payments['paylater_is_default'],
					'paylater_frontend_message' => $sr_form_data->payments['paylater_frontend_message'],
				) );

				$solidres_config = new SR_Config( array( 'scope_id' => $id, 'data_namespace' => 'payments/bankwire' ) );
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
					'id'              => $id,
					'group_namespace' => 'reservationasset_extra_fields'
				) );
				$solidres_custom_fields->set( $custom_field_data_update );

				$message = 2;
				wp_redirect( admin_url( 'admin.php?page=sr-assets&action=edit&id=' . $id . '&message=' . $message ) );
				exit;
			} else {
				$message = __( 'Update asset failed', 'solidres' );
				SR_Helper::error_message( $message );
			}
		}
	} ?>
	<div id="wpbody">
		<div id="wpbody-content" aria-label="Main content" tabindex="0" style="overflow: hidden;">
			<div class="wrap srform_wrapper">
				<div id="message" class="updated below-h2 <?php echo $message == 1 ? '' : 'nodisplay'; ?>">
					<p><?php _e( 'Asset published.', 'solidres' ); ?></p></div>
				<div id="message" class="updated below-h2 <?php echo $message == 2 ? '' : 'nodisplay'; ?>">
					<p><?php _e( 'Asset updated.', 'solidres' ); ?></p></div>
				<h2><?php _e( 'Edit asset', 'solidres' ); ?> <a
						href="<?php echo admin_url( 'admin.php?page=sr-add-new-asset' ); ?>"
						class="add-new-h2"><?php _e( 'Add New', 'solidres' ); ?></a></h2>

				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
						<form name="srform_edit_asset" action="" method="post" id="srform">
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
							<input type="submit" name="edit_asset" value="<?php _e( 'Save', 'solidres' ); ?>"
							       class="srform_button button button-primary button-large edit_asset">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}