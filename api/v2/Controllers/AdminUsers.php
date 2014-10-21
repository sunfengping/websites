<?php
// this controller use the following endpoint:
// http://site.com/api/AdminUsers <--- index route
// http://site.com/api/AdminUsers/item <--- get item details

/**
 * ************************************************************************************************
 * |
 * | http://wekonnect.com
 * | chris@wekonnect.com
 * |
 * |**************************************************************************************************
 * |
 * | By using this software you agree that you have read and acknowledged our End-User License
 * | Agreement available at http://wekonnect.com/ and to be bound by it.
 * |
 * | Copyright (c) 2013 wekonnect.com All rights reserved.
 * |*************************************************************************************************
 */
class Controller_AdminUsers__index extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	public function get() {
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$myauth = new mysql ();
			
			$churchId = $data ['churchId'];
			
			// check unsafe fields
			if (! get_magic_quotes_gpc ()) {
				$churchId = addslashes ( $churchId );
			}
			
			$myauth->query ( "SELECT * FROM wkadminusers WHERE Church_Id=$churchId" );
			
			$result = array ();
			if ($myauth->num_rows () > 0) {
				while ( $myauth->movenext () ) {
					$arr = array ();
					
					array_push ( $arr, $myauth->getfield ( "id" ) );
					array_push ( $arr, $myauth->getfield ( "display_order" ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "firstname" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "surname" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "email" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "mobile" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "position" ) ) );
					array_push ( $arr, $myauth->getfield ( "id" ) );
					
					// array_push ( $arr, '<button type="button" class="btn btn-default btn-sm edit" data-id="' . $myauth->getfield ( "id" ) . '"><i class="icon-pencil"></i></button>' . '<button type="button" class="btn btn-default btn-sm delete" data-id="' . $myauth->getfield ( "id" ) . '"><i class="icon-trash"></i></button>' );
					$result [] = $arr;
				}
			}
			$return ['response'] = $result;
		}
		
		// set the response status and text
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	
	// **********************************************************************************
	// SELECT Admin Users DATA
	// **********************************************************************************
	function getAllAdminUsers($data) {
	}
	
	// **********************************************************************************
	// Delete Admin User DATA
	// **********************************************************************************
	function deleteAdminUser($data) {
		$myauth = new mysql ();
		
		$id = $data ['id'];
		// check unsafe fields
		if (! get_magic_quotes_gpc ()) {
			$id = addslashes ( $id );
		}
		
		$sql = "DELETE FROM wkadminusers WHERE id = $id";
		$results = $myauth->query ( $sql );
		if ($results == "1") {
			return "success";
		} else {
			return "failed";
		}
	}
	
	// **********************************************************************************
	// Get Admin User DATA
	// **********************************************************************************
	function getAdminUser($data) {
		$myauth = new mysql ();
		
		$id = $data ['id'];
		// check unsafe fields
		if (! get_magic_quotes_gpc ()) {
			$id = addslashes ( $id );
		}
		
		$sql = "SELECT * FROM wkadminusers WHERE id = $id";
		$myauth->query ( $sql );
		$rowUsers = $myauth->getAll ();
		
		$getMenu = "SELECT IFNULL(AdminMenu,0) FROM wkadminusers WHERE id = $id";
		$res = $myauth->single_query ( $getMenu );
		
		$sql = "SELECT id,name FROM admin_menu WHERE id not in ($res) ORDER BY id";
		$myauth->query ( $sql );
		$rowMenus = $myauth->getAll ();
		
		$sql = "SELECT id,name FROM admin_menu WHERE id in ($res) ORDER BY id";
		$myauth->query ( $sql );
		$rowAdminMenus = $myauth->getAll ();
		
		return array (
				$rowUsers [0],
				$rowMenus,
				$rowAdminMenus 
		);
	}
	
	// **********************************************************************************
	// Add Or Update Admin User DATA
	// **********************************************************************************
	function addOrUpdate($data) {
		
		// check required fields
		if (! isset ( $data ['firstname'] ) || $data ['firstname'] == '') {
			$info ['status'] = '0';
			$info ['msg'] = 'firstname not null';
			return $info;
		}
		
		if (! isset ( $data ['surname'] ) || $data ['surname'] == '') {
			$info ['status'] = '0';
			$info ['msg'] = 'surname not null';
			return $info;
		}
		if (! isset ( $data ['password'] ) || $data ['password'] == '') {
			$info ['status'] = '0';
			$info ['msg'] = 'password not null';
			return $info;
		}
		if (! isset ( $data ['position'] ) || $data ['position'] == '') {
			$info ['status'] = '0';
			$info ['msg'] = 'position not null';
			return $info;
		}
		if (! isset ( $data ['email'] ) || $data ['email'] == '') {
			$info ['status'] = '0';
			$info ['msg'] = 'email not null';
			return $info;
		}
		if (! isset ( $data ['phone'] ) || $data ['phone'] == '') {
			$info ['status'] = '0';
			$info ['msg'] = 'phone not null';
			return $info;
		}
		
		$id = $data ['id'];
		$churchId = $data ['COMPANY_ID'];
		$admin = $data ['admin'];
		$firstname = $data ['firstname'];
		$surname = $data ['surname'];
		$password = $data ['password'];
		$position = $data ['position'];
		$email = $data ['email'];
		$phone = $data ['phone'];
		$department = $data ['department'];
		$adminmenu = $data ['adminmenu'];
		
		// check unsafe fields
		if (! get_magic_quotes_gpc ()) {
			$id = addslashes ( $id );
			$churchId = addslashes ( $churchId );
			$firstname = addslashes ( $firstname );
			$surname = addslashes ( $surname );
			$password = addslashes ( $password );
			$position = addslashes ( $position );
			$email = addslashes ( $email );
			$phone = addslashes ( $phone );
			$adminmenu = addslashes ( $adminmenu );
		}
		
		$myauth = new mysql ();
		
		if ($id != "") {
			$sql = "UPDATE wkadminusers
					SET
						admin = '$admin',
						firstname = '$firstname',
						surname = '$surname',
						`password` = '$password',
						position = '$position',
						email = '$email',
						phone = '$phone',
						department = '$department',
						mobile = '$phone',
						AdminMenu = '$adminmenu' 
					WHERE id = $id";
		} else {
			$getId = "SELECT MAX(display_order) FROM wkadminusers WHERE Church_id = $churchId";
			$res = $myauth->single_query ( $getId );
			if ($res) {
				$orderId = $res + 1;
			} else {
				$orderId = 1;
			}
			$sql = "INSERT INTO wkadminusers
						(id,firstname,surname,password,position,email,phone,mobile,department,admin,AdminMenu,Church_id,display_order)
					VALUES
						(DEFAULT,'$firstname','$surname','$password','$position','$email','$phone','$phone','$department','$admin','$adminmenu',$churchId,$orderId);";
		}
		
		$result = $myauth->query ( $sql );
		if ($result) {
			$info ['status'] = '1';
			$info ['id'] = $myauth->lastinsert ();
			$info ['msg'] = 'success';
		} else {
			$info ['status'] = '0';
			if (mysql_affected_rows () == 0) {
				$info ['msg'] = 'Data has not changed';
			} else {
				$info ['msg'] = 'DB error';
			}
		}
		return $info;
	}
	
	// **********************************************************************************
	// Updata Orders
	// **********************************************************************************
	function updataOrders($data) {
		$myauth = new mysql ();
		$arr = json_decode ( $data ['arr'] );
		foreach ( $arr as $v ) {
			$sql = "UPDATE wkadminusers SET display_order = $v[1] WHERE id = $v[0];";
			$myauth->query ( $sql );
		}
		return $this->getAllAdminUsers ( $data );
	}
	
	// **********************************************************************************
	// Get Menu List
	// **********************************************************************************
	function getMenuList() {
		$myauth = new mysql ();
		$sql = "SELECT id,name FROM admin_menu";
		$myauth->query ( $sql );
		return $myauth->getAll ();
	}
}

