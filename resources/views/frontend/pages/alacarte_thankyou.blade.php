
@extends('frontend.templates.inner_pages')

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