<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script type="text/javascript" >
		//for Android Success
		function AndroidSuccess(input) {
			//alert(input);
			PayU.onSuccess(input);
		}
		AndroidSuccess("<?php $result=''; foreach($_POST as $key=> $value)$result .= $key . '=' . $value . ','; $result = rtrim($result , ','); echo $result ?>");

		//for Android failure
		function AndroidFailure(input) {
			//alert(input);
			PayU.onFailure(input);
		}
		//AndroidFailure("<?php $result=''; foreach($_POST as $key=> $value)$result .= $key . '=' . $value . ','; $result = rtrim($result , ','); echo $result ?>");


	</script>
</head>
<body>
</body>
</html>