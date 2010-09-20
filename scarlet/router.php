<?php

namespace Scarlet;

class Router
{
    
    private static $_instance;
    private $_default_controller = 'error';
    private $_default_action = 'index';
    
    
    public $request_uri;
    public $routes;
    private $controller, $controller_name;
    private $action, $id;
    private $params;
    public $route_found = false;
    
    
    private function __construct()
    {
        $request = $_SERVER['REQUEST_URI'];
        $pos = strpos($request, '?');
        if ($pos) $request = substr($request, 0, $pos);
        
        $this->request_uri = $request;
        $this->routes = array();
        $this->default_routes();
    }
    
    
    public static function getInstance()
    {
        if (isset(self::$_instance)) {
            return self::$_instance;
        }
        
        self::$_instance = new self();
        return self::$_instance;
    }
    
    
    public static function draw($lambda)
    {
        $lambda(self::getInstance());
    }
    
    
    public function getController($normalised = true)
    {
        if ($normalised) return 'App\Controllers\\' . Inflector::camelize($this->controller_name);
        return $this->controller;
    }
    
    
    public function getAction()
    {
        return Inflector::underscore($this->action);
    }
    
    
    public function root($target)
    {
        $target = $this->parse_target($target);
        $this->_default_controller = $target['controller'];
        $this->_default_action = $target['action'];
        $this->match('/', $target);
    }
    
    
    public function match($rule, $target=array(), $conditions=array())
    {
        //$rules = explode('(', $rule);
        $this->routes[$rule] = new Route($rule, $this->request_uri, $target, $conditions);
    }
    
    
    public function default_routes()
    {
        $this->root('home#index');
        #$this->match('/:controller');
        #$this->match('/:controller/:action');
        #$this->match('/:controller/:action/:id');
    }
    
    
    private function parse_target($target)
    {
        if (is_string($target)) {
            list($controller, $action) = explode('#', $target);
            $target = array('controller' => $controller, 'action' => $action);
        }
        
        return $target;
    }
    
    
    private function set_route($route)
    {
        $this->route_found = true;
        $params = $route->params;
        $this->controller = $params['controller']; unset($params['controller']);
        $this->action = $params['action']; unset($params['action']);
        $this->id = (isset($params['id'])) ? $params['id'] : false; 
        $this->params = array_merge($params, $_GET);
        
        if (empty($this->controller)) $this->controller = $this->_default_controller;
        if (empty($this->action)) $this->action = $this->_default_action;
        if (empty($this->id)) $this->id = null;
        
        $this->controller_name = $this->controller . '_controller';
    }
    
    
    public function execute()
    {
        foreach($this->routes as $route) {
            if ($route->is_matched) {
                $this->set_route($route);
                break;
            }
        }
    }
    
}
 
class Route {
    public $is_matched = false;
    public $params;
    public $url;
    private $conditions;

    function __construct($url, $request_uri, $target, $conditions)
    {
        $this->url = $url;
        $this->params = array();
        $this->conditions = $conditions;
        $p_names = array(); $p_values = array();
        
        preg_match_all('@:([\w]+)@', $url, $p_names, PREG_PATTERN_ORDER);
        $p_names = $p_names[0];
        
        $url_regex = preg_replace_callback('@:[\w]+@', array($this, 'regex_url'), $url);
        $url_regex .= '/?';
        
        if (preg_match('@^' . $url_regex . '$@', $request_uri, $p_values)) {
            array_shift($p_values);
            foreach($p_names as $index => $value)
                $this->params[substr($value,1)] = urldecode($p_values[$index]);
            
            foreach($target as $key => $value)
                $this->params[$key] = $value;
            
            $this->is_matched = true;
        }
        
        unset($p_names); unset($p_values);
    }
    
    
    function regex_url($matches)
    {
        $key = str_replace(':', '', $matches[0]);
        
        if (array_key_exists($key, $this->conditions)) {
            return '('.$this->conditions[$key].')';
        } else {
            return '([a-zA-Z0-9_\+\-%]+)';
        }
    }
}
