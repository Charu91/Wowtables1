<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>



<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Forgot Password</title>
</head>

<body>
Hi {{$data['userName']}},<br>
We have received a forgot password request from you. If you have sent the request, please use the link below to set a new password.
<br /><a href='{{URL::to('setPassword')}}/{{$data['randString']}}/{{$data['user_id']}}'>{{URL::to('setPassword')}}/{{$data['randString']}}/{{$data['user_id']}}</a><br />
If you have not sent the request then you do not need to do anything.<br />
<br>Thanks & Regards<br>
The WowTables Team
</body>
</html>