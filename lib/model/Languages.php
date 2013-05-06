<?php
class Languages{
	private static $list = array(
		'he-IL' => 'Hebrew',
		'en-US' => 'American english',
		'en-UK' => 'British English',
	);
	
	public static function getLanguagesOptions(){
		return self::$list;
	}
}