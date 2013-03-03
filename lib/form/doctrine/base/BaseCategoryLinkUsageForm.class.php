<?php

/**
 * CategoryLinkUsage form base class.
 *
 * @method CategoryLinkUsage getObject() Returns the current form's model object
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCategoryLinkUsageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'category_link_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('CategoryLink'), 'add_empty' => true)),
      'user_cal_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCal'), 'add_empty' => true)),
      'created_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'category_link_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('CategoryLink'), 'required' => false)),
      'user_cal_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCal'), 'required' => false)),
      'created_at'       => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('category_link_usage[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CategoryLinkUsage';
  }

}
