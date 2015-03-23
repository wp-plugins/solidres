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

<div id="roomtype_complex_tariff" class="postbox closed open">
	<div class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e( 'Complex tariff', 'solidres' ); ?></span></h3>

	<div class="inside">
		<?php
		$message_update = __( 'This feature allows you to configure more flexible tariff, more info can be found <a href="http://www.solidres.com/features-highlights#feature-complextariff" target="_blank">here.</a>', 'solidres' );
		$message_trash  = __( '<strong>Notice:</strong> Complex Tariff and User are not installed or enabled. <a target="blank" href="https://www.solidres.com/subscribe/levels">Become a subscriber and download it now.</a>', 'solidres' );
		SR_Helper::update_message( $message_update );
		SR_Helper::trash_message( $message_trash );
		?>
	</div>
</div>