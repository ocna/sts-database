<?php
use STS\Core;

use STS\TestUtilities\UserTestCase;
use STS\Core\Api\DefaultAuthFacade;

class DefaultAuthFacadeTest extends UserTestCase
{


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
        $userDTO = $this->authFacade->authenticate(self::BASIC_USER_NAME, self::BASIC_USER_PASSWORD);
        $this->assertValidUserDTO($userDTO);
    }
    /**
     * @test
     * @expectedException \STS\Core\Api\ApiException
     * @expectedExceptionMessage User not found for given user name.
     * @expectedExceptionCode -104
     */
    public function throwApiExceptionForInvalidUser()
    {
        $this->authFacade->authenticate(self::BAD_BASIC_USER_NAME, self::BASIC_USER_PASSWORD);
    }
    /**
     * @test
     * @expectedException \STS\Core\Api\ApiException
     * @expectedExceptionMessage Credentials are invalid for given user.
     * @expectedExceptionCode -101
     */
    public function throwApiExceptionForInvalidUserPassword()
    {
        $this->authFacade->authenticate(self::BASIC_USER_NAME, self::BAD_BASIC_USER_PASSWORD);
    }
    private function loadFacadeInstance()
    {
        $core = Core::getDefaultInstance();
        $facade = $core->load('AuthFacade');
        return $facade;
    }
    private function assertValidUserDTO($userDTO)
    {
        $this->assertInstanceOf('\STS\Core\User\UserDTO', $userDTO);
        $this->assertEquals($userDTO->getUserName(), self::BASIC_USER_NAME);
        $this->assertEquals($userDTO->getRole(), self::BASIC_USER_ROLE);
    }
}
