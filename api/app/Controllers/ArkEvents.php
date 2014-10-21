<?php
// this controller use the following endpoint:
// http://site.com/api/News <--- index route
// http://site.com/api/News/item <--- get item details

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

			class Controller_ArkEvents__index extends myRestController
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
					$myauth = new mysql();
              		$mysql = new mysql();	
		                
			     	// set the default response text and status
			     	$response = 'You need to authenticate!';
			     	$status = 401; // set the response to unauthorized
				 	
				 	
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
					     	
					     	
									
					     						     	
																		     	
										if($data['campus']=='0' or $data['campus']==''){
											$sql ="SELECT Id,StartTime,EndTime,Subject,Description,image_url, EXTRACT(DAY FROM StartTime) AS OrderDay,register FROM jqcalendar WHERE StartTime >= CURDATE() and user_id ='".$data['COMPANY_ID']."' order by StartTime";
											
											}else{
											$sql ="SELECT jqcalendar.Id,StartTime,EndTime,Subject,Description,image_url, EXTRACT(DAY FROM StartTime) AS OrderDay ,register FROM
											jqcalendar ,latest_news_link			 
											WHERE StartTime >= CURDATE() 
											AND user_id ='".$data['COMPANY_ID']."' AND
											latest_news_link.church_id = ".$data['COMPANY_ID']." AND 
											latest_news_link.campus_id = ".$data['campus']." AND
											jqcalendar.Id = latest_news_link.events_id 
											order by StartTime
											";
											}
									
									$DataSet = array();
							
							 		$myauth->query($sql);
									if ($myauth->num_rows() > 0) {				
											while ($myauth->movenext()) {
												
												$StartTime = strtotime( $myauth->getfield("StartTime"));
												$EndTime = strtotime( $myauth->getfield("EndTime"));
												
											
								            	$msg = substr(strip_tags(urldecode($myauth->getfield("Description"))), 0,250);
												
												if($myauth->getfield("image_url")==""){
													$THUMB= IMAGEURL."timthumb/timthumb.php?src=http://admin.wekonnect.com/dashboard/no_image_large.png";	 
												}else{
													$THUMB= IMAGEURL."timthumb/timthumb.php?src=http://admin.wekonnect.com/".$myauth->getfield("image_url"); 
												}
												$a = array();
												$a['Id'] = $myauth->getfield("Id");
												$a['Location'] = $myauth->getfield("Location");
												$a['StartTime'] = $StartTime;
												$a['EndTime'] = $EndTime;
												$a['Tickets'] = $myauth->getfield("register");
												$a['Subject'] = htmlentities(strip_tags(urldecode ($myauth->getfield("Subject"))));
												$a['Description'] = htmlentities(strip_tags($msg));
												$a['thumb'] = htmlentities($THUMB); 
												$a['Map'] = "http://maps.googleapis.com/maps/api/staticmap?40.714728,-73.998672&zoom=12&size=600x300&markers=color:blue%7Clabel:S%7C11211"; 
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
			
			
			class Controller_ArkEvents__item extends myRestController
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
			
		            $myauth = new mysql();
              		$mysql = new mysql();	   
			     	// set the default response text and status
			     	$response = 'You need to authenticate!';
			     	$status = 401; // set the response to unauthorized
				 		
				 	
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
									
									
					     			$sql="SELECT * FROM jqcalendar WHERE Id ='".$data["EventItem"]."' ";
									$myauth->query($sql);
									$myauth->movenext(); 
										
									$mysql->query("select count(id) as booking_number from event_bookings where event_id ='".$data["EventItem"]."'");
									$mysql->movenext(); 
							
									
									$StartTime = strtotime( $myauth->getfield("StartTime"));
									$EndTime = strtotime( $myauth->getfield("EndTime"));
								
									$DataSet = array();
							
							 		$myauth->query($sql);
									if ($myauth->num_rows() > 0) {				
											while ($myauth->movenext()) {
												
												$datetime = strtotime($myauth->getfield("StartTime"));
												$mysqldate = date("d/m/Y  H:i ", $datetime);
											
								            	$msg = strip_tags(urldecode($myauth->getfield("Description")));
												
												if($myauth->getfield("image_url")==""){
													$THUMB= IMAGEURL."timthumb/timthumb.php?src=http://admin.wekonnect.com/dashboard/no_image_large.png";	 
												}else{
													$THUMB= IMAGEURL."timthumb/timthumb.php?src=http://admin.wekonnect.com/".$myauth->getfield("image_url"); 
												}
												$a = array();
												$a['Id'] = $myauth->getfield("Id");
												$a['Location'] = $myauth->getfield("Location");
												$a['StartTime'] = $StartTime;
												$a['EndTime'] = $EndTime;
												$a['Tickets'] = $myauth->getfield("register");
												$a['Subject'] = htmlentities(strip_tags(urldecode ($myauth->getfield("Subject"))));
												$a['Description'] = htmlentities($msg);
												$a['thumb'] = htmlentities($THUMB); 
												$a['Map'] = "http://maps.googleapis.com/maps/api/staticmap?40.714728,-73.998672&zoom=12&size=600x300&markers=color:blue%7Clabel:S%7C11211"; 

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
			
			
			
			
			
			
		