<div style="height: 80px;">
	<div class="centererOuter">
		<div class="centererInner">
			<form action="<?php echo url_for('main/search')?>">
				<div id="divSearchBox">
					<div id="sbLeft">&nbsp;</div>
					<div id="sbMiddle">
						<input autocomplete="off" class="ctgSearch" type="text" name="txtSearch" id="txtSearch" value="<?php echo $txtSearch ?>" MAXLENGTH="100"/>
						<a id="btnSearch" class="searchSubmit" href="#"><?php echo __('Search');?></a>
					</div>
					<div id="sbRight">&nbsp;</div>
				</div>
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
			</form>

		</div>
	</div>
</div>


<script type="text/javascript" src="/js/datepicker/datepicker.js"></script>
<?php sfContext::getInstance()->getResponse()->addJavascript('search.js'); ?>
<script>

window.addEvent('domready', function() {
	var txtSearch = $('txtSearch');
	if (txtSearch){
		new Search(txtSearch, {
			ajaxSearchEl : $('ajaxSearch'),
			startDateEl :$('mainSearchFromDate'),
			endDateEl : $('mainSearchToDate'),
			submitEls : $$('.searchSubmit'),
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
<!--[if (lte IE 7)]>
<style>
#divSearchBox {
    width:450px;
}
#sbOptions {
	top:0px;
	left:-50px;
}

</style>



<![endif]-->

<!--[if IE]>
<style>
#btnSearch {
	position:relative;
	top:5px;
}

</style>
<![endif]-->

