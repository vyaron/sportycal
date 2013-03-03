<?php

/**
 * User form.
 *
 * @package    evento
 * @subpackage form
 * @author     Yaron Biton
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UserForm extends BaseUserForm
{
  public function configure()
  {
    unset($this['created_at'], $this['updated_at']);
    
    $this->setWidget('cal_id', new sfWidgetFormInputHidden());
    
  }
}
