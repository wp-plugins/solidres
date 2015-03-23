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

$solidres_media = new SR_Media;
$media = $solidres_media->load_by_asset_id( $asset->id );

?>

<div class="row-fluid">
	<?php if ( ! empty( $media ) ):
		$first_media_attr = wp_get_attachment_image_src( $media[0]->media_id, 'full' );
		?>
		<div class="main-photo span5">
			<a class="sr-photo" href="<?php echo $first_media_attr[0]; ?>">
				<?php echo wp_get_attachment_image( $media[0]->media_id, array( 300, 250) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="other-photos clearfix span7">
		<?php foreach ( $media as $media ) :
			$media_attr = wp_get_attachment_image_src( $media->media_id, 'full' )
			?>
			<a class="sr-photo" href="<?php echo $media_attr[0]; ?>">
				<?php echo wp_get_attachment_image( $media->media_id, array( 75, 75) ); ?>
			</a>
		<?php endforeach ?>
	</div>
</div>