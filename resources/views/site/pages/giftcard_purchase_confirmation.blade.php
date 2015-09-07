<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>



<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Giftcard Purchase confirmation</title>

</head>

<body>
<table width="600" border="0" background="#fff" style="margin: 0 auto;  border-collapse: collapse;border-spacing: 0;">
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
                <!--COntent-->
                <tr>
                    <td class="content" style="padding:0 30px 0 35px;">

                        <table width="535" border="0">

                            <tr>
                                <td class="top-content" style="padding:11px 161px 9px 186px; height:33px; width:188px; color:#fff; font-size:17px; letter-spacing:2px; line-height:33px;">
                                    <table width="186" border="0">
                                        <tr>
                                            <td class="inner" style="background:#fa7b47; height:33px; width:178px; padding-left:10px; -moz-box-shadow: 0px 1px 1px #848182; -webkit-box-shadow: 0px 1px 1px #848182; box-shadow: 0px 1px 1px #848182;">Receipt No.<?php echo $giftcard['order_id'];?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="58" class="banner-content" style=" float:left; padding:8px 36px 8px 34px; height:54px; width:465px; color:#000; font-size:12px; letter-spacing:1px; text-align:center; background:url('/images/banner-bg1.png') center top no-repeat;">

                                    <div style="margin: -1px 0;font-size: 11px;"> Thank you for purchasing a WowTables Gift Card. Our concierge will contact you once your gift card has been dispatched to the gift card receiver.If you have any questions please contact us at 09619551387 or email us at concierge@wowtables.com.</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="heading-content" style="float:left; margin-top:10px; height:25px; width:535px; background:#000; line-height:25px; color:#fff; font-weight:bold; font-size:16px; letter-spacing:2px; text-align:center; -moz-box-shadow: 0px 1px 1px #848182; -webkit-box-shadow: 0px 1px 1px #848182; box-shadow: 0px 1px 1px #848182; ">
                                    Please review your purchase details below
                                </td>
                            </tr>
                            <tr>
                                <td class="title-content" style=" float:left; padding:5px 57px 10px 70px; width:408px; background:#ebebeb; color:#fff; font-size:14px; font-family:Times New Roman, Times, serif; letter-spacing:1px; -moz-box-shadow: 0px 1px 1px #848182; -webkit-box-shadow: 0px 1px 1px #848182; box-shadow: 0px 1px 1px #848182; position:relative; z-index:9;">
                                    <div style="margin: 5px 0; color: #000">Purchaser Name: <?php echo $giftcard['guestName'];?></div>
                                    <div style="margin: 5px 0; color: #000">Purchaser Email: <?php echo $giftcard['guestEmail'];?></div>
                                    <div style="margin: 5px 0; color: #000">Purchaser Phone Number: <?php echo $giftcard['phone'];?></div>
                                    <div style="margin: 5px 0; color: #000"> Gift Card Receiver: <?php echo $giftcard['receiverName'];?></div>
                                    <div style="margin: 5px 0; color: #000">Gift Card Receiver Email: <?php echo $giftcard['receiverEmail'];?></div>
                                    <div style="margin: 5px 0; color: #000">Gift Card Type: <?php echo ($giftcard['giftcardType'] == 1)?" Cash":" Experience";?></div>
                                    <div style="margin: 5px 0; color: #000">Total Gift Card Value: Rs. <?php echo number_format($giftcard['total_amount'], 2);?></div>
                                    <?php if($giftcard['giftcardType'] == 2):?>
                                        <div style="margin: 5px 0; color: #000">Experience: <?php echo $giftcard['experienceName'];?></div>
                                        <div style="margin: 5px 0; color: #000">Guests: <?php echo $giftcard['noOfGuests'];?></div>
                                        <?php if($giftcard['addonsDetail'] != ""):?>
                                            <div style="margin: 5px 0; color: #000">Addons: <?php echo $giftcard['addonsDetail'];?></div>
                                        <?php endif ?>
                                    <?php endif ?>
                                    <div style="margin: 5px 0; color: #000">Delivery Type: <?php echo ($giftcard['sendingType'] == "email")?' Email':' Mail';?></div>
                                    <?php if($giftcard['sendingType'] == "mail"):?>
                                    <div style="margin: 5px 0; color: #000">Mailing Address: <?php echo $giftcard['mailingAddress'];?></div>
                                    <?php endif ?>
                                    <?php if($giftcard['specialInstructions'] != 0):?>
                                    <div style="margin: 5px 0; color: #000">Special Instructions: <?php echo $giftcard['specialInstructions']?></div>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <!--Info-->

                            <!--Info-->

                        </table>
                    </td>
                </tr>
            </table>

</body>
</html>