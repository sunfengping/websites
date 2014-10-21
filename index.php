<? if(isset($_GET['logout'])){

    @session_start() ;
    @session_destroy() ;  

    session_start();
    session_destroy();  

}
?>
<!DOCTYPE html>
<!--[if lt IE 7]>  <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->

<html>
<!--<![endif]-->

<head>
    <meta charset="utf-8">

    <title>App Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="description" content="">
    <meta name="author" content=""><!-- Custom styles -->

    <style type="text/css">
.signin-content {
    max-width: 360px;
    margin: 0 auto 20px;
    }
    html, body {
    position: relative;
    height: 100%;
    padding: 0 !important;
    margin: 0 !important;
    background: url(Background/bg.jpg) !important ;
    background-size: cover !important;
    -webkit-transform: translate3d ( 0 , 0 , 0 );
    }
    .main-content{
    background: none !important;

    }

    </style><!-- Le styles -->
    <link href="Content/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="Content/css/bootstrap-responsive.css" rel="stylesheet" type="text/css">
    <link href="Content/css/extension.css" rel="stylesheet" type="text/css">
    <link href="Content/css/main.css" rel="stylesheet" type="text/css">
    <link href="Content/css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="Content/Ladda/css/ladda-themeless.min.css" type="text/css">
    <script src="Content/Ladda/dist/spin.min.js" type="text/javascript">
</script>
    <script src="Content/Ladda/dist/ladda.min.js" type="text/javascript">
</script><!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <script src="Content/libs/selectivizr/selectivizr-1.0.2/js/selectivizr.min.js"></script>
        <script src="Content/libs/pl-visualization/excanvas/js/excanvas.min.js"></script>
    <![endif]-->

    <script src="Content/libs/modernizr/modernizr-2.6.2/js/modernizr-2.6.2.js" type="text/javascript">
</script><!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="Content/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="Content/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="Content/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="Content/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="Content/ico/apple-touch-icon-57-precomposed.png">
</head>

<body class="signin signin-vertical">
    <div class="page-container">
        <div id="main-container">
            <div id="main-content" class="main-content container">
                <div class="signin-content">
                    <img src="/Background/WekonnectLogo.png" width='280' style="margin-bottom:0px;margin-top:20px">

                    <h1 class="welcome text-center" style="line-height: 0.6;"></h1>

                    <div class="well  form-dark">
                        <div class="tab-content overflow">
                            <div class="tab-pane fade in active" id="login">
                                <h3 class="no-margin-top opaci35">Sign in</h3>

                                <div class="alert alert-warning sign-error" role="alert" style="display:none">
                                    Oops! Password or email was incorrect.<br>
                                    Please try again!
                                </div>

                                <form class="form-tied margin-00" method="post" action="dashboard-one.html" id="login_form">
                                    <fieldset>
                                        <ul>
                                            <li><input id="email" class="input-block-level" type="text" name="email" placeholder="your ID or email"></li>

                                            <li><input id="password" class="input-block-level" type="password" name="password" placeholder="password"></li>
                                        </ul>
                                        <hr class="margin-xm">
                                        <label class="checkbox pull-left" for="keepLoggedIn"><input id="keepLoggedIn" name="keepLoggedIn" class="checkbox" type="checkbox">Remember me</label> <a href="#forgot" class="pull-right link" data-toggle="tab">Forgot?</a> <button type="button" class="logincmd btn btn-block btn-large ladda-button" data-style="zoom-in" data-spinner-color="#000"><span class="ladda-label">SIGN IN</span></button>
                                    </fieldset>
                                </form><!-- // form -->
                            </div><!-- // Tab Login -->

                            <div class="tab-pane fade form-dark" id="forgot">
                                <h3 class="no-margin-top opaci35">Forgot your password</h3>

                                <div class="alert alert-warning no-email-found" role="alert" style="display:none">
                                    We could not find any matching records!
                                </div>

                                <div class="alert alert-warning pin-error" role="alert" style="display:none">
                                    Pin entered does not match our records!<br>
                                    Please try again!
                                </div>

                                <div class="alert alert-success email-sent" role="alert" style="display:none">
                                    A new password has been sent to your email address.
                                </div>

                                <form class="margin-00" method="post" action="dashboard-one.html" id="forgot_password">
                                    <div id='EmailDiv'>
                                        <input id="emailforgot" class="input-block-level" type="email" name="emailforgot" placeholder="Enter your e-mail address"> <button type="button" class="btn btn-block btn-large ladda-button send-pin" data-style="zoom-in" data-spinner-color="#000"><span class="ladda-label">Continue</span></button>
                                    </div>

                                    <div id='ConfirmDiv' style="display:none">
                                        <hr class="margin-xm">

                                        <p class="note alert">We have sent a confirmation pin to your mobile number please enter below</p><input id="pin" class="input-block-level" type="tel" name="pin" placeholder="Pin Number"> <button type="submit" class="btn btn-block btn-large ValidatePin">Continue</button>
                                    </div><br>

                                    <p>Have you remembered? <a href="#login" class="pull-right link" data-toggle="tab">Try to log in again.</a></p>
                                </form><!-- // form -->
                            </div><!-- // Tab Forgot -->
                        </div>
                    </div>
                </div><!-- // sign-content -->

                <div class="web-description">
                    <h5>Copyright © <? echo date('Y');?>
                     Wekonnect USA LLC</h5>

                    <p>Cutting edge online payments systems.<br>
                    All rights reserved.</p>
                </div>
            </div><!-- // main-content -->
        </div><!-- // main-container  -->
    </div><!-- // page-container -->
    <!-- Le javascript -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- Libraries -->
    <script src="Content/libs/jquery/jquery-1.9.1/jquery.min.js" type="text/javascript">
