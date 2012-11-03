<?php
namespace STS\Core\Api;

use STS\TestUtilities\PresentationTestCase;
use STS\TestUtilities\UserTestCase;
use STS\TestUtilities\MemberTestCase;
use STS\Domain\Location\Area;
use STS\Domain\Member;
use STS\Domain\School;

class DefaultPresentationFacadeTest extends PresentationTestCase
{
    const ADMIN_USER_ID = 'muser';
    const BASIC_USER_ID = 'buser';
    const COORDINATOR_USER_ID = 'cuser';
    /**
     * @test
     */
    public function itShouldReturnAllPresentationsForAnAdminUser()
    {
        $facade = $this->getFacadeWithMockedDeps();
        $presentations = $facade->getPresentationsForUserId(self::ADMIN_USER_ID);
        $this->assertTrue(is_array($presentations));
        $this->assertCount(3, $presentations);
        $this->assertInstanceOf('STS\Core\Presentation\PresentationDto', $presentations[0]);
    }

    /**
     * @test
     */
    public function itShouldReturnVisiblePresentationForBasicUser()
    {
        //givens
        $correctPresentation = $this->getValidObject();
        $correctPresentation->setEnteredByUserId(self::BASIC_USER_ID);
        $correctPresentation->setNumberOfParticipants(10);
        $presentations = array($this->getValidObject(), $correctPresentation, $this->getValidObject());
        $presentationRepository = \Mockery::mock('STS\Core\Presentation\MongoPresentationRepository', array('find'=>$presentations));
        $correctUser = UserTestCase::createValidUser();
        $correctUser->setId(self::BASIC_USER_ID);
        $correctUser->setRole('member');
        $userRepository = \Mockery::mock('STS\Core\User\MongoUserRepository', array('load'=>$correctUser));
        $memberRepository = \Mockery::mock('STS\Core\Member\MongoMemberRepository', array('load'=>MemberTestCase::createValidMember()));
        $facade = new DefaultPresentationFacade($presentationRepository, $userRepository, $memberRepository);
        //whens
        $presentations = $facade->getPresentationsForUserId(self::BASIC_USER_ID);
        //thens
        $this->assertTrue(is_array($presentations));
        $this->assertCount(1, $presentations);
        $presentation = $presentations[0];
        $this->assertInstanceOf('STS\Core\Presentation\PresentationDto', $presentation);
        $this->assertEquals(10, $presentation->getNumberOfParticipants());
    }

    /**
     * @test
     */
    public function itShouldReturnOnlyPresentationsInMemberAreas()
    {   $area = new Area();
        $area->setCity('test city');
        $school = new School();
        $school->setArea($area);

        //givens
        $correctPresentation = $this->getValidObject();
        $correctPresentation->setEnteredByUserId(self::BASIC_USER_ID);
        $correctPresentation->setNumberOfParticipants(10);
        $correctPresentation->setLocation($school);
        $presentations = array($this->getValidObject(), $correctPresentation, $this->getValidObject());
        $presentationRepository = \Mockery::mock('STS\Core\Presentation\MongoPresentationRepository', array('find'=>$presentations));

        $correctUser = UserTestCase::createValidUser();
        $correctUser->setId(self::COORDINATOR_USER_ID);
        $correctUser->setRole('coordinator');
        $userRepository = \Mockery::mock('STS\Core\User\MongoUserRepository', array('load'=>$correctUser));

        $correctMember = new Member();
        $correctMember->canCoordinateForArea($area);

        $memberRepository = \Mockery::mock('STS\Core\Member\MongoMemberRepository', array('load'=>$correctMember));

        $facade = new DefaultPresentationFacade($presentationRepository, $userRepository, $memberRepository);
        //whens
        $presentations = $facade->getPresentationsForUserId(self::COORDINATOR_USER_ID);
        //thens
        $this->assertTrue(is_array($presentations));
        $this->assertCount(1, $presentations);
        $presentation = $presentations[0];
        $this->assertInstanceOf('STS\Core\Presentation\PresentationDto', $presentation);
        $this->assertEquals(10, $presentation->getNumberOfParticipants());
    }

    private function getFacadeWithMockedDeps()
    {
        $presentations = array($this->getValidObject(), $this->getValidObject(), $this->getValidObject());
        $presentationRepository = \Mockery::mock('STS\Core\Presentation\MongoPresentationRepository', array('find'=>$presentations));
        $userRepository = \Mockery::mock('STS\Core\User\MongoUserRepository', array('load'=>UserTestCase::createValidUser()));
        $memberRepository = \Mockery::mock('STS\Core\Member\MongoMemberRepository', array('load'=>MemberTestCase::createValidMember()));
        $facade = new DefaultPresentationFacade($presentationRepository, $userRepository, $memberRepository);
        return $facade;
    }
}
