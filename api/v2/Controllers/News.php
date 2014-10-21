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
class Controller_News__index extends myRestController {
	
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
			 $currentPage="";
			if(isset($data["page"]))
			{
			 $currentPage=$data["page"];
			}
			if(isset($data["canshu"]))
			{
			 $canshu=$data["canshu"];
			}
			
			 
			
			$sql = "SELECT * FROM latest_news WHERE church_id = $churchId ORDER BY display_order";
		    $myauth->query ( $sql );
			
			$count= $myauth->num_rows();
			
			$page=new page_link($count,6,'page',$canshu);
			$sql.=" limit $page->firstcount,$page->displaypg";
			$myauth->query ($sql);
			
		    $result = array ();
			
		    if ($myauth->num_rows () > 0) {
				while ( $myauth->movenext () ) {
					$arr = array ();
					
					$arr["id"]=urldecode ( $myauth->getfield ( "id" ) ) ;
					$arr["title"]=urldecode ( $myauth->getfield ( "title" ) ) ;
					$arr["msg"]=urldecode ( $myauth->getfield ( "msg" ) ) ;
					$arr["image_url"]=urldecode ( $myauth->getfield ( "image_url" ) ) ;
					
					$result [] = $arr;
				}
			}
		
			
			
			$return ['response'] = array($result,$page->show_link($canshu,$currentPage));
			
			
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