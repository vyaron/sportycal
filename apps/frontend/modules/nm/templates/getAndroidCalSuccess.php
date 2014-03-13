<?php 
use_stylesheet('/css/neverMiss/getAndroidCal.css');
if ($isRTL) use_stylesheet('/css/neverMiss/getAndroidCal_rtl.css');
?>

<h2 id="banner"><?php echo __('Adding to calendar')?></h2>

<div class="container">
	<h3><?php echo __('Your %calName% Calendar is Ready!', array('%calName%' => $calendar->getName()))?></h3>

	<hr/>

    <p><?php echo __('Please enter your email address so we can send your subscription link.')?></p>
    <p><?php echo __('Note - due to some limitations on Android devices, you must open this link on a Desktop.')?></p>

    <form id="mail-form">
        <input type="hidden" name="userCalId" value="<?php echo $userCalId;?>"/>
        <div class="row-fluid">
            <div class="span8">
                <input dir="ltr" id="mail-input" type="email" name="email" placeholder="name@domain.com" required="required"/>
            </div>
            <div class="span4">
                <button id="mail-form-submit" class="btn btn-success pull-right" type="submit">
                    <span><?php echo __('Send')?></span>
                </button>
            </div>
        </div>

        <p><?php echo __('We will never use this email to spam you in any way.')?></p>
    </form>

    <!--
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
	-->
</div>

<?php
use_javascript('/bundle/jquery-plugin-validation/js/jquery.validate.min.js');
use_javascript('/js/neverMiss/getAndroidCal.js');
?>