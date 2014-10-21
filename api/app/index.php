<?php



			/**************************************************************************************************
			|
			| http://wekonnect.com
			| chris@wekonnect.com
			|
			|**************************************************************************************************
			|
			| By using this software you agree that you have read and acknowledged our End-User License
			| Agreement available at http://wekonnect.com/ and to be bound by it.
			|
			| Copyright (c) 2013 wekonnect.com All rights reserved.
			|**************************************************************************************************/

			// error_reporting( E_ALL );
			// ini_set( 'display_errors', '1' );
	
		
			
			

			// load config
			require 'config.php';

            // load the library
            require 'myRestServer.php';

			// run REST server
			new myRestServer();
			
?>
