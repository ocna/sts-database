<?php

namespace STS\Core\Api;

use STS\Core;
use STS\TestUtilities\UserTestCase;
use STS\Core\Api\DefaultUserFacade;

class DefaultUserFacadeTest extends UserTestCase
{

    /**
     * @test
     */
    public function validUpdateUser()
    {
        //givens
        $updatedFirstName = 'Test User Update';
        $facade = $this->loadFacadeInstance();
        $user = $facade->findUserById(self::ID);
        //whens
        $facade->updateUser(
            $user->getId(),
            $updatedFirstName,
            $user->getLastName(),
            $user->getEmail(),
            self::BASIC_USER_PASSWORD, 
            $user->getRole(),
            $user->getAssociatedMemberId()
        );

        //thens
        $updatedUserDto = $facade->findUserById(self::ID);
        $this->assertInstanceOf('STS\Core\User\UserDto', $updatedUserDto);
        $this->assertEquals($updatedFirstName, $updatedUserDto->getFirstName());

        //reset
        $facade->updateUser(
            $user->getId(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getEmail(),
            self::BASIC_USER_PASSWORD, 
            $user->getRole(),
            $user->getAssociatedMemberId()
        );
        $updatedUserDto = $facade->findUserById(self::ID);
        $this->assertValidUserDto($updatedUserDto);
    }
    
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
    public function validGetUserByMemberId()
    {
        $facade = $this->loadFacadeInstance();
        $userDto = $facade->getUserByMemberId(self::ASSOCIATED_MEMBER_ID);
        $this->assertValidUserDto($userDto);
    }
    

    /**
     * @test
     */
    public function validLoadUserByEmail()
    {
        $facade = $this->loadFacadeInstance();
        $userDto = $facade->findUserByEmail(self::BASIC_USER_EMAIL);
        $this->assertValidUserDto($userDto);
    }

    /**
     * @test
     */
    public function getEmptyArrayForNonExistantEmail()
    {
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
