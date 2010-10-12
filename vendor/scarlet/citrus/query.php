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
class Query
{

    /**
     * The constructor.
     *
     * @access public
     */
    public function __construct()
    {
        
    }


    /**
     * An alternative to 'new Query()' so that we can support chaining.
     *
     * @access public
     * @static
     * @return object $this
     */
    public static function __new()
    {
        return new Query();
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

}
