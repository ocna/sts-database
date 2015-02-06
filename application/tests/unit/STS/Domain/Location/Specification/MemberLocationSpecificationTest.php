<?php

use STS\Domain\Member;
use STS\Domain\Location\Specification\MemberLocationSpecification;

class MemberLocationSpecificationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $member = \Mockery::mock('STS\Domain\Member');
        $spec = new MemberLocationSpecification($member);
        $this->assertInstanceOf('STS\Domain\Location\Specification\MemberLocationSpecification',
            $spec);
    }
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Instance of Member required.
     */
    public function throwExceptionForNotPassingMember()
    {
        $spec = new MemberLocationSpecification(null);
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
        $spec = new MemberLocationSpecification($member);
        $this->assertTrue(true === $spec->isSatisfiedBy($school));
    }
}
