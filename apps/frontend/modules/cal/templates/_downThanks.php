<?php
$boxWidth = 500;
$boxHeight = 300;
?>

<?php

	$urlToShare = sfConfig::get('app_domain_full').$_SERVER['REQUEST_URI'];//"http://www.sportYcal.com";
	$loggedinId = UserUtils::getLoggedInId();
	if ($loggedinId) $urlToShare .= '?iu=' . "$loggedinId";

	$partner = UserUtils::getPartner();
	if ($partner) {
		$urlToShare = $partner->getUrlToShare();	
	}
	
	//$urlToShare = urlencode($urlToShare);

?>


<div id="popupWrapper" class="vhidden bgt" >
	<div id="popupBG"></div>
	<div id="popupContent" class="bgt" style=" margin-left:-<?php echo ($boxWidth/2) ?>px; margin-top:-<?php echo ($boxHeight/2) ?>px;background-color: transparent;">
<table border="0px" width="<?php echo $boxWidth ?>" height="<?php echo $boxHeight ?>" cellspacing="0px" cellpadding="0px" class="bgt">
    <tr class="bgt">
        <td class="tdCorner tdBoxTopLeft"></td>
        <td class="tdBoxTopMiddle"></td>
        <td class="tdCorner tdBoxTopRight"></td>
    </tr>
     <tr class="trBox">
        <td class="tdBoxLeft"></td>

        <td class="tdBox" style="background-color: white">
			<div class="whiteCloseIcon popupCloseBtn fr" style="position:relative;top:-50px;"></div>

			<div id="boxThankYou" class="c" >
				<?php echo image_tag($category->getImagePathSub(), 'class="ctgLogo"')?>
				
				<p class="boxThankYouTitle">
				<?php echo image_tag('icons/success.png', '')?>
				<span id="perCalTypeTxt" class="strong" style="font-size: 12px"></span>
				<br/>
				<span id="perCalTypeSubTxt" class="tGray c"></span>
				</p>
				<br/><hr/><br/>
		    	<h2 class="happyTitle"><?php echo __('How about Sharing?')?></h2>
		    	<br/>
		        <h3 class="tGray c">
			        <?php echo __('Sport is Great, We hope you enjoy the games.')?> <br/>
			     	<?php echo __('How about sharing with your friends?')?>
			     </h3>
			     <br/>
				 <a id="fbShareBtn" href="<?php echo $urlToShare?>" style="text-decoration:none;">
					<img alt="fb" src="/images/icons/facebook.png" style="margin-right: 22px;" align="absmiddle" />
				</a>
				<a target="_blank" href="http://twitter.com/share?url=<?php echo $urlToShare?>" style="text-decoration:none;" onclick="return reportThankYou('twitter')">
					<img alt="twt" src="/images/icons/twitter.png" align="absmiddle" />
				</a>
			    <br/>
				<br/>
			</div>
        
        </td>
        <td class="tdBoxRight"></td>
        </tr>
        <tr class="bgt">
            <td class="bgt tdCorner tdBoxBottomLeft"></td>
            <td class="tdBoxBottomMiddle"></td>
            <td class="tdCorner tdBoxBottomRight"></td>
        </tr>
   </table>    
	</div>
</div>
