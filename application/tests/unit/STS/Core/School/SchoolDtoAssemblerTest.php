<?php
use STS\Domain\Location\Address;
use STS\Domain\Location\Region;
use STS\Domain\Location\Area;
use STS\Domain\School;
use STS\Core\School\SchoolDtoAssembler;
use STS\TestUtilities\SchoolTestCase;
use STS\TestUtilities\Location\AreaTestCase;
use STS\TestUtilities\Location\RegionTestCase;
use STS\TestUtilities\Location\AddressTestCase;

class SchoolDtoAssemblerTest extends SchoolTestCase
{
    /**
     * @test
     */
    public function getValidSchoolDTOFromSchoolDomainObject()
    {
        $region = new Region();
        $region->setName(RegionTestCase::NAME)->setLegacyId(RegionTestCase::LEGACY_ID);
        $area = new Area();
        $area->setName(AreaTestCase::NAME)
            ->setLegacyId(AreaTestCase::LEGACY_ID)
            ->setId(AreaTestCase::ID)
            ->setState(AreaTestCase::STATE)
            ->setCity(AreaTestCase::CITY)
            ->setRegion($region);
        $address = new Address();
        $address->setAddress(AddressTestCase::ADDRESS);
        $school = new School();
        $school->setId(SchoolTestCase::ID)
            ->setLegacyId(SchoolTestCase::LEGACY_ID)
            ->setName(SchoolTestCase::NAME)
            ->setType(SchoolTestCase::TYPE)
            ->setIsInactive(SchoolTestCase::INACTIVE)
            ->setNotes(SchoolTestCase::NOTES)
            ->setArea($area)
            ->setAddress($address);
        $schoolDTO = SchoolDtoAssembler::toDTO($school);
        $this->assertValidSchoolDto($schoolDTO);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Instance of \STS\Domain\School not
     * provided.
     */
    public function throwExceptionIfSchoolDomainObjectIsNotPassed()
    {
        SchoolDtoAssembler::toDTO(null);
    }
}
