<?php const PAYPAL_MERCHANT_ID = 'CLZLCXKGKCJKW';?>
<?php const PAYPAL_JS_SRC = 'https://raw.github.com/paypal/JavaScriptButtons/master/src/paypal-button.js';?>
<div class="container">
	<a class="btn plan-btn" href="<?php echo url_for('nm/licenceWizard/?c=' . PartnerLicence::PLAN_A)?>">Silver</a>
	<a class="btn plan-btn" href="<?php echo url_for('nm/licenceWizard/?c=' . PartnerLicence::PLAN_B)?>">Gold</a>
	<a class="btn plan-btn" href="<?php echo url_for('nm/licenceWizard/?c=' . PartnerLicence::PLAN_C)?>">Platinum</a>
	
	
	<script type="text/javascript" src="<?php echo PAYPAL_JS_SRC;?>?merchant=<?php echo PAYPAL_MERCHANT_ID;?>" 
		data-button="buynow"
	    data-name="My product"
	    data-amount="100.00"
    ></script>
    
    <script src="<?php echo PAYPAL_JS_SRC;?>?merchant=<?php echo PAYPAL_MERCHANT_ID;?>"
	    data-button="subscribe"
	    data-name="My product"
	    data-amount="1.00"
	    data-recurrence="1"
	    data-period="M"
	></script>
	
	<script src="<?php echo PAYPAL_JS_SRC;?>?merchant=<?php echo PAYPAL_MERCHANT_ID;?>"
    	data-button="qr"
	    data-name="Product via QR code"
	    data-amount="1.00"
	    data-size="250"
></script>
</div>