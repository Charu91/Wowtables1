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
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="apple-itunes-app" content="app-id=983901046">
<meta name="google-play-app" content="app-id=com.wowtables.android">

<title>{!! $meta_information['seo_title'] or 'WowTables: Exclusive fine dining meals & experiences in your city' !!}</title>
<meta name="title" content="{!! $seo_title or 'WowTables' !!}">
<meta name="description" content="{!! $meta_information['meta_desc'] or 'Search, discover, reserve & experience fine dining in Mumbai, Delhi, Bangalore & Pune' !!}">
<meta name="keywords" content="{!! $meta_information['meta_keywords'] or 'WowTables' !!}">    
{!! Html::style('http://fonts.googleapis.com/css?family=PT+Sans:400,700') !!}
{!! Html::style('assets/css/app.css?ver=1.0.1') !!}
{!! Html::style('assets/css/bootstrap.min.css?ver=1.0.1') !!}
{!! Html::script('assets/js/respond.js') !!}
<!-- new css for front-end design fixes -->
  <link href="{{URL::to('/')}}/assets/css/front_fixes.css?ver=1.0.5" rel='stylesheet' type="text/css">
<link rel="icon" href="{{URL::to('/')}}/assets/img/WTFAV_Y.ico" type="image/x-icon">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

<![endif]-->
{!! Html::script('assets/js/jquery.js') !!}
{!! Html::script('assets/js/home-main.js') !!}
{!! Html::script('assets/js/jquery.smartbanner.js') !!}

<style>
    #smartbanner { position:absolute; left:0; top:-82px; border-bottom:1px solid #e8e8e8; width:100%; height:78px; font-family:'Helvetica Neue',sans-serif; background:-webkit-linear-gradient(top, #f4f4f4 0%,#cdcdcd 100%); background-image: -ms-linear-gradient(top, #F4F4F4 0%, #CDCDCD 100%); background-image: -moz-linear-gradient(top, #F4F4F4 0%, #CDCDCD 100%); box-shadow:0 1px 2px rgba(0,0,0,0.5); z-index:9998; -webkit-font-smoothing:antialiased; overflow:hidden; -webkit-text-size-adjust:none; }
    #smartbanner, html.sb-animation {-webkit-transition: all .3s ease;}
    #smartbanner .sb-container { margin: 0 auto; }
    #smartbanner .sb-close { position:absolute; left:5px; top:5px; display:block; border:2px solid #fff; width:14px; height:14px; font-family:'ArialRoundedMTBold',Arial; font-size:15px; line-height:15px; text-align:center; color:#fff; background:#070707; text-decoration:none; text-shadow:none; border-radius:14px; box-shadow:0 2px 3px rgba(0,0,0,0.4); -webkit-font-smoothing:subpixel-antialiased; }
    #smartbanner .sb-close:active { font-size:13px; color:#aaa; }
    #smartbanner .sb-icon { position:absolute; left:30px; top:10px; display:block; width:57px; height:57px; background:rgba(0,0,0,0.6); background-size:cover; border-radius:10px; box-shadow:0 1px 3px rgba(0,0,0,0.3); }
    #smartbanner.no-icon .sb-icon { display:none; }
    #smartbanner .sb-info { position:absolute; left:98px; top:18px; width:44%; font-size:11px; line-height:1.2em; font-weight:bold; color:#6a6a6a; text-shadow:0 1px 0 rgba(255,255,255,0.8); }
    #smartbanner #smartbanner.no-icon .sb-info { left:34px; }
    #smartbanner .sb-info strong { display:block; font-size:13px; color:#4d4d4d; line-height: 18px; }
    #smartbanner .sb-info > span { display:block; }
    #smartbanner .sb-info em { font-style:normal; text-transform:uppercase; }
    #smartbanner .sb-button { position:absolute; right:20px; top:24px; border:1px solid #bfbfbf; padding: 0 10px; min-width: 10%; height:24px; font-size:14px; line-height:24px; text-align:center; font-weight:bold; color:#6a6a6a; background:-webkit-linear-gradient(top, #efefef 0%,#dcdcdc 100%); text-transform:uppercase; text-decoration:none; text-shadow:0 1px 0 rgba(255,255,255,0.8); border-radius:3px; box-shadow:0 1px 0 rgba(255,255,255,0.6),0 1px 0 rgba(255,255,255,0.7) inset; }
    #smartbanner .sb-button:active, #smartbanner .sb-button:hover { background:-webkit-linear-gradient(top, #dcdcdc 0%,#efefef 100%); }

    #smartbanner .sb-icon.gloss:after { content:''; position:absolute; left:0; top:-1px; border-top:1px solid rgba(255,255,255,0.8); width:100%; height:50%; background:-webkit-linear-gradient(top, rgba(255,255,255,0.7) 0%,rgba(255,255,255,0.2) 100%); border-radius:10px 10px 12px 12px; }

    #smartbanner.android { border-color:#212228; background: #3d3d3d url('dark_background_stripes.gif'); border-top: 5px solid #88B131; box-shadow: none; }
    #smartbanner.android .sb-close { border:0; width:17px; height:17px; line-height:17px; color:#b1b1b3; background:#1c1e21; text-shadow:0 1px 1px #000; box-shadow:0 1px 2px rgba(0,0,0,0.8) inset,0 1px 1px rgba(255,255,255,0.3); }
    #smartbanner.android .sb-close:active { color:#eee; }
    #smartbanner.android .sb-info { color:#ccc; text-shadow:0 1px 2px #000; }
    #smartbanner.android .sb-info strong { color:#fff; }
    #smartbanner.android .sb-button { min-width: 12%; border:1px solid #DDDCDC; padding:1px; color:#d1d1d1; background: none; border-radius: 0; box-shadow: none; min-height:28px}
    #smartbanner.android .sb-button span { text-align: center; display: block; padding: 0 10px; background-color: #42B6C9; background-image: -webkit-gradient(linear,0 0,0 100%,from(#42B6C9),to(#39A9BB)); background-image: -moz-linear-gradient(top,#42B6C9,#39A9BB); text-transform:none; text-shadow:none; box-shadow:none; }
    #smartbanner.android .sb-button:active, #smartbanner.android .sb-button:hover { background: none; }
    #smartbanner.android .sb-button:active span, #smartbanner.android .sb-button:hover span { background:#2AC7E1; }

    #smartbanner.windows .sb-icon { border-radius: 0px; }
