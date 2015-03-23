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

/**
 * Extra handler class
 * @package 	Solidres
 * @subpackage	Extra
 */
class SR_Extra {
	/**
	 * The database object
	 * @var object
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	/**
	 * Update states for listview
	 *
	 * @param $action
	 * @param $extra_id
	 * @param $ids
	 */
	public function update_states( $action, $extra_id, $ids ){
		$states = array(
			'draft' => array( 'state' => 0, 'action' => 'moved', 'title' => 'Draft' ),
			'publish' => array( 'state' => 1, 'action' => 'moved', 'title' => 'Publish' ),
			'trash' => array( 'state' => -2, 'action' => 'moved', 'title' => 'Trash' ),
			'untrash' => array( 'state' => 0, 'action' => 'restored', 'title' => 'Trash' ),
		);

		if ( isset( $action ) && array_key_exists ( $action, $states ) &&  isset( $extra_id ) && $extra_id != null ) {
			foreach ( $ids as $id ) {
				$this->wpdb->update( $this->wpdb->prefix . 'sr_extras', array( 'state' => $states[$action]['state'] ), array( 'id' => $id ) );
			}
			if ( count( $ids ) == 1 ) {
				$message = __( '1 extra ' . $states[$action]['action'] . ' to the ' . $states[$action]['title'], 'solidres' );
				SR_Helper::update_message( $message );
			}
			else {
				$message = __( count( $ids ).' extras ' . $states[$action]['action'] . ' to the ' . $states[$action]['title'], 'solidres' );
				SR_Helper::update_message( $message );
			}
		}
	}

	/**
	 * Delete permanently action
	 *
	 * @param $id
	 * @return bool
	 */
	public function delete( $id ){
		add_filter( 'query', 'solidres_wp_db_null_value' );
		$this->wpdb->update( $this->wpdb->prefix.'sr_reservation_room_extra_xref', array( 'extra_id' => 'NULL' ), array( 'extra_id' => $id ) );
		$this->wpdb->delete( $this->wpdb->prefix.'sr_room_type_extra_xref', array( 'extra_id' => $id ) );
		$this->wpdb->update( $this->wpdb->prefix.'sr_reservation_extra_xref', array( 'extra_id' => 'NULL' ), array( 'extra_id' => $id ) );
		$this->wpdb->delete( $this->wpdb->prefix.'sr_extras', array( 'id' => $id ) );
		remove_filter( 'query', 'solidres_wp_db_null_value' );
	}