/**
 * ************************************************************************************************
 * |
 * | http://wekonnect.com
 * | chris@wekonnect.com
 * |
 * |**************************************************************************************************
 * |
 * | By using this software you agree that you have read and acknowledged our End-User License
 * | Agreement available at http://wekonnect.com/ and to be bound by it.
 * |
 * | Copyright (c) 2013 wekonnect.com All rights reserved.
 * |*************************************************************************************************
 */
class Controller_AdminUsers__item extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	public function post() {
		
		// set the default response text and status
		$response = 'You need to authenticate!';
		$status = 401; // set the response to unauthorized
		$myauth = new mysql ();
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		
		$headers = $this->request ['headers'];
		$checkHeader = isset ( $headers ['API-KEY'] ) ? true : false;
		
		// here you can authorize by calling database or something where your tokens are stored
		if ($checkHeader) {
			$token = $myauth->single_query ( "SELECT token FROM church WHERE id =" . $data ["COMPANY_ID"] . "" );
			$userAuthorized = $headers ['API-KEY'] == $token ? true : false;
			
			// user is authorized change the response and status code
			if ($userAuthorized) {
				$status = 200; // set the response to OK
				$this->responseHeaders [] = 'Welcome user, you\'re authorized to use this API';
				
				/**
				 * ****************************************************************************************
				 */
				
				$sql = "SELECT * FROM latest_news WHERE id ='" . $data ["NewsItem"] . "' ";
				
				$DataSet = array ();
				
				$myauth->query ( $sql );
				if ($myauth->num_rows () > 0) {
					while ( $myauth->movenext () ) {
						
						$datetime = strtotime ( $myauth->getfield ( "sent_date" ) );
						$mysqldate = date ( "d/m/Y  H:i ", $datetime );
						$msg = strip_tags ( urldecode ( $myauth->getfield ( "msg" ) ) );
						
						if ($myauth->getfield ( "image_url" ) == "") {
							$THUMB = IMAGEURL . "timthumb/timthumb.php?src=http://admin.wekonnect.com/dashboard/no_image_large.png";
						} else {
							$THUMB = IMAGEURL . "timthumb/timthumb.php?src=http://admin.wekonnect.com/" . $myauth->getfield ( "image_url" );
						}
						$a = array ();
						$a ['datetimes'] = $datetime;
						$a ['mysqldates'] = $mysqldate;
						$a ['msg'] = htmlentities ( $msg );
						$a ['thumb'] = htmlentities ( $THUMB );
						$a ['title'] = htmlentities ( strip_tags ( urldecode ( $myauth->getfield ( "title" ) ) ) );
						$a ['file_url'] = htmlentities ( $myauth->getfield ( "image_url" ) );
						
						array_push ( $DataSet, $a );
					}
					$response = $DataSet;
				} else {
					
					$response = 'Sorry no data';
					$status = 204; // set the response to unauthorized
				}
			
			/**
			 * ****************************************************************************************
			 */
			}
		}
		
		// set the response status and text
		$this->responseStatus = $status;
		$this->response = $response;
	}
}
class authenticate {
	public function check($data, $headers) {
		// set the default response text and status
		$response = 'You need to authenticate!';
		$status = 401; // set the response to unauthorized
		
		$myauth = new mysql ();
		
		$checkHeader = isset ( $headers ['API-KEY'] ) ? true : false;
		$checkChurch = isset ( $data ["churchId"] ) ? true : false;
		// here you can authorize by calling database or something where your tokens are stored
		if ($checkHeader && $checkChurch) {
			
			$token = $myauth->single_query ( "SELECT token FROM church WHERE id =" . $data ["churchId"] . "" );
			$userAuthorized = $headers ['API-KEY'] == $token ? true : false;
			
			// user is authorized change the response and status code
			if ($userAuthorized) {
				$response = 'Welcome user, you\'re authorized to use this API.';
				$status = 200; // set the response to unauthorized
			}
		}
		return array (
				'status' => $status,
				'response' => $response 
		);
	}
}