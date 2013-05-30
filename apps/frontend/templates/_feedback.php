<?php
    $toki = UserUtils::putSecurityToken();
    
    //echo "-------------------------------------------------------<br/>";
    //var_dump(UserUtils::getSecurityToken());
	//echo "-------------------------------------------------------<br/>";
	
?>

<a href="#" id="feedbackBtn"></a>
<div id="feedbackPopup" class="hidden">
	<div id="feedbackPopupBG"></div>
	<div id="feedbackPopupContent">
		<?php include_partial('main/contact', array('user' => $user, 'closeBtn' => true, 'toki'=>$toki, 'getAjaxCapcha' => true)); ?>
	</div>
</div>

<script type="text/javascript">
window.addEvent('domready', function(){
	var feedbackBtn = $('feedbackBtn');
	if (feedbackBtn){
		feedbackBtn.addEvent('click', function(e){
			e.stop();

			var feedbackPopup = $('feedbackPopup');
			if (feedbackPopup){
				<?php if (!$user):?>
				new Request.JSON({
					url : '/main/getCaptchaImgPath',
					mehod : 'POST',
					onComplete : feedbackUpdateCaptchaImg
				}).send();

				<?php endif;?>
				feedbackPopup.removeClass('hidden');
			}
		});
	}

	var closeFeedbackPopup = $('closeFeedbackPopup');
	if (closeFeedbackPopup){
		closeFeedbackPopup.addEvent('click', function(e){
			e.stop();
			
			var feedbackPopup = $('feedbackPopup');
			if (feedbackPopup){
				feedbackPopup.addClass('hidden');
			}
		});
	}
});

</script>