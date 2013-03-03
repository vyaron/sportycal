<?php

/**
 * Event form base class.
 *
 * @method Event getObject() Returns the current form's model object
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseEventForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'cal_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cal'), 'add_empty' => true)),
      'name'        => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormTextarea(),
      'image_path'  => new sfWidgetFormTextarea(),
      'location'    => new sfWidgetFormTextarea(),
      'tz'          => new sfWidgetFormInputText(),
      'starts_at'   => new sfWidgetFormDateTime(),
      'ends_at'     => new sfWidgetFormDateTime(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
      'address_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Address'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'cal_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cal'), 'required' => false)),
      'name'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description' => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'image_path'  => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'location'    => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'tz'          => new sfValidatorString(array('max_length' => 80, 'required' => false)),
      'starts_at'   => new sfValidatorDateTime(array('required' => false)),
      'ends_at'     => new sfValidatorDateTime(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(array('required' => false)),
      'updated_at'  => new sfValidatorDateTime(array('required' => false)),
      'address_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Address'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('event[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Event';
  }

}
