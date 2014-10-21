<?
/***************************************************************************
 *                          class.site_config.php
 *                            -------------------
 *   begin                : Saturday,10/07/03
 *   copyright            : (C) 2003  Peak Software
 *   email                : chris@peaksoftware.com.au

 ***************************************************************************/
class site {

	  var $site_name;
	  var $site_admin_email;
	  var $site_enable;
	  var $site_contact_form_email;	    
	  var $site_sale_confirmation_email;
	  var $site_email_error;
	  var $email_success;
	  var $site_email_subject;	  
	  var $site_subscribe_msg;	  
	  var $site_ssl_url;
	  var $site_url;
	  var $site_parse_file;
	  var $site_row_set;
	  var $encryption;
	  var $encryption_key;	   	  	  	  	 	
	  var $enable_site_stats;


        function site() {
         
        }
        function start_site($site_name,$site_admin_email,$site_enable,$site_contact_form_email,$site_sale_confirmation_email,$email_success,$site_email_error,$email_subject,$subscribe_msg,$ssl_url,$url,$parse_file,$row_set,$encryption,$encryption_key,$enable_site_stats) {

                $this->site_name=$site_name;
                $this->site_admin_email=$site_admin_email;
				$this->site_enable=$site_enable;

				$this->site_contact_form_email=$site_contact_form_email;
				$this->site_sale_confirmation_email=$site_sale_confirmation_email;
				$this->site_email_error=$site_email_error;
				$this->email_success=$email_success;
				$this->site_email_subject=$email_subject;
				$this->site_subscribe_msg=$subscribe_msg;
				$this->site_ssl_url=$ssl_url;
				$this->site_url=$url;	
				$this->site_parse_file=$parse_file;
				$this->site_row_set=$row_set;	
				$this->encryption=$encryption;
				$this->encryption_key=$encryption_key;
				$this->enable_site_stats=$enable_site_stats;			
																																		

        }
		
		

        function get_site_name() {
                return $this->site_name;
        }
		
		
		function get_site_admin_email() {
                return $this->site_admin_email;
        }
		

		function get_site_enable() {
                return $this->site_enable;
        }
		
		function get_site_contact_form_email() {
                return $this->site_contact_form_email;
        }


		function get_site_sale_confirmation_email() {
                return $this->site_sale_confirmation_email;
        }

        function get_email_success() {
                return $this->email_success;
        }

		function get_site_email_error() {
                return $this->site_email_error;
        }

		function get_site_email_subject() {
                return $this->site_email_subject;
        }

		function get_site_subscribe_msg() {
		return $this->site_subscribe_msg;
		}
				
																														
		function get_site_ssl() {
                return $this->site_ssl_url;
        }

		function get_site_url() {
				return $this->site_url;
		}
		
		function get_site_parse_file() {
				return $this->site_parse_file;
		}		
		
		function get_site_parse_url() {
				return $this->site_url.$this->site_parse_file;
		}
		function get_site_row_set() {
				return $this->site_row_set;
		}		
		function get_encryption() {
				return $this->encryption;
		}	
		function get_encryption_key() {
				return $this->encryption_key;
		}
		
		function get_enable_site_stats() {
				return $this->enable_site_stats;
		}								
						


}
?>