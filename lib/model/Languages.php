<?php
class Languages{
	private static $list = array(
		'he-IL' => 'Hebrew',
		'en-US' => 'American english',
		'en-UK' => 'British English',
		'ar-JO' => 'Jordan Arabic',
	);
	
	public static function getLanguagesOptions(){
		return self::$list;
	}
}