</style>
</head>
<body class="home" id="header">
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-PQHMSR"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-PQHMSR');</script>
<!-- End Google Tag Manager -->
    <div class="container" style="margin-top:10px;max-width:700px;">
        @include('frontend.partials.notifications')
    </div>

    @yield('content')

    

{{--@include('frontend.google_ana')--}}
<?php
if(isset($_REQUEST['gclid'])){
    $cookiearray = array(
        'name' => 'google_add_words',
        'value' => '1',
        'domain' => 'wowtables.com',
    );

    setcookie("wowtables", "wowtables.com", time() + 86500, "/");
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
                      <li><a href="http://blog.gourmetitup.com">Blog</a></li>
                      <li><a href="{{URL::to('/')}}/pages/press">Press</a></li>
                      <li><a href="{{URL::to('/')}}/pages/careers">Careers</a></li>
                      <li><a href="{{URL::to('/')}}/pages/restaurant-partnerships">Restaurateurs</a></li>
                      <li><a href="{{URL::to('/')}}/pages/advertising">Brand Partnerships</a></li>
                      <li><a href="{{URL::to('/')}}/pages/faq">FAQ</a></li>
                      <li><a href="{{URL::to('/')}}/pages/contact-us">Contact Us</a></li>
                      <li><a href="http://www.finedinelove.com" target="_blank">FineDineLove</a></li>
                      <li><a href="{{URL::to('/')}}/pages/gift-cards">Gift cards</a></li>
                      <li><a href="{{URL::to('/')}}/pages/corporate">Corporate Solutions</a></li>
                      <li><a href="{{URL::to('/')}}/pages/event-planning">Parties & Private Events</a></li>
                      <li><a href="{{URL::to('/')}}/users/redeem-rewards">Gourmet Rewards</a></li>
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
                <li><a href="http://www.finedinelove.com" target="_blank">FineDineLove</a></li>
                <li><a href="{{URL::to('/')}}/pages/gift-cards">Gift cards</a></li>
                <li><a href="{{URL::to('/')}}/pages/corporate">Corporate Solutions</a></li>
                <li><a href="{{URL::to('/')}}/pages/event-planning">Parties & Private Events</a></li>
                <li><a href="{{URL::to('/')}}/users/redeem-rewards">Gourmet Rewards</a></li>
                <li><a href="{{URL::to('/')}}/pages/terms-of-use">Terms of Use</a></li>
                <li><a href="{{URL::to('/')}}/pages/privacy-policy">Privacy Statement</a></li>
              </ul>
          </div>
          <div class="col-md-3 col-sm-6">
            <h4 class="foot-widget-title">Follow us on</h4>
            <ul class="inline-list socialicons">
              <li><a href="http://www.facebook.com/WowTables" target="_blank"><img src="/assets/img/fb_icon.png" alt="Wowtables FB"></a></li>
              <li><a href="https://twitter.com/wow_tables" target="_blank"><img src="/assets/img/tw_icon.png" alt="Wowtables twitter"></a></li>
              <li><a href="https://instagram.com/WowTables/" target="_blank"><img src="/assets/img/instagram.png" alt="Wowtables Instagram"></a></li>
            </ul>            
          </div>
          <div class="col-md-3 col-sm-6">
            <h4 class="foot-widget-title">Download our app from</h4>
            <ul class="inline-list socialicons">
              <li><a href="https://itunes.apple.com/app/id983901046" target="_blank"><img src="/assets/img/apple.jpg" alt="Wowtables Apple iTunes"></a></li>
              <li><a href="https://play.google.com/store/apps/details?id=com.wowtables.android&hl=en" target="_blank"><img src="/assets/img/android.jpg" alt="Wowtables Andriod Playstore"></a></li>
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
    {!! Html::script('assets/js/bootstrap.min.js') !!}
</body>
</html>
