<?php
// this controller use the following endpoint:
// http://site.com/api/ArkNotifications <--- index route
// http://site.com/api/ArkNotifications/item <--- get item details

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
			
			
			//************************************************************************************************										
			// Get full list of products
			// http://site.com/api/Products
			//************************************************************************************************	

			class Controller_ArkNotifications__index extends myRestController
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
					     	
					     	//$data["COMPANY_ID"]=587;
					     	
					     	/*******************************************************************************************/
					     	 $sql ="SELECT * from wk_push WHERE  company_id ='".$data["COMPANY_ID"]."' AND status='Sent'  ORDER BY sent_time";
								
									$DataSet = array();
									
									$myauth->query($sql);
									if ($myauth->num_rows() > 0) {				
											while ($myauth->movenext()) {
											
											
											$sent_time = $myauth->getField("sent_time");
											$dt2 = new DateTime("@$sent_time");
											$sent_time =$dt2->format('d/m H:i');
											
											$target = explode("-",$myauth->getField("target"));
											if($target[0]=="N"){
												$link ="News";
												$target_id =$target[1];
											} else if($target[0]=="E"){
												$link ="Events";
												$target_id =$target[1];
											}
											else{
												$link ="";
												$target_id ="";
											}
				
										
												$a = array();
												$a['id'] = $myauth->getfield("id");
												$a['message'] = $myauth->getfield("message");
												$a['status'] = $myauth->getfield("status");
												$a['title'] = $myauth->getfield("title");
												$a['time_created'] = $myauth->getfield("time_created");
												$a['sent_time'] = $myauth->getfield("sent_time");
												$a['sent_time_nice'] = $sent_time;
												$a['device'] = $myauth->getfield("device");
												$a['badge'] = $myauth->getfield("badge");
												$a['target'] = $target_id;
												$a['link'] = $link;
												
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
			