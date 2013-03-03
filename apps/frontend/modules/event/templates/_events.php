<?php $fromFbApp = UserUtils::getFromFbApp(); ?>

<!-- Starts Event LIST -->
<div id="events">
	<div id="eventsHeader">
		<?php if (!$fromFbApp) :?>
		<a id="getEventPdf" title="Download a PDF" onclick="return updateUserCals('<?php echo Cal::TYPE_HARDCOPY ?>');" class="icalLinkA" href="<?php echo $cal->getIcalUrl(Cal::TYPE_HARDCOPY)?>" target="blank"></a>
		<?php endif;?>
		
		<h3><?php echo __('Events List');?></h3>
		<h4><?php echo __('Automatically updated in your Calendar!');?></h4>
	</div>
	
	<div id="eventsListWrapper">
		<table id="eventsList">
			<thead>
				<tr>
					<th><?php echo __('What');?></th>
					
					<?php if (!$cal->isAggregated() && !$fromFbApp) :?>
					<th><?php echo __('Where');?></th>
					<?php endif?>
					
					<th><?php echo __('When');?></th>
					
					<?php if ($isMasterOf && !$cal->isAggregated()) :?>
					<th><?php echo __('Admin');?></th>
					<?php endif;?>
		
				</tr>
			</thead>
			<tbody>
			<?php foreach ($events as $event): ?>
				<?php $eLocation = $event->getLocation()?>
				<?php $eAddress = $event->getAddressToDisplay()?>
				<tr>
					<td style="white-space: nowrap;" class="tdEventName">
						<div class="fl"><?php echo $event->getName() ?></div>
						<?php echo $event->getInfoBubble(null, ESC_RAW) ?>
						<div class="cb"></div>
					</td>
					<?php if (!$cal->isAggregated() && !$fromFbApp) :?>
					<td style="white-space: nowrap;">
						<?php if ($eLocation && $eLocation != "Home" && $eLocation != "Away") : ?>
							<a target="_blank" href="http://maps.google.com/maps?q=<?php echo $eLocation ?>"><?php echo $eLocation ?></a>
						<?php elseif ($eAddress) :?>
							<a target="_blank" href="http://maps.google.com/maps?q=<?php echo $eAddress ?>"><?php echo $eAddress ?></a>
						<?php else :?> 
							<?php echo $event->getLocation() ?>
						<?php endif ?>
					</td>
					<?php endif?>
					
					<td style="white-space: nowrap;"><?php echo $event->getDateForDisplay() ?></td>
					
					<?php if ($isMasterOf) :?>
					<td class="adminLinks" style="width:135px;">
						<?php if (!$cal->isAggregated()):?>
							<a href="<?php echo url_for('event/edit?id='.$event->getId()) ?>" title="<?php echo __('Edit');?>"><img src="<?php echo url_for('/images/icons/edit_16x16.png');?>" /></a>&nbsp;|&nbsp;
							<?php echo link_to('<img src="' . url_for('/images/icons/delete_16x16.png') . '"/>', 'event/delete?id='.$event->getId(),array('method' => 'delete', 'confirm' => 'DELETE this EVENT?')) ?>&nbsp;|&nbsp;
						<?php endif;?>
						
						<a class="mobilePopup eventTextIcon" title="<?php echo __('Preview');?>" itemId="<?php echo $event->getId();?>" href="#"></a>&nbsp;|&nbsp;
						<a class="htmlPopup eventHtmlIcon" title="<?php echo __('Preview HTML');?>" itemId="<?php echo $event->getId();?>" href="#"></a> 
					</td>
					<?php endif;?>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div  id="eventsFooter">
		<div style="font-size: 14px; text-align: center;padding-top:10px">
		<?php if ($cal->hasHours()) :?>
			<?php if (UserUtils::getUserFullNameTZ()):?>
				<?php echo __('Presented in Timezone:');?>
				<b><?php echo UserUtils::getUserFullNameTZ() ?></b>
				<a id="changeTz" href="#"><img src="<?php echo url_for('/images/icons/arrowDown.png')?>" /></a>
			<?php endif?><br/>
			<?php echo __('Times will be converted to your exact timezone by your calendar');?>
			<br />
		<?php endif ?>
		</div>
		<div style="font-size: 12px; text-align: center; margin-top: 5px; color: #999999">
			<?php echo count($events) . " " . __('Events'); ?>
			<?php if ($cal->getUpdatedAt()):?>&nbsp;|&nbsp; <?php echo __('Updated');?>:
			<?php echo format_date($cal->getUpdatedAt(), 'D') ?>
			<?php endif ?>
		</div>
	</div>
</div>

<?php if ($tzList):?>					
	<div id="changeTzDropdown" class="hidden">
	<?php foreach ($tzList as $tz):?>
		<div class="changeTzOption" rel="<?php echo $tz->value?>"><?php echo $tz->name?></div>
	<?php endforeach;?>
	</div>
<?php endif;?>

<?php include_partial('global/infoBubble') ?>
<script type="text/javascript">
window.addEvent('domready', function(){
	var changeTz = $('changeTz');
	if (changeTz){
		changeTz.addEvent('click', function(e){
			e.stop();

			var cord = changeTz.getCoordinates();
			
			var changeTzDropdown = $('changeTzDropdown');
			if (changeTzDropdown){
				changeTzDropdown.setStyles({
					top : cord.top,
					left : cord.left + 10
				});
				changeTzDropdown.removeClass('hidden');
			}
		});
	}

	var changeTzOption = $$('.changeTzOption');
	if (changeTzOption){
		changeTzOption.each(function(el){
			el.addEvent('click', function(e){
				e.stop();

				var req = new Request({
					url : '/main/saveTZ',
					onSuccess : function(res){
						var res = JSON.decode(res);
						if (res.status){
							location.reload(true);
						}
					}
				}).post('tz=' + el.get('rel'));
				
				var changeTzDropdown = $('changeTzDropdown');
				if (changeTzDropdown){
					changeTzDropdown.addClass('hidden');
				}
			});
		});
	}
});
</script>
<!-- END Event LIST -->