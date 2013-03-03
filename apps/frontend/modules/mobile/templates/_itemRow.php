<?php
//include_partial('mobile/itemRow', array('href' => $href, 'h2' => $h2, 'h3' => $h3));

if (!isset($href)) 			$href = null;
if (!isset($h2)) 			$h2 = null;
if (!isset($h3)) 			$h3 = null;
if (!isset($h4)) 			$h4 = null;
if (!isset($a)) 			$a = null;
if (!isset($iconClass)) 	$iconClass = null;
if (!isset($infoIconClass)) $infoIconClass = null;
if (!isset($addClass)) 		$addClass = null;
if (!isset($onClick)) 		$onClick = null;
if (!isset($target)) 		$target = null;
if (!isset($calDown)) 		$calDown = false;

if (!isset($calLink)) 		$calLink = "calLink_mobile";
?>

<?php if ($calDown && Utils::clientIsAndroid()):?>
	<p class="androidDownloadMsg">Google might ask you if to use the mobile version. If so - Click CANCEL</p>
<?php endif;?>

<?php if ($href):?>
	<a class="itemRowLinkWrapper <? echo $calLink;?>" href="<?php echo $href?>" <?php echo ($onClick) ? 'onclick="' . $onClick . '"' : ''?> <?php echo ($target) ? 'target="' . $target . '"' : ''?>>
<?php endif;?>
	<div  class="itemRow <?php echo ($addClass) ? $addClass : ''?>">
		<?php if($iconClass):?>
		<div class="itemIcon <?php echo $iconClass ?>">
		<?php endif;?>
			<div class="itemInfo <?php echo ($infoIconClass) ? $infoIconClass : ''?>">
				<?php if ($h2):?>
				<h2 class="itemH2"><?php echo $h2?></h2>
				<?php endif;?>
				<?php if ($h3):?>
				<h3 class="itemH3"><?php echo $h3?></h3>
				<?php endif;?>
				<?php if ($h4):?>
				<h3 class="itemH4"><?php echo $h4?></h3>
				<?php endif;?>
				<?php if ($a):?>
				<a class="itemA" href="<?php echo url_for($a['href']);?>"><?php echo $a['html']?></a>
				<?php endif;?>
			</div>
		<?php if($iconClass):?>
		</div>
		<?php endif;?>
	</div>
<?php if ($href):?>
	</a>
<?php endif;?>