<?php
$dt=explode(" ",$rows[1]['booking_time']);
$date= $dt['0'];
$date_arr[1]='Jan';
$date_arr[2]='Feb';
$date_arr[3]='Mar';
$date_arr[4]='Apr';
$date_arr[5]='May';
$date_arr[6]='Jun';
$date_arr[7]='Jul';
$date_arr[8]='Aug';
$date_arr[9]='Sep';
$date_arr['01']='Jan';
$date_arr['02']='Feb';
$date_arr['03']='Mar';
$date_arr['04']='Apr';
$date_arr['05']='May';
$date_arr['06']='Jun';
$date_arr['07']='Jul';
$date_arr['08']='Aug';
$date_arr['09']='Sep';
$date_arr[10]='Oct';
$date_arr[11]='Nov';
$date_arr[12]='Dec';
$dt_arr = explode("/",$dt['0']);

$date = $date_arr[$dt_arr['0']]." ".$dt_arr['1']." ".$dt_arr['2'];
//echo 1234567890;
$mixpanel_date = date("Y-m-d",strtotime($dt['0']));
$day_of_seating = date('l',strtotime($mixpanel_date));
$time_of_seating = date("H:i:s", strtotime($dt['1']." ".$dt['2']));
$new_test_date = date('m/d/Y');

//echo "tie_of_seating = ".$time_of_seating;
//echo 1234567890;
if($_GET["type"] == "alacarte") {
    $rest_name = $restaurant[0]['venue'];
    $resv_type = "Alacarte";
    $exp_name = $rest_name." : A la carte Reservation";
}else{
    $rest_name = $experience[1]['venue'];
    $resv_type = "Experience";
    $exp_name = $rest_name." : ".$experience[1]['descriptive_title'];
}

if(isset($experience[1]['post_tax_price']) && $experience[1]['post_tax_price'] != "" && $experience[1]['post_tax_price'] !="0" && $experience[1]['post_tax_price'] != "0.00"){
    $set_post_tax_price = $experience[1]['post_tax_price'] * $rows[1]['no_of_tickets'];
} else {
    $set_post_tax_price = $experience[1]['price'] * $rows[1]['no_of_tickets'];
}
$campaign_id = ((isset($campaign_details[0]['telecampaign']) && $campaign_details[0]['telecampaign'] != "") ? $campaign_details[0]['telecampaign'] : 'Not set');
//echo "rest name = ".$rest_name;
?>
<script type="text/javascript">
    $(document).ready(function(){
        var userID = "<?php echo $rows[1]['user_id']?>";
        var emailID = "<?php echo $rows[1]['order_by_email']?>";
        var city = "<?php echo $rows[1]['city']?>";
        var rest_name = '<?php echo $rest_name;?>';
        var loc = "<?php echo $rows[1]['outlet']?>";
        var exp = "<?php echo $exp_name?>";
        var no_of_guests = "<?php echo $rows[1]['no_of_tickets']?>";
        //var date_of_seating = "<?php echo $mixpanel_date.'T'.$time_of_seating ;?>";
        var date_of_seating = "<?php echo $mixpanel_date;?>";
        var day_of_seating = "<?php echo $day_of_seating;?>";
        var time_of_seating = "<?php echo $time_of_seating;?>";
        var made_by = "User";
        var resv_type = "<?php echo $resv_type;?>";
        var total_resv = "<?php echo $user['bookings_made'];?>";
        var final_date_of_seating = date_of_seating+'T'+time_of_seating;
        var set_billing = "<?php echo $set_post_tax_price;?>";
        var set_campaign_id = "<?php echo $campaign_id;?>";
        //console.log("userID = "+userID+" , emailID = "+emailID+" , city = "+city+" , rest_name = "+rest_name+" , loc = "+loc+" , exp = "+exp+" , no_of_guests = "+no_of_guests+" , date_of_seating = "+final_date_of_seating+" , day_of_seating = "+day_of_seating+" , time_of_seating = "+time_of_seating+" , made_by = "+made_by);
        //console.log("insert if found signup");
        //console.log("userID = "+userID+" , emailID = "+emailID+" , checktype = "+checktype+" , logintype = "+loginType);

        mixpanel.track("Reservation",{"User ID":userID,"Campaign Id":set_campaign_id,"Email ID":emailID,"DB_City":city,"Restaurant":rest_name,"Location":loc,"Experience":exp,"Number of guests":no_of_guests,"Date of seating":final_date_of_seating,"Day of seating":day_of_seating,"Time of seating":time_of_seating,"Reservation Made By":made_by,"Reservation Type":resv_type,"Total reservations":total_resv,"Billing":"Rs."+set_billing+" /-"});
        mixpanel.identify(userID);
        mixpanel.people.track_charge(set_billing);
        mixpanel.people.set({"$email": emailID,"Campaign Id":set_campaign_id});
    });
