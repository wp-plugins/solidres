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

<div id="asset_theme" class="postbox closed open">
	<div class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e( 'Theme', 'solidres' ); ?></span></h3>
	<div class="inside">
		<?php
			$hub = 'solidres-hub/solidres-hub.php';
			$check_plugin_hub = solidres_check_plugin( $hub );
			if ( $check_plugin_hub['status'] == 2 ) {
				$message_update = __( 'This feature allow you to select one or multiple themes for your reservation asset and they can be used in front end search and filtering.', 'solidres' );
				$message_trash  = __( '<strong>Notice:</strong> Solidres Hub plugin is not installed. <a target="blank" href="https://www.solidres.com/subscribe/levels">Become a subscriber and download it now.</a>', 'solidres' );
				SR_Helper::update_message( $message_update );
				SR_Helper::trash_message( $message_trash );
			} else if ( $check_plugin_hub['status'] == 0 ) {
				$message_trash = __( '<strong>Notice:</strong> please active <strong>Solidres Hub plugin</strong>. <a target="blank" href="' . admin_url() . 'plugins.php"><strong>Active now</strong></a>', 'solidres' );
				SR_Helper::trash_message( $message_trash );
			} else {
				$themes = new SR_Theme();
				echo $themes->themes_selected( $id );
			}
		?>
	</div>
</div>