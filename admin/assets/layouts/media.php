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
} ?>

<div id="asset_media" class="postbox">
	<div class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e( 'Media', 'solidres' ); ?></span></h3>

	<div class="inside">
		<div class="gallery_img sortable">
			<?php echo isset( $id ) ? SR_Helper::get_images_gallery_asset( $id ) : SR_Helper::get_images_gallery_asset(); ?>
		</div>
		<div class="clr"></div>
		<div style="text-align: center">
			<input type="button" class="choose_img_gallery button" name="custom_image_btn" value="Choose Image"/>
		</div>
		<div class="clr"></div>
	</div>
</div>