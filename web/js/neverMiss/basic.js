function setGlobalError(content){
	var elStr = jQuery('#DUMMY_GLOBAL_ERROR').html();
	elStr = elStr.replace('{CONTENT}', content);
	
	jQuery('#alerts').html(elStr);
}

function setGlobalSuccess(content){
	var elStr = jQuery('#DUMMY_GLOBAL_SUCCESS').html();
	elStr = elStr.replace('{CONTENT}', content);
	
	jQuery('#alerts').html(elStr);
}