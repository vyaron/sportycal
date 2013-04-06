<?php 
	slot('title', sprintf('%s Calendars: %s', $category->getName(), $cal->getName()));
	slot('keywords',sprintf('%s,%s', $cal->getKeywords(), $category->getCategoryPathAsKeywords()));
	slot('og:description', 'Subscribe to your favourite sport team / sport tournaments and be synched with their schedule so you never miss a game again');
	
	sfContext::getInstance()->getResponse()->addStylesheet('calShow');
    $isMasterOf = UserUtils::userISMasterOf($category);
    
    //$isMaster = true;
	$eventsCount = $events->count();
	
	if (empty($ctgLinks)) $ctgLinks = array();
?>

<div id="divCtgNav">
    <span class="ctgNav"><?php echo $category->getCategoryPathAsNavigation(!$cal->isBirthdayCal(), ESC_RAW) ?></span>
    <?php echo image_tag($category->getImagePathSub(), '" class="imgCtgSub" alt="'.$cal->getNameFixed($category).'"')?>
	
	<?php if (UserUtils::userISMasterOf($category)) :?>
        <?php include_partial('admin/ctgAdminLinks',array('category' => $category, 'ctgLinks' => $ctgLinks, 'parentCtg'=>$category->getParentCategory())) ?>
    <?php endif;?>
	
    <?php if ($isMasterOf && !$cal->isAggregated()) :?>
        <?php include_partial('admin/calAdminLinks',array('cal' => $cal, 'partners'=>$partners, 'partnersDesc'=>$partnersDesc)) ?>
    <?php endif;?>
    
</div>


<center>
<h1><?php echo $cal->getNameFixed($category) ?> </h1>

<?php if ($cal->getLocation()) : ?> 
    <h2>Location: <?php echo $cal->getLocation(); ?> </h2>
<?php endif?>

<?php if (false && $cal->getDescription()) : ?> 
    <h2><?php echo $cal->getDescription(); ?> </h2>
<?php endif?>

</center>

<table class="calBox">
	<tr>
		<td class="calBoxTL"></td>
		<td class="calBoxTC">
			<div id="calDownWrapper"><?php include_partial('cal/calDown',array('cal' => $cal, 'category'=>$category)) ?></div>
		</td>
		<td class="calBoxTR"></td>
	</tr>
	<tr>
		<td class="calBoxCL"></td>
		<td class="calBoxCC">
			<?php if ($leagueEnded):?>
				<br/><br/><br/><br/>
				<h2 class="tRed"><?php echo __('The league has Ended');?></h2>
				<h3 class="tGray"><?php echo __('Events will be automatically added to your calendar when published');?></h3>
			<?php elseif (!$haveFutureEvents):?>
				<br/><br/><br/><br/>
				<h2 class="tRed"><?php echo __('No future games published yet');?></h2>
			<?php endif;?>
		
			<!-- include events -->
			<?php if ($eventsCount) : ?>
				<?php include_partial('event/events',array('events' => $events, 'cal' => $cal, 'isMasterOf' => $isMasterOf, 'tzList' => $tzList)) ?>
			<?php endif ?>
		</td>
		<td class="calBoxCR"></td>
	</tr>
	<tr>
		<td class="calBoxBL"></td>
		<td class="calBoxBC"></td>
		<td class="calBoxBR"></td>
	</tr>
</table>


<?php //include_partial('cal/calDownContainer',array('cal' => $cal, 'category'=>$category)) ?>



<div class="cb"></div>

<div style="margin:auto;margin-top:20px;text-align: center; padding-left: 50px;">
	<g:plusone annotation="none"></g:plusone>
	
	<a href="https://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a>
	<div class="fb-like" data-send="true" data-width="350" data-show-faces="true"></div>
</div>

<!-- Place this tag where you want the +1 button to render -->


<div class="cb"></div>


