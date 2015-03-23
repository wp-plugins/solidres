<?php
/*------------------------------------------------------------------------
  Solidres - Hotel booking plugin for WordPress
  ------------------------------------------------------------------------
  @Author    Solidres Team
  @Website   http://www.solidres.com
  @Copyright Copyright (C) 2013 - 2015 Solidres. All Rights Reserved.
  @License   GNU General Public License version 3, or later
------------------------------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

?>

<div class="row-fluid">
	<div class="span12">
		<?php
		$information_data_view = '';
		foreach ( $custom_fields as $key => $fields ) {
			if ( $key == 'socialnetworks' ) continue;
			$information_data_view .= '<h3>'.solidres_convertslugtostring( $key ).'</h3>';
			foreach ( $fields as $field ) {
				$information_data_view .= '<div class="row-fluid custom-field-row">';
				$information_data_view .= '<div class="span2 info-heading">'.ucfirst( $asset_custom_fields->split_field_name( solidres_convertslugtostring( $field[0] ) ) ).'</div>';
				$information_data_view .= '<div class="span10">'.$field[1].'</div>';
				$information_data_view .= '</div>';
			}
		}
		echo $information_data_view; ?>
	</div>
</div>