<?php
namespace STS\TestUtilities;

use STS\Domain\Location\Area;
use STS\Domain\Member;
use STS\Core\Member\MemberDto;
use STS\TestUtilities\Location\AddressTestCase;
use STS\Domain\Location\Region;
use STS\Domain\Location\Address;
use STS\Domain\Member\Diagnosis;
use STS\Domain\Member\PhoneNumber;

class MemberTestCase extends \PHPUnit_Framework_TestCase
{
    const ID = '50234bc4fe65f50a9579a8cd';
    const LEGACY_ID = 0;
    const FIRST_NAME = 'Member';
    const LAST_NAME = 'User';
    const EMAIL = 'member.user@email.com';
    const TYPE = 'Survivor';
    const NOTES = 'This is an interesting note!';
    const STATUS = 'Deceased';
    const ASSOCIATED_USER_ID = 'muser';
    const DATE_TRAINED = '2012-08-09 04:00:00';
    const DISPLAY_DATE_TRAINED = '08/09/2012';
    const CAN_BE_DELETED = false;

    protected function getValidMember()
    {
        $member = new Member();
        $address = new Address();
        $address->setLineOne(AddressTestCase::LINE_ONE)
                ->setLineTwo(AddressTestCase::LINE_TWO)
                ->setZip(AddressTestCase::ZIP)
                ->setState(AddressTestCase::STATE)
                ->setCity(AddressTestCase::CITY);
        $diagnosis = new Diagnosis(self::DATE_TRAINED, 'I');
        $member->setId(self::ID)
               ->setLegacyId(self::LEGACY_ID)
               ->setFirstName(self::FIRST_NAME)
               ->setLastName(self::LAST_NAME)
               ->setEmail(self::EMAIL)
               ->setNotes(self::NOTES)
               ->setStatus(self::STATUS)
               ->setType(self::TYPE)
               ->setDateTrained(self::DATE_TRAINED)
               ->setAddress($address)
               ->setAssociatedUserId(self::ASSOCIATED_USER_ID)
               ->setDiagnosis($diagnosis);

        foreach ($this->getValidPhoneNumbersArray() as $phoneNumber) {
            $member->addPhoneNumber(new PhoneNumber($phoneNumber['number'], $phoneNumber['type']));
        }

        foreach ($this->getTestAreas() as $area) {
            $member->canPresentForArea($area);
            $member->canCoordinateForArea($area);
            $member->canFacilitateForArea($area);
        }

        return $member;
    }

    public static function createValidMember()
    {
        $memberTestCase = new MemberTestCase();
        return $memberTestCase->getValidMember();
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
        $this->assertFalse($member->canBeDeleted());
    }

    protected function getValidMemberDto()
    {
        $memberDto = new MemberDto(
            self::ID,
            self::LEGACY_ID,
            self::FIRST_NAME,
            self::LAST_NAME,
            self::TYPE,
            self::NOTES,
            self::STATUS,
            $this->getValidActivitiesArray(),
            AddressTestCase::LINE_ONE,
            AddressTestCase::LINE_TWO,
            AddressTestCase::CITY,
            AddressTestCase::STATE,
            AddressTestCase::ZIP,
            self::ASSOCIATED_USER_ID,
            $this->getValidPresentsForAreasArray(),
            $this->getValidFacilitatesForAreasArray(),
            $this->getValidCoordinatesForAreasArray(),
            $this->getValidCoordinatesForRegionsArray(),
            self::EMAIL,
            self::DATE_TRAINED,
            //diagnosis date
            self::DATE_TRAINED,
            'I',
            $this->getValidPhoneNumbersArray(),
            self::CAN_BE_DELETED
        );

        return $memberDto;
    }

