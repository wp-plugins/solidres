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

function sr_systems() {
	$solidres_plugins = array(
		'camera_slideshow',
		'complextariff',
		'hub',
		'invoice',
		'limitbooking',
		'simple_gallery',
		'discount',
		'advancedextra',
		'payment_paypal',
		'users',
	);

	$solidres_widgets = array(
		'advancedsearch',
		'camera',
		'checkavailability',
		'currency',
		'filter',
		'roomtypes',
		'locationmap',
		'coupons',
		'extras',
		'assets',
		'map',
	);
	?>

	<div id="wpbody">
		<h2><?php _e( 'Solidres System', 'solidres' ); ?></h2>

		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content" class="edit-form-section table_sr_system">
				<div id="namediv" class="stuffbox">
					<h3><label for="name"><?php _e( 'Version info', 'solidres' ); ?></label></h3>

					<div class="inside">
						<div class="sr_logo">
							<img src="<?php echo plugins_url( 'solidres/assets/images/logo_black.png' ); ?>"
							     alt="Solidres Logo">
						</div>
						<?php
						$message_version = __( 'Version ' . solidres_check_version( 'solidres/solidres.php' ) . '.Stable', 'solidres' );
						SR_Helper::update_message( $message_version );
						?>
					</div>
				</div>
				<div id="namediv" class="stuffbox">
					<h3><label for="name"><?php _e( 'Plugins status', 'solidres' ); ?></label></h3>

					<div class="inside">
						<table class="form-table">
							<thead>
							<tr>
								<th><?php _e( 'Plugin Name', 'solidres' ); ?></th>
								<th><?php _e( 'Plugin Status', 'solidres' ); ?></th>
							</tr>
							</thead>
							<tbody>
							<?php
							foreach ( $solidres_plugins as $key => $name ) {
								$check_plugin_result = solidres_check_plugin( 'solidres-' . $name . '/solidres-' . $name . '.php' );
								echo '<tr>';
								echo '<td class="first">solidres_' . $name . '</td>';
								echo '<td>' . $check_plugin_result['message'] . '</td>';
								echo '</tr>';
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
				<div id="namediv" class="stuffbox">
					<h3><label for="name"><?php _e( 'Widget status', 'solidres' ); ?></label></h3>

					<div class="inside">
						<table class="form-table">
							<thead>
							<tr>
								<th><?php _e( 'Widget Name', 'solidres' ); ?></th>
								<th><?php _e( 'Widget Status', 'solidres' ); ?></th>
							</tr>
							</thead>
							<tbody>
							<?php
							foreach ( $solidres_widgets as $key => $name ) {
								$check_plugin_result = solidres_check_plugin( 'solidres-' . $name . '/solidres-' . $name . '.php' );
								echo '<tr>';
								echo '<td class="first">solidres-' . $name . '</td>';
								echo '<td>' . $check_plugin_result['message'] . '</td>';
								echo '</tr>';
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
				<div id="namediv" class="stuffbox">
					<h3><label for="name"><?php _e( 'System check list', 'solidres' ); ?></label></h3>

					<div class="inside">
						<table class="form-table">
							<thead>
							<tr>
								<th><?php _e( 'Setting name', 'solidres' ); ?></th>
								<th><?php _e( 'Status', 'solidres' ); ?></th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td class="first"><?php _e( 'GD is enabled in your server', 'solidres' ); ?></td>
								<td><?php extension_loaded( 'gd' ) && function_exists( 'gd_info' ) ? _e( '<span class="sr_enable">YES</span>', 'solidres' ) : _e( '<span class="sr_warning">NO</span>', 'solidres' ); ?>
								</td>
							</tr>
							<tr>
								<td class="first"><?php _e( '/wp-content/upload is writable?', 'solidres' ); ?></td>
								<td><?php
									$upload_dir = wp_upload_dir();
									is_writable( $upload_dir['basedir'] ) ? _e( '<span class="sr_enable">Yes</span>', 'solidres' ) : _e( '<span class="sr_warning">NO</span>', 'solidres' ); ?></td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php }