</script><script src="Content/libs/jquery/jquery.migrate-1.1.1/jquery-migrate.min.js" type="text/javascript">
</script><script src="Content/libs/jquery/jquery.ui.combined-1.10.2/jquery-ui.min.js" type="text/javascript">
</script><script src="Content/libs/bootstrap/js/bootstrap.min.js" type="text/javascript">
</script><!-- System -->
    <script src="Content/libs/pl-system/jquery.nicescroll/js/jquery.nicescroll.min.js" type="text/javascript">
</script><script src="Content/libs/pl-system/jquery-cookie/js/jquery.cookie.js" type="text/javascript">
</script><script src="Content/libs/pl-system/jquery-mousewheel/js/jquery.mousewheel.js" type="text/javascript">
</script><script src="Content/libs/pl-system/xbreadcrumbs/js/xbreadcrumbs.js" type="text/javascript">
</script><!-- System info -->
    <script src="Content/libs/pl-system-info/bootstrapx-clickover/js/bootstrapx-clickover.js" type="text/javascript">
</script><script src="Content/libs/pl-system-info/gritter/js/jquery.gritter.min.js" type="text/javascript">
</script><script src="Content/libs/pl-system-info/jquery.notyfy/js/jquery.notyfy.js" type="text/javascript">
</script><script src="Content/libs/pl-system-info/qtip2/js/jquery.qtip.min.js" type="text/javascript">
</script><!-- Form -->
    <script src="Content/libs/pl-form/bootstrap-select/js/bootstrap-select.js" type="text/javascript">
</script><script src="Content/libs/pl-form/select2/js/select2.js" type="text/javascript">
</script><script src="Content/libs/pl-form/uniform/js/jquery.uniform.min.js" type="text/javascript">
</script><!-- Editors -->
    <!-- Contents -->
    <!-- Component -->
    <!-- File -->
    <!-- Gallery -->
    <!-- Tables -->
    <!-- Data Visualization -->
    <!-- Only example -->
    <script src="Content/libs/google-code-prettify/js/prettify.js" type="text/javascript">
</script><!-- Main js -->
    <script src="Content/js/app/core.js" type="text/javascript">
