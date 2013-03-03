<?php

/**
 * CalRequest form base class.
 *
 * @method CalRequest getObject() Returns the current form's model object
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCalRequestForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'user_cal_id' => new sfWidgetFormInputText(),
      'cal_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cal'), 'add_empty' => true)),
      'cal_type'    => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'partner_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Partner'), 'add_empty' => true)),
      'category_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'user_cal_id' => new sfValidatorInteger(array('required' => false)),
      'cal_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cal'), 'required' => false)),
      'cal_type'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'  => new sfValidatorDateTime(array('required' => false)),
      'partner_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Partner'), 'required' => false)),
      'category_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('cal_request[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CalRequest';
  }

}
