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
<!-- <table width="600" border="0" background="#fff" style="margin: 0 auto;  border-collapse: collapse;
  border-spacing: 0;">
  
</table> -->
Quantity : {{$quantity}} <br><br>
Membership : {{$membership_number}} <br><br>
E-mail : {{$email_id}} <br><br>
Reward : {{$description}} <br><br>
Brand name: WowTables<br><br>
Points spent : {{$points_spent}} <br><br>
Gift: GPRED<?php echo sprintf("%04d",$set_giftcard_id);?>
</body>
</html>
