<?php
class NmRegisterForm extends BaseForm {
	public function configure(){
		$this->setWidgets(array(
				'full_name'   => new sfWidgetFormInputText(),
				'email'   => new sfWidgetFormInputText(),
				'password' => new sfWidgetFormInputPassword(),
				'confirm_password' => new sfWidgetFormInputPassword(),
				'company_name'   => new sfWidgetFormInputText(),
				'website'   => new sfWidgetFormInputText(),
				'agree'   => new sfWidgetFormInputCheckbox(),
		));

		$this->setValidators(array(
				'full_name'   => new sfValidatorString(array('required' => true, 'min_length' => 3, 'trim' => true)),
				'email'   => new sfValidatorDoctrineUnique(array('required' => true, 'model' => 'User', 'column' => 'email'), array('invalid' => 'already exist')),
				'password' => new sfValidatorString(array('required' => true, 'min_length' => 4)),
				'confirm_password' => new sfValidatorString(array('required' => true)),
				'company_name' => new sfValidatorString(array('required' => true, 'min_length' => 3, 'trim' => true)),
				'website' => new sfValidatorString(array('required' => false)),
				/*
				'website' => new sfValidatorAnd(array(
			        new sfValidatorString(array('required' => false, 'min_length' => 5)),
			        new sfValidatorRegex(array('required' => false, 'pattern' => '/([a-z0-9-]+\.)+[a-z]{2,6}/')),
			     )),
			     */
				'agree' => new sfValidatorBoolean(array('required' => true)),
		));
		
		$this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
				new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'confirm_password'),
		)));
		
		$this->widgetSchema->setNameFormat('register[%s]');
		
		//sfValidatorDoctrineUnique
	}
}