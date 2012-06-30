<?php
use STS\Core\Api\DefaultAuthFacade;
class DefaultAuthFacadeTest extends PHPUnit_Framework_TestCase
{
    const BASIC_USER_EMAIL = 'member.user@email.com';
    const BASIC_USER_PASSWORD = 'abc123';
    const BAD_BASIC_USER_EMAIL = 'not.user@email.com';
    const BAD_BASIC_USER_PASSWORD = 'badpass';
    const BASIC_USER_ROLE = 'member';
    private $authFacade;

    protected function setUp()
    {
        $this->authFacade = $this->loadFacadeInstance();
    }

    /**
     * @test
     */
    public function successfulAuthenticationOfBasicUser()
    {
        $userDTO = $this->authFacade->authenticate(self::BASIC_USER_EMAIL, self::BASIC_USER_PASSWORD);
        $this->assertValidUserDTO($userDTO);
    }

    /**
     * @test
     * @expectedException \STS\Core\Api\ApiException
     * @expectedExceptionMessage User not found for given email.
     * @expectedExceptionCode -104
     */
    public function throwApiExceptionForInvalidUser()
    {
        $this->authFacade->authenticate(self::BAD_BASIC_USER_EMAIL, self::BASIC_USER_PASSWORD);
    }

    /**
     * @test
     * @expectedException \STS\Core\Api\ApiException
     * @expectedExceptionMessage Credentials are invalid for given user.
     * @expectedExceptionCode -101
     */
    public function throwApiExceptionForInvalidUserPassword()
    {
        $this->authFacade->authenticate(self::BASIC_USER_EMAIL, self::BAD_BASIC_USER_PASSWORD);
    }

    private function loadFacadeInstance()
    {
        return DefaultAuthFacade::getDefaultInstance();
    }

    private function assertValidUserDTO($userDTO)
    {
        $this->assertInstanceOf('\STS\Core\User\UserDTO', $userDTO);
        $this->assertEquals($userDTO->getEmail(), self::BASIC_USER_EMAIL);
        $this->assertEquals($userDTO->getRole(), self::BASIC_USER_ROLE);
    }
}