<?php
// this controller use the following endpoint:
// http://site.com/api/Home <--- index route

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
class Controller_Home__index extends myRestController {
	
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
			$sql = "SELECT CASE admin WHEN 'Y' THEN -1 ELSE AdminMenu END AS AdminMenu FROM wkadminusers WHERE id = '" . $data ['userId'] . "'";
			$userId = $myauth->single_query ( $sql );
			$sql = "SELECT id, name, link, icon FROM admin_menu";
			if ($userId > - 1) {
				$sql .= " WHERE id in ($userId)";
			}
			$myauth->query ( $sql );
			
			$result = array ();
			
			if ($myauth->num_rows () > 0) {
				while ( $myauth->movenext () ) {
					
					$row = array ();
					
					$row ['id'] = urldecode ( $myauth->getfield ( 'id' ) );
					$row ['name'] = urldecode ( $myauth->getfield ( 'name' ) );
					$row ['link'] = urldecode ( $myauth->getfield ( 'link' ) );
					$row ['icon'] = urldecode ( $myauth->getfield ( 'icon' ) );
					
					$result [] = $row;
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