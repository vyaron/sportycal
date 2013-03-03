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
	  'tz'    		=> new sfWidgetFormInputText(),
      //'starts_at'   => new sfWidgetFormDateTime(),
      //'ends_at'     => new sfWidgetFormDateTime(),
    ));

    // for sportYcal Master, show the TZ (for partners it is set by its default) 
    if (UserUtils::getPartnerIdMaster()) {
    	 unset($this['tz']);
    }
    
    $this->setValidators(array(
      'id'          => new sfValidatorInteger(array('required' => false)),
      'cal_id'      => new sfValidatorInteger(),
      'name'        => new sfValidatorString(array('max_length' => 255, 'required' => true)),
      'description' => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'location'    => new sfValidatorString(array('max_length' => 500, 'required' => false)),
	  'tz'          => new sfTimezoneValidator(array('required' => false)),
      'starts_at'   => new sfValidatorDateTime(array('required' => false)),
      'ends_at'     => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('event[%s]');
  	
  	

  }

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
  

}
