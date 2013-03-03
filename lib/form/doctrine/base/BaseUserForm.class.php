<?php

/**
 * User form base class.
 *
 * @method User getObject() Returns the current form's model object
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'email'           => new sfWidgetFormInputText(),
      'pass'            => new sfWidgetFormInputText(),
      'full_name'       => new sfWidgetFormInputText(),
      'birthdate'       => new sfWidgetFormDate(),
      'country_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Country'), 'add_empty' => true)),
      'state_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('State'), 'add_empty' => true)),
      'city'            => new sfWidgetFormInputText(),
      'address'         => new sfWidgetFormInputText(),
      'zip_code'        => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'activation_key'  => new sfWidgetFormInputText(),
      'activation_date' => new sfWidgetFormDate(),
      'ref_user_id'     => new sfWidgetFormInputText(),
      'balance'         => new sfWidgetFormInputText(),
      'type'            => new sfWidgetFormChoice(array('choices' => array('SIMPLE' => 'SIMPLE', 'MASTER' => 'MASTER'))),
      'gender'          => new sfWidgetFormChoice(array('choices' => array('M' => 'M', 'F' => 'F'))),
      'last_login_date' => new sfWidgetFormDate(),
      'fb_code'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'email'           => new sfValidatorString(array('max_length' => 255)),
      'pass'            => new sfValidatorString(array('max_length' => 50)),
      'full_name'       => new sfValidatorString(array('max_length' => 255)),
      'birthdate'       => new sfValidatorDate(array('required' => false)),
      'country_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Country'), 'required' => false)),
      'state_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('State'), 'required' => false)),
      'city'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'address'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'zip_code'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'      => new sfValidatorDateTime(array('required' => false)),
      'updated_at'      => new sfValidatorDateTime(array('required' => false)),
      'activation_key'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'activation_date' => new sfValidatorDate(array('required' => false)),
      'ref_user_id'     => new sfValidatorInteger(array('required' => false)),
      'balance'         => new sfValidatorInteger(array('required' => false)),
      'type'            => new sfValidatorChoice(array('choices' => array(0 => 'SIMPLE', 1 => 'MASTER'), 'required' => false)),
      'gender'          => new sfValidatorChoice(array('choices' => array(0 => 'M', 1 => 'F'))),
      'last_login_date' => new sfValidatorDate(array('required' => false)),
      'fb_code'         => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'User';
  }

}
