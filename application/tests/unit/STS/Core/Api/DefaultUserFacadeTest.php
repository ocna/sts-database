<?php
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
        $oldUser = $this->getValidUser();
        $user = $this->getValidUser();
        $user->setFirstName($updatedFirstName);
        $userRepository = \Mockery::mock('STS\Core\User\MongoUserRepository', array('load'=>$oldUser, 'save'=>$user));
        $facade = new DefaultUserFacade($userRepository);
        //whens
        $updatedUserDto = $facade->updateUser(
            $user->getId(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getEmail(),
            self::BASIC_USER_PASSWORD, 
            $user->getRole(),
            $user->getAssociatedMemberId()
        );
        //thens
        $this->assertInstanceOf('STS\Core\User\UserDto', $updatedUserDto);
        $this->assertEquals($updatedFirstName, $updatedUserDto->getFirstName());
    }

    /**
     * @test
     */
    public function validLoadUserById()
    {
        $facade = new DefaultUserFacade($this->getMockUserRepository());
        $userDto = $facade->findUserById(self::BASIC_USER_NAME);
        $this->assertValidUserDto($userDto);
    }

    /**
     * @test
     */
    public function validLoadUserByEmail()
    {
        $facade = new DefaultUserFacade($this->getMockUserRepository());
        $userDto = $facade->findUserByEmail(self::BASIC_USER_EMAIL);
        $this->assertValidUserDto($userDto);
    }

    /**
     * @test
     */
    public function getEmptyArrayForNonExistantEmail()
    {
        $facade = new DefaultUserFacade($this->getMockUserRepository());
        $userDto = $facade->findUserByEmail(self::NEW_USER_EMAIL);
        $this->assertEmpty($userDto);
        $this->assertTrue(is_array($userDto));
    }


    /**
     * @test
     */
    public function validCreateObject()
    {
        $facade = new DefaultUserFacade($this->getMockUserRepository());
        $this->assertInstanceOf('STS\Core\Api\DefaultUserFacade', $facade);
    }
    private function getMockUserRepository()
    {
        $user = $this->getValidUser();
        $userRepository = \Mockery::mock('STS\Core\User\MongoUserRepository');
        $userRepository->shouldReceive('load')->with(self::BASIC_USER_NAME)->andReturn($user);
        $userRepository->shouldReceive('find')->with(array('email'=> self::BASIC_USER_EMAIL))->andReturn(array($user));
        $userRepository->shouldReceive('find')->with(array('email'=> self::NEW_USER_EMAIL))->andReturn(array());
        return $userRepository;
    }
}