    protected function assertValidMemberDto($dto, $skipCheck = array())
    {
        $this->assertInstanceOf('STS\Core\Member\MemberDto', $dto);
        $this->assertTrue(is_string($dto->getId()));
        if (!in_array('id', $skipCheck)) {
            $this->assertEquals(self::ID, $dto->getId());
        }
        $this->assertEquals(self::LEGACY_ID, $dto->getLegacyId());
        if (!in_array('firstName', $skipCheck)) {
            $this->assertEquals(self::FIRST_NAME, $dto->getFirstName());
        }
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
        if (!in_array('associatedUserId', $skipCheck)) {
            $this->assertEquals(self::ASSOCIATED_USER_ID, $dto->getAssociatedUserId());
        }
        $this->assertEquals($this->getValidPresentsForAreasArray(), $dto->getPresentsForAreas());
        $this->assertEquals($this->getValidFacilitatesForAreasArray(), $dto->getFacilitatesForAreas());
        $this->assertEquals($this->getValidCoordinatesForAreasArray(), $dto->getCoordinatesForAreas());
        $this->assertEquals($this->getValidCoordinatesForRegionsArray(), $dto->getCoordinatesForRegions());
        $this->assertEquals(self::EMAIL, $dto->getEmail());
        $this->assertEquals(self::DISPLAY_DATE_TRAINED, $dto->getDateTrained());
        $this->assertEquals(self::DISPLAY_DATE_TRAINED, $dto->getDiagnosisDate());
        $this->assertEquals('I', $dto->getDiagnosisStage());
        $this->assertEquals($this->getValidPhoneNumbersArray(), $dto->getPhoneNumbers());
        $this->assertFalse($dto->canBeDeleted());
    }

    protected function getValidPresentsForAreasArray()
    {
        return array(
            '502d90100172cda7d649d465' => 'OH-Clayton',
            '502d90100172cda7d649d461' => 'OH-Dayton'
        );
    }

    protected function getValidFacilitatesForAreasArray()
    {
        return array(
            '502d90100172cda7d649d465' => 'OH-Clayton',
            '502d90100172cda7d649d461' => 'OH-Dayton'
        );
    }
    protected function getValidCoordinatesForAreasArray()
    {
        return array(
            '502d90100172cda7d649d465' => 'OH-Clayton',
            '502d90100172cda7d649d461' => 'OH-Dayton'
        );
    }
    protected function getValidCoordinatesForRegionsArray()
    {
        return array(
            'Great Lakes' => 'Great Lakes'
        );
    }

    protected function getValidPhoneNumbersArray()
    {
        return array(
            'work' => array(
                'number'=>'3015551234',
                'type'=>'work'
                ),
            'cell'=> array(
                'number'=>'5551239999',
                'type'=>'cell'
                )
            );
    }

    protected function getValidActivitiesArray()
    {
        return array(
            'Presenter',
            'On-site Facilitator',
            'Area Facilitator'
        );
    }

    protected function getTestAreas()
    {
        $areas = array();
        $area = new Area();
        $region = new Region();
        $region->setName('Great Lakes')->setLegacyId(12);
        $area->setId('502d90100172cda7d649d465')->setName('OH-Clayton')->setCity('Clayton')->setState('OH')->setRegion($region)->setLegacyId(69);
        $areas[] = $area;
        $area = new Area();
        $region = new Region();
        $region->setName('Great Lakes')->setLegacyId(12);
        $area->setId('502d90100172cda7d649d461')->setName('OH-Dayton')->setCity('Dayton')->setState('OH')->setRegion($region)->setLegacyId(69);
        $areas[] = $area;
        return $areas;
    }

    protected function getTestAreaData()
    {
        $data = array(
            array(
                '_id'=> new \MongoId('502d90100172cda7d649d465'),
                'city'=>'Clayton',
                'legacyid'=>69,
                'name'=>'OH-Clayton',
                'state'=>'OH',
                'region'=> array(
                    'legacyid'=>12,
                    'name'=>'Great Lakes')
                ),
            array(
                '_id'=> new \MongoId('502d90100172cda7d649d461'),
                'city'=>'Dayton',
                'legacyid'=>69,
                'name'=>'OH-Dayton',
                'state'=>'OH',
                'region'=> array(
                    'legacyid'=>12,
                    'name'=>'Great Lakes')
                )
            );
        return $data;
    }
}
