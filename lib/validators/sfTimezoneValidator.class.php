<?php

class sfTimezoneValidator extends sfValidatorBase
{
	protected function configure($options = array(), $messages = array())
	{
		$this->addMessage('timezone', 'wrong timezone "%value%"');
	}
 
 protected function doClean($value)
  {
  	$clean = (string) $value;
  	
  	$objTZ = @timezone_open($value);
  	if (!$objTZ) {
  		throw new sfValidatorError($this, 'timezone', array('value' => $value));
  	}

  	return $clean;
  }
}

