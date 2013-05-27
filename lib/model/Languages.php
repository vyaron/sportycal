<?php
class Languages{
	private static $list = array(
		'en'	=>	'English',
		'bg'	=>	'Bulgarian',
		'cs' 	=> 'Czech',
		'da' 	=> 'Danish',
		'el' 	=> 'Greek',
		'es'	=> 'Spanish',
		'fr'	=>	'French',
		'he'	=>	'Hebrew',
		'hu'	=>	'Hungarian',
		'it'	=>	'Italian',
		'nl' 	=> 'Dutch',
		'pt' 	=> 'Portuguese',
		'ro' 	=> 'Romanian',
		'sr'	=> 'Serbian',
		'sv'	=>	'Swedish'
	);
	
	public static function getLanguagesOptions(){
		return self::$list;
	}
}