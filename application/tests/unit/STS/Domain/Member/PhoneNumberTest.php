<?php
namespace STS\Domain\Member;

use STS\Domain\Member\PhoneNumber;

class PhoneNumberTest extends \PHPUnit_Framework_TestCase
{
    const VALID_PHONE_NUMBER = '3015551234';
    const INVALID_PHONE_NUMBER = '301-123-1234';
    const VALID_TYPE = 'home';
    /**
     * @test
     */
    public function createValidObject()
    {
        $phoneNumber = new PhoneNumber(self::VALID_PHONE_NUMBER, self::VALID_TYPE);
        $this->assertEquals(self::VALID_PHONE_NUMBER, $phoneNumber->getNumber());
        $this->assertEquals(self::VALID_TYPE, $phoneNumber->getType());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Phone number must be 10 digits only.
     */
    public function itShouldThrowExceptionForInvalidPhoneNumber()
    {
        $phoneNumber = new PhoneNumber(self::INVALID_PHONE_NUMBER, self::VALID_TYPE);
    }
}
