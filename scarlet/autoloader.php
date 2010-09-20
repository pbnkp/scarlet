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
 * @license GPLv2 <http://www.gnu.org/licenses/gpl-2.0.html>
 */
namespace Scarlet;
/**
 * Scarlet relies on namespaces to define the layout of the codebase. This class
 * provides the ability to autoload those classes.
 */
class Autoloader
{
    
    /**
     * Converts a namespaced class into a file location and loads it.
     * 
     * @access public
     * @static
     * @param string $name The name of the class to load
     * @return void
     */
    public static function load($name)
    {
        $name = explode('\\', $name);
        if (count($name) < 2) throw new \Exception("Malformed class name");
        if (empty($name[0])) array_shift($name);
        $type = strtolower($name[0]);
        
        unset($name[0]);
        
        foreach ($name as $k => $v) {
            $name[$k] = Inflector::underscore($v);
        }
        $formatted_name = implode('/', $name);
        
        switch ($type) {
            case 'scarlet':
                $file = SCARLET . DS . $formatted_name;
                break;
            
            case 'core':
                $file = CORE . DS . $formatted_name;
                break;
            
            case 'app':
                $file = APP . DS . $formatted_name;
                break;
            
            default:
                // We assume it's a plugin
                $file = PLUGINS . DS . $formatted_name;
        }
        
        $file .= '.php';
        
        if (!file_exists($file)) {
            throw new \Exception("File '$file' not found");
        }
        
        require_once $file;
    }
    
}


spl_autoload_register('\Scarlet\Autoloader::load');
