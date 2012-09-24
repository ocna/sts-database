<?php
use STS\TestUtilities\UserTestCase;
use STS\Core\Api\DefaultUserFacade;

class DefaultUserFacadeTest extends UserTestCase
{
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
    public function validCreateObject()
    {
        $facade = new DefaultUserFacade($this->getMockUserRepository());
        $this->assertInstanceOf('STS\Core\Api\DefaultUserFacade', $facade);
    }
    private function getMockUserRepository()
    {
        $user = $this->getValidUser();
        $schoolRepository = \Mockery::mock('STS\Core\User\MongoUserRepository');
        $schoolRepository->shouldReceive('load')->with(self::BASIC_USER_NAME)->andReturn($user);
        return $schoolRepository;
    }
}
