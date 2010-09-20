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
 * The core Controller class. This is the class that all controllers in your
 * application should inherit from.
 */
class Controller
{
    
    public $data = array();
    public $params = array();
    
    
    protected $layout = 'application';
    protected $view;
    
    
    protected $beforeFilters = array();
    
    
    /**
     * You shouldn't be using your own constructor. However, if you decide that
     * you need to, make sure that you run parent::__construct() before you run
     * any of your own code.
     * 
     * @access public
     * @return object
     */
    public function __construct()
    {
        if (method_exists($this, '__startup')) $this->__startup();
        
    }
    
    
    /**
     * You shouldn't be using your own destructor. However, if you decide that
     * you need to, make sure you run parent::__destruct() after your own code.
     * 
     * @access public
     * @return void
     */
    public function __destruct()
    {
        if (method_exists($this, '__shutdown')) $this->__shutdown();
        
    }
    
    
    /**
     * Returns the name of the layout that we want to sue for this particular action.
     * Defaults to 'application'.
     * 
     * @access public
     * @final
     * @return string The name of the layout
     */
    final public function getLayout()
    {
        return $this->layout;
    }
    
    
    /**
     * Returns the name of the view that we want to use for this particular action.
     * If Controller::view is undefined then we fall back to the name of the action.
     * 
     * @access public
     * @final
     * @return string The name of the view
     */
    final public function getView()
    {
        if (isset($this->view)) {
            if (strpos($this->view, '/') === false) {
                return Router::getInstance()->getController(false) . '/' . $this->view;
            }
            return $this->view;
        }
        
        $Router = Router::getInstance();
        return $Router->getController(false) . '/' . $Router->getAction();
    }
    
}
