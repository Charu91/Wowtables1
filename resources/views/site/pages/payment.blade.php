<?php
//$mode = 'TEST';

//$url=  "http://boot.gourmetitup.com/response";
//$url="http://dev.buzzr.in/gourmet_project/response.php?DR={DR}";
//$url="http://wowtables.app/payment/process_response";
//$url="http://dev.wowtables.com/payment/process_response";
$url="http://wowtables.com/payment/process_response";

//$MerchantID='C0Dr8m';

//$MerchantID = "znGwBI";
//live merchant id
$MerchantID="Jvcsd3";
//$account_id=5880;
//$salt="3sf0jURk";
//$salt = "rfgutw7f";
//live salt id
$salt = "U09bo7dX";
//$secret="ebskey";

//$name_arr = explode(" ",$_POST['name']);
$name_arr = explode(" ",$cookie_array['guestName']);


//$hash = "ceee9798dbb27bb66668c564854dce75|9661|".$_POST['amount']."|".$order_id."|http://gourmetitup.com/response.php?DR={DR}|".$mode;
//$hash = "ceee9798dbb27bb66668c564854dce75|9661|".$_POST['amount']."|".$order_id."|http://dev.buzzr.in/gourmet_project/response.php?DR={DR}|".$mode;

$order_id = $cookie_array['order_id'] ;
$email = $cookie_array['guestEmail'];
$amount = $cookie_array['total_amount'];
$description = $cookie_array['addons_special_request'];
$phone = $cookie_array['phone'];

$hash = $MerchantID."|".$order_id."|".$amount."|".$description."|".$name_arr['0']."|".$email."|||||||||||".$salt;
//$hash = $MerchantID."|".$order_id."|".$_POST['amount']."|".$_POST['description']."|".$name_arr['0']."|".$_POST['email']."|||||||||||".$salt;

//echo "hash code ".$hash;
//out($hash);die;
$secure_hash  = hash("sha512", $hash);

/*
Web Service URL:  https:/info.payu.in/merchant/postservice
Test Server:
 Web Service URL:  https://test.payu.in/merchant/postservice
*/
?>
<?php //echo  $order_id;
//$action_url = "https://test.payu.in/_payment";
$action_url = "https://secure.payu.in/_payment";

?>
<br>
<form  method="post" action="<?php echo $action_url;?>" name="frmTransaction" id="frmTransaction" onSubmit="return validate()">

    <input name="key" type="hidden" value="<?php echo $MerchantID;?>">
    <input name="txnid" type="hidden" value="<?php echo $order_id;?>" />
    <input name="amount" type="hidden" value="<?php echo $amount?>"/>
    <input name="productinfo" type="hidden" value="<?php echo $description ?>" />
    <input name="Firstname" type="hidden" maxlength="255" value="<?php echo $name_arr['0'] ?>" />
    <?php if(isset($name_arr['1']) && $name_arr['1']!=''){ ?>
    <input name="Lastname" type="hidden"  value="<?php echo $name_arr['1'] ?>" />
    <?php }

    ?>

    <input name="email" type="hidden"  value="<?php echo $email?>" />
    <input name="phone" type="hidden" value="<?php echo $phone ?>" />
    <!--<input name="surl" type="hidden"  value="http://wowtables.app/payment/process_response" />
    <input name="furl" type="text"  value="http://wowtables.app/payment/process_response" />-->

     <input name="surl" type="hidden"  value="http://wowtables.com/payment/process_response" />
    <input name="furl" type="text"  value="http://wowtables.com/payment/process_response" />
    <input name="api_version" type="hidden"   value="1" />
    <input name="hash" type="hidden" size="60" value="<?php echo $secure_hash;?>" />

    <input name="submitted" value="Submit" type="submit" style ="display:none;"  />
</form>
<script language="javascript">
    document.getElementById("frmTransaction").submit();
</script>