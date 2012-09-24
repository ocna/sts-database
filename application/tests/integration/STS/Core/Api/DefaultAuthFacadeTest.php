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
    /**
     * @test
     */
    public function successfulAuthenticationOfFacilitatorUser()
    {
        $userDTO = $this->authFacade->authenticate('fuser', self::BASIC_USER_PASSWORD);
        $this->assertEquals($userDTO->getId(), 'fuser');
        $this->assertEquals($userDTO->getRole(), 'facilitator');
    }
    /**
     * @test
     */
    public function successfulAuthenticationOfCoordinatorUser()
    {
        $userDTO = $this->authFacade->authenticate('cuser', self::BASIC_USER_PASSWORD);
        $this->assertEquals($userDTO->getId(), 'cuser');
        $this->assertEquals($userDTO->getRole(), 'coordinator');
    }
    /**
     * @test
     */
    public function successfulAuthenticationOfAdminUser()
    {
        $userDTO = $this->authFacade->authenticate('auser', self::BASIC_USER_PASSWORD);
        $this->assertEquals($userDTO->getId(), 'auser');
        $this->assertEquals($userDTO->getRole(), 'admin');
    }
    private function loadFacadeInstance()
    {
        $core = Core::getDefaultInstance();
        $facade = $core->load('AuthFacade');
        return $facade;
    }
}
