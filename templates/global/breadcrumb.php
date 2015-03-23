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
?>

<?php if ( $breadcrumb ) : ?>

	<?php echo $wrap_before; ?>

	<?php foreach ( $breadcrumb as $key => $crumb ) : ?>

		<?php echo $before; ?>

		<?php if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 ) : ?>
			<?php echo '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>'; ?>
		<?php else : ?>
			<?php echo esc_html( $crumb[0] ); ?>
		<?php endif; ?>

		<?php echo $after; ?>

		<?php if ( sizeof( $breadcrumb ) !== $key + 1 ) : ?>
			<?php echo $delimiter; ?>
		<?php endif; ?>

	<?php endforeach; ?>

	<?php echo $wrap_after; ?>

<?php endif; ?>