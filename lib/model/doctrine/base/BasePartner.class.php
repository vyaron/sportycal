<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Partner', 'doctrine');

/**
 * BasePartner
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $hash
 * @property string $name
 * @property string $tz
 * @property Doctrine_Collection $Alias
 * @property Doctrine_Collection $Cal
 * @property Doctrine_Collection $CalRequest
 * @property Doctrine_Collection $Category
 * @property Doctrine_Collection $Intel
 * @property Doctrine_Collection $PartnerDesc
 * @property Doctrine_Collection $PartnerUser
 * @property Doctrine_Collection $ShortUrl
 * @property Doctrine_Collection $UserCal
 * 
 * @method integer             getId()          Returns the current record's "id" value
 * @method string              getHash()        Returns the current record's "hash" value
 * @method string              getName()        Returns the current record's "name" value
 * @method string              getTz()          Returns the current record's "tz" value
 * @method Doctrine_Collection getAlias()       Returns the current record's "Alias" collection
 * @method Doctrine_Collection getCal()         Returns the current record's "Cal" collection
 * @method Doctrine_Collection getCalRequest()  Returns the current record's "CalRequest" collection
 * @method Doctrine_Collection getCategory()    Returns the current record's "Category" collection
 * @method Doctrine_Collection getIntel()       Returns the current record's "Intel" collection
 * @method Doctrine_Collection getPartnerDesc() Returns the current record's "PartnerDesc" collection
 * @method Doctrine_Collection getPartnerUser() Returns the current record's "PartnerUser" collection
 * @method Doctrine_Collection getShortUrl()    Returns the current record's "ShortUrl" collection
 * @method Doctrine_Collection getUserCal()     Returns the current record's "UserCal" collection
 * @method Partner             setId()          Sets the current record's "id" value
 * @method Partner             setHash()        Sets the current record's "hash" value
 * @method Partner             setName()        Sets the current record's "name" value
 * @method Partner             setTz()          Sets the current record's "tz" value
 * @method Partner             setAlias()       Sets the current record's "Alias" collection
 * @method Partner             setCal()         Sets the current record's "Cal" collection
 * @method Partner             setCalRequest()  Sets the current record's "CalRequest" collection
 * @method Partner             setCategory()    Sets the current record's "Category" collection
 * @method Partner             setIntel()       Sets the current record's "Intel" collection
 * @method Partner             setPartnerDesc() Sets the current record's "PartnerDesc" collection
 * @method Partner             setPartnerUser() Sets the current record's "PartnerUser" collection
 * @method Partner             setShortUrl()    Sets the current record's "ShortUrl" collection
 * @method Partner             setUserCal()     Sets the current record's "UserCal" collection
 * 
 * @package    evento
 * @subpackage model
 * @author     Yaron Biton
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePartner extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('partner');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 8,
             ));
        $this->hasColumn('hash', 'string', 100, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '',
             'notnull' => true,
             'autoincrement' => false,
             'length' => 100,
             ));
        $this->hasColumn('name', 'string', 100, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '',
             'notnull' => true,
             'autoincrement' => false,
             'length' => 100,
             ));
        $this->hasColumn('tz', 'string', 100, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 100,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Alias', array(
             'local' => 'id',
             'foreign' => 'partner_id'));

        $this->hasMany('Cal', array(
             'local' => 'id',
             'foreign' => 'partner_id'));

        $this->hasMany('CalRequest', array(
             'local' => 'id',
             'foreign' => 'partner_id'));

        $this->hasMany('Category', array(
             'local' => 'id',
             'foreign' => 'partner_id'));

        $this->hasMany('Intel', array(
             'local' => 'id',
             'foreign' => 'partner_id'));

        $this->hasMany('PartnerDesc', array(
             'local' => 'id',
             'foreign' => 'partner_id'));

        $this->hasMany('PartnerUser', array(
             'local' => 'id',
             'foreign' => 'partner_id'));

        $this->hasMany('ShortUrl', array(
             'local' => 'id',
             'foreign' => 'partner_id'));

        $this->hasMany('UserCal', array(
             'local' => 'id',
             'foreign' => 'partner_id'));
    }
}