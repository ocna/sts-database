<?php
/**
 *
 * @category    STS
 * @package     Core
 * @subpackage	Api
 */
namespace STS\Core\Api;
interface AuthFacade
{

    /**
     * Given an email and password, returns the authenticated userDto or throws
     * an exception
     * @api
     *
     * @param string $email
     * @param string $password
     * @return \STS\Core\User\UserDTO
     * @throws \STS\Core\Api\ApiException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function authenticate($email, $password);
}
