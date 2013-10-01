<?php use_stylesheet('/css/neverMiss/addToCalendar.css');?>

<div class="container">
	<div id="box">
		<div class="form-box">
			<h2><strong>Add To Your Calendar</strong></h2>
			
			<span class="content">
				<div class="nm-follow" data-cal-id="<?php echo $calId;?>" data-ctg-id="<?php echo $ctgId;?>" data-ref="<?php echo $ref;?>" data-language="en" data-btn-style="list" data-color="default" data-upcoming="5"></div>
			</span>
		</div>
	</div>
</div>


<script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//<?php echo sfConfig::get('app_domain_short');?>/w/neverMiss/all.js";fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'never-miss-jssdk'));</script>