<?php
namespace STS\TestUtilities\Location;
use STS\Domain\Location\Address;
use \Mockery;

class AddressTestCase extends \PHPUnit_Framework_TestCase
{
    const ADDRESS = <<<QQQ
123 Main Street
Suite 200
Grand Rapids MI 12345
QQQ;

    /**
     * @return Address
     */
    protected function getValidAddress()
    {
        $address = new Address();
        $address->setAddress(self::ADDRESS);
        return $address;
    }

    public static function createValidAddress()
    {
        $addressTestCase = new AddressTestCase();
        return $addressTestCase->getValidAddress();
    }

    /**
     * @param Address $address
     */
    protected function assertValidAddress($address)
    {
        $this->assertInstanceOf('STS\Domain\Location\Address', $address);
        $this->assertEquals($address->getAddress(), self::ADDRESS);
    }
}
