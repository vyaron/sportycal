<?php

/**
 * Address form base class.
 *
 * @method Address getObject() Returns the current form's model object
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseAddressForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'country_code' => new sfWidgetFormInputText(),
      'state'        => new sfWidgetFormInputText(),
      'city'         => new sfWidgetFormInputText(),
      'addr'         => new sfWidgetFormInputText(),
      'zip'          => new sfWidgetFormInputText(),
      'location_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Location'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'country_code' => new sfValidatorString(array('max_length' => 50)),
      'state'        => new sfValidatorString(array('max_length' => 255)),
      'city'         => new sfValidatorString(array('max_length' => 100)),
      'addr'         => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'zip'          => new sfValidatorString(array('max_length' => 50)),
      'location_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Location'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('address[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Address';
  }

}
