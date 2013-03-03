<?php
require_once 'local.php';

$ref = null;
if (isset($_GET['ref'])){
	$ref = $_GET['ref'];
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HostingSite.coma</title>
<style type="text/css">*{padding:0; margin:0;}</style>
</head>

<body>
<h1>
<a href="http://sportycal.local/calendarius/index.php?ref=888Sport-0796"> Click Here FIRST</a>
</h1>
<div class="hostExternal">
<table border="1" width="100%" cellpadding="10px">
	<tr>
		<th class="hostTh" colspan="2">
			Premier League
			<div class="spFR">
				<div class="calendarius" title="Download Schedule to your own Calendar" section="English Premier League 2010-2011"></div>
			</div>			
		</th>
	</tr>
	<tr>
		<td>Chelsea</td><td>Manchester City</td>
	</tr>
	<tr>
		<td>Arsenal</td><td>Liverpool</td>
	</tr>
	<!-- LINE 2 -->
	<tr>
		<th class="hostTh" colspan="2">
			Indian premier league
			<div class="spFR">
				<div class="calendarius" title="Download Schedule to your own Calendar" section="Indian premier league"></div>
			</div>			
		</th>
	</tr>
	<tr>
		<td>Chelsea</td><td>Manchester City</td>
	</tr>
	<tr>
		<td>Arsenal</td><td>Liverpool</td>
	</tr>


</table>
</div>


<div id="scContainer"></div>
<div id="scScriptHolder"></div>
<script type="text/javascript" src="<?php echo WIDGET_URL?>widget.php?&ref=<?php echo $ref?>"></script>





</body>
</html>
