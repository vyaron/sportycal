<?php

/**
 * ShortUrl form.
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ShortUrlForm extends BaseShortUrlForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'hash'     	=> new sfWidgetFormInputText(array('label' => 'Text to show')),
      'url'         => new sfWidgetFormInputText(),
      'comment'     => new sfWidgetFormInputText(),
      'partner_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Partner'), 'add_empty' => true)),
      'label'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'partner_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Partner'), 'required' => false)),
      'url'         => new sfValidatorUrl(),
      'label'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'comment'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'hash'     	=> new sfValidatorDoctrineUnique(array('throw_global_error' => true, 'column' => 'hash', 'required' => true, 'model' => $this->getModelName(), 'column' => 'hash'), array('invalid' => __('This text allready exist'))),
      //'hash'     	=> new sfValidatorDoctrineChoice(array('required' => true, 'model' => $this->getModelName(), 'column' => 'hash'), array('invalid' => __('This text allready exist'))),
    ));
  	
  	
    $this->widgetSchema->setNameFormat('l[%s]');
    
  }
  
  
  public function updateObject($values = null) {

  	$object = parent::updateObject($values);
  	
  	$object->setCreatedAt(date('Y-m-d h:i:s'));
  	
  	return $object;
  
  }
}
