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

			//error_reporting( E_ALL );
			//ini_set( 'display_errors', '1' );


			class myRestServer
			{

			     // myRestServer version
			     const VERSION = '1.1';

			     // switch production / debug
			     const PRODUCTION = true;

			     // enable production mode on query string
			     const OVERWRITE_PRODUCTION = true;

			     // simple string protection to disallow users run debug mode. set false to disable.
			     const QUERY_HASH_VAR = true;

			     // hash GET variable to allow change production / debug mode on query string
			     const QUERY_HASH_VAL = '308760';

			     // default response type if not specified
			     const DEFAULT_RESPONSE_FORMAT = 'json';

			     // default response code if not set in controller
			     const DEFAULT_RESPONSE_CODE = 200;

			     // controllers directory
			     const CONTROLLERS_DIR = 'Controllers';

			     // try to redirect to a clean url when is malformed
			     const ENABLE_REDIR = true;

			     // enable or disable auth
			     const ENABLE_AUTH = true;

			     // show response errors in prodution mode
			     const SHOW_RESPONSE_ERRORS = true;

			     // use template for display errors
			     const USE_TEMPLATE_PAGE = false;

			     // default allowed domain for AJAX calls
			     const DEFAULT_CROSSDOMAIN = '*';

			     // allow ajax calls
			     const ALLOW_AJAX_CALLS = false;

			     // save error logs
			     const ENABLE_ERROR_LOGS = false;

			     // use detailed error logs
			     const ENABLE_DETAILED_ERROR_LOGS = false;

			     // save access logs
			     const ENABLE_ACCESS_LOGS = false;

			     // use detailed access logs
			     const ENABLE_DETAILED_ACCESS_LOGS = false;

			     // logs default directory
			     const DEFAULT_LOGS_DIR = 'logs';

			     // handle logs in separated files
			     const ENABLE_TRIM_LOGS = true;

			     // max filze size of log in bytes [default 10240000 / 10 mb]
			     const DEFAULT_MAX_LOG_SIZE = 10240000;

			     // number of logs to rotate
			     const DEFAULT_MAX_LOGS = 20;

			     // enable cache
			     const ENABLE_CACHE = false;

			     // default cache ttl in seconds
			     const DEFAULT_CACHE_TTL = 300;

			     // cache default directory
			     const DEFAULT_CACHE_DIR = 'cache';

			     // maintence mode delete old cache files and log files
			     const ENABLE_MAINTENCE = true;

			     // seconds to run maintence: flush cache and log files [ default 7 days 604800 ]
			     const DEFAULT_MAINTENCE_TTL = 604800;

			     // seconds to consider old a cache file [ default one 12 days: 1036800 ]
			     const DEFAULT_OLD_CACHE_TTL = 1036800;

			     // seconds to consider old a log file [ default one 12 days: 1036800 ]
			     const DEFAULT_OLD_LOGS_TTL = 1036800;

			     // default http scheme when REST server is run over reverse proxy like nginx
			     const DEFAULT_HTTP_SCHEME = 'http';

                 // enable external debug console. use just when debug
			     const ENABLE_CONSOLE = ENABLE_CONSOLE; // set from config to match remote console, you can set true or false here.


			     // variables
			     private $mode;
			     private $server;
			     private $cache;
			     private $request;
			     private $response;
			     private $error = false;
			     private $renderTime;


			     // allowed response types via query string or filetype
			     private static $availableResponseTypes = array( 'json', 'xml' );

			     // available response types and headers
			     private static $contentTypes = array(
			          'json' => 'application/json',
			          'xml' => 'application/xml',
			          'txt' => 'text/plain',
			          'php' => 'text/plain' );

			     // allowed methods
			     private static $methods = array(
			          'get',
			          'post',
			          'put',
			          'delete' );

			     // response codes
			     private static $codes = array(
			          100 => 'Continue',
			          101 => 'Switching Protocols',
			          200 => 'OK',
			          201 => 'Created',
			          202 => 'Accepted',
			          203 => 'Non-Authoritative Information',
			          204 => 'No Content',
			          205 => 'Reset Content',
			          206 => 'Partial Content',
			          300 => 'Multiple Choices',
			          301 => 'Moved Permanently',
			          302 => 'Found',
			          303 => 'See Other',
			          304 => 'Not Modified',
			          305 => 'Use Proxy',
			          306 => '(Unused)',
			          307 => 'Temporary Redirect',
			          400 => 'Bad Request',
			          401 => 'Unauthorized',
			          402 => 'Payment Required',
			          403 => 'Forbidden',
			          404 => 'Not Found',
			          405 => 'Method Not Allowed',
			          406 => 'Not Acceptable',
			          407 => 'Proxy Authentication Required',
			          408 => 'Request Timeout',
			          409 => 'Conflict',
			          410 => 'Gone',
			          411 => 'Length Required',
			          412 => 'Precondition Failed',
			          413 => 'Request Entity Too Large',
			          414 => 'Request-URI Too Long',
			          415 => 'Unsupported Media Type',
			          416 => 'Requested Range Not Satisfiable',
			          417 => 'Expectation Failed',
			          500 => 'Internal Server Error',
			          501 => 'Not Implemented',
			          502 => 'Bad Gateway',
			          503 => 'Service Unavailable',
			          504 => 'Gateway Timeout',
			          505 => 'HTTP Version Not Supported' );

			     // auth default values
			     private static $defaultCredentials = array(
			          'username' => 'admin', // default username
			          'password' => '', // default password
			          'realm' => 'MyRestServer', // default realm
			          'digest' => true, // use digest of basic auth
			          'methods' => array( // default methods to protect
			                    'get' ) );


			     // run
			     public function __construct()
			     {
			          ob_start();
			          $renderTime = microtime( true );
			          $this->setProductionMode( self::PRODUCTION );
			          $this->getServerBaseUrl();
			          $this->getServerBasePath();
			          $this->getControllersPath();
			          $this->getLogsPath();
			          $this->getCachePath();
			          $this->processRequest();
			          $this->processResponse();
			          $this->render();
			          $this->maintence();
			          $this->renderTime = number_format( microtime( true ) - $renderTime, 4, '.', ' ' );
			          $this->debug();
			          ob_end_flush();
			     }


			     // get working mode
			     private function setProductionMode( $mode )
			     {
			          return $this->mode = $mode ? 'production' : 'debug';
			     }


			     // get server base url
			     private function getServerBaseUrl()
			     {
			          $baseUrl = isset( $_SERVER['REQUEST_SCHEME'] ) ? $_SERVER['REQUEST_SCHEME'] : self::DEFAULT_HTTP_SCHEME;
			          $baseUrl .= '://' . $_SERVER['SERVER_NAME'];
			          $baseUrl .= ( $_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443 ) ? '' : ':' . $_SERVER['SERVER_PORT'];
			          $baseUrl .= str_replace( 'index.php', '', $_SERVER['PHP_SELF'] );
			          $this->server['base_url'] = $baseUrl;
			     }


			     // get server path
			     private function getServerBasePath()
			     {
			          $this->server['root'] = realpath( dirname( __class__ ) ) . DIRECTORY_SEPARATOR;
			     }


			     // get controllers path
			     private function getControllersPath()
			     {
			          $this->prepareDir( self::CONTROLLERS_DIR, 0777 );
			          $this->server['controllers'] = realpath( self::CONTROLLERS_DIR ) . DIRECTORY_SEPARATOR;
			     }

			     // get logs path
			     private function getLogsPath()
			     {
			          $this->prepareDir( self::DEFAULT_LOGS_DIR, 0777 );
			          $this->server['logs'] = realpath( self::DEFAULT_LOGS_DIR ) . DIRECTORY_SEPARATOR;
			     }

			     // get cache path
			     private function getCachePath()
			     {
			          $this->prepareDir( self::DEFAULT_CACHE_DIR, 0755 );
			          $this->server['cache'] = realpath( self::DEFAULT_CACHE_DIR ) . DIRECTORY_SEPARATOR;
			     }


			     // process request
			     private function processRequest()
			     {
			          $this->getMethod();
			          $this->getUrlData();
			          $this->getFormat();
			          $this->getResource();
			          $this->getController();
			          $this->getHeaders();
			          $this->getParams();
			          $this->getExtraParams();
			          $this->getContentType();
			     }


			     // get method
			     private function getMethod()
			     {
			          $this->request['method'] = in_array( strtolower( $_SERVER['REQUEST_METHOD'] ), self::$methods ) ? strtolower( $_SERVER['REQUEST_METHOD'] ) :
			               'get';
			     }


			     // get current URI
			     private function getUrlData()
			     {
			          $this->request['resource']['raw'] = $this->getCurrentUrl();
			          $this->request['resource']['clean'] = $this->cleanUrl( $this->request['resource']['raw'] );
			     }


			     // get format type
			     private function getFormat()
			     {
			          $data = parse_url( $this->request['resource']['clean'] );
			          preg_match( '/\b.(' . implode( '|', array_keys( self::$contentTypes ) ) . ')\b/i', $data['path'], $type );
			          $type = isset( $type[1] ) ? $type[1] : self::DEFAULT_RESPONSE_FORMAT;
			          $type = isset( $_GET['format'] ) && in_array( $_GET['format'], array_values( array_keys( self::$contentTypes ) ) ) ? $_GET['format'] : $type;
			          $this->request['format'] = in_array( $type, self::$availableResponseTypes ) ? $type : self::DEFAULT_RESPONSE_FORMAT;
			     }


			     // get resource
			     private function getResource()
			     {
			          $request = parse_url( $this->request['resource']['clean'] );
			          $request = $request['path'];
			          $request = preg_split( '/[.]/', $request );
			          $this->request['resource']['request'] = $request[0];
			          $this->request['resource']['remote'] = $_SERVER['REMOTE_ADDR'];
			          $this->request['resource']['ajax'] = $this->isAJAX();
			     }


			     // get controller name
			     private function getController()
			     {
			          $data = preg_split( '/[.?&#]/', str_replace( $this->server['base_url'], '', $this->getCurrentUrl() ) );
                      $data = $data[0];
			          $data = urldecode( $data );
			          $data = preg_replace( "/[^\/_\-\p{L}\p{N}\\s]/", '', $data );
			          $data = preg_replace( '/\\s/', '-', $data );

			          // return if no controller defined
			          if ( empty( $data ) )
			               return $this->request['controller'] = null;

			          $data = explode( '/', $data, 2 );

			          // construct controller
			          $controller = 'Controller_' . preg_replace( '/[^\p{L}\p{N}]/u', '_', $data[0] );


			          // parse map
			          $map = '';
			          if ( !empty( $data[1] ) ) {
			               $map = preg_replace( '/[^\p{L}\p{N}]/u', '_', rtrim( $data[1], '/' ) );
			          }

			          $controller .= '__' . ( $map ? $map : 'index' );
			          $wildCard = 'Controller___' . preg_replace( '/[^\p{L}\p{N}]/u', '_', $data[0] ) . '___wildcard';

			          // contruct data
			          $controllerData = array();
			          $controllerData['base'] = $data[0];
			          $controllerData['file'] = $data[0] . '.php';
			          $controllerData['map'] = $map ? $map : null;
			          $controllerData['class'] = $controller;
			          $controllerData['wildcard'] = $wildCard;
			          $this->request['controller'] = $controllerData;
			     }


			     // get request headers
			     private function getHeaders()
			     {
			          if ( function_exists( 'apache_request_headers' ) )
			               return $this->request['headers'] = apache_request_headers();
			          $headers = array();
			          $keys = preg_grep( '{^HTTP_}i', array_keys( $_SERVER ) );
			          foreach ( $keys as $val ) {
			               $key = str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $val, 5 ) ) ) ) );
			               $headers[$key] = $_SERVER[$val];
			          }
			          $this->request['headers'] = $headers;
			     }


			     // get params
			     private function getParams()
			     {

			          switch ( $this->request['method'] ) {
			               case 'get':
			                    $params = isset( $_GET ) ? $_GET : null;
			                    break;
			               case 'post':
			                    $params = array_merge( ( isset( $_POST ) ? $_POST : null ), ( isset( $_GET ) ? $_GET : null ) );
			                    break;
			               case 'put':
			               case 'delete':
			                    $this->request['params']['put'] = fopen( "php://input", "r" );
			                    $params = array_merge( ( isset( $_POST ) ? $_POST : null ), ( isset( $_GET ) ? $_GET : null ) );
			                    break;
			          }
			          $this->request['params']['string'] = $params;
			     }

			     // get extra params from url
			     private function getExtraParams()
			     {
			          if ( empty( $this->request['controller']['map'] ) )
			               return $this->request['params']['map'] = array();

			          $data = str_replace( $this->server['base_url'] . $this->request['controller']['base'] . '/', '', $this->request['resource']['clean'] );
			          $data = preg_split( '/[.?&#]/', $data );
			          $data = preg_split( '/[\/]/', $data[0] );

			          for ( $i = 0; $i < count( $data ); $i += 2 )
			               $newArray[$data[$i]] = isset( $data[$i + 1] ) ? $data[$i + 1] : '';

			          $this->request['params']['map'] = $newArray;

			     }


			     // get content type of request
			     private function getContentType()
			     {
			          $this->request['content-type'] = self::$contentTypes[$this->request['format']];
			     }


			     //  process the response
			     public function processResponse()
			     {
			          try {

			               // no controller specified
			               if ( is_null( $this->request['controller'] ) ) {
			                    $this->error = 404;
			                    throw new Exception( 'No Controller was specified', 404 );
			               }

			               // get array of controllers
			               $controllersList = glob( $this->server['controllers'] . '*.php', GLOB_BRACE );
			               $controllers = array();
			               foreach ( $controllersList as $controller ) {
			                    $controllers[] = basename( $controller );
			               }


			               // check and load controller file
			               if ( in_array( $this->request['controller']['file'], $controllers, true ) ) {
			                    @include_once $this->server['controllers'] . $this->request['controller']['file'];
			               } else {
			                    $this->error = 404;
			                    throw new Exception( 'Cannot load the specified controller', 404 );
			               }

			               // no method to load
			               if ( is_null( $this->request['controller']['class'] ) ) {
			                    $this->error = 405;
			                    throw new Exception( 'Method not allowed', 405 );
			               }

			               // check for controller
			               try {
			                    // process class methods
			                    try {
			                         $controller = new ReflectionClass( $this->request['controller']['wildcard'] );
			                         $this->request['controller']['route'] = 'wildcard';
			                    }
			                    catch ( ReflectionException $re ) {
			                         $controller = new ReflectionClass( $this->request['controller']['class'] );
			                         $this->request['controller']['route'] = 'static';
			                    }
			               }
			               catch ( ReflectionException $re ) {
			                    $this->error = 404;
			                    throw new Exception( 'Controller does not exist', 404 );
			               }


			               // check if controller is instantiable
			               if ( !$controller->isInstantiable() ) {
			                    $this->error = 405;
			                    throw new Exception( 'The Class ' . $this->request['controller']['class'] . ' isnt Instantiable', 405 );
			               }


			               // check for method in controller
			               try {
			                    $method = $controller->getMethod( $this->request['method'] );
			               }
			               catch ( ReflectionException $re ) {
			                    $this->error = 405;
			                    throw new Exception( 'Unsupported HTTP method ' . $this->request['method'], 405 );
			               }


			               $isLogged = true;
			               // check if method isnt static or public
			               if ( !$method->isStatic() && $method->isPublic() ) {

			                    $useLogs = self::ENABLE_ACCESS_LOGS;
			                    $useDetailedLogs = self::ENABLE_DETAILED_ACCESS_LOGS;
			                    $enabledCache = $this->request['method'] == 'get' ? self::ENABLE_CACHE : false;

			                    // check for auth settings before run main controller
			                    try {
			                         $checkSettings = $controller->getMethod( 'settings' );

			                         if ( !$checkSettings->isStatic() && $checkSettings->isPublic() ) {

			                              // run controller instance
			                              $checkSettingsController = $controller->newInstance( null );

			                              // invoke method
			                              $checkSettings->invoke( $checkSettingsController );

			                              // load settings from controller
			                              $settings = $checkSettingsController->getSettingsData();
			                              $this->request['controller']['settings'] = $settings;

			                              // check production mode in settings controller
			                              if ( isset( $settings['production'] ) )
			                                   $this->setProductionMode( $settings['production'] );

			                              // check use of logs from controller settings
			                              if ( isset( $settings['logs']['enabled'] ) && is_bool( $settings['logs']['enabled'] ) )
			                                   $useLogs = $settings['logs']['enabled'];

			                              // check use of detailed logs from controller settings
			                              if ( isset( $settings['logs']['detailed'] ) && is_bool( $settings['logs']['detailed'] ) )
			                                   $useDetailedLogs = $settings['logs']['detailed'];

			                              // check auth data in settings controller
			                              if ( isset( $settings['auth']['enabled'] ) && $settings['auth']['enabled'] && self::ENABLE_AUTH )
			                                   $isLogged = $this->processAuth( $settings['auth'], $this->request['method'] );

			                              // enable or disable cache
			                              if ( isset( $settings['cache']['enabled'] ) && is_bool( $settings['cache']['enabled'] ) && $this->request['method'] == 'get' )
			                                   $enabledCache = $settings['cache']['enabled'];

			                              // flush all cache from current controller
			                              if ( isset( $settings['cache']['flush'] ) && $this->request['method'] == 'get' )
			                                   $this->flushCache( $settings['cache']['flush'] );

			                         }
			                    }
			                    catch ( ReflectionException $re ) {
			                    }

			                    // check logged user
			                    if ( $isLogged === false ) {
			                         $this->error = 401;
			                         throw new Exception( 'Unauthorized', 401 );

			                    } else {

			                         // get list of methods to load
			                         $methods = $controller->getMethods( ReflectionMethod::IS_FINAL );

			                         //check cache
			                         if ( $enabledCache ) {
			                              $this->cache['key'] = $this->makeUniqueCacheKey();
			                              $this->cache['ttl'] = isset( $settings['cache']['ttl'] ) && is_int( $settings['cache']['ttl'] ) ? $settings['cache']['ttl'] : self::
			                                   DEFAULT_CACHE_TTL;
			                              if ( !$controllerData = $this->loadCache( $this->cache['key'] ) ) {
			                                   $this->cache['status'] = false;
			                                   $controllerData = $this->processController( $controller, $method, $methods );
			                                   $this->saveCache( $controllerData, $this->cache['key'] );
			                              } else {
			                                   $this->cache['status'] = true;
			                                   $controllerData = $this->loadCache( $this->cache['key'] );
			                              }
			                         } else {
			                              $controllerData = $this->processController( $controller, $method, $methods );
			                         }

			                         // extract data from processed controller cached or not
			                         extract( $controllerData );

			                         // force production or debug in controller itself
			                         if ( isset( $getForcedProduction ) )
			                              $this->setProductionMode( $getForcedProduction );


			                         // overwrite working mode via query string
			                         if ( self::OVERWRITE_PRODUCTION )
			                              $this->getWorkingModeQueryString();

			                         // check for print response

			                         $this->request['print'] = is_null( $getPrintResponse ) ? true : $getPrintResponse;
			                         $this->response['status'] = $getResponseStatus ? $getResponseStatus : self::DEFAULT_RESPONSE_CODE;
			                         if ( $getResponseHeaders )
			                              $this->response['headers'] = $getResponseHeaders;
			                         $this->response['data'] = $getResponseData ? $getResponseData : null;

			                         $isRAW = false;
			                         if ( !is_null( $getResponseType ) ) {
			                              // available response types, included raw
			                              $array = array_merge( array_keys( self::$contentTypes ), array( 'raw' ) );
			                              $checkResponseTypes = in_array( $getResponseType, $array );

			                              // overwrite response format
			                              if ( $checkResponseTypes ) {
			                                   $this->request['format'] = $getResponseType;
			                                   $isRAW = $getResponseType == 'raw' ? true : false;
			                                   if ( $isRAW ) {
			                                        unset( $this->request['content-type'] );
			                                   } else {
			                                        $this->getContentType();
			                                   }
			                              }
			                         }

			                         if ( $useLogs && !$this->error )
			                              $this->processLog( $this->request['controller']['base'] . '-access', array(), $useDetailedLogs );
			                    }

			               } else {
			                    $this->error = 500;
			                    throw new Exception( 'Static or Private methods not supported in Controllers', 500 );
			               }


			               // empty response data
			               if ( !isset( $this->response['data'] ) && !$isRAW ) {
			               		if (empty($this->response['data'])) {
			               			$this->response['data'] = array();
			               		} else {
				                    $this->error = 405;
				                    throw new Exception( 'No response data', 405 );
			               		}
			               }
			          }
			          catch ( exception $re ) {
			               $this->response['status'] = $re->getCode();
			               $this->response['data'] = array( 'ErrorCode' => $re->getCode(), 'ErrorMessage' => $re->getMessage() );
			               if ( self::ENABLE_ERROR_LOGS && $this->error && !empty( $this->request['controller'] ) )
			                    $this->processLog( $this->request['controller']['base'] . '-error', array( $re->getCode(), $re->getMessage() ), self::
			                         ENABLE_DETAILED_ERROR_LOGS );
			          }

                      $this->writeConsoleData();

			     }


			     private function processController( $controller, $method, $load )
			     {

			          // run controller instance
			          $controller = $controller->newInstance( $this->request );
			          // invoke method
			          $method->invoke( $controller );

			          unset( $load[3] );
			          unset( $load[5] );

			          $run = array();
			          foreach ( $load as $item ) {
			               $run[$item->name] = call_user_func( array( $controller, $item->name ) );
			          }
			          $run['getResponseHeaders'] = $this->processResponseHeaders( $run['getResponseHeaders'] );
			          return $run;
			     }


			     // process logs
			     private function processLog( $file, $data, $detailed )
			     {
			          if ( $detailed ) {
			               $log = $this->buildDetailedLog( $data );
			          } else {
			               $log = $this->buildSimpleLog( $data );
			          }
			          $this->writeLog( $file, $log );
			     }

			     // build detailed log
			     private function buildDetailedLog( $data )
			     {
			          $log = print_r( array_merge( $this->request, $this->response ), true );
			          $log .= '----------------------------------------------------------';
			          return $log;
			     }

			     // build simple log
			     private function buildSimpleLog( )
			     {
			          $log = array();
			          $log[] = $this->request['method'];
			          $log[] = $this->request['resource']['clean'];
			          $log[] = $this->response['status'];
			          if ( $this->error )
			               $log[] = $this->response['data']['ErrorMessage'];
			          $log[] = $this->request['resource']['ajax'] == true ? 'ajax' : 'web';
			          $log[] = $this->request['format'];
			          $log[] = $this->request['controller']['base'];
			          $log[] = isset( $this->request['controller']['route'] ) ? $this->request['controller']['route'] : 'invalid';
			          $log[] = $this->request['resource']['remote'];
			          $log[] = self::VERSION;
			          return implode( ' | ', $log );
			     }


			     // process response headers
			     private function processResponseHeaders( $responseHeaders )
			     {
			          if ( is_null( $responseHeaders ) )
			               return null;

			          $responseHeaders = is_string( $responseHeaders ) ? array( $responseHeaders ) : $responseHeaders;
			          $headers = array();
			          foreach ( $responseHeaders as $string ) {
			               if ( preg_match( "/[:{1,1}]/", $string ) ) {
			                    $header = array_filter( array_map( 'trim', explode( ':', $string, 2 ) ) );
			                    // disallow empty headers
			                    if ( count( $header ) > 1 ) {
			                         $headerName = preg_replace( '/[\s]/', '-', ucwords( $header[0] ) );
			                         $headers[$headerName] = isset( $header[1] ) ? $header[1] : null;
			                    }

			               }
			          }
			          return $headers ? $headers : null;
			     }


			     // process auth
			     private function processAuth( $authData, $method )
			     {

			          // prepare data from method
			          $cleanAuthData = array();
			          $cleanAuthData['credentials'] = isset( $authData['credentials'] ) && is_array( $authData['credentials'] ) ? $authData['credentials'] : array( self::
			                    $defaultCredentials['username'] => self::$defaultCredentials['password'] );
			          $cleanAuthData['realm'] = isset( $authData['realm'] ) && is_string( $authData['realm'] ) ? trim( $authData['realm'] ) : trim( self::$defaultCredentials['realm'] );
			          $cleanAuthData['digest'] = isset( $authData['digest'] ) && is_bool( $authData['digest'] ) ? $authData['digest'] : self::$defaultCredentials['digest'];

			          // get protected methods
			          $methods = isset( $authData['methods'] ) ? $authData['methods'] : self::$defaultCredentials['methods'];

			          $methods = is_string( $methods ) && strtolower( trim( $methods ) ) == 'all' ? self::$methods : ( is_string( $methods ) ? array( $methods ) :
			               $methods );

			          // diference between allowed methods and parsed methods
			          $protectedMethods = array_intersect( self::$methods, array_map( 'strtolower', $methods ) );

			          // choose wich method is protected
			          if ( in_array( $method, $protectedMethods ) ) {
			               $this->request['auth'] = array();
			               $this->request['auth']['type'] = $cleanAuthData['digest'] == true ? 'digest' : 'basic';
			               $this->request['auth']['realm'] = $cleanAuthData['realm'];
			               $this->request['auth']['credentials'] = $cleanAuthData['credentials'];
			               $this->request['auth']['methods'] = $protectedMethods;

			               return $cleanAuthData['digest'] ? $this->processDigestAuth( $cleanAuthData ) : $this->processBasicAuth( $cleanAuthData );
			          }
			     }


			     // process Basich Auth
			     private function processBasicAuth( $authData )
			     {
			          $logged = false;
			          if ( isset( $_SERVER['PHP_AUTH_USER'] ) && isset( $_SERVER['PHP_AUTH_PW'] ) ) {
			               if ( isset( $authData['credentials'][$_SERVER['PHP_AUTH_USER']] ) && $_SERVER['PHP_AUTH_PW'] === $authData['credentials'][$_SERVER['PHP_AUTH_USER']] )
			                    $logged = true;
			          }

			          // send basic auth headers
			          if ( $logged === false ) {
			               header( $_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized' );
			               header( 'WWW-Authenticate: Basic realm="' . $authData['realm'] . '"' );
			          }
			          return $logged;
			     }


			     // process Digest Auth
			     private function processDigestAuth( $authData )
			     {
			          $logged = false;
			          if ( isset( $_SERVER['PHP_AUTH_DIGEST'] ) ) {

			               // parse digest data
			               $getDigest = $this->parseDigestData( $_SERVER['PHP_AUTH_DIGEST'] );
			               if ( $getDigest && isset( $authData['credentials'][$getDigest['username']] ) ) {

			                    // build digest data from controller
			                    $A1 = md5( $getDigest['username'] . ':' . $authData['realm'] . ':' . $authData['credentials'][$getDigest['username']] );
			                    $A2 = md5( $_SERVER['REQUEST_METHOD'] . ':' . $getDigest['uri'] );
			                    $response = md5( $A1 . ':' . $getDigest['nonce'] . ':' . $getDigest['nc'] . ':' . $getDigest['cnonce'] . ':' . $getDigest['qop'] . ':' . $A2 );
			                    if ( $response == $getDigest['response'] )
			                         $logged = true;
			               }
			          }
			          // send digest auth headers
			          if ( $logged === false ) {
			               header( $_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized' );
			               header( 'WWW-Authenticate: Digest realm="' . $authData['realm'] . '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5( $authData['realm'] ) .
			                    '"' );
			          }
			          return $logged;
			     }


			     // parse digest data
			     private function parseDigestData( $string )
			     {
			          $needed_parts = array(
			               'nonce' => 1,
			               'nc' => 1,
			               'cnonce' => 1,
			               'qop' => 1,
			               'username' => 1,
			               'uri' => 1,
			               'response' => 1 );
			          $data = array();

			          $keys = implode( '|', array_keys( $needed_parts ) );
			          preg_match_all( '@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $string, $matches, PREG_SET_ORDER );

			          foreach ( $matches as $m ) {
			               $data[$m[1]] = $m[3] ? $m[3] : $m[4];
			               unset( $needed_parts[$m[1]] );
			          }

			          return $needed_parts ? false : $data;
			     }


			     // clean urls
			     private function cleanUrl( $url )
			     {
			          $data = parse_url( $url );
			          $base_url = $data['scheme'] . '://' . $data['host'] . ( isset( $data['port'] ) ? ':' . $data['port'] : '' );

			          // split the path
			          $split = preg_split( '/[?|&|\#]/', $data['path'] );

			          $regex = '/\b(.*).(' . implode( '|', array_keys( self::$contentTypes ) ) . ')\b/i';
			          preg_match( $regex, $split[0], $matches );

			          // path stuff
			          $path = isset( $matches[1] ) ? '/' . $matches[1] : $split[0];
			          $ext = preg_split( '/[\.]/', $path );
			          $path = $ext[0];

			          $path = $this->cleanUrlHelper( $path );
			          $path = str_replace( '.', '-', $path );

			          // type stuff
			          $type = isset( $matches[2] ) ? $matches[2] : ( isset( $ext[1] ) ? $ext[1] : '' );
			          $type = !empty( $type ) ? '.' . strtolower( $type ) : '';

			          // build clean url
			          $cleanUrl = $base_url . $path . $type;

			          // separate query string from raw url
			          $queryString = preg_split( '/[?|&|\#]/', $url, 2 );

			          // url has query string
			          if ( isset( $queryString[1] ) ) {
			               parse_str( urldecode( $queryString[1] ), $get_array );
			               $cleanUrl .= count( $get_array ) > 0 ? '?' . http_build_query( $get_array ) : '';
			          }

			          // redir to valid location
			          if ( $this->request['resource']['raw'] != $cleanUrl && self::ENABLE_REDIR == true ) {
			               header( 'Location: ' . $cleanUrl );
			               die();
			          }

			          return $cleanUrl;

			     }


			     // encode response
			     private function encodeResponse( $data )
			     {
			          switch ( $this->request['format'] ) {
			               case 'json':
			                    $response = json_encode( $data );
			                    break;
			               case 'xml':
			                    $response = $this->myXMLmake( $data );
			                    break;
			               case 'txt':
			                    $response = print_r( $data, true );
			                    break;
			               case 'php':
			                    $response = serialize( $data );
			                    break;
			          }
			          return $response;
			     }


			     // render the controller
			     private function render()
			     {
			     
			          $statusCode = $this->response['status'] ? $this->response['status'] : 200;
			          header( $_SERVER['SERVER_PROTOCOL'] . ' ' . $statusCode . ' ' . self::$codes[$statusCode] );
			          if ( $this->mode == 'production' && !$this->error && $this->request['format'] != 'raw' )
			               header( 'Content-Type: ' . $this->request['content-type'] );

			          // set respose headers set by controller
			          if ( isset( $this->response['headers'] ) && is_array( $this->response['headers'] ) ) {
			               foreach ( $this->response['headers'] as $key => $value ) {
			                    header( $key . ': ' . $value );
			               }
			          }

			          if ( $this->request['resource']['ajax'] === true && self::ALLOW_AJAX_CALLS )
			               header( 'Access-Control-Allow-Origin: ' . self::DEFAULT_CROSSDOMAIN );
			          
			          // **********************************************************************************
			          // EXPORT DATA
			          // **********************************************************************************
			          if (isset($this->response['data']['export']) && $this->response['data']['export'] == 1){
                          header("Content-type:text/csv");
                          header("Content-Disposition:attachment;filename=".$this->response['data']['filename']);
                          header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
                          header('Expires:0');
                          header('Pragma:public');
                          return print $this->response['data']['str'];
			          }
			          // EXPORT END
			               
			          $isRAW = $this->request['format'] == 'raw' ? true : false;
			          $data = $isRAW ? $this->response['data'] : $this->encodeResponse( $this->response['data'] );

			          // no errors
			          if ( !$this->error )
			               return $this->mode == 'production' && $this->request['print'] ? print $data : $data;

			          // handling errors
			          if ( $this->mode == 'production' && self::SHOW_RESPONSE_ERRORS ) {
			               if ( self::USE_TEMPLATE_PAGE ) {
			                    $include = array();
			                    foreach ( glob( 'template.*' ) as $file ) {
			                         $include[] = realpath( $file );
			                    }
			                    if ( !empty( $include ) ) {
			                         return include ( $include[0] );
			                    }
			               }
			               return print $this->error . ' ' . self::$codes[$this->error];
			          }
			     }

			     // maintence
			     private function maintence()
			     {
			          if ( self::ENABLE_MAINTENCE ) {
			               $controlKey = $this->getControlCacheKey();
			               if ( !$maintence = $this->loadCache( $controlKey, self::DEFAULT_MAINTENCE_TTL ) ) {
			                    $maintence = time();

			                    // save last maintence
			                    $this->saveCache( $maintence, $controlKey );

			                    // flush old cache
			                    $this->flushCache( array( '*__*', self::DEFAULT_OLD_CACHE_TTL ) );

			                    // flush old logs
			                    $this->flushLogs( array( '*', self::DEFAULT_OLD_LOGS_TTL ) );
			               }
			               $this->server['maintence'] = self::DEFAULT_MAINTENCE_TTL - ( time() - $maintence );
			          }
			     }


			     // get current url
			     private function getCurrentUrl( $raw = false )
			     {
			          $url = ( isset( $_SERVER['REQUEST_SCHEME'] ) ? $_SERVER['REQUEST_SCHEME'] : self::DEFAULT_HTTP_SCHEME ) . '://';
			          $url .= $_SERVER['SERVER_NAME'] . ( ( $_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443 ) ? '' : ':' . $_SERVER['SERVER_PORT'] ) .
			               $_SERVER['REQUEST_URI'];
			          return $url;
			     }


			     // clean Url Helper
			     private function cleanUrlHelper( $string )
			     {
			          $string = preg_replace( '/([^a-zA-ZA0-9:\/\?\=&-_\s])/', '', urldecode( $string ) );
			          $string = preg_replace( '/\\s/', '-', $string );
			          $string = preg_replace( '/(?<!:)([^a-zA-Z0-9-_])\\1+/', '$1', $string );
			          return $string;
			     }

			     // make the xml
			     private function myXMLasxml( $array, $elements = array(), $tabcount = 0 )
			     {
			          $tabs = '';
			          for ( $i = 0; $i < $tabcount; $i++ ) {
			               $tabs .= "\t";
			          }
			          $result = '';
			          foreach ( $array as $key => $val ) {
			               $element = isset( $elements[0] ) ? $elements[0] : $key;
			               
			               if(is_numeric($element)){
				               
				               $element="data";
			               }
			               
			               $result .= $tabs;
			               $result .= "<" . $element . ">";
			               if ( !is_array( $val ) )
			                    $result .= $val;
			               else {
			                    $result .= "\r\n";
			                    $result .= $this->myXMLasxml( $val, array_slice( $elements, 1, true ), $tabcount + 1 );
			                    $result .= $tabs;
			               }

			               $result .= "</" . $element . ">\r\n";
			          }
			          return $result;
			     }


			     // build xml
			     private function myXMLmake( $data, $root = 'root', $elements = array() )
			     {
			          $result = '<?xml version="1.0" encoding="utf-8"?>' . "\r\n";
			          $result .= '<' . $root . '>' . "\r\n";
			          $result .= is_array( $data ) ? $this->myXMLasxml( $data, $elements, 1 ) : trim( $data );
			          $result .= '</' . $root . '>' . "\r\n";
			          return $result;
			     }


			     // get working mode via query string
			     private function getWorkingModeQueryString()
			     {
			          if ( $this->checkQueryStringSimpleHash( self::QUERY_HASH_VAR ) )
			               $this->mode = isset( $_GET['mode'] ) && in_array( strtolower( trim( $_GET['mode'] ) ), array( 'production', 'debug' ) ) ? strtolower( trim( $_GET['mode'] ) ) :
			                    $this->mode;
			     }


			     // disallow users to run debug mode without a hash key
			     private function checkQueryStringSimpleHash( $var )
			     {
			          return is_bool( $var ) && !$var ? true : ( isset( $_GET[$var] ) && $_GET[$var] == self::QUERY_HASH_VAL ? true : false );
			     }


			     // detect if we have an associative array
			     private function isAssoc( $arr )
			     {
			          return array_keys( $arr ) !== range( 0, count( $arr ) - 1 );
			     }


			     // prepare unique key for each cache request
			     private function makeUniqueCacheKey()
			     {

			          $cleanUrl = $this->request['resource']['clean'];

			          // unset mode from query string to avoid multiple cache on debug/production
			          if ( self::OVERWRITE_PRODUCTION && ( isset( $_GET[self::QUERY_HASH_VAR] ) || isset( $_GET['mode'] ) ) ) {
			               $url = parse_url( $cleanUrl );
			               if ( isset( $url['query'] ) ) {
			                    $query = parse_str( $url['query'], $array );

			                    if ( isset( $array['mode'] ) && in_array( $array['mode'], $array ) ) {
			                         unset( $array['mode'] );
			                         unset( $array[self::QUERY_HASH_VAR] );
			                         unset( $url['query'] );
			                    }
			                    $query = http_build_query( $array );
			                    $cleanUrl = '';
			                    foreach ( $url as $key => $item ) {
			                         $cleanUrl .= $key == 'scheme' ? $item . '://' : ( $key == 'port' ? ':' . $item : $item );
			                    }
			                    $cleanUrl .= !empty( $query ) ? '?' . $query : '';
			               }
			          }
			          $data = array();
			          $data[] = $this->request['method'];
			          $data[] = $cleanUrl;
			          $data[] = $this->request['resource']['remote'];
			          $data[] = $this->request['format'];
			          $data[] = $this->request['controller']['class'];
			          $data[] = $this->request['controller']['wildcard'];
			          $data[] = $this->request['controller']['route'];
			          $pre = $this->request['controller']['route'] == 'static' ? $this->request['controller']['class'] : $this->request['controller']['wildcard'];
			          return str_replace( 'Controller_', '', $pre ) . '_' . md5( implode( '|', $data ) );
			     }


			     // make control cache key
			     private function getControlCacheKey()
			     {
			          return __class__ . '_control';
			     }


			     // load cache from file
			     public function loadCache( $id, $ttl = null )
			     {

			          $file = $this->server['cache'] . $id . '.cache';
			          if ( $this->isValidCache( $file, $ttl ) ) {
			               return $this->readCache( $file );
			          }
			          return false;
			     }


			     // save cache to file
			     public function saveCache( $data, $id )
			     {
			          $file = $this->server['cache'] . $id . '.cache';
			          $this->writeCache( $data, $file );
			     }


			     // check valid cache
			     private function isValidCache( $file, $ttl )
			     {
			          $ttl = isset( $ttl ) && is_int( $ttl ) ? $ttl : $this->cache['ttl'];
			          return file_exists( $file ) && ( filemtime( $file ) > ( time() - $ttl ) ) ? true : false;
			     }


			     // read data from file
			     private function readCache( $file, $serial = true )
			     {
			          if ( file_exists( $file ) ) {
			               $handle = fopen( $file, 'r' );
			               @$data = fread( $handle, filesize( $file ) );
			              @fclose( $handle );
			               return $serial ? unserialize( $data ) : $data;
			          }
			          return false;
			     }


			     // write data to file
			     private function writeCache( $data, $file, $serial = true )
			     {
			          $data = $serial ? serialize( $data ) : $data;
			          $handle = fopen( $file, 'w' );
			         // fwrite( $handle, $data );
			          //fclose( $handle );
			     }

			     // flush controllers cache
			     private function flushCache( $flushData = null )
			     {
			          // build a mask for cache files
			          if ( $flushData === true ) {
			               $pre = $this->request['controller']['route'] == 'static' ? $this->request['controller']['class'] : $this->request['controller']['wildcard'];
			               $mask = str_replace( 'Controller_', '', $pre );
			          } elseif ( is_array( $flushData ) && isset( $flushData[0] ) && is_string( $flushData[0] ) ) {
			               $mask = trim( str_replace( 'Controller_', '', $flushData[0] ) );
			          } elseif ( is_string( $flushData ) && trim( $flushData ) == 'global' ) {
			               $mask = '*';
			          }

			          // search cache files based on mask
			          $files = $this->searchfiles( $mask . '*', 'cache', 'cache' );
			          $isTTL = is_array( $flushData ) && isset( $flushData[1] ) && is_int( $flushData[1] ) && $flushData[1] > 5 ? true : false;
			          // check for older than ttl files
			          if ( !empty( $files ) && $isTTL )
			               $files = $this->testOldFiles( $files, $flushData[1] );

			          // delete cache
			          $this->deleteFiles( $files );
			     }

			     // flush logs
			     private function flushLogs( $flushData = null )
			     {
			          // search cache files based on mask
			          $files = $this->searchfiles( $flushData[0], 'logs', 'log' );
			          $isTTL = is_array( $flushData ) && isset( $flushData[1] ) && is_int( $flushData[1] ) && $flushData[1] > 5 ? true : false;
			          // check for older than ttl files
			          if ( !empty( $files ) && $isTTL )
			               $files = $this->testOldFiles( $files, $flushData[1] );

			          // delete files
			          $this->deleteFiles( $files );
			     }


			     // search cache files
			     private function searchFiles( $mask, $dir, $type )
			     {
			          $mask = $this->server[$dir] . $mask . '.' . trim( $type );
			          return glob( $mask, GLOB_BRACE | GLOB_NOSORT );
			     }

			     // test old files than ttl
			     private function testOldFiles( $files, $ttl )
			     {
			          foreach ( $files as $key => $file ) {
			               // do not delete fresh files
			               $isFresh = ( time() - filemtime( $file ) ) > $ttl ? false : true;
			               if ( $isFresh )
			                    unset( $files[$key] );
			          }
			          return array_values( $files );

			     }


			     // delete files from array
			     private function deleteFiles( $files )
			     {
			          if ( !empty( $files ) )
			               array_map( 'unlink', $files );
			     }


			     // write logs to file
			     private function writeLog( $file, $msg )
			     {
			          // check if base dir is writable
			          if ( !is_writable( dirname( __class__ ) ) )
			               die( 'Error: set base directory writable!' );

			          $file = $this->server['logs'] . $file . '.log';

			          if ( self::ENABLE_TRIM_LOGS && file_exists( $file ) ) {
			               $basename = pathinfo( $file );
			               $basename = $basename['filename'];
			               $next = $this->server['logs'] . $basename . '_archive_1.log';

			               // file size is bigger than allowed
			               if ( filesize( $file ) > self::DEFAULT_MAX_LOG_SIZE ) {

			                    $files = $this->searchFiles( $this->request['controller']['base'] . '*_archive_*', 'logs', 'log' );

			                    if ( !empty( $files ) ) {

			                         // get the last
			                         sort( $files, SORT_NATURAL | SORT_FLAG_CASE );
			                         $next = end( $files );

			                         // match the last item
			                         preg_match( '/_(\d+)\.log/', $next, $matches );
			                         if ( isset( $matches[1] ) && is_numeric( $matches[1] ) )
			                              $next = $this->server['logs'] . $basename . str_replace( '###', $matches[1] + 1, '_archive_###.log' );
			                    }
			                    // rename log
			                    rename( $file, $next );
			               }
			          }

			          // open file
			          $fd = fopen( $file, 'a' );
			          // append date/time to message
			          $str = '[' . date( 'Y/m/d h:i:s', time() ) . '] ' . $msg;
			          // write string
			          fwrite( $fd, $str . "\r\n" );
			          // close file
			          fclose( $fd );
			     }


			     // prepare directory
			     private function prepareDir( $dir, $perm = 0777 )
			     {
			          // try to make logs dir
			          if ( !empty( $dir ) && !file_exists( $dir ) )
			               mkdir( $dir, $perm ) or die( 'Cannot make ' . $dir );
			     }


			     // debug options
			     private function debug()
			     {
			          if ( $this->mode == 'debug' ) {
			               $bgcolor = $this->error ? 'FF0077' : 'A7CD00';
			               echo '<h1 style="font-size:14pt;color:#fff;font-family:verdana;width:auto;background:#' . $bgcolor .
			                    ';padding:5px;" >debug mode</h1><pre style="background:#f5f5f5;padding:5px;border:0;">';
			               print_r( $this );
			               echo "</pre>";
			          }
			     }

			     private function writeConsoleData()
			     {
			          if ( self::ENABLE_CONSOLE && in_array('sqlite', PDO::getAvailableDrivers())) {
			               $error = $this->error ? true  :false;
			               //$db = new PDO( 'sqlite:console2.sqlite:mode=memory' );
                           $db = new PDO( 'sqlite:console.sqlite',
                                null,
                                null,
                                array(PDO::ATTR_PERSISTENT => true)  );

			               $db->exec( 'CREATE TABLE IF NOT EXISTS data (ID INTEGER PRIMARY KEY, line TEXT, controller TEXT, method TEXT, error TEXT, time TEXT, date TEXT)' );
			               $db->exec( 'INSERT INTO data (line, controller, method, error, time, date) VALUES ("'.$this->buildSimpleLog().'", "'.$this->request['controller']['base'].'", "'.$this->request['method'].'","'.$error.'", "'.time().'", "'.date( 'Y/m/d h:i:s', time()).'");' );
			               $db = null;
			          }
			     }

			     // is AJAX call
			     private function isAJAX()
			     {
			          return !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ? true : false;
			     }


			     // validate domains
			     private function isValidDomain( $domain )
			     {
			          return ( preg_match( "/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain ) //valid chars check
			               && preg_match( "/^.{1,253}$/", $domain ) //overall length check
			               && preg_match( "/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain ) ); //length of each label
			     }

			}


			// abstract class to be extended by controller
			abstract class myRestController
			{
			     protected $request;
			     protected $production;
			     protected $responseStatus;
			     protected $responseHeaders;
			     protected $printResponse;
			     protected $response;
			     protected $auth;
			     protected $logs;
			     protected $cache;


			     // constructor for extend
			     public function __construct( $request )
			     {
			          $this->request = $request;
			     }

			     // get the forced producion option
			     final public function getForcedProduction()
			     {
			          return is_bool( $this->production ) ? $this->production : null;
			     }

			     // get response status
			     final public function getResponseStatus()
			     {
			          return isset( $this->responseStatus ) ? $this->responseStatus : null;
			     }

			     // get response headers
			     final public function getResponseHeaders()
			     {
			          return isset( $this->responseHeaders ) ? $this->responseHeaders : null;
			     }

			     // get response auth
			     final public function getSettingsData()
			     {
			          $auth = isset( $this->auth ) ? $this->auth : null;
			          $production = isset( $this->production ) ? $this->production : null;
			          $cache = isset( $this->cache ) ? $this->cache : null;
			          $logs = isset( $this->logs ) ? $this->logs : null;
			          return array(
			               'auth' => $auth,
			               'production' => $production,
			               'cache' => $cache,
			               'logs' => $logs );
			     }

			     // check allowed response types in controller
			     final public function getResponseType()
			     {
			          return isset( $this->responseType ) ? trim( strtolower( $this->responseType ) ) : null;
			     }

			     // get response auth
			     final public function getAuthData()
			     {
			          return isset( $this->auth ) ? $this->auth : null;
			     }

			     // get the response
			     final public function getPrintResponse()
			     {
			          return is_bool( $this->printResponse ) ? $this->printResponse : null;
			     }

			     // TODO delete files from cache
			     final public function getDeleteFiles()
			     {
			     	return isset($this->deleteFiles) ? $this->deleteFiles : null;
			     }

			     // get the response
			     final public function getResponseData()
			     {
			          return isset( $this->response ) ? $this->response : null;
			     }

			}
