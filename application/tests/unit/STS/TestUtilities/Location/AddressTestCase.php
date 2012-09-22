<?php
namespace STS\TestUtilities\Location;
use STS\Domain\Location\Address;
use \Mockery;

class AddressTestCase extends \PHPUnit_Framework_TestCase
{
    const LINE_ONE = '123 Main Street';
    const LINE_TWO = 'Suite 200';
    const ZIP = '12345';
    const STATE = 'MI';
    const CITY = 'Grand Rapids';
    protected function getValidAddress()
    {
        $address = new Address();
        $address->setLineOne(self::LINE_ONE)->setLineTwo(self::LINE_TWO)->setZip(self::ZIP)->setState(self::STATE)
            ->setCity(self::CITY);
        return $address;
    }
    protected function assertValidAddress($address)
    {
        $this->assertInstanceOf('STS\Domain\Location\Address', $address);
        $this->assertEquals($address->getLineOne(), self::LINE_ONE);
        $this->assertEquals($address->getLineTwo(), self::LINE_TWO);
        $this->assertEquals($address->getZip(), self::ZIP);
        $this->assertEquals($address->getState(), self::STATE);
        $this->assertEquals($address->getCity(), self::CITY);
    }
}
