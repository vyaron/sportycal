<?php

/**
 * Contact form.
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ContactForm extends BaseContactForm
{
  public function configure()
  {
  	$this->setWidgets(array(
  			'subject'   => new sfWidgetFormInputText(),
  			'sender_name'   => new sfWidgetFormInputText(),
  			'sender_email'   => new sfWidgetFormInputText(),
  			'phone'   => new sfWidgetFormInputText(),
  			'message'   => new sfWidgetFormInputText(),
  	));
  	
  	$this->setValidators(array(
  			'subject'   => new sfValidatorString(array('required' => true)),
  			'sender_name'   => new sfValidatorString(array('required' => true, 'min_length' => 3, 'trim' => true)),
  			'sender_email'   => new sfValidatorEmail(array('required' => true)),
  			'message'   => new sfValidatorString(array('required' => true)),
  			'phone'   => new sfValidatorString(array('required' => false)),
  			'ip_address'   => new sfValidatorString(array('required' => false)),
  			'created_at'   => new sfValidatorString(array('required' => false)),
  	));
  	
  	$this->widgetSchema->setNameFormat('contact[%s]');
  }
}
