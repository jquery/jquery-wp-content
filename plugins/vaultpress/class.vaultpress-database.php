<?php

class VaultPress_Database {

	var $table = null;
	var $pks = null;

	function VaultPress_Database() {
		$this->__construct();
	}

	function __construct() {
	}

	function attach( $table ) {
		$this->table=$table;
	}

	function get_tables( $filter=null ) {
		global $wpdb;
		$rval = $wpdb->get_col( 'SHOW TABLES' );
		if ( $filter )
			$rval = preg_grep( $filter, $rval );
		return $rval;
	}

	function show_create() {
		global $wpdb;
		if ( !$this->table )
			return false;
		$table = $wpdb->escape( $this->table );
		$results = $wpdb->get_row( "SHOW CREATE TABLE `$table`" );
		$want = 'Create Table';
		if ( $results )
			$results = $results->$want;
		return $results;
	}

	function explain() {
		global $wpdb;
		if ( !$this->table )
			return false;
		$table = $wpdb->escape( $this->table );
		return $wpdb->get_results( "EXPLAIN `$table`" );
	}

	function diff( $signatures ) {
		global $wpdb;
		if ( !is_array( $signatures ) || !count( $signatures ) )
			return false;
		if ( !$this->table )
			 return false;
		$table = $wpdb->escape( $this->table );
		$diff = array();
		foreach ( $signatures as $where => $signature ) {
			$pksig = md5( $where );
			unset( $wpdb->queries );
			$row = $wpdb->get_row( "SELECT * FROM `$table` WHERE $where" );
			if ( !$row ) {
				$diff[$pksig] = array ( 'change' => 'deleted', 'where' => $where );
				continue;
			}
			$row = serialize( $row );
			$hash = md5( $row );
			if ( $hash != $signature )
				$diff[$pksig] = array( 'change' => 'modified', 'where' => $where, 'signature' => $hash, 'row' => $row );
		}
		return $diff;
	}

	function count( $columns ) {
		global $wpdb;
		if ( !is_array( $columns ) || !count( $columns ) )
			return false;
		if ( !$this->table )
			 return false;
		$table = $wpdb->escape( $this->table );
		$column = $wpdb->escape( array_shift( $columns ) );
		return $wpdb->get_var( "SELECT COUNT( $column ) FROM `$table`" );
	}

	function wpdb( $query, $function='get_results' ) {
		global $wpdb;

		if ( !is_callable( array( $wpdb, $function ) ) )
			return false;

		$res = $wpdb->$function( $query );
		if ( !$res )
			return $res;
		switch ( $function ) {
			case 'get_results':
				foreach ( $res as $idx => $row ) {
					if ( isset( $row->option_name ) && $row->option_name == 'cron' )
						$res[$idx]->option_value = serialize( array() );
				}
				break;
			case 'get_row':
				if ( isset( $res->option_name ) && $res->option_name == 'cron' )
					$res->option_value = serialize( array() );
				break;
		}
		return $res;
	}

	function get_cols( $columns, $limit=false, $offset=false, $where=false ) {
		global $wpdb;
		if ( !is_array( $columns ) || !count( $columns ) )
			return false;
		if ( !$this->table )
			return false;
		$table = $wpdb->escape( $this->table );
		$limitsql = '';
		$offsetsql = '';
		$wheresql = '';
		if ( $limit )
			$limitsql = ' LIMIT ' . intval( $limit );
		if ( $offset )
			$offsetsql = ' OFFSET ' . intval( $offset );
		if ( $where )
			$wheresql = ' WHERE ' . base64_decode($where);
		$rval = array();
		foreach ( $wpdb->get_results( "SELECT * FROM `$this->table` $wheresql $limitsql $offsetsql" ) as $row ) {
			// We don't need to actually record a real cron option value, just an empty array
			if ( isset( $row->option_name ) && $row->option_name == 'cron' )
				$row->option_value = serialize( array() );
			$keys = array();
			$vals = array();
			foreach ( get_object_vars( $row ) as $i => $v ) {
				$keys[] = sprintf( "`%s`", $wpdb->escape( $i ) );
				$vals[] = sprintf( "'%s'", $wpdb->escape( $v ) );
				if ( !in_array( $i, $columns ) )
					unset( $row->$i );
			}
			$row->hash = md5( sprintf( "(%s) VALUES(%s)", implode( ',',$keys ), implode( ',',$vals ) ) );
			$rval[]=$row;
		}
		return $rval;
	}
}