<?php
// this controller use the following endpoint:
// http://site.com/api/Campus <--- index route
// http://site.com/api/Campus/item <--- get item details

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
class Controller_Campus__index extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->cache ['ttl'] = 3600;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
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
			
			$sql = "SELECT id,display_order,title,phone,status,campus_email FROM campus WHERE church_id = '$churchId'";
			$myauth->query ( $sql );
			$result = array ();
			
			if ($myauth->num_rows () > 0) {
				while ( $myauth->movenext () ) {
					$arr = array ();
					
					array_push ( $arr, $myauth->getfield ( 'id' ) );
					array_push ( $arr, $myauth->getfield ( 'display_order' ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( 'title' ) ) );
					array_push ( $arr, $myauth->getfield ( 'status' ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( 'phone' ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( 'campus_email' ) ) );
					array_push ( $arr, $myauth->getfield ( 'id' ) );
					
					$result [] = $arr;
				}
			}
			$return ['response'] = $result;
		}
		
		// set the response status and text
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	public function delete() {
		// grab params from query string [this will read all the params from query string]
		$data = array ();
		parse_str ( file_get_contents ( 'php://input' ), $data );
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$myauth = new mysql ();
			
			$id = $data ['id'];
			// check unsafe fields
			if (! get_magic_quotes_gpc ()) {
				$id = addslashes ( $id );
			}
			
			$sql = "delete from campus where id = $id";
			$result = $myauth->query ( $sql );
			
			$return ['response'] = $result == "1" ? "success" : "failed";
		}
		
		// set the response status and text
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	public function put() {
		$data = array ();
		parse_str ( file_get_contents ( 'php://input' ), $data );
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$myauth = new mysql ();
			$arr = json_decode ( $data ['arr'] );
			foreach ( $arr as $v ) {
				$sql = "UPDATE campus SET display_order = $v[1] WHERE id = $v[0];";
				$myauth->query ( $sql );
			}
		}
		
		// set the response status and text
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
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
class Controller_Campus__item extends myRestController {
	
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
			
			$id = $data ['id'];
			// check unsafe fields
			if (! get_magic_quotes_gpc ()) {
				$id = addslashes ( $id );
			}
			
			$sql = "select *  from campus where id = $id";
			$myauth->query ( $sql );
			$return ['response'] = $myauth->getAll ();
		}
		
		// set the response status and text
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	public function post() {
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			// check required fields
			if (! isset ( $data ['title'] ) || ! $data ['title']) {
				$info ['status'] = '0';
				$info ['msg'] = 'title not null';
			} elseif (! isset ( $data ['street_address'] ) || ! $data ['street_address']) {
				$info ['status'] = '0';
				$info ['msg'] = 'street_address not null';
			} elseif (! isset ( $data ['postcode'] ) || ! $data ['postcode']) {
				$info ['status'] = '0';
				$info ['msg'] = 'postcode not null';
			} elseif (! is_numeric ( $data ['postcode'] )) {
				$info ['status'] = '0';
				$info ['msg'] = 'postcode is not a number';
			} elseif (! isset ( $data ['state'] ) || ! $data ['state']) {
				$info ['status'] = '0';
				$info ['msg'] = 'state not null';
			} elseif (! isset ( $data ['phone'] ) || ! $data ['phone']) {
				$info ['status'] = '0';
				$info ['msg'] = 'phone not null';
			} elseif (! isset ( $data ['campus_email'] ) || ! $data ['campus_email']) {
				$info ['status'] = '0';
				$info ['msg'] = 'campus_email not null';
			} else if (! isset ( $data ['image_url'] ) || ! $data ['image_url']) {
				$info ['status'] = '0';
				$info ['msg'] = 'image_url not null';
			} elseif (! isset ( $data ['general'] ) || ! $data ['general']) {
				$info ['status'] = '0';
				$info ['msg'] = 'general not null';
			} else {
				// get files
				$id = $data ['id'];
				$title = $data ['title'];
				$churchId = $data ['churchId'];
				$street_address = $data ['street_address'];
				$postcode = $data ['postcode'];
				$state = $data ['state'];
				$phone = $data ['phone'];
				$campus_email = $data ['campus_email'];
				$Facebook = $data ['Facebook'];
				$Twitter = $data ['Twitter'];
				$Instagram = $data ['Instagram'];
				$image_url = $data ['image_url'];
				$general = $data ['general'];
				$CampusActive = $data ['CampusActive'];
				$lat = $data ['lat'];
				$lng = $data ['lng'];
				
				// check unsafe fields
				if (! get_magic_quotes_gpc ()) {
					$id = addslashes ( $id );
					$title = addslashes ( $title );
				}
				
				$myauth = new mysql ();
				
				// code sql
				if ($data ['id'] != "") {
					$sql = "update campus set
								title='$title',street_address='$street_address',postcode=$postcode,state='$state',phone='$phone'
								,campus_email='$campus_email',Facebook='$Facebook',Twitter='$Twitter',Instagram='$Instagram'
								,image_url='$image_url',general='$general',CampusActive='$CampusActive',lat='$lat',lng='$lng'
					where id=$id";
					$result = $myauth->query ( $sql );
				} else {
					$getId = "SELECT MAX(display_order) FROM tithes_cat WHERE church_id = $churchId";
					$res = $myauth->single_query ( $getId );
					if ($res) {
						$orderId = $res + 1;
					} else {
						$orderId = 1;
					}
					$sql = "insert into campus
								(title,church_id,street_address,postcode,state,phone,campus_email
								,Facebook,Twitter,Instagram,image_url,general,CampusActive,lat,lng,display_order)
							values
								('$title',$churchId,'$street_address',$postcode,'$state','$phone','$campus_email'
								,'$Facebook','$Twitter','$Instagram','$image_url','$general','$CampusActive','$lat','$lng',$orderId)";
					$result = $myauth->query ( $sql );
					$id = $myauth->lastinsert ();
				}
				if ($result) {
					$info ['status'] = '1';
					$info ['msg'] = 'success';
					$info ['id'] = $id;
				} else {
					$info ['status'] = '0';
					if ($myauth->num_rows () == 0) {
						$info ['msg'] = 'Data has not changed';
					} else {
						$info ['msg'] = 'Save failed';
					}
				}
			}
			$return ['response'] = $info;
		}
		
		// set the response status and text
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
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
class Controller_Campus__common extends myRestController {
	
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
			$sql = "SELECT id, title,phone,status,campus_email FROM campus WHERE church_id = '" . $data ["churchId"] . "' ORDER BY id";
			$myauth->query ( $sql );
			$result = array ();
			$arr = array ();
			if ($myauth->num_rows () > 0) {
				while ( $myauth->movenext () ) {
					$arr ['id'] = $myauth->getfield ( 'id' );
					$arr ['text'] = utf8_encode ( $myauth->getfield ( 'title' ) );
					$result [] = $arr;
				}
			}
			$return ['response'] = $result;
		}
		
		// set the response status and text
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
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