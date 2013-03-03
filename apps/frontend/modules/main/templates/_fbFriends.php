<?php


if (!isset($id)) $id = 'fbFriends';
if (!isset($height)) $height = 400;
?>

<?php sfContext::getInstance()->getResponse()->addStylesheet('FBFriendsSelector.css');?>


<div id="<?php echo $id?>" style="<?php echo ($height >= 0) ? 'height:' . $height . 'px;' : ''?>" class="fbFriendLoading">
	<div class="fbFriendFilters">
		<input type="text" class="fbFriendSearch" />
		
		<div class="fbFriendCheckAllWrapper">
			<input type="checkbox" class="fbFriendCheckAll">
			<label class="fbFriendCheckAllLabel">Select all</label>
		</div>
		<div class="cb"></div>
	</div>
	
	<div class="fbFriendWrapper" style="<?php echo ($height >= 0) ? 'height:' . ($height - 50) . 'px;' : ''?>"></div>
	
	<p class="fbFriendSelP"></p>
	
	<div class="dummy_fbFriend hidden">
		<input type="checkbox" class="fbFriendCheckbox"/>
		<div class="fbFriendImg"></div>
		<div class="fbFriendContent">
			<h4 class="fbFriendName"></h4>
			<h5 class="fbFriendDate"></h5>
		</div>
		<div class="cb"></div>
	</div>
</div>

<?php sfContext::getInstance()->getResponse()->addJavascript('FBFriendsSelector.class.js'); ?>

<script type="text/javascript">
var gFBFreinds_<?php echo $id?> = null;

window.addEvent('domready', function(){
	gFBFreinds_<?php echo $id?> = new FBFriendsSelector({
		'id' : '<?php echo $id?>',
		'more_parms' : ['birthday_date'],
		'req_parms' : ['birthday_date']
	});
});

</script>