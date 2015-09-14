@extends('frontend.templates.details_pages')

@section('content')

<!--==============Top Section start=================-->

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
        <!--start updateinfo div-->
          <div class="col-md-9 col-sm-9 reservations-wrap myprofile-wrap">
        <p class="lead">Update Your Profile:<?php //print_r($cities);?></p>
        @if($errors->has())
        @foreach ($errors->all() as $error)
            <div class="text-danger" style="margin-left: 26%;">{{ $error }}</div>
        @endforeach
        @endif
        <form action="{{URL::to('/')}}/users/updateUserinfo" method="post" id="users_form" class="form-horizontal update-profile" role="form">
         <div class="form-group">
            <label for="inputEmail3" class="col-sm-3 control-label">Email:</label>
            <div class="col-sm-9">
                <input type="email" name="email" class="form-control" id="inputEmail3" placeholder="Email" disabled="disabled" value="{{$data['data']['email']}}">
            </div>
         </div>
         <div class="form-group">
            <label class="col-sm-3 control-label">Full Name:</label>
            <div class="col-sm-9">
             <input type="text" name="full_name" id="full_name" class="form-control" value="{{$data['data']['full_name']}}" required>
            </div>
         </div>
         <div class="form-group">
            <label class="col-sm-3 control-label">Password:</label>
            <div class="col-sm-9">
                <input type="password" name="password" id="password" class="form-control" value="" placeholder="Please type a new password if you want to change">
            </div>
         </div>
         <div class="form-group">
            <label class="col-sm-3 control-label">Zip Code:</label>
            <div class="col-sm-9">
                <input type="text" name="zip_code" id="zip_code" class="form-control" value="{{$data['data']['zip_code']}}" required>
            </div>
         </div>
         
          <div class="form-group">
            <label class="col-sm-3 control-label">Phone:</label>
            <div class="col-sm-9">
                <input type="text" name="phone_number" id="phone" class="form-control" value="{{$data['data']['phone_number']}}" value="" required>
            </div>
         </div>
         <div class="form-group">
            <label class="col-sm-3 control-label">Date of Birth:</label>
            <div class="col-sm-9">
                <input type="text" name="dob" id="dob" class="form-control" readonly="readonly" value="{{$data['data']['dob']}}" required>
            </div>
         </div>
         <div class="form-group">
            <label class="col-sm-3 control-label">Anniversary Date:</label>
            <div class="col-sm-9">
              <?php if($data['data']['anniversary_date']=='0' || $data['data']['anniversary_date']=="")
                    {
                      $aniversaryDate = '0000-00-00';
                    }
                    else
                    {
                      $aniversaryDate = $data['data']['anniversary_date'];
                    }
                    ?>
                <input type="text" name="aniversary_date" id="aniversary_date" class="form-control" readonly="readonly" value="{{$aniversaryDate}}" required>
            </div>
         </div>
         <div class="form-group">
            <label class="col-sm-3 control-label">Gender:</label>
             <div class="col-sm-9">
             <label class="radio-inline"> 
                <input type="radio" name="gender" id="gender1" value="Male" checked="checked" required>
                Male
             </label>
             <label class="radio-inline">
                <input type="radio" name="gender" id="gender2" value="Female" required>
                Female
             </label>
            </div>
         </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">City:</label>
            <div class="col-sm-9">
                <select name="location_id" class="form-control" required>
            <!-- <option value="mumbai">Mumbai</option>
            <option value="delhi" selected="">Delhi</option>
            <option value="pune">Pune</option>
            <option value="bangalore">Bangalore</option> -->
                <?php
                foreach ($cities as $key => $city) 
                {

                    echo '<option value="'.$key.'">'.$city.'</option>'; 
                } 
                ?>
                </select>
            </div>
         </div>
         <input type="hidden" name="id" value="21330">
          <div class="form-group">
              <div class="col-sm-offset-3 col-sm-9">
                 <input type="submit" class="btn btn-warning" name="submit" value="Update Profile">  
              </div>
          </div> 
     </form>

  
<link href="{{URL::to('/')}}/css/ui-lightness/jquery-ui-1.10.0.custom.css?ver=1.0.2" rel='stylesheet' type="text/css"> 
<script language="javascript">
$(document).ready(function(){
    var k=0;
    
    $("#users_form").submit(function(){
       //alert("Form submit") ;
       msg='';
      // email_regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;           
    if($("#full_name").val()==''){msg= msg+ 'Please enter Full Name'+ " \n";}
    
      // if($("#password").val()==''){msg= msg+ 'Please enter password'+ " \n";}
       
       if($("#zip_code").val()=='' || isNaN($("#zip_code").val())){msg= msg+ 'Please enter value for zip code'+ " \n";}
       if(isNaN($("#phone").val())){msg= msg+ 'Please enter valid numeric value for Phone'+ " \n";}
       if($("#content").val()==''){msg= msg+ 'Please enter page contents'+ " \n";}
       if(msg==''){ return true;}
       else{         
            alert(msg);
        return false;
       }
    });
})
</script>
      </div>

      <!--closed updateinfo div-->
    
    </div>
  </div>
<!--==============Top Section start=================-->
@endsection