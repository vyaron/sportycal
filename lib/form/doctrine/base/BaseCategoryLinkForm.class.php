<?php

/**
 * CategoryLink form base class.
 *
 * @method CategoryLink getObject() Returns the current form's model object
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCategoryLinkForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'category_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'add_empty' => true)),
      'type'        => new sfWidgetFormInputText(),
      'txt'         => new sfWidgetFormInputText(),
      'url'         => new sfWidgetFormTextarea(),
      'target_url'  => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'category_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'required' => false)),
      'type'        => new sfValidatorString(array('max_length' => 100)),
      'txt'         => new sfValidatorString(array('max_length' => 255)),
      'url'         => new sfValidatorString(),
      'target_url'  => new sfValidatorString(),
    ));

    $this->widgetSchema->setNameFormat('category_link[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CategoryLink';
  }

}
