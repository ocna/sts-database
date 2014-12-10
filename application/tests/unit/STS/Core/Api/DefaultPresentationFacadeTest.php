<?php
namespace STS\Core\Api;

use STS\TestUtilities\PresentationTestCase;
use STS\TestUtilities\ProfessionalGroupTestCase;
use STS\TestUtilities\SurveyTestCase;
use STS\TestUtilities\UserTestCase;
use STS\TestUtilities\MemberTestCase;
use STS\TestUtilities\SchoolTestCase;
use STS\Domain\Location\Area;
use STS\Domain\Member;
use STS\Domain\School;
use STS\Core\Presentation\PresentationDto;

class DefaultPresentationFacadeTest extends PresentationTestCase
{
    const ADMIN_USER_ID = 'muser';
    const BASIC_USER_ID = 'buser';
    const COORDINATOR_USER_ID = 'cuser';


    /**
     * @test
     */
    public function validUpdatePresentation()
    {
        //givens
        $updatedNotes = 'These are updated notes.';
        $updatedPostForms = 123;
        $oldPresentation = $this->getValidObject();
        $presentation = $this->getValidObject();
        $presentation->setNotes($updatedNotes)
                     ->setNumberOfFormsReturnedPost($updatedPostForms);
        $presentations = array($this->getValidObject(), $this->getValidObject(), $this->getValidObject());
        $presentationRepository = \Mockery::mock('STS\Core\Presentation\MongoPresentationRepository', array('load'=>$oldPresentation, 'save'=>$presentation));
        $userRepository = \Mockery::mock('STS\Core\User\MongoUserRepository', array('load'=>UserTestCase::createValidUser()));
        $memberRepository = \Mockery::mock('STS\Core\Member\MongoMemberRepository', array('load'=>MemberTestCase::createValidMember()));
        $schoolRepository = \Mockery::mock('STS\Core\School\MongoSchoolRepository', array('load'=>SchoolTestCase::createValidSchool()));
	    $surveyRepository = \Mockery::mock('STS\Core\Survey\MongoSurveyRepository',
		    array('load'=>SurveyTestCase::createValidSurvey()));
	    $professionalGroupRepository = \Mockery::mock
	    ('STS\Core\ProfessionalGroup\MongoProfessionalGroupRepository', array('load' => ProfessionalGroupTestCase::createValidProfessionalGroup()));
        $facade = new DefaultPresentationFacade($presentationRepository, $userRepository,
	        $memberRepository, $schoolRepository, $surveyRepository, $professionalGroupRepository);
        //whens
        $updatedPresentationDto = $facade->updatePresentation(
            $presentation->getId(),
            $presentation->getLocation()->getId(),
            $presentation->getProfessionalGroup()->getId(),
            'TYPE_MED',
            $presentation->getDate(),
            $presentation->getNotes(),
            array_keys($this->getPresentationDtoMemberArray()),
            $presentation->getNumberOfParticipants(),
            $presentation->getNumberOfFormsReturnedPost(),
            $presentation->getNumberOfFormsReturnedPre()
        );
        //thens
        $this->assertInstanceOf('STS\Core\Presentation\PresentationDto', $updatedPresentationDto);
        $this->assertEquals($updatedNotes, $updatedPresentationDto->getNotes());
        $this->assertEquals($updatedPostForms, $updatedPresentationDto->getNumberOfFormsReturnedPost());
    }

    /**
     * @test
     */
    public function itShouldReturnASinglePresentation()
    {
        $presentation = $this->getValidObject();
        $presentationRepository = \Mockery::mock('STS\Core\Presentation\MongoPresentationRepository', array('load'=>$presentation));
        $userRepository = \Mockery::mock('STS\Core\User\MongoUserRepository', array('load'=>UserTestCase::createValidUser()));
        $memberRepository = \Mockery::mock('STS\Core\Member\MongoMemberRepository', array('load'=>MemberTestCase::createValidMember()));
        $schoolRepository = \Mockery::mock('STS\Core\School\MongoSchoolRepository', array('load'=>SchoolTestCase::createValidSchool()));
	    $surveyRepository = \Mockery::mock('STS\Core\Survey\MongoSurveyRepository',
		    array('load'=>SurveyTestCase::createValidSurvey()));
	    $professionalGroupRepository = \Mockery::mock
	    ('STS\Core\ProfessionalGroup\MongoProfessionalGroupRepository', array('load' => ProfessionalGroupTestCase::createValidProfessionalGroup()));
	    $facade = new DefaultPresentationFacade($presentationRepository, $userRepository,
		    $memberRepository, $schoolRepository, $surveyRepository, $professionalGroupRepository);
        $dto = $facade->getPresentationById(self::ID);
        $this->assertValidPresentationDto($dto);
    }

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
        $schoolRepository = \Mockery::mock('STS\Core\School\MongoSchoolRepository', array('load'=>SchoolTestCase::createValidSchool()));
	    $surveyRepository = \Mockery::mock('STS\Core\Survey\MongoSurveyRepository',
		    array('load'=>SurveyTestCase::createValidSurvey()));
	    $professionalGroupRepository = \Mockery::mock
	    ('STS\Core\ProfessionalGroup\MongoProfessionalGroupRepository', array('load' => ProfessionalGroupTestCase::createValidProfessionalGroup()));
	    $facade = new DefaultPresentationFacade($presentationRepository, $userRepository,
		    $memberRepository, $schoolRepository, $surveyRepository, $professionalGroupRepository);
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
        $schoolRepository = \Mockery::mock('STS\Core\School\MongoSchoolRepository', array('load'=>SchoolTestCase::createValidSchool()));
	    $surveyRepository = \Mockery::mock('STS\Core\Survey\MongoSurveyRepository',
		    array('load'=>SurveyTestCase::createValidSurvey()));
	    $professionalGroupRepository = \Mockery::mock
	    ('STS\Core\ProfessionalGroup\MongoProfessionalGroupRepository', array('load' => ProfessionalGroupTestCase::createValidProfessionalGroup()));
	    $facade = new DefaultPresentationFacade($presentationRepository, $userRepository,
		    $memberRepository, $schoolRepository, $surveyRepository, $professionalGroupRepository);
        //whens
        $presentations = $facade->getPresentationsForUserId(self::COORDINATOR_USER_ID);
        //thens
        $this->assertTrue(is_array($presentations));
        $this->assertCount(1, $presentations);
	    /** @var PresentationDto $presentation */
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
        $schoolRepository = \Mockery::mock('STS\Core\School\MongoSchoolRepository', array('load'=>SchoolTestCase::createValidSchool()));
	    $surveyRepository = \Mockery::mock('STS\Core\Survey\MongoSurveyRepository',
		    array('load'=>SurveyTestCase::createValidSurvey()));
	    $professionalGroupRepository = \Mockery::mock
	    ('STS\Core\ProfessionalGroup\MongoProfessionalGroupRepository', array('load' => ProfessionalGroupTestCase::createValidProfessionalGroup()));
	    $facade = new DefaultPresentationFacade($presentationRepository, $userRepository,
		    $memberRepository, $schoolRepository, $surveyRepository, $professionalGroupRepository);
        return $facade;
    }
}
