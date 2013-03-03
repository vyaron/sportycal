<?php

/**
 * Intel form base class.
 *
 * @method Intel getObject() Returns the current form's model object
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseIntelForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'cal_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cal'), 'add_empty' => true)),
      'category_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'add_empty' => true)),
      'event_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Event'), 'add_empty' => true)),
      'user_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'partner_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Partner'), 'add_empty' => true)),
      'session_code' => new sfWidgetFormInputText(),
      'section'      => new sfWidgetFormInputText(),
      'action'       => new sfWidgetFormInputText(),
      'label'        => new sfWidgetFormInputText(),
      'value'        => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'user_cal_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCal'), 'add_empty' => true)),
      'ip_address'   => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'cal_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cal'), 'required' => false)),
      'category_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'required' => false)),
      'event_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Event'), 'required' => false)),
      'user_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'partner_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Partner'), 'required' => false)),
      'session_code' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'section'      => new sfValidatorString(array('max_length' => 255)),
      'action'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'label'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'value'        => new sfValidatorInteger(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(array('required' => false)),
      'user_cal_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCal'), 'required' => false)),
      'ip_address'   => new sfValidatorString(array('max_length' => 50, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('intel[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Intel';
  }

}
