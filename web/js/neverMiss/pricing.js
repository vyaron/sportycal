var gExternalUserId = null;

function showPriceBubble(paymentPlanId1){
	var paymentPlanId = null
	if (typeof paymentPlanId1 != 'undefined') paymentPlanId = paymentPlanId1;
	
	var options = {
		wizardToken : gToken,
		//featureIds : [ "MAX_SUBSCR5848347ff4", "ONLINE_SUP67eb8a7fb2" ],
		externalUserId : gExternalUserId,
		callbackUrl : 'http://inevermiss.net',
		apiKey : gApiKey,

		successCallback : function(license) {
			cl('success');
			cl(license);
		},
		failCallback : function() {
			cl('fail');
		}
	};
	
	if (paymentPlanId) options.paymentPlanId = paymentPlanId;
	else options.allowedPaymentPlanIds = [ "MAX_SUBSCR5848347ff4", "ONLINE_SUP67eb8a7fb2" ];
	
	licensario.getLicense(options);
}

jQuery(document).ready(function(){
	jQuery('.plan-btn').click(function(e){
		e.preventDefault();
		
		var paymentPlanId = jQuery(this).attr('data-plan-id');
		
		if (!gExternalUserId){
			jQuery.ajax({
				url : '/nm/getPricingToken',
				type : 'POST',
				dataType : 'json'
			}).done(function(res){
				if (res.success){
					gExternalUserId = res.externalUserId;
					gToken = res.token;
					
					showPriceBubble(paymentPlanId);
				}
			});
		} else {
			showPriceBubble(paymentPlanId);
		}
	});
});