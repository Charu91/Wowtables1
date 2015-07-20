<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>



<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Payment</title>
    <style type="text/css">
        .ReadMsgBody {width: 100%; background-color: #ffffff;}
        .ExternalClass {width: 100%; background-color: #ffffff;}
        body     {width: 100%; background-color: #ffffff; margin:0; padding:0; -webkit-font-smoothing: antialiased;font-family: Georgia, Times, serif}
        table {border-collapse: collapse;}

        @media only screen and (max-width: 640px)  {
            body[yahoo] .deviceWidth {width:440px!important; padding:0;}
            body[yahoo] .center {text-align: center!important;}
        }

        @media only screen and (max-width: 479px) {
            body[yahoo] .deviceWidth {width:280px!important; padding:0;}
            body[yahoo] .center {text-align: center!important;}
        }

    </style>
</head>

<body>
<table width="600" border="0" background="#fff" style="margin: 0 auto;  border-collapse: collapse;
  border-spacing: 0;
">
    <tr>
        <td>


            <table width="600" border="0">

                <tr >
                    <td class="top" style="height:60px; padding:0 30px 0 35px;">
                        <table width="535" border="0">
                            <tr>
                                <td width="186"><a href="#"><img src="http://wowtables.com/assets/img/Wowtables_logo.png" width="225" height="49" alt="wowtables" style="margin-bottom:-46px;"></a></td>
                                <td class="right-top" style="width:15%;height:46px; letter-spacing:1px; color:#fff; font-size:7px; text-transform:uppercase; text-align:right;">
                                    follow&nbsp;us
                                    <a href="https://twitter.com/Wow_Tables">
                                        <img style="float:left;  padding-top:4px; margin-left: 25px;" src="http://wowtables.com/images/twitter.png" width="23" height="23" alt="twitter">
                                    </a>
                                    <a href="https://www.facebook.com/WowTables">
                                        <img  style="float:left;  padding-top:4px; margin-left: 5px;" src="http://wowtables.com/images/facebook.png" width="23" height="23" alt="facebook">
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php
                if($post_data['reservation_type']=='event'){
                    $your_booking_order = "Your Booking";
                    $order_bookingno = $order_id;
                    $payment_string = '<div style="margin: 5px 0; color: #000">Please pay directly at the venue.</div>';
                }
                else{
                    $your_booking_order = "Your Order Receipt";
                    $order_bookingno = $post_data['reservationID'];
                    $payment_string = '';
                }
                $exp_venue = $outlet->vendor_name;
                $exp_address= $location_details->address;
                $terms_conditions= $productDetails['attributes']['terms_and_conditions'];
                $exp_includes= $productDetails['attributes']['experience_includes'];
                $exp_short_desc= $productDetails['attributes']['short_description'];
                $guest = $post_data['partySize'];

                $date = date('d-F-Y',strtotime($post_data['reservationDate']));
                $day_of_week = date('l',strtotime($post_data['reservationDate']));

                $time = date('g:i a',strtotime($post_data['reservationTime']));
                ?>

                <!--COntent-->
                <tr>
                    <td class="content" style="padding:0 30px 0 35px;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin-left: 5px;font-size:9pt !important;text-align: center;">Please note that these are your new reservation details.</p>
                    </td>
                </tr>
                <tr>
                    <td height="59" class="banner-content" style=" float:left; padding:0px 34px 0px 32px;height:auto;width:467px; color:#000; letter-spacing:1px; text-align:center; line-height: 14px;">
                        <p style="margin-top: 8px; margin-left: 5px;font-size:9pt!important;">We have received your reservation request for <?php echo $date;?> at <?php echo $time?>.</p>
                        <p style="font-size:9pt!important;margin-top: -5px;">Our concierge desk will send you an email as well as an SMS once your reservation has been accepted by the restaurant/venue.</p>
                        <p style="margin-top: 7px ;font-size:8pt!important;">Note: Most reservations are confirmed within 15 minutes but might take longer if the restaurant is unavailable at this time.</p>
                    </td>
                </tr>

                <?php if(isset($post_data['admin_email']) && $post_data['admin_email'] == 1){ ?>
                <tr>
                    <td class="heading-content" style="float:left; margin-top:5px; height:auto; width:535px; background:#000; line-height:25px; color:#fff; font-weight:bold; font-size:16px; letter-spacing:2px; text-align:center; -moz-box-shadow: 0px 1px 1px #848182; -webkit-box-shadow: 0px 1px 1px #848182; box-shadow: 0px 1px 1px #848182; ">Reservation Changes</td>
                </tr>
                <tr>
                    <td class="title-content" style=" float:left; padding:5px 57px 10px 70px; width:408px; background:#eab703; color:#fff; font-size:14px; font-style:italic; font-family:'Times New Roman', Times, serif; letter-spacing:1px; -moz-box-shadow: 0px 1px 1px #848182; -webkit-box-shadow: 0px 1px 1px #848182; box-shadow: 0px 1px 1px #848182; position:relative; z-index:9;">
                        <?php if(isset($post_data['final_reservation_oulet']) && $post_data['final_reservation_oulet'] != ""){ ?>
                            <div style="margin: 5px 0; color: #000"><?php echo $post_data['final_reservation_oulet'];?></div>
                        <?php } ?>
                        <?php if(isset($post_data['final_reservation_party_size']) && $post_data['final_reservation_party_size'] != ""){ ?>
                            <div style="margin: 5px 0; color: #000"><?php echo $post_data['final_reservation_party_size'];?></div>
                        <?php } ?>
                        <?php if(isset($post_data['final_reservation_date']) && $post_data['final_reservation_date'] != ""){ ?>
                            <div style="margin: 5px 0; color: #000"><?php echo $post_data['final_reservation_date'];?></div>
                        <?php } ?>
                        <?php if(isset($post_data['final_reservation_time']) && $post_data['final_reservation_time'] != ""){ ?>
                            <div style="margin: 5px 0; color: #000"><?php echo $post_data['final_reservation_time'];?></div>
                        <?php } ?>
                            <?php if(isset($post_data['final_giftcard_id']) && $post_data['final_giftcard_id'] != ""){ ?>
                            <div style="margin: 5px 0; color: #000"><?php echo $post_data['final_giftcard_id'];?></div>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>

                <tr>
                    <td class="heading-content" style="float:left; margin-top:10px; height:25px; width:535px; background:#000; line-height:25px; color:#fff; font-weight:bold; font-size:16px; letter-spacing:2px; text-align:center; -moz-box-shadow: 0px 1px 1px #848182; -webkit-box-shadow: 0px 1px 1px #848182; box-shadow: 0px 1px 1px #848182; ">EXPERIENCE (Reservation ID: E<?php echo sprintf("%06d",$order_bookingno);?>)</td>
                </tr>

                <tr>
                    <td class="title-content" style='float:left; padding:5px 57px 10px 70px; width:408px; background:#eab703; color:#fff; font-size:14px; font-style:italic; font-family:Times New Roman, Times, serif; letter-spacing:1px; -moz-box-shadow: 0px 1px 1px #848182; -webkit-box-shadow: 0px 1px 1px #848182; box-shadow: 0px 1px 1px #848182; position:relative; z-index:9;'>

                        <div style="margin: 5px 0; color: #000"><?php echo $exp_short_desc;?></div>
                        <?php
                        $special = trim($post_data['addons_special_request']);
                        if(!empty($special)): ?>
                        <div style="margin: 5px 0; color: #000">Other Info: <?php echo $special;?></div>
                        <?php endif; ?>
                        <?php
                        $giftcardID = trim($post_data['giftcard_id']);
                        if(!empty($giftcardID) && ($giftcardID != '0' || $giftcardID != 0)): ?>
                        <div style="margin: 5px 0; color: #000">Gift Card ID: <?php echo $giftcardID;?></div>
                        <?php endif; ?>
                        <div style="margin: 5px 0; color: #000">Venue: <?php echo $exp_venue;?></div>
                        <div style="margin: 5px 0; color: #000">Address: <?php echo $exp_address;?></div>


                    </td>
                </tr>


                <!--Info-->
                <tr>
                    <td class="info" style=" float:left; padding:0 0 20px 19px; width:516px; background:#ebebeb; -moz-box-shadow: 0px 1px 1px #848182; -webkit-box-shadow: 0px 1px 1px #848182; box-shadow: 0px 1px 1px #848182; margin-bottom:9px;">

                        <table width="516" border="0">


                            <tr>
                                <td class="top-info" style="float:right; padding-left: 7px; width:119px; height:33px; line-height:33px; background:#fa7b47; -moz-box-shadow: 0px 1px 1px #848182; -webkit-box-shadow: 0px 1px 1px #848182; box-shadow: 0px 1px 1px #848182; font-family:Arial, Helvetica, sans-serif; color:#fff; font-size:14px; font-weight:bold; position:relative; z-index:99;">GUESTS:<?php echo $post_data['partySize'];?></td>
                            </tr>

                            <tr>
                                <td>
                                    <table width="512" border="0" class="bottom-info" style="float:left; padding-bottom:13px; color:#000; font-size:14px;">
                                        <tr>

                                            <td class="left-clmn" style="float:left; text-align:right; width:109px;">Name:</td>
                                            <td><?php echo $post_data['guestName'];?></td>
                                        </tr>
                                        <tr>
                                            <td class="left-clmn" style="float:left; text-align:right; width:109px;">Phone&nbsp;/&nbsp;Email:&nbsp;</td>
                                            <td><?php echo $post_data['guestPhoneNo']."&nbsp;/&nbsp;".$post_data['guestEmail'];?></td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>

                        </table>


                    </td>
                </tr>
                <!--Info-->

                <tr style="width:535px; float:left;">
                    <td class="bottom-heading" style="float:left; width:133px; height:17px; text-align:center; line-height:17px; text-transform:uppercase; background:#000; -moz-box-shadow: 0px 1px 1px #848182; -webkit-box-shadow: 0px 1px 1px #848182; box-shadow: 0px 1px 1px #848182; color:#fff; font-size:12px; position:relative; z-index:9;">This Experience Includes</td>
                </tr>



                <tr>
                    <td class="details-info" style=" float:left; width:494px; padding:7px 20px 9px 21px; background:#ebebeb; -moz-box-shadow: 0px 1px 1px #848182; -webkit-box-shadow: 0px 1px 1px #848182; box-shadow: 0px 1px 1px #848182; color:#000; font-size:12px; margin-bottom:9px;"><?php echo $exp_includes;?>
                    </td>
                </tr>


                <tr style="width:535px; float:left;">
                    <td class="bottom-heading"  style="float:left; width:133px; height:17px; text-align:center; line-height:17px; text-transform:uppercase; background:#000; -moz-box-shadow: 0px 1px 1px #848182; -webkit-box-shadow: 0px 1px 1px #848182; box-shadow: 0px 1px 1px #848182; color:#fff; font-size:12px; position:relative; z-index:9;">TERMS & CONDITIONS</td>
                </tr>



                <tr>
                    <td class="details-info" STYLE=" float:left; width:494px; padding:7px 20px 9px 21px; background:#ebebeb; -moz-box-shadow: 0px 1px 1px #848182; -webkit-box-shadow: 0px 1px 1px #848182; box-shadow: 0px 1px 1px #848182; color:#000; font-size:12px; margin-bottom:9px;"><?php echo $terms_conditions;?>


                    </td>
                </tr>

            </table>


        </td>
    </tr>
    <!--COntent-->

    <tr>
        <td style=" text-align: center;" class="footer" style="background:#000000;  height:21px; margin-top:4px; float:left; width:600px;color: white;font-style:bold">
            Please call 9619551387 for any assistance.
        </td>
    </tr>


</table>
</td>
</tr>
</table>

</body>
</html>
