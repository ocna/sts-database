<?php
ini_set('display_exceptions', 1);
defined('ROOT_PATH') || define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application/'));
defined('VENDOR_PATH') || define('VENDOR_PATH', realpath(dirname(__FILE__) . '/../vendor/'));
set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH, VENDOR_PATH, get_include_path()
)));
require_once 'autoload.php';
spl_autoload_unregister(array(
    'Zend_Loader_Autoloader', 'autoload'
));
$config = new Zend_Config_Ini(APPLICATION_PATH . '/config/application.ini', 'all');
$application = new Zend_Application('all', $config);
$application->bootstrap()->run();
