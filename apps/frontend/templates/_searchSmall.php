<div style="margin-top:20px;">

    <div id="divSearchBoxSmall">
        <div id="sbLeftSmall">&nbsp;</div>
        <div id="sbMiddleSmall">
            <form action="<?php echo url_for('main/search')?>">
            	<input class="hidden" type="text" name="firefox" value="1" />
                <input class="ctgSearch" type="text" name="txtSearch" id="txtSearchSmall" value="<?php echo $txtSearch ?>" MAXLENGTH="100" autocomplete="off"/>
                <a id="btnSearchSmall" class="searchSubmit" href="#"><?php echo __('Search');?></a>
            </form>
        </div>
        <div id="sbRightSmall">&nbsp;</div>
    </div>


</div>

<?php sfContext::getInstance()->getResponse()->addJavascript('search.js'); ?>
<script type="text/javascript">
window.addEvent('domready', function() {
	var txtSearch = $('txtSearchSmall');
	if (txtSearch){
		new Search(txtSearch, {
			ajaxSearchEl : $('ajaxSearch'),
			submitEls : $$('.searchSubmit')
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

