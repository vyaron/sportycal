<?php 
	$mainNav = array(
		'1' => array('Home', '/'),
		'2' => array('Search', '/main/search'),
		'3' => array('Today', '/main/search?today=1&fromDate=' . date('U') . '&toDate=' . date('U'))
	);
	
	$user = UserUtils::getLoggedIn();
	
	if ($user){
		$mainNav['4'] = array('Logout', '/main/logout');
	} else {
		$mainNav['4'] = array('Login', '/main/Login');
	}

	if (isset($backRow)) $backRow = $sf_data->getRaw('backRow');
?>


<!-- header -->
<header id="mainHeader">
	<?php if ($backRow) :?>
	<section id="backRow">
		<!-- back button -->
		<a id="backBtn" href="<?php echo ($backRow['parentUrl']) ? url_for($backRow['parentUrl']) : '/';?>" title="<?php echo ($backRow['parentTitle']) ? $backRow['parentTitle'] : '';?>">
			<div class="appleStyleL"></div>
			<div class="appleStyleC"><span>Back</span></div>
			<div class="appleStyleR"></div>
			<div class="cb"></div>
		</a>
		
		<?php if (isset($backRow['iconClass'])):?>
		<!-- ctgIcon -->
		<div id="headerIcon" class="<?php echo $backRow['iconClass']?>"></div>
		<?php endif;?>
		
		
		<!-- next button -->
		<?php if (isset($backRow['nextBtn'])):?>
		<a id="nextBtn" class="hidden nextBtn" href="#">
			<div class="appleStyleRR"></div>
			<div class="appleStyleC"><span>Next</span></div>
			<div class="appleStyleRL"></div>
			<div class="cb"></div>
		</a>
		<?php endif;?>
		
		<div class="cb"></div>
	</section>
	<?php endif;?>
    	
	<nav id="mainNav">
		<?php foreach ($mainNav as $i => $tab):?>
			<a href="<?php echo $tab[1]?>" <?php echo ($selectedTab && ($i == $selectedTab)) ? 'class="selectedTab"' : '';?>>
				<?php if ($i < count($mainNav)):?>
				<div class="mainNavSep"><?php echo $tab[0]?></div>
				<?php else:?>
				<?php echo $tab[0]?>
				<?php endif;?>
			</a>
		<?php endforeach;?>
	</nav>
</header>

<?php if (Utils::clientIsAndroid() && !UserUtils::getFromAndroidApp()) :?>
<a id="getAndroidApp" class="banner darkGrayGradiantBg" href="https://play.google.com/store/apps/details?id=com.sportycal.sportycal" target="_blank"><span>Download Android App</span></a>
<?php endif;?>