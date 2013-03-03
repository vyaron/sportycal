<?php

/**
 * Cal filter form base class.
 *
 * @package    evento
 * @subpackage filter
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCalFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'by_user_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'category_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Category'), 'add_empty' => true)),
      'name'           => new sfWidgetFormFilterInput(),
      'primary_slogan' => new sfWidgetFormFilterInput(),
      'description'    => new sfWidgetFormFilterInput(),
      'location'       => new sfWidgetFormFilterInput(),
      'image_path'     => new sfWidgetFormFilterInput(),
      'access_key'     => new sfWidgetFormFilterInput(),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'by_user_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'category_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Category'), 'column' => 'id')),
      'name'           => new sfValidatorPass(array('required' => false)),
      'primary_slogan' => new sfValidatorPass(array('required' => false)),
      'description'    => new sfValidatorPass(array('required' => false)),
      'location'       => new sfValidatorPass(array('required' => false)),
      'image_path'     => new sfValidatorPass(array('required' => false)),
      'access_key'     => new sfValidatorPass(array('required' => false)),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('cal_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Cal';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'by_user_id'     => 'ForeignKey',
      'category_id'    => 'ForeignKey',
      'name'           => 'Text',
      'primary_slogan' => 'Text',
      'description'    => 'Text',
      'location'       => 'Text',
      'image_path'     => 'Text',
      'access_key'     => 'Text',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
