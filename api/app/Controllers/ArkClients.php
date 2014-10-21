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

			class Controller_ArkClients__index extends myRestController
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
									
							 $sql ="SELECT * FROM church  WHERE status ='Paid' ";
										
										
								
									$DataSet = array();
									
									$myauth->query($sql);
									if ($myauth->num_rows() > 0) {				
											while ($myauth->movenext()) {
												
												$datetime = strtotime($myauth->getfield("sent_date"));
												$mysqldate = date("d/m/Y  H:i ", $datetime);
										
											
												$a = array();
												$a['id'] = $myauth->getfield("id");
												$a['name'] = $myauth->getfield("name");
												$a['token'] = $myauth->getfield("token");
												$a['city'] = $myauth->getfield("city");
												$a['country'] = $myauth->getfield("country");
												$a['logo'] = "https://dashboard.wekonnect.io/Media/587/SideMenu-486x475";
												$a['video'] = "https://gdata.youtube.com/feeds/api/users/iseechurch/uploads";
												$a['video2'] = "https://vimeo.com/api/v2/citylifechurch/videos.json";
												//$a['hometheme'] = rand(1,2);
												$a['hometheme'] = 2;
												
												 
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
			
			
			class Controller_ArkClients__Settings extends myRestController
			{

			     // we dont want to cache nothing
				 public function settings(){
				 	$this->cache['enabled'] = false;
              		$this->logs['enabled'] = false;
              		$this->logs['detailed'] = false;
              		$this->production = true;
				 }
				 
				 


			     public function get()
			     {
			
		                
			     	// set the default response text and status
			     	$response = 'You need to authenticate!';
			     	$status = 401; // set the response to unauthorized
				 	$myauth = new mysql();	
				 	
				 	// grab params from query string  [this will read all the params from query string]
					$data = $this->request['params']['string'];
				


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
									
							 $sql ="SELECT * FROM church  WHERE id = ".$data["COMPANY_ID"]." ";
								
									$DataSet = array();
									
									$myauth->query($sql);
									if ($myauth->num_rows() > 0) {				
											while ($myauth->movenext()) {
												
												$datetime = strtotime($myauth->getfield("sent_date"));
												$mysqldate = date("d/m/Y  H:i ", $datetime);
										
											
												$a = array();
												$a['id'] = $myauth->getfield("id");
												$a['name'] = $myauth->getfield("name");
												$a['token'] = $myauth->getfield("token");
												$a['city'] = $myauth->getfield("city");
												$a['country'] = $myauth->getfield("country");
												$a['logo'] = "https://dashboard.wekonnect.io/Media/587/SideMenu-486x475";
												$a['video'] = "https://gdata.youtube.com/feeds/api/users/iseechurch/uploads";
												$a['rss'] = "https://ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=50&q=http://www.destinyinc.org.au/feed/";
												$a['rssImage'] = "http://placehold.it/640x250";
												$a['rss2'] = "";
												$a['podcast'] = "http://www.iseechurch.com/podcast/iseeworship.xml";
												
												$Menu = array();
														$Menu[0]['Name']="My Home";
														$Menu[0]['icon'] = 'homelogo1';
														$Menu[0]['id'] = 'Home'; 
														
														
														$Menu[1]['Name']="Date & Event!";
														$Menu[1]['icon'] = 'homelogo1'; 
														$Menu[1]['id'] = 'Date';
														
														$Menu[2]['Name']="Staff";
														$Menu[2]['icon'] = 'logo2.jpg';
														$Menu[2]['id'] = 'Staff'; 
														
														$Menu[3]['Name']="Social Media";
														$Menu[3]['icon'] = 'logo2.jpg'; 
														$Menu[3]['id'] = 'Social';
																											
														$Menu[4]['Name']="Notifications";
														$Menu[4]['icon'] = 'logo2.jpg'; 
														$Menu[4]['id'] = 'Notifications';
															
														$Menu[5]['Name']="News";
														$Menu[5]['icon'] = 'logo2.jpg';
														$Menu[5]['id'] = 'News'; 
														
														$Menu[6]['Name']="Podcast";
														$Menu[6]['icon'] = 'logo2.jpg'; 
														$Menu[6]['id'] = 'News'; 
														 
														$Menu[7]['Name']="Video";
														$Menu[7]['icon'] = 'logo2.jpg';
														$Menu[7]['id'] = 'Video'; 
														
														$Menu[8]['Name']="Blog";
														$Menu[8]['icon'] = 'logo2.jpg'; 
														$Menu[8]['id'] = 'Blog'; 
														
														$Menu[9]['Name']="Contacts";
														$Menu[9]['icon'] = 'logo2.jpg';	
														$Menu[9]['id'] = 'Contacts'; 												
													
														$Menu[10]['Name']="Settings";
														$Menu[10]['icon'] = 'logo2.jpg';
														$Menu[10]['id'] = 'Settings'; 
														
														$Menu[11]['Name']="About";
														$Menu[11]['icon'] = 'logo2.jpg';
														$Menu[11]['id'] = 'About'; 

														
																																									
													$a['menu'] =$Menu;
																										
												 
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
			

			
			
			
						
			
			
			
			
		