<?php
namespace STS\TestUtilities;
use STS\Domain\User;

class UserTestCase extends \PHPUnit_Framework_TestCase
{
    const VALID_USER_ID = 'muser';
    const BASIC_USER_EMAIL = 'member.user@email.com';
    const BASIC_USER_NAME = 'muser';
    const SALT = 'f95299ac31b9b43d593d6165dc4d79e7'; //md5 of 'hambone'
    const PASSWORD = '2a91cf94955ba45525412572c359e091'; //md5 of salt+pw
    const BASIC_USER_PASSWORD = 'abc123';
    const BAD_BASIC_USER_NAME = 'notuser';
    const BAD_BASIC_USER_PASSWORD = 'badpass';
    const BASIC_USER_ROLE = 'member';
    protected function getValidUser()
    {
        $user = new User();
        $user->setId(self::VALID_USER_ID)->setEmail(self::BASIC_USER_EMAIL)->setUserName(self::BASIC_USER_NAME)
            ->setPassword(self::PASSWORD)->setSalt(self::SALT)->setRole(self::BASIC_USER_ROLE);
        return $user;
    }
    protected function assertValidUser($user)
    {
        $this->assertInstanceOf('STS\Domain\User', $user);
        $this->assertEquals(self::VALID_USER_ID, $user->getId());
        $this->assertEquals(self::BASIC_USER_EMAIL, $user->getEmail());
        $this->assertEquals(self::BASIC_USER_NAME, $user->getUserName());
        $this->assertEquals(self::PASSWORD, $user->getPassword());
        $this->assertEquals(self::BASIC_USER_ROLE, $user->getRole());
        $this->assertEquals(self::SALT, $user->getSalt());
    }
}
