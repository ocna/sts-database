<?php
use STS\Domain\Member\Specification\MemberByMemberAreaSpecification;
use STS\Domain\Member;
use STS\Core\Api\DefaultMemberFacade;
use STS\Domain\Location\Area;
use \Mockery;
use STS\TestUtilities\MemberTestCase;
use STS\TestUtilities\Location\AddressTestCase;

class DefaultMemberFacadeTest extends MemberTestCase
{



    /**
     * @test
     */
    public function validSaveNewMember()
    {
        $facade = new DefaultMemberFacade($this->getMockMemberRepoForSave(), $this->getMockAreaRepository());
        $presentsFor = array_keys($this->getValidPresentsForAreasArray());
        $facilitatesFor = array_keys($this->getValidFacilitatesForAreasArray());
        $coordinatesFor = array_keys($this->getValidCoordinatesForAreasArray());
        $newMemberDto = $facade->saveMember(
            self::FIRST_NAME,
            self::LAST_NAME,
            self::TYPE,
            self::STATUS,
            self::NOTES,
            $presentsFor,
            $facilitatesFor,
            $coordinatesFor,
            self::ASSOCIATED_USER_ID,
            AddressTestCase::LINE_ONE,
            AddressTestCase::LINE_TWO,
            AddressTestCase::CITY,
            AddressTestCase::STATE,
            AddressTestCase::ZIP,
            self::EMAIL,
            self::DISPLAY_DATE_TRAINED,
            array('date'=>self::DISPLAY_DATE_TRAINED, 'stage'=>'I'),
            array(
                'work'=>'3015551234',
                'cell'=>'5551239999'
            )
        );

        $this->assertValidMemberDto($newMemberDto);
    }

    private function getMockAreaRepository()
    {
        $areas = $this->getTestAreas();
        $areaRepository = \Mockery::mock('STS\Core\Location\MongoAreaRepository');
        $areaRepository->shouldReceive('load')->withAnyArgs()->andReturn($areas[0], $areas[1],$areas[0], $areas[1],$areas[0], $areas[1]);
        return $areaRepository;
    }

    /**
     * @test
     */
    public function validGetMemberStatuses()
    {
        $facade = new DefaultMemberFacade($this->getMockMemberRepository(), $this->getMockAreaRepository());
        $this
            ->assertEquals(array(
                'STATUS_ACTIVE' => 'Active', 'STATUS_INACTIVE' => 'Inactive', 'STATUS_DECEASED' => 'Deceased'
            ), $facade->getMemberStatuses());
    }

    /**
     * @test
     */
    public function validGetDiagnosisStages()
    {
        $facade = new DefaultMemberFacade($this->getMockMemberRepository(), $this->getMockAreaRepository());
        $this->assertEquals(
            array(
            'I'=>'I',
            'IA'=>'IA',
            'IB'=>'IB',
            'IC'=>'IC',
            'II'=>'II',
            'IIA'=>'IIA',
            'IIB'=>'IIB',
            'IIC'=>'IIC',
            'III'=>'III',
            'IIIA'=>'IIIA',
            'IIIB'=>'IIIB',
            'IIIC'=>'IIIC',
            'IV'=>'IV'
        ), $facade->getDiagnosisStages());
    }

    /**
     * @test
     */
    public function validGetPhoneNumberTypes()
    {
        $facade = new DefaultMemberFacade($this->getMockMemberRepository(), $this->getMockAreaRepository());
        $this->assertEquals(array(
            'TYPE_HOME' => 'home',
            'TYPE_CELL' => 'cell',
            'TYPE_WORK'=> 'work'
            ), $facade->getPhoneNumberTypes());
    }
    
    

    /**
     * @test
     */
    public function validGetAllSchoolsWithNoSpec()
    {
        $facade = new DefaultMemberFacade($this->getMockMemberRepository(), $this->getMockAreaRepository());
        $memberDtos = $facade->searchForMembersByNameWithSpec('Jab', null);
        $this->assertCount(2, $memberDtos);
    }
    /**
     * @test
     */
    public function validGetMembersPerMemberSpec()
    {
        $facade = new DefaultMemberFacade($this->getMockMemberRepository(), $this->getMockAreaRepository());
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
        $facade = new DefaultMemberFacade($memberRepository, $this->getMockAreaRepository());
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
        $facade = new DefaultMemberFacade($memberRepository, $this->getMockAreaRepository());
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

    private function getMockMemberRepoForSave()
    {
        $memberRepository = \Mockery::mock('STS\Core\Member\MongoMemberRepository');
        $memberRepository->shouldReceive('save')->withAnyArgs()->andReturn($this->getValidMember());
        return $memberRepository;
    }
    /**
     * @test
     */
    public function validDeleteMemberWithNoAssociations(){
        $member = $this->getValidMember();
        $member->setAssociatedUserId(null);
        $memberRepository = \Mockery::mock('STS\Core\Member\MongoMemberRepository');
        $memberRepository->shouldReceive('load')->with($member->getId())->andReturn($member);
        $memberRepository->shouldReceive('delete')->with($member->getId())->andReturn(true);
        $facade = new DefaultMemberFacade($memberRepository, $this->getMockAreaRepository());
        $this->assertTrue($facade->deleteMember($member->getId()));
    }

    /**
     * @test
     * @expectedException STS\Core\Api\ApiException
     * @expectedExceptionMessage Unable to delete member.
     */
    public function validDeleteMemberWithUserAssociation(){
        $member = $this->getValidMember();
        $memberRepository = \Mockery::mock('STS\Core\Member\MongoMemberRepository');
        $memberRepository->shouldReceive('load')->with($member->getId())->andReturn($member);
        $facade = new DefaultMemberFacade($memberRepository, $this->getMockAreaRepository());
        $this->assertTrue($facade->deleteMember($member->getId()));
    }

    /**
     * @test
     * @expectedException STS\Core\Api\ApiException
     * @expectedExceptionMessage Unable to delete member.
     */
    public function validDeleteMemberWithOtherAssociation(){
        $member = $this->getValidMember();
        $member->setAssociatedUserId(null);
        $member->setCanBeDeleted(false);
        $memberRepository = \Mockery::mock('STS\Core\Member\MongoMemberRepository');
        $memberRepository->shouldReceive('load')->with($member->getId())->andReturn($member);
        $facade = new DefaultMemberFacade($memberRepository, $this->getMockAreaRepository());
        $this->assertTrue($facade->deleteMember($member->getId()));
    }

}