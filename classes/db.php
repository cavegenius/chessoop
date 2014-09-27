<?php
 
/**
 * PHP Class to abstract a database layer
 * 
 * Written by Chris Klosowski of chriskdesigns.com
 * 
 *
 */

Class DB_Class {
	var $last_error; 	// Holds last error returned
	var $last_query; 	// Holds last query executed
	
	var $host;			// MySQL host
	var $userName;		// MySQL user
	var $pw;			// MySQL password
	var $db;			// MySQL database

	var $auto_slashes;	// the calss will add/strip slashes when it can

	function __construct() {
		$this->host = 'localhost';
		$this->userName = 'root';
		$this->pw = 'D20z6a1!';
		$this->db = 'stock_wp';
		$this->auto_slashes = TRUE;
	}
	
	/**
	 * Connection class returns true or false
	 * 
	 * @param string $persistant
	 * 
	 * @return boolean
	 */
	public function connect($persistant=TRUE) {
		if ($persistant) {
			$this->db_link = mysql_pconnect($this->host, $this->userName, $this->pw);
		} else {
			$this->db_link = mysql_connect($this->host, $this->userName, $this->pw);
		}

		if (!$this->db_link) {
			$this->last_error = mysql_error();
			return FALSE;
		}
		return ($this->select_db($this->db)) ? TRUE : FALSE;
	}

	/**
	 * Returns last Error from MySQL
	 */
	public function get_last_error() {
		return $this->last_error;
	}

	
	/**
	 * Do a generic SELECT query from the database
	 * 
	 * @param string $query
	 * 
	 * @return resource
	 */
	public function query($query) {
	  $query = mysql_real_escape_string($query);
	  
	  if (!($results = mysql_query($query))) {
		$this->last_error = mysql_error();
	    return FALSE;
	  }
		
	  return $results;
	}

	/**
	 * Select a single column from a single table
	 * 
	 * @param string $col - The Column to return
	 * @param string $table - The Table to query
	 * @param array $case - Array of Casses to meet [column] => value
	 * @param string $format - array or object
	 * 
	 * @return mixed - Associative array or object of the query results
	 */
	public function get_col($col, $table, $case='', $format='array') {
  	  $query = 'SELECT ' . $col . ' FROM '. $table . ' WHERE 1=1';
  	  if (is_array($case)) {
  	    $query .= $this->_parse_cases($case);
  	  }
  
  	  if (!$results = $this->query($query)) {
  	    return FALSE;
  	  }
  	
  	  return $this->_parse_results($results, $format);
	}
	
	/**
	 * Select specific columns from a single table
	 * @param array $cols
	 * @param string $table
	 * @param array $case
	 * @param string $format
	 * 
	 * @return array
	 */
	public function get_cols($cols, $table, $case='', $format='array') {
	  $columns = '';
		
	  foreach ($cols as $col) {
	    $columns .= $col . ', ';
	  }
		
	  $columns = substr($columns, 0, (strlen($columns)-2));
	  $query = 'SELECT ' . $columns . ' FROM ' . $table . ' WHERE 1=1';
		
	  if (is_array($case)) {
	    $query .= $this->_parse_cases($case);
	  }

	  if (!$results = $this->query($query)) {
	    return FALSE;
	  }
		
	  return $this->_parse_results($results, $format);
	}
	
	/**
	 * Select the database to use
	 *
	 * @param string $db
	 *
	 * @return boolean
	 */
	private function select_db($db='') {
	  if (!mysql_select_db($this->db)) {
		$this->last_error = mysql_error();
		return FALSE;
	  }
	  
	  return TRUE;
	}
	
	/**
	 * Parse the case array from a method
	 * 
	 * @param array $cases
	 * 
	 * @return string
	 */
	private function _parse_cases($cases) {
	  $return = '';
	  foreach ($cases as $key=>$case) {
	    $return .= ' AND ' . $key . ' = ' . $case;
	  }
	  
	  return $return;
	}
	
	/**
	 * Parse the results from the query method
	 * 
	 * @param resource $results
	 * @param string $format
	 * 
	 * @return mixed
	 */
	private function _parse_results($results, $format) {
      while ($row = mysql_fetch_assoc($results)) {
  		foreach ($row as $key=>$item) {
  		  $row_array[$key] = $item;
  		}
  		
  		if ($format == 'object') {
  		  $return[] = (object)$row_array;
  		} else {
  		  $return[] = $row_array;
  		}
  	  }
  	  
  	  return $return;
	}
}
