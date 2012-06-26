<?php
/**
 *
 * @category    STS
 * @package     Core
 * @subpackage	Api
 */
namespace STS\Core\Api;
use STS\Core\Api\DefaultAuthFacade;
class DefaultAuthFacade implements AuthFacade
{

    public function authenticate($username, $password)
    {}

    public static function getDefaultInstance()
    {
        return new DefaultAuthFacade();
    }
}
