<?php
namespace STS\Domain\Member;

use STS\Domain\Member\PhoneNumber;

class PhoneNumberTest extends \PHPUnit_Framework_TestCase
{
    const VALID_PHONE_NUMBER = '3015551234';
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
}
