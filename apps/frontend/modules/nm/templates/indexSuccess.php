<?php 
use_stylesheet('/bundle/mediaelement/mediaelementplayer.min.css');
use_stylesheet('/bundle/mediaelement/mejs-inevermiss.css');
use_stylesheet('/css/neverMiss/index.css');
?>
<div id="banner" class="section">
	<div class="container">
		<h1>Welcome to Never Miss</h1>
		
		<div class="row">
			<div class="span6">
				<p>Sleek, intuitive, and powerful calendar event creator for faster and easier calendar subscription.</p>
				<p>Outlook, Google, iOS ...</p>
				
				<a id="start-now-btn" class="btn btn-yellow btn-large hidden-phone" href="<?php echo url_for('nm/calCreate') ?>">START NOW. <strong>IT'S FREE!</strong></a>
		
				<div class="visible-phone">
					<a class="btn btn-yellow btn-large disabled" href="#">START NOW. <strong>IT'S FREE!</strong></a>
					<p>for now, it's easier to create your calendar with your desktop</p>
				</div>
				
			</div>
			<div class="span6">
				<div id="player-wrapper">
					<video id="player" class="mejs-inevermiss pull-right">
					    <!-- Pseudo HTML5 -->
					   	<!-- <source type="video/mp4" src="/bundle/mediaelement/media/echo-hereweare.mp4"/> -->
					    <source type="video/youtube" src="http://www.youtube.com/watch?v=h8MS6PPNke0"/>
					</video>
					<div class="cb"></div>
				</div>
			</div>
		</div>
		
		<div id="cal-down-counter-wrapper" class="clear-fix">
			<div class="pull-left">CALENDARS DOWNLOADED</div>
			<div id="cal-down-counter" class="pull-left"><?php echo number_format($calsDownloadedCount);?></div>
		</div>
	</div>
</div>


<div class="section light-yellow">
	<div class="container">
		<div class="row">
			<div class="span4">
				<div class="box box-icon-a">
					<h3>ONE CLICK</h3>
					<p>is simply dummy text of the printing and typesetting industry. Lorem Ipsum has make a type specimen book.</p>
				</div>
			</div>
			<div class="span4">
				<div class="box box-icon-b">
					<h3>TOOLS</h3>
					<p>is simply dummy text of the printing and typesetting industry. Lorem Ipsum has make a type specimen book.</p>
				</div>
			</div>
			<div class="span4">
				<div class="box box-icon-c">
					<h3>REALTIONSHIPS</h3>
					<p>is simply dummy text of the printing and typesetting industry. Lorem Ipsum has make a type specimen book.</p>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container hidden-phone">
	<a id="down-cal-link" href="<?php echo url_for('nm/calCreate') ?>"><strong><span class="color-y">DOWNLOAD</span> BEST CALENDARS</strong> FOR FREE!</a>
</div>
 
<?php  
use_javascript('/bundle/mediaelement/mediaelement-and-player.min.js');
if (!UserUtils::getUserTZ()) use_javascript('/js/neverMiss/getUserTZ.js');
use_javascript('/js/neverMiss/index.js');
?>
