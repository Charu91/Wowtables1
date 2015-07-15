<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>



<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>You have been registered as a WowTables member!</title>
</head>

<body>
<p>Dear <?php echo (isset($data['userName']) && $data['userName'] != "" ? $data['userName'] : "Diner")?>,</p>
<p>At your request, we have registered you as a member of WowTables. You can easily log on to <a href='http://wowtables.com' target="_blank">WowTables.com</a> to make an online reservation or download the WowTables app at <a href="http://app.wowtables.com" target="_blank">app.wowtables.com</a> to use our services. Your credentials are:</p>
<p>Email: <?php echo $data['email']?> </p>
<p>Password: <?php echo $data['password']?> </p>
<p>We recommend you change your password immediately by clicking the following link:</p>
<a href='{{URL::to('setPassword')}}/{{$data['randString']}}/{{$data['user_id']}}' target="_blank">{{URL::to('setPassword')}}/{{$data['randString']}}/{{$data['user_id']}}</a>
<p>You can also change the password by logging into <a href='http://wowtables.com' target="_blank">WowTables.com</a> and visiting the 'My Profile' page.</p>
<p>If you have any questions please call our concierge at 91-9619551387.</p>
<p>If you have received this email in error, please reply back to this message and we will remove you from our member list.</p><br><br>
<br>Thanks & Regards<br>
<p>WowTables Concierge</p>
</body>
</html>