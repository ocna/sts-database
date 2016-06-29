<?php
/**
 * Created by PhpStorm.
 * User: sandysmith
 * Date: 6/28/16
 * Time: 5:25 AM
 */

namespace STS\Core;


/**
 * Class Cache
 * @package STS\Core
 */
class Cache implements Cacheable
{
    private static $cache = array();

    /**
     * @param $id
     * @param $object
     */
    public function addToCache($id, $object) {
        self::$cache[$id] = $object;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getFromCache($id) {
        return isset(self::$cache[$id]) ? self::$cache[$id] : null;
    }
}