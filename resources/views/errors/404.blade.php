<?php
$user_array = Session::all();
$current_city = 'mumbai';
$current_city_id ='12';
$cities = array();
if (Session::has('logged_in'))
{
  $user_data=$user_array;
}
else{
    $user_data['full_name']='Guest';
}

 

if((Session::get('add_mixpanel_event') != 0 || Session::get('add_mixpanel_event')!= '')) 
{
    $chk_mixpanel_event = Session::get('add_mixpanel_event');
    $u_id = Session::get('id');
    $e_id = Session::get('email');
    $l_type = Session::get('login_type');
    $reg_page = Session::get('Registration_Page');
    $reg_loc = Session::get('reservation_location');
 }
 else
 {
    $chk_mixpanel_event = 'null';
    $u_id = 'null';
    $e_id = 'null';
    $l_type = 'null';
    $reg_page = 'null';
    $reg_loc = 'null';
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="robots" content="noindex,nofollow" />
<title>{!! $meta_information['seo_title'] or 'WowTables : Exclusive fine dining meals & experiences in your city' !!}</title>
<meta name="title" content="{!! $seo_title or 'WowTables' !!}">
<meta name="description" content="{!! $meta_information['meta_desc'] or 'Search, discover, reserve & experience fine dining in Mumbai, Delhi, Bangalore & Pune' !!}">
<meta name="keywords" content="{!! $meta_information['meta_keywords'] or 'WowTables' !!}">    
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="viewport" content="initial-scale=1, maximum-scale=1">
<meta name="viewport" content="initial-scale=2.3, user-scalable=no">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no">
<style>
    @media screen and (min-width: 0px) and (max-width: 600px) {     
      #city_ul_p {
        margin-top: -4.2% !important;
        padding-top: 0px !important;
      }
     .wowtables_city_color{
        color: #ab9d8a !important;
     }     
     .wowtable_dining_menu{
      float:left !important;
      color:#9d9d9c; !important;
      font-size:15px !important;
      line-height: 12px !important;
      }
      .wowtables_border_right{
      border-right:1px solid #9d9d9c !important;
      }
      
      #wowtable_content{ 
      display: block; 
      }
      #wowtable_img_margin{
        margin-left: -56% !important;
      }
      #wowtables_contents_1{
      margin-left:30%;
      margin-left: 71%;
      margin-top: -10%;
      }
      .wowtables_glyphicon{
        font-size: 22px;
        color: #ffffff;
      }
      .wowtable_divider{
        border:1px solid black !important;
      }     
      .wowtables_big_header{
      display:none !important;
      }   
      .wowtable_refer_friends{
      border-right:1px solid #f6f6f6 !important;
      font-size:14px !important;
      color:#979797 !important;
      margin-left: 18%;
      font-weight:none !important;
      font-family: Swis721 Lt BT !important;
      }
      .wowtables_map{
        font-size: 16px !important;
        padding-top: 9%;
        border-left: 2px solid #979797;
        padding-left: 137%;
        height: 4%;
      }
    }
    @media screen and (min-width: 320px) and (max-width: 359px) {
      #wowtables_width_size{
        padding-left:0px !important;
        padding-right:0px !important;
        width:30% !important;
      }
      #wowtables_width_size2{
        padding-left:0px !important;
        padding-right:0px !important;
        width:32% !important;
      }
      #wowtables_width_size3{
        padding-left:0px !important;
        padding-right:0px !important;
        width:38% !important;
      }
      .wowtables_city_dropdown{
        min-width: 366px !important;
        border-radius: 0px !important;
        left: -20px !important;
        background-color: #f0f0f0 !important;
        -webkit-box-shadow:none !important;
         border:none !important;
      }
      .wowtables_city_name{
        padding-top: 16px !important;
        padding-bottom: 7px !important;
        padding-left: 12% !important;
        color: #ab9d8a !important;
        font-family: inherit !important;
        font-size: 15px !important;
      }
      .wowtable_dining_menu{
        padding: 8px 12px !important;
       }
    
    }
    @media screen and (min-width: 360px) and (max-width: 379px) {
      #redirectloginModal .nav li.active a {
        color: #eabc3f !important;
        font-size: 103% !important;
        font-weight: 800;
      }
      #redirectloginModal .nav li a {
        background: #f0f0f0 !important;
        color: #9d9d9c !important;
        font-size: 103% !important;
      }
      #wowtables_width_size{
        padding-left:0px !important;
        padding-right:0px !important;
        width:31% !important;
      }
      #wowtables_width_size2{
        padding-left:7px !important;
        padding-right:0px !important;
        width:31% !important;
      }
      #wowtables_width_size3{
        padding-left:0px !important;
        padding-right:0px !important;
        width:38% !important;
      }
      .wowtables_city_dropdown{
        min-width: 400px !important;
        border-radius: 0px !important;
        left: -20px !important;
        background-color: #f0f0f0 !important;
        -webkit-box-shadow:none !important;
         border:none !important;
      }
      .wowtables_city_name{
        padding-top: 16px !important;
        padding-bottom: 7px !important;
        padding-left: 12% !important;
        color: #ab9d8a !important;
        font-family: inherit !important;
        font-size: 15px !important;
      }
      .wowtable_dining_menu{
        padding: 8px 18px !important;
       }
    
    }
    @media screen and (min-width: 380px) and (max-width: 419px) {
      #wowtables_width_size{
        padding-left:0px !important;
        padding-right:0px !important;
        width:30% !important;
      }
      #wowtables_width_size2{
        padding-left:0px !important;
        padding-right:0px !important;
        width:32% !important;
      }
      #wowtables_width_size3{
        padding-left:0px !important;
        padding-right:0px !important;
        width:38% !important;
      }
      .wowtables_city_dropdown{
        min-width: 430px !important;
        border-radius: 0px !important;
        left: -20px !important;
        background-color: #f0f0f0 !important;
        -webkit-box-shadow:none !important;
         border:none !important;
      }
      .wowtables_city_name{
        padding-top: 16px !important;
        padding-bottom: 7px !important;
        padding-left: 9% !important;
        color: #ab9d8a !important;
        font-family: inherit !important;
        font-size: 15px !important;
      }
      .wowtable_dining_menu{
        padding: 8px 18px !important;
       }
    
    }
    @media screen (max-width: 420px) {
      #wowtables_width_size{
        padding-left:0px !important;
        padding-right:0px !important;
        width:30% !important;
      }
      #wowtables_width_size2{
        padding-left:0px !important;
        padding-right:0px !important;
        width:32% !important;
      }
      #wowtables_width_size3{
        padding-left:0px !important;
        padding-right:0px !important;
        width:38% !important;
      }
      .wowtables_city_dropdown{
        min-width: 366px !important;
        border-radius: 0px !important;
        left: -20px !important;
        background-color: #f0f0f0 !important;
        -webkit-box-shadow:none !important;
         border:none !important;
      }
      .wowtables_city_name{
        padding-top: 16px !important;
        padding-bottom: 7px !important;
        padding-left: 9% !important;
        color: #ab9d8a !important;
        font-family: inherit !important;
        font-size: 15px !important;
      }
      .wowtable_dining_menu{
        padding: 8px 18px !important;
       }
    
    }
    @media screen and (min-width: 601px) and (max-width: 1990px) {
      #wowtable_content {
      display: none; 
      }
      .wowtables_header3{
      display:none !important;
      }
      .wowtables_city_name{
      padding-top:10px !important;
      }
    }
    .wowtable_font{
        font-family: Swis721 Lt BT !important;
    }
    .btn_city{
      color: #979797 !important;
      background-color: #F6F6F6 !important;
      border-color: #F6F6F6 !important;
    }
    .btn_city:active .btn_city:hover .btn_city.open{
      background-color:white !important;
    }
    .btn_city:hover{
      background-color:white !important;
    }
    .btn_city.open{
      background-color:white !important;
    }
    .wowtabls_btn {
      margin-bottom: 0;
      font-size: 12px !important;
      text-transform: capitalize;
      text-align: left;
      font-weight: 600 !important;
    }
    .btn-group.open .dropdown-toggle{
      border:1px solid #eee !important;
      box-shadow:none !important;
    }
    .wowtabls_btn.active {
      border:1px solid #eee !important;
      box-shadow:none !important;
    }
    .dropdown-menu>li>a{
      text-transform: capitalize;
    }
    hr{
      margin-top:5px !important;
      margin-bottom: -5px !important;
      border-top: 1px solid #c2c2c2 !important;
    }
    #city_ul_p{
      margin-top:-4.2% !important;
      padding-top: 0px !important;
    }
    .wowtable_sidebar{
      margin-left:7px !important;
      color: #979797 !important;
    }

  </style>
  <link href="{{URL::to('/')}}/assets/css/bootstrap.min.css?ver=1.0.4" rel="stylesheet" media="screen">
 <!--<link href="{{URL::to('/')}}/assets/css/all.css" rel="stylesheet" media="screen">-->
    <link href="{{URL::to('/')}}/assets/css/app.css?ver=1.0.4" rel="stylesheet" media="screen">
  <link href="{{URL::to('/')}}/assets/css/app_filters.css?ver=1.0.5" rel="stylesheet" media="screen">
    <link href="{{URL::to('/')}}/assets/css/datepicker.css?ver=1.0.4" rel="stylesheet" media="screen">  
  <link href="{{URL::to('/')}}/assets/css/app_20_11_2014.css?ver=1.0.4" rel="stylesheet" media="screen"> 

  <link href="{{URL::to('/')}}/assets/css/app_new1.css?ver=1.0.5" rel='stylesheet' type="text/css">
  <link href="{{URL::to('/')}}/assets/css/app_new2.css?ver=1.0.6" rel='stylesheet' type="text/css">
  <link href="{{URL::to('/')}}/assets/css/app_new3.css?ver=1.0.6" rel='stylesheet' type="text/css">
  <link href="{{URL::to('/')}}/assets/css/app_new4.css?ver=1.0.5" rel='stylesheet' type="text/css">
  <link href="{{URL::to('/')}}/assets/css/app_new5.css?ver=1.0.5" rel='stylesheet' type="text/css">
  <link href="{{URL::to('/')}}/assets/css/app_new6.css?ver=1.0.5" rel='stylesheet' type="text/css">

  <!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js?ver=1.0.2"></script>-->

    <script src="{{URL::to('/')}}/assets/js/jquery.js"></script>
    <!-- <link href='http://fonts.googleapis.com/css?family=Nunito:400,700' rel='stylesheet' type='text/css'> -->
    <!-- <link href='http://fonts.googleapis.com/css?family=Tenor+Sans' rel='stylesheet' type='text/css'> -->
    <!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'> -->
    <link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
 <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsEnNgLLhw0AFS4JfwsE1d3oTOeaWcccU&sensor=true"></script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="{{URL::to('/')}}/assets/js/html5shiv.js"></script>
      <script src="{{URL::to('/')}}/assets/js/respond.min.js"></script>
    <![endif]-->
<link rel="icon" href="{{URL::to('/')}}/assets/img/WTFAV_Y.ico" type="image/x-icon">
<script type="text/javascript">
  $('div').on('click',function(){
      $('div').removeClass('active');
      $(this).addClass('active');
  });
</script>

    <script type="text/javascript" src="https://www.curebit.com/javascripts/api/all-0.4.js?ver=1.0.2"></script>
<script type="text/javascript">
  $(document).ready(function(){

    var mixpanel_event_check = "<?php echo $chk_mixpanel_event;?>";
    //console.log("mixpanel_event_check = "+mixpanel_event_check);
    if(mixpanel_event_check == 'yes'){
      var userID = "<?php echo $u_id;?>";
      var emailID = "<?php echo $e_id;?>";
      var loginType = "<?php echo $l_type;?>";
      var regPage = "<?php echo $reg_page;?>";
      var reg_loc = "<?php echo $reg_loc;?>";
    //if (!mixpanel.get_property('Registered')) {
      mixpanel.track("Registration",{"User ID":userID,"Email ID":emailID,"Login Type":loginType,"Registration Page":regPage,"Registration Location":reg_loc});
      mixpanel.alias(userID);
      mixpanel.people.set({"$email": emailID});
      $.ajax({
          url: "{{URL::to('/')}}/login/unset_mixpanel_variable",
          type: "POST",
          dataType:"JSON",
          data:{mixpanel_event:'no'},
          success: function(d) {
            console.log(d);
          }
        
      });
      //mixpanel.register({'registered':true});
    //}
    }
  });
  

</script>

<script type="text/javascript">
$(document).ready(function(){
    $("#wowtables_user_icon").click(function(){
       $("#wowtables_services_list").show();
    $("#wowtables_cross_icon_user").show();
    $("#wowtables_city_icon").show();
    $("#wowtables_city_cross_icon").hide();
    $("#wowtables_city_list").hide();
    $("#wowtables_user_icon").hide();
    $("#city_ul_p").hide();
    $(".add_additional_class").removeClass("wowtables_header_padding_bottom");
    
    });
  $("#wowtables_user_icon1").click(function(){
        $("#wowtables_services_list").hide();
    $("#wowtables_cross_icon_user").hide();
    $("#wowtables_city_icon").show();
    $("#wowtables_city_cross_icon").hide();
    $("#wowtables_city_list").hide();
    $("#wowtables_user_icon").hide();
    $("#city_ul_p").hide();
    $(".add_additional_class").removeClass("wowtables_header_padding_bottom");
    
    });

  $("#wowtables_cross_icon_user").click(function(){
    $("#wowtables_services_list").hide();
    $("#wowtables_cross_icon_user").hide();
    $("#wowtables_city_icon").show();
    $("#wowtables_city_cross_icon").hide();
    $("#wowtables_city_list").hide();
    $("#wowtables_user_icon").show();
    $("#city_ul_p").hide();
    $(".add_additional_class").removeClass("wowtables_header_padding_bottom");
  })

  $("#wowtables_city_icon").click(function(){
    $("#wowtables_services_list").hide();
    $("#wowtables_cross_icon_user").hide();
    $("#wowtables_city_icon").hide();
    $("#wowtables_city_cross_icon").show();
    $("#wowtables_city_list").show();
    $("#wowtables_user_icon").show();
    $("#city_ul_p").show();
    $(".add_additional_class").addClass("wowtables_header_padding_bottom");
    
  })

  $("#wowtables_city_cross_icon").click(function(){
    $("#wowtables_services_list").hide();
    $("#wowtables_cross_icon_user").hide();
    $("#wowtables_city_icon").show();
    $("#wowtables_city_cross_icon").hide();
    $("#wowtables_city_list").hide();
    $("#wowtables_user_icon").show();
    $("#city_ul_p").hide();
    $(".add_additional_class").removeClass("wowtables_header_padding_bottom");
    
  })
  
});
</script> 
</head>
<body>
<?php

$uname = (isset($user_data['full_name']) && $user_data['full_name']!=''?$user_data['full_name']:$user_data['username']);

