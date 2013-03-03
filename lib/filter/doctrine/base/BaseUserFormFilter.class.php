<?php

/**
 * User filter form base class.
 *
 * @package    evento
 * @subpackage filter
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'email'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'pass'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'full_name'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'birthdate'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'country_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Country'), 'add_empty' => true)),
      'state_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('State'), 'add_empty' => true)),
      'city'            => new sfWidgetFormFilterInput(),
      'address'         => new sfWidgetFormFilterInput(),
      'zip_code'        => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'activation_key'  => new sfWidgetFormFilterInput(),
      'activation_date' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'ref_user_id'     => new sfWidgetFormFilterInput(),
      'balance'         => new sfWidgetFormFilterInput(),
      'type'            => new sfWidgetFormChoice(array('choices' => array('' => '', 'SIMPLE' => 'SIMPLE', 'MASTER' => 'MASTER'))),
      'gender'          => new sfWidgetFormChoice(array('choices' => array('' => '', 'M' => 'M', 'F' => 'F'))),
      'last_login_date' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'email'           => new sfValidatorPass(array('required' => false)),
      'pass'            => new sfValidatorPass(array('required' => false)),
      'full_name'       => new sfValidatorPass(array('required' => false)),
      'birthdate'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'country_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Country'), 'column' => 'id')),
      'state_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('State'), 'column' => 'id')),
      'city'            => new sfValidatorPass(array('required' => false)),
      'address'         => new sfValidatorPass(array('required' => false)),
      'zip_code'        => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'activation_key'  => new sfValidatorPass(array('required' => false)),
      'activation_date' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'ref_user_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'balance'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'            => new sfValidatorChoice(array('required' => false, 'choices' => array('SIMPLE' => 'SIMPLE', 'MASTER' => 'MASTER'))),
      'gender'          => new sfValidatorChoice(array('required' => false, 'choices' => array('M' => 'M', 'F' => 'F'))),
      'last_login_date' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'User';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'email'           => 'Text',
      'pass'            => 'Text',
      'full_name'       => 'Text',
      'birthdate'       => 'Date',
      'country_id'      => 'ForeignKey',
      'state_id'        => 'ForeignKey',
      'city'            => 'Text',
      'address'         => 'Text',
      'zip_code'        => 'Text',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
      'activation_key'  => 'Text',
      'activation_date' => 'Date',
      'ref_user_id'     => 'Number',
      'balance'         => 'Number',
      'type'            => 'Enum',
      'gender'          => 'Enum',
      'last_login_date' => 'Date',
    );
  }
}
