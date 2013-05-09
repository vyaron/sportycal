<?php 
if (isset($_GET['calId'])){
	$calId = $_GET['calId'];
} else {
	echo 'ERROR';
	die();
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Never Miss Widget</title>
</head>
<style>
body{margin: 0px; padding: 0;}
#never-miss-btn{background: url('/imgs/btn.jpg') no-repeat 0 0; display: block; text-decoration: none;}
</style>
<body>
<a id="never-miss-btn" href="widget_bubble.php?calId=<?php echo $calId;?>">&nbsp;</a>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>');</script>
<script type="text/javascript">
//var gWindow = null;
jQuery(document).ready(function(){
	jQuery('#never-miss-btn').click(function(e){
		e.preventDefault();
		/*if (gWindow) {
			gWindow.close();
			gWindow = null;
		} else {*/
			gWindow = window.open(this.href, "Never Miss", "directories=0,titlebar=0,toolbar=0,location=0,status=0,menubar=0,scrollbars=no,resizable=no,width=350,height=170");
			gWindow.focus();
		//}
	});
});
</script>
</body>
</html> 