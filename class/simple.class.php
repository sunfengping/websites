<?php 
/**
 * ====================================================================================
 *                           Premium URL Shortener (c) KBRmedia
 * ----------------------------------------------------------------------------------
 *  @copyright - This software is exclusively sold at CodeCanyon.net. If you have downloaded this
 *  from another site or received it from someone else than me, then you are engaged
 *  in illegal activity. You must delete this software immediately or buy a proper
 *  license from http://codecanyon.net/user/KBRmedia/portfolio?ref=KBRmedia.
 *
 *	@license http://gempixel.com/license
 *
 *  Thank you for your cooperation and don't hesitate to contact me if anything :)
 * ====================================================================================
 *
 * @author Chris Vanderhorst
 * @package simplePHP Class
 */

class simple {
	/**
	 * Constructor - Start a session needed for other functions
	 * @since 3.0
	 **/
	public function __construct(){

		if(!isset($_SESSION)){
			session_start();
		}
	}



	/**
	 * BBcode to HTML function
	 * @since 2.0
	 **/
	public function bbcode($string){
		// Replace [video] using another method
		$string = preg_replace('~\[video\](.*?)\[/video\]~e', "self::video('$1')" , $string);
		// To set your own bbcode simple follow use one of the existing tags as an example.
		$find = array( 
        '~\[b\](.*?)\[/b\]~s', 
        '~\[i\](.*?)\[/i\]~s', 
        '~\[u\](.*?)\[/u\]~s', 	        
        '~\[size=(.*?)\](.*?)\[/size\]~s', 
        '~\[color=(.*?)\](.*?)\[/color\]~s',
				'~\[img\](.*?)\[/img\]~s',
				'~\[url=(.*?)\](.*?)\[/url\]~s'
	    ); 
	    $replace = array( 
        '<b>$1</b>', 
        '<i>$1</i>', 
        '<u>$1</u>', 
        '<span style="font-size:$1px;">$2</span>', 
        '<span style="color:$1;">$2</span>',
        '<img src="$1" alt="" />',
        '<a href="$1">$2</a>'
	    ); 
	    return preg_replace($find, $replace, $string);
	}
	
		/**
	 * BBcode to HTML function
	 * @since 2.0
	 **/
	public function get_string_between($string, $start, $end){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
	}
	
	
	/**
	 * Cleans out a text and strip potentially dangerous tags.
	 * @since 3.0
	 **/

	public function jclean($text) {
	    // Damn pesky carriage returns...
	    $text = str_replace("\r\n", "\n", $text);
	    $text = str_replace("\r", "\n", $text);
	
	    // JSON requires new line characters be escaped
	    $text = str_replace("\n", "\\n", $text);
	    return $text;
	}
	
		/**
	 * Cleans out a text and strip potentially dangerous tags.
	 * @since 3.0
	 **/

	public function pretty($text) {
	    // Damn pesky carriage returns...
	    $text=quoted_printable_decode(nl2br($text));
	    return $text;
	}
	



	/**
	 * Cleans out a text and strip potentially dangerous tags.
	 * @since 3.0
	 **/
	public function clean($text, $full= FALSE){
		$text=preg_replace('/<script[^>]*>([\s\S]*?)<\/script[^>]*>/i', '', $text);			
		if($full){
      $search = array('@<script[^>]*?>.*?</script>@si',
                     '@<[\/\!]*?[^<>]*?>@si',
                     '@<style[^>]*?>.*?</style>@siU',
                     '@<![\s\S]*?--[ \t\n\r]*>@'
      ); 
      $text = preg_replace($search, '', $text);			
    }else{
			$text=strip_tags($text,'<b><i><s><u><a><pre><code><p>');
			$text=str_replace('href=','rel="nofollow" href=', $text);
    }
		return $text;
	}

