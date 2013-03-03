<?php

/**
 * Category form base class.
 *
 * @method Category getObject() Returns the current form's model object
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCategoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'name'              => new sfWidgetFormInputText(),
      'image_path'        => new sfWidgetFormTextarea(),
      'by_user_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'approved_at'       => new sfWidgetFormDateTime(),
      'parent_id'         => new sfWidgetFormInputText(),
      'rate'              => new sfWidgetFormInputText(),
      'cals_count'        => new sfWidgetFormInputText(),
      'category_ids_path' => new sfWidgetFormInputText(),
      'deleted_at'        => new sfWidgetFormDateTime(),
      'address_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Address'), 'add_empty' => true)),
      'partner_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Partner'), 'add_empty' => true)),
      'is_public'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'              => new sfValidatorString(array('max_length' => 255)),
      'image_path'        => new sfValidatorString(array('required' => false)),
      'by_user_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'approved_at'       => new sfValidatorDateTime(array('required' => false)),
      'parent_id'         => new sfValidatorInteger(array('required' => false)),
      'rate'              => new sfValidatorInteger(array('required' => false)),
      'cals_count'        => new sfValidatorInteger(array('required' => false)),
      'category_ids_path' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'deleted_at'        => new sfValidatorDateTime(array('required' => false)),
      'address_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Address'), 'required' => false)),
      'partner_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Partner'), 'required' => false)),
      'is_public'         => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('category[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Category';
  }

}
