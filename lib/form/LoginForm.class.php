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
      'email'   => new sfValidatorEmail(array(), array('invalid' => __('Incorrect Email/Password Combination.'))),
      'password' => new sfValidatorString()
    ));

    $this->widgetSchema->setNameFormat('user[%s]');
    
    
  }
	
	
}
