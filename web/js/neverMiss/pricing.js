function showPriceBubble(){
	var options = {
		wizardToken : gToken,
		paymentPlanId : gPaymentPlanCode,
		externalUserId : gExternalUserId,
		callbackUrl : gCallbackUrl,
		apiKey : gApiKey,

		successCallback : function(license) {
			cl('success');
			cl(license);
		},
		failCallback : function() {
			cl('fail');
		}
	};
	
	licensario.getLicense(options);
}

jQuery(document).ready(function(){
	//showPriceBubble();
});