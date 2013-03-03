<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sportYcal Hosting Site</title>
</head>

<body>

<h3>Some Hosting Site, Showing Next 2 weeks Sport Events (max 300)</h3>


<script type='text/javascript'>

	//DEV
	//var scRootUrl = 'http://sportYcal.local/widget/';
	//var scBaseUrl = 'http://sportYcal.local';
	
	//PROD
	var scRootUrl = 'http://sportYcal.com/widget';
	var scBaseUrl = 'http://sportYcal.com';

	scReqParam = [];
	scReqParam.locationId =  '<?php echo $_REQUEST["locId"] ?>';
	scReqParam.partnerId =   'GoPlanIt-4217';
	scReqParam.widgetWidth = 590;
	
    document.write('<div id="scWidget"></div>');

	//sportYcal Widget
	document.write('<scr'+'ipt type="text/JavaScript" src="' + scBaseUrl + '/js/basics.js"></scr'+'ipt>');
	document.write('<scr'+'ipt type="text/JavaScript" src="' + scRootUrl + '/js/sportYcal-wigdetLang.en-US.js"></scr'+'ipt>');
    document.write('<scr'+'ipt type="text/JavaScript" src="' + scRootUrl + '/js/sportYcal-wigdet.v.0.1.js"></scr'+'ipt>');
</script>





</body>
</html>
