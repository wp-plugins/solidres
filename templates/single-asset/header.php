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

$solidres_country = new SR_Country;
$country = $solidres_country->load( $asset->country_id );

$asset_custom_fields = new SR_Custom_Field( array( 'id' => (int) $asset->id, 'type' => 'asset' ) );
$custom_fields = $asset_custom_fields->create_array_group();
?>
<div class="row-fluid">
	<h3>
		<?php echo esc_attr( $asset->name ) . ' '; ?>
		<?php for ( $i = 1; $i <= $asset->rating; $i++ ) : ?>
			<i class="rating icon-star uk-icon-star fa-star"></i>
		<?php endfor ?>
	</h3>
	<span class="address_1 reservation_asset_subinfo">
		<?php
		echo esc_attr( $asset->address_1 .', '.
				( ! empty( $asset->postcode ) ? $asset->postcode.', ' : '' ) .
				( ! empty( $asset->city ) ? $asset->city.', ' : '' ) .
					   $country->name )
		?>
		<a class="show_map" href="#inline_map"><?php _e( 'Show map', 'solidres' ) ?></a>
	</span>

	<span class="address_2 reservation_asset_subinfo">
		<?php echo esc_attr( $asset->address_2 ) ?>
	</span>

	<span class="phone reservation_asset_subinfo">
		<?php _e( 'Phone: ', 'solidres' ) ?> <?php echo esc_attr( $asset->phone ) ?>
	</span>

	<span class="fax reservation_asset_subinfo">
		<?php _e( 'Fax: ', 'solidres' ) ?> <?php echo esc_attr( $asset->fax ) ?>
	</span>
	<?php
	if ( isset( $custom_fields['socialnetworks'] ) ) :
	?>
	<span class="social_network reservation_asset_subinfo clearfix">
<?php
	foreach ( $custom_fields['socialnetworks'] as $network ) :
		if ( ! empty( $network[1] ) ) :
			$network_parts = explode( '.', $network[0] );
			?>
				<a href="<?php echo esc_url( $network[1] );?>" target="_blank">
					<div alt="" class="dashicons dashicons-<?php echo esc_attr( $network_parts[2] )?>"></div>
				</a>
			<?php
		endif;
	endforeach;
?>
	</span>
	<?php
	endif; ?>
</div>

<div class="row-fluid">
	<div class="span12">
		<?php require( 'simple-gallery.php' ); ?>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<p><?php echo $asset->description ?></p>
	</div>
</div>