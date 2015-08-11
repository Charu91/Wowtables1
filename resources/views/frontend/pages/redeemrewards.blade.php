@extends('frontend.templates.details_pages')

@section('content')
<style>
@media (min-width: 300px) and (max-width: 379px) {
  .selected-data{
    background: url("/assets/img/Selected_50_small_device.png");
  }
  .selected-data img {
    opacity: 0.10;
  }
  
  .img_padding_bottom{
    padding-bottom:12px !important;
  }
}

@media (min-width: 380px) and (max-width: 419px) {
  .selected-data{
    background: url("/assets/img/selected_380.png");
  }
  .selected-data img {
    opacity: 0.10;
  }
  
  .img_padding_bottom{
    padding-bottom:12px !important;
  }
}

@media (min-width: 420px) and (max-width: 499px) {
  .selected-data{
    background: url("/assets/img/selected_420.png");
  }
  .selected-data img {
    opacity: 0.10;
  }
  
  .img_padding_bottom{
    padding-bottom:12px !important;
  }
}

@media (min-width: 500px) and (max-width: 557px) {
  .selected-data{
    background: url("/assets/img/selected_500.png");
  }
  .selected-data img {
    opacity: 0.10;
  }
  
  .img_padding_bottom{
    padding-bottom:12px !important;
  }
}

@media (min-width: 558px) and (max-width: 599px) {
  .selected-data{
    background: url("/assets/img/selected_558.png");
  }
  .selected-data img {
    opacity: 0.10;
  }
  
  .img_padding_bottom{
    padding-bottom:12px !important;
  }
}

@media (min-width: 600px) and (max-width: 624px) {
  .selected-data{
    background: url("/assets/img/selected_600.png");
  }
  .selected-data img {
    opacity: 0.10;
  }
  
  .img_padding_bottom{
    padding-bottom:12px !important;
  }
}

@media (min-width: 625px) and (max-width: 650px) {
  .selected-data{
    background: url("/assets/img/selected_625.png");
  }
  .selected-data img {
    opacity: 0.10;
  }
  
  .img_padding_bottom{
    padding-bottom:12px !important;
  }
}

@media (min-width: 651px) and (max-width: 766px) {
  .selected-data{
    background: url("/assets/img/selected_650.png");
  }
  .selected-data img {
    opacity: 0.10;
  }
  
  .img_padding_bottom{
    padding-bottom:12px !important;
  }
}
@media (min-width: 767px) and (max-width: 990px) {
  .selected-data{
    background: url("/assets/img/selected_153.png");
  }
  .selected-data img {
    opacity: 0.10;
  }
}
@media (min-width: 991px) and (max-width: 1920px) {
  .selected-data{
    background: url("/assets/img/Selected_50.png");
    }
    .selected-data img {
      opacity: 0.10;
    }
  }
  
    .success {
      color: green;
      display: block;
      font-size: 12px;
      padding-top: 5px;
  }
  </style>
<script type="text/javascript">
    $(document).ready(function(){
            
      $(".img-design").click(function(){
        $('.img-design').removeClass('selected-data');
        $(this).addClass('selected-data');
        var gc_price = $(this).data('gift_card_price');
        var gc_points = $(this).data('gift_card_points');
        
        $("#gc_price").val(gc_price);
        $("#gc_points").val(gc_points);
    });
      
      $("#redeem_gourmet_points").click(function(){
        $("#select_gift_error").addClass('hidden');
      $("#gift_card_success").addClass('hidden');
      $("#gift_card_fail").addClass('hidden');
      $("#not_enough_points").addClass('hidden');
        var gc_price_val = $("#gc_price").val();
        var gc_points_val = $("#gc_points").val();
        var gc_user_id_val = $("#gc_user_id").val();
        
        if(gc_price_val == 0 || gc_points_val == 0){
          $("#select_gift_error").removeClass('hidden');
        }else{
          $.ajax({
          url: "/user/redeem_points",
          type: "POST",
          dataType: "json",
          data:{gc_price: gc_price_val,gc_points:gc_points_val,gc_user_id:gc_user_id_val},
          beforeSend : function(){
            $("#redeem_loading").removeClass('hidden');
          },
          success: function(d) {
            //alert(d.message);
            if(d.message == 1){
              $("#gift_card_success").removeClass('hidden').html('You have successfully redeemed your Gourmet Points for a Gift Card. Your Gift card id is '+d.giftcard_id+'. This can be used immediately when making a reservation. You have also been sent an email with your gift card id.');
            } else if (d.message == 2){
              $("#gift_card_fail").removeClass('hidden').html('There seems to be a problem with your redemption. Please try again later or contact our concierge desk at concierge@gourmetitup.com.');
            }else if (d.message == 3){
              $("#not_enough_points").removeClass('hidden').html('You do not have enough Gourmet points for this redemption. You can earn more points by making reservations, writing reviews and recommending your friends to WowTables.');
            }
            $("#redeem_loading").addClass('hidden');
            $("#gc_price").val('');
              $("#gc_points").val('');
              $('.img-design').removeClass('selected-data');
          }
        });
        }
      });
    });
  </script>
  <!--==============Content Section closed=================--> 
  
