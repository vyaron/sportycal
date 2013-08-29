<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('User', 'doctrine');

/**
 * BaseUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $email
 * @property string $pass
 * @property string $full_name
 * @property date $birthdate
 * @property integer $country_id
 * @property integer $state_id
 * @property string $city
 * @property string $address
 * @property string $zip_code
 * @property timestamp $created_at
 * @property timestamp $updated_at
 * @property string $activation_key
 * @property date $activation_date
 * @property integer $ref_user_id
 * @property integer $balance
 * @property enum $type
 * @property enum $gender
 * @property date $last_login_date
 * @property string $fb_code
 * @property string $tz
 * @property integer $is_subscribe
 * @property Country $Country
 * @property State $State
 * @property Doctrine_Collection $User
 * @property Doctrine_Collection $Cal
 * @property Doctrine_Collection $Category
 * @property Doctrine_Collection $City
 * @property Doctrine_Collection $Contact
 * @property Doctrine_Collection $Intel
 * @property Doctrine_Collection $Invitation
 * @property Doctrine_Collection $Mailinglist
 * @property Doctrine_Collection $PartnerUser
 * @property Doctrine_Collection $ShortUrl
 * @property Doctrine_Collection $UserBirthday
 * @property Doctrine_Collection $UserCal
 * @property Doctrine_Collection $UserCal_5
 * @property Doctrine_Collection $UserFbUser
 * @property Doctrine_Collection $UserSearch
 * @property Doctrine_Collection $Wix
 * 
 * @method integer             getId()              Returns the current record's "id" value
 * @method string              getEmail()           Returns the current record's "email" value
 * @method string              getPass()            Returns the current record's "pass" value
 * @method string              getFullName()        Returns the current record's "full_name" value
 * @method date                getBirthdate()       Returns the current record's "birthdate" value
 * @method integer             getCountryId()       Returns the current record's "country_id" value
 * @method integer             getStateId()         Returns the current record's "state_id" value
 * @method string              getCity()            Returns the current record's "city" value
 * @method string              getAddress()         Returns the current record's "address" value
 * @method string              getZipCode()         Returns the current record's "zip_code" value
 * @method timestamp           getCreatedAt()       Returns the current record's "created_at" value
 * @method timestamp           getUpdatedAt()       Returns the current record's "updated_at" value
 * @method string              getActivationKey()   Returns the current record's "activation_key" value
 * @method date                getActivationDate()  Returns the current record's "activation_date" value
 * @method integer             getRefUserId()       Returns the current record's "ref_user_id" value
 * @method integer             getBalance()         Returns the current record's "balance" value
 * @method enum                getType()            Returns the current record's "type" value
 * @method enum                getGender()          Returns the current record's "gender" value
 * @method date                getLastLoginDate()   Returns the current record's "last_login_date" value
 * @method string              getFbCode()          Returns the current record's "fb_code" value
 * @method string              getTz()              Returns the current record's "tz" value
 * @method integer             getIsSubscribe()     Returns the current record's "is_subscribe" value
 * @method Country             getCountry()         Returns the current record's "Country" value
 * @method State               getState()           Returns the current record's "State" value
 * @method Doctrine_Collection getUser()            Returns the current record's "User" collection
 * @method Doctrine_Collection getCal()             Returns the current record's "Cal" collection
 * @method Doctrine_Collection getCategory()        Returns the current record's "Category" collection
 * @method Doctrine_Collection getCity()            Returns the current record's "City" collection
 * @method Doctrine_Collection getContact()         Returns the current record's "Contact" collection
 * @method Doctrine_Collection getIntel()           Returns the current record's "Intel" collection
 * @method Doctrine_Collection getInvitation()      Returns the current record's "Invitation" collection
 * @method Doctrine_Collection getMailinglist()     Returns the current record's "Mailinglist" collection
 * @method Doctrine_Collection getPartnerUser()     Returns the current record's "PartnerUser" collection
 * @method Doctrine_Collection getShortUrl()        Returns the current record's "ShortUrl" collection
 * @method Doctrine_Collection getUserBirthday()    Returns the current record's "UserBirthday" collection
 * @method Doctrine_Collection getUserCal()         Returns the current record's "UserCal" collection
 * @method Doctrine_Collection getUserCal5()        Returns the current record's "UserCal_5" collection
 * @method Doctrine_Collection getUserFbUser()      Returns the current record's "UserFbUser" collection
 * @method Doctrine_Collection getUserSearch()      Returns the current record's "UserSearch" collection
 * @method Doctrine_Collection getWix()             Returns the current record's "Wix" collection
 * @method User                setId()              Sets the current record's "id" value
 * @method User                setEmail()           Sets the current record's "email" value
 * @method User                setPass()            Sets the current record's "pass" value
 * @method User                setFullName()        Sets the current record's "full_name" value
 * @method User                setBirthdate()       Sets the current record's "birthdate" value
 * @method User                setCountryId()       Sets the current record's "country_id" value
 * @method User                setStateId()         Sets the current record's "state_id" value
 * @method User                setCity()            Sets the current record's "city" value
 * @method User                setAddress()         Sets the current record's "address" value
 * @method User                setZipCode()         Sets the current record's "zip_code" value
 * @method User                setCreatedAt()       Sets the current record's "created_at" value
 * @method User                setUpdatedAt()       Sets the current record's "updated_at" value
 * @method User                setActivationKey()   Sets the current record's "activation_key" value
 * @method User                setActivationDate()  Sets the current record's "activation_date" value
 * @method User                setRefUserId()       Sets the current record's "ref_user_id" value
 * @method User                setBalance()         Sets the current record's "balance" value
 * @method User                setType()            Sets the current record's "type" value
 * @method User                setGender()          Sets the current record's "gender" value
 * @method User                setLastLoginDate()   Sets the current record's "last_login_date" value
 * @method User                setFbCode()          Sets the current record's "fb_code" value
 * @method User                setTz()              Sets the current record's "tz" value
 * @method User                setIsSubscribe()     Sets the current record's "is_subscribe" value
 * @method User                setCountry()         Sets the current record's "Country" value
 * @method User                setState()           Sets the current record's "State" value
 * @method User                setUser()            Sets the current record's "User" collection
 * @method User                setCal()             Sets the current record's "Cal" collection
 * @method User                setCategory()        Sets the current record's "Category" collection
 * @method User                setCity()            Sets the current record's "City" collection
 * @method User                setContact()         Sets the current record's "Contact" collection
 * @method User                setIntel()           Sets the current record's "Intel" collection
 * @method User                setInvitation()      Sets the current record's "Invitation" collection
 * @method User                setMailinglist()     Sets the current record's "Mailinglist" collection
 * @method User                setPartnerUser()     Sets the current record's "PartnerUser" collection
 * @method User                setShortUrl()        Sets the current record's "ShortUrl" collection
 * @method User                setUserBirthday()    Sets the current record's "UserBirthday" collection
 * @method User                setUserCal()         Sets the current record's "UserCal" collection
 * @method User                setUserCal5()        Sets the current record's "UserCal_5" collection
 * @method User                setUserFbUser()      Sets the current record's "UserFbUser" collection
 * @method User                setUserSearch()      Sets the current record's "UserSearch" collection
 * @method User                setWix()             Sets the current record's "Wix" collection
 * 
 * @package    evento
 * @subpackage model
 * @author     Yaron Biton
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUser extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('user');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 8,
             ));
        $this->hasColumn('email', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('pass', 'string', 50, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 50,
             ));
        $this->hasColumn('full_name', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('birthdate', 'date', 25, array(
             'type' => 'date',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('country_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('state_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('city', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('address', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('zip_code', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 255,
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
        $this->hasColumn('updated_at', 'timestamp', 25, array(
             'type' => 'timestamp',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '0000-00-00 00:00:00',
             'notnull' => true,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('activation_key', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('activation_date', 'date', 25, array(
             'type' => 'date',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('ref_user_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('balance', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '0',
             'notnull' => false,
             'autoincrement' => false,
             'length' => 8,
             ));
        $this->hasColumn('type', 'enum', 11, array(
             'type' => 'enum',
             'fixed' => 0,
             'unsigned' => false,
             'values' => 
             array(
              0 => 'SIMPLE',
              1 => 'MASTER',
              2 => 'PARTNER',
              3 => 'MAILINGLIST',
             ),
             'primary' => false,
             'default' => 'SIMPLE',
             'notnull' => false,
             'autoincrement' => false,
             'length' => 11,
             ));
        $this->hasColumn('gender', 'enum', 1, array(
             'type' => 'enum',
             'fixed' => 0,
             'unsigned' => false,
             'values' => 
             array(
              0 => 'M',
              1 => 'F',
             ),
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 1,
             ));
        $this->hasColumn('last_login_date', 'date', 25, array(
             'type' => 'date',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('fb_code', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('tz', 'string', 80, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 80,
             ));
        $this->hasColumn('is_subscribe', 'integer', 1, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             'length' => 1,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Country', array(
             'local' => 'country_id',
             'foreign' => 'id'));

        $this->hasOne('State', array(
             'local' => 'state_id',
             'foreign' => 'id'));

        $this->hasMany('User', array(
             'local' => 'id',
             'foreign' => 'ref_user_id'));

        $this->hasMany('Cal', array(
             'local' => 'id',
             'foreign' => 'by_user_id'));

        $this->hasMany('Category', array(
             'local' => 'id',
             'foreign' => 'by_user_id'));

        $this->hasMany('City', array(
             'local' => 'id',
             'foreign' => 'by_user_id'));

        $this->hasMany('Contact', array(
             'local' => 'id',
             'foreign' => 'by_user_id'));

        $this->hasMany('Intel', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Invitation', array(
             'local' => 'id',
             'foreign' => 'by_user_id'));

        $this->hasMany('Mailinglist', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('PartnerUser', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('ShortUrl', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('UserBirthday', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('UserCal', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('UserCal as UserCal_5', array(
             'local' => 'id',
             'foreign' => 'birthday_cal_for_user_id'));

        $this->hasMany('UserFbUser', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('UserSearch', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Wix', array(
             'local' => 'id',
             'foreign' => 'user_id'));
    }
}