$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
if (strpos($url,'alacarte') !== false) {
  $set_referal_url = "alacartereferrals";
} else {
  $set_referal_url = "referrals";
}
?>
<header id="header" class="wowtables_header1" style="border-bottom:1px solid #b4b4b4;">
    <div class="container">
      <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-4">
          <a href="{{URL::to('/')}}/" class="site-logo"><img src="{{URL::to('/')}}//assets/img/wow.png" alt="GourmetItUp" id="wowtable_img_margin"></a>
        </div>
        <?php
          if (isset($referral) && is_array($referral) && !empty($referral['logo_image'])) {
              echo '<div class="col-md-4 col-sm-4 col-xs-4">';
              echo '<div class="partner_title">In partnership with:</div>';
              
              if (!empty($referral['website_url'])) {
                  echo '<a href="'.$referral['website_url'].'" target="_blank" class="site-logo">';
                  echo '<img src="/uploads/partners/'.$referral['logo_image'].'">';
                  echo '</a>';
              } else {
                  echo '<img src="/uploads/partners/'.$referral['logo_image'].'">';
              }
              
              echo '</div>';
          }
          ?>
         <?php if (isset($referral) && is_array($referral) && !empty($referral['logo_image'])) :?> 
              <div class="col-md-4 col-sm-4 col-xs-4 pull-right head-links">
         <?php else :?>
            <div class="col-md-8 col-sm-8 col-xs-8 pull-right head-links" id="wowtable_content">
              <div id="wowtables_contents_1">
          <span class="glyphicon glyphicon-map-marker wowtables_padding" id="wowtables_city_icon" style="color:#ffffff !important;font-size:22px !important;"></span>
          <span class="glyphicon glyphicon-remove wowtables_glyphicon wowtables_padding" id="wowtables_city_cross_icon" style="color:#ffffff !important;font-size:22px !important;display:none;"></span>
          
          <!--<a class="border_none header_loc" href="#" data-target="#redirectloginModal" data-page_loc="Header" data-toggle="modal">Sign In</a>-->
          <?php if(isset($user_data['full_name']) && $user_data['full_name']=='Guest'){?>
            <span class="glyphicon glyphicon-user wowtables_glyphicon" style="padding-left: 16px; !important;" id="wowtables_user_icon1" href="#" data-target="#redirectloginModal" data-page_loc="Header" data-toggle="modal" ></span>
          <?php } else {?>
            <span class="glyphicon glyphicon-user wowtables_glyphicon" id="wowtables_user_icon" style="padding-left: 16px; !important;"></span>
            <span class="glyphicon glyphicon-remove wowtables_glyphicon" id="wowtables_cross_icon_user" style="display:none;padding-left: 16px; !important"></span>
          <?php }?>
          
          
        </div>
            </div>
              <div class="col-md-8 col-sm-8 col-xs-8 pull-right head-links wowtables_big_header">       
        <?php endif;?>
          <ul class="nav navbar-nav navbar-right">
          <?php
          if ($current_city == "mumbai" || $current_city == "pune") {?>
            <li>
              <a href="{{URL::to('/')}}/<?php echo $current_city;?>" class="wowtable_font"> Dining Experiences </a>
            </li>
          
            <li>
              <a href="{{URL::to('/')}}/<?php echo $current_city;?>/alacarte" class="wowtable_font"> A la carte Reservations</a>
            </li>
          <?php }?>

      <?php if(isset($user_data['full_name']) && $user_data['full_name']=='Guest'): ?>
      <?php else: ?>
          </ul>
    <?php endif; ?>
        </div>
      </div>
    </div>
  </header>
<div class="clearfix"></div>

<header id="header1" class="wowtables_header2" style="background: #f6f6f6 !important;padding-top:4px !important;padding-bottom: 0px !important;margin-bottom: 30px !important;border-bottom:1px solid #e7e7e7 !important;">
      <div class="container">
        <div class="row">
          <div class="col-md-4 col-sm-3 col-xs-4">
            <div class="row">
              <div class="col-sm-7">
                <input type="hidden" id="uri_city" value="<?php echo $current_city; ?>"/>
                <input type="hidden" id="uri_city_id" value="<?php echo $current_city_id; ?>"/>
                <div class="row">
                  <div class="top-filter col-md-12">
                    <div class="filter-left">
                      <div class="btn-group btn-block wowtable_mobile_view" style="padding-top:5px !important;  padding-bottom:5px !important;">
                        <button type="button" class="btn btn-default btn-block dropdown-toggle wowtabls_btn btn_city wowtables_button_hide" data-toggle="dropdown" id="city_p">
                          <span class="glyphicon glyphicon-map-marker" style="margin-left: -41%;padding-right: 9%;font-size:16px !important;color:#9d9d9c !important;"></span>
                          <?php
                          $current_city = ($current_city ? $current_city : "mumbai");
                          echo $current_city;
                          ?>
                          <span class="caret wowtable_sidebar" style="color:#c8c8c8 !important;"></span>
                        </button>
                         <!--  <span class="glyphicon glyphicon glyphicon-chevron-up" id="wowtables_glyphicon_city"></span> -->
                          <ul class="dropdown-menu wowtables_city_dropdown" id="city_ul_p">
                          <?php foreach ($cities as $cur_city => $city_name_data): ?>
                            <?php
                              $selected = ($city_name_data == $current_city) ? ' selected' : ' ';
                            ?>                            
                            <li><a href="javascript:void(0);" class="wowtables_city_name"><?php echo $city_name_data;?></a></li><hr />
                          <?php endforeach; ?>
                          </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
         </div>
         
           <div class="col-md-8 col-sm-9 col-xs-8 pull-right head-links wowtables_hide_refer_menu">
            <ul class="nav navbar-nav navbar-right wowtables_border_color wowtables_tablet_size">
              <li>
                <a href="{{URL::to('/')}}/<?php echo $set_referal_url;?>" target="_blank" style="border-right:1px solid #f6f6f6 !important;font-size:12px !important;margin-right:-18px;color:#81ad5e !important;font-weight:none !important;"> Refer a friend and Get 3000 Gourmet points </a>
              </li>
              <?php if(isset($user_data['full_name']) && $user_data['full_name']=='Guest')
              {
                ?>
                <li>
                  <a class="border_none header_loc wowtable_font" href="#" data-target="#redirectloginModal" data-page_loc="Header" data-toggle="modal" style="color:#9d9d9c !important;font-size:13px !important;">Sign in | Register</a><?php //echo $base_url."?sign_in=true"?>
              </li>
              <?php
              } ?>
              <li class="dropdown">
                <?php if($uname != "Guest") {?>
                  <a href="javascript:void(0);{{URL::to('/')}}/users/myaccount" class="dropdown-toggle" data-toggle="dropdown" style="color:#9d9d9c !important;font-size:12px !important;font-weight:none !important;text-transform:capitalize;"><?php echo $uname; ?><span style="padding-left:5px;"></span><span style="font-family: sans-serif !important;">(<?php $user = Auth::user(); echo $user->points_earned - $user->points_spent; ?> Pts.)</span><span class="caret" style="margin-left: 9px;color:#979797 !important;"></span></a>
                <?php }?>
                <?php if(isset($user_data['full_name']) && $user_data['full_name'] !='Guest'): ?>
                  <ul class="dropdown-menu wowtables_dropdown_menu">
                    <?php if(isset($user_data['user_role']) && $user_data['user_role'] == '1'):?>
                      <li><a href="{{URL::to('/')}}/admin">Admin</a></li>
                      <li><a href="{{URL::to('/')}}/adminreservations">Admin Reservations</a></li>
                    <?php endif;?>
                    <li> <a href="{{URL::to('/')}}/users/myreservations">My Reservations</a></li>
                    <li><a href="{{URL::to('/')}}/users/myaccount">My Profile</a></li>
                    <li><a href="{{URL::to('/')}}/redeem-rewards">Redeem Points</a></li>
                    <?php 
                    $logout = Cookie::get('token');
                    if(isset($user_array['facebook_id']) && !empty($user_array['facebook_id'])){
                    ?>
                    <li><a href="<?php echo $logout;?>">Logout</a></li>
                    <?PHP } else {?>
                    <li><a href="{{URL::to('/')}}/logout">Logout</a></li>          
                    <?PHP } ?>
                  </ul>
                <?php endif; ?>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </header>
