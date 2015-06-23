@extends('frontend.templates.register_pages')

@section('content')
<?php
	$user_array = Session::all(); 
    if(!isset($_GET["utm_source"])) $_GET["utm_source"] = "";
    if(!isset($_GET["utm_medium"])) $_GET["utm_medium"] = "";
    if(!isset($_GET["utm_campaign"])) $_GET["utm_campaign"] = "";

	function RegPageURL() {
		$pageURL = 'http';
		//if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}

	$currentPageUrl =  RegPageURL();
?>
<style>
.wowtables_small_div{
  background: #eabc3f;
  margin-bottom: 48% !important;
  border-radius: 5px;
  color: #000000 !important;
  opacity: 0.9;
}

.wowtable_nameing{
	color: #000000!important;
	font-weight: bolder;
}
.button_wowtables{
 	margin-left: 4%;
	margin-bottom: 2%;
}

.btn-primary:active,.btn-primary:hover,.btn-primary {
  color: #000;
  background-color: #FFFFFF;
  border-color: #FFFFFF;
  border: 2px solid #FFFFFF !important;
  font-weight: bolder;
  margin-left: 30%;
}

.signup-wrapper{
  background:#000000;
  filter: progid:DXImageTransform.Microsoft.Alpha(opacity=80);
}
.form-group{
  margin-bottom: 10px !important;
}   
</style>
<script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      console.log('Please log into this app.');
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      console.log('Please log into Facebook.');
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '487953444640436',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.2' // use version 2.2
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name);      
    });
  }
