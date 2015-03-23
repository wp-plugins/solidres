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

function sr_add_new_category() {
	global $wpdb;
	if ( isset( $_POST['add_new_asset_category'] ) ) {
		$sr_form_data = (object) $_POST['srform'];
		$check_slug   = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}sr_categories WHERE slug = '%s'", $sr_form_data->slug ) );
		if ( $check_slug > 0 ) { ?>
			<div id="message" class="error below-h2">
				<p><?php _e( 'Asset category slug already exists.', 'solidres' ); ?></p></div>
		<?php } else {
			$add_category = $wpdb->insert( $wpdb->prefix . 'sr_categories',
				array(
					'name'      => $sr_form_data->name,
					'slug'      => $sr_form_data->slug,
					'state'     => $sr_form_data->state,
					'parent_id' => $sr_form_data->parent_id,
				)
			);
			if ( $add_category == true ) {
				$last_id = $wpdb->insert_id;
				$message = 1;
				wp_redirect( admin_url( 'admin.php?page=sr-categories&action=edit&id=' . $last_id . '&message=' . $message ) );
				exit;
			} else {
				$message = __( 'Add category failed', 'solidres' );
				SR_Helper::error_message( $message );
			}
		}
	} ?>

	<div id="wpbody">
		<h2><?php _e( 'Add new category', 'solidres' ); ?></h2>

		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content" class="edit-form-section">
				<div id="namediv" class="stuffbox">
					<h3><label for="name"><?php _e( 'General infomartion', 'solidres' ); ?></label></h3>

					<div class="inside">
						<form name="srform_add_new_asset_category" action="" method="post" id="srform">
							<?php require( 'layouts/general-infomation.php' ); ?>
							<input type="submit" name="add_new_asset_category"
							       value="<?php _e( 'Save', 'solidres' ); ?>"
							       class="srform_button button button-primary button-large add_new_asset_category">
						</form>
						<br>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}