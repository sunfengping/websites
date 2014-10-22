<?php include ($_SERVER ['DOCUMENT_ROOT'] . "/header.php"); ?>
<?php 

	use \Curl\Curl;
	$curl = new Curl();
	$curl->setHeader ( 'API-KEY', $_SESSION ['token'] );
	
	if(!$_GET["page"])
	{
		$_GET["page"]=1;
	}
	
	$curl->get(REST_ENDPOINT.'News', array(
		'churchId' => $_SESSION ["church_id"] ,
		'page'=>$_GET["page"],
	));
	
	if ($curl->error) {
		echo 'Error: ' . $curl->error_code . ': ' . $curl->error_message;
	} else {
		$result=$curl->response;
		$newsList=$result[0];
		$page=$result[1];
	  }
	$page_url='/Modules/News/?'.$_SERVER['QUERY_STRING'];
	$page_url=preg_replace("/(^|&)page=[0-9]$/","",$ceshi_url);
	$page_url=preg_replace("/(^|)page=[0-9]$/","",$ceshi_url);
	if(strrpos ($page_url , "?" )>0)
	{
		if(strrpos ($page_url , "?" )!=strlen($page_url)-1)
		{
		  $page_url.="&page="; 
		}
		else
		{
		  $page_url.="page=";
		}
	}
	else
	{
	  $page_url.="?page=";
	}
?> 
<!-- Sub Banner -->      
<section class="sub-banner newsection">
    <div class="container">
        <h2 class="title">News Left Sidebar</h2>
        <ul class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li><a href="#">News</a></li>
            <li>News Left Sidebar</li>
        </ul>
    </div>
</section>

<!-- Events -->
<section class="events newsection">
    <div class="container">
        <div class="row">

            <div class="col-md-9 col-sm-9 pull-right">

                <div class="eventform-con fielder-items clearfix">

                    <form>

                        <div class="form-input">
                            <div class="styled-select">
                                <select>
                                    <option>Sort by: Default Sorting</option>
                                    <option>The second option</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-input arrow-up-down">
                            <button class="btn btn-pri"><i class="fa fa-arrow-up"></i></button>
                        </div>

                        <div class="form-input">
                            <div class="styled-select">
                                <select>
                                    <option>Show: 10 items / page</option>
                                    <option>The second option</option>
                                </select>
                            </div>
                        </div>

                    </form>
                    <!-- Event Filter -->
                    <ul class="event-filter">
                        <li class="filter stylelist"><i class=" fa fa-th-list"></i></li>
                        <li class="filter stylegrid"><i class=" fa fa-th"></i></li>
                    </ul>
                </div> 
                <!-- GridList & Itemlist -->

                <div class="grid-list event-container clearfix">
                    <div class="row">
                    <?php 
					 foreach ($newsList as $news)
					 {
					?>
                        <div class="event-border col-md-4">
                            <div class="event clearfix">
                                <div class="eventsimg">
                                   <img src="<?php echo $news->image_url; ?>" alt="">
                                </div>
                                <div class="event-content">
                                    <h3 class="title"><a href="/Modules/News/details/?id=<?php echo $news->id; ?>"><?php echo $news->title; ?> </a></h3>
                                    <ul class="meta">
                                        <li class="date"><i class="icon fa fa-calendar"></i> Feb 17-19, 2014</li><li><a href="#"><i class="icon fa fa-home"></i> Grand Hotel Califonria</a></li><li><a href="#"><i class="icon fa fa-map-marker"></i>Istanbul / Turkey</a></li>
                                    </ul>
                                  <div style="width:100%; height:120px; overflow:hidden;">
                                      <?php echo  substr($news->msg,0,210); ?>[...]
                                  </div>
                                  
                                   
                                    <a href="#" class="btn btn-pri">buy tıcket</a><a href="#" class="btn btn-border">FACEBOOK PAGE</a>
                                </div>

                                <div class="links clearfix">
                                    <ul>
                                        <li><a class='st_sharethis_large' displayText='ShareThis'><i class="icon fa fa-share"></i> share</a></li>
                                        <li><a href="#"><i class="icon fa fa-heart"></i>26</a></li>
                                        <li><a href="#" ><i class="icon fa fa-comment"></i>33</a> </li> 
                                    </ul> 
                                </div>
                            </div>
                        </div>
						<?php 
					         }
                        ?>
                       

                       

                       

                       
                    </div>
                </div> 
                <div style="clear:both; height:10px; width:100%;"></div>
                <!-- pagination -->
                <ul class="pagination clearfix">
                     <?php 
					  if($page->prepg) echo '<li class=""><a href="'.$page_url.$page->prepg.'"><i class="fa fa-angle-left"></i></a></li> ';
					  
					  for($i=1;$i<=$page->lastpg;$i++){
							if($i==$_GET["page"]) echo '<li class="active"><a href="'.$page_url.$i.'">'.$i.'</a></li>';
							else echo '<li><a href="'.$page_url.$i.'">'.$i.'</a></li>';
						}
						
                   	  if($page->nextpg) echo '<li class=""><a href="'.$page_url.$page->nextpg.'"><i class="fa fa-angle-right"></i></a></li>';
					
					?>
                </ul>

            </div> 
            <!-- col-md-3 -->
             <?php include ($_SERVER ['DOCUMENT_ROOT'] . "/newsMenu.php"); ?>

            </div> 
        </div> 
    </section>
    <!-- footer -->
  <?php include ($_SERVER ['DOCUMENT_ROOT'] . "/footer.php"); ?>