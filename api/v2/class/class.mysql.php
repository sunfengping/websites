<?php
/***************************************************************************
 *                          class.mysql.php
 *                            -------------------
 *   begin                : Saturday,10/07/03
 *   copyright            : (C) 2003  Peak Software
 *   email                : chris@peaksoftware.com.au

 ***************************************************************************/
// include("../admin/config.php");
 class mysql {

	   var $debug ='';
		var $host = '192.168.1.199';
		var $database ='wkadmin';
		var $user = 'root';
		var $password = 'root';

	


	/* private: connection parameters */
	var $conn;
	var $rstemp;
	var $record;	
	
	/**
	 * mysql::mysql()
	 * Constructor this class - define public connection parameters and
	 * call the connect method
	 * 
	 * @param $host
	 * @param $user
	 * @param $password
	 * @param $database
	 */
	 
	 	function mysql () {
	/* public: connection parameters */
		$this->connect();
	}
	
	
	function set_mysql ($host,$user,$password,$database,$debug=0) {

		$this->debug = $debug;
		if ($this->debug) echo "\n\nDebug On <br>\n";		
		
		$this->host = $host;
    	$this->user = $user;
	    $this->password = $password;
	    $this->database = $database;
		
	
		/**
         * open connection
         */
		$this->connect();
	}
	    
	
        /**
         * mysql::connect()
         * Open connection with the server
         * 
         * @return id da conexao
         */
         function connect () { 
		 
		    
             /**
              * Open connection
              */
             if ($this->debug) echo "Connecting  to $this->host <br>\n";
             $this->conn = @mysql_pconnect($this->host,$this->user,$this->password)
                			or die("Connection to $this->host failed <br>\n");
         
            /**
             * Select to database
             */	
             if ($this->debug) echo "Selecting to $this->database <br>\n";
             @mysql_select_db($this->database,$this->conn)
                			or die("Error:" . mysql_errno() . " : " . mysql_error() . "<br>\n");	
            	
             return $this->conn;				
        }
	
	/**
	 * mysql::query()
	 * Execute SQL
	 * 
	 * @param $sql
	 * @return 
	 */
	function query($sql) {
		
		if ($this->debug) echo "Run SQL:  $sql <br>\n\n";
		$this->rstemp = @mysql_query($sql,$this->conn) 
		            or die("Error:" . mysql_errno() . " : " . mysql_error() . "<br>\n"); 
	
					
					
										

		return $this->rstemp;
	
	}
	

	
		/**
	 * mysql::query()
	 * Execute SQL
	 * 
	 * @param $sql
	 * @return 
	 */
		function single_query($sql,$field=0) {		
  
		if ($this->debug) echo "Run SQL:  $sql <br>\n\n";
		$this->rstemp = @mysql_query($sql,$this->conn) 
		            or die("Error:" . mysql_errno() . " : " . mysql_error() . "<br>\n");	
					
		$this->movenext();								
		return $this->getfield($field);			
	 
	}
	
		function static_query($sql) {		
  
		if ($this->debug) echo "Run SQL:  $sql <br>\n\n";
		$this->rstemp = @mysql_query($sql,$this->conn) 
		            or die("Error:" . mysql_errno() . " : " . mysql_error() . "<br>\n");	
					
		$this->movenext();								

	 
	}
	

	
	/**
	 * mysql::num_rows()
	 * return number of records in current select
	 * 
	 * @param $rstemp 
	 * @return 
	 */
	function num_rows() {
	
		$num = @mysql_num_rows($this->rstemp);
		if ($this->debug) echo "$num records returneds <br>\n\n";	
		
		return $num;		
	}
	
	
  	/**
  	 * mysql::movenext()
	 * fetch next record in result
  	 * 
  	 * @return 
  	 */
  	function movenext(){
		
		if ($this->debug) echo "Fetching next record  ... ";
	    $this->record = @mysql_fetch_array($this->rstemp);
	    $status = is_array($this->record);
		
		if ($this->debug && $status) echo "OK <br>\n\n";
		elseif ($this->debug) echo "EOF <br>\n\n";
		
	    return($status);
	}
  
  
	/**
	 * mysql::getfield()
	 * get field value from the current record
	 * 
	 * @param $field
	 * @return 
	 */	
	function getfield($field){

		return($this->record[$field]);
	}
	
	/**
	 * mysql::getALL()
	 * get all field value from the current record
	 *
	 * @param $field
	 * @return
	 */
	function getALL(){
		$result=$this->rstemp;
		$rows = array();
		while($row=mysql_fetch_assoc($result)){
			 $rows[]=$row;
			}
			return $rows;

		
	}	

	/**
	 * mysql::movefirst()
	 * move to the first record
	 * 
	 * 
	 * @return true
	 */
	function movefirst() {
		if(mysql_data_seek($this->rstemp,0)) {
			return true;
		} else {
			return false;
		}
	}
	
	function get_site_debug() {
			return $this->debug;
	}	
	
	function get_site_host() {
			return $this->host;
	}	
	
	function get_site_database() {
			return $this->database;
	}	
	
	function get_site_user() {
			return $this->user;
	}	
	
	function get_site_password() {
			return $this->password;
	}
	
		 
	function lastinsert() {
		return mysql_insert_id();
	}

		
  	
}

?>
