<?php
class NeverMissUtils{
	public static function hostIsNeverMiss(){
		return (strpos(strtolower($_SERVER['HTTP_HOST']), 'inevermiss.') !== false);
	}
}