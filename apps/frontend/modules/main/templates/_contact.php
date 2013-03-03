<table border="0px" cellspacing="0px">
    <tr class="">
        <td class="tdCorner tdBoxTopLeft"></td>
        <td class="tdBoxTopMiddle"></td>
        <td class="tdCorner tdBoxTopRight"></td>
    </tr>
     <tr class="trBox" >
        <td class="tdBoxLeft"></td>

        <td class="tdBox" >
            <h2 id="feedbackTitle"><?php echo __('We appreciate your Feedback');?></h2><br/>
            

            <div id="thankYouMsg" class="center hidden">
                <h2><?php echo __('Thank you!');?></h2>
                <br/><br/><br/><br/><br/><br/><br/>
                <?php if (!isset($closeBtn)):?>
                	<a href='<?php echo url_for("main/index")?>' > <?php echo __('Back to Home Page');?> </a>
                <?php endif;?>
                <br/><br/>
            </div>
            

            <div id="feedbackFormWrapper" class="smallText">
	            <form action="<?php echo url_for("main/sendContact")?>">
					<input name="toki" type="hidden" value="<?php echo $toki?>" />
	            	<div id="ratingWrapper">
	            		<input type="hidden" name="feedbackRating" id="feedbackRating"/>
	            		
	            		<p id="ratingP"><?php echo __('How do you feel about our site:');?></p>
	            		<a class="smileIcon smile1" href="#" rel="1"></a>
	            		<a class="smileIcon smile2" href="#" rel="2"></a>
	            		<a class="smileIcon smile3" href="#" rel="3"></a>
	            		<a class="smileIcon smile4" href="#" rel="4"></a>
	            		<a class="smileIcon smile5" href="#" rel="5"></a>
	            		<div class="cb"></div>
	            	</div>
	                <textarea name="msg" rows="5" cols="40"></textarea><br/><br/>
	                <?php if (!$user) : ?>
	                    <?php echo __('Name');?>: <input name="name" type="text" value="" style="width:120px" />
	                    &nbsp;&nbsp;&nbsp;&nbsp;
	                    <?php echo __('Email');?>: <input name="email" type="text" value="" style="width:120px" />
	                    
						<div class="mt10">
							<p><?php echo __("Please type the letters") ?>:</p>
							<img id="captchaImg" src="<?php echo UserUtils::getCaptchaImgPath();?>"/><br/>
							<input id="captcha" name="captcha" type="text"/>
						</div>
					<?php endif?>
					
					<p id="feedbackFormErrorMsg" class="hidden"><?php echo __('Some of the data entered is not valid');?></p>
					
	                <div style="text-align:right">
	                    <input id="feedbackSubmit" type="submit" value="<?php echo __('Send');?>" class="niceButton" />
	                </div>
	            </form>
            </div>
            
            <?php if (isset($closeBtn)):?>
                <a id="closeFeedbackPopup" href="#"><?php echo __('[x] Close');?></a>
			<?php endif;?>


        </td>
        <td class="tdBoxRight">
        </td>
    </tr>
    <tr class="">
        <td class="tdCorner tdBoxBottomLeft"></td>
        <td class="tdBoxBottomMiddle"></td>
        <td class="tdCorner tdBoxBottomRight"></td>
    </tr>
</table>

<script type="text/javascript">
	window.addEvent('domready', function(){
		var feedbackSubmit = $('feedbackSubmit');

		if (feedbackSubmit){
			var form = feedbackSubmit.getParent('form');
			form.set('send', {
				method : 'post',
				onComplete : function(res){
					var res = JSON.decode(res);
					if (res.status){
						var thankYouMsg = $('thankYouMsg');
						if (thankYouMsg){
							thankYouMsg.removeClass('hidden');
						}

						var feedbackFormWrapper = $('feedbackFormWrapper');
						if (feedbackFormWrapper){
							feedbackFormWrapper.addClass('hidden');
						}
					} else {
						var captchaImg = $('captchaImg');
						if (captchaImg){
							captchaImg.set('src', res.captchaImgPath);
						}

						var captcha = $('captcha');
						if (captcha){
							captcha.set('value', '');
						}

						var feedbackFormErrorMsg = $('feedbackFormErrorMsg');
						feedbackFormErrorMsg.removeClass('hidden');
					}
				}
			});

			feedbackSubmit.addEvent('click', function(e){
				e.stop();
				form.send();
			});
		}

		var smileIcons = $$('.smileIcon');
		if (smileIcons){
			smileIcons.each(function(smileIcon){
				smileIcon.addEvent('click', function(e){
					e.stop();

					var feedbackRating = $('feedbackRating');

					var currRel = feedbackRating.get('value');
					var currSmileSel = $$('.smileIcon.smile' + currRel);
					if (currSmileSel){
						currSmileSel.removeClass('smileIconSel');
					}

					smileIcon.addClass('smileIconSel');
					var rel = smileIcon.get('rel');

					feedbackRating.set('value', rel);
					
				});
			});
		}
	});
</script>