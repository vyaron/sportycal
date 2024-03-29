<?php 
use_stylesheet('/bundle/mediaelement/mediaelementplayer.min.css');
use_stylesheet('/bundle/mediaelement/mejs-inevermiss.css');
use_stylesheet('/css/neverMiss/index.css');
?>
<div id="banner" class="section">
	<div class="container">
		<div id="banner-content" class="clearfix">
			<div id="banner-col-left">
				<h1>Become part of your customer's day</h1>
				
				<p>Allow users to see events created by you in their personal calendars. Your users will never miss a sale, a promotion, a show or an event</p>
			</div>
			<div id="banner-col-right">
				<div id="player-wrapper">
					<video id="player" class="mejs-inevermiss pull-right" width="100%" height=100% poster="/videos/nevermiss/iNeverMiss.jpg">
					    <!-- Pseudo HTML5 -->
					    <source type="video/webm" src="/videos/nevermiss/iNeverMiss.webm"/>
					   	<source type="video/mp4" src="/videos/nevermiss/iNeverMiss.mp4"/>
					    <!-- <source type="video/youtube" src="http://www.youtube.com/watch?v=UQHZCQfMBB8"/> -->
					</video>
					<div class="cb"></div>
				</div>
			</div>
		</div>
		
		<div id="start-wrapper">
			<?php if ($isReachedMaxCalendars):?>
			<a class="start-now-btn btn btn-success btn-large disabled hidden-phone" href="#" title="<?php echo __('Reached calendars limit');?>">START NOW. <strong>IT'S FREE!</strong></a>
			<?php else:?>
			<a class="start-now-btn btn btn-success btn-large hidden-phone" href="<?php echo url_for('nm/calCreate') ?>">START NOW. <strong>IT'S FREE!</strong></a>
			<?php endif;?>
			
			<p class="visible-phone">You are on a mobile, we recommend creating your calendar on a desktop, its easy.</p>
		</div>
		
		<div id="cal-down-counter-wrapper" class="clearfix">
			<div class="pull-left">CALENDARS DOWNLOADED</div>
			<div id="cal-down-counter" class="pull-left"><?php echo number_format($calsDownloadedCount);?></div>
		</div>
	</div>
</div>


<div class="section light-gray bby fs14">
	<div class="container">
		<div class="row">
			<div class="span4">
				<div class="box">
					<h3><i class="box-icon-a"></i> Simple</h3>
					<p>Users subscribe to your calendar in just one click, using any calendar (Google, Outlook, or iCal), on any device (Mobile, PC or Mac).</p>
          			<p>Your users will get a reminder just in time and will be able to plan their day with you in it.</p>
				</div>
			</div>
			<div class="span4">
				<div class="box">
					<h3><i class="box-icon-b"></i> Easy</h3>
					<p>Adding the iNeverMiss subscription service to your site is quick. Hit start now and then you'll just need to add up-coming events, and publish the iNeverMiss button to your site.</p>
				</div>
			</div>
			<div class="span4">
				<div class="box">
					<h3><i class="box-icon-c"></i> Relationship</h3>
					<p>Boost your brand and enhance user relationships whether you are a retailer, loyalty club, sports club, fan club, event organizer, artist... make sure your users never miss you.</p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php if (false) :?>
<div class="container hidden-phone">
	<a id="down-cal-link" href="<?php echo url_for('nm/calCreate') ?>"><strong><span class="color-y">DOWNLOAD</span> BEST CALENDARS</strong> FOR FREE!</a>
</div>
<?php endif ?>
<?php  
use_javascript('/bundle/mediaelement/mediaelement-and-player.min.js');
if (!UserUtils::getUserTZ()) use_javascript('/js/neverMiss/getUserTZ.js');
use_javascript('/js/neverMiss/index.js');
?>
