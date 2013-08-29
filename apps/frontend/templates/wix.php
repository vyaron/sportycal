<!DOCTYPE HTML>
<html lang="en">
  <head>
  	<meta charset="utf-8"/>
  	<title><?php echo sfConfig::get('app_domain_name');?> Website - Wix</title>
  	<link type="text/css" href="/bundle/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
  	<link type="text/css" href="/css/wix/main.css" rel="stylesheet"/>
  	<?php include_stylesheets() ?>
</head>
<body>
	<div id="container">
	<?php echo $sf_content;?>
	</div>
	
	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script>window.jQuery || document.write('<script src="/bundle/jquery/js/jquery-1.10.1.min.js"><\/script>');</script>
	<script type="text/javascript" src="//sslstatic.wix.com/services/js-sdk/1.19.0/js/Wix.js"></script>
	<script type="text/javascript" src="/js/neverMiss/basic.js"></script>
	<?php include_javascripts()?>
</body>
</html>