	/**
	 * Get a single extra by id
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function load( $id ) {
		$item = $this->wpdb->get_row( "SELECT * FROM {$this->wpdb->prefix}sr_extras WHERE id = $id" );
		if ( isset ( $item->id ) ) {
			$assetTable = new SR_Asset();
			$taxTable = new SR_Tax();
			$options = get_option( 'solidres_plugin' );
			$showTaxIncl = isset( $options['show_price_with_tax'] ) ? $options['show_price_with_tax'] : 0;

			$assettable = $assetTable->load( $item->reservation_asset_id );
			$solidresCurrency = new SR_Currency( 0, $assettable->currency_id );

			$taxtable = $taxTable->load( $item->tax_id );
			$taxAmount = 0;

			if ( ! empty( $taxtable->rate ) ) {
				$taxAmount = $item->price * $taxtable->rate;
			}
			$item->currencyTaxIncl = clone $solidresCurrency;
			$item->currencyTaxExcl = clone $solidresCurrency;
			$item->currencyTaxIncl->setValue( $item->price + $taxAmount );
			$item->currencyTaxExcl->setValue( $item->price );
			$item->price_tax_incl = $item->price + $taxAmount;
			$item->price_tax_excl = $item->price;

			if ( $showTaxIncl ) {
				$item->currency = $item->currencyTaxIncl;
			}
			else {
				$item->currency = $item->currencyTaxExcl;
			}
		}
		return $item;
	}

	public function load_by_room_type_id( $room_type_id, $state, $show_price_with_tax ) {
		$extras = $this->wpdb->get_results( "
			SELECT * FROM {$this->wpdb->prefix}sr_extras as a
 			INNER JOIN {$this->wpdb->prefix}sr_room_type_extra_xref as b
 			ON a.id = b.extra_id AND b.room_type_id = $room_type_id
 			WHERE a.state = '$state'
 			" );

		if ( !empty( $extras ) ) {
			$solidres_asset = new SR_Asset();
			$asset = $solidres_asset->load( $extras[0]->reservation_asset_id );
			$solidres_tax = new SR_Tax();
			$tax = $solidres_tax->load( $extras[0]->tax_id );
			$solidresCurrency = new SR_Currency( 0, $asset->currency_id );

			foreach ( $extras as $extra ) {
				if ( $asset->id != $extra->reservation_asset_id ) {
					$asset = $solidres_asset->load( $extra->reservation_asset_id );
				}

				if ( isset( $tax->id ) && $tax->id != $extra->tax_id ) {
					$tax = $solidres_tax->load( $extra->tax_id );
				}

				$taxAmount = 0;
				if ( ! empty( $tax->rate ) ) {
					$taxAmount = $extra->price * $tax->rate;
				}

				$extra->currencyTaxIncl = clone $solidresCurrency;
				$extra->currencyTaxExcl = clone $solidresCurrency;
				$extra->currencyTaxIncl->setValue( $extra->price + $taxAmount );
				$extra->currencyTaxExcl->setValue( $extra->price );

				if ( $show_price_with_tax ) {
					$extra->currency = $extra->currencyTaxIncl;
				} else {
					$extra->currency = $extra->currencyTaxExcl;
				}
			}
		}

		return $extras;
	}

	/**
	 * Get a list of room type by asset's id
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function load_by_asset_id ( $asset_id ) {
		return $this->wpdb->get_results( $this->wpdb->prepare( "SELECT * FROM {$this->wpdb->prefix}sr_extras WHERE reservation_asset_id = %d AND state = 1", $asset_id ) );
	}

	public function load_by_reservation_asset_id( $asset_id, $state, $show_price_with_tax ) {
		$extras = $this->wpdb->get_results( $this->wpdb->prepare(
			"SELECT * FROM {$this->wpdb->prefix}sr_extras as a
 			WHERE a.state = %d AND a.reservation_asset_id = %d AND a.charge_type = 1", $state, $asset_id) );

		if ( !empty( $extras ) ) {
			$solidres_asset = new SR_Asset();
			$asset = $solidres_asset->load( $extras[0]->reservation_asset_id );
			$solidres_tax = new SR_Tax();
			$tax = $solidres_tax->load( $extras[0]->tax_id );
			$solidresCurrency = new SR_Currency( 0, $asset->currency_id );

			foreach ( $extras as $extra ) {
				if ( $asset->id != $extra->reservation_asset_id ) {
					$asset = $solidres_asset->load( $extra->reservation_asset_id );
				}

				if ( isset( $tax->id ) && $tax->id != $extra->tax_id ) {
					$tax = $solidres_tax->load( $extra->tax_id );
				}

				$taxAmount = 0;
				if ( ! empty( $tax->rate ) ) {
					$taxAmount = $extra->price * $tax->rate;
				}

				$extra->currencyTaxIncl = clone $solidresCurrency;
				$extra->currencyTaxExcl = clone $solidresCurrency;
				$extra->currencyTaxIncl->setValue( $extra->price + $taxAmount );
				$extra->currencyTaxExcl->setValue( $extra->price );

				if ( $show_price_with_tax ) {
					$extra->currency = $extra->currencyTaxIncl;
				} else {
					$extra->currency = $extra->currencyTaxExcl;
				}
			}
		}

		return $extras;
	}
}