</script><script src="Content/js/app/demo-common.js" type="text/javascript">
</script><script type="text/javascript">
$(document).ready(function () {
            // uniform - checkbox, radio style
            $("input.checkbox, input.radio").uniform({
                radioClass: 'radios' // edited class - the original radio
            });
            $(".loading_cont").fadeOut(100);
            /*--------------------------------------------------------------------------*/  
            //Login Functions
            /*--------------------------------------------------------------------------*/                      
         if($.cookie("password")!=null){
             
            $("#password").val($.cookie("password"));
            $("#email").val($.cookie("email"));
            $("#keepLoggedIn").attr('checked', true); 
               $("input.checkbox, input.radio").uniform({
                radioClass: 'radios' // edited class - the original radio
            });
             
         }
    /*--------------------------------------------------------------------------*/  
    // Functions
    /*--------------------------------------------------------------------------*/ 
            $(".logincmd").click(function() {   
            
                var l = Ladda.create(this);
                l.start();
            
                
                    $.ajax({
                    url: "ajax.php",
                    global: false,
                    type: "POST",
                    data: ({"ACTIONS" : "LOGIN"
                       ,password : $("#password").val(),
                       email : $("#email").val()
                       
                       }),
                    dataType: "html",
                        success: function(new_data){
                        console.log(new_data)
                        
                            if(new_data!="false"){  
                            
                            if($('#keepLoggedIn:checked').val()){
                                $.cookie("email", $("#email").val(), { expires: 30, path: '/' });   
                                $.cookie("password", $("#password").val(), { expires: 30, path: '/'});  
                            }
                            else{
                                 $.cookie("email", null);
                                  $.cookie("password", null);
                            }   
                            
                                if ($('#hidedashboard').is(':checked')) {
                                    window.location = "dashboard/?hidedashboard=true";   
                                } else {
                                   window.location = "dashboard/";  
                                } 

                
                                                
                            }else{                  
                                    $(".sign-error").fadeIn(500);
                                    l.stop();   
                            }                   
                                        
                        }                               
                    });                             
            });
    /*--------------------------------------------------------------------------*/  
    // Functions
    /*--------------------------------------------------------------------------*/   
            $(".send-pin").click(function(e) { 
            
                e.preventDefault();
                var l = Ladda.create(this);
                l.start();
            
                 $.ajax({
                    url: "ajax.php",
                    global: false,
                    type: "POST",
                    data: ({"ACTIONS" : "SEND_PIN"
                      ,email : $("#emailforgot").val()
                       
                       }),
                    dataType: "html",
                        success: function(new_data){
                        
                        if(new_data=="ERROR"){
                        $(".no-email-found").fadeIn(100);
                            l.stop();
                        }else{
                            
                            setTimeout(function(){ 
                                $(".alert").hide();
                                $("#EmailDiv").hide();
                                $("#ConfirmDiv").fadeIn(200);
                                l.stop();
                            },1000);
                        }               
                                        
                                        
                        }                               
                    }); 
            
            }); 
    /*--------------------------------------------------------------------------*/  
    // Functions
    /*--------------------------------------------------------------------------*/ 
            
             $(".ValidatePin").click(function(e) { 
            
                e.preventDefault();
                var l = Ladda.create(this);
                l.start();
            
                 $.ajax({
                    url: "ajax.php",
                    global: false,
                    type: "POST",
                    data: ({"ACTIONS" : "VALIDATE_PIN"
                      ,pin : $("#pin").val()
                       
                       }),
                    dataType: "html",
                        success: function(new_data){
                        if(new_data=="Error"){
                        
                          $(".pin-error").fadeIn(100);
                           l.stop();
                            
                        }else{
                            setTimeout(function(){ 
                                l.stop();
                                $(".alert").hide();
                                $(".email-sent").fadeIn(100);
                                
                                
                             },1000); 
                        }                
                                        
                                        
                        }                               
                    }); 
            
            }); 
            
            
    /*--------------------------------------------------------------------------*/  
    //Misc Functions xxx
    /*--------------------------------------------------------------------------*/          
        Ladda.bind( '.ladda-button' );
            
        });
    </script>
</body>
</html>
