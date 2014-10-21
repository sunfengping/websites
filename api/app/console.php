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


			include 'config.php';

			// check for enabled console
			if(!ENABLE_CONSOLE)
				die();

			// try to detect proxied webserver like nginx
			if (function_exists('apache_get_modules')){
				$modules = apache_get_modules();
				$isProxied = in_array('mod_rpaf-2', $modules) ? true : false;
			}

			if(!$isProxied){
				$isAJAX =  ( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ? true : false;
				if(!$isAJAX)
					die();
			}


			$request = isset( $_GET['type'] ) && in_array( strtolower( trim( $_GET['type'] ) ), array(
			     'timestamp',
                 'query',
			     'controllers',
			     'flush' ) ) ? strtolower( trim( $_GET['type'] ) ) : 'query';

			$usedatabase = ( in_array($request, array('query','flush')) ) ? true : false;
			$tableExists = false;

			if ( $usedatabase ) {
				try {

			    	$db = new PDO( 'sqlite:console.sqlite', null, null, array( PDO::ATTR_PERSISTENT => true ) );
			    	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$tableExists = ( gettype( $db->exec( "SELECT count(*) FROM data" ) ) == "integer" ) ? true : false;


				} catch (Exception $e) {
					$response = $e->getMessage();
				}
			}

			$response = false;
			switch ( $request ) {
			     case 'timestamp':
			     		$response = time();
			          break;
			     case 'flush':
			          if ( $tableExists ) {
			               $db->exec( "DROP TABLE data" );
			               $db = null;
			               $response = true;
			          }
			          break;

			     case 'controllers':
			          $response = array();
			          foreach ( glob( 'Controllers/*.php', GLOB_BRACE ) as $controller ) {
                           $controller = pathinfo( $controller );
                           if(preg_match('/^[a-zA-Z0-9-_]+\.php$/', $controller['basename'])) {
                                $response[] = $controller['filename'];
                           }
			          }
			          $response = !empty( $response ) ? $response : false;
			          break;

			     case 'query':
			     default:
			          if ( $tableExists ) {
			               $controller = isset( $_GET['controller'] ) ? $_GET['controller'] : false;
			               $timestamp = isset( $_GET['timestamp'] ) ? $_GET['timestamp'] : false;

			               $limit = isset( $_GET['limit'] ) ? $_GET['limit'] : 20;

			               $filter = isset( $_GET['filter'])  && in_array($_GET['filter'], array('0','1'))? $_GET['filter'] : false;

			               $from = isset( $_GET['from'] ) ? $_GET['from'] : false;
			               $to = isset( $_GET['to'] ) ? $_GET['to'] : 0;

			               $add = ' ';
			               $controller_add = '';
			               if ( $controller ) {
			                    $add .= ' WHERE controller="' . $controller . '" ';
			                    $controller_add = $add;			               }

			               if ( $timestamp ) {
			                    $add .= $add != ' ' ? 'AND ' : ' WHERE ';
			                    $add .= 'time > "' . $timestamp . '" ';
			               }

			               if ( $from ) {
			                    $add .= $add != ' ' ? 'AND ' : ' WHERE ';
			                    $add .= 'ID > "' . $from . '" ';
			               }

			               if ( $to ) {
			                    $add .= $add != ' ' ? 'AND ' : ' WHERE ';
			                    $add .= 'ID < "' . $to . '" ';
			               }

			               if ( $filter!==false ) {
			                    $add .= $add != ' ' ? 'AND ' : ' WHERE ';
			                    $add .= 'error ="' . ($filter=='1' ? '1' : '' ) .'" ';
			               }

			               $query = 'SELECT * FROM data' . $add . 'ORDER BY ID DESC LIMIT 0,' . $limit;

			               $response = $db->query( $query )->fetchAll( PDO::FETCH_ASSOC );
			               $db = null;
			          }
			          $response = !empty( $response ) ? $response : false;


			}

			header( "Expires: Tue, 03 Jul 2001 06:00:00 GMT" );
			header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
			header( "Cache-Control: no-store, no-cache, must-revalidate" );
			header( "Cache-Control: post-check=0, pre-check=0", false );
			header( "Pragma: no-cache" );
			header( 'Access-Control-Allow-Origin: ' . CONSOLE_ALLOW_AJAX_DOMAIN );
			header( 'Content-type: application/json' );
			echo json_encode( array( 'response' => $response) );