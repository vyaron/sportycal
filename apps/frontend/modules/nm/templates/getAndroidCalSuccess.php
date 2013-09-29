<?php 
use_stylesheet('/css/neverMiss/getAndroidCal.css');
?>

<h2 id="banner">Adding to calendar</h2>

<div class="container">
	<div id="instructions">&nbsp;</div>
	
	<div class="clearfix">
		<a href="<?php echo Cal::GOOGLE_IMPORT_URL . urlencode(sfConfig::get('app_domain_full') . '/cal/get/h/' . $userCalId . '/' . $fileName . '.ics');?>" class="btn btn-success pull-right mb15">Got It!</a>
	</div>
</div>