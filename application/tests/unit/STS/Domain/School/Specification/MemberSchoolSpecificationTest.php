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
    public function isSatisfiedByPresenter()
    {
        $this->markTestIncomplete();
        $member = \Mockery::mock('STS\Domain\Member');
        
        
        $spec = new MemberSchoolSpecification($member);
        
        $this->assertTrue($spec->isSatisfiedBy($school));
        
        
        
    }
    public function teardown()
    {
        \Mockery::close();
    }
}
