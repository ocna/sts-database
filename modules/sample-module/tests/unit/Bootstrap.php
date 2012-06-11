<?php
$applicationRoot = dirname(dirname(dirname(__FILE__)));
$tests = $applicationRoot . DIRECTORY_SEPARATOR . 'tests/unit';
$modelSource = $applicationRoot . DIRECTORY_SEPARATOR . 'models';
$path = array(
    $tests , $modelSource
);
set_include_path(implode(PATH_SEPARATOR, $path) . PATH_SEPARATOR . get_include_path());
require_once 'Zend/Loader/Autoloader.php';
$autoloader = \Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('SampleModule_');