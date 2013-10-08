<?php 
use_stylesheet('/bundle/wix/stylesheets/css/bootstrap.css');
use_stylesheet('/bundle/wix/stylesheets/css/common.css');
use_stylesheet('/bundle/wix/stylesheets/css/buttons.css');
use_stylesheet('/bundle/wix/stylesheets/css/settings.css');

use_stylesheet('/bundle/wix/javascripts/components/color-picker/css/color-picker.css');
use_stylesheet('/bundle/wix/javascripts/components/glued-position-min/glued.min.css');
?>

<header class="intro box">
    <div class="title">
        <!-- App Logo with native CSS3 gloss -->
        <div class="icon">
            <div class="logo">
                <span class="gloss"></span>
            </div>
        </div>

        <!-- This divider is a must according to the Wix design requirements -->
        <div class="divider"></div>
    </div>

    <!-- Connect account area -->
    <div class="login">
        <div class="guest">
            <div class="description">
                <p>
                    Allow users to see events created by you in their personal calendars. Your users will never miss a sale, a promotion, a show or an event
                </p>
            </div>
			
			<?php if (!$user):?>
            <div class="login-panel">
                <p class="create-account">Don't have an<br/>account? <a id="create-account" href="#"><strong>Create one</strong></a></p>
                <button id="connect" class="submit btn connect">Connect account</button>
            </div>
            <?php endif;?>
        </div>
		
		<?php if ($user):?>
        <div class="user">
            <p>
                You are now connected to <strong class="user-name"><?php echo $user->getFullName()?> (<?php echo $user->getEmail()?>)</strong> account<br/>
                <a id="disconnect" class="disconnect-account">Disconnect account</a>
            </p>
            
            <?php if (!$isPremium):?>
            <div class="premium">
                <p class="premium-features">Premium features</p>
                <button class="submit btn upgrade">Upgrade</button>
            </div>
            <?php endif;?>
        </div>
        <?php endif;?>
    </div>
</header>

<!-- Settings box -->
<form id="settings-form">
	<input type="hidden" name="instance" value="<?php echo $wix->getInstanceCode();?>"/>
	<input type="hidden" name="compId" value="<?php echo $wix->getCompCode();?>"/>
	
	<input type="hidden" name="line_color" value="<?php echo $lineColor;?>"/>
	<input type="hidden" name="text_color" value="<?php echo $textColor;?>"/>
	<input type="hidden" name="bg_color" value="<?php echo $bgColor;?>"/>
	<input type="hidden" name="bg_opacity" value="<?php echo $bgOpacity;?>"/>
	<input type="hidden" name="bg_is_transparent" value="<?php echo $bgIsTransparent;?>"/>
		
	<div class="accordion">
		<!-- Calendar -->
	    <div class="box">
	        <h3>Calendar Setup</h3>
			<div class="feature">
				<?php if ($user): $calsCount = count($cals);?>
	            	<?php if ($calsCount === 0):?>
			            <div>
			                <button id="create-calendar" type="button" value="option1" class="btn gray">Create your First!</button>
			            </div>
		            <?php elseif ($calsCount === 1): ?>
						<input type="hidden" name="cal_id" value="<?php echo $cals[0]->getId();?>"/>
	
						<strong><?php echo $cals[0]->getName();?></strong>
						<a id="edit-calendar" href="#">Edit</a>
					<?php else:?>
						<select id="cal-id" name="cal_id" class="span6">
							<option value="">&nbsp;</option>
							<?php foreach ($cals as $cal):?>
							<option value="<?php echo $cal->getId();?>" <?php echo ($cal->getId() == $calId) ? 'selected="selected"' : '';?>><?php echo $cal->getName();?></option>
							<?php endforeach;?>
						</select>
						<a id="edit-calendar" href="#" style="display:none;">Edit</a>
					<?php endif;?>
	            <?php else:?>
	            <p>In order to start please first <a href="#" class="connect">connect your account</a></p>
	            <?php endif;?>
	        </div>
	    </div>
	
	    <!-- Colors -->
	    <div class="box">
	        <h3>Colors</h3>
	        <div class="feature" style="display: none;">
	            <p>Customize colors</p>
	            <ul class="list">
	                <li>
	                    <span class="option">Line Color</span>
	                    <span class="picker"><a id="line_color" rel="popover" class="color-selector default" data-color="<?php echo $lineColor;?>"></a></span>
	                </li>
	                <li>
	                    <span class="option">Text Color</span>
	                    <span class="picker"><a id="text_color" rel="popover" class="color-selector default" data-color="<?php echo $textColor;?>"></a></span>
	                </li>
	                <li>
	                    <span class="option">Background Color</span>
	                    <span class="picker"><a id="bg_color" rel="popover" class="color-selector default" data-color="<?php echo $bgColor;?>"></a></span>
	                    <span class="slider-container">
	                        0<span id="bg_opacity" class="values slider" data-opacity="<?php echo $bgOpacity;?>"></span>100
	                    </span>
	                    
	                    <span id="bg_is_transparent" class="checkbox" data-checked="<?php echo $bgIsTransparent?>">
                        	<span class="check"></span>
	                        Transparent
	                    </span>
	                </li>
	            </ul>
	        </div>
	    </div>
	</div>  

</form>

<script type="text/javascript">
	var BASE_URL = '<?php echo sfConfig::get('app_domain_full');?>';
</script>


<?php 
use_javascript('//sslstatic.wix.com/services/js-sdk/1.19.0/js/Wix.js');
use_javascript('//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js');

use_javascript('/bundle/wix/javascripts/bootstrap/bootstrap-tooltip.js');
use_javascript('/bundle/wix/javascripts/bootstrap/bootstrap-popover.js');

use_javascript('/bundle/wix/javascripts/components/accordion/accordion.js');
use_javascript('/bundle/wix/javascripts/components/checkbox/checkbox.js');
use_javascript('/bundle/wix/javascripts/components/radio-button/radio-button.js');
use_javascript('/bundle/wix/javascripts/components/slider/slider.js');
use_javascript('/bundle/wix/javascripts/components/color-picker/color-pickers/simple.js');
use_javascript('/bundle/wix/javascripts/components/color-picker/color-pickers/advanced.js');
use_javascript('/bundle/wix/javascripts/components/color-picker/color-picker.js');

use_javascript('/bundle/wix/javascripts/components/glued-position-min/glued.min.js');

use_javascript('/js/wix/settings.js');

?>