<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('CategoryLinkUsage', 'doctrine');

/**
 * BaseCategoryLinkUsage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $category_link_id
 * @property integer $user_cal_id
 * @property timestamp $created_at
 * @property CategoryLink $CategoryLink
 * @property UserCal $UserCal
 * 
 * @method integer           getId()               Returns the current record's "id" value
 * @method integer           getCategoryLinkId()   Returns the current record's "category_link_id" value
 * @method integer           getUserCalId()        Returns the current record's "user_cal_id" value
 * @method timestamp         getCreatedAt()        Returns the current record's "created_at" value
 * @method CategoryLink      getCategoryLink()     Returns the current record's "CategoryLink" value
 * @method UserCal           getUserCal()          Returns the current record's "UserCal" value
 * @method CategoryLinkUsage setId()               Sets the current record's "id" value
 * @method CategoryLinkUsage setCategoryLinkId()   Sets the current record's "category_link_id" value
 * @method CategoryLinkUsage setUserCalId()        Sets the current record's "user_cal_id" value
 * @method CategoryLinkUsage setCreatedAt()        Sets the current record's "created_at" value
 * @method CategoryLinkUsage setCategoryLink()     Sets the current record's "CategoryLink" value
 * @method CategoryLinkUsage setUserCal()          Sets the current record's "UserCal" value
 * 
 * @package    evento
 * @subpackage model
 * @author     Yaron Biton
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCategoryLinkUsage extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('category_link_usage');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 8,
             ));
        $this->hasColumn('category_link_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('user_cal_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('created_at', 'timestamp', 25, array(
             'type' => 'timestamp',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '0000-00-00 00:00:00',
             'notnull' => true,
             'autoincrement' => false,
             'length' => 25,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('CategoryLink', array(
             'local' => 'category_link_id',
             'foreign' => 'id'));

        $this->hasOne('UserCal', array(
             'local' => 'user_cal_id',
             'foreign' => 'id'));
    }
}