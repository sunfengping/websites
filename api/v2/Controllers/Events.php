<?php
// this controller use the following endpoint:
// http://site.com/api/News <--- index route
// http://site.com/api/News/item <--- get item details

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
class Controller_Events__index extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->cache ['ttl'] = 3600;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
	}
	// **********************************************************************************
	// SELECT Event DATA / getEventsList
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
			
			$result = array ();
			
			$myauth->query ( "SELECT
						Id,display_order, Subject AS Title,
						CASE Location WHEN '' THEN '&nbsp;' ELSE Location END AS Location,
						startTime, endTime, register AS feature
							FROM jqcalendar where church_id=$churchId order by display_order asc" );
			
			if ($myauth->num_rows () > 0) {
				while ( $myauth->movenext () ) {
					$arr = array ();
					
					array_push ( $arr, $myauth->getfield ( "Id" ) );
					array_push ( $arr, $myauth->getfield ( "display_order" ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( 'Title' ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "Location" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "startTime" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "endTime" ) ) );
					array_push ( $arr, $myauth->getfield ( "Id" ) );
					array_push ( $arr, $myauth->getfield ( "feature" ) );
					
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
	// Put Events Orders / updataOrders
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
				$sql = "UPDATE jqcalendar SET display_order = $v[1] WHERE Id = $v[0]";
				$myauth->query ( $sql );
			}
		}
		
		// set the response status and text
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	// **********************************************************************************
	// Delete Events DATA / deleteItem
	// **********************************************************************************
	public function delete() {
		$data = array ();
		parse_str ( file_get_contents ( 'php://input' ), $data );
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$id = $data ['id'];
			if (! get_magic_quotes_gpc ()) {
				$id = addslashes ( $id );
			}
			
			$myauth = new mysql ();
			$sql = "delete from jqcalendar where id=" . $id;
			$result = $myauth->query ( $sql );
			$return ['response'] = $result == "1" ? "success" : "Delete failed";
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
class Controller_Events__feature extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->cache ['ttl'] = 3600;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
	}
	// **********************************************************************************
	// Put Feature / setFeatured
	// **********************************************************************************
	public function put() {
		$data = array ();
		parse_str ( file_get_contents ( 'php://input' ), $data );
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$id = $data ['pk'];
			$isFeatured = $data ['value'];
			
			if (! get_magic_quotes_gpc ()) {
				$id = addslashes ( $id );
				$isFeatured = addslashes ( $isFeatured );
			}
			
			$myauth = new mysql ();
			$result = $myauth->query ( "UPDATE jqcalendar SET register='" . $isFeatured . "' WHERE Id=" . $id );
			$return ['response'] = $result ? 1 : 0;
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
class Controller_Events__item extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	// **********************************************************************************
	// Get Event Item DATA / selectItem
	// **********************************************************************************
	public function get() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$id = $data ['id'];
			if (! get_magic_quotes_gpc ()) {
				$id = addslashes ( $id );
			}
			
			$myauth = new mysql ();
			
			$sql = "select *  from jqcalendar where Id=$id";
			$myauth->query ( $sql );
			$row = $myauth->getAll ();
			while ( list ( $key ) = each ( $row ) ) {
				$row [$key] ["Subject"] = rawurldecode ( $row [$key] ["Subject"] );
				$row [$key] ["Description"] = rawurldecode ( $row [$key] ["Description"] );
				$row [$key] ["Location"] = rawurldecode ( $row [$key] ["Location"] );
				$row [$key] ["StartTime"] = date ( "d F Y", strtotime ( $row [$key] ["StartTime"] ) );
				$row [$key] ["EndTime"] = date ( "d F Y", strtotime ( $row [$key] ["EndTime"] ) );
				$row [$key] ["schedule_time"] = date ( "d F Y", $row [$key] ["schedule_time"] );
				$row [$key] ["schedule_time_expiry"] = date ( "d F Y", $row [$key] ["schedule_time_expiry"] );
			}
			$campus_selection = array ();
			// $arraylist=$row[$key]["campus"].split(',');
			$sql = "select * from latest_news_link where church_id=" . $row [0] ['church_id'] . " and events_id=" . $row [0] ['Id'];
			$myauth->query ( $sql );
			$rowsCampus = $myauth->getAll ();
			if (isset ( $rowsCampus )) {
				for($i = 0; $i < sizeof ( $rowsCampus ); $i ++) {
					$sql = "SELECT * FROM campus WHERE id =" . $rowsCampus [$i] ["campus_id"];
					$results = $myauth->query ( $sql );
					while ( $myauth->movenext () ) {
						
						array_push ( $campus_selection, array (
								$myauth->getfield ( "id" ),
								urldecode ( $myauth->getfield ( "title" ) ) 
						) );
					}
				}
			}
			$return ['response'] = array (
					$row,
					$campus_selection 
			);
		}
		// set the response status and text
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	// **********************************************************************************
	// Add Or Update Event DATA / addOrUpdate
	// **********************************************************************************
	public function post() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			if (! isset ( $data ['StartTime'] ) || ! $data ['StartTime']) {
				$info ['status'] = '0';
				$info ['msg'] = 'StartTime not null';
			} elseif (! isset ( $data ['EndTime'] ) || ! $data ['EndTime']) {
				$info ['status'] = '0';
				$info ['msg'] = 'EndTime not null';
			} elseif (! isset ( $data ['img_url'] ) || ! $data ['img_url']) {
				$info ['status'] = '0';
				$info ['msg'] = 'img_url not null';
			} elseif (! isset ( $data ['title'] ) || ! $data ['title']) {
				$info ['status'] = '0';
				$info ['msg'] = 'title not null';
			} elseif (! isset ( $data ['Location'] ) || ! $data ['Location']) {
				$info ['status'] = '0';
				$info ['msg'] = 'Location not null';
			} elseif (! isset ( $data ['Description'] ) || ! $data ['Description']) {
				$info ['status'] = '0';
				$info ['msg'] = 'Description not null';
			} elseif (! isset ( $data ['campus'] ) || ! $data ['campus']) {
				$info ['status'] = '0';
				$info ['msg'] = 'campus_selection not null';
			} else {
				// get files
				$id = $data ['id'];
				$lat = $data ['lat'];
				$lng = $data ['lng'];
				$title = $data ['title'];
				$startTime = date ( 'Y-m-d H:i:s', strtotime ( $data ['StartTime'] ) );
				$endTime = date ( 'Y-m-d H:i:s', strtotime ( $data ['EndTime'] ) );
				$location = $data ['Location'];
				$imgUrl = $data ['img_url'];
				$fileUrl = $data ['file_url'];
				$Description = $data ['Description'];
				$church_id = $data ['churchId'];
				$display = $data ['display'];
				$scheduleTime = 0;
				$scheduleTimeExpiry = 0;
				
				if (isset ( $data ['schedule_time'] ) && $data ['schedule_time']) {
					$scheduleTime = strtotime ( $data ['schedule_time'] );
				} else {
					$scheduleTime = 0;
				}
				
				if (isset ( $data ['schedule_time_expiry'] ) && $data ['schedule_time_expiry']) {
					$scheduleTimeExpiry = strtotime ( $data ['schedule_time_expiry'] );
				} else {
					$scheduleTimeExpiry = 0;
				}
				
				// check unsafe fields
				if (! get_magic_quotes_gpc ()) {
					$title = addslashes ( $title );
					$Description = addslashes ( $Description );
					$location = addslashes ( $location );
				}
				
				$myauth = new mysql ();
				if ($id != "") {
					$sql = "update jqcalendar set 
								Subject='$title',Location='$location',church_id=$church_id,Description='$Description'
								,StartTime='$startTime',EndTime='$endTime',image_url='$imgUrl',schedule_time=$scheduleTime
								,schedule_time_expiry=$scheduleTimeExpiry,file_url='$fileUrl',lat='$lat',lng='$lng',display='$display'
							where id = $id";
				} else {
					$sql = "insert into jqcalendar 
								(Subject,Location,church_id,StartTime,EndTime,image_url,file_url,lat,lng,schedule_time,schedule_time_expiry,display) 
							values
								('$title','$location',$church_id,'$startTime','$endTime','$imgUrl','$fileUrl','$lat','$lng',$scheduleTime,$scheduleTimeExpiry,'$display')";
				}
				$result = $myauth->query ( $sql );
				
				if ($id == "") {
					$id = $myauth->lastinsert ();
				}
				
				$campusArr = explode ( ',', $data ['campus'] ); // 逗号分隔的campus_id
				if (isset ( $campusArr ) && $campusArr) {
					$myauth = new mysql ();
					$result = $myauth->query ( "delete from latest_news_link where church_id = $church_id and events_id=$id" );
					for($i = 0; $i < sizeof ( $campusArr ); $i ++) {
						if ($result == 1) {
							$sql = "insert into latest_news_link (church_id,campus_id,events_id) values ($church_id,$campusArr[$i],$id)";
							$myauth->query ( $sql );
						}
					}
				}
				
				$info ['status'] = '1';
			}
			$return ['response'] = $info;
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
class Controller_Events__ticket extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	
	// **********************************************************************************
	// Get Tickets DATA / selete
	// **********************************************************************************
	public function get() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$eventId = $data ['event_id'];
			if (! get_magic_quotes_gpc ()) {
				$eventId = addslashes ( $eventId );
			}
			
			$sql = "SELECT display_order,id,name, price, reserved, status FROM event_tickets ";
			if ($eventId != "") {
				$sql = $sql . " where event_id='$eventId' ";
			}
			$sql = $sql . " ORDER BY display_order asc";
			
			$myauth = new mysql ();
			$myauth->query ( $sql );
			$i = 0;
			$dataArray = array ();
			
			if ($myauth->num_rows () > 0) {
				while ( $myauth->movenext () ) {
					
					$dataArray [$i] [] = urldecode ( $myauth->getfield ( 'id' ) );
					$dataArray [$i] [] = urldecode ( $myauth->getfield ( 'display_order' ) );
					$dataArray [$i] [] = urldecode ( $myauth->getfield ( 'name' ) );
					$dataArray [$i] [] = urldecode ( $myauth->getfield ( 'price' ) );
					$dataArray [$i] [] = urldecode ( $myauth->getfield ( 'reserved' ) );
					$dataArray [$i] [] = urldecode ( $myauth->getfield ( 'status' ) );
					$dataArray [$i] [] = $myauth->getfield ( 'id' );
					
					$i ++;
				}
			}
			$return ['response'] = $dataArray;
		}
		// set the response status and text
		
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	
	// **********************************************************************************
	// Add Ticket / addTicket
	// **********************************************************************************
	public function post() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			if (! isset ( $data ['event_id'] ) || ! $data ['event_id']) {
				$data ['event_id'] = 0;
			}
			if (! isset ( $data ['status'] ) || ! $data ['status']) {
				$_POST ['status'] = "N";
			}
			if (! isset ( $data ['name'] ) || ! $data ['name']) {
				$info ['status'] = '0';
				$info ['msg'] = 'ticket name not null';
			} elseif (! isset ( $data ['price'] ) || ! $data ['price']) {
				$info ['status'] = '0';
				$info ['msg'] = 'price  not null';
			} elseif (! (is_numeric ( $data ['price'] ) || is_float ( $data ['price'] ))) {
				$info ['status'] = '0';
				$info ['msg'] = 'price is number';
			} elseif (! isset ( $data ['reserved'] ) || ! $data ['reserved']) {
				$info ['status'] = '0';
				$info ['msg'] = 'reserved  not null';
			} elseif (! is_numeric ( $data ['reserved'] )) {
				$info ['status'] = '0';
				$info ['msg'] = 'reserved is number';
			} else {
				$myauth = new mysql ();
				
				$eventId = $data ['event_id'];
				$name = $data ['name'];
				$price = $data ['price'];
				$reserved = $data ['reserved'];
				$status = $data ['status'];
				
				if (! get_magic_quotes_gpc ()) {
					$eventId = addslashes ( $eventId );
					$name = addslashes ( $name );
				}
				
				if (isset ( $data ['ticket_id'] ) && $data ['ticket_id'] != '') {
					$sql = "update event_tickets set name='$name',price=$price,reserved=$reserved,status='$status' where id='" . $data ['ticket_id'] . "'";
				} else {
					$sql = "insert into event_tickets(event_id,name,price,reserved,status) values ($eventId,'$name',$price,$reserved,'$status')";
				}
				
				$results = $myauth->query ( $sql );
				
				if ($results == "1") {
					$info ['status'] = '1';
					$info ['msg'] = 'success';
				} else {
					$info ['status'] = '0';
					$info ['msg'] = 'failed';
				}
			}
			$return ['response'] = $info;
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
				$sql = "UPDATE event_tickets SET display_order = $v[1] WHERE id = $v[0];";
				$myauth->query ( $sql );
			}
		}
		// set the response status and text
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	// **********************************************************************************
	// Delete Ticket DATA / delTicket
	// **********************************************************************************
	public function delete() {
		$data = array ();
		parse_str ( file_get_contents ( 'php://input' ), $data );
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$id = $data ['id'];
			if (! get_magic_quotes_gpc ()) {
				$id = addslashes ( $id );
			}
			
			$myauth = new mysql ();
			$sql = "delete from event_tickets where id=" . $id;
			$result = $myauth->query ( $sql );
			$return ['response'] = $result == "1" ? "success" : "Delete failed";
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
class Controller_Events__ticketItem extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	
	// **********************************************************************************
	// Get Ticket Detail DATA / getTicketdetails
	// **********************************************************************************
	public function get() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$myauth = new mysql ();
			
			$sql = "select *  from event_tickets where id=" . $data ['ticket_id'];
			$myauth->query ( $sql );
			$rows = $myauth->getAll ();
			// var_dump($rows);
			while ( list ( $key ) = each ( $rows ) ) {
				$rows [$key] ["name"] = rawurldecode ( $rows [$key] ["name"] );
			}
			$return ['response'] = $rows;
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
class Controller_Events__custom extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	
	// **********************************************************************************
	// Add Custom Link DATA / saveCustomLink
	// **********************************************************************************
	public function post() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$myauth = new mysql ();
			$sql = "update jqcalendar set CustomLink='" . $data ["CustomLink"] . "' where Id=" . $data ["id"];
			$results = $myauth->query ( $sql );
			if ($results == "1") {
				$info ['status'] = '1';
				$info ['msg'] = 'success';
			} else {
				$info ['status'] = '0';
				$info ['msg'] = 'Save failed';
			}
			$return ['response'] = $info;
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
class Controller_Events__seats extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	
	// **********************************************************************************
	// Add Seats DATA / saveSeats
	// **********************************************************************************
	public function post() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$myauth = new mysql ();
			$sql = "update jqcalendar set seats=" . $data ["seats"] . " where Id=" . $data ["id"];
			$results = $myauth->query ( $sql );
			if ($results == "1") {
				$info ['status'] = '1';
				$info ['msg'] = 'success';
			} else {
				$info ['status'] = '0';
				$info ['msg'] = 'Save failed';
			}
			$return ['response'] = $info;
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
class Controller_Events__register extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	
	// **********************************************************************************
	// Add Register DATA / addRegister
	// **********************************************************************************
	public function post() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$myauth = new mysql ();
			$sql = "update jqcalendar set register='" . $data ["register"] . "' where Id=" . $data ["id"];
			$results = $myauth->query ( $sql );
			if ($results == "1") {
				$info ['status'] = '1';
				$info ['msg'] = 'success';
			} else {
				$info ['status'] = '0';
				$info ['msg'] = 'Save failed';
			}
			$return ['response'] = $info;
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
class Controller_Events__organiger extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	
	// **********************************************************************************
	// Get Organizer List DATA / getEventsOrganizerList
	// **********************************************************************************
	public function get() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$myauth = new mysql ();
			$event_id = $data ['event_id'];
			$result = array ();
			$myauth->query ( "SELECT * FROM event_organizer where Event_id=$event_id order by display_order asc" );
			
			if ($myauth->num_rows () > 0) {
				while ( $myauth->movenext () ) {
					$arr = array ();
					
					array_push ( $arr, urldecode ( $myauth->getfield ( "id" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "display_order" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( 'Name' ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( 'Title' ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( 'Sponser' ) ) );
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
	// Add Or Update Organizer DATA / aOrUEventOrganizer
	// **********************************************************************************
	public function post() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			if (! isset ( $data ['Title'] ) || ! $data ['Title']) {
				$info ['status'] = '0';
				$info ['msg'] = 'Title not null';
			} elseif (! isset ( $data ['name'] ) || ! $data ['name']) {
				$info ['status'] = '0';
				$info ['msg'] = 'Location not null';
			} elseif (! isset ( $data ['Description'] ) || ! $data ['Description']) {
				$info ['status'] = '0';
				$info ['msg'] = 'Description not null';
			} else {
				// get files
				$id = $data ['id'];
				$Title = $data ['Title'];
				$Name = $data ['name'];
				$image_url = $data ['image_url'];
				$Description = $data ['Description'];
				$Event_id = $data ['Event_id'];
				$Sponser = $data ['Sponser'];
				$Phone = $data ['Phone'];
				$Email = $data ['Email'];
				$Twitter = $data ['Twitter'];
				$Facebook = $data ['Facebook'];
				
				// check unsafe fields
				if (! get_magic_quotes_gpc ()) {
					$title = addslashes ( $Title );
					$Name = addslashes ( $Name );
				}
				
				$myauth = new mysql ();
				if ($id != "") {
					$sql = "update event_organizer  set 
								 Title='$Title',Name='$Name',Description='$Description',Event_id=$Event_id
								,image_url='$image_url',Sponser='$Sponser',Phone='$Phone',Email='$Email'
								,Twitter='$Twitter',Facebook='$Facebook'
							where id = $id";
				} else {
					$sql = "insert into event_organizer 
								(Title,Name,Event_id,Description,image_url,Sponser,Phone,Email,Twitter,Facebook) 
							values
								('$Title','$Name',$Event_id,'$Description','$image_url','$Sponser','$Phone','$Email','$Twitter','$Facebook')";
				}
				$result = $myauth->query ( $sql );
				
				if ($id == "") {
					$id = $myauth->lastinsert ();
				}
				
				$info ['status'] = '1';
			}
			$return ['response'] = $info;
		}
		// set the response status and text
		
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	// **********************************************************************************
	// Put Organizer Orders DATA / updataOrganizerOrders
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
				$sql = "UPDATE event_organizer SET display_order = $v[1] WHERE id = $v[0]";
				$myauth->query ( $sql );
			}
		}
		
		// set the response status and text
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	// **********************************************************************************
	// Delete Organizer Item DATA / deleteOrganizerItem
	// **********************************************************************************
	public function delete() {
		$data = array ();
		parse_str ( file_get_contents ( 'php://input' ), $data );
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$id = $data ['id'];
			if (! get_magic_quotes_gpc ()) {
				$id = addslashes ( $id );
			}
			
			$myauth = new mysql ();
			$sql = "delete from event_organizer where id=" . $id;
			$results = $myauth->query ( $sql );
			$return ['response'] = $result == "1" ? "success" : "Delete failed";
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
class Controller_Events__organizerItem extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	
	// **********************************************************************************
	// Get Organizer Item DATA / selectOrganizerItem
	// **********************************************************************************
	public function get() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$id = $data ['id'];
			$event_id = $data ['event_id'];
			if (! get_magic_quotes_gpc ()) {
				$id = addslashes ( $id );
			}
			
			$myauth = new mysql ();
			
			$sql = "select *  from event_organizer where id=$id";
			$myauth->query ( $sql );
			$row = $myauth->getAll ();
			while ( list ( $key ) = each ( $row ) ) {
				$row [$key] ["Title"] = rawurldecode ( $row [$key] ["Title"] );
				$row [$key] ["Name"] = rawurldecode ( $row [$key] ["Name"] );
				$row [$key] ["Description"] = rawurldecode ( $row [$key] ["Description"] );
				$row [$key] ["Email"] = rawurldecode ( $row [$key] ["Email"] );
				$row [$key] ["Twitter"] = rawurldecode ( $row [$key] ["Twitter"] );
				$row [$key] ["Facebook"] = rawurldecode ( $row [$key] ["Facebook"] );
			}
			$return ['response'] = $row;
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
class Controller_Events__schedule extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	
	// **********************************************************************************
	// Get Schedule List DATA / getEventsScheduleList
	// **********************************************************************************
	public function get() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$myauth = new mysql ();
			$event_id = $data ['event_id'];
			$result = array ();
			$myauth->query ( "SELECT * FROM event_schedule where Event_id=$event_id order by display_order asc" );
			
			if ($myauth->num_rows () > 0) {
				while ( $myauth->movenext () ) {
					$arr = array ();
					
					array_push ( $arr, urldecode ( $myauth->getfield ( "id" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "display_order" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( 'Title' ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "Location" ) ) );
					array_push ( $arr, date ( 'd M Y', $myauth->getfield ( 'Date_Time' ) ) );
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
	// Add Or Update Schedule DATA / aOrUEventSchedule
	// **********************************************************************************
	public function post() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			if (! isset ( $data ['Date_Time'] ) || ! $data ['Date_Time']) {
				$info ['status'] = '0';
				$info ['msg'] = 'Date_Time not null';
			} elseif (! isset ( $data ['Title'] ) || ! $data ['Title']) {
				$info ['status'] = '0';
				$info ['msg'] = 'Title not null';
			} else if (! isset ( $data ['Location'] ) || ! $data ['Location']) {
				$info ['status'] = '0';
				$info ['msg'] = 'Location not null';
			} elseif (! is_numeric ( $data ['Speaker'] )) {
				$info ['status'] = '0';
				$info ['msg'] = 'Speaker not number';
			} else {
				// get files
				$id = $data ['id'];
				$Title = $data ['Title'];
				$Location = $data ['Location'];
				$image_url = $data ['image_url'];
				$Description = $data ['Description'];
				$Event_id = $data ['Event_id'];
				$display = $data ['display'];
				$Speaker = $data ['Speaker'];
				
				if (isset ( $data ['Date_Time'] ) && $data ['Date_Time']) {
					$Date_Time = strtotime ( $data ['Date_Time'] );
				} else {
					$Date_Time = 0;
				}
				
				// check unsafe fields
				if (! get_magic_quotes_gpc ()) {
					$title = addslashes ( $Title );
					$Location = addslashes ( $Location );
				}
				
				$myauth = new mysql ();
				if ($id != "") {
					$sql = "update event_schedule set 
						 		Title='$Title',Location='$Location',Description='$Description',Event_id=$Event_id
								,Speaker=$Speaker,image_url='$image_url',Date_Time=$Date_Time,display='$display'
							where id = $id";
				} else {
					$sql = "insert into event_schedule 
								(Title,Location,Event_id,Description,Speaker,image_url,Date_Time,display) 
							values
								('$Title','$Location',$Event_id,'$Description',$Speaker,'$image_url',$Date_Time,'$display')";
				}
				$result = $myauth->query ( $sql );
				
				if ($id == "") {
					$id = $myauth->lastinsert ();
				}
				
				$info ['status'] = '1';
			}
			$return ['response'] = $info;
		}
		// set the response status and text
		
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	// **********************************************************************************
	// Put Schedule Orders DATA / updataScheduleOrders
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
				$sql = "UPDATE event_schedule SET display_order = $v[1] WHERE id = $v[0]";
				$myauth->query ( $sql );
			}
		}
		
		// set the response status and text
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	// **********************************************************************************
	// Delete Schedule Item DATA / deleteScheduleItem
	// **********************************************************************************
	public function delete() {
		$data = array ();
		parse_str ( file_get_contents ( 'php://input' ), $data );
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$id = $data ['id'];
			if (! get_magic_quotes_gpc ()) {
				$id = addslashes ( $id );
			}
			
			$myauth = new mysql ();
			$sql = "delete from event_schedule where id=" . $id;
			$results = $myauth->query ( $sql );
			$return ['response'] = $results == "1" ? "success" : "Delete failed";
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
class Controller_Events__scheduleItem extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	
	// **********************************************************************************
	// Get Schedule Item DATA / selectScheduleItem
	// **********************************************************************************
	public function get() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$id = $data ['id'];
			$event_id = $data ['event_id'];
			if (! get_magic_quotes_gpc ()) {
				$id = addslashes ( $id );
			}
			
			$myauth = new mysql ();
			
			$sql = "select *  from event_schedule where id=$id";
			$myauth->query ( $sql );
			$row = $myauth->getAll ();
			while ( list ( $key ) = each ( $row ) ) {
				$row [$key] ["Title"] = rawurldecode ( $row [$key] ["Title"] );
				$row [$key] ["Description"] = rawurldecode ( $row [$key] ["Description"] );
				$row [$key] ["Location"] = rawurldecode ( $row [$key] ["Location"] );
				$row [$key] ["Date_Time"] = date ( "d F Y", $row [$key] ["Date_Time"] );
			}
			$sql = "select *  from event_speakers_sponser where Event_id=$event_id";
			$myauth->query ( $sql );
			$rowSpeaker = $myauth->getAll ();
			while ( list ( $key ) = each ( $rowSpeaker ) ) {
				$rowSpeaker [$key] ["Title"] = rawurldecode ( $rowSpeaker [$key] ["Title"] );
				$rowSpeaker [$key] ["Description"] = rawurldecode ( $rowSpeaker [$key] ["Description"] );
			}
			$return ['response'] = array (
					$row,
					$rowSpeaker 
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
class Controller_Events__speaker extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	
	// **********************************************************************************
	// Get Speaker List DATA / getEventsSpeakerList
	// **********************************************************************************
	public function get() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$myauth = new mysql ();
			$event_id = $data ['event_id'];
			$result = array ();
			$myauth->query ( "SELECT * FROM event_speakers_sponser where Event_id=$event_id order by display_order asc" );
			
			if ($myauth->num_rows () > 0) {
				while ( $myauth->movenext () ) {
					$arr = array ();
					
					array_push ( $arr, urldecode ( $myauth->getfield ( "id" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( "display_order" ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( 'Name' ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( 'Title' ) ) );
					array_push ( $arr, urldecode ( $myauth->getfield ( 'Sponser' ) ) );
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
	// Add Or Update Speaker DATA / aOrUEventSpeaker
	// **********************************************************************************
	public function post() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			if (! isset ( $data ['Title'] ) || ! $data ['Title']) {
				$info ['status'] = '0';
				$info ['msg'] = 'Title not null';
			} elseif (! isset ( $data ['name'] ) || ! $data ['name']) {
				$info ['status'] = '0';
				$info ['msg'] = 'Location not null';
			} elseif (! isset ( $data ['Description'] ) || ! $data ['Description']) {
				$info ['status'] = '0';
				$info ['msg'] = 'Description not null';
			} else {
				// get files
				$id = $data ['id'];
				$Title = $data ['Title'];
				$Name = $data ['name'];
				$image_url = $data ['image_url'];
				$Description = $data ['Description'];
				$Event_id = $data ['Event_id'];
				$Sponser = $data ['Sponser'];
				$Phone = $data ['Phone'];
				$Email = $data ['Email'];
				$Twitter = $data ['Twitter'];
				$Facebook = $data ['Facebook'];
				
				// check unsafe fields
				if (! get_magic_quotes_gpc ()) {
					$title = addslashes ( $Title );
					$Name = addslashes ( $Name );
				}
				
				$myauth = new mysql ();
				if ($id != "") {
					$sql = "update event_speakers_sponser set 
						 		Title='$Title',Name='$Name',Description='$Description',Event_id=$Event_id,image_url='$image_url'
						 		,Sponser='$Sponser',Phone='$Phone',Email='$Email',Twitter='$Twitter',Facebook='$Facebook'
							where id = $id";
				} else {
					$sql = "insert into event_speakers_sponser 
								(Title,Name,Event_id,Description,image_url,Sponser,Phone,Email,Twitter,Facebook) 
							values
								('$Title','$Name',$Event_id,'$Description','$image_url','$Sponser','$Phone','$Email','$Twitter','$Facebook')";
				}
				$result = $myauth->query ( $sql );
				
				if ($id == "") {
					$id = $myauth->lastinsert ();
				}
				
				$info ['status'] = '1';
			}
			$return ['response'] = $info;
		}
		// set the response status and text
		
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	// **********************************************************************************
	// Put Speaker Orders DATA / updataSpeakerOrders
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
				$sql = "UPDATE event_speakers_sponser SET display_order = $v[1] WHERE id = $v[0]";
				$myauth->query ( $sql );
			}
		}
		
		// set the response status and text
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	// **********************************************************************************
	// Delete Speaker Item DATA / deleteSpeakerItem
	// **********************************************************************************
	public function delete() {
		$data = array ();
		parse_str ( file_get_contents ( 'php://input' ), $data );
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$id = $data ['id'];
			if (! get_magic_quotes_gpc ()) {
				$id = addslashes ( $id );
			}
			
			$myauth = new mysql ();
			$sql = "delete from event_speakers_sponser where id=" . $id;
			$results = $myauth->query ( $sql );
			$return ['response'] = $results == "1" ? "success" : "Delete failed";
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
class Controller_Events__speakerItem extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	
	// **********************************************************************************
	// Get Speaker Item DATA / selectSpeakerItem
	// **********************************************************************************
	public function get() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$id = $data ['id'];
			$event_id = $data ['event_id'];
			if (! get_magic_quotes_gpc ()) {
				$id = addslashes ( $id );
			}
			
			$myauth = new mysql ();
			
			$sql = "select *  from event_speakers_sponser where id=$id";
			$myauth->query ( $sql );
			$row = $myauth->getAll ();
			while ( list ( $key ) = each ( $row ) ) {
				$row [$key] ["Title"] = rawurldecode ( $row [$key] ["Title"] );
				$row [$key] ["Name"] = rawurldecode ( $row [$key] ["Name"] );
				$row [$key] ["Description"] = rawurldecode ( $row [$key] ["Description"] );
				$row [$key] ["Email"] = rawurldecode ( $row [$key] ["Email"] );
				$row [$key] ["Twitter"] = rawurldecode ( $row [$key] ["Twitter"] );
				$row [$key] ["Facebook"] = rawurldecode ( $row [$key] ["Facebook"] );
			}
			$return ['response'] = array (
					$row 
			);
		}
		// set the response status and text
		
		$this->responseStatus = $return ['status'];
		$this->response = $return ['response'];
	}
	
	
	// **********************************************************************************
	// Add Or Update Speaker DATA / aOrUEventSpeaker
	// **********************************************************************************
	public function post() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			if (! isset ( $data ['Title'] ) || ! $data ['Title']) {
			$info ['status'] = '0';
			$info ['msg'] = 'Title not null';
			return $info;
		}else if (! isset ( $data ['name'] ) || ! $data ['name']) {
			$info ['status'] = '0';
			$info ['msg'] = 'Location not null';
			return $info;
		}else if (! isset ( $data ['Description'] ) || ! $data ['Description']) {
			$info ['status'] = '0';
			$info ['msg'] = 'Description not null';
			return $info;
		}
		else
		{
			// get files
			$id = $data ['id'];
			$Title = $data ['Title'];
			$Name = $data ['name'];
			$image_url = $data ['image_url'];
			$Description = $data ['Description'];
			$Event_id = $data['Event_id'];
			$Sponser = $data['Sponser'];
			$Phone = $data['Phone'];
			$Email = $data['Email'];
			$Twitter = $data['Twitter'];
			$Facebook = $data['Facebook'];
			
			
			
			
			// check unsafe fields
			if (! get_magic_quotes_gpc ()) {
				$title = addslashes ( $Title );
				$Name = addslashes ( $Name );
			}
			
			$myauth = new mysql ();
			if ($id != "") {
				$sql = "update event_speakers_sponser 
						set 
							 Title='$Title'
							,Name='$Name'
							,Description='$Description'
							,Event_id=$Event_id
							,image_url='$image_url'
							,Sponser='$Sponser'
							,Phone='$Phone'
							,Email='$Email'
							,Twitter='$Twitter'
							,Facebook='$Facebook'
						where id = $id";
				} else {
					$sql = "insert into event_speakers_sponser 
								(Title,Name,Event_id,Description,image_url,Sponser,Phone,Email,Twitter,Facebook) 
							values('$Title','$Name',$Event_id,'$Description','$image_url','$Sponser','$Phone','$Email','$Twitter','$Facebook')";
				}
				$result = $myauth->query ( $sql );
				$info ['status'] = '1';
			}
			$return ['response'] = $info;
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
class Controller_Events__Speakerselect extends myRestController {
	
	// we dont want to cache nothing
	public function settings() {
		$this->cache ['enabled'] = false;
		$this->logs ['enabled'] = false;
		$this->logs ['detailed'] = false;
		$this->production = true;
	}
	
	// **********************************************************************************
	// Get Speaker Item TO Select2 
	// **********************************************************************************
	public function get() {
		
		// grab params from query string [this will read all the params from query string]
		$data = $this->request ['params'] ['string'];
		$headers = $this->request ['headers'];
		
		$authenticate = new authenticate ();
		$return = $authenticate->check ( $data, $headers );
		
		if ($return ['status'] == 200) {
			$event_id = $data ['event_id'];
			
			$myauth = new mysql ();
			$sql = "select *  from event_speakers_sponser where Event_id=$event_id";
			$myauth->query ( $sql );
			$rowSpeaker = $myauth->getAll ();
			while ( list ( $key ) = each ( $rowSpeaker ) ) {
				$rowSpeaker [$key] ["Title"] = rawurldecode ( $rowSpeaker [$key] ["Title"] );
				$rowSpeaker [$key] ["Description"] = rawurldecode ( $rowSpeaker [$key] ["Description"] );
			}
			$return ['response'] = $rowSpeaker;
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
