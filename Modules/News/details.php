<?php include ($_SERVER ['DOCUMENT_ROOT'] . "/header.php"); ?> 
<?php 

	use \Curl\Curl;
	$curl = new Curl();
	$curl->setHeader ( 'API-KEY', $_SESSION ['token'] );
	$curl->get(REST_ENDPOINT.'News/item', array(
		'churchId' => $_SESSION ["church_id"] ,
		'id'=>$_GET["id"],
	));
	
	if ($curl->error) {
		echo 'Error: ' . $curl->error_code . ': ' . $curl->error_message;
	} else {
		$result=$curl->response;
		$details=$result[0];
	  }

?> 
<!-- Sub-banner -->      
<section class="sub-banner newsection">
    <div class="container">
        <h2 class="title">News Detail</h2>
        <ul class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li><a href="#">News</a></li>
            <li>News Detail</li>
        </ul>
    </div>
</section>

<!-- Events -->
<section class="events text-left newsection">
    <div class="container">
        <div class="row">
            <!-- col-md-9 -->
            <div class="col-md-9 col-sm-9">
                <!--Event Detail  -->
                <section class="event-detail newsection">
                    <h2 class="main-title "><a href="#"><?php echo $details->title ?></a></h2>
                    <!-- meta -->
                    <ul class="meta clearfix">
                        <li class="date"><i class="icon fa fa-calendar"></i> <?php echo $details->date_created; ?></li>
                        <li><a href="#"><i class="icon fa fa-home"></i> Grand Hotel Califonria</a></li>
                        <li><a href="#"><i class="icon fa fa-map-marker"></i></a></li>
                    </ul>
                    <!-- event-detail-img -->
                    <div class="event-detail-img">
                    <?php 
					  if(isset($details->image_url))
					  {
					?>
                      <img src="<?php echo $details->image_url; ?>" alt="" width="870" height="460">
                    <?php 
					  } else {
					?>
                        <img src="/Content/img/870x460.gif" alt="">
                    <?php 
					  }
					?>
                    </div>

                    <h3 class="title">Whats About</h3>

                    <?php echo $details->msg ?>

                    <!-- Social Icon -->
                    <div class="social-icon">
                        <a href="#" class="facebook facebook-pagle">FACEBOOK PAGE</a>
                        <a href="#" class="facebook fa fa-facebook"></a>
                        <a href="#" class="twitter fa fa-twitter"></a>
                        <a href="#" class=" googleplus fa fa-google-plus"></a>
                        <a href="#" class="vimeo fa fa-vimeo-square"></a>
                        <a href="#" class="linkedin fa fa-linkedin"></a>
                    </div>
                </section>

                <!-- speakers-tabs -->
                <section class="speakers-tabs newsection">
                    <h2 class="title">Schedule with Speakers</h2>
                    <div id="speakers-tabs">    

                        <ul class="speaker-ul resp-tabs-list clearfix">
                            <li>Monday, 9th</li>
                            <li>Tuesday, 11th</li>
                            <li>Wednesday, 12th</li>
                            <li>Monday, 9th</li>
                        </ul>
                        <div class="resp-tabs-container">     
                            <div>
                                <div class="speakers"> 	
                                    <div class="speaker clearfix">
                                        <div class="speaker-img">
                                           <img src="/Content/img/515x390.gif" alt="">					                                    
                                        </div>
                                        <div class="speaker-content">
                                            <p class="author">Jane Crowley <span class="job">Web Expert</span></p>
                                            <h3 class="title"><a href="#">Using Social Services Increase Your Sales </a></h3>
                                            <ul class="meta clearfix">
                                                <li><i class="icon fa fa-times-circle-o"></i>08:00 to 18:00 pm</li>
                                                <li><a href="#"><i class="icon fa fa-map-marker"></i>Hall B</a></li>
                                            </ul>
                                            <p>Aliquam id metus purus. Aliquam ultricies massa a eros euismod mattis. Nunc commodo enim at commodo pellentesque. Etiam turpis eros, lobortis non libero vel, pharetra iaculis augue. [...] </p>
                                            <a href="#" class="btn btn-border">read more</a> <a href="#" class="btn btn-border">About Speaker</a>
                                        </div>
                                    </div>
                                    <div class="bar"><p><i class="icon fa fa-glass"></i>LETS HAVE A BREAK, ENJOY IT</p></div>
                                </div> 

                                <div class="speakers"> 	
                                    <div class="speaker clearfix">
                                        <div class="speaker-img">
                                           <img src="/Content/img/515x390.gif" alt="">				                                    
                                        </div>
                                        <div class="speaker-content">
                                            <p class="author">Yaris Crowley <span class="job">Web Expert</span></p>
                                            <h3 class="title"><a href="#">Using Social Services Increase Your Sales </a></h3>
                                            <ul class="meta clearfix">
                                                <li><i class="icon fa fa-times-circle-o"></i>08:00 to 18:00 pm</li>
                                                <li><a href="#"><i class="icon fa fa-map-marker"></i>Hall B</a></li>
                                            </ul>
                                            <p>Aliquam id metus purus. Aliquam ultricies massa a eros euismod mattis. Nunc commodo enim at commodo pellentesque. Etiam turpis eros, lobortis non libero vel, pharetra iaculis augue. [...] </p>
                                            <a href="#" class="btn btn-border">read more</a> <a href="#" class="btn btn-border">About Speaker</a>
                                        </div>
                                    </div>
                                    <div class="bar"><p><i class="icon fa fa-glass"></i>LETS HAVE A BREAK, ENJOY IT</p></div>
                                </div>

                                <div class="speakers"> 	
                                    <div class="speaker clearfix">
                                        <div class="speaker-img">
                                           <img src="/Content/img/515x390.gif" alt="">			                                    
                                        </div>
                                        <div class="speaker-content">
                                            <p class="author">Jane Crowley <span class="job">Web Expert</span></p>
                                            <h3 class="title"><a href="#">Using Social Services Increase Your Sales </a></h3>
                                            <ul class="meta clearfix">
                                                <li><i class="icon fa fa-times-circle-o"></i>08:00 to 18:00 pm</li>
                                                <li><a href="#"><i class="icon fa fa-map-marker"></i>Hall B</a></li>
                                            </ul>
                                            <p>Aliquam id metus purus. Aliquam ultricies massa a eros euismod mattis. Nunc commodo enim at commodo pellentesque. Etiam turpis eros, lobortis non libero vel, pharetra iaculis augue. [...] </p>
                                            <a href="#" class="btn btn-border">read more</a> <a href="#" class="btn btn-border">About Speaker</a>
                                        </div>
                                    </div>
                                    <div class="bar"><p><i class="icon fa fa-glass"></i>LETS HAVE A BREAK, ENJOY IT</p></div>
                                </div>

                                <div class="speakers"> 	
                                    <div class="speaker clearfix">
                                        <div class="speaker-img">
                                           <img src="/Content/img/515x390.gif" alt="">					                                    
                                        </div>
                                        <div class="speaker-content">
                                            <p class="author">Yaris Crowley <span class="job">Web Expert</span></p>
                                            <h3 class="title"><a href="#">Using Social Services Increase Your Sales </a></h3>
                                            <ul class="meta clearfix">
                                                <li><i class="icon fa fa-times-circle-o"></i>08:00 to 18:00 pm</li>
                                                <li><a href="#"><i class="icon fa fa-map-marker"></i>Hall B</a></li>
                                            </ul>
                                            <p>Aliquam id metus purus. Aliquam ultricies massa a eros euismod mattis. Nunc commodo enim at commodo pellentesque. Etiam turpis eros, lobortis non libero vel, pharetra iaculis augue. [...] </p>
                                            <a href="#" class="btn btn-border">read more</a> <a href="#" class="btn btn-border">About Speaker</a>
                                        </div>
                                    </div>
                                    <div class="bar">
                                        <p><i class="icon fa fa-glass"></i>LETS HAVE A BREAK, ENJOY IT</p>
                                    </div>
                                </div>
                            </div> 

                            <div>
                                <div class="speakers">  
                                    <div class="speaker clearfix">
                                        <div class="speaker-img">
                                           <img src="/Content/img/515x390.gif" alt="">                                                     
                                        </div>
                                        <div class="speaker-content">
                                            <p class="author">Jane Crowley <span class="job">Web Expert</span></p>
                                            <h3 class="title"><a href="#">Using Social Services Increase Your Sales </a></h3>
                                            <ul class="meta clearfix">
                                                <li><i class="icon fa fa-times-circle-o"></i>08:00 to 18:00 pm</li>
                                                <li><a href="#"><i class="icon fa fa-map-marker"></i>Hall B</a></li>
                                            </ul>
                                            <p>Aliquam id metus purus. Aliquam ultricies massa a eros euismod mattis. Nunc commodo enim at commodo pellentesque. Etiam turpis eros, lobortis non libero vel, pharetra iaculis augue. [...] </p>
                                            <a href="#" class="btn btn-border">read more</a> <a href="#" class="btn btn-border">About Speaker</a>
                                        </div>
                                    </div>
                                    <div class="bar"><p><i class="icon fa fa-glass"></i>LETS HAVE A BREAK, ENJOY IT</p></div>
                                </div> 
                                <div class="speakers">  
                                    <div class="speaker clearfix">
                                        <div class="speaker-img">
                                           <img src="/Content/img/515x390.gif" alt="">                                                  
                                        </div>
                                        <div class="speaker-content">
                                            <p class="author">Yaris Crowley <span class="job">Web Expert</span></p>
                                            <h3 class="title"><a href="#">Using Social Services Increase Your Sales </a></h3>
                                            <ul class="meta clearfix">
                                                <li><i class="icon fa fa-times-circle-o"></i>08:00 to 18:00 pm</li>
                                                <li><a href="#"><i class="icon fa fa-map-marker"></i>Hall B</a></li>
                                            </ul>
                                            <p>Aliquam id metus purus. Aliquam ultricies massa a eros euismod mattis. Nunc commodo enim at commodo pellentesque. Etiam turpis eros, lobortis non libero vel, pharetra iaculis augue. [...] </p>
                                            <a href="#" class="btn btn-border">read more</a> <a href="#" class="btn btn-border">About Speaker</a>
                                        </div>
                                    </div>
                                    <div class="bar"><p><i class="icon fa fa-glass"></i>LETS HAVE A BREAK, ENJOY IT</p></div>
                                </div>

                                <div class="speakers">  
                                    <div class="speaker clearfix">
                                        <div class="speaker-img">
                                           <img src="/Content/img/515x390.gif" alt="">                                              
                                        </div>
                                        <div class="speaker-content">
                                            <p class="author">Jane Crowley<span class="job">Web Expert</span></p>
                                            <h3 class="title"><a href="#">Using Social Services Increase Your Sales </a></h3>
                                            <ul class="meta clearfix">
                                                <li><i class="icon fa fa-times-circle-o"></i>08:00 to 18:00 pm</li>
                                                <li><a href="#"><i class="icon fa fa-map-marker"></i>Hall B</a></li>
                                            </ul>
                                            <p>Aliquam id metus purus. Aliquam ultricies massa a eros euismod mattis. Nunc commodo enim at commodo pellentesque. Etiam turpis eros, lobortis non libero vel, pharetra iaculis augue. [...] </p>
                                            <a href="#" class="btn btn-border">read more</a> <a href="#" class="btn btn-border">About Speaker</a>
                                        </div>
                                    </div>
                                    <div class="bar"><p><i class="icon fa fa-glass"></i>LETS HAVE A BREAK, ENJOY IT</p></div>
                                </div>

                                <div class="speakers">  
                                    <div class="speaker clearfix">
                                        <div class="speaker-img">
                                           <img src="/Content/img/515x390.gif" alt="">                                                       
                                        </div>
                                        <div class="speaker-content">
                                            <p class="author">Jane Crowley <span class="job"> Web Expert</span></p>
                                            <h3 class="title"><a href="#">Using Social Services Increase Your Sales </a></h3>
                                            <ul class="meta clearfix">
                                                <li><i class="icon fa fa-times-circle-o"></i>08:00 to 18:00 pm</li>
                                                <li><a href="#"><i class="icon fa fa-map-marker"></i>Hall B</a></li>
                                            </ul>
                                            <p>Aliquam id metus purus. Aliquam ultricies massa a eros euismod mattis. Nunc commodo enim at commodo pellentesque. Etiam turpis eros, lobortis non libero vel, pharetra iaculis augue. [...] </p>
                                            <a href="#" class="btn btn-border">read more</a> <a href="#" class="btn btn-border">About Speaker</a>
                                        </div>
                                    </div>
                                    <div class="bar">
                                        <p><i class="icon fa fa-glass"></i>LETS HAVE A BREAK, ENJOY IT</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="speakers">  
                                    <div class="speaker clearfix">
                                        <div class="speaker-img">
                                           <img src="/Content/img/515x390.gif" alt="">                                                     
                                        </div>
                                        <div class="speaker-content">
                                            <p class="author">Jane Crowley <span class="job">Web Expert</span></p>
                                            <h3 class="title"><a href="#">Using Social Services Increase Your Sales </a></h3>
                                            <ul class="meta clearfix">
                                                <li><i class="icon fa fa-times-circle-o"></i>08:00 to 18:00 pm</li>
                                                <li><a href="#"><i class="icon fa fa-map-marker"></i>Hall B</a></li>
                                            </ul>
                                            <p>Aliquam id metus purus. Aliquam ultricies massa a eros euismod mattis. Nunc commodo enim at commodo pellentesque. Etiam turpis eros, lobortis non libero vel, pharetra iaculis augue. [...] </p>
                                            <a href="#" class="btn btn-border">read more</a> <a href="#" class="btn btn-border">About Speaker</a>
                                        </div>
                                    </div>
                                    <div class="bar"><p><i class="icon fa fa-glass"></i>LETS HAVE A BREAK, ENJOY IT</p></div>
                                </div> 
                                <div class="speakers">  
                                    <div class="speaker clearfix">
                                        <div class="speaker-img">
                                           <img src="/Content/img/515x390.gif" alt="">                                                  
                                        </div>
                                        <div class="speaker-content">
                                            <p class="author">Yaris Crowley <span class="job"> Web Expert</span></p>
                                            <h3 class="title"><a href="#">Using Social Services Increase Your Sales </a></h3>
                                            <ul class="meta clearfix">
                                                <li><i class="icon fa fa-times-circle-o"></i>08:00 to 18:00 pm</li>
                                                <li><a href="#"><i class="icon fa fa-map-marker"></i>Hall B</a></li>
                                            </ul>
                                            <p>Aliquam id metus purus. Aliquam ultricies massa a eros euismod mattis. Nunc commodo enim at commodo pellentesque. Etiam turpis eros, lobortis non libero vel, pharetra iaculis augue. [...] </p>
                                            <a href="#" class="btn btn-border">read more</a> <a href="#" class="btn btn-border">About Speaker</a>
                                        </div>
                                    </div>
                                    <div class="bar"><p><i class="icon fa fa-glass"></i>LETS HAVE A BREAK, ENJOY IT</p></div>
                                </div>

                            </div>

                            <div>
                                <div class="speakers">  
                                    <div class="speaker clearfix">
                                        <div class="speaker-img">
                                           <img src="/Content/img/515x390.gif" alt="">                                                       
                                        </div>
                                        <div class="speaker-content">
                                            <p class="author">Jane Crowley <span class="job">Web Expert</span></p>
                                            <h3 class="title"><a href="#">Using Social Services Increase Your Sales </a></h3>
                                            <ul class="meta clearfix">
                                                <li><i class="icon fa fa-times-circle-o"></i>08:00 to 18:00 pm</li>
                                                <li><a href="#"><i class="icon fa fa-map-marker"></i>Hall B</a></li>
                                            </ul>
                                            <p>Aliquam id metus purus. Aliquam ultricies massa a eros euismod mattis. Nunc commodo enim at commodo pellentesque. Etiam turpis eros, lobortis non libero vel, pharetra iaculis augue. [...] </p>
                                            <a href="#" class="btn btn-border">read more</a> <a href="#" class="btn btn-border">About Speaker</a>
                                        </div>
                                    </div>
                                    <div class="bar">
                                        <p><i class="icon fa fa-glass"></i>LETS HAVE A BREAK, ENJOY IT</p>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>   

                </section> 
                <!-- Speaker Event -->
                <section class="speaker-event newsection">
                    <h2 class="title">Speakers of Event </h2>

                    <!-- owl slider  -->
                    <div class="owl-team">

                        <div class="event">
                            <div class="eventsimg">
                                <img src="/Content/img/515x390.gif" alt="">
                            </div>
                            <div class="event-content">
                                <h3 class="title">Yaris Crowley</h3>
                                <p class="job">Ceo on Google Inc</p>
                                <p>Lorem ipsum dolor sit amet, feugiat delicata id cum, eu sit. [...] </p>

                            </div>
                            <div class="social-icon">
                                <a href="#" class="email fa fa-envelope-o"></a><a href="#" class="facebook fa fa-facebook"></a><a href="#" class="fa linkedin fa-linkedin"></a><a href="#" class="googleplus fa fa-google-plus"></a><a href="#" class="twitter fa fa-twitter"></a>

                            </div>
                        </div>



                        <div class="event">
                            <div class="eventsimg">
                                <img src="/Content/img/515x390.gif" alt="">
                            </div>
                            <div class="event-content">
                                <h3 class="title">Jane Crowley</h3>
                                <p class="job">Ceo on Google Inc</p>
                                <p>Lorem ipsum dolor sit amet, feugiat delicata id cum, eu sit. [...] </p>

                            </div>
                            <div class="social-icon">
                                <a href="#" class="email fa fa-envelope-o"></a><a href="#" class="facebook fa fa-facebook"></a><a href="#" class="fa linkedin fa-linkedin"></a><a href="#" class="googleplus fa fa-google-plus"></a><a href="#" class="twitter fa fa-twitter"></a>

                            </div>
                        </div>



                        <div class="event">
                            <div class="eventsimg">
                                <img src="/Content/img/515x390.gif" alt="">
                            </div>
                            <div class="event-content">
                                <h3 class="title">Yaris Crowley</h3>
                                <p class="job">Ceo on Google Inc</p>
                                <p>Lorem ipsum dolor sit amet, feugiat delicata id cum, eu sit. [...] </p>

                            </div>
                            <div class="social-icon">
                                <a href="#" class="email fa fa-envelope-o"></a><a href="#" class="facebook fa fa-facebook"></a><a href="#" class="fa linkedin fa-linkedin"></a><a href="#" class="googleplus fa fa-google-plus"></a><a href="#" class="twitter fa fa-twitter"></a>
                            </div>
                        </div>



                        <div class="event">
                            <div class="eventsimg">
                                <img src="/Content/img/515x390.gif" alt="">
                            </div>
                            <div class="event-content">
                                <h3 class="title">Jane Crowley</h3>
                                <p class="job">Ceo on Google Inc</p>
                                <p>Lorem ipsum dolor sit amet, feugiat delicata id cum, eu sit. [...] </p>

                            </div>
                            <div class="social-icon">
                                <a href="#" class="email fa fa-envelope-o"></a><a href="#" class="facebook fa fa-facebook"></a><a href="#" class="fa linkedin fa-linkedin"></a><a href="#" class="googleplus fa fa-google-plus"></a><a href="#" class="twitter fa fa-twitter"></a>
                            </div>
                        </div>
                    </div>  
                </section>
                <!-- sponsored -->
                <section class="sponsored newsection">
                    <h2 class="title">Sponsored by</h2>
                    <div class=" owl-sponsored">

                        <a href="#" class="sponsored-logo">
                            <img src="/Content/img/120x40.gif" alt="">
                        </a>

                        <a href="#" class="sponsored-logo">
                            <img src="/Content/img/120x40.gif" alt="">
                        </a>

                        <a href="#" class="sponsored-logo">
                            <img src="/Content/img/120x40.gif" alt="">
                        </a>

                        <a href="#" class="sponsored-logo">
                            <img src="/Content/img/120x40.gif" alt="">
                        </a>

                        <a href="#" class="sponsored-logo">
                            <img src="/Content/img/120x40.gif" alt="">
                        </a>

                        <a href="#" class="sponsored-logo">
                            <img src="/Content/img/120x40.gif" alt="">
                        </a>

                        <a href="#" class="sponsored-logo">
                            <img src="/Content/img/120x40.gif" alt="">
                        </a>

                        <a href="#" class="sponsored-logo">
                            <img src="/Content/img/120x40.gif" alt="">
                        </a>
                    </div>
                </section> 
                <!-- Event Gllery -->
                <section class="event-gallery newsection">
                    <h2 class="title">Gallery of Event</h2>
                    <div class="owl-team">

                        <div class="event-gallery-content">
                            <div class="gallery-event-img">
                                <img src="/Content/img/515x390.gif" alt="">
                            </div>
                            <div class="content">
                                <h3 class="title">Lodem Ipsum Dolor SIT</h3>
                                <p>Conference</p>
                            </div>
                        </div>	

                        <div class="event-gallery-content">
                            <div class="gallery-event-img">
                                <img src="/Content/img/515x390.gif" alt="">
                            </div>
                            <div class="content">
                                <h3 class="title">Lodem Ipsum Dolor SIT</h3>
                                <p>Conference</p>
                            </div>
                        </div>	

                        <div class="event-gallery-content">
                            <div class="gallery-event-img">
                                <img src="/Content/img/515x390.gif" alt="">
                            </div>
                            <div class="content">
                                <h3 class="title">Lodem Ipsum Dolor SIT</h3>
                                <p>Conference</p>
                            </div>
                        </div>	

                        <div class="event-gallery-content">
                            <div class="gallery-event-img">
                                <img src="/Content/img/515x390.gif" alt="">
                            </div>
                            <div class="content">
                                <h3 class="title">Lodem Ipsum Dolor SIT</h3>
                                <p>Conference</p>
                            </div>
                        </div>	

                        <div class="event-gallery-content">
                            <div class="gallery-event-img">
                                <img src="/Content/img/515x390.gif" alt="">
                            </div>
                            <div class="content">
                                <h3 class="title">Lodem Ipsum Dolor SIT</h3>
                                <p>Conference</p>
                            </div>
                        </div>	

                    </div>
                </section>

                <!--Ricket Resgister  -->
                <section class="ticket-resgister">
                    <h2 class="title">Get Tickets and Register Now</h2>
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <div class="ticket clearfix">
                                <h3 class="title">one day tıcket</h3>
                                <div class="ticket-value"><span>$ 29</span></div>
                                <ul>
                                    <li>Full-access to all sessions</li>
                                    <li>Free wifi at the conference</li>
                                    <li>Conference plus lunch coffee/snacks</li>
                                </ul>
                                <a href="#" class="btn btn-pri"><i class="button-icon fa fa-arrow-circle-o-down"></i>Regıster</a>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-4">
                            <div class="ticket clearfix">
                                <h3 class="title">two day tıcket</h3>
                                <div class="ticket-value"><span>$ 39</span></div>
                                <ul>
                                    <li>Full-access to all sessions</li>
                                    <li>Free wifi at the conference</li>
                                    <li>Conference plus lunch coffee/snacks</li>
                                </ul>
                                <a href="#" class="btn btn-pri"><i class="button-icon fa fa-arrow-circle-o-down"></i>Regıster</a>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-4">
                            <div class="ticket clearfix">
                                <h3 class="title">All day tıcket</h3>
                                <div class="ticket-value"><span>$ 49</span></div>
                                <ul>
                                    <li>Full-access to all sessions</li>
                                    <li>Free wifi at the conference</li>
                                    <li>Conference plus lunch coffee/snacks</li>
                                </ul>
                                <a href="#" class="btn btn-pri"><i class="button-icon fa fa-arrow-circle-o-down"></i>Regıster</a>
                            </div>
                        </div>
                    </div>

                    <a href="#" class="btn btn-pri btn-lg">seating is limited ‒ register now</a>
                </section>
            </div>
            <!-- Col-md-3 -->
            <div class="col-md-3 col-sm-3">
                <aside id="aside" class="aside-bar-style-two clearfix">
                        
                    <div class="widget border-remove">
                               <div id="contact-map" class="map"></div>

                               <div class="clearfix">
                                <div class="main-example">
                                    <div class="countdown-container" id="upcomeing-events"></div>
                                </div>
                            </div>
                            <a href="#" class="btn btn-black btn-full"><i class="icon fa fa-heart"> </i>Add wishlist</a>
                            <a href="#" class="btn btn-Gradient-pri btn-full"> buy tıcket</a>
                        </div>


                    <div class="widget clearfix">
                        <h3 class="title">Top Popular Post</h3>
                        <div class="top-ppost">
                            <div class="date">
                                <p><span>31</span>FEB</p>
                            </div>
                            <div class="content">
                                <h4  class="title"><a href="#">Using Social Services Increase Your Sales </a></h4>
                                <a href="#" class="meta"><i class="icon fa fa-map-marker"></i>Istanbul / TR</a>
                            </div>
                        </div>

                        <div class="top-ppost">
                            <div class="date">
                                <p><span>31</span>FEB</p>
                            </div>
                            <div class="content">
                                <h4  class="title"><a href="#">Using Social Services Increase Your Sales </a></h4>
                                <a href="#" class="meta"><i class="icon fa fa-map-marker"></i>Istanbul / TR</a>
                            </div>
                        </div>
                    </div>


                        <div class="widget tag">
                            <h3 class="title">Tags Widget</h3>  
                            <a href="#">Event</a><a href="#">Fashion</a><a href="#">Design</a><a href="#">Hotels</a><a href="#">Up Coming</a><a href="#">Venue</a><a href="#">Speaker</a><a href="#" class="last">Conference</a>
                        </div>  


                        <div class="widget news">
                            <h3 class="title">Join Our Newsletter</h3>
                            <form>
                                <div class="form-group">
                                    <input type="text" placeholder="Select Location" >
                                    <button class="icon fa fa-paper-plane-o "></button>
                                </div>
                                <button class="btn btn-disabled">Sign Up</button>
                            </form>
                        </div>

                        <div class="widget">
                            <h3 class="title">Organizer</h3>
                            <p>Nullam facilisis metus quis nunc rhoncus fringilla. Donec nec nisl .</p>

                            <a href="#" class="btn btn-black contact-button"><i class="button-icon fa fa-envelope-o"></i>Contact the Organizer</a>
                            <ul class="social-icon">
                                <li class="email"><a href="#"><i class=" icon fa fa-user"></i><div class="content">View Profile of EventOrganizer</div></a>
                                </li>
                                <li class="facebook"><a href="#"><i class="icon fa fa-facebook"></i><div class="content">facebook.com/EventOrganizer</div></a>
                                </li>
                                <li class="twitter"><a href="#"><i class=" icon fa fa-twitter"></i><div class="content">twitter.com/EventOrganizer</div></a>
                                </li>
                            </ul>
                        </div>

                        <div class="widget">
                            <h3 class="title">Need a Event Manager</h3>
                            <p>Aliquam id metus purus. Etiam turpis eros, lobortis non libero vel, pharetra iaculis augue. [...] </p>
                            <ul class="widget-list">
                                <li><span class="blod">phone:</span> +90 555 55 55</li>
                                <li><span class="blod">Email:<a href="#"> info@example.com</a></li>
                            </ul>
                        </div>

                    </aside>
                </div>
            </div> 
        </div> 
