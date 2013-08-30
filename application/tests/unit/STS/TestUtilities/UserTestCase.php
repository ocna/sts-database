<?php
namespace STS\TestUtilities;

use STS\Domain\User;

class UserTestCase extends \PHPUnit_Framework_TestCase
{
    const VALID_LEGACY_ID = 1;
    const BASIC_USER_EMAIL = 'member.user@email.com';
    const NEW_USER_EMAIL = 'new.user@email.com';
    const BASIC_USER_NAME = 'muser';
    const ID = 'muser';
    const SALT = 'f95299ac31b9b43d593d6165dc4d79e7'; //md5 of 'hambone'
    const PASSWORD = '64f5c419fb3ec946807544e7a6b40d16413cadc4'; //sha1 of salt+pw
    const BASIC_USER_PASSWORD = 'hambone';
    const BAD_BASIC_USER_NAME = 'notuser';
    const BAD_BASIC_USER_PASSWORD = 'badpass';
    const BASIC_USER_ROLE = 'admin';
    const VALID_FIRST_NAME = 'Member';
    const VALID_LAST_NAME = 'User';
    const ASSOCIATED_MEMBER_ID = '50234bc4fe65f50a9579a8cd';

    protected function getValidUser()
    {
        $user = new User();
        $user->setId(self::BASIC_USER_NAME)->setEmail(self::BASIC_USER_EMAIL)->setPassword(self::PASSWORD)
            ->setSalt(self::SALT)->setFirstName(self::VALID_FIRST_NAME)->setLastName(self::VALID_LAST_NAME)
            ->setLegacyId(self::VALID_LEGACY_ID)->setRole(self::BASIC_USER_ROLE)->setAssociatedMemberId(self::ASSOCIATED_MEMBER_ID);
        return $user;
    }

    public static function createValidUser()
    {
        $userTestCase = new UserTestCase();
        return $userTestCase->getValidUser();
    }
    protected function assertValidUser($user)
    {
        $this->assertInstanceOf('STS\Domain\User', $user);
        $this->assertEquals(self::BASIC_USER_NAME, $user->getId());
        $this->assertEquals(self::BASIC_USER_EMAIL, $user->getEmail());
        $this->assertEquals(self::PASSWORD, $user->getPassword());
        $this->assertEquals(self::SALT, $user->getSalt());
        $this->assertEquals(self::VALID_FIRST_NAME, $user->getFirstName());
        $this->assertEquals(self::VALID_LAST_NAME, $user->getLastName());
        $this->assertEquals(self::BASIC_USER_ROLE, $user->getRole());
        $this->assertEquals(self::VALID_LEGACY_ID, $user->getLegacyId());
        $this->assertEquals(self::ASSOCIATED_MEMBER_ID, $user->getAssociatedMemberId());
    }

    protected function assertValidUserDto($dto)
    {
        $this->assertInstanceOf('STS\Core\User\UserDto', $dto);
        $this->assertEquals(self::BASIC_USER_NAME, $dto->getId());
        $this->assertEquals(self::BASIC_USER_EMAIL, $dto->getEmail());
        $this->assertEquals(self::VALID_FIRST_NAME, $dto->getFirstName());
        $this->assertEquals(self::VALID_LAST_NAME, $dto->getLastName());
        $this->assertEquals(self::BASIC_USER_ROLE, $dto->getRole());
        $this->assertEquals(self::VALID_LEGACY_ID, $dto->getLegacyId());
        $this->assertEquals(self::ASSOCIATED_MEMBER_ID, $dto->getAssociatedMemberId());
    }
}
