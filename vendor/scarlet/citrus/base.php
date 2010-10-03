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
 * The core Citrus class. It's used for setting up the environment and configuration
 * of the ORM. You shouldn't really be calling this class in your application code.
 */
class Base
{

    /**
     * The instance for the singleton.
     *
     * @access private
     * @static
     * @var object
     */
    private static $_instance;


    /**
     * The cached database connection.
     *
     * @access private
     * @var resource
     */
    private $_db;


    /**
     * The settings to use to connect to the database.
     *
     * @access private
     * @var array
     */
    private $connectionSettings = array();


    /**
     * This class is a singleton. Use getInstance();
     *
     * @access private
     */
    private function __construct()
    {

    }


    /**
     * This class is a singleton. Use this method instead of __construct();
     *
     * @access public
     * @static
     * @return object $this
     */
    public static function getInstance()
    {
        if (isset(self::$_instance)) {
            return self::$_instance;
        }

        self::$_instance = new self();
        return self::$_instance;
    }


    /**
     * Use this method to set up Citrus. This method expects an anonymous function
     * to be passed.
     *
     * @access public
     * @static
     * @param function $lambda
     * @return void
     */
    public static function config($lambda)
    {
        $lambda(self::getInstance());
    }


    /**
     * Adds a database connection.
     *
     * @access public
     * @param string $environment The environment to use this connection in. Can
     *                              be either 'development', 'production' or 'test'
     * @param array $connection The database connection
     * @return void
     * @todo Provide support for different environments and a MySQL cluster setup
     */
    public function addConnection($environment, $connection)
    {
        $dsn = "{$connection['adapter']}:dbname={$connection['database']};host={$connection['host']};charset={$connection['encoding']}";
        $username = $connection['username'];
        $password = $connection['password'];

        $options = $connection;
        unset($options['adapter'], $options['database'], $options['host'], $options['encoding'], $options['username'], $options['password']);
        
        $this->_db = new \PDO($dsn, $username, $password, $options);
    }


    /**
     * Returns the currently active database connection.
     *
     * @access public
     * @return resource
     */
    public function getConnection()
    {
        return $this->_db;
    }


    /**
     * Get the information about the table that we're working with. Only needs to
     * be called once, usually when we're creating the object.
     *
     * @access public
     * @param string $table The table to enumerate
     * @return array
     */
    public function enumerateTable($table)
    {
        return Query::__new()->describe($table);
    }

}
