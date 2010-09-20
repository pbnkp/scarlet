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
 * Handles the rendering of layouts, views and partials.
 */
class View
{
    
    protected $_content = array(
        '__main__' => '',
    );
    
    
    protected $content;
    
    
    protected $Controller;
    protected $view_folder;
    
    
    protected $data;
    protected $params;
    
    
    /**
     * Initialises a new view whether it's a layout, view or partial. Don't call
     * this in your apps, use the provided helper functions instead.
     * 
     * @access public
     * @param object $Controller The controller object
     * @param string $view The view file to render
     * @param string $type The type of file we're rendering. Can be either 'view',
     *                      'layout' or 'partial'. Defaults to 'view'.
     * @return object
     */
    public function __construct($Controller, $view, $type = 'view', $content = '')
    {
        $this->Controller =& $Controller;
        $this->data =& $this->Controller->data;
        $this->params =& $this->Controller->params;
        
        $type = strtolower($type);
        switch ($type) {
            case 'view':
                list($controller, $action) = explode('/', $view);
                $this->view_folder = $controller;
                $viewfile = VIEWS . DS . $controller . DS . $action;
                break;
            
            case 'layout':
                $this->view_folder = 'layouts';
                $viewfile = LAYOUTS . DS . $view;
                break;
            
            case 'partial':
                list($controller, $action) = explode('/', $view);
                $viewfile = VIEWS . DS . $controller . DS . '_' . $action;
                $this->data = $content;
                break;
        }
        
        $viewfile .= '.php';
        
        if (!file_exists($viewfile)) {
            // We can't find the viewfile, so if we're in debug mode throw an exception
            if (Environment::getInstance()->isDebug()) {
                $prefix = ($type == 'partial') ? '_' : '';
                throw new \Exception("Missing template $controller/$prefix$action");
            } else {
                // If this is a view or layout then go 404
                if ($type != 'partial') {
                    echo file_get_contents(PUBLIC_DIR . DS . '404.html');
                    exit;
                }
            }
        }
        
        
        // If we've got this far then everything's good, so render the viewfile.
        if ($type != 'partial') {
            if (is_array($content)) {
                $this->_content = $content;
            } else {
                $this->_content['__main__'] = $content;
            }
            if (!isset($this->_content['__main__'])) $this->_content['__main__'] = '';
            $this->content =& $this->_content['__main__'];
        }
        
        ob_start();
        include($viewfile);
        $this->_content['__main__'] = ob_get_contents();
        ob_end_clean();
    }
    
    
    /**
     * Rendered content. If |$return_all| is set to |true| then all content,
     * including |content_for| values, is returned. Otherwise just the rendered
     * view is returned.
     * 
     * @access public
     * @param bool $return_all Do not set this if you are echoing a view or partial
     *                          content in a parent view
     * @return mixed
     */
    public function content($return_all=false)
    {
        if ($return_all) return $this->_content;
        return $this->_content['__main__'];
    }
    
    
    /**
     * A Rails inspired content_for view helper. Set the second parameter to store
     * content for later use, leave the second parameter (or set it to false) to
     * retrieve that content.
     * 
     * @access public
     * @param string $key The key to store this content under
     * @param mixed $value The content. Leave this unset if you are retrieving data
     * @return mixed
     */
    public function content_for($key, $value=false)
    {
        if ($value === false) {
            if (array_key_exists($key, $this->_content)) return $this->_content[$key];
            return false;
        }
        
        $this->_content[$key] = $value;
    }
    
    
    /**
     * Renders a partial. You can pass parameters to the partial through the
     * second parameter.
     * 
     * @access public
     * @param string $partial The name of the partial to load
     * @param array $data The data to pass to the partial (optional)
     * @return string The rendered partial
     */
    public function partial($partial, $data = array())
    {
        if (strpos($partial, '/') === false) {
            $partial = $this->view_folder . '/' . $partial;
        }
        
        $View = new View($this->Controller, $partial, 'partial', $data);
        return $View->content();
    }
    
}
