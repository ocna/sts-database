<?php
use STS\Domain\School\Specification\MemberSchoolSpecification;
use STS\Domain\Member;
use \Mockery;

class MemberSchoolSpecificationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $member = \Mockery::mock('STS\Domain\Member');
        $spec = new MemberSchoolSpecification($member);
        $this->assertInstanceOf('STS\Domain\School\Specification\MemberSchoolSpecification', $spec);
    }
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Instance of Member required.
     */
    public function throwExceptionForNotPassingMember()
    {
        $spec = new MemberSchoolSpecification(null);
    }
    /**
     * @test
     */
    public function isSatisfiedBySchool()
    {
        $member = \Mockery::mock('STS\Domain\Member');
        $area = \Mockery::mock('STS\Domain\Location\Area');
        $otherArea = \Mockery::mock('STS\Domain\Location\Area');
        $school = \Mockery::mock('STS\Domain\School');
        $school->shouldReceive('getArea')->withNoArgs()->andReturn($area);
        $member->shouldReceive('getAllAssociatedAreas')->withNoArgs()
            ->andReturn(array(
                $area, $otherArea
            ));
        $spec = new MemberSchoolSpecification($member);
        $this->assertTrue(true === $spec->isSatisfiedBy($school));
    }
}
