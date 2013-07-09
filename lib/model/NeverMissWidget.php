<?php
class NeverMissWidget{
	const DEFAULT_LANGUAGE = 'en';
	const DEFAULT_VALUE = 'default';
	const LANGUAGE_HEBREW = 'he';
	
	public static $LANGUAGES_OPTIONS = array(
		self::DEFAULT_LANGUAGE => 'English',
		self::LANGUAGE_HEBREW => 'Hebrew'
	);
	
	public static function getWidgetCode($cal, $language='en', $btnStyle = NeverMissWidget::DEFAULT_VALUE, $btnSize = NeverMissWidget::DEFAULT_VALUE, $color = NeverMissWidget::DEFAULT_VALUE){
		$code = '';
	
		$scriptUrl = sfConfig::get('app_domain_short') . '/w/neverMiss/all.js';
	
		if ($cal){
			$code = '<div class="nm-follow" data-cal-id="' . $cal->getId() . '" data-language="' . $language . '" data-btn-style="' . $btnStyle . '" data-btn-size="' . $btnSize . '" data-color="' . $color . '"></div>' . "\n";
			$code .= '<script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//' . $scriptUrl . '";fjs.parentNode.insertBefore(js, fjs);}(document, \'script\', \'never-miss-jssdk\'));</script>';
	
		}
	
		return $code;
	}
}