<!--==============Top Section closed=================-->

<div class="container reservation-page">
<div class="row">
    <?php if(empty(Auth::user()->id)) { ?>

        <div class="col-md-3 col-sm-3 reservation-menu">
          <aside class="affix-top res-aside">
            <h4 class="text-center aside-title">MY ACCOUNT</h4>
            <div class="list-group">              
               <a href="{{URL::to('/')}}/users/redeem-rewards" class="list-group-item active">Redeem Gourmet Points</a>                        
            </div>
          </aside>
          <div class="query-contact">
            <p>Got a question? <br> Call our Concierge at 9619551387</p>
          </div>
        </div>

    <?php }else{?>

        <div class="col-md-3 col-sm-3 reservation-menu">
          <aside class="affix-top res-aside">
            <h4 class="text-center aside-title">MY ACCOUNT</h4>
            <div class="list-group">              
                                        
                <a href="{{URL::to('/')}}/users/myreservations" class="list-group-item">
                  My Reservations
                </a>
                <a href="{{URL::to('/')}}/users/myaccount" class="list-group-item ">My Profile</a>
               <a href="{{URL::to('/')}}/users/redeem-rewards" class="list-group-item active">Redeem Gourmet Points</a>
                 
                <a href="{{URL::to('/')}}/users/logout" class="list-group-item">Logout</a>  
                         
            </div>
          </aside>
          <div class="query-contact">
            <p>Got a question? <br> Call our Concierge at 9619551387</p>
          </div>
        </div>
     <?php } ?>
       
          
     <div class="col-md-9 col-sm-9 reservations-wrap myprofile-wrap"> 
     <div class="row">
        <div class="col-sm-12 col-md-12">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab_a" data-toggle="tab">Redeem Gourmet Points</a></li>
    <li><a href="#tab_b" data-toggle="tab">About Gourmet Points</a></li>
  </ul>
  <div class="tab-content">
        <div class="tab-pane active" id="tab_a">
        <br />
       <p>
        Redeem your Gourmet Points for WowTables Gift Cards. These gift cards can be used to pay for your next WowTables experience reservation. Note that you need to enter the Gift Card ID in the special requests box while making a reservation.
         </p>
        <p>
         <span style="font-size:18px;"><strong>Terms:</strong></span>
        </p>
          <p>
            WowTables Gift Cards are only valid with a WowTables experience reservation made in advance and not for A la carte reservations or directly at restaurants.
      Gift Cards are valid for 3 months from the date of issue.
      Other terms might apply. Please review our gift cards page
          </p>
           <p>
         <span style="font-size:16px;"><strong>Select a Gift Card:</strong></span><br />
        </p>
        <div class="row">
          <div class="col-sm-4 img_padding_bottom">
          <div class="option grid-3 alpha">
            <div class="price">
              <img src="/assets/img/Indian_Rupee_symbol.png" height="15" width="10" style="margin-top: -8px;margin-left:20px;"/><span style="font-size: 20px;">500</span>
            </div>
            <div class="photo">
              <div class="img-design" data-gift_card_price="500" data-gift_card_points="3000">
                <img src="/assets/img/Redeem_Gc_500.jpg" class="img-responsive" style="height: 150px !important;width:100% !important;"/>
                <!--<div class="selected-data" style="display:none;">Selected</div>-->
              </div>
            </div>
            <div class="blurb">
              <span style="font-size: 20px;">3000 Pts.</span>
            </div>
            </div>
          </div>
          <div class="col-sm-4 img_padding_bottom">
          <div class="option grid-3 alpha">
            <div class="price">
              <img src="/assets/img/Indian_Rupee_symbol.png" height="15" width="10" style="margin-top: -8px;margin-left:20px;"/><span style="font-size: 20px;">1000</span>
            </div>
            <div class="photo">
              <div class="img-design" data-gift_card_price="1000" data-gift_card_points="6000">
                <img src="/assets/img/Redeem_Gc_1000.jpg" class="img-responsive" style="height: 150px !important;width:100% !important;"/>
                <!--<div class="selected-data" style="display:none;">Selected</div>-->
            </div>
            </div>
            <div class="blurb">
              <span style="font-size: 20px;">6000 Pts.</span>
            </div>
            </div>
          </div>
          <div class="col-sm-4">
          <div class="option grid-3 alpha">
            <div class="price">
              <img src="/assets/img/Indian_Rupee_symbol.png" height="15" width="10" style="margin-top: -8px;margin-left:20px;"/><span style="font-size: 20px;">1500</span>
            </div>
            <div class="photo">
              <div class="img-design" data-gift_card_price="1500" data-gift_card_points="9000">
                <img src="/assets/img/Redeem_Gc_1500.jpg" class="img-responsive" style="height: 150px !important;width:100% !important;"/>
                <!--<div class="selected-data" style="display:none;">Selected</div>-->
            </div>
            </div>
            <div class="blurb">
              <span style="font-size: 20px;">9000 Pts.</span>
            </div>
          </div>
          </div>
        </div>
    <?php //echo print_r($this->session->all_userdata());?>
        <div class="form-group" style="padding-top: 10px;">
          <input type="hidden" name="gc_price" id="gc_price" value="0" />
          <input type="hidden" name="gc_points" id="gc_points" value="0" />
          <input type="hidden" name="user_id" id="gc_user_id" value="<?php if(empty(Auth::user()->id)){echo '';}else{echo Auth::user()->id;}?>" />
          <?php if(empty(Auth::user()->id)){?>
          <button type="button" class="btn btn-inverse" data-toggle="modal" data-target="#redirectloginModal" data-page_loc="Header">Redeem</button>
          <!-- <a style="color:#9d9d9c !important;font-size:13px !important;font-family:Swis721 Lt BT !important;" data-toggle="modal" data-page_loc="Header" data-target="#redirectloginModal" href="#" class="border_none header_loc wowtable_font">Sign in | Register</a> -->
          <?php }
          else{?>
          <button type="button" class="btn btn-inverse"  id='redeem_gourmet_points'>Redeem</button>
          <?php }?>
          <span class="hidden" id="redeem_loading"><img src="assets/img/loading.gif" title="loading.."/></span>
        </div>  
        <span class="error hidden" id="select_gift_error">Please select a gift card to redeem.</span>
        <span class="success hidden" id="gift_card_success"></span>
        <span class="error hidden" id="gift_card_fail"></span>
        <span class="error hidden" id="not_enough_points"></span>
        </div>
        <div class="tab-pane" id="tab_b">  
         <br />  
         <br />
         <p>
       <span style="font-size:18px;"><strong>About Gourmet Points</strong></span>
         </p>
       <p>
        Gourmet Points are our way of rewarding regular users of WowTables!  Think of it as a thank you for trusting us with your fine dining activities.
         </p>

       <p>
          <span style="font-size:18px;"><strong>How do I earn Gourmet Points?</strong></span>
       </p>
          <p>
            Gourmet Points can be earned in the following ways:
          </p>
          <p>
            - Making a reservation (500 - 1500 Gourmet Points): The points awarded per reservation are mentioned on the reservation page.
          </p>
          <p>
            - Refer a friend (3000 Gourmet Points): Tell others about WowTables and you both get rewarded the first time they make a reservation.
          </p>
       <p>
          <span style="font-size:18px;"><strong>How do I use Gourmet Points?</strong></span>
       </p>
        <p>
          Gourmet Points can be redeemed for our WowTables gift cards to be used towards your next WowTables experience reservation. To redeem your points please visit the Redeem Gourmet Points tab.
        </p>
     <p>
          <span style="font-size:18px;"><strong>FAQ:</strong></span>
       </p>
       <p>
          <span style="font-size:18px;"><strong>Do my points expire?</strong></span>
       </p>
        <p>
          If your account has been inactive for a period of 12 months, all points will automatically expire. All you have to do is make 1 reservation in a year to keep your account active.
        </p>
       <p>
          <span style="font-size:18px;"><strong>What if I cancel my reservation or don't end up going to the restaurant.</strong></span>
       </p>
        <p>
          - Points will be deducted for any cancellations or no-shows.
        </p>
       <p>
          <span style="font-size:18px;"><strong>Can I get points for reservations made over the phone?</strong></span>
       </p>
        <p>
          -We only award Gourmet Points for reservations made through our website and not over the phone. However if you are having difficulty with using the website we will be happy to make an exception.
        </p>
        <p>
          <span style="font-size:18px;"><strong>Can I redeem a gift card with points earned on a  reservation made but not yet completed?</strong></span>
       </p>
        <p>
          -No. Gourmet Points earned for reservations can only be used for gift card redemptions 1 week after a successful seating.
        </p>
                   
            
        </div>
    </div><!-- tab content -->
        </div>
      </div>     
     
      <!--Content closed--> 
      </div>
    </div>
  </div>

@endsection

