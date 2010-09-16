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

/**
 * Use the DS to separate the directories in other defines. Also a useful shortcut.
 */
if (!defined('DS'))         define('DS', DIRECTORY_SEPARATOR);

/**
 * These defines should only be edited if you have installed Scarlet with a
 * non-default directory structure. When using custom settings be sure to use
 * the DS and do not add a trailing DS.
 */
if (!defined('ROOT'))       define('ROOT', dirname(dirname(__FILE__)));
if (!defined('APP'))        define('APP', ROOT . DS . 'app');
if (!defined('CORE'))       define('CORE', APP . DS . 'core');
if (!defined('CONFIG'))     define('CONFIG', ROOT . DS . 'config');
if (!defined('PLUGINS'))    define('PLUGINS', ROOT . DS . 'plugins');
if (!defined('PUBLIC'))     define('PUBLIC', ROOT . DS . 'public');
if (!defined('SCARLET'))    define('SCARLET', ROOT . DS . 'scarlet');


require_once SCARLET . DS . "bootstrap.php";
