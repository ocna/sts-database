<?php
namespace STS\TestUtilities;
use STS\Domain\Location\Area;
use STS\Domain\Member;
use STS\Core\Member\MemberDto;
use STS\TestUtilities\Location\AddressTestCase;
use STS\Domain\Location\Region;

class MemberTestCase extends \PHPUnit_Framework_TestCase
{
    const ID = '50234bc4fe65f50a9579a8cd';
    const LEGACY_ID = 0;
    const FIRST_NAME = 'Member';
    const LAST_NAME = 'User';
    const TYPE = 'Survivor';
    const NOTES = 'This is an interesting note!';
    const STATUS = 'Deceased';
    const ASSOCIATED_USER_ID = 'muser';
    protected function getValidMember()
    {
        $member = new Member();
        $address = \Mockery::mock('STS\Domain\Location\Address');
        $member->setId(self::ID)->setLegacyId(self::LEGACY_ID)->setFirstName(self::FIRST_NAME)
            ->setLastName(self::LAST_NAME)->setNotes(self::NOTES)->setStatus(self::STATUS)->setType(self::TYPE)
            ->setAddress($address)->setAssociatedUserId(self::ASSOCIATED_USER_ID);
        foreach ($this->getTestAreas() as $area) {
            $member->canPresentForArea($area);
            $member->canCoordinateForArea($area);
            $member->canFacilitateForArea($area);
        }
        return $member;
    }
    protected function assertValidMember($member)
    {
        $this->assertInstanceOf('STS\Domain\Member', $member);
        $expectedMember = $this->getValidMember();
        $expectedMember->setAddress($member->getAddress());
        $this->assertEquals($expectedMember, $member);
        $this->assertEquals(self::TYPE, $member->getType());
        $this->assertEquals(self::STATUS, $member->getStatus());
        $this->assertTrue($member->isDeceased());
        $this->assertInstanceOf('STS\Domain\Location\Address', $member->getAddress());
    }
    protected function getValidMemberDto()
    {
        $memberDto = new MemberDto(self::ID, self::LEGACY_ID, self::FIRST_NAME, self::LAST_NAME, self::TYPE,
                        self::NOTES, self::STATUS, AddressTestCase::LINE_ONE, AddressTestCase::LINE_TWO,
                        AddressTestCase::CITY, AddressTestCase::STATE, AddressTestCase::ZIP, self::ASSOCIATED_USER_ID,
                        $this->getValidPresentsForAreasArray(), $this->getValidFacilitatesForAreasArray(),
                        $this->getValidCoordinatesForAreasArray(), $this->getValidCoordinatesForRegionsArray());
        return $memberDto;
    }
    protected function assertValidMemberDto($dto)
    {
        $this->assertInstanceOf('STS\Core\Member\MemberDto', $dto);
        $this->assertTrue(is_string($dto->getId()));
        $this->assertEquals(self::ID, $dto->getId());
        $this->assertEquals(self::LEGACY_ID, $dto->getLegacyId());
        $this->assertEquals(self::FIRST_NAME, $dto->getFirstName());
        $this->assertEquals(self::LAST_NAME, $dto->getLastName());
        $this->assertEquals(self::TYPE, $dto->getType());
        $this->assertEquals(self::NOTES, $dto->getNotes());
        $this->assertEquals(self::STATUS, $dto->getStatus());
        $this->assertTrue($dto->isDeceased());
        $this->assertEquals(AddressTestCase::LINE_ONE, $dto->getAddressLineOne());
        $this->assertEquals(AddressTestCase::LINE_TWO, $dto->getAddressLineTwo());
        $this->assertEquals(AddressTestCase::CITY, $dto->getAddressCity());
        $this->assertEquals(AddressTestCase::STATE, $dto->getAddressState());
        $this->assertEquals(AddressTestCase::ZIP, $dto->getAddressZip());
        $this->assertEquals(self::ASSOCIATED_USER_ID, $dto->getAssociatedUserId());
        $this->assertEquals($this->getValidPresentsForAreasArray(), $dto->getPresentsForAreas());
        $this->assertEquals($this->getValidFacilitatesForAreasArray(), $dto->getFacilitatesForAreas());
        $this->assertEquals($this->getValidCoordinatesForAreasArray(), $dto->getCoordinatesForAreas());
        $this->assertEquals($this->getValidCoordinatesForRegionsArray(), $dto->getCoordinatesForRegions());
    }
    private function getValidPresentsForAreasArray()
    {
        return array(
            '502d90100172cda7d649d465' => 'OH-Clayton', '502d90100172cda7d649d461' => 'OH-Dayton'
        );
    }
    private function getValidFacilitatesForAreasArray()
    {
        return array(
            '502d90100172cda7d649d465' => 'OH-Clayton', '502d90100172cda7d649d461' => 'OH-Dayton'
        );
    }
    private function getValidCoordinatesForAreasArray()
    {
        return array(
            '502d90100172cda7d649d465' => 'OH-Clayton', '502d90100172cda7d649d461' => 'OH-Dayton'
        );
    }
    private function getValidCoordinatesForRegionsArray()
    {
        return array(
            'Mid-West'
        );
    }
    private function getTestAreas()
    {
        $areas = array();
        $area = new Area();
        $region = new Region();
        $region->setName('Mid-West')->setLegacyId(12);
        $area->setId('502d90100172cda7d649d465')->setName('OH-Clayton')->setCity('Clayton')->setState('OH')
            ->setRegion($region)->setLegacyId(69);
        $areas[] = $area;
        $area = new Area();
        $region = new Region();
        $region->setName('Mid-West')->setLegacyId(12);
        $area->setId('502d90100172cda7d649d461')->setName('OH-Dayton')->setCity('Dayton')->setState('OH')
            ->setRegion($region)->setLegacyId(69);
        $areas[] = $area;
        return $areas;
    }
}
