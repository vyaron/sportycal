<!DOCTYPE HTML>
<html>
<head>
<title>Never Miss Widget</title>
</head>
<link rel="stylesheet" href="/widgets/neverMiss/css/main.css"/>
<style>
#never-miss-btn{background: url('/widgets/neverMiss/imgs/btn.jpg') no-repeat 0 0; display: block; height:20px; width:45px; text-decoration: none;}
</style>
<body>
<a id="never-miss-btn" href="<?php echo url_for('w/neverMissPopup/?calid=' . $calId . ($language ? ('&language=' . $language) : ''))?>">&nbsp;</a>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/widgets/neverMiss/js/vendor/jquery-1.9.1.min.js"><\/script>');</script>
<script type="text/javascript">
var gWindow = null;
jQuery(document).ready(function(){
	jQuery('#never-miss-btn').click(function(e){
		e.preventDefault();

		if (parent.postMessage) {
			parent.postMessage('<?php echo $popupId;?>', "*");
		} else {
			//Old Browser open in new window
			if (gWindow) {
				gWindow.close();
				gWindow = null;
			} else {
				gWindow = window.open(this.href, "Never Miss", "directories=0,titlebar=0,toolbar=0,location=0,status=0,menubar=0,scrollbars=no,resizable=no,width=350,height=170");
				gWindow.focus();
			}
		}
	});
});
</script>
</body>
</html>