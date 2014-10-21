<?
/***************************************************************************
 *                               class_database.php
 *                      --------------------------------------
 *   Begin                 :  Tuesday, April 13, 2004
 *   Copyright             :  (C) 2004 MCWeb
 *   Email                 :  marc@mcweb.com.au
 *   Description           :  Class for easily using a msql database
 *   Version               :  1.00
 *
 ***************************************************************************/ 
class database {
    /************************************************
    VARIABLES                            
    ************************************************/
	/*
    *  Public connection parameters
    *  @var string
    */
	var $host = 'localhost';
	var $database ='gold';
	var $user = 'gold';
	var $password = 'kbMGqt9bz3';
	/*
    *  Private connection parameters
    */
	var $conn;
	var $result;
	var $record;
	
	/*
	* General Variables
	*/
	var $affected = '';
	
	/************************************************
    METHODS                           
	************************************************/
	/*
	* Constructor of class
	*/
	function database() {
		if(!empty($this->host)&&!empty($this->user)&&!empty($this->password)&&!empty($this->database)) {
			$this->connect();
		}
	}
		
	function configMYSQL ($host,$user,$password,$database) {	
		$this->host 	= $host;
		$this->user	 	= $user;
		$this->password = $password;
		$this->database = $database;

		$this->connect();
	}

    function connect($persistant=true) { 
		if($persistant=true) {
        	$this->conn = @mysql_pconnect($this->host,$this->user,$this->password)
        		or die("Connection $server failed <br/>\n");
		} else {
			$this->conn = @mysql_connect($this->host,$this->user,$this->password)
        		or die("Connection $server failed <br/>\n");
		}
        @mysql_select_db($this->database,$this->conn)
                			or die("Error:" . mysql_errno() . " : " . mysql_error() . "<br>\n");	
        return $this->conn;				
    }

	function query($sql) {
		$sql = trim($sql);
		$this->result = @mysql_query($sql,$this->conn) 
		            or die("Error:" . mysql_errno() . " : " . mysql_error() . "<br>\n"); 	
		echo $this->affected = @mysql_affected_rows($this->result);
		$this->record = @mysql_fetch_array($this->result);
		@mysql_data_seek($this->result,0);
		return $this->result;
	}

	function numRows() {
		if($this->result) {
			return @mysql_num_rows($this->result);
		}
		return FALSE;
	}
	
	function affectedRows(){
		if($this->result) {
			return $this->affected;

		}
		return FALSE;
	}

	function moveFirst(){
		if($this->result) {
			@mysql_data_seek($this->result,0);
			return TRUE;
		}
		return FALSE;
	}	

  	function moveNext(){
		if($this->result) {
			return $this->record = @mysql_fetch_array($this->result);
		}
		return FALSE;
	}

	function getField($field){
		if($this->record) {
			return $this->record[$field];
		}
		return FALSE;
	}

	function fetchArray() {
		
		if($this->result) {
			$values = array();
			$this->moveFirst();
			while($row = @mysql_fetch_array($this->result, MYSQL_ASSOC)) {
				array_push($values,$row);
			}
			return $values;
		}
		return FALSE;
	}
	
	function getInsertID() {
		return mysql_insert_id();
	}
}

?>
