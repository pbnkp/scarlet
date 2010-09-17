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
require_once SCARLET . DS . 'autoloader.php';
require_once SCARLET . DS . 'inflector.php';

$Environment = Environment::getInstance();

$Kernel = new Kernel($Environment);
$Kernel->handle();