<br/><br/>
<?php if (false && count($ctgLinks)) : ?>
	<?php include_partial('main/ctgLinks',array('category' => $category, 'cal' => $cal, 'ctgLinks' => $ctgLinks)) ?>
<?php endif;?>




<div id="mobilePopup" class="hidden">
	<div class="popupBG"></div>
	<div class="mobilepopupContentWrapper popupContent">
		<div class="mobilePopupContent">
			<div class="mobilePopupContentT"></div>
			<div class="mobilePopupContentC">
				<h3 class="mobilePopupTitle popupTitle">Blackburn Rovers vs. Asenal</h3>
				<p class="mobilePopupLabel">Notes:</p>
				<p class="mobilePopupNotes popupNotes">H 4.35 | D 3.49 | A 1.84 <br/> Bet on this Game at: http://samplebet.com</p>
			</div>
			<div class="mobilePopupContentB"></div>
		</div>
	</div>
</div>

<div id="htmlPopup" class="hidden">
	<div class="popupBG"></div>
	<div class="popupContent">
		<div id="htmlPopupContent">
			<h3 class="popupTitle"></h3>
			<p>Mon, June 11, 2012, 20:00 - 20:00</p>
			<p class="popupNotes"></p>
		</div>
	</div>
</div>

<?php include_partial('cal/downThanks',array('category' => $category, 'cal' => $cal)) ?>


<div class="cb"></div>

<?php 
$gpiLocationId = 0; 
$address = $category->getAddress();
if ($address) {
	$gpiLocation 		= $address->getLocation();
	$gpiLocationId 		= $gpiLocation->getId();
	$gpiLocationName 	= $gpiLocation->getName();
} 


?>
<?php if (false && $gpiLocationId) : ?>
<br/><br/>
<h1> Going to <?php echo $gpiLocationName ?>? </h1>
<iframe frameborder="0" border="0" style="border:none; overflow:hidden; width:250px; height:360px; " src="http://www.goplanit.com/assets/widget/top/index.php?destId=<?php echo $gpiLocationId ?>&type=l|d|a|deal&ipc=5&pId=sportycal-0796"></iframe>
<div class="cb"></div>
<?php endif;?>

<script type="text/javascript">
var gHtmlPreivews = <?php echo ($mobilePopupItems) ? $sf_data->getRaw('htmlPreviews') : '{}';?>;
var gMobilePopupItems = <?php echo ($mobilePopupItems) ? $sf_data->getRaw('mobilePopupItems') : '{}';?>;

function previewPopup(e){
	if (e){
		e.stop();

		var btn = e.target;
		if (btn){
			var id = btn.getProperty('itemId');

			var item = null;
			var popupEl = null;
			if (btn.hasClass('mobilePopup')){
				item = gMobilePopupItems[id];
				popupEl = $('mobilePopup');
			} else if (btn.hasClass('htmlPopup')){
				item = gHtmlPreivews[id];
				popupEl = $('htmlPopup');
			}

			if (item && popupEl){
				var titleEl = popupEl.getElement('.popupTitle');
				if (titleEl){
					titleEl.set('html', item.title);
				}
				var notesEl = popupEl.getElement('.popupNotes');
				if (notesEl){
					notesEl.set('html', item.notes);
				}

				popupEl.removeClass('hidden');

				popupEl.removeEvents();
				popupEl.addEvent('click', function(e){
					if (e && e.target && e.target.get('tag') != 'a' ){
						e.stop();
					}
				});

				var popupBG = popupEl.getElement('.popupBG');
				if (popupBG){
					popupBG.removeEvents();
					popupBG.addEvent('click', function(e){
						e.stop();
						popupEl.addClass('hidden');
					});
				}
			}
		}
	}
}


window.addEvent('domready', function(){
	var mobilePopups = $$('.mobilePopup, .htmlPopup');
	if (mobilePopups){
		mobilePopups.each(function(btn){
			btn.addEvent('click', previewPopup.bind(this));
		});
	}
});

// Gooogle + Button
(function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();

//Twiter
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");

</script>	

