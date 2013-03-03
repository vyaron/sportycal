<?php

/**
 * Category form.
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CategoryForm extends BaseCategoryForm
{
  public function configure()
  {
  	/*
    unset(
      $this['image_path'], $this['by_user_id'],
      $this['approved_at'], $this['cals_count'],
       $this['category_ids_path'], $this['deleted']
    );
    */
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'name'              => new sfWidgetFormInputText(array(), array('style' => 'width: 300px;')),
      'rate'              => new sfWidgetFormInputText(array(), array('style' => 'width: 30px;')),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorInteger(array('required' => false)),
	  'parent_id'        => new sfValidatorInteger(array('required' => true)),
      'name'              => new sfValidatorString(array('max_length' => 255)),
      'rate'              => new sfValidatorInteger(array('required' => false)),
    ));

	$this->widgetSchema->setHelp('rate', 'Categories with bigger rate will show up first in the list');
    
    
    $this->widgetSchema->setNameFormat('category[%s]');
  	    
  }
  
  public function updateObject($values = null) {

  	$ctg = parent::updateObject($values);
  	
  	if ($ctg->isNew()) {
	  	$ctg->setCalsCount(0);
  		$ctg->setPartnerId(UserUtils::getPartnerIdMaster());
  		$ctg->setByUserId(UserUtils::getLoggedInId());
  	}  	
  	
  	return $ctg;
  
  }
  
}
