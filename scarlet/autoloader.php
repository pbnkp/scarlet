<?php
/**
 * Requires PHP 5.3
 * 
 * Scarlet : Next generation e-commerce.
 * Copyright (c) 2010, Matt Kirman <matt@mattkirman.com>
 * 
 * Licensed under the GPL license
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright Copyright 2010, Matt Kirman <matt@mattkirman.com>
 * @package scarlet
 * @license GPLv2 <http://www.gnu.org/licenses/gpl-2.0.html>
 */
namespace Scarlet\Framework;
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
        if (count($name) < 3) throw new \Exception("Malformed class name");
        $type = strtolower($name[1]);
        
        unset($name[0], $name[1]);
        
        switch ($type) {
            case 'framework':
                $file = SCARLET . DS . Inflector::underscore(implode('/', $name));
                break;
            
            case 'core':
                $file = CORE . DS . Inflector::underscore(implode('/', $name));
                break;
        }
        
        $file .= '.php';
        
        if (!file_exists($file)) {
            throw new \Exception("File '$file' not found");
        }
        
        require_once $file;
    }
    
}


spl_autoload_register('\Scarlet\Framework\Autoloader::load');
