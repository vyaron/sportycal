<?php

/**
 * ShortUrl form base class.
 *
 * @method ShortUrl getObject() Returns the current form's model object
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseShortUrlForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'cal_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Cal'), 'add_empty' => true)),
      'category_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'add_empty' => true)),
      'event_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Event'), 'add_empty' => true)),
      'user_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'partner_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Partner'), 'add_empty' => true)),
      'url'         => new sfWidgetFormTextarea(),
      'hash'        => new sfWidgetFormInputText(),
      'label'       => new sfWidgetFormInputText(),
      'comment'     => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'used_at'     => new sfWidgetFormDateTime(),
      'count_used'  => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'cal_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Cal'), 'required' => false)),
      'category_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'required' => false)),
      'event_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Event'), 'required' => false)),
      'user_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'partner_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Partner'), 'required' => false)),
      'url'         => new sfValidatorString(),
      'hash'        => new sfValidatorString(array('max_length' => 250)),
      'label'       => new sfValidatorString(array('max_length' => 255)),
      'comment'     => new sfValidatorString(array('max_length' => 255)),
      'created_at'  => new sfValidatorDateTime(array('required' => false)),
      'used_at'     => new sfValidatorDateTime(array('required' => false)),
      'count_used'  => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('short_url[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ShortUrl';
  }

}
