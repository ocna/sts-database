<?php
use STS\Domain\Member\Specification\MemberByMemberAreaSpecification;
use STS\Domain\Member;
use \Mockery;

class MemberByMemberAreaSpecificationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $member = \Mockery::mock('STS\Domain\Member');
        $spec = new MemberByMemberAreaSpecification($member);
        $this->assertInstanceOf('STS\Domain\Member\Specification\MemberByMemberAreaSpecification', $spec);
    }
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Instance of Member required.
     */
    public function throwExceptionForNotPassingMember()
    {
        $spec = new MemberByMemberAreaSpecification(null);
    }
    /**
     * @test
     */
    public function isSatisfiedBySchool()
    {
        $member = \Mockery::mock('STS\Domain\Member');
        $candidateMember = \Mockery::mock('STS\Domain\Member');
        $area = \Mockery::mock('STS\Domain\Location\Area');
        $otherArea = \Mockery::mock('STS\Domain\Location\Area');
        $member->shouldReceive('getAllAssociatedAreas')->withNoArgs()
            ->andReturn(array(
                $area, $otherArea
            ));
        $candidateMember->shouldReceive('getAllAssociatedAreas')->withNoArgs()
            ->andReturn(array(
                $area
            ));
        $spec = new MemberByMemberAreaSpecification($member);
        $this->assertTrue(true === $spec->isSatisfiedBy($candidateMember));
    }
    /**
     * @test
     */
    public function isNotSatisfiedBySchool()
    {
        $member = \Mockery::mock('STS\Domain\Member');
        $candidateMember = \Mockery::mock('STS\Domain\Member');
        $area = \Mockery::mock('STS\Domain\Location\Area');
        $otherArea = \Mockery::mock('STS\Domain\Location\Area');
        $member->shouldReceive('getAllAssociatedAreas')->withNoArgs()
            ->andReturn(array(
                $otherArea
            ));
        $candidateMember->shouldReceive('getAllAssociatedAreas')->withNoArgs()
            ->andReturn(array(
                $area
            ));
        $spec = new MemberByMemberAreaSpecification($member);
        $this->assertTrue(false === $spec->isSatisfiedBy($candidateMember));
    }
    public function teardown()
    {
        \Mockery::close();
    }
}
