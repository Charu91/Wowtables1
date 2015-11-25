<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>App Login Issue</title>
</head>

<body>

<p>User has found error while surfing the APP Login. Below are the few details:</p>
<p> User Email: <?php echo $data['email'];?></p>
<p> User Password: <?php echo $data['password'];?></p>
<p> Message: <?php echo $data['message'];?></p>
<p> Action: <?php echo $data['action'];?></p>
<p> Code: <?php echo $data['code'];?></p>
<p> App Version: <?php echo $data['app_version'];?></p>
<p> Hardware: <?php echo $data['hardware'];?></p>
<p> OS version: <?php echo $data['os_version'];?></p>
<p> OS Type: <?php echo $data['os_type'];?></p>
<p> Device ID: <?php echo $data['device_id'];?></p>

</body>
</html>