<?php 
use_stylesheet('/css/neverMiss/getAndroidCal.css');
if ($isRTL) use_stylesheet('/css/neverMiss/getAndroidCal_rtl.css');
?>

<h2 id="banner"><?php echo __('Adding to calendar')?></h2>

<div class="container">
	<h3><?php echo __('Hey, You might need to..')?></h3>

	<hr/>
	
	<table id="instructions">
		<tr>
			<td rowspan="3">
				<div id="instructions-img"></div>
			</td>
			<td class="content">
				<div class="circle">1</div>
				<h4><?php echo __('Click "OK"');?></h4>
				<p>(<?php echo __("don't worry..");?><br/><?php echo __('all features will work')?>)</p>
			</td>
		</tr>
		<tr>
			<td class="content">
				<div class="circle">2</div>
				<h4><?php echo __('Log in to your');?><br/><?php echo __('Google account');?></h4>
				<p>(<?php echo __("unless you're already logged in");?>)</p>
			</td>
		</tr>
		<tr>
			<td class="content">
				<div class="circle">3</div>
				<h4><?php echo __('Click');?><br/>"<?php echo __('Yes, add this calendar');?>"</h4>
				<p><?php echo __("and you're done!");?></p>
			</td>
		</tr>
		<tr>
			<td colspan="2"><a id="download-btn" href="<?php echo Cal::GOOGLE_IMPORT_URL . urlencode(sfConfig::get('app_domain_full') . '/cal/get/h/' . $userCalId . '/' . $fileName . '.ics');?>" class="btn btn-success"><?php echo __('Got It!');?></a></td>
		</tr>
	</table>
</div>