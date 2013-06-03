<?php

/**
 * Event form.
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EventForm extends BaseEventForm
{
	const EMPTY_DATETIME = '0000-00-00 00:00:00';
	
	public function updateDefaultsFromObject(){
		parent::updateDefaultsFromObject();
		
		$partner = UserUtils::getPartnerIfPartnerMaster();
		
		if ($this->getDefault('starts_at') == self::EMPTY_DATETIME) $this->setDefault('starts_at', '');
		if ($this->getDefault('ends_at') == self::EMPTY_DATETIME) $this->setDefault('ends_at', '');
		
		//on Edit
		$tzValue = $this->getDefault('tz');
		
		//Default partener TZ
		if ($partner && !$tzValue) $tzValue = $partner->getTz();
		
		if ($tzValue && GeneralUtils::getTZValue($tzValue) === null) $this->setDefault('tz_custom', $tzValue);
		
		if ($tzValue) $tzValue = GeneralUtils::getTZValue($tzValue);
		else if (UserUtils::getUserTzValue()) $tzValue = UserUtils::getUserTzValue();
		
		$this->setDefault('tz', $tzValue);
	}
	
  public function configure()  {
/*
    unset($this['created_at'], $this['updated_at'], $this['image_path']);
    
    $this->setWidget('cal_id', new sfWidgetFormInputHidden());
    
    $this->widgetSchema['location'] = new sfWidgetFormInputText();    
*/
  	
  	
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'cal_id'      => new sfWidgetFormInputHidden(),
      'name'        => new sfWidgetFormInputText(),
      'location'    => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormTextarea(array(), array('rows' => 15)),
	  'tz_custom'   => new sfWidgetFormInputText(),
      'tz' 			=> new sfWidgetFormSelect(array('choices' => GeneralUtils::getTZList(true), 'default' => 0)),
      'starts_at'   => new sfWidgetFormInputText(),
      'ends_at'     => new sfWidgetFormInputText(),
    ));

    // for sportYcal Master, show the TZ (for partners it is set by its default) 
    /*
    if (UserUtils::getPartnerIdMaster()) {
    	 unset($this['tz']);
    }
    */
    
    $this->setValidators(array(
      'id'          => new sfValidatorInteger(array('required' => false)),
      'cal_id'      => new sfValidatorInteger(),
      'name'        => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'description' => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'location'    => new sfValidatorString(array('max_length' => 500, 'required' => false)),
	  'tz_custom'   => new sfTimezoneValidator(array('required' => false)),
      'tz' 			=> new sfValidatorChoice(array('choices' => array_keys(GeneralUtils::getTZList(true)))),
      'starts_at'   => new sfValidatorString(array('required' => true)),
      'ends_at'     => new sfValidatorString(array('required' => true)),
      'countryCodes'  => new sfValidatorString(array('required' => false)),
      'languageCodes' => new sfValidatorString(array('required' => false)),
      'custom'     => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('event[%s]');
  }
	
  /*
  public function updateObject($values = null) {
  	$event = parent::updateObject($values);
  	//if ($event->isNew()) {
  		$partner = UserUtils::getPartnerIfPartnerMaster();
	    if ($partner) {
  			$event->setTz($partner->getTz());
  			//Utils::pp($event->getTz());			
	    }
  	//}  	
  	return $event;
  }
  */

}
