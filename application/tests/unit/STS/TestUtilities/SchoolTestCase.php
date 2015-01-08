<?php
namespace STS\TestUtilities;

use STS\Domain\School;
use STS\TestUtilities\Location\AddressTestCase;
use STS\TestUtilities\Location\RegionTestCase;
use STS\TestUtilities\Location\AreaTestCase;
use STS\Core\School\SchoolDto;

class SchoolTestCase extends \PHPUnit_Framework_TestCase
{
    const ID = '509c47941f804c464d01959d';
    const LEGACY_ID = 14;
    const NAME = 'Michigan State University School of Medicine';
    const TYPE = 'Other';
    const INACTIVE = true;
    const NOTES = 'This is an interesting note!';
    const TYPE_KEY = 'TYPE_OTHER';

    protected function getValidSchool()
    {
        $school  = new School();
        $area    = AreaTestCase::createValidArea();
        $address = AddressTestCase::createValidAddress();
        $school->setId(self::ID)
            ->setLegacyId(self::LEGACY_ID)
            ->setName(self::NAME)
            ->setType(self::TYPE)
            ->setNotes(self::NOTES)
            ->setIsInactive(self::INACTIVE)
            ->setArea($area)
            ->setAddress($address);

        return $school;
    }

    public static function createValidSchool()
    {
        $schoolTestCase = new SchoolTestCase();

        return $schoolTestCase->getValidSchool();
    }

    /**
     * @param School $school
     */
    protected function assertValidSchool($school)
    {
        $this->assertEquals($school->getId(), self::ID);
        $this->assertEquals($school->getLegacyId(), self::LEGACY_ID);
        $this->assertEquals($school->getName(), self::NAME);
        $this->assertEquals(self::INACTIVE, $school->isInactive());
        $this->assertEquals($school->getNotes(), self::NOTES);
        $this->assertInstanceOf('STS\Domain\Location\Area', $school->getArea());
        $this->assertInstanceOf('STS\Domain\Location\Address', $school->getAddress());
    }

    protected function getValidSchoolDto()
    {
        $dto = new SchoolDto(
            self::ID,
            self::LEGACY_ID,
            self::NAME,
            self::TYPE,
            self::INACTIVE,
            self::NOTES,
            RegionTestCase::NAME,
            AreaTestCase::NAME,
            AddressTestCase::ADDRESS,
            AreaTestCase::ID,
            self::TYPE_KEY
        );

        return $dto;
    }

    /**
     * @param SchoolDto $dto
     */
    protected function assertValidSchoolDto($dto)
    {
        $this->assertInstanceOf('STS\Core\School\SchoolDto', $dto);
        $this->assertTrue(is_string($dto->getId()));
        $this->assertEquals(self::ID, $dto->getId());
        $this->assertEquals(self::LEGACY_ID, $dto->getLegacyId());
        $this->assertEquals(self::NAME, $dto->getName());
        $this->assertEquals(self::TYPE_KEY, $dto->getTypeKey());
        $this->assertEquals(self::INACTIVE, $dto->isInactive());
        $this->assertEquals(AreaTestCase::ID, $dto->getAreaId());
        $this->assertEquals(RegionTestCase::NAME, $dto->getRegionName());
        $this->assertEquals(AreaTestCase::NAME, $dto->getAreaName());
        $this->assertEquals(AddressTestCase::ADDRESS, $dto->getAddress());
        $this->assertEquals(self::NOTES, $dto->getNotes());
    }
}
