<?php
class RegisterForm extends BaseForm {
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
				'full_name'   => new sfValidatorString(array('required' => true, 'min_length' => 3)),
				'email'   => new sfValidatorEmail(array('required' => true)),
				'password' => new sfValidatorString(array('required' => true, 'min_length' => 4)),
				'confirm_password' => new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'confirm_password'),
				'company_name' => new sfValidatorString(array('required' => true, 'min_length' => 3)),
				'website' => new sfValidatorUrl(array('required' => false)),
				'agree' => new sfValidatorBoolean(array('required' => true)),
		));

		$this->widgetSchema->setNameFormat('register[%s]');
	}
}