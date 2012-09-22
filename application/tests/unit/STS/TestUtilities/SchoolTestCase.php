<?php
namespace STS\TestUtilities;
use STS\Domain\School;
use STS\TestUtilities\SchoolTestCase;
use STS\TestUtilities\Location\AddressTestCase;
use STS\TestUtilities\Location\RegionTestCase;
use STS\TestUtilities\Location\AreaTestCase;
use STS\Core\School\SchoolDto;

class SchoolTestCase extends \PHPUnit_Framework_TestCase
{
    const ID = '502314eec6464712c1e705cc';
    const LEGACY_ID = 14;
    const NAME = 'Michigan State University School of Medicine';
    const TYPE = 'School';
    const NOTES = 'This is an interesting note!';
    protected function getValidSchool()
    {
        $school = new School();
        $area = \Mockery::mock('STS\Domain\Location\Area');
        $address = \Mockery::mock('STS\Domain\Location\Address');
        $school->setId(self::ID)->setLegacyId(self::LEGACY_ID)->setName(self::NAME)->setType(self::TYPE)->setNotes(self::NOTES)->setArea($area)->setAddress($address);
        return $school;
    }
    protected function assertValidSchool($school)
    {
        $this->assertEquals($school->getId(), self::ID);
        $this->assertEquals($school->getLegacyId(), self::LEGACY_ID);
        $this->assertEquals($school->getName(), self::NAME);
        $this->assertEquals($school->getNotes(), self::NOTES);
        $this->assertInstanceOf('STS\Domain\Location\Area', $school->getArea());
        $this->assertInstanceOf('STS\Domain\Location\Address', $school->getAddress());
    }
    
    protected function getValidSchoolDto()
    {
        $dto = new SchoolDto(self::ID, self::LEGACY_ID, self::NAME, self::TYPE, self::NOTES, RegionTestCase::NAME,
                AreaTestCase::NAME, AddressTestCase::LINE_ONE, AddressTestCase::LINE_TWO,
                AddressTestCase::CITY, AddressTestCase::STATE, AddressTestCase::ZIP);
        return $dto;
    }
    protected function assertValidSchoolDto($dto)
    {
        $this->assertInstanceOf('STS\Core\School\SchoolDto', $dto);
        $this->assertTrue(is_string($dto->getId()));
        $this->assertEquals(self::ID, $dto->getId());
        $this->assertEquals(self::LEGACY_ID, $dto->getLegacyId());
        $this->assertEquals(self::NAME, $dto->getName());
        $this->assertEquals(RegionTestCase::NAME, $dto->getRegionName());
        $this->assertEquals(AreaTestCase::NAME, $dto->getAreaName());
        $this->assertEquals(AddressTestCase::LINE_ONE, $dto->getAddressLineOne());
        $this->assertEquals(AddressTestCase::LINE_TWO, $dto->getAddressLineTwo());
        $this->assertEquals(AddressTestCase::CITY, $dto->getAddressCity());
        $this->assertEquals(AddressTestCase::STATE, $dto->getAddressState());
        $this->assertEquals(AddressTestCase::ZIP, $dto->getAddressZip());
        $this->assertEquals(self::NOTES, $dto->getNotes());
    }
}
