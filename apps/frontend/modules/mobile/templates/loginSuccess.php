<?php $user = UserUtils::getLoggedIn();?>

<!-- header -->
<?php
	if (!isset($backRow)) $backRow = null;
	
	$selectedTab = 4;
	include_partial('mobile/mainHeader',array('backRow' => $backRow, 'selectedTab' => $selectedTab)) ;
?>

<?php if (!$user) : ?>
	<div class="fb-login-button pl10" data-show-faces="true" data-width="200" data-max-rows="1" data-scope="email,user_birthday"></div>
<?php else: ?>
	<?php echo $user->getFullName()?>
	<a href="<?php echo url_for('main/logout') ?>" onclick="doLogout();return false;">Logout</a>
<?php endif; ?>