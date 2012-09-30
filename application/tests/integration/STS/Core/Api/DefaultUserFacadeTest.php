<?php
use STS\Core;
use STS\TestUtilities\UserTestCase;
use STS\Core\Api\DefaultUserFacade;

class DefaultUserFacadeTest extends UserTestCase
{
    /**
     * @test
     */
    public function validLoadUserById()
    {
        $facade = $this->loadFacadeInstance();
        $userDto = $facade->findUserById(self::BASIC_USER_NAME);
        $this->assertValidUserDto($userDto);
    }
    
    /**
     * @test
     */
    public function validLoadUserByEmail(){
        $facade = $this->loadFacadeInstance();
        $userDto = $facade->findUserByEmail(self::BASIC_USER_EMAIL);
        $this->assertValidUserDto($userDto);
    }
    
    /**
     * @test
     */
    public function getEmptyArrayForNonExistantEmail(){
        $facade = $this->loadFacadeInstance();
        $userDto = $facade->findUserByEmail(self::NEW_USER_EMAIL);
        $this->assertEmpty($userDto);
        $this->assertTrue(is_array($userDto));
    }
    
    /**
     * @test
     */
    public function validCreateObject()
    {
        $facade = $this->loadFacadeInstance();
        $this->assertInstanceOf('STS\Core\Api\DefaultUserFacade', $facade);
    }
    private function loadFacadeInstance()
    {
        $core = Core::getDefaultInstance();
        $facade = $core->load('UserFacade');
        return $facade;
    }
}
