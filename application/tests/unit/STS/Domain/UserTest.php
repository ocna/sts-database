<?php
use STS\TestUtilities\UserTestCase;
use STS\Domain\User;

class UserTest extends UserTestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $user = $this->getValidUser();
        $this->assertValidUser($user);
    }
}
