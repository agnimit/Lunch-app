<?php

    class DB {

	public $USER = 'root';
	public $PASSWORD = 'black_betty';
	public $HOST = '127.0.0.1';
	public $DATABASE = 'lunch';
	public $CONNECTION = null;

	static private $instance = null;

	static function getInstance(){
	    if(self::$instance == null){
		self::$instance = new DB();
	    }
	    return self::$instance;
	}
	
	public function __construct(){
	    self::connect();
	    mysql_select_db($this->DATABASE, $this->CONNECTION);
	}

	private function connect(){
	   if($this->CONNECTION == null)
		$this->CONNECTION = mysql_connect($this->HOST, $this->USER, $this->PASSWORD, $this->DATABASE);
	   return $this->CONNECTION;
	}
	
	public function Read($Table, $Id = false, $Params = null){
	    $query = 'SELECT ';
	    $query_condition =  '';
	    if(!empty($Params) && !is_null($Params)){
			if(is_array(@$Params['Selectors'])){
		    	foreach($Params['Selectors'] as $selector){
					$query = $query . $selector. ', ';
		    	}
			    $query = substr($query, 0, -2);	
			} else {
		    	$query = $query . ' * ';
			}
			if(is_array(@$Params['Conditions'])){
		    	$query_condition = 'WHERE ';
			    foreach($Params['Conditions'] as $condition_name => $condition){
						$query_condition = $query_condition . $condition_name . ' = "' . $condition . '" AND '; 
		    	}
		    	$query_condition = substr($query_condition, 0, -5);
			}
	    } else {
			$query = $query . " * ";
		}
		
	    $query = $query . ' FROM ' . $Table . ' ';
	    if(!empty($Id) && empty($query_condition)){
			$query = $query . 'WHERE id = ' . $Id;
	    	
		} else if(empty($Id) && !empty($query_condition)){
			$query = $query . $query_condition;
	    } else {
			return false;
	    }
		error_log("This is the query - " . $query);
	    $result = mysql_query($query);
		$rows = array();
		while($r = mysql_fetch_assoc($result)){
			$rows[] = $r; 
		}	
	    return $rows;
	}

	public function Write($Table, $Params = array() ){
		if(count($Params) == 0)
			return false;
		$query = 'INSERT INTO '. $Table . ' '; 
		$col = array();
		$val = array();
		foreach($Params as $field => $value) {
			$col[] = $field;
			$val[] = $value;
		}
		$cols = '(';
		$vals = '(';
		for($i = 0; $i < count($Params); $i++) {
			$cols =  $cols .  $col[$i] . ', ';
			$vals =  $vals . '"' . $val[$i] . '", ';
		}
		$cols = substr($cols,0,-2) . ')';
		$vals = substr($vals,0,-2) . ')';
		$query = $query . $cols . ' VALUES ' . $vals;
		$result = mysql_query($query);
		
		error_log("This is the query - " . $query);
		return $result;	
	}

	public function Edit($Table, $Params = array() ) {
		if(count($Params) != 0 && is_array($Params['Selectors']) && is_array($Params['Conditions']))
			return false;
		$query = 'UPDATE ' . $Table . ' SET '; 
		foreach($Params['Selectors'] as $selector_name => $selector){
			$query = $query . $selector_name . ' = "' . $selector . '", ';
		}
		$query = substr($query, 0, -2);		
		$query = $query . ' WHERE ';
		foreach($Params['Conditions'] as $condition_name => $condition) {
			$query = $query . $condition_name  . ' = "' . $condition . '" AND ';
		}
		$query = substr($query, 0, -5);
		error_log("This is the query - " . $query);
		$result = mysql_query($query);
		return $result;
	}

	public function Delete($Table, $Params = array() ){
		if(count($Params) == 0) {
			return false;
		}
		$query = "DELETE FROM " . $Table . " WHERE ";
		foreach($Params as $fields => $values) {
			$query .= $fields . ' = "' . $values . '" AND ';
		}
		$query = substr($query, 0, -5);
		error_log("This is the query- " . $query);
		$result = mysql_query($query);
		return $result;
    }
	}

?>
