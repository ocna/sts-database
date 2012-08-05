<?php
/**
 *
 * @category    STS
 * @package     Core
 * @subpackage	Api
 */
namespace STS\Core\Api;
use STS\Core\User\UserDTOAssembler;
use STS\Domain\User;
use STS\Core\Api\DefaultAuthFacade;

class DefaultAuthFacade implements AuthFacade
{
    function authenticate($userName, $password)
    {
        if ($userName != 'muser') {
            throw new ApiException('User not found for given user name.', ApiException::FAILURE_USER_NOT_FOUND);
        }
        if ($password != 'abc123') {
            throw new ApiException('Credentials are invalid for given user.', ApiException::FAILURE_CREDENTIAL_INVALID);
        }
        $user = new User();
        $user->setId(1)->setEmail('member.user@email.com')->setUserName('muser')->setRole('member');
        return UserDTOAssembler::toDTO($user);
    }
    public static function getDefaultInstance()
    {
        return new DefaultAuthFacade();
    }
}