</section>
<!-- Footer -->
<footer class="main-footer">
        <div class="container">
            <div class="row">   
                <div class="widget col-md-3">
                    <div class="about">
                        <h2 class="title">About <span class="border"></span></h2>
                        <p>Vivamus ante nulla, ultrices ut molestie pellentesque, posuere eu ligula. In porttitor in turpis eu porttitor. </p> 
                    </div>
                    <ul class="fa-ul">
                        <li><i class="fa-li fa fa-map-marker"></i>1600 Pennsylvania Ave NW,
                            Washington, D.C., DC 20500, ABD</li>
                            <li> <i class="fa-li fa fa-phone fa-flip-horizontal"></i>+90 555 55 55</li>
                            <li><i class="fa-li fa fa-envelope-o "></i><a href="#">info@example.com</a></li>
                        </ul>           
                    </div>

                    <div class="widget col-md-3">
                        <h2 class="title">Recent Blog Posts<span class="border"></span></h2>
                        <div class="recent-blog">
                            <div class="recent-img">
                               <img src="/Content/img/70x70.gif" alt="">
                            </div>
                            <div class="recent-content">
                                <h3 class="title"><a href="#"> Lorem ipsum dolor sit amet consectetur. </a> </h3>
                                <p class="date"><i class="icon fa fa-calendar"></i>October 24th, 2013</p>
                                <p class="comment"><i class="icon fa fa-comment"></i><a href="#">23 Comments</a></p>
                            </div>
                        </div>

                        <div class="recent-blog">
                            <div class="recent-img">
                               <img src="/Content/img/70x70.gif" alt="">
                            </div>
                            <div class="recent-content">
                                <h3 class="title"><a href="#"> Lorem ipsum dolor sit amet consectetur. </a> </h3>
                                <p class="date"><i class="icon fa fa-calendar"></i>October 24th, 2013</p>
                                <p class="comment"><i class="icon fa fa-comment"></i><a href="#">23 Comments</a></p>
                            </div>
                        </div>

                        <div class="recent-blog">
                            <div class="recent-img">
                               <img src="/Content/img/70x70.gif" alt="">
                            </div>
                            <div class="recent-content">
                                <h3 class="title"><a href="#"> Lorem ipsum dolor sit amet consectetur. </a> </h3>
                                <p class="date"><i class="icon fa fa-calendar"></i>October 24th, 2013</p>
                                <p class="comment"><i class="icon fa fa-comment"></i><a href="#">23 Comments</a></p>
                            </div>
                        </div>
                    </div>

                    <div class="widget lastest-tweets col-md-3">
                        <h2 class="title">Lastest Tweets<span class="border"></span></h2>
                        <ul class="fa-ul twitters"></ul>
                            </div>

                            <div class="widget col-md-3">
                                <h2 class="title">Flickr Photos<span class="border"></span></h2>
                                 <div class="flicker flickrwidget"></div>  
                            </div>

                        </div> 
                    </div>
                </footer> 

