<?php
namespace STS\Core;

interface DbFactory {
    /**
     * @param \Zend_Config $config
     * @return mixed
     */
    public function getDb($config);
}