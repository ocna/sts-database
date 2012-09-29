<?php
use STS\Domain\Member\Specification\MemberByMemberAreaSpecification;
use STS\Domain\Member;
use STS\Core\Api\DefaultMemberFacade;
use STS\Domain\Location\Area;
use \Mockery;

class DefaultMemberFacadeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function validGetMemberStatuses(){
        $facade = new DefaultMemberFacade($this->getMockMemberRepository());
        $this
            ->assertEquals(array(
                'STATUS_ACTIVE' => 'Active', 'STATUS_INACTIVE' => 'Inactive', 'STATUS_DECEASED' => 'Deceased'
            ), $facade->getMemberStatuses());
    }
    
    /**
     * @test
     */
    public function validGetAllSchoolsWithNoSpec()
    {
        $facade = new DefaultMemberFacade($this->getMockMemberRepository());
        $memberDtos = $facade->searchForMembersByNameWithSpec('Jab', null);
        $this->assertCount(2, $memberDtos);
    }
    /**
     * @test
     */
    public function validGetMembersPerMemberSpec()
    {
        $facade = new DefaultMemberFacade($this->getMockMemberRepository());
        $member = new Member();
        $areaA = new Area();
        $spec = new MemberByMemberAreaSpecification($member->canPresentForArea($areaA->setId(11)));
        $memberDtos = $facade->searchForMembersByNameWithSpec('Jab', $spec);
        $this->assertCount(1, $memberDtos);
        $dto = $memberDtos[0];
        $this->assertEquals(1, $dto->getId());
    }
    /**
     * @test
     */
    public function validGetMemberByMemberAreaSpecForId()
    {
        $memberRepository = \Mockery::mock('STS\Core\Member\MongoMemberRepository');
        $memberA = new Member();
        $memberA->setId(1);
        $memberRepository->shouldReceive('load')->with('1')->andReturn($memberA);
        $facade = new DefaultMemberFacade($memberRepository);
        $spec = $facade->getMemberByMemberAreaSpecForId(1);
        $this->assertInstanceOf('STS\Domain\Member\Specification\MemberByMemberAreaSpecification', $spec);
    }
    /**
     * @test
     */
    public function validGetMemberSchoolSpecForId()
    {
        $memberRepository = \Mockery::mock('STS\Core\Member\MongoMemberRepository');
        $memberA = new Member();
        $memberA->setId(1);
        $memberRepository->shouldReceive('load')->with('1')->andReturn($memberA);
        $facade = new DefaultMemberFacade($memberRepository);
        $spec = $facade->getMemberSchoolSpecForId(1);
        $this->assertInstanceOf('STS\Domain\School\Specification\MemberSchoolSpecification', $spec);
    }
    private function getMockMemberRepository()
    {
        $areaA = new Area();
        $areaB = new Area();
        $memberA = new Member();
        $memberB = new Member();
        $memberA->setId(1)->canPresentForArea($areaA->setId(11));
        $memberB->setId(2)->canFacilitateForArea($areaB->setId(22));
        $memberRepository = \Mockery::mock('STS\Core\Member\MongoMemberRepository');
        $memberRepository->shouldReceive('searchByName')->with('Jab')
            ->andReturn(array(
                $memberA, $memberB
            ));
        return $memberRepository;
    }
}
