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

function sr_customers_notinstall() { ?>
	<div id="sr_panel_right" class="sr_list_view">
		<?php
		$message_update = __( 'This feature allow you to select one or multiple themes for your reservation asset and they can be used in front end search and filtering.', 'solidres' );
		$message_trash  = __( '<strong>Notice:</strong> Solidres Users plugin is not installed. <a target="blank" href="https://www.solidres.com/subscribe/levels">Become a subscriber and download it now.</a>', 'solidres' );
		SR_Helper::update_message( $message_update );
		SR_Helper::trash_message( $message_trash );
		?>
	</div>
<?php
}

function sr_customers_inactive() { ?>
	<div id="sr_panel_right" class="sr_list_view">
		<?php
		$message_trash = __( '<strong>Notice:</strong> please active <strong>Solidres Users plugin</strong>. <a target="blank" href="' . admin_url() . 'plugins.php"><strong>Active now</strong></a>', 'solidres' );
		SR_Helper::trash_message( $message_trash );
		?>
	</div>
<?php }