<?php
namespace STS\TestUtilities;

use STS\Core;

class MongoUtility
{
    public static function getDbConnection()
    {
        $configPath = APPLICATION_PATH . Core::CORE_CONFIG_PATH;
        $config = new \Zend_Config_Xml($configPath, 'all');
        $mongoConfig = $config->modules->default->db->mongodb;
        $auth = $mongoConfig->username ? $mongoConfig->username . ':' . $mongoConfig->password . '@' : '';
        $mongo = new \Mongo('mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/' . $mongoConfig->dbname);
        $mongoDb = $mongo->selectDB($mongoConfig->dbname);
        return $mongoDb;
    }
}
