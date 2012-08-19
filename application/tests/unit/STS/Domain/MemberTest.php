<?php
use STS\TestUtilities\MemberTestCase;
use STS\Domain\Member;

class MemberTest extends MemberTestCase
{
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
        $area = \Mockery::mock('STS\Domain\Location\Area');
        $otherArea = \Mockery::mock('STS\Domain\Location\Area');
        $member->canPresentForArea($area);
        $member->canFacilitateForArea($area);
        $member->canFacilitateForArea($otherArea);
        $member->canCoordinateForArea($area);
        $areas = $member->getAllAssociatedAreas();
        $this->assertCount(2, $areas);
        $this->assertEquals($areas[0], $area);
    }
}
