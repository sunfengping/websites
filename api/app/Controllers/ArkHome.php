<?php
// this controller use the following endpoint:
// http://site.com/api/ArkHome <--- index route

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

			class Controller_ArkHome__index extends myRestController
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
					
					$mySettings = new mysql();
	
					$mySettings->query("SELECT * from  settings where church_id ='".$data['COMPANY_ID']."'");
					while ($mySettings->movenext()) { 
						$home_screen_num_events =$mySettings->getfield('home_screen_num_events');	
						$home_screen_num_news =$mySettings->getfield('home_screen_num_news');		
					}
					$home_screen_num_news = ($home_screen_num_news == '' ? '1' : $home_screen_num_news);
					$home_screen_num_events = ($home_screen_num_events == '' ? '1' : $home_screen_num_events);
					
				 


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
												$sql ="SELECT * FROM latest_news 
												 WHERE church_id ='".$data['COMPANY_ID']."' 
												 AND status ='true' 
												 AND schedule_time_expiry > ".time()."
												  or 
												church_id ='".$data['COMPANY_ID']."' 
												 AND status ='true' 
												 AND schedule_time_expiry =0 
												 order by display_order,id limit 2";
			 
										
										}else{
											  $sql ="select latest_news.id,latest_news.sent_date,latest_news.image_url,latest_news.title,latest_news.msg  FROM
												 latest_news ,latest_news_link			 
												 WHERE latest_news_link.church_id = ".$data['CHURCH_ID']." AND 
												 latest_news_link.campus_id = ".$data['campus']." AND
												 latest_news.id = latest_news_link.latest_news_id AND
												 latest_news.status  ='true'
												 AND latest_news.schedule_time_expiry > ".time()."
												 Or
												 latest_news_link.church_id = ".$data['COMPANY_ID']." AND 
												 latest_news_link.campus_id = ".$data['campus']." AND
												 latest_news.id = latest_news_link.latest_news_id AND
												 latest_news.status  ='true'
												 and latest_news.schedule_time_expiry =0 
												order by display_order,id limit 2
												 ";

										}
								
									$DataSet = array();
									
									$myauth->query($sql);
									if ($myauth->num_rows() > 0) {				
											while ($myauth->movenext()) {
												
												$datetime = strtotime($myauth->getfield("sent_date"));
												$mysqldate = date("d/m/Y  H:i ", $datetime);
											
												$msg = substr(strip_tags(urldecode($myauth->getfield("msg"))), 0,300);
												
												if($myauth->getfield("image_url")==""){
													$THUMB= IMAGEURL."timthumb/timthumb.php?src=http://admin.wekonnect.com/dashboard/no_image_large.png";	 
												}else{
													$THUMB= IMAGEURL."timthumb/timthumb.php?src=http://admin.wekonnect.com".$myauth->getfield("image_url"); 
												}
												$a = array();
												$a['Type']="News";
												$a['Featured'] = (bool)rand(0,1);
												$a['id'] = $myauth->getfield("id");
											
												$a['title'] = htmlentities(substr(strip_tags(urldecode ($myauth->getfield("title"))), 0,300));
												$a['datetimes'] = $datetime;
												$a['thumb'] = $THUMB; 
												//$a['link'] = "?Actions=NewsItem&id=".$myauth->getfield("id")."&COMPANY_ID=".$data["COMPANY_ID"].""; 
												
										
												if($data["html"]!="true"){
													
													//$a['msg'] = htmlentities(strip_tags(urldecode ($myauth->getfield("msg"))));
												}else{
													//$a['msg'] =urldecode($myauth->getfield("msg"));
												}
											
												//$a['Subject'] = htmlentities(substr(strip_tags(urldecode ($myauth->getfield("title"))), 0,300));
												//$a['Description'] = htmlentities(wordwrap($msg, 300, "<br>\n", true));
												
												array_push($DataSet, $a);
												
											}
												$response =$DataSet;
											
										}else{
											
											    	//$response = 'Sorry no data';
													//$status = 204; // set the response to unauthorized
											
										}	
										
										
										
										
								if($data['campus']=='0' or $data['campus']==''){
										$sql ="SELECT Id,StartTime,EndTime,register,display,Subject,Description,image_url, EXTRACT(DAY FROM StartTime) AS OrderDay FROM jqcalendar WHERE StartTime >= CURDATE() and user_id ='".$data['COMPANY_ID']."'   order by StartTime LIMIT $home_screen_num_events";
											
										}else{
											$sql ="SELECT jqcalendar.Id,jqcalendar.register,StartTime,EndTime,display,Subject,Description,image_url, EXTRACT(DAY FROM StartTime) AS OrderDay  FROM
											 jqcalendar ,latest_news_link			 
											 WHERE StartTime >= CURDATE() 
											 AND user_id ='".$data['COMPANY_ID']."' AND
											  latest_news_link.church_id = ".$data['COMPANY_ID']." AND 
											 latest_news_link.campus_id = ".$data['campus']." AND
											 jqcalendar.Id = latest_news_link.events_id 
											 order by StartTime LIMIT $home_screen_num_events
											 ";
										}
									
										$DataSet2 = array();
										
										$myauth->query($sql);
										if ($myauth->num_rows() > 0) {				
												while ($myauth->movenext()) {
													
													$datetime = strtotime($myauth->getfield("sent_date"));
													$mysqldate = date("d/m/Y  H:i ", $datetime);
								
													
													if($myauth->getfield("image_url")==""){
														$THUMB= IMAGEURL."timthumb/timthumb.php?src=http://admin.wekonnect.com/dashboard/no_image_large.png";	 
													}else{
														$THUMB= IMAGEURL."timthumb/timthumb.php?src=http://admin.wekonnect.com".$myauth->getfield("image_url"); 
													}
													
													
													
													 $ticket_image = ($myauth->getfield("register") == 1 ? 'images/Tickets.png' : 'http://wekonnect.com/app/images/NoTickets.png'); //harsh!
		 			

													
													$a = array();
													$a['Type']="Event";
													$a['Featured'] = (bool)rand(0,1);
													$a['id'] = $myauth->getfield("Id");
													$a['StartTime'] = $myauth->getfield("StartTime");
													$a['EndTime'] = $myauth->getfield("EndTime");
													$a['register'] = $myauth->getfield("register");
													$a['tickets'] = $ticket_image;
													//$a['display'] = $myauth->getfield("display");
													$a['thumb'] = htmlentities($THUMB); 
													$a['event'] = "true"; 
													$a['title'] = htmlentities(substr(strip_tags(urldecode ($myauth->getfield("Subject"))), 0,300));
													//$a['Location'] = htmlentities(substr(strip_tags(urldecode ($myauth->getfield("Location"))), 0,300));
													//$a['Description'] = htmlentities(substr(strip_tags(urldecode ($myauth->getfield("Description"))), 0,300));
													 $a['Map'] = "http://maps.googleapis.com/maps/api/staticmap?40.714728,-73.998672&zoom=12&size=600x300&markers=color:blue%7Clabel:S%7C11211"; 

													
													array_push($DataSet2, $a);
													
												}
									
											}
								
								
								
											        $a = array();
													$a['Type']="Contact";
													$a['thumb'] = 'https://dashboard.wekonnect.io/Media/587/HomeContact-600x600'; 
																										
													array_push($DataSet2, $a);
													
													
													$give = array();
													$give['Type']="Give";
													$give['thumb'] = 'https://dashboard.wekonnect.io/Media/587/HomeGive-600x600'; 
																										
													array_push($DataSet2, $give);
													
													
													$notifications = array();
													$notifications['Type']="Notifications";
													$notifications['thumb'] = 'https://dashboard.wekonnect.io/Media/587/HomeNotifications-600x600'; 
																										
													array_push($DataSet2, $notifications);
													
													
													$Update = array();
													$Update['Type']="Update";
													$Update['value'] = 0; //0 false 1 yes update
																										
													array_push($DataSet2, $Update);
													
													
															
									$response = array_merge($DataSet, $DataSet2);
									
													
					     	
					     			/*******************************************************************************************/    
					     	
					     	
					     	
			     		}
			     	}


			     	// set the response status and text
			     	$this->responseStatus = $status;
			        $this->response = $response;
			     }
			}

			
			

			
			
			
			
		