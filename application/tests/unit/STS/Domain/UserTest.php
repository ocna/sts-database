<?php
use STS\Domain\User;

class UserTest extends PHPUnit_Framework_TestCase {
    const VALID_USER_ID = 1;
    const BASIC_USER_EMAIL = 'member.user@email.com';
    const BASIC_USER_NAME = 'muser';
    const BASIC_USER_PASSWORD = 'abc123';
    const BAD_BASIC_USER_EMAIL = 'not.user@email.com';
    const BAD_BASIC_USER_PASSWORD = 'badpass';
    const BASIC_USER_ROLE = 'member';
    /**
     * @test
     */
    public function createValidObject() {
        $id = self::VALID_USER_ID;
        $email = self::BASIC_USER_EMAIL;
        $userName = self::BASIC_USER_NAME;
        $password = self::BASIC_USER_PASSWORD;
        $role = self::BASIC_USER_ROLE;
        $user = new User();
        $user->setId($id)
                ->setEmail($email)
                ->setUserName($userName)
                ->setPassword($password)
                ->setRole($role);
        $this->verifyValidUser($user);
    }
    private function verifyValidUser($user) {
        $id = self::VALID_USER_ID;
        $email = self::BASIC_USER_EMAIL;
        $userName = self::BASIC_USER_NAME;
        $password = self::BASIC_USER_PASSWORD;
        $role = self::BASIC_USER_ROLE;
        $this->assertEquals($id, $user->getId());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($userName, $user->getUserName());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($role, $user->getRole());
    }
}
    