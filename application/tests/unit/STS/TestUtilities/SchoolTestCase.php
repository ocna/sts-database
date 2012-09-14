<?php
namespace STS\TestUtilities;
use STS\Domain\School;

class SchoolTestCase extends \PHPUnit_Framework_TestCase
{
    const ID = '502314eec6464712c1e705cc';
    const LEGACY_ID = 14;
    const NAME = 'Michigan State University School of Medicine';
    const TYPE = 'School';
    protected function getValidSchool()
    {
        $school = new School();
        $area = \Mockery::mock('STS\Domain\Location\Area');
        $school->setId(self::ID)->setLegacyId(self::LEGACY_ID)->setName(self::NAME)->setArea($area);
        return $school;
    }
    protected function assertValidSchool($school)
    {
        $this->assertEquals($school->getId(), self::ID);
        $this->assertEquals($school->getLegacyId(), self::LEGACY_ID);
        $this->assertEquals($school->getName(), self::NAME);
        $this->assertInstanceOf('STS\Domain\Location\Area', $school->getArea());
    }
}
