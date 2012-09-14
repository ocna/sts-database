<?php
use STS\Domain\Location\Address;
use STS\Domain\Location\Region;
use STS\Domain\Location\Area;
use STS\Domain\School;
use STS\Core\School\SchoolDTOAssembler;
use STS\TestUtilities\SchoolTestCase;
use STS\TestUtilities\Location\AreaTestCase;
use STS\TestUtilities\Location\RegionTestCase;
use STS\TestUtilities\Location\AddressTestCase;

class SchoolDTOAssemblerTest extends SchoolTestCase
{
    /**
     * @test
     * @group core
     * @group school
     */
    public function getValidSchoolDTOFromSchoolDomainObject()
    {
        $region = new Region();
        $region->setName(RegionTestCase::NAME)->setLegacyId(RegionTestCase::LEGACY_ID);
        $area = new Area();
        $area->setName(AreaTestCase::NAME)->setLegacyId(AreaTestCase::LEGACY_ID)->setId(AreaTestCase::ID)
            ->setState(AreaTestCase::STATE)->setCity(AreaTestCase::CITY)->setRegion($region);
        $address = new Address();
        $address->setLineOne(AddressTestCase::LINE_ONE)->setLineTwo(AddressTestCase::LINE_TWO)
            ->setZip(AddressTestCase::ZIP)->setState(AddressTestCase::STATE)->setCity(AddressTestCase::CITY);
        $school = new School();
        $school->setId(SchoolTestCase::ID)->setLegacyId(SchoolTestCase::LEGACY_ID)->setName(SchoolTestCase::NAME)
            ->setType(SchoolTestCase::TYPE)->setNotes(SchoolTestCase::NOTES)->setArea($area)->setAddress($address);
        $schoolDTO = SchoolDTOAssembler::toDTO($school);
        $this->assertValidSchoolDto($schoolDTO);
    }
    /**
     * @test
     * @group core
     * @group school
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Instance of \STS\Domain\School not
     * provided.
     */
    public function throwExceptionIfSchoolDomainObjectIsNotPassed()
    {
        $schoolDTO = SchoolDTOAssembler::toDTO(null);
    }
}
