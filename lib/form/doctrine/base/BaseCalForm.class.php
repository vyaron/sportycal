<?php

/**
 * Cal form base class.
 *
 * @method Cal getObject() Returns the current form's model object
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCalForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'by_user_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'category_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'add_empty' => true)),
      'name'              => new sfWidgetFormInputText(),
      'primary_slogan'    => new sfWidgetFormInputText(),
      'description'       => new sfWidgetFormTextarea(),
      'location'          => new sfWidgetFormTextarea(),
      'image_path'        => new sfWidgetFormTextarea(),
      'access_key'        => new sfWidgetFormInputText(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
      'category_ids_path' => new sfWidgetFormInputText(),
      'deleted_at'        => new sfWidgetFormDateTime(),
      'rate'              => new sfWidgetFormInputText(),
      'partner_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Partner'), 'add_empty' => true)),
      'is_public'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'by_user_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'category_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'required' => false)),
      'name'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'primary_slogan'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'       => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'location'          => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'image_path'        => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'access_key'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'        => new sfValidatorDateTime(array('required' => false)),
      'updated_at'        => new sfValidatorDateTime(array('required' => false)),
      'category_ids_path' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'deleted_at'        => new sfValidatorDateTime(array('required' => false)),
      'rate'              => new sfValidatorInteger(array('required' => false)),
      'partner_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Partner'), 'required' => false)),
      'is_public'         => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('cal[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Cal';
  }

}
