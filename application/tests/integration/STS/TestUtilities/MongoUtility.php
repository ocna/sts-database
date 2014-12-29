<?php
namespace STS\TestUtilities;

use STS\Core;

class MongoUtility
{
    public static function getDbConnection()
    {
        $configPath = APPLICATION_PATH . Core::CORE_CONFIG_PATH;
        $config = new \Zend_Config_Xml($configPath, 'all');
        $factory = new Core\MongoFactory();
        $mongoDb = $factory->getDb($config);
        return $mongoDb;
    }
}
