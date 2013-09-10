<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Wix', 'doctrine');

/**
 * BaseWix
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $cal_id
 * @property string $instance_code
 * @property string $comp_code
 * @property string $locale
 * @property integer $upcoming
 * @property string $line_color
 * @property string $text_color
 * @property string $bg_color
 * @property decimal $bg_opacity
 * @property timestamp $created_at
 * @property timestamp $updated_at
 * @property User $User
 * @property Cal $Cal
 * 
 * @method integer   getId()            Returns the current record's "id" value
 * @method integer   getUserId()        Returns the current record's "user_id" value
 * @method integer   getCalId()         Returns the current record's "cal_id" value
 * @method string    getInstanceCode()  Returns the current record's "instance_code" value
 * @method string    getCompCode()      Returns the current record's "comp_code" value
 * @method string    getLocale()        Returns the current record's "locale" value
 * @method integer   getUpcoming()      Returns the current record's "upcoming" value
 * @method string    getLineColor()     Returns the current record's "line_color" value
 * @method string    getTextColor()     Returns the current record's "text_color" value
 * @method string    getBgColor()       Returns the current record's "bg_color" value
 * @method decimal   getBgOpacity()     Returns the current record's "bg_opacity" value
 * @method timestamp getCreatedAt()     Returns the current record's "created_at" value
 * @method timestamp getUpdatedAt()     Returns the current record's "updated_at" value
 * @method User      getUser()          Returns the current record's "User" value
 * @method Cal       getCal()           Returns the current record's "Cal" value
 * @method Wix       setId()            Sets the current record's "id" value
 * @method Wix       setUserId()        Sets the current record's "user_id" value
 * @method Wix       setCalId()         Sets the current record's "cal_id" value
 * @method Wix       setInstanceCode()  Sets the current record's "instance_code" value
 * @method Wix       setCompCode()      Sets the current record's "comp_code" value
 * @method Wix       setLocale()        Sets the current record's "locale" value
 * @method Wix       setUpcoming()      Sets the current record's "upcoming" value
 * @method Wix       setLineColor()     Sets the current record's "line_color" value
 * @method Wix       setTextColor()     Sets the current record's "text_color" value
 * @method Wix       setBgColor()       Sets the current record's "bg_color" value
 * @method Wix       setBgOpacity()     Sets the current record's "bg_opacity" value
 * @method Wix       setCreatedAt()     Sets the current record's "created_at" value
 * @method Wix       setUpdatedAt()     Sets the current record's "updated_at" value
 * @method Wix       setUser()          Sets the current record's "User" value
 * @method Wix       setCal()           Sets the current record's "Cal" value
 * 
 * @package    evento
 * @subpackage model
 * @author     Yaron Biton
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseWix extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('wix');
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
             'notnull' => false,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('cal_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('instance_code', 'string', 512, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 512,
             ));
        $this->hasColumn('comp_code', 'string', 512, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 512,
             ));
        $this->hasColumn('locale', 'string', 16, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 16,
             ));
        $this->hasColumn('upcoming', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('line_color', 'string', 7, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 7,
             ));
        $this->hasColumn('text_color', 'string', 7, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 7,
             ));
        $this->hasColumn('bg_color', 'string', 7, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 7,
             ));
        $this->hasColumn('bg_opacity', 'decimal', 10, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 10,
             ));
        $this->hasColumn('created_at', 'timestamp', 25, array(
             'type' => 'timestamp',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('updated_at', 'timestamp', 25, array(
             'type' => 'timestamp',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $this->hasOne('Cal', array(
             'local' => 'cal_id',
             'foreign' => 'id'));
    }
}