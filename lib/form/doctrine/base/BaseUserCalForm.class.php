<?php

/**
 * UserCal form base class.
 *
 * @method UserCal getObject() Returns the current form's model object
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUserCalForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'user_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'cal_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cal'), 'add_empty' => true)),
      'cal_type'    => new sfWidgetFormInputText(),
      'reminder1'   => new sfWidgetFormInputText(),
      'reminder2'   => new sfWidgetFormInputText(),
      'taken_at'    => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
      'partner_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Partner'), 'add_empty' => true)),
      'category_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'add_empty' => true)),
      'ip_address'  => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'user_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'cal_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cal'), 'required' => false)),
      'cal_type'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'reminder1'   => new sfValidatorInteger(array('required' => false)),
      'reminder2'   => new sfValidatorInteger(array('required' => false)),
      'taken_at'    => new sfValidatorDateTime(array('required' => false)),
      'updated_at'  => new sfValidatorDateTime(array('required' => false)),
      'partner_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Partner'), 'required' => false)),
      'category_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'required' => false)),
      'ip_address'  => new sfValidatorString(array('max_length' => 50, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('user_cal[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UserCal';
  }

}
