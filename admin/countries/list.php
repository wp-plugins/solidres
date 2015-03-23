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

class SR_Countries_Table_Data extends Solidres_List_Table {
	public $datatable;

	function __construct() {
		global $status, $state, $wpdb, $string_search;
		$countries     = new SR_Country();
		$status        = isset( $_GET['status'] ) ? $_GET['status'] : null;
		$string_search = isset( $_GET['s'] ) ? $_GET['s'] : null;
		$country_id    = isset( $_GET['id'] ) ? $_GET['id'] : null;
		$ids           = (array) $country_id;
		$action        = isset( $_GET['action'] ) ? $_GET['action'] : null;
		$state         = SR_Helper::get_listview_state( $status );

		if ( isset( $action ) && $action == 'edit' && isset( $country_id ) && $country_id != null ) {
			sr_edit_country_item( $country_id );
		}
		if ( $action == 'draft' || $action == 'publish' || $action == 'trash' || $action == 'untrash' ) {
			$countries->update_states( $action, $country_id, $ids );
		}

		if ( isset( $action ) && $action == 'delete' && isset( $country_id ) && $country_id != null ) {
			foreach ( $ids as $id ) {
				$country_name = $wpdb->get_row( $wpdb->prepare( "SELECT name FROM {$wpdb->prefix}sr_countries WHERE id = %d", $id ) );
				$return       = $countries->delete( $id );
				if ( $return === false ) {
					$message = __( 'Error, can not delete <span class="bold"> ' . $country_name->name . ' </span> because it is containing reservations or states. You must delete all its room reservations or states first.', 'solidres' );
					SR_Helper::error_message( $message );
				} else {
					$message = __( '<span class="bold"> ' . $country_name->name . ' </span> permanently deleted.', 'solidres' );
					SR_Helper::update_message( $message );
				}
			}
		}

		if ( $string_search != '' ) {
			if ( stripos( $string_search, 'id:' ) === 0 ) {
				$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sr_countries WHERE id =  %d", (int) substr( $string_search, 3 ) ) );
			} else {
				$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sr_countries WHERE name LIKE %s", '%' . $string_search . '%' ) );
			}
		} else if ( is_null( $state ) ) {
			$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}sr_countries WHERE state = 0 OR state = 1" );
		} else {
			$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}sr_countries WHERE state = %d", $state ) );
		}
		$this->datatable = array();
		foreach ( $results as $result ) {
			$published          = SR_Helper::view_status( $result->state );
			$this->datatable [] = array( 'id' => $result->id, 'name' => $result->name, 'published' => $published );
		}
		parent::__construct( array(
			'singular' => __( 'country' ),
			'plural'   => __( 'countries' ),
			'ajax'     => false,
		) );
	}

	function no_items() {
		_e( 'No country found!', 'solidres' );
	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
			case 'name':
			case 'published':
				return $item[ $column_name ];
			default:
				return print_r( $item, true );
		}
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'id'        => array( 'id', false ),
			'name'      => array( 'name', false ),
			'published' => array( 'published', false ),
		);

		return $sortable_columns;
	}

	function get_columns() {
		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'id'        => __( 'ID', 'solidres' ),
			'name'      => __( 'Name', 'solidres' ),
			'published' => __( 'Published', 'solidres' ),
		);

		return $columns;
	}
}

function sr_countries() {
	global $ListTableData, $status, $string_search;
	$helper        = new SR_Helper();
	$ListTableData = new SR_Countries_Table_Data();
	$ListTableData->prepare_items();
	$action = isset( $_GET['action'] ) ? $_GET['action'] : null;
	$helper->listview( 'sr_countries', $action, $string_search, $status, $ListTableData );
}