<?php

$userSession = sfContext::getInstance()->getUser();
$culture = $userSession->getCulture();

$langs = array(
	'en' => 'English',
	'fr' => 'Français',
	'es' => 'Español',
	'ru' => 'Русский',
	'he' => 'עברית'
);

?>

<div id="changeLangWrapper">
	<div id="changeLangFlag" class="<?php echo 'flag_' . $culture;?>"></div>
	<select id="changeLang">
		<?php foreach ($langs as $val => $name):?>
		<option value="<?php echo $val;?>" <?php echo ($val == $culture) ? 'selected="selected"' : '';?>><?php echo $name;?></option>
		<?php endforeach;?>
	</select>
</div>
