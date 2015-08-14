@extends('frontend.templates.details_pages')

@section('content')


    <div class="container thankyou-page">
        <div class="row">

            <div class="col-md-8 col-sm-8 thankyou-main">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span class="lead">Thank you for your Reservation</span>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-info">
                            <strong>Next Steps:</strong> Our concierge desk will send you an email as well as an SMS once your reservation has been accepted by the restaurant/venue.
                            <p>Most reservations are confirmed within 15 minutes but might take longer if the restaurant is unavailable at this time.</p>
                            <p>If you would like to modify or cancel this reservation, please visit the <a href="URL::to('/users/myreservations')">My Reservations</a> page.</p>
                        </div>

                        <p class="lead">
                            {{$result['restaurant_name']}}: A la carte Reservation
                        </p>


                        <div class="row">
                            <div class="col-md-6 col-sm-6 res-details">
                                <ul>
                                    <li>
                                        <p class="text-warning"><em>Date</em></p>
                                        <p><strong>{{ date('F d Y',strtotime($result['reservation_date']))}}</strong></p>
                                    </li>
                                    <li>
                                        <p class="text-warning"><em>Time</em></p>
                                        <p><strong>{{date("G:ia", strtotime($result['reservation_time']))}}</strong></p>
                                    </li>
                                    <li>
                                        <p class="text-warning"><em>Number of Guests</em></p>
                                        <p><strong>{{$result['guests']}}</strong></p>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 col-sm-6 res-details col2">
                                <ul>
                                    <li>
                                        <p class="text-warning"><em>Receipt ID</em></p>
                                        <p><strong>A{{$result['order_id']}}</strong></p>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="sendparty">
                        <p class="text-center">
                            <a type="button" id="send_reservation" data-target="#shareModal" data-toggle="modal" href="javscript:void(0)" class="btn btn-warning">SEND RESERVATION DETAILS TO PARTY</a>
                        </p>
                    </div>
                </div>

                <div class="thankyou-tab">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs">

                        <li class="active"><a href="#details" data-toggle="tab">
                                T&C
                            </a>
                        </li>

                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="details">
                            <ul>
                                {{$result['terms_and_conditions']}}
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-4 col-sm-4 thankyou-aside">
                <div class="widget map">
                    <p style="text-transform: uppercase;"><strong>{{$result['city']}}</strong></p>
                    <p>{{$result['address']}}</p>
                    <div id="map-canvas" style="height:200px">

                        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsEnNgLLhw0AFS4JfwsE1d3oTOeaWcccU&sensor=true"></script>
                        <script type="text/javascript">

                            var dealer_lat = '{{$result['lat']}}';
                            var dealer_lng = '{{$result['long']}}';

                            function initialize() {
                                var  mylatlng =  new google.maps.LatLng(dealer_lat, dealer_lng);
                                var mapOptions = {
                                    center: mylatlng,
                                    zoom: 10
                                };
                                var map = new google.maps.Map(document.getElementById("map-canvas"),
                                        mapOptions);

                                var marker = new google.maps.Marker({
                                    position: mylatlng,
                                    map: map
                                });
                            }
                            google.maps.event.addDomListener(window, 'load', initialize);
                        </script>
                    </div>
                </div>
                <div class="query-contact widget">
                    <p>Got a question? <br/> Call our Concierge at 9619551387</p>
                </div>
                <div class="widget social-box">
                    <ul class="clearfix" style="list-style: none; display: flex;">
                        <li>
                            <div class='social-button' id='facebook-social-button'>
                                <div id="fb-root"></div>
                                <script>
                                    (function(d, s, id) {
                                        var js, fjs = d.getElementsByTagName(s)[0];
                                        if (d.getElementById(id)) return;
                                        js = d.createElement(s); js.id = id;
                                        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
                                        fjs.parentNode.insertBefore(js, fjs);
                                    }(document, 'script', 'facebook-jssdk'));
                                </script>

                            </div>
                        </li>
                        <li style="margin-left: 4px; margin-right: 4px;">
                            <div class='social-button' id='twitter-social-button'>

                                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                            </div></li>
                        <li>
                            <div class='social-button' id='mail-social-button'>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
    <img style="position:absolute; visibility:hidden" src="http://www.ref-r.com/campaign/t1/settings?bid_e=D604D097C8F8B0C4D3A75B7D7F4772E0&bid=4944&t=420&orderID=<?php echo "A".$result['order_id']; ?>&email=<?php echo $result['guestEmail']; ?>" />
    <!--Share Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title text-center" id="myModalLabel">Share Reservation Details</h4>
                </div>
                <div class="modal-body" style="min-height: 110px;">
                    <div id="email_form">
                        <form>
                            <div class="form-group">
                                <label for="">Add Email Addresses</label>
                                <textarea class="form-control" rows="3" id='guest_emails'></textarea>
                                <div class="row">
                                    <div class="col-xs-6"><small>seperate with commas (,)</small></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 error hidden" id="error_email"><small>Please enter a valid email.</small></div>
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
                            <input type="hidden" name='reserv_type' value='alacarte' id='reserv_type'>
                            <input type="hidden" name='reservid' value='A{{$result['order_id']}}' id='reservid'>
                            <input type="hidden" name='userid' value='<?php echo Session::get('id');?>' id='userid'>
                            <input type="hidden" name="vendorLocationID" value="{{$result['vendorLocationID']}}">
                            <input type="hidden" name="restaurantID" value="{{$result['restaurantID']}}">
                            <input type="hidden" name='user_email' value="{{$result['guestEmail']}}" id="customer_email">
                            <input type="hidden" name='full_name' value="{{$result['guestName']}}" id="customer_name">
                            <input type="hidden" name="restaurant" value="{{$result['restaurant_name']}}" id="restaurant">
                            <input type="hidden" name='number_guests' value="{{$result['guests']}}" id="number_guests">
                            <input type="hidden" name='date_reservation' value="{{ date('m/d/Y',strtotime($result['reservation_date']))}}" id="date_reservation">
                            <input type="hidden" name='date_seating' value="{{date("g:i A", strtotime($result['reservation_time']))}}" id="date_seating">
                            <input type="hidden" name='url_product' value="<?=URL::to('/');?>/{{$result['city']}}/alacarte/{{$result['slug']}}" id="url_product">
                            <input type="hidden" name='outlet_name' value="{{$result['outlet_name']}}" id="outlet_name">

                            <button type="submit" class="btn btn-warning btn-block" id="thank_details">Share Details</button>
                        </form>
                    </div>
                    <div id="email_sent_confirmation" class="hidden">
                        <div class="col-xs-12 reservation-msg">
                            <p>Your message has been sent</p>
                    <span style="padding: 10px">
                    <button type="button" class="btn btn-warning btn-block" data-dismiss="modal" aria-hidden="true">Close This</button>
                    </span>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    <script type="text/javascript">
        var gaJsHost = (("https:" == document.location.protocol ) ? "https://ssl." : "http://www.");
        document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
        try{
            var pageTracker = _gat._getTracker("UA-1717133-12");
            pageTracker._trackPageview();
            pageTracker._addTrans(
                    "<?php echo 'A'.$result['order_id'];?>", // transaction ID - required
                    "WowTables",                            // affiliation or store name
                    "0",   // total - required
                    "0",                                      // tax
                    "0",                                      // shipping
                    "<?php echo $result['city']; ?>",  // city
                    "<?php echo $result['city']; ?>",  // state or province
                    "India"                                   // country
            );


            // add item might be called for every item in the shopping cart
            // where your ecommerce engine loops through each item in the cart and
            // prints out _addItem for each
            pageTracker._addItem(
                    "<?php echo 'A'.$result['order_id'];?>", // transaction ID - necessary to associate item with transaction
                    "<?php echo 'A'.$result['order_id']; ?>",               // SKU/code - required
                    "<?php echo $result['city'].' - '.$result['restaurant_name'].' - A la carte Reservation'; ?>",    // product name
                    "Tickets",                                            // category or variation
                    "0",             // unit price - required
                    "<?php echo $result['guests']; ?>"            // quantity - required
            );

            pageTracker._trackTrans();                       //submits transaction to the Analytics servers
        } catch(err) {}

        /*$(document).ready(function(){
         var order_id = "<?php //echo $setReservationVariable.''.sprintf("%06d",$rows[1]['id']); ?>";
         var purchase_value = "<?php //echo $rows[1]['ord_amount']; ?>";
         var email = "<?php //echo $rows['1']['order_by_email']; ?>";

         $.ajax({
         url: "http://www.ref-r.com/campaign/t1/settings?bid_e=D604D097C8F8B0C4D3A75B7D7F4772E0&bid=4944&t=420&orderID="+order_id+"&purchaseValue="+purchase_value+"&email="+email,
         });
         });*/
    </script>

 <?php
    $arrdata = DB::table('codes')->where('view_pages', 'thankyou')
      ->select('code')
      ->get();
      foreach ($arrdata as $value) 
      {
        echo $value->code;
      }
    ?>
@endsection