	/**
	 * Validates email
	 * @since 2.0
	 **/		
	public function checkemail($email){
		if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{2,})+$/i', $email) || strlen($email)>50 || !filter_var($email, FILTER_VALIDATE_EMAIL)) return 'Please enter a valid email.';
	}

	/**
	 * Validates username
	 * @since 2.0
	 **/		
	public function checkuser($user){
		if(!preg_match('/^\w{4,}$/', $user)) return 'Please enter a valid username.';
	}

	/**
	 * Encode password: 3 Choice of encoding MD5, SHA1 or SHA256
	 * @since 2.0
	 **/		
	public function encode_password($password, $type='MD5', $salt=''){
		return hash($type,$password.$salt);
	}

	/**
	 * Output the disqus system given the username otherwise output message.
	 * @since 2.0
	 **/		
	public function disqus_comment($username=''){
		if(empty($username)){
			return 'Disqus requires you to register your website. You can do that from <a href="http://disqus.com">here</a>';
		}
		$return="<div id=\"disqus_thread\"></div>
	        <script type=\"text/javascript\">
	            /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
	            var disqus_shortname = '{$username}'; // required: replace example with your forum shortname

	            /* * * DON'T EDIT BELOW THIS LINE * * */
	            (function() {
	                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
	                dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
	                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
	            })();
	        </script>";
        return $return;
	}

	/**
	 * Output the facebook commenting system. Set w='0' to make it Responsive.
	 * @since 2.0
	 **/		
	public function facebook_comment($url="",$num="4",$w='700',$theme='light'){
			$code="";
			if(empty($url)){
				$url="http://" . $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI'];
			}
			if($w=='0'){
				$code.=	'<style type="text/css">.fbcomments,.fb_iframe_widget,.fb_iframe_widget[style],.fb_iframe_widget iframe[style],.fbcomments iframe[style],.fb_iframe_widget span {width: 100% !important;}</style>';
			}
			$code.='<div id="fb-root"></div>
	          <script>(function(d, s, id) {
	          var js, fjs = d.getElementsByTagName(s)[0];
	          if (d.getElementById(id)) return;
	          js = d.createElement(s); js.id = id;
	          js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	          fjs.parentNode.insertBefore(js, fjs);
	          }(document, \'script\', \'facebook-jssdk\'));</script>
	          <div class="fb-comments" data-href="'.$url.'" data-num-posts="'.$num.'" data-width="'.$w.'" data-colorscheme="'.$theme.'" ></div>';
          return $code;
	}

	/**
	 * Formats Numbers
	 * @since 2.0
	 **/		
	public function formatnumber($number,$decimal="0") {
		if($number>1000000000000) $number= round($number /1000000000000, $decimal)."T";
		if($number>1000000000) $number= round($number /1000000000, $decimal)."B";
		if($number>1000000) $number= round($number /1000000, $decimal)."M";
		if($number>10000) $number= round($number /10000, $decimal)."K";
		return $number;
	}

	/**
	 * Formats Date and Time,setting  $time to FALSE will return the time.
	 * @since 2.0
	 **/		
	public function formatdate($date, $time=TRUE) {
		$date=strtotime($date);
			if(!$time){
				return date('F j, Y', $date);
			}else{
				return date('F j, Y', $date).' at '.date('h:ia', $date);
			}
		return FLASE;
	}
	
	
	/**
	 * Formats Date and Time,setting  $time to FALSE will return the time.
	 * @since 2.0
	 **/		
	public function epoch($epoch, $format="d-m-Y g:i a") {
		return date($format, $epoch);
	}
	
	

	/**
	 * Format Price
	 * @since 2.0
	 **/		
	public function formatprice($price,$currency="USD",$cent=FALSE){
		$currencies = array(
				"USD" => "$",
				"CAD" => "C$",
				"GBP" => "&#163;",
				"EUR" => "&#8364;",
				"AUD" => "$",
				'NZD' => "$",
				'CNY' => '&#165;',
				"DKK" => 'kr'
			);
		if(empty($currency) OR !isset($currencies[$currency])) $currency="USD";			
		$price=explode(".",$price);
		if($cent){
			$cent="<sup>{$price[1]}</sup>";	
		}else{
			$cent=".".$price[1];
		}
		return $currencies[$currency].$price[0].$cent;
	}

	/**
	 * Gets the extension of file.
	 * @since 2.0
	 **/		
	public function getextension($filename) {
		$ext = strrchr($filename, ".");
		return $ext; 
	}
	
	/**
	 * Generates a pagination Menu
	 * @since 2.0
	 **/		
	public function pagination($total, $current, $format, $limit='1'){
	     $page_count = ceil($total/$limit);
	     $current_range = array(($current-5 < 1 ? 1 : $current-3), ($current+5 > $page_count ? $page_count : $current+3));

	     $first_page = $current > 3 ? '<a href="'.sprintf($format, '1').'">1</a>'.($current < 5 ? ' ' : ' ... ') : null;
	     $last_page = $current < $page_count-2 ? ($current > $page_count-4 ? ' ' : ' ... ').'<a href="'.sprintf($format, $page_count).'">'.$page_count.'</a>' : null;

	     $previous_page = $current > 1 ? '<a href="'.sprintf($format, ($current-1)).'">Previous</a> ' : null;
	     $next_page = $current < $page_count ? ' <a href="'.sprintf($format, ($current+1)).'">Next</a> ' : null;

	     for ($x=$current_range[0];$x <= $current_range[1]; ++$x)    
			$pages[] = ($x == $current ? '<span class="current">'.$x.'</span>' : '<a href="'.sprintf($format, $x).'"">'.$x.'</a>');
	     if ($page_count > 1)
		return '<div class="pagination">'.$previous_page.implode(' ', $pages).$last_page.$next_page.'</div>';
	}	

	/**
	 * Syntax Highlighter
	 * @since 3.0
	 **/
	public function pre($code){
		$find = array(
      	'~&lt;(.*?)\s~',
				'~&lt;(.*?)&gt;~',	        				 
        '~class~',
        "~=\'(.*?)\'~"
    	); 
    $replace = array( 
        '<span style="color:#008033">&lt;$1</span> ',	
        '<span style="color:#008033">&lt;$1&gt;</span> ',			        	    	
        '<span style="color:#1E347B">class</span>',
        '="<span style="color:#D14">$1</span>"'
    	); 
    return preg_replace($find, $replace, htmlentities($code));
	}

	/**
	 * Generates random Password: need the length, default is 8
	 * @since 2.0
	 **/
	public function random_password($l='8'){ 
		$rand="";
		$c= "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@&*$"; 
			srand((double)microtime()*1000000); 
			for($i=0; $i<$l; $i++) { 
				$rand.= $c[rand()%strlen($c)]; 
			} 
		return $rand; 
	}

	/**
	 * Sets the notification message: require the message content and type
	 * @since 2.0
	 **/
	public function setmsg($message,$type=''){
		if(!isset($_SESSION)){
			session_start();
		}
		if(isset($_SESSION["msg"])) unset($_SESSION["msg"]);
		$_SESSION["msg"]="<div class='message $type'>$message</div>";
	}	

	/**
	 * Show the notification message and unset the $_session variable
	 * @since 2.0
	 **/
	public function showmsg(){
		if(isset($_SESSION["msg"])) {
			$msg=$_SESSION["msg"];
			unset($_SESSION["msg"]);
			return $msg;
		}
	}	
	/**
	 * Shorten URLs
	 * @since 1.0
	 **/
	public function shorten_url($url, $provider='tinyurl', $apikey=''){
		//Clean URL
		$url = trim($url);
		if (get_magic_quotes_gpc()) {
			$url = stripslashes($url);
		}
		$url = strtr($url,array_flip(get_html_translation_table(HTML_ENTITIES)));
		$url = strip_tags($url);

		//Shorten it
		if($provider=="tinyurl"){

			$short=@file_get_contents('http://tinyurl.com/api-create.php?url='.$url);

		}elseif($provider=="isgd"){

			$short=@file_get_contents('http://is.gd/create.php?format=simple&url='.$url);

		}elseif($provider=="google"){

			$postData = array('longUrl' => $url);
			$jsonData = json_encode($postData);
			 
			$curlObj = curl_init();
			 
			curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url');
			curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curlObj, CURLOPT_HEADER, 0);
			curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
			curl_setopt($curlObj, CURLOPT_POST, 1);
			curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);
			$response = @curl_exec($curlObj);
			$json = json_decode($response);
			curl_close($curlObj);
			$short=$json->id;				
		}

		return $short;
	}

	/**
	 * Unshorten URLs
	 * @since 1.0
	 **/
	public function unshorten_url($url){
		//Clean URL
		$url = trim($url);
		if (get_magic_quotes_gpc()) {
			$url = stripslashes($url);
		}
		$url = strtr($url,array_flip(get_html_translation_table(HTML_ENTITIES)));
		$url = strip_tags($url);
		preg_match('((http://|https://|www.)([\w-\d]+\.)+[\w-\d]+)',$url, $domain);

		if($domain[2]=="goo."){
			$file=@file_get_contents("https://www.googleapis.com/urlshortener/v1/url?shortUrl=$url");
			$json = json_decode($file);
			return $json->longUrl;		
		}elseif($domain[2]=="is."){
			$file=@file_get_contents("http://is.gd/forward.php?format=json&shorturl=$url");
			$json = json_decode($file);
			return $json->url;	
		}else{
			return array('error' => TRUE, 'msg'=>'This provider is not currently available.');
		}
	}	
	/**
	 * Generates a clean url
	 * @since 1.0
	 **/
	public function slug($str){
			$slug=preg_replace('/[^_0-9a-zA-Z ]/', '', strtolower($str));
			$slug=preg_replace('/\s\s+/', ' ', $slug);
			$slug=str_replace(' ','-',$slug);
		return $slug;
	}

	/**
	 * Converts date/time to timeago
	 * @since 1.0
	 **/
	public function timeago($time){
	   $time=strtotime($time);
	   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	   $lengths = array("60","60","24","7","4.35","12","10");
	   $now = time();
		   $difference = $now - $time;
		   $tense= "ago";
		   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			   $difference /= $lengths[$j];
		   }
		   $difference = round($difference);
		   if($difference != 1) {
			   $periods[$j].= "s";
		   }
	   return "$difference $periods[$j] $tense ";
	}	
	
	
		/**
	 * Converts date/time to timeago
	 * @since 1.0
	 **/
	public 	function ago($timestamp){
	if($timestamp){
	   $difference = time() - $timestamp;
	   $periods = array("second", "minute", "hour", "day", "week", "month", "years", "decade");
	   $lengths = array("60","60","24","7","4.35","12","10");
	   for($j = 0; $difference >= $lengths[$j]; $j++)
	   $difference /= $lengths[$j];
	   $difference = round($difference);
	   if($difference != 1) $periods[$j].= "s";
	   $text = "$difference $periods[$j] ago";
	   return $text;
	  
	}
	}
	

  
  

	/**
	 * Cut a text after XX numbers of characters.
	 * @since 1.0
	 **/
	public function truncate($string, $limit,$end="...") {
	  $len = strlen($string);
		if ($len > $limit) return substr($string,0,$limit).$end;
		else return $string; 
	}

	/**
	 * Generate a thumbnail of the an image.
	 * @since 1.0
	 **/
	public function thumb($src,$dest='',$desired_width, $quality='100'){

				if(!file_exists($src)) return array("error"=>TRUE, "msg"=> "This file ($src) doesn't exist."); 

				$extension = $this->getextension($src);		// Gets the extension of the file
				if(empty($dest)){
					$dest=str_replace($extension, $desired_width.$extension, $src); //Rename the file if the user didn't rename it.
				}
				$suffix = array(        
				  '.jpeg' => 'jpeg',      
				  '.jpg' => 'jpeg',      
				  '.gif' => 'gif',        
				  '.png' => 'png'      
				);
				if($suffix[$extension]=="png"){
					$quality=0;
				}elseif ($suffix[$extension]=="gif") {
					$quality=FALSE;
				}

				//Determines if the file has a valid extension, if not outputs an error.
				if(!isset($suffix[$extension])) return array("error"=>TRUE, "msg"=> "Unknown File Type. You can only resize jpeg (jpg), gif and png.");

				//Proceeds with resizing
				$image_suffix=$suffix[$extension];
				$createfrom='imagecreatefrom'.$image_suffix;		
				$image='image'.$image_suffix;
			  	$source_image = $createfrom($src);
			  	$width = imagesx($source_image);
			  	$height = imagesy($source_image);
			  	$desired_height = floor($height*($desired_width/$width));
			  	$virtual_image = imagecreatetruecolor($desired_width,$desired_height);
			  	imagecopyresampled($virtual_image,$source_image,0,0,0,0,$desired_width,$desired_height,$width,$height);
			  	$image($virtual_image,$dest,$quality);

		  	return array("error"=>FALSE, "msg"=> "Image has been successfully resized.", "thumb"=>$dest);
	}

	/**
	 * Grab Youtube video from link
	 * @since 1.0
	 **/
	public function youtube($url,$w="400",$h="250",$theme="dark"){
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            $id = $match[1];
            return '<div class="video"><iframe width="'.$w.'" height="'.$h.'" src="http://www.youtube.com/embed/' . $id . '?theme='.$theme.'&iv_load_policy=3&wmode=transparent" frameborder="0" allowfullscreen></iframe></div>';
		}	
	}	

	/**
	 * Grabs Vimeo video from link
	 * @since 1.0
	 **/
	public function vimeo($url, $w="400", $h="250") {
	        if (preg_match('((http://|https://|www.)+(vimeo.)+[\w-\d]+(/)+(\d+))',$url, $id)){
	        $id=$id[4];
	        return '<div class="video"><iframe src="http://player.vimeo.com/video/'.$id.'?title=0&amp;byline=0&amp;portrait=0" width="'.$w.'" height="'.$h.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>'; 
	    	}
	}		

	/**
	 * Replace all Youtube and Vimeo link with Videos
	 * @since 1.0
	 **/
	public function video($text, $w='400', $h='250', $url=''){
		if(!empty($url)){
			preg_match('((http://|https://|www.)([\w-\d]+\.)+[\w-\d]+)', $url, $domain);
        	$host = str_replace(".","", $domain[2]);	
			return $this->$host($url,$w,$h);
		}else{
	      preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $text, $match);
	      foreach ($match[0] as $link) {
			preg_match('((http://|https://|www.)([\w-\d]+\.)+[\w-\d]+)',$link, $domain);
        	$host = str_replace(".","", $domain[2]);	
	        	if(method_exists(__CLASS__,$host)){
	        		$text=str_replace($link,"",$text);
	            	$text.=$this->$host($link,$w,$h);
	          	}      	
			}
			return $text;
		}
	}

	/**
	 * Upload and Generate Thumbnail
	 * @since 1.0
	 **/
	public function upload_image($folder='upload', $file, $thumb=FALSE, $thumbwidth='300', $thumbfolder='',$thumbprefix=""){
		$msg='';
			$suffix = array(        
				  '.jpeg' => 'jpeg',      
				  '.jpg' => 'jpeg',      
				  '.gif' => 'gif',        
				  '.png' => 'png'      
			);
		if(!isset($suffix[$this->getextension($file['name'])])) return array("error"=>TRUE, "msg" => "Please only upload image (jpeg/jpg, png or gif)");


		if(!file_exists($folder) OR !is_dir($folder)){
	        mkdir($folder);         
	    }
		if($thumb && empty($thumbfolder)){
			$thumbname=$thumbprefix.$file['name'];
			$thumbfolder=$folder.'thumb/';				
		}else{
			$thumbname=$file['name'];				 
		}
			if(!file_exists($thumbfolder) OR !is_dir($thumbfolder)){
			    mkdir($thumbfolder);         
			 } 
	    if(move_uploaded_file($file['tmp_name'], $folder.$file['name'])){
	    	$msg["file"]="File has been successfully uploaded.";
	    	$msg["filepath"]=$folder.$file['name'];			    	
	    }else{
	    	return array("error"=>TRUE, "msg" => "Cannot upload the file.");
	    }				 				
	    if($thumb){
	    	if(!empty($thumbfolder)){
	    		$thumbnail = $this->thumb($folder.$file['name'],$thumbfolder.$thumbname, $thumbwidth);
	    	}else{
	    		$thumbnail = $this->thumb($folder.$file['name'],'', $thumbwidth);
	    	}
	    	if(!$thumbnail["error"]){
	    		$msg['thumb']=$thumbnail['msg'];
	    		$msg["thumbpath"]=$thumbfolder.$thumbname;	    		
	    	}
	    }
	    return $msg;
	}

	/**
	 *  Social Count
	 * @since 2.0
	 **/
	public function social($site='twitter',$url=''){
		if(empty($url)){
			$url="http://" . $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI'];
		}
		if($site=="facebook"){
			$content=@file_get_contents("http://graph.facebook.com/?id={$url}");
			$count=json_decode($content,true);
			return ($count["shares"]?$count["shares"]:0);
		}else{
			$content=@file_get_contents("http://cdn.api.twitter.com/1/urls/count.json?callback=?&url={$url}");
			$count=json_decode($content,true);
			return $count["count"];
		}
	}
  /**
  * Generated CSRF Token
  * @since 3.0
  */   
  public function csrf_token($form=FALSE){
      if($form && isset($_SESSION["_CSRF"])) return "<input type='hidden' name='_token' value='{$_SESSION["_CSRF"]}' />";      
      if(isset($_SESSION["_CSRF"])) return $_SESSION["_CSRF"];

      $token = $this->encode_password("csrf_token".rand(0,1000000).time().uniqid(),"SHA1");
      $_SESSION["_CSRF"] = $token;
    return $token;
  }
  /**
  * Validate CSRF Token
  * @since 3.0
  */   
  public function validate_csrf_token($token=""){
  	if(empty($token)) $token = $_POST["_token"];
    if(isset($_SESSION["_CSRF"]) && ($_SESSION["_CSRF"] == trim($token))) {
      unset($_SESSION["_CSRF"]);
      return TRUE;
    }
    return FALSE;
  }
  /**
   * Set and Read Cookie
   * @since 3.0
   */  
  public function cookie($name,$value="",$time=1, $ssl=FALSE){
    if(empty($value)){
      if(isset($_COOKIE[$name])){
        return $_COOKIE[$name];
      }else{
        return FALSE;
      }
    }
    setcookie($name,$value, time()+($time*60), "/","",$ssl,TRUE);
  }
	/**
   * Get IP
   * @since 3.0 
   **/
  public function ip(){
     $ipaddress = '';
      if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
          $ipaddress =  $_SERVER['HTTP_CF_CONNECTING_IP'];
      } else if (isset($_SERVER['HTTP_X_REAL_IP'])) {
          $ipaddress = $_SERVER['HTTP_X_REAL_IP'];
      }
      else if (isset($_SERVER['HTTP_CLIENT_IP']))
          $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
      else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
          $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
      else if(isset($_SERVER['HTTP_X_FORWARDED']))
          $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
      else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
          $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
      else if(isset($_SERVER['HTTP_FORWARDED']))
          $ipaddress = $_SERVER['HTTP_FORWARDED'];
      else if(isset($_SERVER['REMOTE_ADDR']))
          $ipaddress = $_SERVER['REMOTE_ADDR'];
      else
          $ipaddress = 'UNKNOWN';
      return $ipaddress;
  }
  /**
   * Validate URLs
   * @since 3.0
   **/
  public function checkurl($url){
    if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url) && filter_var($url, FILTER_VALIDATE_URL)){
      return true;
    }    
    return "Please enter a valid URL";     
  }
	/**
  * Redirect function
  * @param url/path (not including base), message and header code
  * @return nothing
  */   
    public function redirect($url,$message=array(),$header=""){      

      if(!empty($message)){      
        $this->setmsg($message[1],$message[0]);
      }
      switch ($header) {
        case '301':
          header('HTTP/1.1 301 Moved Permanently');
          break;
        case '404':
          header('HTTP/1.1 404 Not Found');
          break;
        case '503':
          header('HTTP/1.1 503 Service Temporarily Unavailable');
          header('Status: 503 Service Temporarily Unavailable');
          header('Retry-After: 60');
          break;
      }
      header("Location: $url");
      exit;
    }      
}