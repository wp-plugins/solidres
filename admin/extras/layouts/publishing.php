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
if ( isset( $sr_form_data->params ) ) {
	if ( isset( $_POST['edit_extra'] ) ){
		$sr_form_data->params = json_encode( $sr_form_data->params );
	}
	$json_param = json_decode( $sr_form_data->params, true );
}

?>

<div id="extra_publishing" class="postbox">
	<div class="handlediv"><br></div>
	<h3 class="hndle"><span><?php _e( 'Publishing', 'solidres' ); ?></span></h3>

	<div class="inside">
		<table class="form-table">
			<tbody>
			<tr>
				<td class="first"><label for="srform_article"
				                         title="<?php _e( 'Select an article for this extra, this article could be used to show full information of this extra in front end.', 'solidres' ); ?>"><?php _e( 'Article', 'solidres' ); ?></label>
				</td>
				<td>
					<?php echo wp_dropdown_pages( array(
						'name'             => 'srform[params][article]',
						'echo'             => false,
						'id'               => 'srform_article',
						'show_option_none' => __( 'Select an Article', 'solidres' ),
						'selected'         => ! empty( $json_param['article'] ) ? $json_param['article'] : false
					) ); ?>
				</td>
			</tr>
			<tr>
				<td class="first"><label for="srform_image"
				                         title="<?php _e( 'Select an image for this extra, it could be used in front end display.', 'solidres' ); ?>"><?php _e( 'Image', 'solidres' ); ?></label>
				</td>
				<td>
					<input type="text" name="srform[params][image]" size="30"
					       value="<?php echo isset( $json_param['image'] ) ? $json_param['image'] : ''; ?>"
					       id="srform_image" readonly="true"/>
					<input type="button" name="upload_srform_image" class="botton upload_srform_image" value="Upload"/>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>