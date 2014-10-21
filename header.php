<?php 
 @session_start();
 error_reporting(E_ALL & ~E_NOTICE);
 include ($_SERVER['DOCUMENT_ROOT']."/class/class.Curl.php");
 include ($_SERVER['DOCUMENT_ROOT']."/class/class.mysql.php");
 include ($_SERVER['DOCUMENT_ROOT']."/Config.php");
 include ($_SERVER['DOCUMENT_ROOT']."/class/class.pageLink.php");
 
 $myauth = new mysql();
 $_SESSION['church_id'] = 587; 
 $_SESSION['token'] = $myauth->single_query ( "SELECT token FROM church WHERE id = ".$_SESSION['church_id']);
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Event On</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,900,300italic,400italic,600italic,700italic' rel='stylesheet' type='text/css'>
     <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="apple-touch-icon" href="apple-touch-icon-precomposed.png">
    <link rel="shortcut icon" href="favicon.png">

    <link rel="stylesheet" type="text/css" href="/Content/css/jquery.datetimepicker.css">
    <link rel="stylesheet" type="text/css" href="/Content/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/Content/css/bootstrap.min.css">
   
    <link rel="stylesheet" type="text/css" href="/Content/css/owl.carousel.css">
    <link rel="stylesheet" href="/Content/css/main.css">
    <script src="/Content/js/vendor/modernizr-2.6.2.min.js"></script>
    <script type="text/javascript">var switchTo5x=true;</script>
    <script type="text/javascript" src="https://w.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({publisher: "ur-b4964695-8b2f-20dd-2ced-c9f6141de24c", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
    <script src="/Content/js/vendor/jquery-1.10.2.min.js"></script>
	<script src="/Content/js/plugins.js"></script>
    <script src="/Content/js/main.js"></script>
</head>
<body>
<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<!-- Header -->
<header class="header-container">
    <!-- Header Top -->
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <ul class="login-details clearfix">
                        <li><a href="#" class="agenticon">Agent Login</a></li>
                        <li><a href="#" class="customericon">Customer Login</a></li>
                        <li><a href="#" class="membericon">Not a Member?</a></li>
                        <li><a href="#" class="pri-color">Register Now</a></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <div class="social-icon pull-right">
                        <a href="#" class="facebook fa fa-facebook"></a>
                        <a href="#" class="twitter fa fa-twitter"></a>
                        <a href="#" class="googleplus fa fa-google-plus"></a>
                        <a href="#" class="dribble fa fa-dribbble"> </a>
                    </div>
                </div>
            </div> 
        </div>     
    </div>
        <!-- Main Header -->
    <div class="main-header affix">
        <!-- moblie Nav wrapper -->
        <div class="mobile-nav-wrapper">
            <div class="container">
                <!-- logo -->
               <div id="logo">
                <a href="index.htm"><img src="/Content/img/logo.png" alt=""></a> 
                </div>
                <!-- Search  -->
                <div id="sb-search" class="sb-search">
                    <form>
                        <input class="sb-search-input" placeholder="Search" type="text" name="search" id="search">
                        <input class="sb-search-submit" type="submit" value="">
                        <span class="sb-icon-search"></span>
                    </form>
                </div>
                <!-- Moblie Menu Icon -->
                <div class="mobile-menu-icon">
                    <i class="fa fa-bars"></i>
                </div> 
                <!-- main Nav -->
                <nav class="main-nav mobile-menu">

                    <ul class="clearfix">
                        <li class="parent "><a href="#">Home</a>
                            <!-- sub menu -->
                            <ul class="sub-menu">
                                <li class="arrow"></li>
                                <li><a href="index.html">Home-1</a></li> <li><a href="index-2.html">Home-2</a></li>
                                <li><a href="index-3.html">Home-3</a></li>
                                <li><a href="index-4.html">Home-4</a></li>
                                <li><a href="index-5.html">Home-5</a></li>
                                <li><a href="index-6.html">Home-6</a></li>
                            </ul>
                        </li>
                        <li class="parent  <?php if(strstr ( $_SERVER ["REQUEST_URI"], "Modules/News/" )){echo "active";}?>"><a href="/Modules/News/">News</a>
                             <ul class="sub-menu">
                                <li class="arrow"></li>
                                <li><a href="index.html">News-1</a></li> 
                                <li><a href="index-2.html">News-2</a></li>
                            </ul>
                        </li>

                        <li class="parent <?php if(strstr ( $_SERVER ["REQUEST_URI"], "Modules/Events/" )){echo "active";}?>"><a href="/Modules/Events/">Events</a>
                            <!-- sub menu -->
                            <ul class="sub-menu">
                                <li class="arrow"></li>
                                <li><a href="event.html">Event</a></li>
                                <li><a href="event-sidebar-left.html">Event-Sidebar-Left</a></li>
                                <li><a href="event-sidebar-right.html">Event-Sidebar-Right</a></li>
                                <li><a href="event-detail.html">Event Detail</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Directions</a></li>
                        <li class="parent"><a href="event-blog.html">Blog</a>
                            <!-- sub menu -->
                            <ul class="sub-menu">
                                <li class="arrow"></li>
                                <li><a href="event-blog.html">Event Blog</a></li>
                                <li><a href="event-single-blog.html">Event Single-Blog</a></li>
                            </ul>
                        </li>
                        <li><a href="gallery.html">Gallery</a></li>
                        <li><a href="contact.html">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>  
</header> 