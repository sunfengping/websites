<?php
// this controller use the following endpoint:
// http://site.com/api/ArkClients <--- index route
// http://site.com/api/ArkClients/item <--- get item details

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

			class Controller_ArkCampus__index extends myRestController
			{

			     // we dont want to cache nothing
				 public function settings(){
				 	$this->cache['enabled'] = false;
              		$this->cache['ttl'] = 3600;
              		$this->logs['enabled'] = false;
              		$this->logs['detailed'] = false;
              	
				 }
				 
				 
			     public function get()
			     {
			
		                
			     	// set the default response text and status
			     	$response = 'You need to authenticate!';
			     	$status = 401; // set the response to unauthorized
				 	$myauth = new mysql();	
				 	
					// grab params from query string  [this will read all the params from query string]
					$data = $this->request['params']['string'];
					$start_row = ($data["start_row"] == '' ? 0 : $data["start_row"]);
					$limit_row = ($data["limit"] == '' ? LIMIT_RESLTS : $data["limit"]);				 


			     	$headers = $this->request['headers'];
			     	$checkHeader = isset($data['API-KEY']) ? true : false;

			     	// here you can authorize by calling database or something where your tokens are stored
			     	if($checkHeader){
			     	
			     		$token=$myauth->single_query("SELECT token FROM church WHERE id =".$data["COMPANY_ID"]."");
			     		$userAuthorized = $data['API-KEY'] == $token ? true : false;

			     		// user is authorized change the response and status code
			     		if($userAuthorized){
					     	$status = 200; // set the response to OK
					     	$this->responseHeaders[] = 'Welcome user, you\'re authorized to use this API';
					     	
					     	
					     	
					     	/*******************************************************************************************/
									
							 $sql ="SELECT * FROM campus  WHERE church_id ='".$data["COMPANY_ID"]."' ";
								
									$DataSet = array();
									
									$myauth->query($sql);
									if ($myauth->num_rows() > 0) {				
											while ($myauth->movenext()) {
												
												$datetime = strtotime($myauth->getfield("sent_date"));
												$mysqldate = date("d/m/Y  H:i ", $datetime);
										
											
												$a = array();
												$a['id'] = $myauth->getfield("id");
												$a['title'] = $myauth->getfield("title");
												$a['street_address'] = $myauth->getfield("street_address");
												$a['postcode'] = $myauth->getfield("postcode");
												$a['state'] = $myauth->getfield("state");
												$a['phone'] = $myauth->getfield("phone");
												$a['status'] = $myauth->getfield("status");
												$a['lat'] = $myauth->getfield("lat");
												$a['lng'] = $myauth->getfield("lng");
												$a['general'] = $myauth->getfield("general");
												$a['campus_email'] = $myauth->getfield("campus_email");
												$a['fee'] = $myauth->getfield("fee");
												 
												array_push($DataSet, $a);
												
											}
												$response =$DataSet;
											
										}else{
											
											    	$response = 'Sorry no data';
													$status = 204; // set the response to unauthorized
											
										}		
					     	
					     			/*******************************************************************************************/    
					     	
			     		}
			     	}


			     	// set the response status and text
			     	$this->responseStatus = $status;
			        $this->response = $response;
			     }
			     
		
			}
			
			
			
			
			