<?php
// this controller use the following endpoint:
// http://site.com/api/Giving <--- index route
// http://site.com/api/Giving/item <--- get item details

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
class Controller_Giving__index extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->cache ['ttl'] = 3600;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
	}
	
	// **********************************************************************************
	// GET Giving DATA
	// **********************************************************************************
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
			
			$sql = "SELECT * FROM tithes_cat WHERE church_id=$churchId";
			$myauth->query ( $sql );
			
			$result = array ();
			
			if ($myauth->num_rows () > 0) {
				while ( $myauth->movenext () ) {
					$arr = array ();
					
					array_push ( $arr, urldecode ( $myauth->getfield ( "id" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "display_order" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "title" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "status" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "tax_deduct" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "description" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "id" ) ) );
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
	// PUT Giving Orders
	// **********************************************************************************
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
				$sql = "UPDATE tithes_cat SET display_order = $v[1] WHERE id = $v[0];";
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
class Controller_Giving__item extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	// **********************************************************************************
	// Add or update Giving Item DATA
	// **********************************************************************************
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
			} elseif (! isset ( $data ['campus'] ) || ! $data ['campus']) {
				$info ['status'] = '0';
				$info ['msg'] = 'campus not null';
			} else {
				// get files
				$id = $data ['id'];
				$churchId = $data ['churchId'];
				$title = $data ['title'];
				$description = $data ['description'];
				$campus = $data ['campus'];
				$display = $data ['display'];
				$tax = $data ['tax'];
				$eway = $data ['eway'];
				
				// check unsafe fields
				if (! get_magic_quotes_gpc ()) {
					$title = addslashes ( $title );
					$description = addslashes ( $description );
				}
				
				$myauth = new mysql ();
				
				// code sql
				if ($id != "") {
					$sql = "update tithes_cat set title='$title' ,description='$description' ,status='$display' ,tax_deduct='$tax' ,eway_account=$eway
							where id = $id";
					$result = $myauth->query ( $sql );
				} else {
					$getId = "SELECT MAX(display_order) FROM tithes_cat WHERE church_id = $churchId";
					$res = $myauth->single_query ( $getId );
					if ($res) {
						$orderId = $res + 1;
					} else {
						$orderId = 1;
					}
					$sql = "insert into tithes_cat
								(church_id,title,description,status,tax_deduct,eway_account,display_order)
							values($churchId,'$title','$description','$display','$tax','$eway',$orderId)";
					$result = $myauth->query ( $sql );
					$id = $myauth->lastinsert ();
				}
				
				if (! $result) {
					$info ['status'] = '0';
					if ($myauth->num_rows () == 0) {
						$info ['msg'] = 'Data has not changed';
					} else {
						$info ['msg'] = 'Save failed';
					}
				} else {
					$campusArr = explode ( ',', $campus );
					if (isset ( $campusArr ) && $campusArr) {
						$myauth->query ( "delete from latest_news_link where church_id = $churchId and tithes_id=$id" );
						
						for($i = 0; $i < count ( $campusArr ); $i ++) {
							if ($result == 1) {
								$sql = "insert into latest_news_link (church_id,campus_id,tithes_id) values ($churchId,$campusArr[$i],$id)";
								$myauth->query ( $sql );
							}
						}
					}
					
					if ($result) {
						$info ['status'] = '1';
						$info ['msg'] = 'success';
					} else {
						$info ['status'] = '0';
						if ($myauth->num_rows () == 0) {
							$info ['msg'] = 'Data has not changed';
						} else {
							$info ['msg'] = 'Save failed';
						}
					}
				}
			}
			$return ['response'] = $info;
		}
		
		// set the response status and text
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	// **********************************************************************************
	// GET Giving Item DATA
	// **********************************************************************************
	function get() {
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
			
			$sql = "select * from tithes_cat where id=$id";
			$myauth->query ( $sql );
			$rows = $myauth->getAll ();
			
			$sql = "SELECT C.id,C.title as text FROM latest_news_link L INNER JOIN campus C ON L.campus_id = C.id where C.church_id=" . $rows [0] ['church_id'] . " and tithes_id=" . $rows [0] ['id'];
			$myauth->query ( $sql );
			$rowsCampus = $myauth->getAll ();
			$return ['response'] = array (
					$rows [0],
					$rowsCampus 
			);
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