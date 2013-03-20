<?php
/**
 * PHP Payment Library
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.opensource.org/licenses/bsd-license.php
 *
 * @category    PPL
 * @package     Test Bootstrap
 * @copyright   Copyright (c) 2013 PHP Payment Library
 * @since       2013-03-20
 */
/**
 * Bootstrap file for the PHP Payment Library.
 *
 * @category    PPL
 * @package     Test Bootstrap
 * @copyright   Copyright (c) 2013 PHP Payment Library
 * @since       2013-03-20
 */

define('BASE_PATH', dirname(dirname(realpath(__FILE__))));
define('LIBRARY_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'library');
set_include_path(implode(PATH_SEPARATOR, array(LIBRARY_PATH, get_include_path())));

require_once('Zend/Loader/Autoloader.php');
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('PPL');
$autoloader->setFallbackAutoloader(false);