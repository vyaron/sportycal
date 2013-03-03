<?php 
if (!isset($txtSearch)) $txtSearch = '';
if (!isset($fromDate)) $fromDate = '';
if (!isset($toDate)) $toDate = '';
?>

<form id="searchForm" action="<?php echo url_for('main/search')?>">
	<div id="divSearchBoxSmall">
		<div id="searchBox">
    		<input class="hidden" type="text" name="firefox" value="1" />
			<input class="ctgSearch" type="text" name="txtSearch" id="txtSearchSmall" value="<?php echo $txtSearch ?>" MAXLENGTH="100" autocomplete="off"/>
			<a id="btnSearchSmall" class="searchSubmit" href="#"><?php echo __('Search');?></a>
    	</div>

		<div class="cb"></div>
		
		<div id="sbOptions">
			<a href="#" id="sbOptionsShow"><?php echo __('More Options');?></a>
			<div id="sbMoreOptions" class="hidden">
				<div id="sbMoreOptionsPadding">
					<a href="#" id="sbOptionsHide"><?php echo __('Hide Options');?></a>
					<label class="sbMoreOptionsLabel"><?php echo __('filter by dates')?>:</label>
	
					<?php echo __('From');?>:
					<div class="pickDateWrapper">
						<input name="fromDate" type="text" class="sbDatePick" id="mainSearchFromDate" value="<?php echo $fromDate ?>" />
						<?php echo image_tag('layout/calendar.gif', 'class="imgSearchCal"')?> 
					</div>
					<?php echo __('To');?>:
					<div class="pickDateWrapper">
						<input name="toDate" type="text" class="sbDatePick" id="mainSearchToDate" value="<?php echo $toDate ?>" />
						<?php echo image_tag('layout/calendar.gif', 'class="imgSearchCal"')?> 
					</div>
					<a id="searchBtnSmall" class="searchSubmit" href="#"><?php echo __('Search');?></a>
				</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript" src="/js/datepicker/datepicker.js"></script>
<?php sfContext::getInstance()->getResponse()->addJavascript('search.js'); ?>
<script type="text/javascript">
window.addEvent('domready', function() {
	var txtSearch = $('txtSearchSmall');
	if (txtSearch){
		new Search(txtSearch, {
			ajaxSearchEl : $('ajaxSearch'),
			submitEls : $$('.searchSubmit'),
			startDateEl :$('mainSearchFromDate'),
			endDateEl : $('mainSearchToDate'),
			moreOptions : true
		});
	}

	new DatePicker('.sbDatePick', {
		pickerClass: 'datepicker_dashboard',
		allowEmpty: true
	});

	//Cal icons events
	var pickDateWrapperImgs = $$('.pickDateWrapper img');
	if (pickDateWrapperImgs){
		pickDateWrapperImgs.each(function(pickDateWrapperImg){
			pickDateWrapperImg.addEvent('click', function(e){
				e.stop();
				var inputEl = pickDateWrapperImg.getParent().getElements('.sbDatePick')[1];
				if (inputEl){
					inputEl.fireEvent('focus');
				}
			});
		});
	}
});
</script>
<!--[if IE]>
<style>
#btnSearchSmall {
	position:relative;
	top:1px;
}
</style>
<![endif]-->

