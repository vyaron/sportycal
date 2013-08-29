<!DOCTYPE html>
<html>
<head>
<title>Sign Up</title>
<link type="text/css" href="/bundle/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
<style>
	body{margin: 0; padding: 0;}
</style>
</head>
<body>
	<h1>Sign Up</h1>
	
	<button id="register">Register</button>
	
	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="//sslstatic.wix.com/services/js-sdk/1.19.0/js/Wix.js"></script>
	
	<script src="/js/neverMiss/basic.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#register').click(function(){
				var message = {"reason": "User register succesfully"};
				Wix.closeWindow(message);
			});
		});
	</script>
</body>
</html>