</script>
<section id="abovefold">
      <div class="cover-images">
        <div class="visible-xs mobile-cover">
          <img src="{{URL::to('/')}}/assets/img/wine-dine.jpg" alt="GourmetItUp Mobile Cover">
        </div>
        <div id="carousel-cover" class="carousel slide hidden-xs" data-ride="carousel">
          
          <!-- Wrapper for slides -->
          <div class="carousel-inner">
      <?php 
      if(isset($bg_images))
      {
      foreach($bg_images as $key=>$val) {?>
        <div class="item<?=($key==0) ? ' active' : ''?>">
          <img class="img-responsive" src="<?php echo $val;?>" alt="cover<?php echo $key+1?>">
          <div class="carousel-caption hidden-sm hidden-xs">
          <p class="text-right"><?php echo $info[$key]['title'].' - '.$info[$key]['description']; ?>
          </p>
          </div>
        </div>
      <?php }
      }?>
          </div>
        </div>
      </div>

      <div class="container overlay wowtables_padding_bottom" style="margin-bottom:100px !important;">
        <div class="row">
          <div class="logo-wrap visible-md visible-lg">
            <a href="#" alt="gourmet logo" class="site-logo">
                <img src="{{URL::to('/')}}/assets/img/WT 2.png" class="img-responsive" /> 
              </a>
            <div class="logo-bg">              
              <div class="tagline">
                <p class="text-center">BOOK EXCLUSIVE FINE DINING EXPERIENCES AT THE BEST RESTAURANTS</p>
              </div>
              <div class="view-more text-center">
                <a href="#how-it-works" type="button" class="btn btn-success">HOW IT WORKS</a>
              </div>              
            </div>
          </div>
           <div class="col-md-12">           

              <div class="logo-form-wrap signup-wrapper wowtables_signin_form">
                <a href="#"><img src="{{URL::to('/')}}/assets/img/logo_bygoumetitup.png" class="img-responsive wowtable_logo_padding"></a>
              </div>
              <div class="signup-wrapper wowtables_signin_wrapper">
                <div class="tagline-wrapper wowtables_tagline_wrapper">              
                  <p class="text-center wowtables_text_align">BOOK EXCLUSIVE FINE DINING EXPERIENCES AT THE BEST RESTAURANTS</p>
                </div>
               
                <!-- <a href='javascript:void(0)' id="FBLogin" type="submit" ><span class="fbicon"></span>Log in with Facebook</a> -->
                <a href='javascript:void(0)' id="FBLogin" type="submit" onclick=popup()><span class="fbicon"></span>Log in with Facebook</a>
               
              <div class="form-slide-wrapper" <?=(!empty($errors->has()))? 'style="left:-320px;"' : '' ;?>>
                  <div id="signupForm" >
                   <div id="signupFormwrap">
                      <div class="info-text new clearfix">                        
                        <p class="text-center wowtables_text_color">OR</p>
                        <a href="javascript:void(0);" class="col-xs-6 text-right active">Sign up</a>
                        <a id="slideSignin" href="javascript:void(0);" class="col-xs-6">Sign in</a>
                      </div>
                     <form role="form" style="border-top:1px dashed #c2c2c2;padding-top: 12px;">
                        <div class="form-group">
                          <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email" name="email">
                          <label class="control-label error-code text-danger" id="email_error">Please enter a valid email address</label>
                        </div>
                         <div class="form-group">
                          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
                          <label class="control-label error-code text-danger" id="password_error">Write correct password</label>
                        </div>
                        <div class="form-group">
                          <select class="form-control" name="city" id="city">
                            <option value="-1">Choose a City</option>
                                <?php foreach ($cities as $city => $visibility): ?>
                                
                                    <?php if ($visibility != 'hidden'): ?>
                                        
                                    <option value="<?php echo $city?>"><?php echo ucfirst($visibility);?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                          </select>
                          <label class="control-label error-code text-danger" id="city_error">Please pick a city</label>
                        </div>                      
                        <input type="submit" class="btn btn-warning btn-block" name="send" id="send" style="color:#ffffff !important;font-size:16px !important;height:47px !important;" value="REGISTER"/>
                        <small class="text-center comment"<span style="color:#ab9d8a !important;">By joining, you agree to our</span><a href="http://wowtables.com/terms-of-use"> Terms & Conditions</a>
                        </small>
                        </form>
                    </div>

                    <div id="signupSecondscreen">
                      <div class="info-text">
                        <h4 class="text-center">THANK YOU FOR JOINING!</h4>
                        <p class="text-center">Help us customize your experience further.</p>
                      </div>
                      <?php  $cur_year = @date("Y");?>  
                      <form role="form">
                        <div class="form-group">
                          <input type="text" class="form-control" id="full_name" placeholder="Full Name" name='full_name'>
                          <label class="control-label error-code text-danger" id="error_name">Please enter your first and last name</label>
                        </div>

                        <div class="form-group gender-checkbox">
                          <div>
                          <label class="radio-inline" name="gender">
                            <input type="radio" name="generRadios" id="inlineRadio1" value="Male"><span>Male</span>
                          </label>
                          <label class="radio-inline" name="gender">
                            <input type="radio" name="generRadios" id="inlineRadio2" value="Female"><span>Female</span>
                          </label>
                          </div>
                          <label class="control-label error-code text-danger">Value cannot be blank.</label>
                        </div>
                        <div class="form-group year-dropdown">
                          <select class="form-control pull-left b_day" id="day_bd" name='day_bd'>
                            <option value="0">Date of birth</option>
                             <?php 
                                    
                                    for($i=1;$i<=31;$i++)
                                    {
                                        $day = ($i<10)? "0".$i : $i;
                                        echo '<option value="'.$day.'">'.$day.'</option>';
                                    }
                                  ?> 
                          </select>
                          <select class="form-control pull-right b_day" id="month_bd" name='day_bd'>
                            <option value="0">Month of birth</option>
                             <?php 
                                    for($x = 1; $x <= 12; $x++) {
                                        echo '<option value="'.date('m', mktime(0, 0, 0, $x, 1)).'">'.date('F', mktime(0, 0, 0, $x, 1)).'</option>';
                                    }
                                  ?> 
                          </select>
                         <div class="clearfix"></div>
                         <label class="control-label error-code text-danger" id="error_bd">Please select your birthday.</label> 
                         <input type="hidden" id="year_bd" value="<?=date('Y');?>">                    
                        </div> 
                        <button type="submit" class="btn btn-warning btn-block" id="send1">Complete My Profile</button>
                        <p class="lead text-center"><a href='javascript:' id='skip'> SKIP THIS ></a></p>
                      </form>
                    </div>

                  </div>

                 <div id="signinForm">
               
                    <div id="signinForm-wrap">
                      <div class="info-text new clearfix">                        
                        <p class="text-center wowtables_text_color">OR</p>
                        <a id="slideSignup" href="javascript:void(0);"  class="col-xs-6 text-right" style="margin-top:-2px;">Sign up</a>
                        <a href="javascript:void(0);" class="col-xs-6 active">Sign in</a>
                      </div>
                      <form role="form" method="post" style="border-top: 1px dashed #c2c2c2;padding-top: 12px;" action="{{URL::to('/')}}/users/login">
                        <div class="form-group">
                          <input type="email" class="form-control" id="email1" placeholder="Enter email" name="email" value="">
                          <label class="control-label error-code text-danger" id="email_error_1">Please enter a valid email address</label>
                        </div>
                        <div class="form-group">
                          <input type="password" class="form-control" id="pass1" placeholder="Password" name="password">
                          <label class="control-label error-code text-danger" id="password_error_1" <?=($errors->has())?'style="display:block;"':'';?>>
                          @if($errors->has())
						        @foreach($errors->all() as $error)
			                        {!! $error !!}
			                    @endforeach
							@endif;
                          </label>
                          <p class="text-right pass-forget"><a href="javascript:void(0)" class="forgot-pass-link" id="forget_password">Forgot Password?</a></p>
                        </div>                      
                        <button type="submit" class="btn btn-warning btn-block" id="login" style="color:#fffffF !important">Enter</button>
                      </form>
                    </div> <!-- signinForm-wrap end -->
                    <div id="forgotpassForm">
                      <div class="info-text">
                        <h4 class="text-center" style="font-family:Swis721 Lt BT !important;font-size:15px;color: #ab9d8a!important;">ENTER YOUR EMAIL ADDRESS</h4>
                      </div>
                      <form role="form" method="POST" style="border-top: 1px dashed #c2c2c2;padding-top: 12px;" action="{{URL::to('/')}}/users/forgot_password" id='forgot_password'>
                        <div class="form-group">
                          <input type="email" class="form-control" id="f_pass" placeholder="Enter email" name="forgotemail">
                          <label class="control-label error-code text-danger" id='wrong_email'>Please enter your email address</label>
                        </div>                     
                        <button type="submit" class="btn btn-warning btn-block" style="color:#ffffff !important;">Get Reset Link</button>   
                        <p class="text-center pass-forget"><a href="javascript:void(0);" class="login-link" id="go_back_link">Go back to Login</a></p>
                        <p class="text-center pass-forget check-email hidden" id='f_response'>
                            Please check your email for a forgot password link
                        </p>
                      </form>
                    </div> <!-- forgot pass end -->

                  </div> <!-- signinForm end -->

                  <div style="clear:both"></div>
                </div>
                <div class="clearfix"></div>
              </div> <!-- signup-wrapper end -->   

              <!-- Facebook plugin start --> 
              <div class="questionpage-facebook-link">
                <div class="wowtables_small_div">
                  <div class="row">
                  <div class="col-sm-12 col-xs-12 col-md-12">
                  <h3 class="wowtable_nameing" style="text-align:center !important;">GourmetItUp is now WowTables</h3>
                  </div>
                </div>
                <div class="row">
                <div class="col-sm-12 col-xs-12 col-md-12">
                <p style="text-align:center !important;">Fine dining has changed - and so have we.</p>
                  <button type="button" class="btn btn-primary button_wowtables" onclick="window.location.href='http://blog.gourmetitup.com/gourmetitup-is-now-wowtables-the-fine-dining-revolution-begins/'" target="_blank">FIND OUT MORE</button>
                </div>
              </div>
                </div>
                <div class="row questionpage-facebook-link_hide">
                    <div class="col-sm-12 col-xs-12">                           
                        <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) return;
                              js = d.createElement(s); js.id = id;
                              js.src = "http://connect.facebook.net/en_US/sdk.js#xfbml=1&appId=487953444640436&version=v2.0";
                              fjs.parentNode.insertBefore(js, fjs);
                              }(document, 'script', 'facebook-jssdk'));
                            </script>
                        <div class="fb-facepile" data-app-id="487953444640436" data-href="https://www.facebook.com/GourmetItUp" data-width="500" data-height="100" data-max-rows="1" data-colorscheme="dark" data-size="large" data-show-count="true"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
              </div>  
              
             

          </div> <!-- cold12 end  -->
        </div>   <!-- row end -->       
      </div> <!-- container end -->
    </section>

    <section id="how-it-works">
      <div class="container">
        <div class="row">
          <p class="text-center lead">HOW IT WORKS</p>
          <div class="col-md-4 work-steps text-center">
            <img class="img-responsive" alt="Step1" src="{{URL::to('/')}}/assets/img/step1.png">
            <h4 class="text-center">Make a Reservation for one of our fine dining experiences</h4>
            <p class="text-center">
              Our exclusive experiences are available for members of WowTables.com. Membership and reservations are completely free.
            </p>
            <span class="arrow-right visible-md visible-lg"></span>
          </div>
          <div class="col-md-4 work-steps text-center">
            <img class="img-responsive" alt="Step1" src="{{URL::to('/')}}/assets/img/step2.png">
            <h4 class="text-center">When you reach the restaurant, just give the host your name</h4>
            <p class="text-center">
              No vouchers or printouts are required. Your host will have a table ready for you with a special WowTables experience menu.
            </p>
            <span class="arrow-right visible-md visible-lg"></span>
          </div>
          <div class="col-md-4 work-steps text-center">
            <img class="img-responsive" alt="Step1" src="{{URL::to('/')}}/assets/img/step3.png">
            <h4 class="text-center">Enjoy the WowTables experience!</h4>
            <p class="text-center">
              You pay the restaurant directly. You will get Gourmet Points that you can redeem for exciting rewards on our website!
            </p>
          </div>
        </div>
      </div>
    </section>

     <section id="experiences" class="visible-xs">
      <div class="container">
        <div class="row">
          <p class="text-center lead">SOME OF OUR EXCITING EXPERIENCES</p>
          <div class="col-md-12">
            <div id="experience-carousel" class="carousel slide" data-ride="carousel">
              
              <!-- Wrapper for slides -->
              <div class="carousel-inner">
                <?php 
                if(isset($bg_images))
                {
                    foreach($bg_images as $key=>$val) {?>
                    <div class="item<?=($key == 0) ? ' active' : ''?>">
                      <img src="<?php echo $val;?>" alt="cover<?php echo $key+1?>">
                      <div class="experience-desc">
                        <p class="text-center"><?php echo $info[$key]['title'].' - '.$info[$key]['description']; ?> </p>                    
                      </div>
                      <div class="experience-detail">
                        <p class="details text-center lead"><a href="javascript:void(0);">Exclusively for WowTables Members</a></p>
                      </div>                                    
                    </div>
                <?php } 
                }?>
              </div>

              <!-- Controls -->
              <a class="left carousel-control" href="#experience-carousel" data-slide="prev">
                <span><</span>
              </a>
              <a class="right carousel-control" href="#experience-carousel" data-slide="next">
                <span>></span>
              </a> 
            </div>

          </div>
        </div>
      </div>
    </section>

    <section id="press">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <p class="lead text-center">FEATURED IN:</p>
          </div>
          <div class="col-md-12">
            <div class="row">
              <ul class="press-logos">
                <li class="col-md-2 col-sm-2 col-xs-6"><a href="{{URL::to('/')}}/press"><img src="{{URL::to('/')}}/assets/img/sunday-express.png" class="img-responsive"></a></li>
                <li class="col-md-2 col-sm-2 col-xs-6"><a href="{{URL::to('/')}}/press"><img src="{{URL::to('/')}}/assets/img/mumbai-boss.png" class="img-responsive"></a></li>
                <li class="col-md-2 col-sm-2 col-xs-6"><a href="{{URL::to('/')}}/press"><img src="{{URL::to('/')}}/assets/img/asian-age.png" class="img-responsive"></a></li>
                <li class="col-md-2 col-sm-2 col-xs-6"><a href="{{URL::to('/')}}/press"><img src="{{URL::to('/')}}/assets/img/grazia.png" class="img-responsive"></a></li>
                <li class="col-md-2 col-sm-2 col-xs-6"><a href="{{URL::to('/')}}/press"><img src="{{URL::to('/')}}/assets/img/inonit.png" class="img-responsive"></a></li>
                <li class="col-md-2 col-sm-2 col-xs-6"><a href="{{URL::to('/')}}/press"><img src="{{URL::to('/')}}/assets/img/live-mint.png" class="img-responsive"></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="journal">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <p class="lead">JOURNAL</p>
            <h4><?php echo isset($journal[0]['journal_title'])?$journal[0]['journal_title']:'';?></h4>
            <p><?php echo isset($journal[0]['journal_text'])?$journal[0]['journal_text']:'';?></p>
            <p><a href="<?php echo isset($journal[0]['journal_url'])?$journal[0]['journal_url']:'#';?>" target="_blank" class="btn btn-warning">Read more</a></p>
          </div>
          <div class="col-md-6 testimonial">
            <p class="lead text-right">TESTIMONIALS</p>
            <div id="testimonial-carousel" class="carousel slide" data-ride="carousel">
              <!-- Indicators -->
              <ol class="carousel-indicators">
              <?php 
              if(isset($testimonials))
              {  
              $i=1;foreach($testimonials as $tst):?>
                    <li data-target="#testimonial-carousel" data-slide-to="<?php echo $i;?>" <?=($i == 1)? 'class="active"':'';?>></li>
               <?php $i++;endforeach;
              }?>
              </ol>

              <!-- Wrapper for slides -->
              <div class="carousel-inner">
              <?php 
              if(isset($testimonials))
              { 
                $i=1;foreach($testimonials as $tst):?>
                <div class="item <?=($i == 1)? 'active':'';?>">
                  <blockquote class="pull-right">
                    <p><?=stripslashes($tst['content']);?></p>
                    <small><?php echo $tst['someone_famous_in']?><cite title="Source Title"><?php echo $tst['source_title']?></cite></small>
                  </blockquote>                  
                </div>
              <?php $i++;endforeach;
              }?>
              </div>              
            </div>
          </div>
        </div>
      </div>
    </section>

       <!-- Modal -->
    <div class="modal fade" id="fbSelectCity" tabindex="-1" role="dialog" aria-labelledby="fbSelectCityLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button> -->
            <h4 id="myModalLabel" class="modal-title text-center">Thank you for signing in</h4>
          </div>
          <div class="modal-body">   
            <p class="text-center">Please select your city to proceed:</p>         
            <div class="panel panel-default">
              <!-- List group -->
              <ul class="list-group">
                <li class="list-group-item"><a href="{{URL::to('/mumbai')}}" data-dismiss="modal">Mumbai</a></li>
                <li class="list-group-item"><a href="{{URL::to('/delhi')}}" data-dismiss="modal">Delhi</a></li>
                <li class="list-group-item"><a href="{{URL::to('/pune')}}" data-dismiss="modal">Pune</a></li>
                <li class="list-group-item"><a href="{{URL::to('/bangalore')}}" data-dismiss="modal">Bangalore</a></li>
              </ul>
            </div>
          </div>
          
        </div><!-- /.modal-content -->
      </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){           
            $("#forget_password").click(function(){
                $("#FBLogin").hide();
            });
            $("#go_back_link").click(function(){
                $("#FBLogin").show();
            });
            
        });
    </script>

   <script type="text/javascript">

    function popup(){
        myWindow=window.open("{{URL::to('/')}}/users/facebook", "_blank", "scrollbars=1,resizable=1,height=300,width=450");
        myWindow.moveTo(500, 200); 
        myWindow.focus();
        intervalID = window.setInterval(checkWindow, 500);
    }
    function checkWindow() {
      try {
        if(myWindow.location.href.indexOf("wowtables.com") >= 0){
            myWindow.close();
            myWindow.clearInterval(intervalID);
              if(myWindow.location.href.indexOf("mumbai") >= 0 || myWindow.location.href.indexOf("delhi") >= 0 || myWindow.location.href.indexOf("pune") >= 0 || myWindow.location.href.indexOf("bangalore") >= 0){
                //location.reload();
                console.log('IF Section');
                 // window.location.href = "{{URL::to('/')}}/"+mumbai;
              }
              else { 
                console.log('else section');
                  var pageUrl = "<?php echo $currentPageUrl;?>";
                  /*$.ajax({
                        url: "{{URL::to('/')}}/login/registration_page_url",
                        type: "POST",
                        dataType: "json",
                        data:{page_url: pageUrl},
                        success: function(d) {
                        }
                    });*/
                  $("#fbSelectCity").modal('toggle');
              }
             //$("#fbSelectCity").modal('toggle');
             /* var city = '< ?="$facebook";?>';
          alert(city);*/
            /*   if("< ?PHP echo $facebook; ?>"==1){   
               location.reload();
              }
              else {
            }*/
         }
      } catch(e) {
      // we don't actually catch anything, we just want to stop the error from throwing
      }
        if(myWindow && myWindow.closed) {
          myWindow.clearInterval(intervalID);
        }
    }

      $(document).ready(function() {
          var v = "Registration Page";
          $.ajax({
            type: "get",
            url: "/set_reservation_location",
            data: {resv_loc : v},
            success: function(e) {
                //console.log(e);
            }
        });
          $('.tooltip1').tooltip();
          email_regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
          $("input[name='email']").focus(function() {
              $("#email_error").css('display','none');
              $("input[name='email']").css('border','0px');
          });
          $("input[name='password']").focus(function() {
              $("#password_error").css('display','none');
              $("input[name='password']").css('border','0px');
          });
          $("#city").focus(function() {
              $("#city_error").css('display','none');
              $("#city").css('border','0px');
          });
          $("#full_name").focus(function(){
                $('#error_name').css('display','none');
                $('#full_name').css('border','0px');
          });
          $("#email1").focus(function(){
              $("#email_error_1").css('display','none');
          });
          $("#pass1").focus(function(){
              $("#password_error_1").css('display','none');
          });

          $('#send').click(function(event){          
                event.preventDefault();
                email_address = $("input[name='email']"); 
                err=0;
                mypassword='';
                if(email_address.val()==''){
                    $("#email_error").text("Please enter your email address");
                    $("#email_error").css('display','block');
                    $("input[name='email']").css('border','2px solid #B94A39');
                    err++;
                }
                else if(!email_regex.test(email_address.val())){ 
                    $("#email_error").text("Please enter a valid email address");
                    $("#email_error").css('display','block');
                    $("input[name='email']").css('border','2px solid #B94A39');
                    err++;
                }
                else{
                    $("#email_error").css('display','none');
                    $("input[name='email']").css('border','0px');
                }
                
                if($("input[name='password']").val()==''){
                    $("#password_error").text("Please enter a password");
                    $("input[name='password']").css('border','2px solid #B94A39');
                    $("#password_error").css('display','block');
                    err++;
                }
                else if($("input[name='password']").val().replace(/\s+$/,"")=='' || $("input[name='password']").val().replace(/\s+$/,"")=='******' ){ 
                    $("#password_error").text("Write correct password");
                    $("input[name='password']").css('border','2px solid #B94A39');
                    $("#password_error").css('display','block');
                    err++;
                }
                else{
                    $("input[name='password']").css('border','0px');
                    $("#password_error").css('display','none');
                    mypassword=$("input[name='password']").val();
                }
                
                if ($('#city').val() == -1) {   
                    $("#city").css('border','2px solid #B94A39');
                    $("#city_error").css('display','block');
                    err++;
                }
                else{
                    $("#city").css('border','0px');
                    $("#city_error").css('display','none');
                }
                
                if(err>=1){
                     return false;
                }
                else{
                    $.ajax({
                            url: '/users/checkemail',
                            data: {'email' : email_address.val()},
                            type: 'post',
                            success: function(data){
                                if(data != '')
                                {    
                                    //console.log(url);
                                    console.log(data);
                                    $("input[name='email']").css('border','2px solid #B94A39');
                                    $("#email_error").html(data);
                                    $("#email_error").css('display','block');
                                    $('#email_error a').on('click', function(e){
                                        $('.form-slide-wrapper').animate({'left': '-320px'});
                                        $("input[name='email']").css('border','0px');
                                    });
                                    err++;   
                                }
                                else
                                {
                                    yourcity=$('#city option:selected').val();
                                    $.ajax({
                                        type: 'post',
                                        url:'/users/register?utm_source=<?php echo $_GET["utm_source"]?>&utm_medium=<?php echo $_GET["utm_medium"]?>&utm_campaign=<?php echo $_GET["utm_campaign"]?>',

                                        data: {
                                            email:email_address.val(),
                                            password:mypassword,
                                            city:yourcity
                                        },
                                        success:function(data){
                                            var  order_path = "";
                                            if(data==1){
                                                <?php 
                                                $order = Session::get('order');
                                                if (!empty($order) && is_array($order)) {
                                                    $order_path = $order['slug']; ?>
                                                    order_path = <?php echo json_encode($order_path); ?>;
                                                <?php         } ?>
                                                if(order_path!=""){
                                                    window.location = "{{URL::to('/')}}/exp/"+order_path;
                                                } else{
                                                    window.location="{{URL::to('/')}}/exp/lists/?signup=true";
                                                }  
                                            }
                                        }
                                    });
                                    //Adding for now End
                                }
                                
                            }      
                        });
                }
          });
                $("#send1").click(function(event){ 
                    event.preventDefault();
                    error=0;
                    fullname_regex =/(^(?:(?:[a-zA-Z]{2,4}\.)|(?:[a-zA-Z]{2,24}))){1} (?:[a-zA-Z]{2,24} )?(?:[a-zA-Z']{2,25})(?:(?:, [A-Za-z]{2,6}\.?){0,3})?/gim;
                    if($("#full_name").val()=='')
                    {
                        $('#error_name').css('display','block');
                        $('#full_name').css('border','2px solid #B94A39');
                        error++;
                    }
                    else if(!fullname_regex.test($("#full_name").val()))
                    { 
                        $("#error_name").text("Please enter your first and last name");
                        $("#error_name").css('display','block');
                        $("#full_name").css('border','2px solid #B94A39');
                        error++;
                    }
                    else
                    {
                        $("#error_name").css('display','none');
                        $("#full_name").css('border','0px');
                    }
                    if($("#day_bd").val()==0 || $("#month_bd").val()==0){
                        $("#error_bd").text("Please select your birthday.");
                        $("#error_bd").css('display','block');
                        $("#day_bd,#month_bd").css('border','2px solid #B94A39');
                        error++; 
                    }else{
                        $("#error_bd").css('display','none');
                        $("#day_bd,#month_bd").css('border','0px');
                    }
                    
                    
                    
                    if(error>=1)
                    {
                        return false;
                    }
                    else{
                        yourcity=$('#city option:selected').val();
                        var birth_day = $('#year_bd').val()+"-"+ $('#month_bd').val()+"-"+$('#day_bd').val();
                        $.ajax({
                            type: 'POST',
                            url:'registration/register',
                            
                            data: {email:email_address.val(),password:mypassword,city:yourcity,full_name:$("#full_name").val(),gender:$("input:radio[name=generRadios]:checked").val(),dob:birth_day},
                            success:function(data){
                                var  order_path = "";
                                console.log(data);
                                if(data==1){
                                    <?php 
                                        $order = Session::get('order');
                                        if (!empty($order) && is_array($order)) {
                                            $order_path = $order['slug']; ?>
                                        order_path = <?php echo json_encode($order_path); ?>;
                                            <?php         } ?>
                                    if(order_path!=""){
                                       window.location = "{{URL::to('/')}}/experiences/"+order_path;
                                    }
                                    else{
                                            
                                    window.location="{{URL::to('/')}}/exp/lists/?signup=true";
                                    }  
                                }
                                else{
                                    //alert("Your email is not added in our database Yet. Please contact the site administrator");
                                    //alert(data);
                                }
                            }
                        });
                    }
                });

            $(".list-group-item").click(function(){

              var city_name = $(this).text().toLowerCase();
             
                $.ajax({

                  type:'GET',
                  url:'fbAddCity/'+city_name,
                  /*data:{city:city_name}, */
                  success:function(data){
                      /*window.location.href = "{{URL::to('/')}}/"+city_name;*/
                  }

                })

              //window.location.href = "{{URL::to('/')}}/"+city_name;
              });


            $('#login').click(function(event){
                event.preventDefault();
                //console.log('hello'); 
                login=$("#email1").val();
                psw=$("#pass1").val();
                logerror=0;
               // console.log(login);
                if(login=='')
                {
                    $("#email_error_1").text("Please enter your email address");
                    $('#email_error_1').css('display','block');
                    $('#email1').css('border','2px solid #B94A39');
                    logerror++;
                } 
                else if(!email_regex.test(login))
                { 
                    $("#email_error_1").text("Please enter a valid email address");
                    $("#email_error_1").css('display','block');
                    $("#email1").css('border','2px solid #B94A39');
                    logerror++;
                }
                else
                {
                     $("#email_error_1").css('display','none');
                     $("#email1").css('border','0px');
                }
                
                if(psw=='')
                {
                    $("#password_error_1").text("Please enter a password.");
                    $('#password_error_1').css('display','block');
                    $('#pass1').css('border','2px solid #B94A39');
                    logerror++;
                }
                else if(psw.replace(/\s+$/,"")=='' || psw.replace(/\s+$/,"")=='******' )
                { 
                    $("#password_error_1").text("Write correct password.");
                    $("#pass1").css('border','2px solid #B94A39');
                    $("#password_error_1").css('display','block');
                    logerror++;
                }
                else
                {
                    $("#pass1").css('border','0px');
                    $("#password_error_1").css('display','none');    
                }
                
                if(logerror>0)
                {
                    return false;
                }
                else
                {
                    $('#login').closest('form').submit();   
                }
            }); 
           $('#forgot_password').submit(function(e){
                e.preventDefault();
                $('#f_response').addClass('hidden'); 
                forgotmail=$(this).find('input').val();
                
                if(forgotmail.length == 0)
                {
                    $(this).find('label').css('display','block');
                    $(this).find('input').css('border','2px solid #B94A39');
                }
                else
                {
                    $.ajax({
                       type:'POST',
                       url:'users/forgot_password',
                       data:{forgotemail:forgotmail},
                       success:function(data){
                           $('#f_response').html(data);
                           $('#f_response').removeClass('hidden'); 
                       } 
                    });
                }
           });
           $('#forgot_password input').focus(function(){
                   // console.log('hi');
                   //$(this).val('');
                   $(this).next().css('display','none');
                   $(this).css('border','0px');
                   $('#f_response').addClass('hidden'); 
           }); 
           $('#skip').click(function(e){
               e.preventDefault();
               $.ajax({
                    type: 'POST',
                    url:'registration/register',
                    
                    data: {email:email_address.val(),password:mypassword,city:$('#city option:selected').val()},
                    success:function(data){
                        var  order_path = "";
                        console.log(data);
                        if(data==1){
                          //  console.log(1);
                            <?php 
                                $order = Session::get('order');
                                if (!empty($order) && is_array($order)) {
                                    $order_path = $order['slug']; ?>
                                order_path = <?php echo json_encode($order_path); ?>;
                                    <?php         } ?>
                            if(order_path!=""){
                               window.location = "{{URL::to('/')}}/experiences/"+order_path;
                            }
                            else{
                                    
                            window.location="{{URL::to('/')}}/experience/lists/?signup=true";
                            }  
                        }
                        else{
                            //alert("Your email is not added in our database Yet. Please contact the site administrator");
                            //alert(data);
                        }
                    }
                });
           })
      });
    </script> 
@endsection