<?php

class LoginForm extends BaseForm
{
 public function configure()
  {
    $this->setWidgets(array(
      'email'   => new sfWidgetFormInputText(),
      'password' => new sfWidgetFormInputPassword(),
    ));

	$this->setValidators(array(
      'email'   => new sfValidatorEmail(),
      'password' => new sfValidatorString(array('min_length' => 4))
    ));

    $this->widgetSchema->setNameFormat('user[%s]');
    
    
  }
	
	
}
