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

			class Controller_ArkNews__index extends myRestController
			{

			     // we dont want to cache nothing
				 public function settings(){
				 	$this->cache['enabled'] = false;
              		$this->cache['ttl'] = 3600;
              		$this->logs['enabled'] = false;
              		$this->logs['detailed'] = false;
              	
				 }
				 
				 
			     public function post()
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
					     	
					     	
									
					     						     	
											     	
										if($data['campus']=='0' or $data['campus']==''){
											  $sql ="SELECT * FROM latest_news 
											 WHERE church_id ='".$data["COMPANY_ID"]."' 
											 and status ='true' 
											 and schedule_time_expiry > ".time()."
											 or
											 church_id ='".$data["COMPANY_ID"]."' 
											 and status ='true' 
											 and schedule_time_expiry =0 
											 order by display_order limit ".$start_row .",".$limit_row."";
										
										}else{
											  $sql ="select latest_news.id,latest_news.sent_date,latest_news.image_url,latest_news.title,latest_news.msg  FROM
											 latest_news ,latest_news_link			 
											 WHERE latest_news_link.church_id = ".$data["COMPANY_ID"]." AND 
											 latest_news_link.campus_id = ".$data['campus']." AND
											 latest_news.id = latest_news_link.latest_news_id AND
											 latest_news.status  ='true'
											 AND latest_news.schedule_time_expiry > ".time()."
								
											 Or
								
											latest_news_link.church_id = ".$data["COMPANY_ID"]." AND 
											 latest_news_link.campus_id = ".$data['campus']." AND
											 latest_news.id = latest_news_link.latest_news_id AND
											 latest_news.status  ='true'
											  and latest_news.schedule_time_expiry =0 
											 order by latest_news.display_order limit ".$start_row .",".$limit_row."
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
												$a['id'] = $myauth->getfield("id");
												$a['datetimes'] = $datetime;
												$a['mysqldates'] = $mysqldate;
												$a['msg'] = htmlentities($msg);
												$a['thumb'] = htmlentities($THUMB); 
												$a['link'] = "?Actions=NewsItem&id=".$myauth->getfield("id")."&COMPANY_ID=".$data["COMPANY_ID"].""; 
												
										
												if($data["html"]!="true"){
													
													$a['msg'] = htmlentities(strip_tags(urldecode ($myauth->getfield("msg"))));
												}else{
													$a['msg'] =urldecode($myauth->getfield("msg"));
												}
												
									
													$info = pathinfo($myauth->getfield("file_url"));
													
													 $a['file'] = urldecode($myauth->getfield("file_url"));
													 $a['file_type'] = $info['extension'];
										
													 
			
											
												$a['title'] = htmlentities(substr(strip_tags(urldecode ($myauth->getfield("title"))), 0,300));
												$a['blurb'] = htmlentities(wordwrap($msg, 300, "<br>\n", true));
												
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
					     	
					     	
									
					     						     	
											     	
										if($data['campus']=='0' or $data['campus']==''){
											  $sql ="SELECT * FROM latest_news 
											 WHERE church_id ='".$data["COMPANY_ID"]."' 
											 and status ='true' 
											 and schedule_time_expiry > ".time()."
											 or
											 church_id ='".$data["COMPANY_ID"]."' 
											 and status ='true' 
											 and schedule_time_expiry =0 
											 order by display_order limit ".$start_row .",".$limit_row."";
										
										}else{
											  $sql ="select latest_news.id,latest_news.sent_date,latest_news.image_url,latest_news.title,latest_news.msg  FROM
											 latest_news ,latest_news_link			 
											 WHERE latest_news_link.church_id = ".$data["COMPANY_ID"]." AND 
											 latest_news_link.campus_id = ".$data['campus']." AND
											 latest_news.id = latest_news_link.latest_news_id AND
											 latest_news.status  ='true'
											 AND latest_news.schedule_time_expiry > ".time()."
								
											 Or
								
											latest_news_link.church_id = ".$data["COMPANY_ID"]." AND 
											 latest_news_link.campus_id = ".$data['campus']." AND
											 latest_news.id = latest_news_link.latest_news_id AND
											 latest_news.status  ='true'
											  and latest_news.schedule_time_expiry =0 
											 order by latest_news.display_order limit ".$start_row .",".$limit_row."
											 ";
										}
								
									$DataSet = array();
									$x=0;
									$myauth->query($sql);
									if ($myauth->num_rows() > 0) {				
											while ($myauth->movenext()) {
											$x++;
												
												$datetime = strtotime($myauth->getfield("sent_date"));
												$mysqldate = date("d/m/Y  H:i ", $datetime);
											
												$msg = strip_tags(urldecode($myauth->getfield("msg")));
												
												if($myauth->getfield("image_url")==""){
													$THUMB= IMAGEURL."timthumb/timthumb.php?src=http://admin.wekonnect.com/dashboard/no_image_large.png";	 
												}else{
													$THUMB= IMAGEURL."timthumb/timthumb.php?src=http://admin.wekonnect.com".$myauth->getfield("image_url")."&h=174"; 
												}
												$a = array();
												$a['id'] = $myauth->getfield("id");
												$a['datetimes'] = $datetime;
												
												if($x==2){
												$a['featured'] = "true";	
												}else{
												$a['featured'] = "false";	
												}
												
												$a['mysqldates'] = $mysqldate;
												$a['msg'] = htmlentities($msg);
												$a['thumb'] = $THUMB; 
												$a['link'] = "?Actions=NewsItem&id=".$myauth->getfield("id")."&COMPANY_ID=".$data["COMPANY_ID"].""; 
												
										
												if($data["html"]!="true"){
													
													$a['msg'] = htmlentities(strip_tags(urldecode ($myauth->getfield("msg"))));
												}else{
													$a['msg'] =urldecode($myauth->getfield("msg"));
												}
												
									
													$info = pathinfo($myauth->getfield("file_url"));
													
													 $a['file'] = urldecode($myauth->getfield("file_url"));
													 $a['file_type'] = $info['extension'];
										
													 
			
											
												$a['title'] = htmlentities(substr(strip_tags(urldecode ($myauth->getfield("title"))), 0,300));
												$a['blurb'] = htmlentities(wordwrap($msg, 300, "<br>\n", true));
												
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
			
			
			class Controller_ArkNews__item extends myRestController
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
									
									
					     						     	
										$sql="SELECT * FROM latest_news WHERE id ='".$data["NewsItem"]."' ";
			
								
											$DataSet = array();
									
											 $myauth->query($sql);
													if ($myauth->num_rows() > 0) {				
															while ($myauth->movenext()) {
																
																$datetime = strtotime($myauth->getfield("sent_date"));
																$mysqldate = date("d/m/Y  H:i ", $datetime);
																$msg = strip_tags(urldecode($myauth->getfield("msg")));
															
																if($myauth->getfield("image_url")==""){
																	$THUMB= IMAGEURL."timthumb/timthumb.php?src=http://admin.wekonnect.com/dashboard/no_image_large.png";	 
																}else{
																	$THUMB= IMAGEURL."timthumb/timthumb.php?src=http://admin.wekonnect.com/".$myauth->getfield("image_url"); 
																}
															    $a = array();
																$a['datetimes'] = $datetime;
																$a['mysqldates'] = $mysqldate;
																$a['msg'] = htmlentities($msg);
																$a['thumb'] = htmlentities($THUMB); 
																$a['title'] = htmlentities(strip_tags(urldecode ($myauth->getfield("title"))));
																$a['file_url'] = htmlentities($myauth->getfield("image_url"));
																
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
			
			
			
			
			
			
		