<script src="/Content/js/vendor/jquery-1.10.2.min.js"></script>
<script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script src="/Content/js/plugins.js"></script>
<script src="/Content/js/main.js"></script>

<script type="text/template" id="upcomeing-events-template">

    <div class="time-count-container">
        <div class="time days">
          <span class="count curr top"><%= curr.days %></span>
          <span class="count next top"><%= next.days %></span>
          <span class="count curr bottom"><%= curr.days %></span>
          <span class="count next bottom"><%= next.days %></span>
          <span class="label">days</span>
      </div>
      <span class="arrow">:</span>
  </div>

  <div class="time-count-container">
    <div class="time hours">
      <span class="count curr top"> <%= curr.hours %></span>
      <span class="count next top"><%= next.hours %></span>
      <span class="count curr bottom"><%= curr.hours %></span>
      <span class="count next bottom"><%= next.hours %></span>
      <span class="label">hours</span>
  </div>
  <span class="arrow">:</span>
</div>
<div class="time-count-container">
    <div class="time minutes">
      <span class="count curr top"><%= curr.minutes %></span>
      <span class="count next top"><%= next.minutes %></span>
      <span class="count curr bottom"><%= curr.minutes %></span>
      <span class="count next bottom"><%= next.minutes %></span>
      <span class="label">min</span>
  </div>
  <span class="arrow">:</span>
</div>
<div class="time-count-container">
    <div class="time seconds">
      <span class="count curr top"><%= curr.seconds %></span>
      <span class="count next top"><%= next.seconds %></span>
      <span class="count curr bottom"><%= curr.seconds %></span>
      <span class="count next bottom"><%= next.seconds %></span>
      <span class="label">sec</span>
  </div>

</div>
</script>

        </body>
        </html>
