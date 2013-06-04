<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Mailinglist', 'doctrine');

/**
 * BaseMailinglist
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $partner_id
 * @property integer $cal_id
 * @property integer $category_id
 * @property string $full_name
 * @property string $email
 * @property string $tz
 * @property timestamp $created_at
 * @property timestamp $deleted_at
 * @property Partner $Partner
 * @property Cal $Cal
 * @property Category $Category
 * @property Doctrine_Collection $MailinglistTask
 * 
 * @method integer             getId()              Returns the current record's "id" value
 * @method integer             getPartnerId()       Returns the current record's "partner_id" value
 * @method integer             getCalId()           Returns the current record's "cal_id" value
 * @method integer             getCategoryId()      Returns the current record's "category_id" value
 * @method string              getFullName()        Returns the current record's "full_name" value
 * @method string              getEmail()           Returns the current record's "email" value
 * @method string              getTz()              Returns the current record's "tz" value
 * @method timestamp           getCreatedAt()       Returns the current record's "created_at" value
 * @method timestamp           getDeletedAt()       Returns the current record's "deleted_at" value
 * @method Partner             getPartner()         Returns the current record's "Partner" value
 * @method Cal                 getCal()             Returns the current record's "Cal" value
 * @method Category            getCategory()        Returns the current record's "Category" value
 * @method Doctrine_Collection getMailinglistTask() Returns the current record's "MailinglistTask" collection
 * @method Mailinglist         setId()              Sets the current record's "id" value
 * @method Mailinglist         setPartnerId()       Sets the current record's "partner_id" value
 * @method Mailinglist         setCalId()           Sets the current record's "cal_id" value
 * @method Mailinglist         setCategoryId()      Sets the current record's "category_id" value
 * @method Mailinglist         setFullName()        Sets the current record's "full_name" value
 * @method Mailinglist         setEmail()           Sets the current record's "email" value
 * @method Mailinglist         setTz()              Sets the current record's "tz" value
 * @method Mailinglist         setCreatedAt()       Sets the current record's "created_at" value
 * @method Mailinglist         setDeletedAt()       Sets the current record's "deleted_at" value
 * @method Mailinglist         setPartner()         Sets the current record's "Partner" value
 * @method Mailinglist         setCal()             Sets the current record's "Cal" value
 * @method Mailinglist         setCategory()        Sets the current record's "Category" value
 * @method Mailinglist         setMailinglistTask() Sets the current record's "MailinglistTask" collection
 * 
 * @package    evento
 * @subpackage model
 * @author     Yaron Biton
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseMailinglist extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('mailinglist');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 8,
             ));
        $this->hasColumn('partner_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => true,
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
        $this->hasColumn('category_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('full_name', 'string', 128, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 128,
             ));
        $this->hasColumn('email', 'string', 256, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 256,
             ));
        $this->hasColumn('tz', 'string', 80, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 80,
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
        $this->hasColumn('deleted_at', 'timestamp', 25, array(
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
        $this->hasOne('Partner', array(
             'local' => 'partner_id',
             'foreign' => 'id'));

        $this->hasOne('Cal', array(
             'local' => 'cal_id',
             'foreign' => 'id'));

        $this->hasOne('Category', array(
             'local' => 'category_id',
             'foreign' => 'id'));

        $this->hasMany('MailinglistTask', array(
             'local' => 'id',
             'foreign' => 'mailinglist_id'));
    }
}