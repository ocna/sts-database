<?php
namespace STS\TestUtilities\Location;
use STS\Domain\Location\Region;

class RegionTestCase extends \PHPUnit_Framework_TestCase
{
    const NAME = 'Great Lakes';
    const LEGACY_ID = 10;
    protected function getValidRegion()
    {
        $region = new Region();
        $region->setName(self::NAME)->setLegacyId(self::LEGACY_ID);
        return $region;
    }

    public static function createValidRegion()
    {
        $regionTestCase = new RegionTestCase();
        return $regionTestCase->getValidRegion();
    }
    protected function assertValidRegion($region)
    {
        $this->assertInstanceOf('STS\Domain\Location\Region', $region);
        $this->assertEquals($this->getValidRegion(), $region);
    }
}