<div class="clearfix"></div>

 <div class="row wowtables_services" id="wowtables_services_list" style="display:none;">
    <div class="col-sm-12">
      <!-- <span class="glyphicon glyphicon glyphicon-chevron-up" id="wowtables_glyphicon_services"></span> -->
      <?php if(isset($user_data['full_name']) && $user_data['full_name'] !='Guest'): ?>
      <?php if(isset($user_data['user_role']) && $user_data['user_role'] == 'Admin'):?>
        <button onClick="window.location.href='{{URL::to('/')}}/admin'" type="button" class="btn btn-primary btn-block wowtables_big_btn wowtables_border_width">Admin</button>
        <button onClick="window.location.href='{{URL::to('/')}}/adminreservations; ?>'" type="button" class="btn btn-primary btn-block wowtables_big_btn wowtables_border_width">Admin Reservations</button>
           <?php endif;?>
      <button onClick="window.location.href='{{URL::to('/')}}/users/myreservations'" type="button" class="btn btn-primary btn-block wowtables_big_btn wowtables_border_width">My Reservations</button>
      <button type="button" onClick="window.location.href='{{URL::to('/')}}/ users/myaccount'" class="btn btn-primary btn-block wowtables_big_btn wowtables_border_width">My Profile</button>
      <button type="button" onClick="window.location.href='{{URL::to('/')}}/ redeem-rewards'" class="btn btn-primary btn-block wowtables_big_btn wowtables_border_width">Redeem Gourmet Pts. <span style="padding-left:5px !important;"></span>(<?php //echo @$user['points_earned']-@$user['points_spent']; ?> Pts.)</button>
      <button type="button" onClick="window.location.href='{{URL::to('/')}}/ index.php/users/logout'" class="btn btn-primary btn-block wowtables_big_btn">Logout</button>
      <?php endif; ?>
      </div>
  </div>
  <div class="row wowtables_user_info" id="wowtables_user_information" style="display:none;">
    <div class="col-sm-12">
      <span class="glyphicon glyphicon-triangle-top" id="wowtables_glyphicon_user"></span>
      <button type="button" class="btn btn-primary btn-block wowtables_big_btn wowtables_border_width">Dining Experience</button>
      <button type="button" class="btn btn-primary btn-block wowtables_big_btn wowtables_border_width">A la Carte Reservation</button>
    </div>
  </div>

<header id="header" class="wowtables_header3 add_additional_class" style="background: #f6f6f6 !important;padding-top:4px !important;padding-bottom: 0px !important;margin-bottom: 30px !important;margin-top: -45px;border-bottom:1px solid #e7e7e7 !important;">
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12" style="padding-bottom:4px;">
              <div class="row">
          <?php

          if ($current_city == "mumbai" || $current_city == "pune") {?>
                <div class="col-xs-4" id="wowtables_width_size" style="border-right:1px solid #9d9d9c !important;">
                  <a href="{{URL::to('/')}}/ <?php echo $current_city;?>" class="wowtable_dining_menu">Experiences</a>
                </div>
                <div class="col-xs-4"  id="wowtables_width_size2" style="border-right:1px solid #9d9d9c !important;">
                  <a href="{{URL::to('/')}}/ <?php echo $current_city;?>/alacarte" class="wowtable_dining_menu">A la carte</a>
                </div>
          <?php }?>
                <div class="col-xs-4" id="wowtables_width_size3">
                  <a href="{{URL::to('/')}}/<?php echo $set_referal_url;?>" target="_blank"  class="wowtable_dining_menu">Refer a friend</a>
                </div>
              </div>
          
          </div>
            </div>            
          </div>
        </div>
      </div>
    </header>    
      
<?php    ?>

 <!-- <h1 style="text-align: center;
margin-bottom: 124px;
margin-top: 131px;">Error 404 - Not Found</h1> -->

<div class="container error-page">
      <div class="row">
        <div class="col-md-8 col-sm-8 col-md-offset-2 error-main-wrap">
          <div class="error-cover">
            <img src="{{URL::to('/')}}/assets/img/404.png" class="img-responsive" alt="404-error">
          </div>
          <h1 class="text-center">Sorry, this page could not be found</h1>
          <p class="lead text-center" style="font-style: italic; font-size: 135%;">Please click below to return to our home page and browse all our dining experiences.</p>
          <p class="text-center lead">
            <a href="http://wowtables.com/" class="btn btn-warning">Return to home page</a>
          </p>
          <!--<form role="search" class="navbar-form text-center">
            <div class="form-group">
              <input type="text" placeholder="Search" class="form-control">
            </div>
            <button class="btn btn-inverse" type="submit">Search</button>
          </form>-->
        </div>

        <div class="col-md-2">
          <p></p>
        </div>

        <!-- <div class="col-md-4 col-sm-4 deal-listing-right error-aside-wrap">
          <div class="widget browse-pages">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title text-center">Browse our pages</h3>
              </div>
              <div class="panel-body">
                <ul>
                  <li><a href="#">Home</a></li>
                  <li><a href="#">Blog</a></li>
                  <li><a href="#">Gift cards</a></li>
                  <li><a href="#">Gourmet rewards</a></li>
                  <li><a href="#">Contact us</a></li>                  
                </ul>
              </div>
            </div>
          </div>
          <div class="widget">
            <a href="#"><img alt="Top Rated Experiences" class="img-responsive" src="img/top-rated.jpg"></a>
            <div class="desc">
            <p>Top Rated Expriences</p>
            <p class="small">GourmetItUp brings you specially curated experiences â€“ Explore some of our top rated and best reviewed experiences.</p>
            </div>
          </div>
        </div> -->

      </div>
    </div>

 @yield('content')

   

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

function current_page_url() {
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
$current_page_url =  current_page_url();

if(isset($dropdowns_opt) && $dropdowns_opt == 1)
{
?>
<section class="related-experiences deal-detail">
      <div class="container">
        <div class="row wowtables_padding_left"> 
          <div class="bottom-filters">
    <ul>
        <li class="col-md-4 col-sm-4">
                <div class="btn-group dropup btn-block">
                  <button type="button" class="btn btn-default btn-block dropdown-toggle" data-toggle="dropdown" id="f_cuisine">
                    Experiences by Cuisine
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" id="f_cuisine_ul">
                  <?php 
                  if(isset($allCuisines))
                  {
                      foreach($allCuisines as $cousine): ?>
                     <li ><a rel="<?php echo strtolower($cousine); ?>" href="javascript:void(0);"><?php echo $cousine; ?></a></li>
                  <?php endforeach; 
                  }
                  ?>
                  </ul>
                </div>
              </li>
               <li class="col-md-4 col-sm-4">
                <div class="btn-group dropup btn-block">
                  <button type="button" class="btn btn-default btn-block dropdown-toggle" data-toggle="dropdown" id="f_area">
                    Experiences by Location
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" id="f_area_ul">
                  <?php 
                  if(isset($allAreas))
                  {
                      foreach($allAreas as $area_data): ?>
                     <li ><a rel="<?php echo strtolower($area_data['slug']); ?>" href="javascript:void(0);"><?php echo $area_data['name']; ?></a></li>
                     <?php endforeach; 
                  }
                  ?>
                  </ul>
                </div>
              </li>
               <li class="col-md-4 col-sm-4">
                <div class="btn-group dropup btn-block">
                  <button type="button" class="btn btn-default btn-block dropdown-toggle" data-toggle="dropdown" id="f_price">
                    Experiences by Price
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" id="f_price_ul">
                  <?php
                  if(isset($allPrices))
                  {
                   foreach($allPrices as $key => $price): ?>  
                     <li rel="<?php echo $key; ?>"><a rel="<?php echo strtolower($key); ?>" href="javascript:void(0);"><?php echo htmlspecialchars($price); ?></a></li>
                  <?php endforeach; 
                  }
                  ?>
                  </ul>
                </div>
              </li>
          </ul>
    </div> 
        </div>
      </div>
</section> 
<?php
}
?>
  <!--Share Modal -->

  <div class="modal fade" id="redirectloginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title text-center" id="myModalLabel">Please login / signup to make a reservation</h4>
        </div>
          <div class="modal-body">
  <p class="wowtables_doted"></p>
              <a href="javascript:void(0)" type="submit" onclick=popup() id="FBSignup" style="border-radius: 3px;margin-top:18px; !important"><span class="fbicon"></span> Log in with Facebook</a>
              <p class="text-center" style="color:#9d9d9c;">OR</p>            
          <!-- Nav tabs -->
          <ul class="nav nav-pills nav-justified wowtables-nav">
            <li class="wowtables_signin active" style="padding-left:19%;padding-right:1% !important;border-right:1px solid #9d9d9c;"><a href="#signin" data-toggle="tab">SIGN IN</a></li>          
            <li class="wowtables_signup" style="padding-right:21%;"><a href="#signup" data-toggle="tab">SIGN UP</a></li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content" style="border-top:1px dashed #c2c2c2;margin-top:20px;">
            <div class="tab-pane fade in active" id="signin">
            <div id="signinForm-wrap" style="  padding-top: 20px !important;">
              <form role="form" method="POST" >
                <div class="form-group">
                  <input type="email" class="form-control" id="email1" placeholder="Enter email" name="email">
                  <label class="control-label error-code text-danger" id="email_error_1">Please enter a valid email address</label>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" id="pass1" placeholder="Password" name="password">
                  <input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI'];?>">
                  <label class="control-label error-code text-danger" id="password_error_1">Please enter a password.</label>
                  <p class="text-right pass-forget"><a href="javascript:void(0)" class="forgot-pass-link">Forgot Password?</a></p>
                </div>                      
                <button type="button" class="btn btn-warning btn-block wowtable_enter_btn" id="logine">ENTER</button>
              </form>
              </div>
              <div id="forgotpassForm">
                <div class="info-text">
                  <h5 class="text-center" style="color: #725A32;">ENTER YOUR EMAIL ADDRESS</h5>
                </div>
                <form role="form" method="POST" action="{{URL::to('/')}}users/forgot_password" id='forgot_password'>
                  <div class="form-group">
                    <input type="email" class="form-control" id="f_pass" placeholder="Enter email" name="forgotemail">
                    <label class="control-label error-code text-danger" id='wrong_email'>Please enter your email address</label>
                  </div>                     
                  <button type="submit" class="btn btn-warning btn-block">Get Reset Link</button>
                  <p class="text-center pass-forget"><a href="#" class="login-link">Go back to Login</a></p>
                  <p class="text-center pass-forget check-email hidden" id='f_response'>
                      New password has been emailed to you
                  </p>
                </form>
              </div> <!-- forgot pass end -->
              
            </div>
            <div class="tab-pane fade" id="signup">                
              <div id="signupFormwrap" style="padding-top:20px;">
              <form role="form">
                <div class="form-group">
                  <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email" name="email1">
                  <label class="control-label error-code text-danger" id="email_error">Please enter a valid email address</label>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password1">
                  <label class="control-label error-code text-danger" id="password_error">Write correct password</label>
                </div>
                <div class="form-group">
                  <select class="form-control" name="city" id="city">
                    <option value="-1">Choose A City</option>
                        <?php foreach ($cities as $city => $visibility): ?>
                                
                                    <?php if ($visibility != 'hidden'): ?>
                                        
                                    <option value="<?php echo $city?>"><?php echo ucfirst($visibility);?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                  </select>
                  <label class="control-label error-code text-danger" id="city_error">Please pick a city</label>
                </div>                      
                <input type="submit" class="btn btn-warning btn-block wowtable_enter_btn" name="send" id="send" value="REGISTER"/>
                 <input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI'];?>">
                 <input type="hidden" name="base_url" id="url" value="{{URL::to('/')}}">
                <small class="text-center comment"><span style="color: #ab9d8a !important;padding-left: 16%;">By Joining, you agree to our</span><a href="{{URL::to('/')}}/terms-of-use" style="color: #81ad5e !important;"> Terms & Conditions</a>
                </small>
              </form>
              </div>
              <div id="signupSecondscreen">
                <div class="info-text">
                  <h4 class="text-center">THANK YOU FOR JOINING!</h4>
                  <p class="text-center">Help us customize your experience further.</p>
                </div>
                <form role="form">
                  <div class="form-group">
                    <input type="text" class="form-control" id="full_name" placeholder="Full Name" name='full_name'>
                    <label class="control-label error-code text-danger" id="error_name">Please enter your first and last name</label>
                  </div>
                  <div class="form-group gender-checkbox">
                    <div>
                    <label class="radio-inline">
                      <input type="radio" name="generRadios" id="inlineRadio1" value="option1"><span>Male</span>
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="generRadios" id="inlineRadio2" value="option2"><span>Female</span>
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
          </div>
          
          </div>
        </div>
      </div><!-- /.modal-content -->



    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->


        <!-- Modal -->
    <div class="modal fade" id="fbSelectCity" tabindex="-5" role="dialog" aria-labelledby="fbSelectCityLabel" aria-hidden="true">
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
                <li class="list-group-item"><a href="#" data-dismiss="modal">Mumbai</a></li>
                <li class="list-group-item"><a href="#" data-dismiss="modal">Delhi</a></li>
                <li class="list-group-item"><a href="#" data-dismiss="modal">Pune</a></li>
        <li class="list-group-item"><a href="#" data-dismiss="modal">Bangalore</a></li>
              </ul>
            </div>
          </div>
          
        </div><!-- /.modal-content -->
      </div>
    </div>
<a href="#" class="scrollToTop"><span id="scroll_top_display">Top</span> &and;</a>
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
                  <li><a href="#">Careers</a></li>
                  <li><a href="#">Partner With Us</a></li>
                  <li><a href="#">How It Works</a></li>
                  <li><a href="{{URL::to('/')}}/pages/faq">FAQ</a></li>
                  <li><a href="{{URL::to('/')}}/pages/contact-us">Contact Us</a></li>
          <li><a href="http://www.finedinelove.com" target="_blank">FineDineLove</a></li>
                  <li><a href="{{URL::to('/')}}/pages/gift-cards">Gift cards</a></li>
                  <li><a href="#">Corporate Solutions</a></li>
                  <li><a href="#">Parties & Private Events</a></li>
                  <li><a href="{{URL::to('/')}}/pages/redeem-rewards">Gourmet Rewards</a></li>
                  <li><a href="#">Terms of Use</a></li>
                  <li><a href="#">Privacy Statement</a></li>
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
               <!-- <li><a href="{{URL::to('/')}}/sitemap">Sitemap</a></li>-->
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
                <li><a href="{{URL::to('/')}}/pages/redeem-rewards">Gourmet Rewards</a></li>
                <li><a href="{{URL::to('/')}}/pages/terms-of-use">Terms of Use</a></li>
                <li><a href="{{URL::to('/')}}/pages/privacy-policy">Privacy Statement</a></li>
              </ul>
          </div>
          <div class="col-md-3 col-sm-6">
            <h4 class="foot-widget-title">Follow us on</h4>
            <ul class="inline-list socialicons">
              <li><a href="http://www.facebook.com/GourmetItUp"><img src="{{URL::to('/')}}/assets/img/fb_icon.png" alt="Gourmet FB"></a></li>
              <li><a href="https://twitter.com/gourmetitup"><img src="{{URL::to('/')}}/assets/img/tw_icon.png" alt="Gourmet twitter"></a></li>
              <li><a href="https://plus.google.com/112736118414045928797/posts"><img src="{{URL::to('/')}}/assets/img/g+_icon.png" alt="Gourmet google+"></a></li>
            </ul>            
          </div>
        </div>
      </div>

      <div class="foot2">
        <div class="container">
          <div class="row">                      
            <div class="col-md-4 col-sm-4">
              <p class="copyright">&copy; All Rights reserved | WowTables</p>
            </div>
            <div class="col-md-8 col-sm-8">
              <p class="pull-right concierge-info">Login to make a reservation online or Call our Concierge for assistance at 09619551387
                <?php
                /*$arrdata = DB::table('codes')->where('view_pages', 'all')
                  ->select('code')
                  ->get();
                  foreach ($arrdata as $value) {
                    echo $value->code;
                  }*/
                ?>
  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-1717133-12', 'gourmetitup.com');
  ga('send', 'pageview');

</script>
<!-- ClickTale Top part -->
<script type="text/javascript">
var WRInitTime=(new Date()).getTime();
</script>
<!-- ClickTale end of Top part -->
<script type='text/javascript'>
document.write(unescape("%3Cscript%20src='"+
(document.location.protocol=='https:'?
"https://clicktalecdn.sslcs.cdngc.net/www07/ptc/17b8ec97-b2d4-4809-ae36-b15d4088ca9e.js":
"http://cdn.clicktale.net/www07/ptc/17b8ec97-b2d4-4809-ae36-b15d4088ca9e.js")+"'%20type='text/javascript'%3E%3C/script%3E"));
</script>
<!-- begin SnapEngage code -->
<script type="text/javascript">
  (function() {
    var se = document.createElement('script'); se.type = 'text/javascript'; se.async = true;
    se.src = '//commondatastorage.googleapis.com/code.snapengage.com/js/73f5004b-0538-4182-a8dc-ba74b178db61.js';
    var done = false;
    se.onload = se.onreadystatechange = function() {
      if (!done&&(!this.readyState||this.readyState==='loaded'||this.readyState==='complete')) {
        done = true;
        // Place your SnapEngage JS API code below
        // SnapEngage.allowChatSound(true); // Example JS API: Enable sounds for Visitors. 
      }
    };
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(se, s);
  })();
</script>
<!-- end SnapEngage code -->  
  <script type="text/javascript">
      var heap=heap||[];heap.load=function(a){window._heapid=a;var b=document.createElement("script");b.type="text/javascript",b.async=!0,b.src=("https:"===document.location.protocol?"https:":"http:")+"//cdn.heapanalytics.com/js/heap.js";var c=document.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c);var d=function(a){return function(){heap.push([a].concat(Array.prototype.slice.call(arguments,0)))}},e=["identify","track"];for(var f=0;f<e.length;f++)heap[e[f]]=d(e[f])};
      heap.load("966329605");
    </script><script>(function() {
  var _fbq = window._fbq || (window._fbq = []);
  if (!_fbq.loaded) {
    var fbds = document.createElement('script');
    fbds.async = true;
    fbds.src = '//connect.facebook.net/en_US/fbds.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(fbds, s);
    _fbq.loaded = true;
  }
  _fbq.push(['addPixelId', '1491404511074517']);
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', 'PixelInitialized', {}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=1491404511074517&amp;ev=NoScript" /></noscript><script>(function() {
  var _fbq = window._fbq || (window._fbq = []);
  if (!_fbq.loaded) {
    var fbds = document.createElement('script');
    fbds.async = true;
    fbds.src = '//connect.facebook.net/en_US/fbds.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(fbds, s);
    _fbq.loaded = true;
  }
  _fbq.push(['addPixelId', '886637294682125']);
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', 'PixelInitialized', {}]);
</script>
<noscript>
<img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=886637294682125&amp;ev=PixelInitialized" /></noscript>

              </p> 
        <a style="float: right;" href="https://mixpanel.com/f/partner"><img src="//cdn.mxpnl.com/site_media/images/partner/badge_light.png" alt="Mobile Analytics" /></a>
            </div>
          </div>
        </div>
      </div>

</footer>
<?php    

     $old_query=Request::segment(1);
     $old_query_array=explode("-", $old_query);
     if(count($old_query_array)>=3){
        for($i=0;$i<count($old_query_array);$i++)
        {    if(isset($old_query_array[$i+2])){
                 if($old_query_array[$i]=="all" && $old_query_array[$i+2]=="experiences")
                 {
                     $f_cuisine=$old_query_array[$i+1];
                 }
              }
             if($old_query_array[$i])
             {
                 if($old_query_array[$i]=="in")
                 {
                     $str = preg_replace("/[0-9]/", "", $old_query_array[$i+1]);
                     $str= preg_replace("/%/", " ", $str);
                     $f_area=$str;
                     //out($old_query_array);
                     //out($f_area);
                 } 
             }
         }
         $price=  $old_query_array[count($old_query_array)-1];
         $pos = strrpos($price, "%");
         if($pos==FALSE){
            $pric = preg_replace("/[^0-9]/", '', $price);
            if(is_numeric($pric)){
                $f_price=$price;
            }
         }
     }
    ?>
    <input type="hidden" id="cuis" value="<?php  if(isset($f_cuisine)) echo $f_cuisine ; ?>">
    <input type="hidden" id="are" value="<?php if(isset($f_area)) echo $f_area; ?>">
    <input type="hidden" id="pric" value="<?php if(isset($f_price))echo $f_price; ?>">
    <input type="hidden" id="cur_city" value="<?php if(isset($current_city))echo  $current_city;?>">
      <?php
        if(isset($rows[1]) && !empty($rows[1])){
            if ($rows[1]['start_date'] != '0000-00-00') {
                if($rows[1]['start_date'] < date('Y-m-d')) 
                {
                    $startDate = 'new Date()';
                }
                else
                {
                    $tmp = explode('-', $rows[1]['start_date']);
                    $startDate = 'new Date('.$tmp[0].','.($tmp[1]-1).','.$tmp[2].')';    
                }                           
                
            } else {
                $startDate = 'new Date()'; 
            }
            if ($rows[1]['end_date'] != '0000-00-00') {
                $tmp = explode('-', $rows[1]['end_date']);
                $endDate = 'new Date('.$tmp[0].','.($tmp[1]-1).','.$tmp[2].')';
            } else {
                $endDate = '\'\'';
            }
        }    
    if(isset($restaurant) && !empty($restaurant)) {
      if ($restaurant[0]['start_date'] != '0000-00-00') {
                if($restaurant[0]['start_date'] < date('Y-m-d')) 
                {
                    $res_startDate = 'new Date()';
                }
                else
                {
                    $tmp = explode('-', $restaurant[0]['start_date']);
                    $res_startDate = 'new Date('.$tmp[0].','.($tmp[1]-1).','.$tmp[2].')';    
                }                           
                
            } else {
                $res_startDate = 'new Date()'; 
            }
            if ($restaurant[0]['end_date'] != '0000-00-00') {
                $tmp = explode('-', $restaurant[0]['end_date']);
                $res_endDate = 'new Date('.$tmp[0].','.($tmp[1]-1).','.$tmp[2].')';
            } else {
                $res_endDate = '\'\'';
            }
    }
    
    //for alacarte reservation module on alacarte detail page
    if(isset($alacarte_start_date) && $alacarte_start_date != ""){
      if($alacarte_start_date != '0000-00-00'){
        if($alacarte_start_date < date('Y-m-d')) 
        {
          $alacarte_startDate = 'new Date()';
        }
        else
        {
          $tmp = explode('-', $alacarte_start_date);
          $alacarte_startDate = 'new Date('.$tmp[0].','.($tmp[1]-1).','.$tmp[2].')';    
        }
      } else {
        $alacarte_startDate = 'new Date()'; 
      }
    }
    ?>    
@include('frontend.google_ana')   
<style>
.wowtables_doted{
  border-top: 1px dashed #c2c2c2;
  margin-top: -16px !important;
}

.wowtables_fonts_resturents{
  color: #ab9d8a !important;
  font-weight: 600;
  font-size: 15px !important;
  font-family: Swis721 Lt BT Light !important;
}
.nav-pills>li+li {
  margin-left: 2px !important;
  padding-left: 5px !important;
}

.nav>li>a{
  padding: 2px 15px !important;
}
.wowtables-nav{
  margin-top: -3px !important;
}
.modal-body {
  background: #f0f0f0 !important;
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
}
.bookmark_boot_plan{
    margin-left: 89% !important;
    margin-top: -12px !important;
  }
.scrollToTop{
  width:60px; 
  height:30px;
  padding:5px; 
  padding-right: 19px;
  text-align:center; 
  font-weight: bold;
  color: #fff;
  text-decoration: none;
  position:fixed;
  top:60%;
  right:0px;
  display:none;
  -webkit-border-radius: 4px 0px 0px 4px;
  -moz-border-radius: 4px 0px 0px 4px;
  border-radius: 4px 0px 0px 4px;
  border:1px  #000;
  background-color:#EA9403;
  cursor: pointer;
  font:medium 13px/28px 'PT Sans', sans-serif;
  z-index:999;
}
.scrollToTop:hover{
  text-decoration:none;
  color:#000;
  z-index:999;
}
#exp_list_load_layer{
    background: url("/images/b.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
      height: 100%;
      padding-bottom: 24px;
      position: absolute;
      top: 0;
      width: 100%;
      z-index: 10;
  }
  #exp_list_load_layer img {
      left: 45%;
      position: absolute;
      top: 1%;
  }
</style>
<!--css for scrolltotop ends here-->    
    
   
    <?php if(isset($allow_guest) && $allow_guest == "Yes"):?>
     <script src="{{URL::to('/')}}/assets/js/exp_details_signin.js?ver=1.0.1"></script> 
    <?php endif;?>
    
    <?php 
    $method = Request::segment(1);//$this->router->fetch_class();
    if($method != 'adminreservations'):?>
    <script src="{{URL::to('/')}}/assets/js/scripts.js?ver=1.0.1"></script>
    <?php endif;?>
    <script type="text/javascript" src="{{URL::to('/')}}/js/jquery-ui-1.10.0.custom.min.js?ver=1.0.1"></script>
  <script type="text/javascript" src="{{URL::to('/')}}/assets/js/jquery-ui.min.js?ver=1.0.1"></script>
    <script src="{{URL::to('/')}}/assets/js/bootstrap.min.js?ver=1.0.1"></script>
    <?php 
    if($method == 'adminreservations'):?>
      <script src="{{URL::to('/')}}/assets/js/adminreservations.js?ver=1.3"></script>
    <?php endif;?>
     <script src="{{URL::to('/')}}/assets/js/home-main.js?ver=1.0.1"></script> 
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js?ver=1.0.4"></script>
<!-- Google Code for Remarketing Tag -->
<!--------------------------------------------------
Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. See more information and instructions on how to setup the tag on: http://google.com/ads/remarketingsetup
---------------------------------------------------->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 996473455;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/996473455/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<?php $act = Request::segment(1);//$this->router->fetch_method();?>
<?php if($act == "exp" || $act = "lists"):?>
<script type="text/javascript">

 function popup(){ 
    myWindow=window.open("{{URL::to('/')}}//login/index","_blank","scrollbars=1,resizable=1,height=300,width=450");
    myWindow.moveTo(500,200);myWindow.focus();intervalID=window.setInterval(checkWindow,500)}

     function checkWindow() {
      try {
        if(myWindow.location.href.indexOf("wowtables.com") >= 0){
      //console.log("cookie created");
      //setCookie('add_event_mixpanel','yes',1);
            myWindow.close();
            myWindow.clearInterval(intervalID);
              if(myWindow.location.href.indexOf("mumbai") >= 0 || myWindow.location.href.indexOf("delhi") >= 0 || myWindow.location.href.indexOf("pune") >= 0){
                window.location.href = "<?php echo $_SERVER['REQUEST_URI'];?>";
                
                }
              else { 
                  $('#redirectloginModal').modal('hide');
          var pageUrl = "<?php echo $current_page_url;?>";
          $.ajax({
            url: "{{URL::to('/')}}/login/registration_page_url",
            type: "POST",
            dataType: "json",
            data:{page_url: pageUrl},
            success: function(d) {
            }
          });
                  $("#fbSelectCity").modal('toggle');
        }
        /*var uID = "< ?php echo $this->session->userdata['id'];?>";
        var eID = "< ?php echo $this->session->userdata['email'];?>"; 

        mixpanel.track("Registration",{"User ID":uID,"Email ID":eID,"Login Type":"Facebook"});*/
         }
      } catch(e) {
     
      }
        if(myWindow && myWindow.closed) {
          myWindow.clearInterval(intervalID);
        }
    }

         var logged_in = "<?php echo isset($user_data['full_name'])?$user_data['full_name']:''; ?>";
        <?php if(empty($logged_in) && (isset($details['make_reservation_opt']) && $details['make_reservation_opt']==1)):?>
        logged_in = "1";
        <?php endif ?> 
        
        $(document).ready(function(){
      
      <?php if(isset($rows[1]) && !empty($rows[1])) {?>
            var bl_dates = Array();
            <?php 
                if(!empty($block_dates) && is_array($block_dates)){
                    $tmp_bl_date = array();
                    foreach($block_dates as $bl_date){
                        if($bl_date['block_time'] != $rows[1]['start_time'].'-'.$rows[1]['end_time'] && !empty($bl_date['block_time'])){
                            $tmp_bl_date[][$bl_date['block_time']] = $bl_date['block_date']; 
                        }
                    }
            ?>
                bl_dates = <?php echo json_encode($tmp_bl_date);?>;
            <?php           
                }
            ?>
            window.schedule = <?php echo json_encode($schedule);  ?>;
            $("#choose_date").datepicker({
            dateFormat: 'yy-m-d',
            minDate: <?php echo $startDate; ?>,
            maxDate: <?php echo $endDate; ?>,
            onSelect: function(dateText, inst) {
                var d = $.datepicker.parseDate("yy-m-dd",  dateText);
                /*var bl_time_start = "";
                var bl_time_end = "";*/

        var bl_time_start = [];
                var bl_time_end = [];
                $.each(bl_dates,function(key,val){
                    $.each(val,function(k,v){
                        if($.datepicker.formatDate( "yy-mm-dd", d) == v){
                            key = k.split('-');
                            /*bl_time_start = key[0];
                            bl_time_end = key[1];*/
              bl_time_start.push(key[0]);
                            bl_time_end.push(key[1]);
                        }
                    })
                })
                var datestrInNewFormat = $.datepicker.formatDate( "D", d).toLowerCase();
                var txt = '<div class="btn-group col-lg-10 pull-right actives ">';
                var txt2 = '';
                var g = 1;
              var cur_date =  new Date('<?php echo date('d M Y H:i:s'); ?>');
              month = parseInt(cur_date.getMonth());
                month += 1;
                c_date = cur_date.getFullYear() + '-' + month +  '-'  + cur_date.getDate();
                c_time = cur_date.getHours()+":"+((cur_date.getMinutes()<10)?'0':'')+cur_date.getMinutes();
                for(key_sch in schedule[datestrInNewFormat])
                {   
                    
                    var obj_length = Object.keys(schedule[datestrInNewFormat]).length;
                    active_tab = (g == obj_length) ? 'active' : '' ;
                    active_blck = (g == obj_length) ? '' : 'hidden' ;  
                    txt+= '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="'+key_sch+'">'+key_sch.toUpperCase()+'</label>';
                    txt2 +=    '<div id="' + key_sch + '_tab"  class="'+active_blck+'">';
                    for(key_sch_time in schedule[datestrInNewFormat][key_sch])
                    {
            
              var is_valid = true;
              for (var i = 0; i < bl_time_start.length; i++) {
                
                if (String(key_sch_time) < String(bl_time_start[i]) || String(key_sch_time) > String(bl_time_end[i])) {
                  console.log(String(key_sch_time)+" < "+String(bl_time_start[i])+" || "+String(key_sch_time) +"> "+String(bl_time_end[i]))
                  is_valid = is_valid && true;

                } else {
                  is_valid = is_valid && false;

                }

              }
              if(is_valid) {
                txt2 += '<div class="time col-lg-3 col-xs-5" rel="' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '"><a href="javascript:">' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '</a></div>';
              }
                                              
                    }
                    txt2+= '</div>';    
                    g++;
                }
                <?php if(isset($schedule_times)): ?>  
                    var schedule_times = <?php echo json_encode($schedule_times)?>;
                    var datestrInNewFormat = $.datepicker.formatDate( "D", d).toLowerCase();
                    for(key in schedule_times[datestrInNewFormat])
                    { 
                        var obj_length = Object.keys(schedule_times[datestrInNewFormat]).length; 
                        active_tab = (g == obj_length) ? 'active' : '' ;
                        active_blck = (g == obj_length) ? '' : 'hidden' ;
                        txt+= '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="'+key+'">'+key.toUpperCase()+'</label>';
                        txt2 +=    '<div id="' + key + '_tab"  class="'+active_blck+'">';
                       
                        for(key_sch_time in schedule_times[datestrInNewFormat][key])
                        {
                            txt2+= '<div class="time col-lg-3" rel="' + schedule_times[datestrInNewFormat][key][key_sch_time] + '"><a href="javascript:">' + schedule_times[datestrInNewFormat][key][key_sch_time]+ '</a></div>';                        
                        txt2+= '</div>';    
                        g++;                               
                         }
                    }
                <?php endif; ?>
                txt += '</div><div class="clearfix"></div>';
                txt += '<input type="hidden" name="booking_time" id="booking_time" value="">';
                    $('#hours').html(txt2);
                    $('#time').html(txt);
                d = new Date('<?php echo date('d M Y H:i:s'); ?>');
                month = parseInt(d.getMonth());
                month += 1;
                current_date = d.getFullYear() + '-' + month +  '-'  + d.getDate();
                var select_table = $('#select_table');
                var cant_select_table = $('#cant_select_table');
                if (current_date == dateText &&  (d.getHours() > 20 || (d.getHours() >= 20 && d.getMinutes()>=30))) {
                    cant_select_table.removeClass('hidden');
                    if(!select_table.hasClass('hidden')){
                        select_table.addClass('hidden');   
                    }
                } else{
                    if(!cant_select_table.hasClass('hidden')){
                        cant_select_table.addClass('hidden');   
                    }
                    $('#booking_date').val(dateText);
                    //$('#date_edit1 span').text(formatDate(dateText));
                    $('#date_edit1 span').text("date comes here");
                    $('#date_edit1').click();
                    timehide=0;
                    $('#time_edit1').click();    
                }
                
            },
            beforeShowDay: function(date) {
                var is_event = "<?php echo $rows[1]['is_event'];?>";
                if( is_event == 1 ){
                    var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
                    if($.inArray(y + '-' + (m+1) + '-' + d,schedule) != -1) {
                        return [true, '', ''];
                    } else{
                        return [false];
                    }
                    return date;
                } else{
                    var weekday_cnd = $.datepicker.formatDate( "D", date).toLowerCase();    
                }
                if(schedule[weekday_cnd] == undefined)
                {
                    return new Array(false);
                }
                <?php
                    
                    if(!empty($block_dates) && is_array($block_dates))
                    {
                        foreach($block_dates as $bd)
                        {       
                            if($bd['block_time'] == $rows[1]['start_time'].'-'.$rows[1]['end_time'] || empty($bd['block_time'])){
                                $tmp_bd[] = $bd['block_date'];   
                            }
                        }
                        $bd_dates = implode('","',$tmp_bd);
                        $bd_dates = '"' . $bd_dates . '"';
                    }              
                    
                    if(isset($bd_dates))
                    {
                ?>
                var bd_dates = new Array(<?php echo $bd_dates ?>);    
                tmp_date = $.datepicker.formatDate('yy-mm-dd', date);
                tmp_day = $.datepicker.formatDate('dd', date);
                for(var i in bd_dates)
                {
                    if(bd_dates[i] == tmp_date)
                    {      
                        return new Array(false,'closed_date');
                    }                      
                }
                <?php } if (!empty($rows[1]['weekdays'])): ?>
                
                var td = date.getDay();
                
                <?php
                    $dates = explode(',', $rows[1]['weekdays']);
                    $td = array();
                    $wd = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
                    foreach ($dates as &$date) {
                        $td[] = 'td != \''.$wd[$date].'\'';
                        $date = 'date.getDay() != '.$date;
                    }
                ?>
                var dxk = "<?php echo implode(' && ', $dates);?>";
                var dxkl = "<?php echo implode(' && ', $td);?>";
                var ret = [!(<?php echo implode(' && ', $dates);?>),'',!(<?php echo implode(' && ', $td);?>)?'':''];
                return ret;
                <?php endif; ?>
                <?php if(!empty($block_dates)):?>
                        <?php foreach($block_dates as $date): ?>
                            <?php
                                list($year, $month, $day) = explode('-', $date['block_date']);
                            ?>
                            var b_date = new Date('<?php echo $year; ?>', '<?php echo $month-1; ?>', '<?php echo $day; ?>');
                            var ret = [!(date == b_date),'',!(date == b_date)?'':''];                                                        
                            return ret;
                        <?php endforeach; ?>
                <?php endif; ?>
                return [date];
            }, 
        });
    <?php } ?>
  //#choose date ends here

    <?php if((isset($res_startDate) && $res_startDate != "") && $res_endDate) :?>
    window.ac_schedule = <?php echo json_encode($res_schedule_times);  ?>;
    var ac_bl_dates = Array();
    <?php 
        if(!empty($res_block_dates) && is_array($res_block_dates)){
            $tmp_bl_date = array();
            foreach($res_block_dates as $bl_date){
                if($bl_date['block_time'] != $rows[1]['start_time'].'-'.$rows[1]['end_time'] && !empty($bl_date['block_time'])){
                    $tmp_bl_date[][$bl_date['block_time']] = $bl_date['block_date']; 
                }
            }
    ?>
        ac_bl_dates = <?php echo json_encode($tmp_bl_date);?>;
    <?php           
        }
    ?>
    $("#ac_choose_date").datepicker({
            dateFormat: 'yy-m-d',
            minDate: <?php echo $res_startDate; ?>,
            maxDate: <?php echo $res_endDate; ?>,
            onSelect: function(dateText, inst) {
                var d = $.datepicker.parseDate("yy-m-dd",  dateText);
                var bl_time_start = "";
                var bl_time_end = "";
                $.each(ac_bl_dates,function(key,val){
                    $.each(val,function(k,v){
                        if($.datepicker.formatDate( "yy-mm-dd", d) == v){
                            key = k.split('-');
                            bl_time_start = key[0];
                            bl_time_end = key[1];
                        }
                    })
                })
                var datestrInNewFormat = $.datepicker.formatDate( "D", d).toLowerCase();
                var txt = '<div class="btn-group col-lg-10 pull-right ac_actives ">';
                var txt2 = '';
                var g = 1;
              var cur_date =  new Date('<?php echo date('d M Y H:i:s'); ?>');
              month = parseInt(cur_date.getMonth());
                month += 1;
                c_date = cur_date.getFullYear() + '-' + month +  '-'  + cur_date.getDate();
                c_time = cur_date.getHours()+":"+((cur_date.getMinutes()<10)?'0':'')+cur_date.getMinutes();
                for(key_sch in ac_schedule[datestrInNewFormat])
                {   
                    var obj_length = Object.keys(ac_schedule[datestrInNewFormat]).length;
                    active_tab = (g == obj_length) ? 'active' : '' ;
                    active_blck = (g == obj_length) ? '' : 'hidden' ;  
                    txt+= '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="ac_'+key_sch+'">'+key_sch.toUpperCase()+'</label>';
                    txt2 +=    '<div id="ac_' + key_sch + '_tab"  class="'+active_blck+'">';
                    for(key_sch_time in ac_schedule[datestrInNewFormat][key_sch])
                    {
                      if(dateText == c_date){
                        //console.log("key_sch_time = " + key_sch_time);
              if(key_sch_time>=c_time && (key_sch_time < bl_time_start || key_sch_time > bl_time_end)){
                          txt2+= '<div class="ac_time col-lg-3" rel="' + ac_schedule[datestrInNewFormat][key_sch][key_sch_time] + '"><a href="javascript:">' + ac_schedule[datestrInNewFormat][key_sch][key_sch_time] + '</a></div>';
                          }     
                      } else{
                          if(key_sch_time < bl_time_start || key_sch_time > bl_time_end){
                          txt2+= '<div class="ac_time col-lg-3" rel="' + ac_schedule[datestrInNewFormat][key_sch][key_sch_time] + '"><a href="javascript:">' + ac_schedule[datestrInNewFormat][key_sch][key_sch_time] + '</a></div>';
                          } 
                        }                       
                    }
                    txt2+= '</div>';    
                    g++;
                }
                txt += '</div><div class="clearfix"></div>';
                txt += '<input type="hidden" name="booking_time" id="ac_booking_time" value="">';
                    //$('#time').html(txt);
                    $('#ac_hours').html(txt2);
                    $('#ac_time').html(txt);
                d = new Date('<?php echo date('d M Y H:i:s'); ?>');
                month = parseInt(d.getMonth());
                month += 1;
                current_date = d.getFullYear() + '-' + month +  '-'  + d.getDate();
                var select_table = $('#ac_select_table');
                var cant_select_table = $('#ac_cant_select_table');
                if (current_date == dateText &&  (d.getHours() > 20 || (d.getHours() >= 20 && d.getMinutes()>=30))) {
                    cant_select_table.removeClass('hidden');
                    if(!select_table.hasClass('hidden')){
                        select_table.addClass('hidden');   
                    }
                } else{
                    if(!cant_select_table.hasClass('hidden')){
                        cant_select_table.addClass('hidden');   
                    }
                    $('#ac_booking_date').val(dateText);
                    $('#ac_date_edit1 span').text(formatDate(dateText));
                    $('#ac_date_edit1').click();
                    timehide=0;
                    $('#ac_time_edit1').click();
          $('#ac_date_edit1').removeClass('hidden');
                }
            },
            beforeShowDay: function(date) {
                var is_event = "<?php echo $rows[1]['is_event'];?>";
                if( is_event == 1 ){
                    var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
                    if($.inArray(y + '-' + (m+1) + '-' + d,schedule) != -1) {
                        return [true, '', ''];
                    } else{
                        return [false];
                    }
                    return date;
                } else{
                    var weekday_cnd = $.datepicker.formatDate( "D", date).toLowerCase();    
                }
                if(schedule[weekday_cnd] == undefined)
                {
                    return new Array(false);
                }
                <?php
                    if(!empty($res_block_dates) && is_array($res_block_dates))
                    {
                        foreach($res_block_dates as $bd)
                        {       
                            if($bd['block_time'] == $rows[1]['start_time'].'-'.$rows[1]['end_time'] || empty($bd['block_time'])){
                                $tmp_bd[] = $bd['block_date'];   
                            }
                        }
                        $bd_dates = implode('","',$tmp_bd);
                        $bd_dates = '"' . $bd_dates . '"';
                    }    
                    if(isset($bd_dates))
                    {
                ?>
                var bd_dates = new Array(<?php echo $bd_dates ?>);    
                tmp_date = $.datepicker.formatDate('yy-mm-dd', date);
                tmp_day = $.datepicker.formatDate('dd', date);
                for(var i in bd_dates)
                {
                    if(bd_dates[i] == tmp_date)
                    {      
                        return new Array(false,'closed_date');
                    }                      
                }
                <?php } if (!empty($rows[1]['weekdays'])): ?>
                var td = date.getDay();
                <?php
                    $dates = explode(',', $rows[1]['weekdays']);
                    $td = array();
                    $wd = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
                    foreach ($dates as &$date) {
                        $td[] = 'td != \''.$wd[$date].'\'';
                        $date = 'date.getDay() != '.$date;
                    }
                ?>
                var dxk = "<?php echo implode(' && ', $dates);?>";
                var dxkl = "<?php echo implode(' && ', $td);?>";
                var ret = [!(<?php echo implode(' && ', $dates);?>),'',!(<?php echo implode(' && ', $td);?>)?'':''];
                 
                return ret;
                <?php endif; ?>
                
                <?php if(!empty($res_block_dates)):?>
                        <?php foreach($res_block_dates as $date): ?>
                            <?php
                                list($year, $month, $day) = explode('-', $date['block_date']);
                            ?>
                            var b_date = new Date('<?php echo $year; ?>', '<?php echo $month-1; ?>', '<?php echo $day; ?>');
                            var ret = [!(date == b_date),'',!(date == b_date)?'':''];                                                        
                            return ret;
                        <?php endforeach; ?>
                <?php endif; ?>
                return [date];
            }, 
        });//END
        <?php endif; ?>


    /*alacarte detail page*/
    <?php if((isset($alacarte_start_date) && $alacarte_start_date != "")) : ?>
    window.alacarte_schedule = <?php echo json_encode($alacarte_schedule_times);  ?>;
    window.schedule = <?php echo json_encode($schedule);  ?>;
    var alacarte_bl_dates = Array();
    <?php 
      if(!empty($alacarte_block_dates) && is_array($alacarte_block_dates)){
        $tmp_bl_date = array();
        foreach($alacarte_block_dates as $bl_date){
          if($bl_date['ala_block_time'] != $rows[0]['start_time'].'-'.$rows[0]['end_time'] && !empty($bl_date['ala_block_time'])){
            $tmp_bl_date[][$bl_date['ala_block_time']] = $bl_date['ala_block_date']; 
          }
        }
    ?>
      alacarte_bl_dates = <?php echo json_encode($tmp_bl_date);?>;
    <?php           
      }
    ?> 
      $("#ac_choose_date2").datepicker({
        dateFormat: 'yy-m-d',
        minDate: <?php echo $alacarte_startDate; ?>,
        onSelect: function(dateText, inst) {
          var d = $.datepicker.parseDate("yy-m-dd",  dateText);
          
          var bl_time_start = [];
          var bl_time_end = [];
          $.each(alacarte_bl_dates,function(key,val){
            $.each(val,function(k,v){
              if($.datepicker.formatDate( "yy-mm-dd", d) == v){
                key = k.split('-');
                
                bl_time_start.push(key[0]);
                bl_time_end.push(key[1]);
              }
            })
          })
          var datestrInNewFormat = $.datepicker.formatDate( "D", d).toLowerCase();
          var txt = '<div class="btn-group col-lg-10 pull-right ac_actives ">';
          var txt2 = '';
          var g = 1;
          var cur_date =  new Date('<?php echo date('d M Y H:i:s'); ?>');
          month = parseInt(cur_date.getMonth());
          month += 1;
          c_date = cur_date.getFullYear() + '-' + month +  '-'  + cur_date.getDate();
          c_time = cur_date.getHours()+":"+((cur_date.getMinutes()<10)?'0':'')+cur_date.getMinutes();
          for(key_sch in alacarte_schedule[datestrInNewFormat])
          {   
            var obj_length = Object.keys(alacarte_schedule[datestrInNewFormat]).length;
            active_tab = (g == obj_length) ? 'active' : '' ;
            active_blck = (g == obj_length) ? '' : 'hidden' ;  
            txt+= '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="ac_'+key_sch+'">'+key_sch.toUpperCase()+'</label>';
            txt2 +=    '<div id="ac_' + key_sch + '_tab"  class="'+active_blck+'">';
            for(key_sch_time in alacarte_schedule[datestrInNewFormat][key_sch])
            {
              if(dateText == c_date){
                var is_valid = true;
                for (var i = 0; i < bl_time_start.length; i++) {
                  
                  if (String(key_sch_time) < String(bl_time_start[i]) || String(key_sch_time) > String(bl_time_end[i])) {
                    console.log(String(key_sch_time)+" < "+String(bl_time_start[i])+" || "+String(key_sch_time) +"> "+String(bl_time_end[i]))
                    is_valid = is_valid && true;

                  } else {
                    is_valid = is_valid && false;

                  }

                }
                if(is_valid) {
                  txt2+= '<div class="alacarte_time col-lg-3 col-xs-5" rel="' + alacarte_schedule[datestrInNewFormat][key_sch][key_sch_time] + '"><a href="javascript:">' + alacarte_schedule[datestrInNewFormat][key_sch][key_sch_time] + '</a></div>';
                }

              } else{
                var is_valid = true;
                for (var i = 0; i < bl_time_start.length; i++) {
                  
                  if (String(key_sch_time) < String(bl_time_start[i]) || String(key_sch_time) > String(bl_time_end[i])) {
                    console.log(String(key_sch_time)+" < "+String(bl_time_start[i])+" || "+String(key_sch_time) +"> "+String(bl_time_end[i]))
                    is_valid = is_valid && true;

                  } else {
                    is_valid = is_valid && false;

                  }

                }
                if(is_valid) {
                  txt2+= '<div class="alacarte_time col-lg-3 col-xs-5" rel="' + alacarte_schedule[datestrInNewFormat][key_sch][key_sch_time] + '"><a href="javascript:">' + alacarte_schedule[datestrInNewFormat][key_sch][key_sch_time] + '</a></div>';
                }
                
              }                       
            }
            txt2+= '</div>';    
            g++;
          }
          txt += '</div><div class="clearfix"></div>';
          txt += '<input type="hidden" name="booking_time" id="alacarte_booking_time" value="">';
            //$('#time').html(txt);
            $('#alacarte_hours').html(txt2);
            $('#alacarte_time').html(txt);
          d = new Date('<?php echo date('d M Y H:i:s'); ?>');
          month = parseInt(d.getMonth());
          month += 1;
          current_date = d.getFullYear() + '-' + month +  '-'  + d.getDate();
          var select_table = $('#ac_select_table2');
          var cant_select_table = $('#alacarte_cant_select_table');
          if (current_date == dateText &&  (d.getHours() > 20 || (d.getHours() >= 20 && d.getMinutes()>=30))) {
            cant_select_table.removeClass('hidden');
            if(!select_table.hasClass('hidden')){
              select_table.addClass('hidden');   
            }
          } else{
            if(!cant_select_table.hasClass('hidden')){
              cant_select_table.addClass('hidden');   
            }
            $('#ac_booking_date2').val(dateText);
            $('#ac_date_edit2 span').text(formatDate(dateText));
            $('#ac_date_edit2').click();
            timehide=0;
            $('#ac_time_edit2').click();
            $('#ac_date_edit2').removeClass('hidden');
          }
        },
        beforeShowDay: function(date) {

          var weekday_cnd = $.datepicker.formatDate( "D", date).toLowerCase();
          if(schedule[weekday_cnd] == undefined)
          {
            return new Array(false);
          }
          <?php
            if(!empty($alacarte_block_dates) && is_array($alacarte_block_dates))
            {
              foreach($alacarte_block_dates as $bd)
              {       
                if($bd['ala_block_time'] == $rows[0]['start_time'].'-'.$rows[0]['end_time'] || empty($bd['ala_block_time'])){
                  $tmp_bd[] = $bd['ala_block_date'];   
                }
              }
              $bd_dates = implode('","',$tmp_bd);
              $bd_dates = '"' . $bd_dates . '"';
            }    
            if(isset($bd_dates))
            {
          ?>
          var bd_dates = new Array(<?php echo $bd_dates ?>);    
          tmp_date = $.datepicker.formatDate('yy-mm-dd', date);
          tmp_day = $.datepicker.formatDate('dd', date);
          for(var i in bd_dates)
          {
            if(bd_dates[i] == tmp_date)
            {      
              return new Array(false,'closed_date');
            }                      
          }
          <?php } if (!empty($rows[0]['weekdays'])): ?>
          var td = date.getDay();
          <?php
            $dates = explode(',', $rows[0]['weekdays']);
            $td = array();
            $wd = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
            foreach ($dates as &$date) {
              $td[] = 'td != \''.$wd[$date].'\'';
              $date = 'date.getDay() != '.$date;
            }
          ?>
          var dxk = "<?php echo implode(' && ', $dates);?>";
          var dxkl = "<?php echo implode(' && ', $td);?>";
          var ret = [!(<?php echo implode(' && ', $dates);?>),'',!(<?php echo implode(' && ', $td);?>)?'':''];
           
          return ret;
          <?php endif; ?>
          
          <?php if(!empty($alacarte_block_dates)):?>
              <?php foreach($alacarte_block_dates as $date): ?>
                <?php
                  list($year, $month, $day) = explode('-', $date['ala_block_date']);
                ?>
                var b_date = new Date('<?php echo $year; ?>', '<?php echo $month-1; ?>', '<?php echo $day; ?>');
                var ret = [!(date == b_date),'',!(date == b_date)?'':''];                                                        
                return ret;
              <?php endforeach; ?>
          <?php endif; ?>
          return [date];
        }, 
      });//END
      <?php endif; ?>
    /*alacarte detail page*/
        <?php if(isset($order) && is_array($order)):?>
            var  timehide=0;
        <?php endif;?>
        $(document).on('click', '.time', function(){
           $('#hours').find('.time_active').removeClass('time_active') 
           $(this).addClass('time_active');
           $('#time_edit1 span').text($(this).text()); 
           $('#booking_time').val($(this).text());
           $('#time_edit1').removeClass('hidden');
           $('#cant_do_reserv1,#cant_do_reserv2,#brs_my_reserv').addClass('hidden');
           timehide=1;
           $('#time_edit1').click();
           counter=$('#party_edit1 span').text();
           str='';
           for(var i=0;i<=counter;i++)
           {
               str+="<option value='"+i+"'>"+i+"</option>";
           }
           $('.meals select').html(str);
           $('#meal_edit1').click();
       });
        $(document).on('click', '.ac_time', function(){
           $('#ac_hours').find('.time_active').removeClass('time_active') 
           $(this).addClass('time_active');
           $('#ac_time_edit1 span').text($(this).text()); 
           $('#ac_booking_time').val($(this).text());
           $('#ac_time_edit1').removeClass('hidden');
           $('#ac_cant_do_reserv1,#ac_cant_do_reserv2,#ac_brs_my_reserv').addClass('hidden');
           timehide=1;
           $('#ac_time_edit1').click();
           counter=$('#ac_party_edit1 span').text();
           str='';
           for(var i=0;i<=counter;i++)
           {
               str+="<option value='"+i+"'>"+i+"</option>";
           }
           $('.meals select').html(str);
           $('#ac_meal_edit1').click();
       });

     /*alacarte details page*/

     $(document).on('click', '.alacarte_time', function(){
           $('#alacarte_hours').find('.time_active').removeClass('time_active') 
           $(this).addClass('time_active');
           $('#ac_time_edit2 span').text($(this).text()); 
           $('#alacarte_booking_time').val($(this).text());
           $('#ac_time_edit2').removeClass('hidden');
           $('#alacarte_cant_do_reserv1,#alacarte_cant_do_reserv2,#alacarte_brs_my_reserv').addClass('hidden');
           timehide=1;
           $('#ac_time_edit2').click();
           counter=$('#ac_party_edit2 span').text();
           str='';
           for(var i=0;i<=counter;i++)
           {
               str+="<option value='"+i+"'>"+i+"</option>";
           }

       });
     /*alacarte details page*/
         $('#time_edit1').click(function(){
            $('#party_edit1').removeClass('hidden');
            $('#date_edit1').removeClass('hidden');
            if(timehide!=1)
            {
                $(this).addClass('hidden');
            } 
            else 
            {
                timehide=0;
                $(this).removeClass('hidden');   
            }   
       });
        <?php if (isset($hasOrder) && $hasOrder != ""): ?>
                <?php
                    $dateJS = explode('-', $order['date']);
                    $year = $dateJS[0];
                    $month = $dateJS[1]-1;
                    $day = $dateJS[2];
                    $dateJS = "$year,$month,$day";
                ?>
             $( "#choose_date" ).datepicker("setDate", new Date(<?php echo $dateJS; ?>));
       $( "#ac_choose_date" ).datepicker("setDate", new Date(<?php echo $dateJS; ?>));
             $('#date .date').text(formatDate('<?php echo $order['date'];?>')).show();
             $('#time .time1').text('<?php echo $order['time'];?>').show();
            <?php endif; ?>
           
           <?php if(empty($logged_in) && (isset($details['make_reservation_opt']) && $details['make_reservation_opt']==1)):?>
               logged_in = "1";
            <?php endif ?>
   $('#make_reservation').click(function(){
       $('#booking_form').submit();
   })  

  $('#ac_make_reservation').click(function(){
       $('#ac_booking_form').submit();
   })

  $('#alacarte_make_reservation').click(function(){
       $('#alacarte_booking_form').submit();
   })
           
  $('#booking_form').submit(function(){
        var error = false;
        
        email = $('#email').val();
        fullname = $.trim($('#fullname').val());
        phone = $('#phone').val();
        special = $('#special').val();
        people = $('#party_size1').val();
        
        if (email == '') {
            error = true;
            $('#error_email').text('Please enter a valid email.');
        } else {
            $('#error_email').empty();
        }
        
        var check_fullname = fullname.split(' ');
        if (fullname == '' || check_fullname.length < 2) {
            error = true;
            $('#error_fullname').text('Please enter your first and last name.');
        } else if(fullname.length <= 2){
           error = true;
           $('#error_fullname').text('Please enter your full name.'); 
        } else {
            $('#error_fullname').empty();
        }
        
        if (phone == '' || phone.length < 10) {
            error = true;
            $('#error_phone').text('Please enter a valid telephone number.');
        } else {
            $('#error_phone').empty();
        }  
        if (people == 0) {
            error = true;
            $('#error_people').text('The amount of people can not be null(0).');
        } else {
            $('#error_people').empty();
        }
        if (error){
            $('#load_layer').hide();
            return false;
        }
        else {
            time = $('#booking_time').val();
            
            date = $('#booking_date').val();
            date = date.split('-');
            date = date[1] + '/' + date[2] + '/' + date[0];
            date_time = date + ' ' + time;
            $('#fulltime').val(date_time);
            
            price = parseFloat(<?php echo isset($rows[1])?$rows[1]['post_tax_price']:'' ; ?>);
            post_price = parseFloat(<?php echo isset($rows[1])?$rows[1]['post_tax_price']:''; ?>);
            
            if ($('#non_veg').length > 0)
                non_veg_qty = parseInt($('#non_veg').val());
            else
                non_veg_qty = 0;
            qty = parseInt($('#party_size1').val());    
            if ($('#alcohol').length > 0)
                alcohol_qty = parseInt($('#alcohol').val());
            else
                alcohol_qty = 0;
            non_veg_price = parseFloat(<?php echo isset($rows[1])?$rows[1]['price_non_veg']:'';?>);
            alcohol_price = parseFloat(<?php echo isset($rows[1])?$rows[1]['price_alcohol']:'';?>);
            amount = qty * price + non_veg_qty * non_veg_price + alcohol_qty * alcohol_price;
            post_amount = qty * post_price + non_veg_qty * non_veg_price + alcohol_qty * alcohol_price;
            $('#amount').val(amount);
            $('#post_amount').val(post_amount);
            $('#load_layer').show();
        }
    });           
    $('#ac_booking_form').submit(function(){
        var error = false;        
        email = $('#ac_email').val();
        fullname = $.trim($('#ac_fullname').val());
        phone = $('#ac_phone').val();
        special = $('#ac_special').val();        
        if (email == '') {
            error = true;
            $('#ac_error_email').text('Please enter a valid email.');
        } else {
            $('#ac_error_email').empty();
        }        
        var check_fullname = fullname.split(' ');
        if (fullname == '' || check_fullname.length < 2) {
            error = true;
            $('#ac_error_fullname').text('Please enter your first and last name.');
        } else if(fullname.length <= 2){
           error = true;
           $('#ac_error_fullname').text('Please enter your full name.'); 
        } else {
            $('#ac_error_fullname').empty();
        }
        
        if (phone == '' || phone.length < 10) {
            error = true;
            $('#ac_error_phone').text('Please enter a valid telephone number.');
        } else {
            $('#ac_error_phone').empty();
        }
        if (error){
            $('#ac_load_layer').hide();
            return false;
        }
        else {
            time = $('#ac_booking_time').val();            
            date = $('#ac_booking_date').val();
            date = date.split('-');
            date = date[1] + '/' + date[2] + '/' + date[0];
            date_time = date + ' ' + time;
            $('#ac_fulltime').val(date_time);
            
            price = parseFloat(<?php echo isset($rows[1])?$rows[1]['price']:'';?>);
            
            if ($('#ac_non_veg').length > 0)
                non_veg_qty = parseInt($('#ac_non_veg').val());
            else
                non_veg_qty = 0;
            qty = parseInt($('#ac_party_size1').val());    
            if ($('#ac_alcohol').length > 0)
                alcohol_qty = parseInt($('#ac_alcohol').val());
            else
                alcohol_qty = 0;
            non_veg_price = parseFloat(<?php echo isset($rows[1])?$rows[1]['price_non_veg']:'';?>);
            alcohol_price = parseFloat(<?php echo isset($rows[1])?$rows[1]['price_alcohol']:'';?>);
            amount = qty * price + non_veg_qty * non_veg_price + alcohol_qty * alcohol_price;
            $('#ac_amount').val(amount);
            $('#ac_load_layer').show();
        }
    });

  <?php if((isset($alacarte_start_date) && $alacarte_start_date != "")) { ?>
    /*alacarte detail page*/
    $('#alacarte_booking_form').submit(function(){
      var error = false;        
      email = $('#alacarte_email').val();
      fullname = $.trim($('#alacarte_fullname').val());
      phone = $('#alacarte_phone').val();
      special = $('#alacarte_special').val();        
      if (email == '') {
        error = true;
        $('#alacarte_error_email').text('Please enter a valid email.');
      } else {
        $('#alacarte_error_email').empty();
      }        
      var check_fullname = fullname.split(' ');
      if (fullname == '' || check_fullname.length < 2) {
        error = true;
        $('#alacarte_error_fullname').text('Please enter your first and last name.');
      } else if(fullname.length <= 2){
         error = true;
         $('#alacarte_error_fullname').text('Please enter your full name.'); 
      } else {
        $('#alacarte_error_fullname').empty();
      }
      
      if (phone == '' || phone.length < 10) {
        error = true;
        $('#alacarte_error_phone').text('Please enter a valid telephone number.');
      } else {
        $('#alacarte_error_phone').empty();
      }
      if (error){
        $('#alacarte_load_layer').hide();
        return false;
      }
      else {
        time = $('#alacarte_booking_time').val();            
        date = $('#ac_booking_date2').val();
        date = date.split('-');
        date = date[1] + '/' + date[2] + '/' + date[0];
        date_time = date + ' ' + time;
        $('#ac_fulltime2').val(date_time);
        
        price = parseFloat(<?php echo $rows[0]['price'] ; ?>);
        
        qty = parseInt($('#ac_party_size2').val());    

        $('#ac_amount2').val(price);
        $('#alacarte_load_layer').show();
      }
    });
    /*alacarte detail page*/
  <?php } ?>
        $("#jump2-alacarte").click(function(){
        $("#ReservationBox").css('display','none');
      $("#AlacarteBox").fadeIn();
    }); 
    
    $("#jump2-expres").click(function(){
        $("#AlacarteBox").css('display','none');
      $("#ReservationBox").fadeIn();
    });



      $(".list-group-item").click(function(){
              var city_name = $(this).text().toLowerCase();
                $.ajax({
                  type:'POST',
                  url:'{{URL::to('/')}}/login/index/'+city_name,
                  data:{city:city_name},
                  success:function(data){
                          window.location.href = "<?php echo $_SERVER['REQUEST_URI'];?>";
                  }
                })
              });
        });
    $(document).ready(function(){
  
      //Check to see if the window is top if not then display button
      $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
          $('.scrollToTop').fadeIn();
        } else {
          $('.scrollToTop').fadeOut();
        }
      });
      
      //Click event to scroll to top
      $('.scrollToTop').click(function(){
        $('html, body').animate({scrollTop : 0},2000);
        return false;
      });

      $("body").delegate(".sort_results","change",function(){
        var v = $('option:selected', this).val();
        var cur_city = $("#uri_city_id").val();
        $.ajax({
          url: "{{URL::to('/')}}/custom_search/sorting",
          type:"post",
          dataType: "JSON",
          data: {sortby:v,city:cur_city},
          success: function( data ) {
            $("#left-content").fadeOut(500, function() {
              $("#left-content").empty();
              $("#left-content").html(data.restaurant_data);
            });
          },
          complete: function() {
            $(".show_loading_img").css("display","none");
            $("#left-content").fadeIn(500)
          }
        });
      });

      var search_var = 0;
      
      //search by cuisine/restaurant/area and brings the dropdown and appends it to the table which is below the search bar (Mobile resolution)
      $('#search_by_rest').autocomplete({     
        
        source: function( request, response ) {

          $.ajax({
            url: "{{URL::to('/')}}/custom_search/new_custom_search",
            dataType: "JSON",
            data: {
              term: request.term,city : c
            },
            success: function( data ) {
                            //console.log('response for all== '+data);
              response( data );
            }
          });
        },
        select: function(event,ui){
          event.preventDefault();
          var itemArr = ui.item.value.split('~~~');
          var rest_val = itemArr[0];
          var date_val = $("#datepicker").val();
          var time_val = $("#search_by_time").val();
          var amount_value = $("#amount").val();
          var final_amount = amount_value.split(' ');
          var start_from = final_amount[1];
          var end_with = final_amount[4];
          var c = $("#uri_city_id").val();
          var sList1        = "";
          var sList2        = "";
          var sList         = "";

          if(itemArr[2] == 'location')
          {
            sList1  =   itemArr[1]
          }

          if(itemArr[2] == 'cuisine')
          {
            sList2  =   itemArr[1]
          }

          if(itemArr[2] == 'vendor')
          {
            sList =   itemArr[1]
          }
          $( "#search_by_rest" ).val( rest_val);
          $( "#search_by" ).val( rest_val );

          search_var = 1;
          //console.log("rest_val = "+rest_val+" , date_val = "+date_val+" , time_val = "+time_val+" , amount_val = "+amount_value);
          $.ajax({

            url: "{{URL::to('/')}}/custom_search/search_filter",
            dataType: "JSON",
            type: "post",
            //data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
            data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with, city: c,area_values : sList1,cuisine_values : sList2,vendor_value : sList},
            beforeSend:function(){
              //$(".show_loading_img").css("display","block");
              $('#exp_list_load_layer').removeClass('hidden');
            },
            success: function(d) {
              //console.log(d.area_count);
              var area_replace = '';
                $.each(d.area_count,function(index, valueData){
                  area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
                });

              var cuisine_replace = '';
            $.each(d.cuisine_count,function(index, valueData){
              cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
            });

            var tags_replace = '';
            $.each(d.tags_count,function(index, valueData){
              tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
            });

              $("#left-content").fadeOut(500, function() {
                $("#left-content").empty();
                $("#left-content").html(d.restaurant_data);
              });
            if(area_replace == "") {
              area_replace = "No Areas found";
            }
            if(cuisine_replace == "") {
              cuisine_replace = "No Cuisine found";
            }
            if(tags_replace == "") {
              tags_replace = "No Tags found";
            }
              $(".dynamic_areas").html(area_replace);
              $(".dynamic_cuisine").html(cuisine_replace);
              $(".dynamic_tags").html(tags_replace);
              
            },
            complete: function() {
              $(".show_loading_img").css("display","none");
              $("#left-content").fadeIn(500);
              $('#exp_list_load_layer').addClass('hidden');
              $('html, body').animate({
                scrollTop: $('#left-content').offset().top
              }, 'slow');
            },
            timeout: 9999999
            });
        },
        create: function () {
              $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
                   itemArr = item.value.split('~~~');
            return $( "<li>" )
            .append( "<a data-id='"+itemArr[1]+"' data-type='"+itemArr[2]+"'>"+itemArr[0] + "</a>" )
            .appendTo( ul );
                };
            },  
        minLength: 1
      })



      //ajax call to bring the relevant cuisine,areas,tags,and restaurant results (for site and mobile resolution)
      $("#manual_search").click(function(){
        var rest_val = $("#search_by").val();
        var date_val = $("#datepicker").val();
        var time_val = $("#search_by_time").val();
        var amount_value = $("#amount").val();
        var final_amount = amount_value.split(' ');
        var start_from = final_amount[1];
        var end_with = final_amount[4];
        var c = $("#uri_city_id").val();

        var sList1        = "";
        var sList2        = "";
        var sList         = "";

        var hdn_search_id = $( "#hdn_search_id").val();
        var hdn_search_type =  $( "#hdn_search_type").val();

        if(hdn_search_type == 'location')
        {
          sList1  =   hdn_search_id
        }

        if(hdn_search_type == 'cuisine')
        {
          sList2  =   hdn_search_id
        }

        if(hdn_search_type == 'vendor')
        {
          sList =   hdn_search_id
        }

        
        

        search_var = 1;
        $("#search_by").val(rest_val);
        $("#search_by_rest").val(rest_val);
        $(".search_by_results tbody").html('');
        //console.log("rest_val = "+rest_val+" , date_val = "+date_val+" , time_val = "+time_val+" , amount_val = "+amount_value);
        //ajax call beings required results and according to results bring area,cuisine and tags results if any of above values are not null  
        if(rest_val != "") {
          $.ajax({
          //url: "custom_search/search_result",
          url: "custom_search/search_filter",
          dataType: "JSON",
          type: "post",
          data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with, city: c,area_values : sList1,cuisine_values : sList2,vendor_value : sList},
          beforeSend:function(){
            $(".show_loading_img").css("display","block");
          },
          success: function(d) {
            //console.log(d.area_count);
            var area_replace = '';
                $.each(d.area_count,function(index, valueData){
                  area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
                });

            var cuisine_replace = '';
            $.each(d.cuisine_count,function(index, valueData){
              cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
            });

            var tags_replace = '';
            $.each(d.tags_count,function(index, valueData){
              tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
            });

            $("#left-content").fadeOut(500, function() {
              $("#left-content").empty();
              $("#left-content").html(d.restaurant_data);
            });
          if(area_replace == "") {
            area_replace = "No Areas found";
          }
          if(cuisine_replace == "") {
            cuisine_replace = "No Cuisine found";
          }
          if(tags_replace == "") {
            tags_replace = "No Tags found";
          }
            $(".dynamic_areas").html(area_replace);
            $(".dynamic_cuisine").html(cuisine_replace);
            $(".dynamic_tags").html(tags_replace);
            
          },
          complete: function() {
            $(".show_loading_img").css("display","none");
            $("#left-content").fadeIn(500)
          },
          timeout: 9999999
          });
        }
      });

      $("#Manual_Search").click(function(){
        var rest_val = $("#search_by_rest").val();
        //var rest_val = $(this).val();
        var date_val = $("#datepicker-small").val();
        var time_val = $("#Search_By_Time").val();
        var amount_value = $("#amount-small").val();
        var final_amount = amount_value.split(' ');
        var start_from = final_amount[1];
        var end_with = final_amount[4];
        var c = $("#uri_city_id").val();
        search_var = 1;

        var sList1        = "";
        var sList2        = "";
        var sList         = "";

        var hdn_search_id = $( "#hdn_search_id").val();
        var hdn_search_type =  $( "#hdn_search_type").val();

        if(hdn_search_type == 'location')
        {
          sList1  =   hdn_search_id
        }

        if(hdn_search_type == 'cuisine')
        {
          sList2  =   hdn_search_id
        }

        if(hdn_search_type == 'vendor')
        {
          sList =   hdn_search_id
        }

        $("#search_by").val(rest_val);
        $("#search_by_rest").val(rest_val);
        $(".search_by_results tbody").html('');
        //console.log("rest_val = "+rest_val+" , date_val = "+date_val+" , time_val = "+time_val+" , amount_val = "+amount_value);
        //ajax call beings required results and according to results bring area,cuisine and tags results if any of above values are not null  
        if(rest_val != "") {
          $.ajax({
          //url: "custom_search/search_result",
          url: "custom_search/search_filter",
          dataType: "JSON",
          type: "post",
          data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with, city: c,area_values : sList1,cuisine_values : sList2,vendor_value : sList},
          beforeSend:function(){
            $(".show_loading_img").css("display","block");
          },
          success: function(d) {
            //console.log(d.area_count);
            var area_replace = '';
                $.each(d.area_count,function(index, valueData){
                  area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
                });

            var cuisine_replace = '';
            $.each(d.cuisine_count,function(index, valueData){
              cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
            });

            var tags_replace = '';
            $.each(d.tags_count,function(index, valueData){
              tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
            });

            $("#left-content").fadeOut(500, function() {
              $("#left-content").empty();
              $("#left-content").html(d.restaurant_data);
            });
          if(area_replace == "") {
            area_replace = "No Areas found";
          }
          if(cuisine_replace == "") {
            cuisine_replace = "No Cuisine found";
          }
          if(tags_replace == "") {
            tags_replace = "No Tags found";
          }
            $(".dynamic_areas").html(area_replace);
            $(".dynamic_cuisine").html(cuisine_replace);
            $(".dynamic_tags").html(tags_replace);
            
          },
          complete: function() {
            $(".show_loading_img").css("display","none");
            $("#left-content").fadeIn(500)
          },
          timeout: 9999999
          });
        }
      });


      //ajax call for getting results according to the date selected in the search 
      $('#datepicker').on('change', function() {
          $("#date_error").css("display","none");
          $('#datepicker').css("border","");
          var selectedDate = $('#datepicker').datepicker('getDate');
          var date_val = $(this).val();
          var rest_val = $("#search_by").val();
          var time_val = $("#search_by_time").val();
          var amount_value = $("#amount").val();
          var final_amount = amount_value.split(' ');
          var start_from = final_amount[1];
          var end_with = final_amount[4];
          var c = $("#uri_city_id").val();
          var today = new Date();
          today.setHours(0);
          today.setMinutes(0);
          today.setSeconds(0);
          if(today || selectedDate) {
            //ajax call brings results according to date selected and accordingly area,cuisine and tags results is selected date is future date
            if (Date.parse(today) < Date.parse(selectedDate)) {
              
              $("#date_error").css("display","none");
              $('#datepicker').css("border","");
              
              $.ajax({
                //url: "custom_search/search_future_date_restaurant",
                url: "custom_search/search_filter",
                dataType: "JSON",
                type: "post",
                //data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
                data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,city: c},
                beforeSend:function(){
                  $(".show_loading_img").css("display","block");
                },
                success: function(d) {
                  //$("#results").append(d);
                  //console.log(d);
                  var area_replace = '';
                $.each(d.area_count,function(index, valueData){
                  area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
                });

                  var cuisine_replace = '';
                  $.each(d.cuisine_count,function(index, valueData){
                    cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
                  });

                  var tags_replace = '';
                  $.each(d.tags_count,function(index, valueData){
                    tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
                  });

                  $("#left-content").fadeOut(500, function() {
                    $("#left-content").empty();
                    $("#left-content").html(d.restaurant_data);
                  });
                  //console.log(text);
                  if(area_replace == "") {
                    area_replace = "No Areas found";
                  }
                  if(cuisine_replace == "") {
                    cuisine_replace = "No Cuisine found";
                  }
                  if(tags_replace == "") {
                    tags_replace = "No Tags found";
                  }
                  $(".dynamic_areas").html(area_replace);
                  $(".dynamic_cuisine").html(cuisine_replace);
                  $(".dynamic_tags").html(tags_replace);
                },
                complete: function() {
                  $(".show_loading_img").css("display","none");
                  $("#left-content").fadeIn(500)
                },
                timeout: 9999999
              });
            
            } //ajax call brings todays details and accordingly area,cuisine and tags results is selected date is todays date
            else if(Date.parse(today) == Date.parse(selectedDate)){
              $("#date_error").css("display","none");
              $('#datepicker').css("border","");
              
              $.ajax({
                //url: "custom_search/search_todays_date_restaurant",
                url: "custom_search/search_filter",
                dataType: "JSON",
                type: "post",
                //data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
                data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,city: c},
                beforeSend:function(){
                  $(".show_loading_img").css("display","block");
                },
                success: function(d) {
                  //$("#results").append(d);
                  //console.log(d);
                  var area_replace = '';
                $.each(d.area_count,function(index, valueData){
                  area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
                });

                  var cuisine_replace = '';
                  $.each(d.cuisine_count,function(index, valueData){
                    cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
                  });

                  var tags_replace = '';
                  $.each(d.tags_count,function(index, valueData){
                    tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
                  });
          
                  $("#left-content").fadeOut(500, function() {
                    $("#left-content").empty();
                    $("#left-content").html(d.restaurant_data);
                  });
                  //console.log(text);
                  if(area_replace == "") {
                    area_replace = "No Areas found";
                  }
                  if(cuisine_replace == "") {
                    cuisine_replace = "No Cuisine found";
                  }
                  if(tags_replace == "") {
                    tags_replace = "No Tags found";
                  }
                  $(".dynamic_areas").html(area_replace);
                  $(".dynamic_cuisine").html(cuisine_replace);
                  $(".dynamic_tags").html(tags_replace);
                },
                complete: function() {
                  $(".show_loading_img").css("display","none");
                  $("#left-content").fadeIn(500)
                },
                timeout: 9999999
              });
            } 
            //show error if selected date is less than todays date
            else if (Date.parse(today) > Date.parse(selectedDate)) {

            $("#date_error").css("display","block");
            $('#datepicker').css("border","1px solid red");
            }
          }
      });
      
      //ajax call for getting results according to the date selected in the search 
      $('#datepicker-small').on('change', function() {
          $("#date_error_small").css("display","none");
          $('#datepicker-small').css("border","");
          var selectedDate = $('#datepicker-small').datepicker('getDate');
          var date_val = $(this).val();
          var rest_val = $("#search_by_rest").val();
          var time_val = $("#Search_By_Time").val();
          var amount_value = $("#amount-small").val();
          var final_amount = amount_value.split(' ');
          var start_from = final_amount[1];
          var end_with = final_amount[4];
          var today = new Date();
          var c = $("#uri_city_id").val();
          today.setHours(0);
          today.setMinutes(0);
          today.setSeconds(0);
          if(today || selectedDate) {
            //ajax call brings results according to date selected and accordingly area,cuisine and tags results is selected date is future date
            if (Date.parse(today) < Date.parse(selectedDate)) {
              
              $("#date_error_small").css("display","none");
              $('#datepicker-small').css("border","");
              
              $.ajax({
                //url: "custom_search/search_future_date_restaurant",
                url: "custom_search/search_filter",
                dataType: "JSON",
                type: "post",
                //data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
                data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,city: c},
                beforeSend:function(){
                  $(".show_loading_img").css("display","block");
                },
                success: function(d) {
                  //$("#results").append(d);
                  //console.log(d);
                  var area_replace = '';
                $.each(d.area_count,function(index, valueData){
                  area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
                });

                  var cuisine_replace = '';
                  $.each(d.cuisine_count,function(index, valueData){
                    cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
                  });

                  var tags_replace = '';
                  $.each(d.tags_count,function(index, valueData){
                    tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
                  });
                  $("#left-content").fadeOut(500, function() {
                    $("#left-content").empty();
                    $("#left-content").html(d.restaurant_data);
                  });
                  //console.log(text);
                  if(area_replace == "") {
                    area_replace = "No Areas found";
                  }
                  if(cuisine_replace == "") {
                    cuisine_replace = "No Cuisine found";
                  }
                  if(tags_replace == "") {
                    tags_replace = "No Tags found";
                  }
                  $(".dynamic_areas").html(area_replace);
                  $(".dynamic_cuisine").html(cuisine_replace);
                  $(".dynamic_tags").html(tags_replace);
                },
                complete: function() {
                  $(".show_loading_img").css("display","none");
                  $("#left-content").fadeIn(500)
                },
                timeout: 9999999
              });
            
            } //ajax call brings todays details and accordingly area,cuisine and tags results is selected date is todays date
            else if(Date.parse(today) == Date.parse(selectedDate)){
              $("#date_error_small").css("display","none");
              $('#datepicker-small').css("border","");
              
              $.ajax({
                //url: "custom_search/search_todays_date_restaurant",
                url: "custom_search/search_filter",
                dataType: "JSON",
                type: "post",
                //data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
                data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,city: c},
                beforeSend:function(){
                  $(".show_loading_img").css("display","block");
                },
                success: function(d) {
                  //$("#results").append(d);
                  //console.log(d);
                 var area_replace = '';
                $.each(d.area_count,function(index, valueData){
                  area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
                });

                  var cuisine_replace = '';
                  $.each(d.cuisine_count,function(index, valueData){
                    cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
                  });

                  var tags_replace = '';
                  $.each(d.tags_count,function(index, valueData){
                    tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
                  });
          
                  $("#left-content").fadeOut(500, function() {
                    $("#left-content").empty();
                    $("#left-content").html(d.restaurant_data);
                  });
                  //console.log(text);
                  if(area_replace == "") {
                    area_replace = "No Areas found";
                  }
                  if(cuisine_replace == "") {
                    cuisine_replace = "No Cuisine found";
                  }
                  if(tags_replace == "") {
                    tags_replace = "No Tags found";
                  }
                  $(".dynamic_areas").html(area_replace);
                  $(".dynamic_cuisine").html(cuisine_replace);
                  $(".dynamic_tags").html(tags_replace);
                },
                complete: function() {
                  $(".show_loading_img").css("display","none");
                  $("#left-content").fadeIn(500)
                },
                timeout: 9999999
              });
            } 
            //show error if selected date is less than todays date
            else if (Date.parse(today) > Date.parse(selectedDate)) {

            $("#date_error_small").css("display","block");
            $('#datepicker-small').css("border","1px solid red");
            }
          }
      });
      

      //ajax call for getting results according to the time selected in the search 
      $("#search_by_time").on('change',function(){ console.log("called");
        var time_val = $(this).val();
        var date_val = $("#datepicker").val();
        var rest_val = $("#search_by").val();
        var amount_value = $("#amount").val();
        var final_amount = amount_value.split(' ');
        var start_from = final_amount[1];
        var end_with = final_amount[4];
        var c = $("#uri_city_id").val();
        //console.log('final amount split = '+final_amount);
        console.log(" first amount =="+final_amount[1]+" , second amount == "+final_amount[4]);
        //console.log("time value == "+time_val+" , date value = "+date_val+" , rest val = "+rest_val+" , start_from = "+start_from+" , end_with = "+end_with);
        if(time_val != "") {
          $.ajax({
            url: "custom_search/search_filter",
            dataType: "JSON",
            type: "post",
            //data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
            data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,city: c},
            beforeSend:function(){
              $(".show_loading_img").css("display","block");
            },
            success: function(d) {
              //$("#results").append(d);
              //console.log(d);
              var area_replace = '';
                $.each(d.area_count,function(index, valueData){
                  area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
                });

              var cuisine_replace = '';
              $.each(d.cuisine_count,function(index, valueData){
                cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
              });

              var tags_replace = '';
              $.each(d.tags_count,function(index, valueData){
                tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
              });

              $("#left-content").fadeOut(500, function() {
                $("#left-content").empty();
                $("#left-content").html(d.restaurant_data);
              });
              //console.log(text);
              if(area_replace == "") {
                area_replace = "No Areas found";
              }
              if(cuisine_replace == "") {
                cuisine_replace = "No Cuisine found";
              }
              if(tags_replace == "") {
                tags_replace = "No Tags found";
              }
              $(".dynamic_areas").html(area_replace);
              $(".dynamic_cuisine").html(cuisine_replace);
              $(".dynamic_tags").html(tags_replace);
            },
            complete: function() {
              $(".show_loading_img").css("display","none");
              $("#left-content").fadeIn(500)
            },
            timeout: 9999999
          });
        } 
      });
      
      //ajax call for getting results according to the time selected in the search 
      $("#Search_By_Time").on('change',function(){
        var time_val = $(this).val();
        var date_val = $("#datepicker-small").val();
        var rest_val = $("#search_by_rest").val();
        var amount_value = $("#amount-small").val();
        var final_amount = amount_value.split(' ');
        var start_from = final_amount[1];
        var end_with = final_amount[4];
        var c = $("#uri_city_id").val();
        //console.log('final amount split = '+final_amount);
        console.log(" first amount =="+final_amount[1]+" , second amount == "+final_amount[4]);
        //console.log("time value == "+time_val+" , date value = "+date_val+" , rest val = "+rest_val+" , start_from = "+start_from+" , end_with = "+end_with);

        $.ajax({
          url: "custom_search/search_filter",
          dataType: "JSON",
          type: "post",
          //data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val},
          data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with, city: c},
          beforeSend:function(){
            $(".show_loading_img").css("display","block");
          },
          success: function(d) {
            //$("#results").append(d);
            //console.log(d);
            var area_replace = '';
                $.each(d.area_count,function(index, valueData){
                  area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
                });

            var cuisine_replace = '';
            $.each(d.cuisine_count,function(index, valueData){
              cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
            });

            var tags_replace = '';
            $.each(d.tags_count,function(index, valueData){
              tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
            });

            $("#left-content").fadeOut(500, function() {
              $("#left-content").empty();
              $("#left-content").html(d.restaurant_data);
            });
            //console.log(text);
            if(area_replace == "") {
              area_replace = "No Areas found";
            }
            if(cuisine_replace == "") {
              cuisine_replace = "No Cuisine found";
            }
            if(tags_replace == "") {
              tags_replace = "No Tags found";
            }
            $(".dynamic_areas").html(area_replace);
            $(".dynamic_cuisine").html(cuisine_replace);
            $(".dynamic_tags").html(tags_replace);
          },
          complete: function() {
            $(".show_loading_img").css("display","none");
            $("#left-content").fadeIn(500)
          },
          timeout: 9999999
        });
      });

      //ajax call for getting results according to the area selected from more options
      $("body").delegate(".search_by_place","change",function(){
        var time_val      = $("#search_by_time").val();
        var date_val      = $("#datepicker").val();
        var rest_val      = $("#search_by").val();
        var amount_value  = $("#amount").val();
        var final_amount  = amount_value.split(' ');
        var start_from    = final_amount[1];
        var end_with      = final_amount[4];
        var sList         = "";
        var c             = $("#uri_city_id").val();

        $( ".search_by_place" ).each(function() {
          var sThisVal = (this.checked ? $(this).val() : "0");
          if(parseInt(sThisVal)) {
            sList += (sList=="" ? sThisVal : "," + sThisVal+"");
          }
        });
        
        
        $.ajax({
          url: "custom_search/search_filter",
          dataType: "JSON",
          type: "post",
         data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,area_values : sList,city: c},
          beforeSend:function(){
            $(".show_loading_img").css("display","block");
          },
          success: function(d) {
            var cuisine_replace = '';
            $.each(d.cuisine_count,function(index, valueData){
              cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
            });

            var tags_replace = '';
            $.each(d.tags_count,function(index, valueData){
              tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
            });

            $("#left-content").fadeOut(500, function() {
              $("#left-content").empty();
              $("#left-content").html(d.restaurant_data);
            });
            
            if(cuisine_replace == "") {
              cuisine_replace = "No Cuisine found";
            }
            if(tags_replace == "") {
              tags_replace = "No Tags found";
            }
            $(".dynamic_cuisine").html(cuisine_replace);
            $(".dynamic_tags").html(tags_replace);
          },
          complete: function() {
            $(".show_loading_img").css("display","none");
            $("#left-content").fadeIn(500)
          },
          timeout: 9999999
        });
      });


      //ajax call for getting results according to the cuisine selected in the search bar
      $("body").delegate(".search_by_cuisine","change",function(){
        var time_val      = $("#search_by_time").val();
        var date_val      = $("#datepicker").val();
        var rest_val      = $("#search_by").val();
        var amount_value  = $("#amount").val();
        var final_amount  = amount_value.split(' ');
        var start_from    = final_amount[1];
        var end_with      = final_amount[4];
        var sList         = "";
        var c             = $("#uri_city_id").val();

        var sList1        = "";
        var sList         = "";

       $( ".search_by_place" ).each(function() {
          var sThisVal = (this.checked ? $(this).val() : "0");
          if(parseInt(sThisVal)) {
            sList1 += (sList=="" ? sThisVal : "," + sThisVal+"");
          }
        });

        $( ".search_by_cuisine" ).each(function() {
          var sThisVal = (this.checked ? $(this).val() : "0");
          if(parseInt(sThisVal)) {
            sList += (sList=="" ? sThisVal : "," + sThisVal+"");
          }
        });

        //console.log (sList);
        $.ajax({
          url: "custom_search/search_filter",
          dataType: "JSON",
          type: "post",
          data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,area_values : sList1,cuisine_values : sList,city: c},
          beforeSend:function(){
            $(".show_loading_img").css("display","block");
          },
          //data: {cuisine_values : sList},
          success: function(d) {
            var tags_replace = '';
            $.each(d.tags_count,function(index, valueData){
              tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
            });
                        
            $("#left-content").fadeOut(500, function() {
              $("#left-content").empty();
              $("#left-content").html(d.restaurant_data);
            });

            if(tags_replace == "") {
              tags_replace = "No Tags found";
            }
            $(".dynamic_tags").html(tags_replace);
          },
          complete: function() {
            $(".show_loading_img").css("display","none");
            $("#left-content").fadeIn(500)
          },
          timeout: 9999999
        });
      });

      $("body").delegate(".search_by_tags","change",function(){
        var time_val      = $("#search_by_time").val();
        var date_val      = $("#datepicker").val();
        var rest_val      = $("#search_by").val();
        var amount_value  = $("#amount").val();
        var final_amount  = amount_value.split(' ');
        var start_from    = final_amount[1];
        var end_with      = final_amount[4];
        var sList         = "";
        var c             = $("#uri_city_id").val();

        var sList1        = "";
        var sList2        = "";
        var sList         = "";

       $( ".search_by_place" ).each(function() {
          var sThisVal = (this.checked ? $(this).val() : "0");
          if(parseInt(sThisVal)) {
            sList1 += (sList=="" ? sThisVal : "," + sThisVal+"");
          }
        });

        $( ".search_by_cuisine" ).each(function() {
          var sThisVal = (this.checked ? $(this).val() : "0");
          if(parseInt(sThisVal)) {
            sList2 += (sList=="" ? sThisVal : "," + sThisVal+"");
          }
        });        
        
        $( ".search_by_tags" ).each(function() {
          var sThisVal = (this.checked ? $(this).val() : "0");
          if(parseInt(sThisVal)) {
            sList += (sList=="" ? sThisVal : "," + sThisVal+"");
          }
        });

        $.ajax({
          url: "custom_search/search_filter",
          dataType: "JSON",
          type: "post",
          data: {restaurant_val : rest_val,date_value : date_val,time_value : time_val,start_price: start_from, end_price : end_with,area_values : sList1,cuisine_values : sList2,tags_values : sList, city : c},
          beforeSend:function(){
            $(".show_loading_img").css("display","block");
          },
          success: function(d) {
            //$("#results").append(d);
            $("#left-content").fadeOut(500, function() {
              $("#left-content").empty();
              $("#left-content").html(d.restaurant_data);
            });
          },
          complete: function() {
            $(".show_loading_img").css("display","none");
            $("#left-content").fadeIn(500)
          },
          timeout: 9999999
        });
      });

      $("#search_by, #search_by_rest").on("blur",function(){
        var val = $(this).val();
        var c = $("#uri_city_id").val();
        console.log("val = "+val+" , val.length = "+val.length);
        if(val.length == 0 && search_var == 1) {
          search_var = 0;
          $.ajax({
              url: "custom_search/search_filter",
              type: "POST",
              dataType: "json",
              data:{city: c},
              beforeSend:function(){
                $(".show_loading_img").css("display","block");
              },
              success: function(d) {
              //console.log(d.area_count);
             var area_replace = '';
                $.each(d.area_count,function(index, valueData){
                  area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
                });

             var cuisine_replace = '';
            $.each(d.cuisine_count,function(index, valueData){
              cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
            });

            var tags_replace = '';
            $.each(d.tags_count,function(index, valueData){
              tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
            });

              $("#left-content").fadeOut(500, function() {
                $("#left-content").empty();
                $("#left-content").html(d.restaurant_data);
              });
              if(area_replace == "") {
                area_replace = "No Areas found";
              }
              if(cuisine_replace == "") {
                cuisine_replace = "No Cuisine found";
              }
              if(tags_replace == "") {
                tags_replace = "No Tags found";
              }
              $(".dynamic_areas").html(area_replace);
              $(".dynamic_cuisine").html(cuisine_replace);
              $(".dynamic_tags").html(tags_replace);
              
            },complete: function() {
              $(".show_loading_img").css("display","none");
              $("#left-content").fadeIn(500)
            },
            timeout: 9999999
            
          });
        }
      });

      $("#reset_form").on("click", function(){
        document.getElementById("custom_refine_search").reset();
        
        $("#slider-range").slider("values", 0, 0);
        $("#slider-range").slider("values", 1, 15000);
        $( "#amount" ).val( "Rs " + $( "#slider-range" ).slider( "values", 0 ) + " - Rs " + $( "#slider-range" ).slider( "values", 1 ) );
        $("#slider-range-small").slider("values", 0, 0);
        $("#slider-range-small").slider("values", 1, 15000);
        $( "#amount-small" ).val( "Rs " + $( "#slider-range-small" ).slider( "values", 0 ) + " - Rs " + $( "#slider-range-small" ).slider( "values", 1 ) );
        var c = $("#uri_city_id").val();       
        $.ajax({
            url: "custom_search/search_filter",
            type: "POST",
            dataType: "json",
            data:{city: c},
            beforeSend:function(){
              $(".show_loading_img").css("display","block");
            },
            success: function(d) {
            
            var area_replace = '';
            $.each(d.area_count,function(index, valueData){
              area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
            });

            var cuisine_replace = '';
            $.each(d.cuisine_count,function(index, valueData){
              cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
            });

            var tags_replace = '';
            $.each(d.tags_count,function(index, valueData){
              tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
            });

            $("#left-content").fadeOut(500, function() {
              $("#left-content").empty();
              $("#left-content").html(d.restaurant_data);
            });
            if(area_replace == "") {
              area_replace = "No Areas found";
            }
            if(cuisine_replace == "") {
              cuisine_replace = "No Cuisine found";
            }
            if(tags_replace == "") {
              tags_replace = "No Tags found";
            }
            $(".dynamic_areas").html(area_replace);
            $(".dynamic_cuisine").html(cuisine_replace);
            $(".dynamic_tags").html(tags_replace);
            
          },complete: function() {
            $(".show_loading_img").css("display","none");
            $("#left-content").fadeIn(500)
          },
          timeout: 9999999
          
        });
      });

      $("#reset_filters").on("click", function(){
        //console.log("function callaed");
        document.getElementById("custom_refine_search_form").reset();
        $("#slider-range").slider("values", 0, 0);
          $("#slider-range").slider("values", 1, 15000);
          $( "#amount" ).val( "Rs " + $( "#slider-range" ).slider( "values", 0 ) + " - Rs " + $( "#slider-range" ).slider( "values", 1 ) );
        $("#slider-range-small").slider("values", 0, 0);
          $("#slider-range-small").slider("values", 1, 15000);
          $( "#amount-small" ).val( "Rs " + $( "#slider-range-small" ).slider( "values", 0 ) + " - Rs " + $( "#slider-range-small" ).slider( "values", 1 ) );
        var c = $("#uri_city_id").val();       
        $.ajax({
            url: "custom_search/search_filter",
            type: "POST",
            dataType: "json",
            data:{city: c},
            beforeSend:function(){
              $(".show_loading_img").css("display","block");
            },
            success: function(d) {
            //console.log(d.area_count);
            var area_replace = '';
            $.each(d.area_count,function(index, valueData){
              area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
            });

            var cuisine_replace = '';
            $.each(d.cuisine_count,function(index, valueData){
              cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
            });

            var tags_replace = '';
            $.each(d.tags_count,function(index, valueData){
              tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
            });

            $("#left-content").fadeOut(500, function() {
              $("#left-content").empty();
              $("#left-content").html(d.restaurant_data);
            });
            if(area_replace == "") {
              area_replace = "No Areas found";
            }
            if(cuisine_replace == "") {
              cuisine_replace = "No Cuisine found";
            }
            if(tags_replace == "") {
              tags_replace = "No Tags found";
            }
            $(".dynamic_areas").html(area_replace);
            $(".dynamic_cuisine").html(cuisine_replace);
            $(".dynamic_tags").html(tags_replace);
            
          },complete: function() {
            $(".show_loading_img").css("display","none");
            $("#left-content").fadeIn(500)
          },
          timeout: 9999999
          
        });
      });

      $("body").delegate(".clear_filters","click",function(){

        $("#slider-range").slider("values", 0, 0);
          $("#slider-range").slider("values", 1, 15000);
          $( "#amount" ).val( "Rs " + $( "#slider-range" ).slider( "values", 0 ) + " - Rs " + $( "#slider-range" ).slider( "values", 1 ) );
        $("#slider-range-small").slider("values", 0, 0);
          $("#slider-range-small").slider("values", 1, 15000);
          $( "#amount-small" ).val( "Rs " + $( "#slider-range-small" ).slider( "values", 0 ) + " - Rs " + $( "#slider-range-small" ).slider( "values", 1 ) );
        //console.log("clieck");
        //window.location.reload();
        var c = $("#uri_city_id").val();       
        $.ajax({
            url: "custom_search/search_filter",
            type: "POST",
            dataType: "json",
            data:{city: c},
            beforeSend:function(){
              $(".show_loading_img").css("display","block");
            },
            success: function(d) {
            //console.log(d.area_count);
            var area_replace = '';
            $.each(d.area_count,function(index, valueData){
              area_replace += '<div class="checkbox"><label><input class="search_by_place" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
            });

            var cuisine_replace = '';
            $.each(d.cuisine_count,function(index, valueData){
              cuisine_replace += '<div class="checkbox"><label><input class="search_by_cuisine" type="checkbox" value="'+valueData.id+'">'+valueData.name+'<span class="badge">'+valueData.count+'</span></label></div>'
            });

            var tags_replace = '';
            $.each(d.tags_count,function(index, valueData){
              tags_replace += '<label class="btn btn-warning"><input type="checkbox" class="search_by_tags" value="'+valueData.id+'"> '+valueData.name+'</label>'
            });

            $("#left-content").fadeOut(500, function() {
              $("#left-content").empty();
              $("#left-content").html(d.restaurant_data);
            });
            if(area_replace == "") {
              area_replace = "No Areas found";
            }
            if(cuisine_replace == "") {
              cuisine_replace = "No Cuisine found";
            }
            if(tags_replace == "") {
              tags_replace = "No Tags found";
            }
            $(".dynamic_areas").html(area_replace);
            $(".dynamic_cuisine").html(cuisine_replace);
            $(".dynamic_tags").html(tags_replace);
            
          },complete: function() {
            $(".show_loading_img").css("display","none");
            $("#left-content").fadeIn(500)
          },
          timeout: 9999999
          
        });
      });
      
    });
  

</script>
<?php endif;?> 
</body>
</html>
