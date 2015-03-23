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

class SR_Categories_Table_Data extends Solidres_List_Table {
	public $datatable;

	function __construct() {
		global $wpdb, $status, $state, $string_search, $query_default;
		$categories    = new SR_Category();
		$status        = isset( $_GET['status'] ) ? $_GET['status'] : null;
		$string_search = isset( $_GET['s'] ) ? $_GET['s'] : null;
		$category_id   = isset( $_GET['id'] ) ? $_GET['id'] : null;
		$ids           = (array) $category_id;
		$action        = isset( $_GET['action'] ) ? $_GET['action'] : null;
		$query_default = "SELECT * FROM {$wpdb->prefix}sr_categories";
		$state         = SR_Helper::get_listview_state( $status );

		if ( isset( $action ) && $action == 'edit' && isset( $category_id ) && $category_id != null ) {
			sr_edit_category_item( $category_id );
		}
		if ( $action == 'draft' || $action == 'publish' || $action == 'trash' || $action == 'untrash' ) {
			$categories->update_states( $action, $category_id, $ids );
		}
		if ( isset( $action ) && $action == 'delete' && isset( $category_id ) && $category_id != null ) {
			foreach ( $ids as $id ) {
				$categories->delete( $id );
			}
			if ( count( $ids ) == 1 ) {
				$message = __( '1 asset category permanently deleted.', 'solidres' );
				SR_Helper::error_message( $message );
			} else {
				$message = __( count( $ids ) . ' asset categories permanently deleted.', 'solidres' );
				SR_Helper::update_message( $message );
			}
		}
		if ( $string_search != '' ) {
			if ( stripos( $string_search, 'id:' ) === 0 ) {
				$query_default = $query_default . ' WHERE id = ' . (int) substr( $string_search, 3 );
				$results       = $wpdb->get_results( $query_default );
			} else {
				$query_default = $query_default . ' WHERE name LIKE "%%' . $string_search . '%%"';
				$results       = $wpdb->get_results( $query_default );
			}
		} else if ( is_null( $state ) ) {
			$results = $wpdb->get_results( $query_default . " WHERE state = 0 OR state = 1" );
		} else {
			$results = $wpdb->get_results( $wpdb->prepare( $query_default . " WHERE state = %d", $state ) );
		}
		$this->datatable = array();
		foreach ( $results as $result ) {
			$published          = SR_Helper::view_status( $result->state );
			$parent_name        = $wpdb->get_var( $wpdb->prepare( "SELECT name FROM {$wpdb->prefix}sr_categories WHERE id = %d", $result->parent_id ) );
			$this->datatable [] = array( 'id'        => $result->id,
			                             'name'      => $result->name,
			                             'slug'      => $result->slug,
			                             'state'     => $published,
			                             'parent_id' => $parent_name
			);
		}

		parent::__construct(
			array(
				'singular' => __( 'categogy' ),
				'plural'   => __( 'categogies' ),
				'ajax'     => false,
			)
		);
	}

	function no_items() {
		_e( 'No category found!', 'solidres' );
	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'id':
			case 'name':
			case 'slug':
			case 'state':
			case 'parent_id':
				return $item[ $column_name ];
			default:
				return print_r( $item, true );
		}
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'id'        => array( 'id', false ),
			'name'      => array( 'name', false ),
			'slug'      => array( 'slug', false ),
			'state'     => array( 'state', false ),
			'parent_id' => array( 'parent_id', false ),
		);

		return $sortable_columns;
	}

	function get_columns() {
		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'id'        => __( 'ID', 'solidres' ),
			'name'      => __( 'Name', 'solidres' ),
			'slug'      => __( 'Slug', 'solidres' ),
			'state'     => __( 'State', 'solidres' ),
			'parent_id' => __( 'Parent ID', 'solidres' ),
		);

		return $columns;
	}
}

function sr_categories() {
	global $status, $string_search, $ListTableData;
	$helper        = new SR_Helper();
	$ListTableData = new SR_Categories_Table_Data();
	$ListTableData->prepare_items();
	$action = isset( $_GET['action'] ) ? $_GET['action'] : null;
	$helper->listview( 'sr_categories', $action, $string_search, $status, $ListTableData );
}