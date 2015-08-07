<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>404 Page not found</title>
</head>

<body>

<p>User has found error while surfing the website. Below are the few details:</p>
<p> Website: <?php echo $error_array['domain'];?></p>
<p> Error url: <?php echo $error_array['error_url'];?></p>
<p> User's IP Address: <?php echo $error_array['ip_address'];?></p>
<p> User's browser details: <?php echo $error_array['browser_details'];?></p>

</body>
</html>