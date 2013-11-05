<!DOCTYPE HTML>
<html lang="en">
  <head>
  	<meta charset="utf-8"/>
  	<title><?php echo sfConfig::get('app_domain_name');?> Website - Wix</title>

  	<?php include_stylesheets() ?>
</head>
<body>
	<?php echo $sf_content;?>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="/bundle/jquery/js/jquery-1.10.2.min.js"><\/script>');</script>
	<script type="text/javascript" src="//sslstatic.wix.com/services/js-sdk/1.19.0/js/Wix.js"></script>
	<?php include_javascripts()?>
</body>
</html>