<?php
namespace STS\Domain;

use STS\TestUtilities\MemberTestCase;
use STS\Domain\Member\Diagnosis;
use STS\Domain\Member\PhoneNumber;

class MemberTest extends MemberTestCase
{
    /**
     * @test
     */
    public function getValidTypes()
    {
        $this->assertEquals(
            array(
                'TYPE_CAREGIVER' => 'Caregiver',
                'TYPE_FAMILY_MEMBER' => 'Family Member',
                'TYPE_SURVIVOR' => 'Survivor',
                'TYPE_SYSTEM_USER'=>'System User'
            ),
            Member::getAvailableTypes()
        );
    }

    /**
     * @test
     */
    public function getValidStatuses()
    {
        $this->assertEquals(
            array(
                'STATUS_ACTIVE' => 'Active',
                'STATUS_INACTIVE' => 'Inactive',
                'STATUS_DECEASED' => 'Deceased'
            ),
            Member::getAvailableStatuses()
        );
    }
    /**
     * @test
     */
    public function createValidObject()
    {
        $member = $this->getValidMember();
        $this->assertValidMember($member);
    }
    /**
     * @test
     */
    public function validAddPresentsForArea()
    {
        $member = $this->getValidMember();
        $area = \Mockery::mock('STS\Domain\Location\Area');
        $member->canPresentForArea($area);
        $areas = $member->getPresentsForAreas();
        $this->assertContains($area, $areas);
        $areas = $member->getAllAssociatedAreas();
        $this->assertContains($area, $areas);
    }
    /**
     * @test
     */
    public function validAddFacilitatesForArea()
    {
        $member = $this->getValidMember();
        $area = \Mockery::mock('STS\Domain\Location\Area');
        $member->canFacilitateForArea($area);
        $areas = $member->getFacilitatesForAreas();
        $this->assertContains($area, $areas);
        $areas = $member->getAllAssociatedAreas();
        $this->assertContains($area, $areas);
    }
    /**
     * @test
     */
    public function validAddCoordinatesForArea()
    {
        $member = $this->getValidMember();
        $area = \Mockery::mock('STS\Domain\Location\Area');
        $member->canCoordinateForArea($area);
        $areas = $member->getCoordinatesForAreas();
        $this->assertContains($area, $areas);
        $areas = $member->getAllAssociatedAreas();
        $this->assertContains($area, $areas);
    }
    /**
     * @test
     */
    public function validGetAllAssociatedAreas()
    {
        $member = $this->getValidMember();
        $presentsArea = \Mockery::mock('STS\Domain\Location\Area');
        $member->canPresentForArea($presentsArea);
        $facilitatesArea = \Mockery::mock('STS\Domain\Location\Area');
        $member->canFacilitateForArea($facilitatesArea);
        $coordinatesArea = \Mockery::mock('STS\Domain\Location\Area');
        $member->canCoordinateForArea($coordinatesArea);
        $areas = $member->getAllAssociatedAreas();
        $this->assertContains($presentsArea, $areas);
        $this->assertContains($facilitatesArea, $areas);
        $this->assertContains($coordinatesArea, $areas);
    }
    /**
     * @test
     */
    public function assertAllAssociatedAreasReturnUniqueAreas()
    {
        $member = $this->getValidMember();
        $areas = $member->getAllAssociatedAreas();
        $this->assertCount(2, $areas);
    }

    /**
     * @test
     */
    public function validSetDiagnosis()
    {
        $member = $this->getValidMember();
        $diagnosis = new Diagnosis(time(), 'I');
        $member->setDiagnosis($diagnosis);
        $this->assertInstanceOf('STS\Domain\Member\Diagnosis', $member->getDiagnosis());
    }

    /**
     * @test
     */
    public function validAddPhoneNumbers()
    {
        $member = $this->getValidMember();
        $this->assertCount(2, $member->getPhoneNumbers());
        $phoneNumbers = $member->getPhoneNumbers();
        $this->assertInstanceOf('STS\Domain\Member\PhoneNumber', $phoneNumbers[0]);
    }

    /**
     * @test
     */
    public function validCanBeDeleted()
    {
        $member = new Member();
        $this->assertTrue($member->canBeDeleted(), 'returns false against new member when should be true');
        $member->setCanBeDeleted(false);
        $this->assertFalse($member->canBeDeleted(), 'returns true after setting it to false');
        $member->setCanBeDeleted(true);
        $this->assertTrue($member->canBeDeleted(), 'returns false against after setting it to true');
        $member->setAssociatedUserId(self::ASSOCIATED_USER_ID);
        $this->assertFalse($member->canBeDeleted(), 'returns true after associating a user');
    }
    
}
