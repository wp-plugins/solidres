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

function sr_edit_category_item( $id ) {
	$message = isset( $_GET['message'] ) ? $_GET['message'] : '';
	global $wpdb;
	$categories = new SR_Category();
	if ( ! isset( $_POST['edit_asset_category'] ) ) {
		$sr_form_data = $categories->load( $id );
	} else {
		$sr_form_data     = (object) $_POST['srform'];
		$get_current_slug = $wpdb->get_row( $wpdb->prepare( "SELECT slug FROM {$wpdb->prefix}sr_categories WHERE id = %d", $id ) );
		$check_slug       = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}sr_categories WHERE slug = '%s' AND slug != '%s'", $sr_form_data->slug, $get_current_slug->slug ) );
		if ( $check_slug > 0 ) {
			$message = ''; ?>
			<div id="message" class="error below-h2">
				<p><?php _e( 'Asset slug name already exists.', 'solidres' ); ?></p></div>
		<?php } else {
			$update_category = $wpdb->update( $wpdb->prefix . 'sr_categories',
				array(
					'name'      => $sr_form_data->name,
					'slug'      => $sr_form_data->slug,
					'state'     => $sr_form_data->state,
					'parent_id' => $sr_form_data->parent_id,
				),
				array(
					'id' => $id,
				)
			);
			if ( $update_category == true ) {
				$message = 2;
				wp_redirect( admin_url( 'admin.php?page=sr-categories&action=edit&id=' . $id . '&message=' . $message ) );
				exit;
			} else {
				$message = __( 'Update category failed', 'solidres' );
				SR_Helper::error_message( $message );
			}
		}
	}
	?>
	<div class="wrap">
		<div id="wpbody">
			<div id="message" class="updated below-h2 <?php echo $message == 1 ? '' : 'nodisplay'; ?>">
				<p><?php _e( 'Asset category published.', 'solidres' ); ?></p></div>
			<div id="message" class="updated below-h2 <?php echo $message == 2 ? '' : 'nodisplay'; ?>">
				<p><?php _e( 'Asset category updated.', 'solidres' ); ?></p></div>
			<h2><?php _e( 'Edit Asset category', 'solidres' ); ?><a
					href="<?php echo admin_url( 'admin.php?page=sr-add-new-category' ); ?>"
					class="add-new-h2"><?php _e( 'Add New', 'solidres' ); ?></a></h2>

			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content" class="edit-form-section">
					<div id="namediv" class="stuffbox">
						<h3><label for="name"><?php _e( 'General infomartion', 'solidres' ); ?></label></h3>

						<div class="inside">
							<form name="srform_edit_asset_category" action="" method="post" id="srform">
								<?php require( 'layouts/general-infomation.php' ); ?>
								<input type="submit" name="edit_asset_category"
								       value="<?php _e( 'Save', 'solidres' ); ?>"
								       class="srform_button button button-primary button-large edit_asset_category">
							</form>
							<br>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}