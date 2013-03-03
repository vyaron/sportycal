<?php

/**
 * Cal form.
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CalForm extends BaseCalForm
{
  /*
  private $underCategoryId;
  
  public function setUnderCategoryId($ctgId) {
    $rhis->underCategoryId = $ctgId;
  }*/
  
  public function configure()
  {

//    unset($this['id'], $this['by_user_id'], $this['access_key'],
//          $this['created_at'], $this['updated_at']);
/*
    unset($this['by_user_id'], $this['access_key'],
          $this['created_at'], $this['updated_at'], $this['image_path'], $this['primary_slogan']);
    
    $this->setWidget('category_id', new sfWidgetFormInputHidden());
    $this->widgetSchema['location'] = new sfWidgetFormInputText();    
*/    
/*
    $this->widgetSchema['image_path'] = new sfWidgetFormInputFile(array(
      'label' => 'Image',
    ));
    $this->validatorSchema['image_path'] = new sfValidatorFile(array(
      'required'   => false,
      'path'       => sfConfig::get('sf_upload_dir').'/cals',
      'mime_types' => 'web_images',
    ));

    $this->widgetSchema->setHelp('primary_slogan', 'Write something exciting about your Calendar');
*/
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'name'              => new sfWidgetFormInputText(array(), array('style' => 'width: 350px;')),
      'description'       => new sfWidgetFormTextarea(array(), array('rows' => 10, 'style'=>'width: 350px;')),
      'location'          => new sfWidgetFormInputText(array(), array('style' => 'width: 350px;')),
      'rate'              => new sfWidgetFormInputText(array(), array('style' => 'width: 30px;')),
      'category_id'       => new sfWidgetFormInputHidden()
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorInteger(array('required' => false)),
      'name'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'       => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'location'          => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'rate'              => new sfValidatorInteger(array('required' => false)),
      'category_id'       => new sfValidatorInteger(array('required' => true))
    ));
	
    $this->widgetSchema->setHelp('rate', 'Calendars with bigger rate will show up first in the list');
    
    $this->widgetSchema->setNameFormat('cal[%s]');  	
  	
  }
  public function updateObject($values = null) {

  	$cal = parent::updateObject($values);
  	
  	if ($cal->isNew()) {
  		$cal->setPartnerId(UserUtils::getPartnerIdMaster());
  		$cal->setByUserId(UserUtils::getLoggedInId());
  		$cal->setCreatedAt(date('Y-m-d h:i:s'));
  		$ctg = $cal->getCategory();
  		$cal->setCategoryIdsPath($ctg->getCategoryIdsPath());
  	}  	
  	$cal->setUpdatedAt(date('Y-m-d h:i:s'));
  	return $cal;
  
  }
  
  public function save($con = null)
  {
    
    //$this->getObject()->setCategoryId($this->underCategoryId);
/*
    if (file_exists($this->getObject()->getFile()))
    {
      unlink($this->getObject()->getFile());
    }
 
    $file = $this->getValue('file');
    $filename = sha1($file->getOriginalName()).$file->getExtension($file->getOriginalExtension());
    $file->save(sfConfig::get('sf_upload_dir').'/'.$filename);
*/
//    $this->getObject()->setByUserId(1);
    return parent::save($con);
  }
    
  
}
