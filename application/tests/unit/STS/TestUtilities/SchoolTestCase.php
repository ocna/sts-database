<?php
namespace STS\TestUtilities;
use STS\Domain\School;

class SchoolTestCase extends \PHPUnit_Framework_TestCase
{
    const ID = '502314eec6464712c1e705cc';
    const LEGACY_ID = 14;
    const NAME = 'Michigan State University School of Medicine';
    protected function getValidSchool()
    {
        $school = new School();
        $school->setId(self::ID)->setLegacyId(self::LEGACY_ID)->setName(self::NAME);
        return $school;
    }
    protected function assertValidSchool($school)
    {
        $this->assertEquals($this->getValidSchool(), $school);
    }
}
