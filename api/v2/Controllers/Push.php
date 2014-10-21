<?php
// this controller use the following endpoint:
// http://site.com/api/Push <--- index route
// http://site.com/api/Push/item <--- get item details

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
class Controller_Push__index extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->cache ['ttl'] = 3600;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
	}
	// **********************************************************************************
	// SELECT DATA / select
	// **********************************************************************************
	public function get() {
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$myauth = new mysql ();
			$sql = "SELECT id,display_order,title,message,time_created,sent_time,status FROM wk_push WHERE company_id = '" . $data ["churchId"] . "' ORDER BY display_order asc";
			$myauth->query ( $sql );
			$result = array ();
			
			if ($myauth->num_rows () > 0) {
				while ( $myauth->movenext () ) {
					$row = array ();
					
					array_push ( $row, urldecode ( $myauth->getfield ( 'id' ) ) );
					array_push ( $row, $myauth->getfield ( 'display_order' ) );
					array_push ( $row, urldecode ( $myauth->getfield ( 'title' ) ) );
					array_push ( $row, date ( "d F Y", $myauth->getfield ( 'time_created' ) ) );
					array_push ( $row, date ( "d F Y", $myauth->getfield ( 'sent_time' ) ) );
					array_push ( $row, urldecode ( $myauth->getfield ( 'id' ) ) );
					array_push ( $row, urldecode ( $myauth->getfield ( 'status' ) ) );
					
					$result [] = $row;
				}
			}
			$return ['response'] = $result;
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
			if (! isset ( $data ['title'] ) || ! $data ['title']) {
				$info ['status'] = '0';
				$info ['title'] = 'title not null';
			} elseif (! isset ( $data ['message'] ) || ! $data ['message']) {
				$info ['status'] = '0';
				$info ['msg'] = 'message not null';
			} else {
				// get files
				$id = $data ['id'];
				$title = $data ['title'];
				$churchId = $data ['churchId'];
				$message = $data ['message'];
				$link = $data ['link'];
				$device = $data ['device'];
				$badge = $data ['badge'];
				
				// check unsafe fields
				if (! get_magic_quotes_gpc ()) {
					$title = addslashes ( $title );
					$message = addslashes ( $message );
				}
				
				$myauth = new mysql ();
				
				// code sql
				if ($data ['id'] != "") {
					$sql = "update wk_push set 
								title='$title',message='$message',target='$link',device='$device',badge='$badge'
							where id=$id";
				} else {
					$sql = "insert into wk_push
								(title,company_id,message,target,device
								,badge,time_created)
							values
								('$title',$churchId,'$message','$target','$device'
								,'$badge',now())";
				}
				$results = $myauth->query ( $sql );
				
				if ($results == '1') {
					$info ['status'] = '1';
					$info ['id'] = $id;
					$info ['msg'] = 'success';
				} else {
					$info ['status'] = '0';
					if (mysql_affected_rows () == 0) {
						$info ['msg'] = 'Data has not changed';
					} else {
						$info ['msg'] = 'Save faild';
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
	// DELETE DATA / deleteItem
	// **********************************************************************************
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
			
			$sql = "delete from wk_push where id = $id";
			$result = $myauth->query ( $sql );
			
			$return ['response'] = $result == "1" ? "success" : "Delete failed";
		}
		
		// set the response status and text
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	// **********************************************************************************
	// Updata Orders / updataOrders
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
				$sql = "UPDATE wk_push SET display_order = $v[1] WHERE id = $v[0];";
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
class Controller_Push__item extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	// **********************************************************************************
	// selectItem
	// **********************************************************************************
	public function get() {
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$myauth = new mysql ();
			
			$id = $data ['id'];
			$churchId = $data ['churchId'];
			// check unsafe fields
			if (! get_magic_quotes_gpc ()) {
				$id = addslashes ( $id );
			}
			
			$sql = "select * from wk_push where id = $id";
			$myauth->query ( $sql );
			$row = $myauth->getAll ();
			$target_selection = array ();
			
			while ( list ( $key ) = each ( $row ) ) {
				$row [$key] ["title"] = rawurldecode ( $row [$key] ["title"] );
				$row [$key] ["message"] = rawurldecode ( $row [$key] ["message"] );
				$row [$key] ["time_created"] = date("d F Y",$row [$key] ["time_created"]);
				$row [$key] ["sent_time"] = date("d F Y",$row [$key] ["sent_time"]);
				
				
				if (! strstr ( $row [$key] ["target"], "blank" ) && ! $row [$key] ["target"] == "") {
					if (strstr ( $row [$key] ["target"], "N-" )) {
						$news_id = substr ( $row [$key] ["target"], 2, strlen ( $row [$key] ["target"] ) - 1 );
						$sql = "select id, title from latest_news where id=" . $news_id;
					}
					if (strstr ( $row [$key] ["target"], "E-" )) {
						$event_id = substr ( $row [$key] ["target"], 2, strlen ( $row [$key] ["target"] ) - 1 );
						$sql = "select Id as id, Subject as title from jqcalendar where Id=" . $event_id;
					}
					$myauth->query ( $sql );
					while ( $myauth->movenext () ) {
						
						array_push ( $target_selection, array (
								$myauth->getfield ( "id" ),
								urldecode ( $myauth->getfield ( "title" ) ) 
						) );
					}
				} else {
					array_push ( $target_selection, array (
							0,
							"blank" 
					) );
				}
			}
			$return ['response'] = array (
					$row,
					$target_selection 
			);
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
class Controller_Push__link extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	// **********************************************************************************
	// select2
	// **********************************************************************************
	public function get() {
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$dataArray = array ();
			$row_array = array ();
			$myauth = new mysql ();
			$sql = "SELECT id,title FROM latest_news WHERE church_id = '" . $data ["churchId"] . "' and status='true'";
			$myauth->query ( $sql );
			$i = 0;
			if ($myauth->num_rows () > 0) {
				while ( $myauth->movenext () ) {
					
					$row_array ['id'] = rawurldecode ( $myauth->getfield ( 'id' ) );
					$row_array ['title'] = rawurldecode ( $myauth->getfield ( 'title' ) );
					array_push ( $dataArray, $row_array );
					$i ++;
				}
			}
			$sql = "SELECT Id,Subject FROM jqcalendar WHERE church_id = '" . $data ["churchId"] . "' and display='yes'";
			$myauth->query ( $sql );
			$i = 0;
			if ($myauth->num_rows () > 0) {
				while ( $myauth->movenext () ) {
					
					$row_array ['id'] = rawurldecode ( $myauth->getfield ( 'Id' ) );
					$row_array ['title'] = rawurldecode ( $myauth->getfield ( 'Subject' ) );
					array_push ( $dataArray, $row_array );
					$i ++;
				}
			}
			$return ['response'] = $dataArray;
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
class Controller_Push__send extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	// **********************************************************************************
	// SEND
	// **********************************************************************************
	public function get() {
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$mysql = new mysql();
   			$update = new mysql();
			
   			$sql = "SELECT * FROM wk_push where id=".$data["id"];
  			$mysql->query($sql);
			if ($mysql->num_rows() > 0) 
			{    
    			while ($mysql->movenext()) 
				{
     				$mypush= new mysql();
	 				$sql = "SELECT * from  settings where church_id ='".$data["churchId"]."'";
    				$mypush->query($sql); 
     				$mypush->movenext();
					
      				$APPLICATION_ID = $mypush->getField("push_app_id");
			  		$REST_API_KEY = $mypush->getField("push_app_api_key");
     				
					$url = 'https://api.parse.com/1/push';
					
					$data = array(
						  'channel' => '',
						  'data' => array(
								   'alert' => urldecode($mysql->getField("message")),
								   'd' => $mysql->getField("target"),
								   'sound' => 'Notification.wav',
						  ),
					);
				  if($mysql->getField("badge")=='checked')
				  {
				 	 $data["data"]["badge"] = "1";
     			  }
    			 $_data = json_encode($data);
				 
				 $headers = array(
					  'X-Parse-Application-Id: ' . $APPLICATION_ID ,
					  'X-Parse-REST-API-Key: ' . $REST_API_KEY,
					  'Content-Type: application/json',
					  'Content-Length: ' . strlen($_data),
				 );
 
				$curl = curl_init($url);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $_data);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      			$response = curl_exec($curl);
      			$response =json_decode($response);

				if(isset($response->{'result'}))
				{
					  if($response->{'result'}==1){
						  
						$sql = "UPDATE wk_push set sent_time =".time()." , status ='Sent' WHERE id='".$_POST['id']."'"; 
						$info['msg']=$update->query($sql);
						$info['msg'].= "<br>Your Push Messages have been sent!";
					 } else {
							 $info['msg']="<br>There was an error in sending your message!".var_dump($response);
							}
				}
				else
				{
				  $info['msg']="There was an error in sending your message!";
				}
				
				 $info['status']='1';
    
    			}
   			}
			
			$return ['response'] = $info;
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
			
			
			
			
			
		