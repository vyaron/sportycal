<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Alias', 'doctrine');

/**
 * BaseAlias
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $alias
 * @property integer $category_id
 * @property integer $partner_id
 * @property Category $Category
 * @property Partner $Partner
 * 
 * @method integer  getId()          Returns the current record's "id" value
 * @method string   getAlias()       Returns the current record's "alias" value
 * @method integer  getCategoryId()  Returns the current record's "category_id" value
 * @method integer  getPartnerId()   Returns the current record's "partner_id" value
 * @method Category getCategory()    Returns the current record's "Category" value
 * @method Partner  getPartner()     Returns the current record's "Partner" value
 * @method Alias    setId()          Sets the current record's "id" value
 * @method Alias    setAlias()       Sets the current record's "alias" value
 * @method Alias    setCategoryId()  Sets the current record's "category_id" value
 * @method Alias    setPartnerId()   Sets the current record's "partner_id" value
 * @method Alias    setCategory()    Sets the current record's "Category" value
 * @method Alias    setPartner()     Sets the current record's "Partner" value
 * 
 * @package    evento
 * @subpackage model
 * @author     Yaron Biton
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseAlias extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('alias');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             'length' => 8,
             ));
        $this->hasColumn('alias', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 255,
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
        $this->hasColumn('partner_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 8,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Category', array(
             'local' => 'category_id',
             'foreign' => 'id'));

        $this->hasOne('Partner', array(
             'local' => 'partner_id',
             'foreign' => 'id'));
    }
}