<?php
    $user_array = Auth::User();
             
    if(isset($user_array->id) && Input::get('signup') != 'extraprofile'){
                redirect('/'.$user_array['city']); 
    }

    if(Input::has('ref') && !isset($user_array['logged_in']) ){

    }

    function currentPageURL() {
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
    $currentPageUrl =  currentPageURL();

    $session_user_status = Session::get('new_user_status');

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="robots" content="noindex,nofollow" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{!! $meta_information['seo_title'] or 'WowTables : Exclusive fine dining meals & experiences in your city' !!}</title>
<meta name="title" content="{!! $seo_title or 'WowTables' !!}">
<meta name="description" content="{!! $meta_information['meta_desc'] or 'Search, discover, reserve & experience fine dining in Mumbai, Delhi, Bangalore & Pune' !!}">
<meta name="keywords" content="{!! $meta_information['meta_keywords'] or 'WowTables' !!}">    
{!! Html::style('http://fonts.googleapis.com/css?family=PT+Sans:400,700') !!}
{!! Html::style('assets/css/app.css?ver=1.0.1') !!}
{!! Html::style('assets/css/bootstrap.min.css?ver=1.0.1') !!}
{!! Html::script('assets/js/respond.js') !!}
<link rel="icon" href="{{URL::to('/')}}/assets/img/WTFAV_Y.ico" type="image/x-icon">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
{!! Html::script('assets/js/jquery.js') !!}
{!! Html::script('assets/js/home-main.js') !!}
<script type="text/javascript">
    $(document).ready(function(){
        var sess_usr_status = '<?php echo $session_user_status;?>';
        if(sess_usr_status == 'false'){
            mixpanel.register({"New User":'False'});
        }
        var curr_url = '<?php echo $currentPageUrl;?>';
        mixpanel.track("Landing Page",{"Page Type":'Registration','Url':curr_url});

    });
</script>
</head>
<body class="home" id="header">
    <div class="container" style="margin-top:10px;max-width:700px;">
        @include('frontend.partials.notifications')
    </div>

    @yield('content')

    

@include('frontend.google_ana')
<?php
if(isset($_REQUEST['gclid'])){
    $this->input->set_cookie(array(
        'name' => 'google_add_words', 
        'value' => '1', 
        'expire' => time()+ 86500, 
        'domain' => 'dev.gourmetitup.com', 
        'path'  => '/')
    );
}
?>
<footer>
      <div class="container">
        <div class="row">
          <div class="col-md-3 visible-xs">
            <nav class="navbar navbar-default navbar-inverse" role="navigation">
              <!-- Brand and toggle get grouped for better mobile display -->
              <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand visible-xs">Other Links</a>
              </div>

              <!-- Collect the nav links, forms, and other content for toggling -->
              <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav block-list foot-menu">
                  <li class=""><a href="{{URL::to('/')}}/pages/about-us">About Us</a></li>
                  <li><a href="http://blog.gourmetitup.com/">Blog</a></li>
                  <li><a href="{{URL::to('/')}}/pages/press">Press</a></li>
                  <li><a href="#">Careers</a></li>
                  <li><a href="#">Partner With Us</a></li>
                  <li><a href="#">How It Works</a></li>
                  <li><a href="{{URL::to('/')}}/pages/faq">FAQ</a></li>
                  <li><a href="{{URL::to('/')}}/pages/contact-us">Contact Us</a></li>
                  <li><a href="http://www.finedinelove.com" target="_blank">FineDineLove</a></li>
                  <li><a href="{{URL::to('/')}}/pages/gift-cards">Gift cards</a></li>
                  <li><a href="#">Corporate Solutions</a></li>
                  <li><a href="#">Parties & Private Events</a></li>
                  <li><a href="{{URL::to('/')}}/pages/gourmet-rewards">Gourmet Rewards</a></li>
                  <li><a href="{{URL::to('/')}}/pages/terms-of-use">Terms of Use</a></li>
                  <li><a href="{{URL::to('/')}}/pages/privacy-policy">Privacy Statement</a></li>
                </ul>
              </div><!-- /.navbar-collapse -->
            </nav>
          </div>
          <div class="col-md-3 col-sm-6 hidden-xs">
            <h4 class="foot-widget-title">Company</h4>
              <ul class="block-list foot-menu">
                <li><a href="{{URL::to('/')}}/pages/about-us">About Us</a></li>
                <li><a href="http://blog.gourmetitup.com">Blog</a></li>
                <li><a href="{{URL::to('/')}}/pages/press">Press</a></li>
                <li><a href="{{URL::to('/')}}/pages/careers">Careers</a></li>
                <li><a href="{{URL::to('/')}}/pages/restaurant-partnerships">Restaurateurs</a></li>
                <li><a href="{{URL::to('/')}}/pages/advertising">Brand Partnerships</a></li>  
              </ul>
          </div>          
          <div class="col-md-3 col-sm-6 hidden-xs">
            <h4 class="foot-widget-title">Help</h4>
              <ul class="block-list foot-menu">
               <!-- <li><a href="#">How It Works</a></li> -->
                <li><a href="{{URL::to('/')}}/pages/faq">FAQ</a></li>
                <li><a href="{{URL::to('/')}}/pages/contact-us">Contact Us</a></li>
                <li><a href="{{URL::to('/')}}/pages/sitemap">Sitemap</a></li>
              </ul>
          </div>
          <div class="clearfix visible-sm"></div>
          <div class="col-md-3 col-sm-6 hidden-xs">
            <h4 class="foot-widget-title">More</h4>
              <ul class="block-list foot-menu">
                <ul class="block-list foot-menu">
                <li><a href="http://www.finedinelove.com" target="_blank">FineDineLove</a></li>
                <li><a href="{{URL::to('/')}}/pages/gift-cards">Gift cards</a></li>
                <li><a href="{{URL::to('/')}}/pages/corporate">Corporate Solutions</a></li>
                <li><a href="{{URL::to('/')}}/pages/event-planning">Parties & Private Events</a></li>
                <li><a href="{{URL::to('/')}}/pages/gourmet-rewards">Gourmet Rewards</a></li>
                <li><a href="{{URL::to('/')}}/pages/terms-of-use">Terms of Use</a></li>
                <li><a href="{{URL::to('/')}}/pages/privacy-policy">Privacy Statement</a></li>
              </ul>
          </div>
          <div class="col-md-3 col-sm-6">
            <h4 class="foot-widget-title">Follow us on</h4>
            <ul class="inline-list socialicons">
              <li><a href="http://www.facebook.com/GourmetItUp" target="blank"><img src="/assets/img/fb_icon.png" alt="Gourmet FB"></a></li>
              <li><a href="https://twitter.com/gourmetitup" target="blank"><img src="/assets/img/tw_icon.png" alt="Gourmet twitter"></a></li>
              <li><a href="https://plus.google.com/112736118414045928797/posts" target="blank"><img src="/assets/img/g+_icon.png" alt="Gourmet google+"></a></li>
            </ul>            
          </div>
        </div>
      </div>

      <div class="foot2">
        <div class="container">
          <div class="row">                      
            <div class="col-md-4 col-sm-4">
              <p class="copyright">&copy; All Rights reserved | Wowtables</p>
            </div>
            <div class="col-md-8 col-sm-8">
              <p class="pull-right concierge-info"><a href="#header">Login</a> to make a reservation online or Call our Concierge for assistance at 09619551387
                 <?php
                $arrdata = DB::table('codes')->where('view_pages', 'registration')
                  ->select('code')
                  ->get();
                  foreach ($arrdata as $value) {
                    echo $value->code;
                  }
                ?>
              </p> 
              <a style="float: right;" href="https://mixpanel.com/f/partner"><img src="//cdn.mxpnl.com/site_media/images/partner/badge_light.png" alt="Mobile Analytics" /></a>
            </div>
          </div>
        </div>
      </div>
    </footer>
    {!! Html::script('assets/js/bootstrap.min.js') !!}
</body>
</html>
