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

$solidres_room_type = new SR_Room_Type;
if ( isset( $id ) ) {
	$custom_fields = $solidres_room_type->load_custom_fields( $id );
} else {
	$custom_fields = $solidres_room_type->load_custom_fields();
}
?>

<div id="roomtype_custom_fields" class="postbox closed open">
	<div class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e( 'Custom fields', 'solidres' ); ?></span></h3>

	<div class="inside">
		<div class="add_tabs_dynamic">
			<input type="button" id="add_new_group" value="<?php _e( 'Add New Group', 'solidres' ); ?>"/>
			<input type="text" id="group_name" value="" placeholder="<?php _e( 'Enter group name', 'solidres' ); ?>">
		</div>

		<div id="custom_fields_tab" class="<?php echo empty( $load_data_custom_fields ) ? 'nodisplay' : ''; ?>">
			<?php echo $custom_fields; ?>
		</div>
	</div>
</div>