<?
/***************************************************************************
 *                          class.user.php
 *                            -------------------
 *   begin                : Saturday,10/07/03
 *   copyright            : (C) 2003  Peak Software
 *   email                : chris@peaksoftware.com.au

 ***************************************************************************/
class user {

	  var $user_id  ;
	  var $user_name ;
	  var $user_pass  ;
	  //var $user_access ;
	
	  var $user_edit_page = 1;
	  var $user_edit_menu= 1;
	  var $user_edit_groups= 1;
	  var $user_add_page= 1  ;
	
	  var $user_add_user= 1 ;
	
	  var $user_newsletter= 1 ;
	  var $user_newsletter_create= 1  ;
	  var $user_newsletter_subscribers= 1  ;
	
	  var $user_cart= 1;
	  var $user_cart_products= 1;
	  var $user_cart_customers= 1;
	  var $user_cart_orders= 1;
	
	  var $user_search= 1;
	  var $user_system= 1 ;
	  
	  var $user_support_lib= 1 ;
	  var $builds = 1 ;
	  var $user_progress_update= 1 ;	  


        function user() {
         
        }
        function add_user($user_id,$user_name,$user_pass,$user_edit_page,$user_edit_menu,$user_edit_groups,$user_add_page
		,$user_add_user,$user_newsletter,$user_newsletter_create,$user_newsletter_subscribers
		,$user_cart,$user_cart_products,$user_cart_customers,$user_cart_orders,$user_search,$user_system,$user_support_lib,$builds,$user_progress_update) {

                $this->user_id=$user_id;
                $this->user_name=$user_name;
				$this->user_pass=$user_pass;
				$this->user_edit_page=$user_edit_page;
				$this->user_edit_menu=$user_edit_menu;
				$this->user_edit_groups=$user_edit_groups;
				$this->user_add_page=$user_add_page;
				$this->user_add_user=$user_add_user;
				$this->user_newsletter=$user_newsletter;
				$this->user_newsletter_create=$user_newsletter_create;
				$this->user_newsletter_subscribers=$user_newsletter_subscribers;
				$this->user_cart=$user_cart;
				$this->user_cart_products=$user_cart_products;
				$this->user_cart_customers=$user_cart_customers;
				$this->user_cart_orders=$user_cart_orders;
				$this->user_search=$user_search;
				$this->user_system=$user_system;
				$this->user_support_lib=$user_support_lib;	
				$this->builds=$builds;	
				$this->user_progress_update=$user_progress_update;										
				

        }

        function get_id() {
                return $this->user_id;
        }
		function get_name() {
                return $this->user_name;
        }
		

		function get_user_pass() {
                return $this->user_pass;
        }
		
		function get_user_edit_page() {
                return $this->user_edit_page;
        }


		function get_user_edit_menu() {
                return $this->user_edit_menu;
        }


		function get_user_edit_groups() {
                return $this->user_edit_groups;
        }


		function get_user_add_page() {
                return $this->user_add_page;
        }


		function get_user_add_user() {
                return $this->user_add_user;
        }


		function get_user_newsletter() {
                return $this->user_newsletter;
        }


		function get_user_newsletter_create() {
                return $this->user_newsletter_create;
        }


		function get_user_newsletter_subscribers() {
                return $this->user_newsletter_subscribers;
        }


		function get_user_cart() {
                return $this->user_cart;
        }


		function get_user_cart_products() {
                return $this->user_cart_products;
        }


		function get_user_cart_customers() {
                return $this->user_cart_customers;
        }


		function get_user_cart_orders() {
                return $this->user_cart_orders;
        }


		function get_user_search() {
                return $this->user_search;
        }


		function get_user_system() {
                return $this->user_system;
        }
		function get_user_support_lib() {
                return $this->user_support_lib;
        }	
		
		function get_user_builds() {
                return $this->builds;
        }	
		function get_user_progress_update() {
                return $this->user_progress_update;
        }				
																														
		


}
?>