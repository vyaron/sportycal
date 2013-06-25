<h1>Welcome to Never Miss</h1>
<p>Sleek, intuitive, and powerful calendar event creator for faster and easier calendar subscription.</p>
<p>Outlook, Google, iOS ...</p>

<a class="btn btn-success btn-large hidden-phone" href="<?php echo url_for('nm/calCreate') ?>">START NOW.</a>

<div class="visible-phone">
	<a class="btn btn-success btn-large disabled" href="#">START NOW.</a>
	<p>for now, it's easier to create your calendar with your desktop</p>
</div>


<div class="clear-fix">
	<div class="pull-right">CALENDARS DOWNLOADED <span id="cal-down-counter"><?php echo number_format($calsDownloadedCount);?></span></div>
</div>
<?php  if (!UserUtils::getUserTZ()) use_javascript('/js/neverMiss/getUserTZ.js');?>
