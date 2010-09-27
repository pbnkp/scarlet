<?php
/**
 * Requires PHP 5.3
 *
 * Scarlet : An event driven PHP framework.
 * Copyright (c) 2010, Matt Kirman <matt@mattkirman.com>
 *
 * Licensed under the GPL license
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010, Matt Kirman <matt@mattkirman.com>
 * @package scarlet
 * @subpackage citrus
 * @license GPLv2 <http://www.gnu.org/licenses/gpl-2.0.html>
 */
namespace Citrus;
/**
 * The core Model class. This is the class that all Citrus models in your
 * application should inherit from.
 */
class Model
{

    /**
     * The cached database connection
     *
     * @access private
     * @var resource
     */
    private $_db;


    /**
     * The table that this model uses.
     *
     * @access protected
     * @var string
     */
    protected $_table;


    /**
     * A record of relationships that this model has.
     *
     * @access private
     * @var array
     */
    private $_relationships = array(
        'belongs_to' => array(),
        'has_one' => array(),
        'has_many' => array(),
        'has_and_belongs_to_many' => array(),
    );


    /**
     * A record of validations that this model has.
     *
     * @access private
     * @var array
     */
    private $_validations = array(
        'validate_format_of' => array(),
        'validate_length_of' => array(),
        'validate_presence_of' => array(),
        'validate_type_of' => array(),
    );


    /**
     * The columns that are in this table.
     *
     * @access private
     * @var object
     */
    private $_columns;


    /**
     * The primary key for this table, of false if there isn't one.
     *
     * @access private
     * @var string|false
     */
    private $_primaryKey;


    /**
     * If you define your own constructor for your model make sure you call
     * parent::__construct(). You should almost never have to modify the constructor,
     * put all of your code in the protected __setup() method instead.
     *
     * @access public
     */
    public function __construct()
    {
        // Get the name of the model that we're implementing. We use this to
        // create our default table name.
        $class = explode('\\', get_class($this));
        $table = array_pop($class);

        if (!isset($this->_table))
            $this->_table = \Scarlet\Inflector::underscore($table);

        // Enumerate the table so we know which fields exist and what type they are
        $etable = Base::getInstance()->enumerateTable($this->_table);

        if (method_exists($this, '__setup')) {
            $this->__setup();
        }
    }


    /**
     * Creates a new query for the model.
     *
     * @access private
     * @final
     * @return object Query
     */
    final public function query()
    {
        
    }


    /**
     * Add a belongs_to relationship to the model. For example, a comment belongs
     * to a user. Should only be called in __setup().
     *
     * @access protected
     * @final
     * @param string $model The model that this model belongs to.
     * @param array $options Any other relationship specific options.
     * @return void
     */
    final protected function belongsTo($model, $options=array())
    {
        // Default options for this type of relationship
        $defaults = array();
        $this->_relationships['belongs_to'][$model] = array_merge($defaults, $options);
    }


    /**
     * Add a has_one relationship to the model. For example, a user has one name.
     * Should only be called in __setup().
     *
     * @access protected
     * @final
     * @param string $model The model that this model has one of.
     * @param array $options Any other relationship specific options.
     * @return void
     */
    final protected function hasOne($model, $options=array())
    {
        // Default options for this type of relationship
        $defaults = array();
        $this->_relationships['has_one'][$model] = array_merge($defaults, $options);
    }


    /**
     * Add a has_many relationship to this model. For example, a user has many
     * emails. Should only be called in __setup().
     *
     * @access protected
     * @final
     * @param string $model The model that this model has many of.
     * @param array $options Any other relationship specific options.
     * @return void
     */
    final protected function hasMany($mode, $optins=array())
    {
        // Default options for this type of relationship
        $defaults = array();
        $this->_relationships['has_many'][$model] = array_merge($defaults, $options);
    }


    /**
     * Add a has_and_belongs_to_many relationship to this model. For example, an object
     * has many colours and a colour can be on many objects.
     *
     * @access protected
     * @final
     * @param string $mode The model that this has and belongs to many of.
     * @param array $options Any other relationship specific options.
     * @return void
     */
    final protected function hasAndBelongsToMany($model, $options=array())
    {
        // Default options for this type of relationship
        $defaults = array();
        $this->_relationships['has_and_belongs_to_many'][$model] = array_merge($defaults, $options);
    }

}
