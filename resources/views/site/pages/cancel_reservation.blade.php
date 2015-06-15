<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Payment Cancel</title>
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
                                <td class="mid-top" style=" padding-top:44px; width:283px; height:22px; letter-spacing:2px; color:#eab703; font-size:12px; font-weight:bold; text-transform:uppercase;"><?php echo $post_data['order_id'];?></td>
                                <td class="right-top" style="width:15%;height:46px; letter-spacing:1px; color:#fff; font-size:7px; text-transform:uppercase; text-align:right;">
                                    follow&nbspus
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


                <!--COntent-->
                <tr>
                    <td class="content" style="padding:0 30px 0 35px;">

                        <table width="535" border="0">
                            <tr>
                                </td>
                            </tr>

                            <tr>
                                <td class="heading-content" style="float:left; margin-top:10px; height:25px; width:535px; background:#000; line-height:25px; color:#fff; font-weight:bold; font-size:16px; letter-spacing:2px; text-align:center; -moz-box-shadow: 0px 1px 1px #848182; -webkit-box-shadow: 0px 1px 1px #848182; box-shadow: 0px 1px 1px #848182; ">CANCEL RESERVATION (Reservation ID: <?php echo $post_data['order_id'];?>)</td>
                            </tr>

                            <tr>
                                <td class="title-content" style='float:left; padding:5px 57px 10px 70px; width:408px; background:#eab703; color:#fff; font-size:14px; font-style:italic; font-family:Times New Roman, Times, serif; letter-spacing:1px; -moz-box-shadow: 0px 1px 1px #848182; -webkit-box-shadow: 0px 1px 1px #848182; box-shadow: 0px 1px 1px #848182; position:relative; z-index:9;'>

                                    <div style="margin: 5px;color:#000">
                                        Dear <?php
                                         echo $post_data['guestName'];
                                        ?>,
                                    </div>
                                    <div style="margin: 5px;color:#000">
                                        Your WowTables experience reservation at <?=$post_data['venue'];?> for <?php echo $post_data['partySize'];?> guests on <?php echo $date = date('d-F-Y',strtotime($post_data['reservationDate']));; ?> at <?php echo date('g:i a',strtotime($post_data['reservationTime']));; ?> has been cancelled.
                                    </div>
                                    <div style="margin: 5px;color:#000">
                                        Thank you for taking the time to make an advance cancellation. This helps free up your table for other guests and avoids wasted food.
                                    </div>

                                </td>
                            </tr>


                            <!--Info-->
                            <tr>
                                <td class="info" style=" float:left; padding:0 0 20px 19px; width:516px; background:#ebebeb; -moz-box-shadow: 0px 1px 1px #848182; -webkit-box-shadow: 0px 1px 1px #848182; box-shadow: 0px 1px 1px #848182; margin-bottom:9px;">


                                </td>


                        </table>


                    </td>
                </tr>
                <!--COntent-->

                <tr>
                    <td style=" text-align: center;" class="footer" style="background:#000000;  height:60px; margin-top:4px; float:left; width:600px;color: white;font-style:bold">
                        <div style="margin: 5px;">
                            Regards,<br>WowTables Concierge<br>09619551387
                        </div>

                    </td>
                </tr>


            </table>
        </td>
    </tr>
</table>

</body>
</html>
