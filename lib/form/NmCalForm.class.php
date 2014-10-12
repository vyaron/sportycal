<?php
class NmCalForm extends BaseForm {
	public function configure(){
		$this->setWidgets(array(
				'name'   => new sfWidgetFormInputText(),
				'description'   => new sfWidgetFormTextarea(),
				'tz' => new sfWidgetFormSelect(array('choices' => GeneralUtils::getTZList(true))),
		));

		$this->setValidators(array(
				'name'   => new sfValidatorString(array('required' => true, 'min_length' => 3, 'trim' => true)),
				'description'   => new sfValidatorString(array('required' => false, 'max_length' => 256)),
				'tz' => new sfValidatorChoice(array('choices' => array_keys(GeneralUtils::getTZList(true)))),
		));
		
		$this->widgetSchema->setNameFormat('cal[%s]');
	}
}