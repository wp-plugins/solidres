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

class SR_Currencies_Table_Data extends Solidres_List_Table {
	public $datatable;

	function __construct() {
		global $status, $state, $wpdb, $string_search;
		$currencies    = new SR_Currency();
		$status        = isset( $_GET['status'] ) ? $_GET['status'] : null;
		$string_search = isset( $_GET['s'] ) ? $_GET['s'] : null;
		$currency_id   = isset( $_GET['id'] ) ? $_GET['id'] : null;
		$ids           = (array) $currency_id;
		$action        = isset( $_GET['action'] ) ? $_GET['action'] : null;
		$state         = SR_Helper::get_listview_state( $status );

		if ( isset( $action ) && $action == 'edit' && isset( $currency_id ) && $currency_id != null ) {
			sr_edit_currency_item( $currency_id );
		}
		if ( $action == 'draft' || $action == 'publish' || $action == 'trash' || $action == 'untrash' ) {
			$currencies->update_states( $action, $currency_id, $ids );
		}
		if ( isset( $action ) && $action == 'delete' && isset( $currency_id ) && $currency_id != null ) {
			foreach ( $ids as $id ) {
				$currency_name = $wpdb->get_row( $wpdb->prepare( "SELECT currency_name FROM {$wpdb->prefix}sr_currencies WHERE id = %d", $id ) );
				$return        = $currencies->delete( $id );
				if ( $return === false ) {
					$message = __( 'Error, can not delete <span class="bold"> ' . $currency_name->currency_name . ' </span> because it is containing tarrifs or reservations. You must delete all its room tarrifs or reservations first.', 'solidres' );
					SR_Helper::error_message( $message );
				} else {
					$message = __( '<span class="bold"> ' . $currency_name->currency_name . ' </span> permanently deleted.', 'solidres' );
					SR_Helper::update_message( $message );
				}
			}
		}

		if ( $string_search != '' ) {
			if ( stripos( $string_search, 'id:' ) === 0 ) {
				$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sr_currencies WHERE id = %d", (int) substr( $string_search, 3 ) ) );
			} else {
				$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sr_currencies WHERE currency_name LIKE %s", '%' . $string_search . '%' ) );
			}
		} else if ( is_null( $state ) ) {
			$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}sr_currencies WHERE state = 0 OR state = 1" );
		} else {
			$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sr_currencies WHERE state = %d", $state ) );
		}
		$this->datatable = array();
		foreach ( $results as $result ) {
			$published          = SR_Helper::view_status( $result->state );
			$this->datatable [] = array( 'id'           => $result->id,
			                             'currencyname' => $result->currency_name,
			                             'published'    => $published,
			                             'currencycode' => $result->currency_code,
			                             'exchangerate' => $result->exchange_rate
			);
		}
		parent::__construct(
			array(
				'singular' => __( 'currency' ),
				'plural'   => __( 'currencies' ),
				'ajax'     => false,
			)
		);
	}

	function no_items() {
		_e( 'No currency found!', 'solidres' );
	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
			case 'currencyname':
			case 'published':
			case 'currencycode':
			case 'exchangerate':
				return $item[ $column_name ];
			default:
				return print_r( $item, true );
		}
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'id'           => array( 'id', false ),
			'currencyname' => array( 'name', false ),
			'published'    => array( 'published', false ),
			'currencycode' => array( 'currencycode', false ),
			'exchangerate' => array( 'exchangerate', false ),
		);

		return $sortable_columns;
	}

	function get_columns() {
		$columns = array(
			'cb'           => '<input type="checkbox" />',
			'id'           => __( 'ID', 'solidres' ),
			'currencyname' => __( 'Currency name', 'solidres' ),
			'published'    => __( 'Published', 'solidres' ),
			'currencycode' => __( 'Code', 'solidres' ),
			'exchangerate' => __( 'Exchange rate', 'solidres' ),
		);

		return $columns;
	}

	function column_currencyname( $item ) {
		global $status;
		if ( $status == 'trash' ) {
			$actions = array(
				'untrash' => sprintf( __( '<a href="?page=%s&action=%s&id=%s">Restore</a>', 'solidres' ), $_REQUEST['page'], 'untrash', $item['id'] ),
				'delete'  => sprintf( __( '<a href="?page=%s&action=%s&id=%s">Delete Permanently</a>', 'solidres' ), $_REQUEST['page'], 'delete', $item['id'] ),
			);
		} else {
			$actions = array(
				'edit'  => sprintf( __( '<a href="?page=%s&action=%s&id=%s">Edit</a>', 'solidres' ), $_REQUEST['page'], 'edit', $item['id'] ),
				'trash' => sprintf( __( '<a href="?page=%s&action=%s&id=%s">Trash</a>', 'solidres' ), $_REQUEST['page'], 'trash', $item['id'] ),
			);
		}

		return sprintf( '%1$s %2$s', $item['currencyname'], $this->row_actions( $actions ) );
	}
}

function sr_currencies() {
	global $ListTableData, $status, $string_search;
	$helper        = new SR_Helper();
	$ListTableData = new SR_Currencies_Table_Data();
	$ListTableData->prepare_items();
	$action = isset( $_GET['action'] ) ? $_GET['action'] : null;
	$helper->listview( 'sr_currencies', $action, $string_search, $status, $ListTableData );
}