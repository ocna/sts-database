<?php
/**
 * Created by PhpStorm.
 * User: sandysmith
 * Date: 6/28/16
 * Time: 11:44 AM
 */
namespace STS\Core;


/**
 * Class Cache
 * @package STS\Core
 */
interface Cacheable
{
    /**
     * @param $id
     * @param $object
     */
    public function addToCache($id, $object);

    /**
     * @param $id
     * @return mixed
     */
    public function getFromCache($id);

    /**
     * @return void
     */
    public function bust();
}