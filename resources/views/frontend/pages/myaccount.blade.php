@extends('frontend.templates.inner_pages')

@section('content')

<!--==============Top Section closed=================-->
<style type="text/css">
.notice{
    border:1px solid #666;
    padding:8px 10px 0px 39px;
    margin-top: 24px;
    border-radius:10px;
    background-color: #a2d246;
    font-size: 13px;
    width: 58%;
}
</style>
<script>
$(document).ready(function(){
    
   //alert('hi');
 $("#flash_notice").fadeOut(3000);
    
});
</script>
<div class="container reservation-page">
<div class="row">
        <div class="col-md-3 col-sm-3 reservation-menu">
          <aside class="affix-top res-aside">
            <h4 class="text-center aside-title">MY ACCOUNT</h4>
            <div class="list-group">              
                                        
                <a href="{{URL::to('/')}}/users/myreservations" class="list-group-item">
                  My Reservations
                </a>
                <a href="{{URL::to('/')}}/users/myaccount" class="list-group-item active">My Profile</a>
               <a href="{{URL::to('/')}}/users/redeem-rewards" class="list-group-item ">Redeem Gourmet Points</a>
                 
                <a href="{{URL::to('/')}}/users/logout" class="list-group-item">Logout</a>  
                         
            </div>
          </aside>
          <div class="query-contact">
            <p>Got a question? <br> Call our Concierge at 9619551387</p>
          </div>
        </div>
       
          
     <div class="col-md-9 col-sm-9 reservations-wrap myprofile-wrap"> 
       <p class="lead">Profile:</p>
      <?php if(Session::has('flash_notice')) { ?>
    <div id="flash_notice" class="notice" style="">
      <p>Profile updated successfully. </p>
    </div><br />
<?php }?>
      <dl class="dl-horizontal">
      
            <dt>Full Name:</dt>
            <dd>{{$data['data']['full_name']}}</dd>
            <dt>Email:</dt>
            <dd>{{$data['data']['email']}}</dd>
            <dt>Zip Code:</dt>
            <dd>{{$data['data']['zip_code']}}</dd>
            <dt>Phone:</dt>
            <dd>{{$data['data']['phone_number']}}</dd>
            <dt>Gender:</dt>
            <dd>{{$data['data']['gender']}}</dd>
            <dt>Date of Birth:</dt>
            <dd>{{$data['data']['dob']}}</dd>
            <dt>Anniversary Date:</dt>
            <dd>{{$data['data']['anniversary_date']}}</dd>
            <dt>City:</dt>
            <dd>{{$data['data']['location']}}</dd>
            <dd><a href="{{URL::to('/')}}/users/updateinfo" class="btn btn-warning">Update Profile Info</a></dd>
          </dl>

          <hr>          

          <p class="lead">Membership Details:</p>
          <dl class="dl-horizontal">
            <!-- <dt>Tier:</dt>
            <dd>Nibble</dd> -->
            <dt>Points Earned:</dt>
            <dd>{{$data['data']['points_earned']}}</dd>
            <dt>Ponts Spent:</dt>
            <dd>{{$data['data']['points_spent']}}</dd>
            <dt>Points Remaining:</dt>
            <dd>{{$data['data']['points_remaining']}}</dd>
            <dt>Bookings:</dt>
            <dd>{{$data['data']['bookings_made']}}</dd>
            <dt>Last Booking Made:</dt>
            <dd>{{$data['data']['last_reservation_date']}} {{$data['data']['last_reservation_time']}}</dd>
          </dl>
     
      <!--Content closed--> 
      </div>
    </div>
  </div>

@endsection