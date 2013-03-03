<?php

/**
 * Location form base class.
 *
 * @method Location getObject() Returns the current form's model object
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseLocationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'name'        => new sfWidgetFormInputText(),
      'name_ascii'  => new sfWidgetFormInputText(),
      'city'        => new sfWidgetFormInputText(),
      'state'       => new sfWidgetFormInputText(),
      'country'     => new sfWidgetFormInputText(),
      'latitude'    => new sfWidgetFormInputText(),
      'longitude'   => new sfWidgetFormInputText(),
      'type_id'     => new sfWidgetFormInputText(),
      'fips_code'   => new sfWidgetFormInputText(),
      'priority'    => new sfWidgetFormInputText(),
      'timezone_id' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'name_ascii'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'city'        => new sfValidatorString(array('max_length' => 150)),
      'state'       => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'country'     => new sfValidatorString(array('max_length' => 100)),
      'latitude'    => new sfValidatorNumber(),
      'longitude'   => new sfValidatorNumber(),
      'type_id'     => new sfValidatorInteger(array('required' => false)),
      'fips_code'   => new sfValidatorInteger(array('required' => false)),
      'priority'    => new sfValidatorInteger(),
      'timezone_id' => new sfValidatorString(array('max_length' => 5, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('location[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Location';
  }

}
