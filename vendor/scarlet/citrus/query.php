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
 *
 * It implements the Iterator interface so it's possible to iterate through the
 * returned records.
 */
class Query extends Iterator
{
    
    /**
     * The model that is creating this query.
     * 
     * @access private
     * @var object
     */
    private $_Model = false;
    
    
    /**
     * Keeps a record of paramaters associated with this query e.g. WHERE conditions, etc.
     * 
     * @access private
     * @var array
     **/
    private $_params = array(
        'action' => false,
        'limit' => false,
        'offset' => false,
        'select' => array(),
        'where' => array(),
    );


    /**
     * Keeps a record of the returned results.
     *
     * @access private
     * @var array
     */
    protected $_iterable = array();
    
    
    /**
     * The constructor.
     *
     * @access public
     * @param string $Model The model creating this query
     */
    public function __construct($Model=false)
    {
        $this->_Model = $Model;
    }
    
    
    /**
     * An alternative to 'new Query()' so that we can support chaining.
     *
     * @access public
     * @static
     * @param object $Model The model creating this query
     * @return object $this
     */
    public static function __new($Model=false)
    {
        return new Query($Model);
    }
    
    
    /**
     * Performs a simple SQL query, bypassing all ORM features. However, that
     * doesn't mean that it isn't actually used by the ORM.
     *
     * @access public
     * @param string $sql The SQL to perform
     * @param bool $autofetch Fetch the results immediately?
     * @return mixed
     */
    public function sql($sql, $all=false)
    {
        $q = Base::connectionManager()->getConnection()->query($sql);
        return ($all) ? $q->fetchAll() : $q;
    }


    /**
     * Executes the query.
     *
     * @access public
     * @return object $this
     */
    public function execute()
    {

        return $this;
    }



    /**
     * Choose which columns to return from the database. There is no need to add
     * the SELECT statement, this will be automatically by the ORM. You can only
     * call this method once per query. Calling this multiple times will simply
     * overwrite the previous SELECT statment.
     *
     * Usage:
     *      ->select()      // Returns all fields by default
     *      ->select(column_1, column_2, ... column_3)
     *
     * @access public
     * @final
     * @params string The columns to return. By default will return all columns
     * @return object $this
     */
    final public function select()
    {
        $this->_params['action'] = 'select';
        $columns = func_get_args();
        $this->_params['select'] = (empty($columns)) ? array('*') : $columns;
        return $this;
    }
    
    
    /**
     * Describes a table, mapping the returned columns into some sane defaults
     * that Citrus can then understand.
     *
     * @access public
     * @param string $table
     * @return array
     */
    public function describe($table)
    {
        $q = $this->sql("DESCRIBE `$table`", true);
        
        $primary = '';
        $columns = array();
        
        foreach ($q as $r) {
            $columns[$r['Field']] = array(
                'type' => $r['Type'],
                'null' => (strtolower($r['Null']) == 'no') ? false : true,
                'key' => (empty($r['Key'])) ? false : $r['Key'],
                'default' => $r['Default'],
            );
            
            if ($r['Key'] == 'PRI') $primary = $r['Field'];
        }
        
        return array($primary, $columns);
    }
    
    
    /**
     * Adds conditions to the SQL statement. By default we perform a "WHERE X=Y"
     * however, you can also perform:
     *      =, !=, >, <, like, between, is
     * 
     * Conditions can be set by:
     *      ->where("$your_column", "$value")           : matches $your_column = $value
     *      ->where("$your_column", "$operator $value") : matches $your_column $operator $value
     * 
     * In addition, you can also specify whether you are doing an "AND" or an
     * "OR" condition.
     *
     * @access  public
     * @param string $column The name of the column to perform the WHERE on
     * @param string $value The value of the column to match.
     * @param string $type Either an AND or OR. Defaults to AND.
     * @return  object $this For chaining
     */
    public function where($column, $value, $type='AND')
    {
        $operators = array('=', '!=', '>', '<', 'like', 'between', 'is');
        
        $operator = explode(' ', $value);
        $operator = strtolower($operator[0]);
        if (!in_array($operator, $operators)) {
            $operator = '=';
        } else {
            $value = explode(' ', $value);
            $operator = $value[0];
            unset($value[0]);
            $value = implode(' ', $value);
        }
        
        $this->_params['where'][] = array(
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'type' => $type,
        );
        
        return $this;
    }
    
    
    /**
     * Convenience method for where('column', 'value', 'AND')
     *
     * @access public
     * @final
     * @param string $columnName The name of the column to perform the WHERE statement on
     * @param string $value The value of the column to look up. By default this is
     *                          a = where, however you can prepend any operator to this
     *                          value (such as >, < or LIKE)
     * @return object $this
     */
    final public function andWhere($columnName, $value)
    {
        return $this->where($columnName, $value, 'AND');
    }
    
    
    /**
     * Convenience method for where('column', 'value', 'OR')
     *
     * @access public
     * @final
     * @param string $columnName The name of the column to perform the WHERE statement on
     * @param string $value The value of the column to look up. By default this is
     *                          a = where, however you can prepend any operator to this
     *                          value (such as >, < or LIKE)
     * @return object $this
     */
    final public function orWhere($columnName, $value)
    {
        return $this->where($columnName, $value, 'OR');
    }
    
    
    /**
     * Performs a SQL LIMIT. The LIMIT statement will be automatically added to the
     * query, simply state by how many results you want. You can only call this once
     * per query. Calling this multiple times will simply overwrite the previous
     * LIMIT statement.
     *
     * @access public
     * @final
     * @param int $limit The maximum number of results to return.
     * @return object $this
     */
    final public function limit($limit)
    {
        $this->_params['limit'] = $limit;
        return $this;
    }
    
    
    /**
     * Performs a SQL offset. The OFFSET statement will be automatically added to the
     * query, simply state by how much you want the results to be offset. You can
     * only call this once per query. Calling this multiple times will simply overwrite
     * the previous OFFSET statement.
     *
     * @access public
     * @final
     * @param int $offset The result set offset
     * @return object $this
     */
    final public function offset($offset)
    {
        $this->_params['offset'] = $offset;
        return $this;
    }
    
}
