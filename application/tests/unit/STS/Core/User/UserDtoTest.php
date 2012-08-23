<?php
use STS\Core\User\UserDTO;
use STS\TestUtilities\UserTestCase;
use STS\Domain\User;

class UserDtoTest extends UserTestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $userDto = new UserDTO(self::BASIC_USER_NAME, self::BASIC_USER_EMAIL, self::VALID_FIRST_NAME,
                        self::VALID_LAST_NAME, self::BASIC_USER_ROLE, self::VALID_LEGACY_ID,
                        self::ASSOCIATED_MEMBER_ID);
        $this->assertValidUserDto($userDto);
    }
    private function assertValidUserDto($userDto)
    {
        $this->assertEquals($userDto->getId(), self::BASIC_USER_NAME);
        $this->assertEquals($userDto->getAssociatedMemberId(), self::ASSOCIATED_MEMBER_ID);
        $this->assertEquals($userDto->getEmail(), self::BASIC_USER_EMAIL);
    }
}
