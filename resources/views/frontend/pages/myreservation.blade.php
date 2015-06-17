@extends('frontend.templates.inner_pages')

@section('content')
<!--==============Top Section closed=================-->
<?php  //echo json_encode($data['schedule']); exit;
/*$schedule = $data['schedule'];
$reserveData = $data['reserveData'];
  $weeks = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');
  $week_number = date('w',strtotime('2015-06-12'));
  $week = $weeks[$week_number];
  $myscheduleTime = $data['schedule']['97'][$week];
/*print_r($myscheduleTime);
exit;*/
 //print_r($reserveData);
/* $min_people = $data['reserveData']['97']['min_people'];
 $max_people = $data['reserveData']['97']['max_people'];*/
 
?>
<div class="container reservation-page">
      <div class="row">
        <div class="col-md-3 col-sm-3 reservation-menu">
          <aside class="affix-top res-aside">
            <h4 class="text-center aside-title">MY ACCOUNT</h4>
            <div class="list-group">              
                                        
                <a href="{{URL::to('/')}}/users/myreservations" class="list-group-item active">
                  My Reservations
                </a>
                <a href="{{URL::to('/')}}/users/myaccount" class="list-group-item">My Profile</a>
                            <a href="http://wowtables.com/redeem-rewards" class="list-group-item ">
                Redeem Gourmet Points
              </a>
                 
             <a href="{{URL::to('/')}}/users/logout" class="list-group-item">Logout</a>  
                         
              
            </div>
          </aside>
          <div class="query-contact">
            <p>Got a question? <br> Call our Concierge at 9619551387</p>
          </div>
        </div>   
        <div class="col-md-9 col-sm-9 reservations-wrap" id="myres_div">
          <p class="lead wrap-title">Upcoming Reservations:</p>
               <?php foreach ($arrReservation['data']['upcomingReservation'] as $data) {
            ?>
          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="row">
                <span class="lead col-md-8">
                  <?php if($data['type']=='experience')
                  {
                     echo $data['name'];

                  } 
                  else
                  {
                    echo $data['vendor_name'] .' : '.'A la carte Reservation';
                  }
                  ?>
                </span>
                <ul class="col-md-4 list-inline text-right">
              <?php if($data['type']=='experience')
                    {?>
                  <li>
                  <a href="<?php echo $data['type'].','.$data['vl_id'].','.$data['product_id'];?>" class="btn btn-defaulbt tn-sm" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#editModal" id="change_reservation">Change</a>
                </li>
                    <?php 
                  }else if($data['type'] == "alacarte")
                    {?>
                    <li>
                  <a href="<?php echo $data['type'].','.$data['vendor_location_id'].','.$data['vendor_location_id'];?>" class="btn btn-defaulbt tn-sm" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#editModal" id="change_reservation">Change</a>
                </li>

                    <?php }?>
                                    <li>
                  <?php if($data['type'] == "experience"){
                          $change_id = "cancel_reservation";
                        } else if($data['type'] == "alacarte"){
                          $change_id = "ac_cancel_reservation";
                        }
                  ?>                    
                  <a href="javascript:" class="btn btn-default btn-sm" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#cancelModal" id="<?php echo $change_id;?>" data-reserve-type="{{$data['type']}}">Cancel</a>
                  </li>
                  <input type="hidden" value="{{$data['id']}}"> 
                  <input type="hidden" value="{{$data['type']}}" class="reserv_typee"> 
                </ul>
              </div>              
            </div>
            <div class="panel-body">
              <?php if($data['type']=='experience')
                  {
                    echo "<p class='res-desc'><strong>Description: ".$data['short_description']."</strong></p>";
                  } 
                  else
                  {
                    //echo $data['name'] .' : '.'A la carte Reservation';
                  }

                  ?>
              <!-- <p class="res-desc"><strong>Description: </strong>Packed with oozing goodness and topped with the freshest ingredients, take a bite of authentic California-style pizza.</p> -->
              <div class="row">
                <div class="col-md-4 col-sm-4 res-details">
                  <ul>
                    <li>
                      <p class="text-warning"><em>Date</em></p>
                       <p><strong>{{$data['dayname']}}, {{date('F j Y',strtotime($data['date']))}}</strong></p>
                    </li>
                    <li>
                      <p class="text-warning"><em>Time</em></p>
                      <p><strong>{{date('h:i A',strtotime($data['time']))}}</strong></p>
                    </li>
                    <li>
                      <p class="text-warning"><em>Number of guests</em></p>
                      <p><strong>{{$data['no_of_persons']}}</strong></p>
                    </li>
                    <li>
                      <p class="text-warning"><em>Reservation ID</em></p>
                      <p><strong>EU-{{$data['id']}}</strong></p>
                    </li>

                    <?php if($data['type']=='experience')
                    {?>
                      <li>
                      <p class="text-warning"><em>Experience</em></p>
                      <p><strong><a href="{{URL::to('/')}}/{{$data['city']}}/experiences/{{$data['product_slug']}}" target="_blank">View Details</a></strong></p>
                    </li>
                  <?php } 
                  
                  ?>
                    
                    
                  </ul>
                </div>
                <div class="col-md-8 col-sm-8 res-location">
                  <ul>                    
                    <li>
                      <p class="text-warning"><em>Outlet</em></p>
                      <p><strong>{{$data['locality']}}</strong></p>
                    </li>
                    <li>
                      <p class="text-warning"><em>Address</em></p>
                      <address>
                        <strong>{{$data['name']}}</strong><br>
                        {{$data['address']}}<br>
                        <!--<abbr title="Phone">P:</abbr> CPK at Bandra Kurla Complex  
Hanif, General Manager: 7738899507 
gm.cpkbandra@jsmcorp.in

Adesh, Assistant General Manager:7738899506 
agm.cpkbandra@jsmcorp.in
022 - 6558 8888-->
                        <!--lattitude_coordinate
                        longitude_coordinates-->
                      </address>
                    </li>
                      <li>
                  
                        <div id="map{{$data['id']}}" style="height:200px;width:100%">
                         
                    <script type="text/javascript">
                         var dealer_lat{{$data['id']}} = '{{$data['latitude']}}';
                         var dealer_lng{{$data['id']}} = '{{$data['longitude']}}';
                        function initialize() {
                          var  mylatlng{{$data['id']}} =  new google.maps.LatLng(dealer_lat{{$data['id']}}, dealer_lng{{$data['id']}});
                          var mapOptions{{$data['id']}} = {
                            center: mylatlng{{$data['id']}},
                            zoom: 16
                          };
                          var map{{$data['id']}} = new google.maps.Map(document.getElementById("map{{$data['id']}}"),
                              mapOptions{{$data['id']}});
                       
                          var marker{{$data['id']}} = new google.maps.Marker({
                            position: mylatlng{{$data['id']}},
                            map: map{{$data['id']}} 
                          });
                        }
                        google.maps.event.addDomListener(window, 'load', initialize);
                              
                    </script>
                    </div>
                    </li>
                  </ul>               
                </div>
                <?php if($data['type']=='alacarte')
                  { ?>

                    <div class="col-md-8 col-sm-8 thankyou-main" style="float:right">
                     <div class="thankyou-tab">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#details" data-toggle="tab">
                              A la carte T&amp;C
                          </a>
                          </li>
                        </ul>
                    <div class="tab-content">
                    <div class="tab-pane fade in active" id="details">
                        <ul>
                        <ul>
                            <li>
                              No pre-payment is necessary. Please pay directly at the restaurant.</li>
                            <li>
                              Please make a reservation at least 2 hours in advance to ensure availability.</li>
                            <li>
                              After making a reservation, you will receive a confirmation by e-mail as well as SMS.</li>
                            <li>
                              Rights to table reservation is solely on the basis of availability.</li>
                            <li>
                              Call our Concierge service at 9619551387 if you have any queries.</li>
                          </ul>
                          <p>
                            &nbsp;</p>
                        </ul>              
                    </div>
                  </div>
                      </div>  
                  </div>

                  <?php } ?>
              </div>              
            </div>
          </div>
          <?php }?> 

                   <hr>
          <p class="lead wrap-title">Previous Reservations:</p>
          <?php foreach ($arrReservation['data']['pastReservation'] as $data) {
            ?>
          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="row">
                <span class="lead col-md-8">
                  <?php if($data['type']=='experience')
                  {
                    echo $data['name'];
                  } 
                  else
                  {
                    echo $data['name'] .' : '.'A la carte Reservation';
                  }
                  ?>
                  
                </span>
              </div>              
            </div>
            <div class="panel-body">
              <?php if($data['type']=='experience')
                  {
                    echo "<p class='res-desc'><strong>Description: ".$data['short_description']."</strong></p>";
                  } 
                  else
                  {
                    //echo $data['name'] .' : '.'A la carte Reservation';
                  }

                  ?>
              <!-- <p class="res-desc"><strong>Description: </strong>Packed with oozing goodness and topped with the freshest ingredients, take a bite of authentic California-style pizza.</p> -->
              <div class="row">
                <div class="col-md-4 col-sm-4 res-details">
                  <ul>
                    <li>
                      <p class="text-warning"><em>Date</em></p>
                       <p><strong>{{$data['dayname']}}, {{date('F j Y',strtotime($data['date']))}}</strong></p>
                    </li>
                    <li>
                      <p class="text-warning"><em>Time</em></p>
                      <p><strong>{{date('h:i A',strtotime($data['time']))}}</strong></p>
                    </li>
                    <li>
                      <p class="text-warning"><em>Number of guests</em></p>
                      <p><strong>{{$data['no_of_persons']}}</strong></p>
                    </li>
                    <li>
                      <p class="text-warning"><em>Reservation ID</em></p>
                      <p><strong>EU-{{$data['id']}}</strong></p>
                    </li>

                    <?php if($data['type']=='experience')
                    {?>
                      <li>
                      <p class="text-warning"><em>Experience</em></p>
                      <p><strong><a href="{{URL::to('/')}}/{{$data['city']}}/experiences/{{$data['product_slug']}}" target="_blank">View Details</a></strong></p>
                    </li>
                  <?php } 
                  
                  ?>
                    
                    
                  </ul>
                </div>
                <div class="col-md-8 col-sm-8 res-location">
                  <ul>                    
                    <li>
                      <p class="text-warning"><em>Outlet</em></p>
                      <p><strong>{{$data['locality']}}</strong></p>
                    </li>
                    <li>
                      <p class="text-warning"><em>Address</em></p>
                      <address>
                        <strong>{{$data['name']}}</strong><br>
                        {{$data['address']}}<br>
                        <!--<abbr title="Phone">P:</abbr> CPK at Bandra Kurla Complex  
Hanif, General Manager: 7738899507 
gm.cpkbandra@jsmcorp.in

Adesh, Assistant General Manager:7738899506 
agm.cpkbandra@jsmcorp.in
022 - 6558 8888-->
                        <!--lattitude_coordinate
                        longitude_coordinates-->
                      </address>
                    </li>
                      <li>
                  
                        <div id="map{{$data['id']}}" style="height:200px;width:100%">
                         
                    <script type="text/javascript">
                         var dealer_lat{{$data['id']}} = '{{$data['latitude']}}';
                         var dealer_lng{{$data['id']}} = '{{$data['longitude']}}';
                        function initialize() {
                          var  mylatlng{{$data['id']}} =  new google.maps.LatLng(dealer_lat{{$data['id']}}, dealer_lng{{$data['id']}});
                          var mapOptions{{$data['id']}} = {
                            center: mylatlng{{$data['id']}},
                            zoom: 16
                          };
                          var map{{$data['id']}} = new google.maps.Map(document.getElementById("map{{$data['id']}}"),
                              mapOptions{{$data['id']}});
                       
                          var marker{{$data['id']}} = new google.maps.Marker({
                            position: mylatlng{{$data['id']}},
                            map: map{{$data['id']}} 
                          });
                        }
                        google.maps.event.addDomListener(window, 'load', initialize);
                              
                    </script>
                    </div>
                    </li>
                  </ul>               
                </div>
                <?php if($data['type']=='alacarte')
                  { ?>

                    <div class="col-md-8 col-sm-8 thankyou-main" style="float:right">
                     <div class="thankyou-tab">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#details" data-toggle="tab">
                              A la carte T&amp;C
                          </a>
                          </li>
                        </ul>
                    <div class="tab-content">
                    <div class="tab-pane fade in active" id="details">
                        <ul>
                        <ul>
                            <li>
                              No pre-payment is necessary. Please pay directly at the restaurant.</li>
                            <li>
                              Please make a reservation at least 2 hours in advance to ensure availability.</li>
                            <li>
                              After making a reservation, you will receive a confirmation by e-mail as well as SMS.</li>
                            <li>
                              Rights to table reservation is solely on the basis of availability.</li>
                            <li>
                              Call our Concierge service at 9619551387 if you have any queries.</li>
                          </ul>
                          <p>
                            &nbsp;</p>
                        </ul>              
                    </div>
                  </div>
                      </div>  
                  </div>

                  <?php } ?>
              </div>              
            </div>
          </div>
          <?php }?>                                  
      <div class="last_reservations">
                <div id="load_layer" class="change_loader">
                    <img src="/images/loading.gif">
                </div>
                <button id="show_more_reservations" type="button" class="btn btn-warning">Load All Previous Reservations</button>
                <input type="hidden" id="map_id" value="5">
          </div>
                  </div>
      </div>
    </div>


    <!--edit Modal -->
     <!--edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content" style="background: #EAB703 !important;">
            <div id="load_layer" class="change_loader">
            <img src="/images/loading.gif">
            </div>
          <div class="modal-header" style="margin-top:-7px !important;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id='close_changes'>&times;</button>
            <h4 class="modal-title text-center" id="myModalLabel">Change This Reservation</h4>
          </div>
          <div class="modal-body">
           <div id="reserv_table">
                        
                           <div class="panel panel-default">
                <div class="panel-heading active">
                  <h4 class="panel-title">
                     <a href="javascript:" style="text-decoration: none;">
                      Select Party Size </a><a  href="javascript:" data-original-title="Select the total number of guests at the table. If a larger table size is needed, please contact the WowTables Concierge." data-placement="top" data-toggle="tooltip" class="btn tooltip1"><img src="http://wowtables.app/images/question_icon_small_display.png"></a>
                      <select name="qty" id="party_size1"  class="pull-right space hidden">
                            <option value="0">SELECT</option>
                         
                            <!-- <option value="2">2 People</option>
                            <option value="7">7 People</option> -->

                     </select>
                        <strong><a id="party_edit1" href="javascript:"  style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color:#756554 !important;" id="myselect_person"></span> EDIT</a></strong>
                  </h4>
                </div>
             
              </div>
              <div class="panel panel-default">
                <div class="panel-heading" >
                  <h4 class="panel-title">
                    <a href="javascript:" style="text-decoration: none;">
                      Select Date <input type="hidden" value="" id="vendor_id">
                    </a>
                     <strong><a id="date_edit12"  data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color:#756554 !important;" id="myselect_date"></span> EDIT</a></strong>
                  </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse">
                  <div class="panel-body">
                    <div class="input-append date" id='dp1' data-date-format="dd-mm-yyyy">
                        <input type="hidden" value="" name="booking_date" id="booking_date">
                        <div class="options" style="margin: -10px;">
                            <div id="choose_date"></div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <a href="javascript:" style="text-decoration: none;">
                      Select Time
                    </a>
                    <strong><a id="time_edit5"  data-toggle="collapse" data-parent="#accordion" href="#collapseThree" style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;"><span style="color:#756554 !important;" id="myselect_time"></span> EDIT</a></strong>
                  </h4>
                </div>
                <div id="collapseThree5" class="panel-collapse collapse in" style="display:none;">
                  <div class="panel-body" id="timeajax">
                       
                </div>
              </div>
                                 
              </div>
              <a id="save_changes" class="btn btn-warning" href="javascript:" style="margin-left: 32%;display:none;">Confirm Changes</a> 
           
            <div class="text-center" >
                
                <input type="hidden" name="res_id" id="res_id"> 
                <input type="hidden" value="" id="last_reserv_date" name="last_reserv_date">
                <input type="hidden" value="" id="last_reserv_time" name="last_reserv_time">
                <input type="hidden" value="Nariman Point" id="last_reserv_outlet" name="last_reserv_outlet">
                <input type="hidden" value="2" id="last_reserv_party_size" name="last_reserv_party_size">
                <p id="cant_change_table" class="hidden">To make changes to your reservation for this evening please call our concierge or the restaurant directly.</p>
                <p id="cant_change_table" class="hidden cant_change">Please make a change to confirm. If no change is required, please click on cancel.</p>
                <a aria-hidden="true" data-dismiss="modal" id="cancel" class="btn btn-warning hidden cant_change" href="javascript:">Cancel</a>        
              </div>
          </div>
            <div class="change_reserv_confirmation hide" >
                 <h4 class="panel-title" style="margin-bottom: 20px;color:#fff;">
                    We have received your table change request. You will receive a confirmation mail & SMS from our concierge soon.
                </h4>
                <div class="text-center">
              <a  class="btn btn-warning close_modal" href="javascript:" data-dismiss="modal">Close This</a>
            </div>
            </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div>

     <!--Cancel Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content" style="background: #EAB703 !important;">
        <div id="load_layer" class="cancel_loader">
            <img src="/images/loading.gif">
        </div>
          <div class="modal-header" style="margin-top: -5px !important;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title text-center" id="myModalLabel">Cancel Reservation</h4>
          </div>
          <div class="modal-body">
          <div class="cancel_reserv_form">
                <h4 class="panel-title" style="margin-bottom:20px;color:#756554!important;">
                    Are you sure you want to cancel this reservation? Once cancelled, it cannot be undone.
                </h4>
            <div class="text-center">
              <a type="button" class="btn btn-warning" href="javascript:" id='cancel_current'>Yes, Cancel It</a>
              <a type="button" class="btn btn-warning close" data-dismiss="modal" style="color:#000;text-shadow:0 0 0 #000">Nevermind</a>
              <input type="hidden" name='reserv_id'>
            </div>
         </div>   
          </div>
          <div class="cancel_reserv_confirmation hide">
                <h4 class="panel-title text-center" style="margin-bottom: 20px;color:#fff;">
                   We have received your cancel request.
                </h4>
                <div class="text-center">+6*/
                <a  class="btn btn-warning close_modal" href="javascript:" data-dismiss="modal">Close This</a>
                </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

  <div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title text-center" id="myModalLabel">Share Reservation Details</h4>
          </div>
          <div class="modal-body">
          <div id="email_form">
            <form>
              <div class="form-group">
                <label for="">Add Email Addresses</label>
                <textarea class="form-control" rows="3" id='guest_emails'></textarea> 
                <div class="row">
                  <div class="col-xs-6"><small>seperate with commas (,)</small></div>
                </div>
                <div class="row">
                  <div class="col-xs-12 hidden" id="error_email"><small>Please enter a valid email.</small></div>
                </div>
              </div>

              <div class="form-group">
                <textarea class="form-control" rows="3" placeholder="Enter a personal message here." id='det_content'></textarea>
                <div class="row">
                  <div class="col-xs-12 hidden" id="error_content"><small>Please enter your message.</small></div>
                </div>
                <div class="col-xs-12 reservation-msg">
                    <p>The email to your party will include your personal message above as well as details about the date, time, location and experience details.</p>
                </div>                
              </div>
              <input type="hidden" id="reservid" name='reservid' value='12'>
              <input type="hidden" name='userid' value="21330">
              <input type="hidden" id="experienceid" name="experienceid" value="5">  
              <input type="hidden" name='user_email' value="tech@gourmetitup.com">        
              <button type="submit" class="btn btn-warning btn-block" id="thank_details">Share Details</button>
            </form>
            </div>
            <div id="email_sent_confirmation" class="hidden">
                <div class="col-xs-12 reservation-msg">
                    <p>Your message has been sent</p>
                </div>
                 <button type="button" class="btn btn-warning btn-block" data-dismiss="modal" aria-hidden="true">Close This</button>
            </div>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <link href="{{URL::to('/')}}/css/ui-lightness/jquery-ui-1.10.0.custom.css?ver=1.0.2" rel='stylesheet' type="text/css"> 
    <script type="text/javascript">
       //code for floating reservation button
      $(function() {
             var offsetPixels = 50; // change with your sidebar height

             $(window).scroll(function() {
                     if ($(window).scrollTop() > offsetPixels) {
                             $(".scrollingBox").css({
                                     "position": "fixed",
                                     "top": "88%"
                             });
                     } else {
                             $(".scrollingBox").css({
                                     "position": "relative",
                                     "top": "88%"
                             });
                     }
             });
      }); 
       
      function changeClass()
      {
            document.getElementById("menu_tab").classList.add('active');
            document.getElementById("info_tab").classList.remove('active');
            document.getElementById('menu').className = "tab-pane fade in active"; 
            document.getElementById('info').className = "tab-pane fade";
            
       }

       
       /* var disabledAllDays = <?php echo json_encode('2015-05-06');?>;
        var allschedule = <?php //echo json_encode('5');  ?>'';
        var reserveminmax = <?php //echo json_encode('2015-05-06');  ?>'';*/
        var disabledAllDays = <?php //echo json_encode($data['block_dates']);?>'';
        var allschedule = <?php   ?>'';
        var reserveminmax = <?php   ?>'';

        function disableAllTheseDays(date) {
            var m = date.getMonth(), d = date.getDate(), y = date.getFullYear(),mon="",day="";
            var location_id = $('#locations1').val();
            var disabledDays = disabledAllDays[location_id];
           
            if(disabledDays != undefined)
            {
              for (i = 0; i < disabledDays.length; i++) {
                  m=m+1;
                  mon=m.toString();
                  if(mon.length <2){
                      m="0"+m;
                  }
                  day=d.toString();
                  if(day.length <2){
                      d="0"+d;
                  }
                  if ($.inArray( m + '-' + d + '-' + y, disabledDays) != -1) {
                      return [false];
                  }
              }
            }
            return [true];
        }

        $(document).ready(function(){       
            //alert("here");
            var current_url = window.location.href;
            var check_menu = current_url.indexOf("#menu"); 
            //console.log("checkmenu = "+check_menu);
            if(check_menu > 0){
              $('html, body').animate({
                scrollTop: $('.deal-bottom-box').offset().top
              }, 'slow');
              $("#info_tab").removeClass("active");
              $("#menu_tab").addClass("active");
              $("#info").removeClass("in");
              $("#info").removeClass("active");
              $("#menu").addClass("in");
              $("#menu").addClass("active");
            }

            $(".a-list-group-item").on('click',function(){
              var v = $(this).attr('data-alacarte_link');
              window.location.href=v;
            });

            /*reservation strat*/
           // loadDatePicker();

            $('#locations1').change(function(){
              $('#party_edit1').trigger('click'); 
              
               loadPartySelect();
               loadDatePicker();
            });


             /*reservation over*/

             $('#time_edit5').click(function(){
              $('#save_changes').show();
              var last_reserve_date = $('last_reserv_date').val();
              var vendor_id = $('#vendor_id').val();
              var last_reserv_time = $('#last_reserv_time').val();
             $('#collapseThree5').slideToggle();
              $.ajax({
                  url: "/users/timedataload",
                  type: "post",
                  data: {
                      dateText:  last_reserve_date,
                      vendor_id: vendor_id,
                      last_reserv_time:last_reserv_time
                  },
                  success: function(e) {
                     //console.log(e);
                     $('#timeajax').html(e);
                  }
               });
              });

             

       });

       function loadPartySelect()
       {
          var location_id = $('#locations1').val();
          var jsondata = reserveminmax[location_id];
          //console.log(jsondata);
          var selectList = $("#party_size1");
          selectList.find("option:gt(0)").remove();
          
          var min_people = jsondata.min_people;
          var max_people = jsondata.max_people;
          if(parseInt(max_people)>0)
          {
            for(var j = min_people;j <max_people;)
            {
               var optiontext = (j == 1) ? ' Person' : ' People';
               selectList.append('<option value="'+j+'">'+j+optiontext+'</option>')

               j = j+ parseInt(jsondata['increment']);
            }
          }

        }

      /* function loadDatePicker() {
          $("#choose_date").datepicker("destroy");*/
         $('#date_edit12').click(function(){
          $('#save_changes').show();
          var vendor_id = $('#vendor_id').val();
          var last_reserv_time = $('#last_reserv_time').val();
          $("#choose_date").datepicker({
             dateFormat: 'yy-mm-dd',
             minDate: 'new Date()',
             beforeShowDay: disableAllTheseDays,
             onSelect: function(dateText, inst) 
             {
                    var d = $.datepicker.parseDate("yy-m-dd",  dateText);
                   
                    var datestrInNewFormat = $.datepicker.formatDate( "D", d).toLowerCase();
                    var txt = '<div class="btn-group col-lg-10 pull-right actives ">';
                    var txt2 = '';
                    var g = 1;
                    var cur_date =  new Date('<?php echo date('d M Y H:i:s'); ?>');
                    month = parseInt(cur_date.getMonth());
                    month += 1;
                    c_date = cur_date.getFullYear() + '-' + ((month<10)?'0':'')+month +  '-'  + cur_date.getDate();
                    c_time = cur_date.getHours()+":"+((cur_date.getMinutes()<10)?'0':'')+cur_date.getMinutes()+':00';
                    
                    //console.log(c_date);
                    //alert(dateText);
                    $('#last_reserv_date').val(dateText);
                      $.ajax({
                          url: "/users/timedataload",
                          type: "post",
                          data: {
                              dateText:dateText,
                              vendor_id:vendor_id,
                              last_reserv_time:last_reserv_time
                          },
                          success: function(e) {
                             //console.log(e);
                             $('#timeajax').html(e);
                          }
                       });
                    //here using ajax!!!
                    //console.log(dateText);
                    /*Time display container*/
                      /*var location_id = '97';
                      var schedule = allschedule[location_id];
                      console.log(schedule);

                      if(schedule != undefined)
                      {
                        for(key_sch in schedule[datestrInNewFormat])
                        {   
                            
                            var obj_length = Object.keys(schedule[datestrInNewFormat]).length;
                            console.log(obj_length);
                            active_tab = (g == obj_length) ? 'active' : '' ;
                            active_blck = (g == obj_length) ? '' : 'hidden' ;  
                            txt+= '<label class="btn btn-warning btn-xs time_tab ' + active_tab + '" id="'+key_sch.toLowerCase()+'">'+key_sch.toUpperCase()+'</label>';
                            txt2 +=    '<div id="' + key_sch.toLowerCase() + '_tab"  class="'+active_blck+'">';
                            for(key_sch_time in schedule[datestrInNewFormat][key_sch])
                            {
                               if(c_date == dateText)
                               {
                                 if(String(c_time) < String(schedule[datestrInNewFormat][key_sch][key_sch_time])) {
                                   txt2 += '<div class="time col-lg-3 col-xs-5" rel="' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '"><a href="javascript:">' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '</a></div>';
                                 } 
                               }  
                               else
                               {
                                 txt2 += '<div class="time col-lg-3 col-xs-5" rel="' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '"><a href="javascript:">' + schedule[datestrInNewFormat][key_sch][key_sch_time] + '</a></div>';
                               }                          
                                                       
                            }
                            txt2+= '</div>';    
                            g++;
                        }
                      }*/
                      /*Time display container*/


                   /* txt += '</div><div class="clearfix"></div>';
                    txt += '<input type="hidden" name="booking_time" id="booking_time" value="">';
                           $('#hours').html(txt2);
                           $('#time').html(txt);

                    $('#booking_date').val(dateText);*/


                    
                    $('#date_edit12 span').text(formatDate(dateText));
                    $('#date_edit12').click();
                    timehide=0;
                   // $('#time_edit1').click(); 
              }
          });
        $( "#choose_date" ).datepicker("refresh");
      /*}*/
    });

  </script>
@endsection