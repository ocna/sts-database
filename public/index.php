<?php
require_once 'Zend/Application.php';
require_once 'Zend/Config/Ini.php';
require_once 'Zend/Loader/Autoloader.php';
ini_set('display_errors', 1);
// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
defined('ROOT_PATH') || define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
// Set include paths
set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH . '/src' , get_include_path()
)));
// Build Zend Application
$config = new \Zend_Config_Ini(APPLICATION_PATH . '/config/application.ini', 'all');
$application = new \Zend_Application('all', $config);
$application->bootstrap();
$application->run();

