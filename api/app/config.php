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
			
			include("class/class.mysql.php");
			include("class/class.ArrayToXML.php");	
			include("class/class.rss.php");				
			$myauth = new mysql();	
			$mysql = new mysql(); 
			
	
			define('LIMIT_RESLTS', 15);
			define('ADMINIMAGEURL', "http://admin.wekonnect.com");
			define('IMAGEURL', "https://wekonnect.com/app/");
			define('URL', "https://wekonnect.com/app/".basename($_SERVER['PHP_SELF'], ".php").".php");


			// default time zone
			date_default_timezone_set('Australia/Melbourne');

			// enable console
			define('ENABLE_CONSOLE', false);

			// allowed domain for console
			define('CONSOLE_ALLOW_AJAX_DOMAIN', '*');
			
			
			header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept,api-key");
			?>
