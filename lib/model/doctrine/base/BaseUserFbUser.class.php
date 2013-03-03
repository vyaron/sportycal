<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('UserFbUser', 'doctrine');

/**
 * BaseUserFbUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $fb_user_id
 * @property timestamp $created_at
 * @property integer $in_birthday_cal
 * @property FbUser $FbUser
 * @property User $User
 * 
 * @method integer    getId()              Returns the current record's "id" value
 * @method integer    getUserId()          Returns the current record's "user_id" value
 * @method integer    getFbUserId()        Returns the current record's "fb_user_id" value
 * @method timestamp  getCreatedAt()       Returns the current record's "created_at" value
 * @method integer    getInBirthdayCal()   Returns the current record's "in_birthday_cal" value
 * @method FbUser     getFbUser()          Returns the current record's "FbUser" value
 * @method User       getUser()            Returns the current record's "User" value
 * @method UserFbUser setId()              Sets the current record's "id" value
 * @method UserFbUser setUserId()          Sets the current record's "user_id" value
 * @method UserFbUser setFbUserId()        Sets the current record's "fb_user_id" value
 * @method UserFbUser setCreatedAt()       Sets the current record's "created_at" value
 * @method UserFbUser setInBirthdayCal()   Sets the current record's "in_birthday_cal" value
 * @method UserFbUser setFbUser()          Sets the current record's "FbUser" value
 * @method UserFbUser setUser()            Sets the current record's "User" value
 * 
 * @package    evento
 * @subpackage model
 * @author     Yaron Biton
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUserFbUser extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('user_fb_user');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 8,
             ));
        $this->hasColumn('user_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('fb_user_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
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
        $this->hasColumn('in_birthday_cal', 'integer', 1, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '0',
             'notnull' => false,
             'autoincrement' => false,
             'length' => 1,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('FbUser', array(
             'local' => 'fb_user_id',
             'foreign' => 'id'));

        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id'));
    }
}