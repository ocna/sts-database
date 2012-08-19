<?php
namespace STS\TestUtilities;
use STS\Domain\Location\Area;
use \Mockery;

class AreaTestCase extends \PHPUnit_Framework_TestCase
{
    const ID = '502d8ff40172cda7d649d41b';
    const NAME = 'MI-Grand Rapids';
    const LEGACY_ID = 10;
    const STATE = 'MI';
    const CITY = 'Grand Rapids';
    protected function getValidArea()
    {
        $area = new Area();
        $region = \Mockery::mock('STS\Domain\Location\Region');
        $area->setName(self::NAME)->setLegacyId(self::LEGACY_ID)->setId(self::ID)->setState(self::STATE)
            ->setCity(self::CITY)->setRegion($region);
        return $area;
    }
    protected function assertValidArea($area)
    {
        $this->assertInstanceOf('STS\Domain\Location\Area', $area);
        $this->assertEquals($area->getId(), self::ID);
        $this->assertEquals($area->getName(), self::NAME);
        $this->assertEquals($area->getLegacyId(), self::LEGACY_ID);
        $this->assertEquals($area->getState(), self::STATE);
        $this->assertEquals($area->getCity(), self::CITY);
        $this->assertInstanceOf('STS\Domain\Location\Region', $area->getRegion());
    }
    public function tearDown()
    {
        \Mockery::close();
    }
}
