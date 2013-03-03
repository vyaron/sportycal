<?php

require_once 'D:\\php\\symfony\\lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin');
  }
  
  public function configureDoctrine(Doctrine_Manager $manager)
  {
    // Yaron: Comming through here, but it didnt help me, suppose to solve problems with reserved words used in table/col names
    //$manager->setAttribute(Doctrine::ATTR_QUOTE_IDENTIFIER, true);
  }
  
  
  
}