</script>
<?php if($status==='failure'){?>
<div class="row">
    <p>Oops, looks like we didn't receive your payment. If you are having trouble with paying please call our concierge at 09619551387.</p>
</div>
<?php }else { ?>
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
                        <p>If you would like to modify or cancel this reservation, please visit the <a href="<?=base_url('users/myreservations')?>">My Reservations</a> page.</p>
                    </div>
                    <?php
                    if($_GET["type"] == "alacarte") {
                    $setReservationVariable = "A";
                    ?>
                    <p class="lead"><?php echo $restaurant[0]['venue']; ?>: <?php echo "A la carte Reservation";
                        } else {
                        $setReservationVariable = "E";
                        ?>
                    <p class="lead"><?php echo $experience[1]['venue']; ?>: <?php echo $experience[1]['descriptive_title'];
                        }
                        ?></p>
                    <?php if($_GET["type"] != "alacarte") { ?>
                    <p class="res-desc"><strong>Description: </strong><?php echo $experience[1]['exp_short_desc'] ?></p>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 res-details">
                            <ul>
                                <li>
                                    <p class="text-warning"><em>Date</em></p>
                                    <p><strong><?php echo $date; ?></strong></p>
                                </li>
                                <li>
                                    <p class="text-warning"><em>Time</em></p>
                                    <p><strong><?php echo $dt['1']." ".$dt['2'] ?></strong></p>
                                </li>
                                <li>
                                    <p class="text-warning"><em>Number of Guests</em></p>
                                    <p><strong><?php echo $rows[1]['no_of_tickets']; ?></strong></p>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 col-sm-6 res-details col2">
                            <ul>
                                <li>
                                    <p class="text-warning"><em><?php echo ($experience['1']['prepay']=='No'?"Receipt":"Reservation");?> ID</em></p>
                                    <p><strong><?php echo $setReservationVariable;?><?php echo sprintf("%06d",$rows[1]['id']); ?></strong></p>
                                </li>
                                <?php if($_GET["type"] != "alacarte") { ?>
                                <li>
                                    <p class="text-warning"><em>Experience</em></p>
                                    <p><strong><a href="<?php echo $base_url.$experience['1']['city'];?>/experiences/<?php echo $experience['1']['slug'];?>">View Details</a></strong></p>
                                </li>
                                <?php } ?>
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
                    <?php if($_GET["type"] == "alacarte") { ?>
                    <li class="active"><a href="#details" data-toggle="tab">

                            A la carte T&C

                        </a>
                    </li>
                    <?php } else { ?>
                    <li class="active"><a href="#details" data-toggle="tab">
                            Experience Details
                        </a>
                    </li>
                    <li><a href="#terms" data-toggle="tab">T&C</a></li>
                    <?php } ?>
                </ul>
                <?php if($_GET["type"] == "alacarte") { ?>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="details">
                        <ul>
                            <?php echo $restaurant[0]['terms_conditions'] ?>
                        </ul>
                    </div>
                </div>
                <?php } else {?>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="details">
                        <ul>
                            <?php echo $experience[1]['offer_details'] ?>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="terms">
                        <ul>
                            <?php echo $experience[1]['terms_conditions'] ?>
                        </ul>
                    </div>
                </div>
                <?php } ?>

            </div>
            <img style="position:absolute; visibility:hidden" src="http://www.ref-r.com/campaign/t1/settings?bid_e=D604D097C8F8B0C4D3A75B7D7F4772E0&bid=4944&t=420&orderID=<?php echo $setReservationVariable.''.sprintf("%06d",$rows[1]['id']); ?>&purchaseValue=<?php echo $rows[1]['ord_amount']; ?>&email=<?php echo $rows['1']['order_by_email']; ?>" />
        </div>

        <div class="col-md-4 col-sm-4 thankyou-aside">
            <div class="widget map">
                <p style="text-transform: uppercase;"><strong><?php echo $rows[1]['city']; ?></strong></p>
                <p><?php echo $rows[1]['order_by_address']; ?></p>
                <div id="map-canvas" style="height:200px">

                    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsEnNgLLhw0AFS4JfwsE1d3oTOeaWcccU&sensor=true"></script>
                    <script type="text/javascript">
                        <?php if($_GET["type"] == "alacarte") { ?>
                        var dealer_lat = '<?php echo $restaurant_addr['latitude']?>';
                        var dealer_lng = '<?php echo $restaurant_addr['longitude']?>';
                        <?php } else { ?>
                        var dealer_lat = '<?php echo $experience_addr['lattitude']?>';
                        var dealer_lng = '<?php echo $experience_addr['longitude']?>';
                        <?php } ?>
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
                            <div class="fb-like" data-href="<?php echo $base_url.$experience['1']['city'];?>/experiences/<?php echo  $experience['1']['slug'];?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true"></div>
                        </div>
                    </li>
                    <li style="margin-left: 4px; margin-right: 4px;">
                        <div class='social-button' id='twitter-social-button'>
                            <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $base_url.$experience['1']['city'];?>/experiences/<?php echo  $experience['1']['slug'];?>" data-text="Gourmetitup" data-via="GourmetItUp" data-count="none">Tweet</a>
                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                        </div></li>
                    <li>
                        <div class='social-button' id='mail-social-button'>
                            <a href="mailto:"><img src="<?php echo base_url(); ?>/images/email.png" width="58" height="20" alt=""></a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>


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
                        <?php if($_GET["type"] == "alacarte") { ?>
                        <input type="hidden" name="restaurantid" value="<?php echo $restaurant[0]['id']; ?>">
                        <?php } ?>
                        <input type="hidden" name="reserv_type" value="<?php echo $_GET["type"] ?>" id="reserv_type">
                        <input type="hidden" name='reservid' value='<?php echo $rows[1]['id']; ?>' id='reservation_id'>
                        <input type="hidden" name='userid' value="<?php echo $rows[1]['user_id']; ?>" id="customer_id">
                        <input type="hidden" name="experienceid" value="<?php echo $experience[1]['id']; ?>">
                        <input type="hidden" name='user_email' value="<?php echo $rows['1']['order_by_email']; ?>" id="customer_email">
                        <input type="hidden" name='full_name' value="<?php echo $rows['1']['order_by_name']; ?>" id="customer_name">
                        <input type="hidden" name="slug" value="<?php echo $experience[1]['slug']; ?>" id="exp_slug">
                        <input type="hidden" name="exp_title" value="<?php echo $experience[1]['exp_title']; ?>" id="exp_title">
                        <input type="hidden" name='city' value="<?php echo $rows['1']['city']; ?>" id="city">
                        <input type="hidden" name="restaurant" value="<?php echo $experience[1]['venue']; ?>" id="restaurant">
                        <input type="hidden" name='number_guests' value="<?php echo $rows[1]['no_of_tickets']; ?>" id="number_guests">
                        <input type="hidden" name='total_bill' value="<?php echo $rows[1]['ord_amount']; ?>" id="total_bill">
                        <input type="hidden" name='date_reservation' value="<?php echo $rows[1]['order_date']; ?>" id="date_reservation">
                        <input type="hidden" name='date_seating' value="<?php echo $rows[1]['booking_time']; ?>" id="date_seating">
                        <input type="hidden" name='url_product' value="<?php echo $base_url.$experience['1']['city'];?>/experiences/<?php echo $experience['1']['slug'];?>" id="url_product">

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
</div><!-- /.modal -->
<?php }  ?>
<script type="text/javascript">
    kk=0;
    var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
</script>
<script type="text/javascript">
    var gaJsHost = (("https:" == document.location.protocol ) ? "https://ssl." : "http://www.");
    document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
    try{
        var pageTracker = _gat._getTracker("UA-1717133-12");
        pageTracker._trackPageview();
        pageTracker._addTrans(
                "<?php echo $setReservationVariable;?><?php echo sprintf('%06d',$rows[1]['id']); ?>", // transaction ID - required
                "WowTables",                            // affiliation or store name
                "<?php echo $rows[1]['ord_amount'] ?>",   // total - required
                "0",                                      // tax
                "0",                                      // shipping
                "<?php echo $experience[1]['city']; ?>",  // city
                "<?php echo $experience[1]['city']; ?>",  // state or province
                "India"                                   // country
        );


        // add item might be called for every item in the shopping cart
        // where your ecommerce engine loops through each item in the cart and
        // prints out _addItem for each
        pageTracker._addItem(
                "<?php echo $setReservationVariable;?><?php echo sprintf('%06d',$rows[1]['id']); ?>", // transaction ID - necessary to associate item with transaction
                "E<?php echo $experience[1]['id']; ?>",               // SKU/code - required
                "<?php echo $experience[1]['city'].' - '.$experience[1]['venue'].' - '.$experience[1]['descriptive_title']; ?>",    // product name
                "Tickets",                                            // category or variation
                "<?php echo $experience[1]['price']; ?>",             // unit price - required
                "<?php echo $rows[1]['no_of_tickets']; ?>"            // quantity - required
        );

        pageTracker._trackTrans();                       //submits transaction to the Analytics servers
    } catch(err) {}

    /*$(document).ready(function(){
     var order_id = "<?php echo $setReservationVariable.''.sprintf("%06d",$rows[1]['id']); ?>";
     var purchase_value = "<?php echo $rows[1]['ord_amount']; ?>";
     var email = "<?php echo $rows['1']['order_by_email']; ?>";

     $.ajax({
     url: "http://www.ref-r.com/campaign/t1/settings?bid_e=D604D097C8F8B0C4D3A75B7D7F4772E0&bid=4944&t=420&orderID="+order_id+"&purchaseValue="+purchase_value+"&email="+email,
     });
     });*/
</script>
<?php $this->load->view('conversiontracking');?>
