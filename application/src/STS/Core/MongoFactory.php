<?php
namespace STS\Core;

class MongoFactory implements DbFactory
{
    /**
     * @param \Zend_Config $config
     * @return mixed
     */
    public function getDb($config)
    {
        $mongoConfig    = $config->modules->default->db->mongodb;
        $auth           = $mongoConfig->username ? $mongoConfig->username . ':'
                            . $mongoConfig->password . '@' : '';
        $mongo_client_class = '\Mongo';

        if (class_exists('\MongoClient')) {
            $mongo_client_class = '\MongoClient';
        }
        /** @var \MongoClient $mongo */
        $mongo = new $mongo_client_class(
            'mongodb://' . $auth . $mongoConfig->host . ':' . $mongoConfig->port . '/'
            . $mongoConfig->dbname
        );

        return $mongo->selectDB($mongoConfig->dbname);
    }
}
