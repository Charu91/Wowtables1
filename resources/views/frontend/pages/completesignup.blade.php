<?php 
/*$user_array = $this->session->all_userdata();
if(isset($user_array['logged_in']) && $this->input->get('signup') != 'extraprofile'){
    redirect('/'.$user_array['city']);                  
}*/
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Gourmet it Up - Exclusive Gourmet Dining Experiences</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="{{URL::to('/')}}/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="{{URL::to('/')}}/assets/css/app.css" rel="stylesheet" media="screen">
    <link href="{{URL::to('/')}}/assets/css/signup.css" rel="stylesheet" media="screen"> 

    <!-- <link href='http://fonts.googleapis.com/css?family=Nunito:400,700' rel='stylesheet' type='text/css'> -->
    <!-- <link href='http://fonts.googleapis.com/css?family=Tenor+Sans' rel='stylesheet' type='text/css'> -->
    <!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'> -->
    <link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
  </head>
  <body id="signup-abovefold">

  <iframe src="http://cztrk.com/p.ashx?a=206&t=<?php echo $_GET['email']?>" height="1" width="1" frameborder="0"></iframe>

  <div class="logo">
    <a href="#"><img class="img-responsive" src="{{URL::to('/')}}/assets/img/logo.png"></a>
    <div class="tagline-wrapper">              
      <p class="lead text-center">BOOK EXCLUSIVE FINE DINING <br/> EXPERIENCES AT THE BEST RESTAURANTS</p>
    </div>
  </div>
	<?php //echo "<pre>"; print_r($_GET);?>
    <div class="signin">
      <div class="form1">
        <p class="intro">
          <span class="lead">CONGRATULATIONS!</span><br/>You are a tiny step away from accessing WowTable's dining experiences! Please enter your name and choose a password to complete your registration.
        </p>

        <form role="form" class="register-form" method="POST">
          <div class="form-group">
            <input type="text" placeholder="Enter Your Name" id="full_name" name="full_name" class="form-control" autocomplete="off">
            <label id="error_name" class="control-label error-code text-danger">Please enter your first and last name.</label>
          </div>
          <div class="form-group">
            <input type="password" name="password" placeholder="Enter A Password" id="password" class="form-control" autocomplete="off">
            <label id="password_error" class="control-label error-code text-danger">Please enter a password.</label>
          </div>                      
          <button class="btn btn-warning btn-block" type="button" >COMPLETE MY REGISTRATION</button>
          <input type="hidden" name="city" id="city" value="<?=$city;?>">
           <input type="hidden" name="cityName" id="cityName" value="<?=strtolower($cityName);?>">
          <input type="hidden" name="email" id="email" value="<?=$email;?>">
          <input type="hidden" name="phone" id="phone" value="<?=$phone;?>">
        </form>
      </div>

      <div class="form2">
        <p class="intro">
          This email address is already registered on WowTables.com.
        </p>
        <a class="btn btn-warning btn-block" href="{{URL::to('/')}}/">CLICK HERE TO LOGIN</a> <br/>
        <p class="intro">
         or call us at 09820541255 to speak to our concierge.
        </p>
      </div>

      <div class="bottom hidden-xs">
        <p>For assistance, please call our Concierge at 09619551387</p>
        <ul class="list-inline">
          <li><a hre="#">Gift cards</a></li>
          <li><a hre="#">Corporate Solutions</a></li>
          <li><a hre="#">Parties & Private Events</a></li>
          <li><a hre="#">Gourmet Rewards</a></li>
          <li><a hre="#">Terms of Use</a></li>
          <li><a hre="#">Privacy Statement</a></li>
        </ul>
      </div>

    </div>
	<?php 
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


   
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{URL::to('/')}}/assets/js/jquery.js"></script>
    <script src="{{URL::to('/')}}/assets/js/home-main.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{URL::to('/')}}/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {

		  var curr_url = '<?php echo $currentPageUrl;?>';
		  //mixpanel.track("Landing Page",{"Page Type":'Registration','Url':curr_url});

		  var v = "Complete Signup";
			 /* $.ajax({
				type: "POST",
				url: "/login/set_reservation_location",
				data: {resv_loc : v},
				success: function(e) {
					//console.log(e);
				}
			});*/
          $('.btn').click(function(){
                error=0;
                if($("#password").val()==''){
                    $("#password_error").text("Please enter a password.");
                    $("#password").css('border','2px solid #B94A39');
                    $("#password_error").css('display','block');
                    error++;
                }
                else if($("#password").val().replace(/\s+$/,"")=='' || $("#password").val().replace(/\s+$/,"")=='******' ){ 
                    $("#password_error").text("Write correct password.");
                    $("#password").css('border','2px solid #B94A39');
                    $("#password_error").css('display','block');
                    error++;
                }
                else{
                    $("#password").css('border','0px');
                    $("#password_error").css('display','none');
                    mypassword=$("input[name='password']").val();
                }
                if(error >= 1){
                    return false
                } else {
					var currUrl = '<?php echo $currentPageUrl;?>';
                    $.ajax({
                        url: '/users/register',
                        data: {
                            'email' : $('#email').val(),
                            'password':$('#password').val(),
                            'full_name':$('#full_name').val(),
                            'city':$('#city').val(),
                            'phone':$('#phone').val(),
							'login_type':'Email',
							'reg_page':currUrl
                        },
                        type: 'POST',
                        async: false,
                        success:function(data){
                            if(data == 1){
                               window.location="{{URL::to('/')}}/"+$('#cityName').val()+"?signup=true"; 
                            } else{
                                $('.form1').hide();
                                $('.form2').show();
                            }
                        }
                    });
                }
          })
      });
    </script>
    <script type="text/javascript">
      (function($){

        $(document).on('ready', function(){

        var windowheight = $(window).height();
        if(windowheight > 565){
        $('#signup-abovefold').height($(window).height());
        }else{
        $('#signup-abovefold').height(565);
        }

        });
        
        
      })(jQuery)   
    </script> 
    <?php 
    if(!empty($codes)){
        foreach($codes as $code){
            echo $code['code'];
        }
    }
    ?>
  </body>
</html>
