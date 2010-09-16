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
 * The kernel is the heart of the Scarlet system. It manages an environment that
 * can host bundles.
 */
class Kernel
{
    
    /**
     * The application environment.
     * 
     * @access private
     * @var bool
     */
    private $_environment;
    
    
    /**
     * Debug status of the application.
     * 
     * @access private
     * @var bool
     */
    private $_debug;
    
    
    /**
     * Stores the status of the kernel.
     * 
     * @access private
     * @var bool
     */
    private $_booted;
    
    
    /**
     * Initialises the kernel.
     * 
     * @access public
     * @param Environment $environment The application environment
     * @return object $this
     */
    public function __construct(Environment $environment)
    {
        $this->_environment =& $environment;
        $this->_debug = $this->_environment->isDebug();
        $this->_booted = false;
        
        if ($this->_debug) {
            ini_set('display_errors', 1);
            error_reporting(-1);
        } else {
            ini_set('display_errors', 0);
        }
    }
    
    
    /**
     * 
     */
    public function isBooted()
    {
        return $this->_booted;
    }
    
    
    /**
     * 
     */
    public function boot()
    {
        if ($this->_booted) throw new \Exception('The kernel is already booted.');
        
        
        
        $this->_booted = true;
    }
    
    
    /**
     * Shutdowns the kernel. Mostly useful when running tests.
     * 
     * @access public
     * @return void
     */
    public function shutdown()
    {
        $this->_booted = false;
        
    }
    
    
    /**
     * Restarts the kernel. Mostly usefule when running tests.
     * 
     * @access public
     * @return void
     */
    public function restart()
    {
        $this->shutdown();
        $this->boot();
    }
    
    
    /**
     * 
     */
    public function handle()
    {
        if ($this->_booted == false) {
            $this->boot();
        }
        
        
    }
    
}
