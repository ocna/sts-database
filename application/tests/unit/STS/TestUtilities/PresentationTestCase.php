<?php
namespace STS\TestUtilities;

use STS\Domain\Presentation;
use STS\Core\Presentation\PresentationDto;
use STS\TestUtilities\Location\AreaTestCase;
use STS\TestUtilities\SurveyTestCase;

class PresentationTestCase extends \PHPUnit_Framework_TestCase
{
    const ENTERED_BY_USER_ID = 'jfox';
    const ID = '5068b274559ac99cfe2f6796';
    const TYPE = 'MED';
    const DATE = '2012-05-10 11:55:23';
    const DISPLAY_DATE = '05/10/2012';
    const NOTES = 'The presentation went quite well I must say.';
    const PARTICIPANTS = 22;
    const FORMS_POST = 22;
    const FORMS_PRE = 20;
	const BEFORE_PERCENTAGE = 23.5;
	const AFTER_PERCENTAGE = 34.09;
	const EFFECTIVENESS = 45.06;

    protected function getValidObject()
    {
        $school = SchoolTestCase::createValidSchool();
	    $professional_group = ProfessionalGroupTestCase::createValidProfessionalGroup();
        $members = array(
            MemberTestCase::createValidMember()
        );
        $survey = \Mockery::mock('STS\Domain\Survey', array(
		        'getId'                         => SurveyTestCase::ID,
		        'getNumCorrectBeforeResponses'  => SurveyTestCase::NUM_CORRECT_BEFORE,
		        'getNumCorrectAfterResponses'   => SurveyTestCase::NUM_CORRECT_AFTER
	        )
        );
        $presentation = new Presentation();
        $presentation->setEnteredByUserId(self::ENTERED_BY_USER_ID)
                     ->setId(self::ID)
                     ->setLocation($school)
	                 ->setProfessionalGroup($professional_group)
                     ->setType(self::TYPE)
                     ->setDate(self::DATE)
                     ->setNotes(self::NOTES)
                     ->setMembers($members)
                     ->setNumberOfParticipants(self::PARTICIPANTS)
                     ->setNumberOfFormsReturnedPost(self::FORMS_POST)
                     ->setNumberOfFormsReturnedPre(self::FORMS_PRE)
                     ->setSurvey($survey);
        return $presentation;
    }

	protected function getValidMongoArray()
	{
		$presentation = $this->getValidObject();
		$array = array(
			'id'                    => $presentation->getId(),
			'entered_by_user_id'    => $presentation->getEnteredByUserId(),
			'type'                  => $presentation->getType(),
			'notes'                 => utf8_encode($presentation->getNotes()),
			'nforms'                => $presentation->getNumberOfFormsReturnedPost(),
			'nformspre'             => $presentation->getNumberOfFormsReturnedPre(),
			'date'                  => $presentation->getDate(),
			'nparticipants'         => $presentation->getNumberOfParticipants(),
			'school_id'             => $presentation->getLocation()->getId(),
			'professional_group_id' => $presentation->getProfessionalGroup()->getId(),
			'survey_id'             => $presentation->getSurvey()->getId(),
			'dateCreated'           => new \MongoDate($presentation->getCreatedOn()),
			'dateUpdated'           => new \MongoDate($presentation->getUpdatedOn())
		);
		$members = array();
		foreach ($presentation->getMembers() as $member) {
			$members[] = $member->getId();
		}
		$array['members'] = $members;
		return $array;
	}

    public static function createValidObject()
    {
        $presentationTestCase = new PresentationTestCase();
        return $presentationTestCase->getValidObject();
    }

    protected function getValidPresentationDto()
    {
        return new PresentationDto(
            self::ID,
            SchoolTestCase::NAME,
            AreaTestCase::CITY,
            ProfessionalGroupTestCase::NAME,
            self::PARTICIPANTS,
            self::DATE,
            self::TYPE,
            self::FORMS_POST,
            self::FORMS_PRE,
            SchoolTestCase::ID,
            SurveyTestCase::ID,
            $this->getPresentationDtoMemberArray(),
            self::NOTES,
            self::BEFORE_PERCENTAGE,
            self::AFTER_PERCENTAGE,
            self::EFFECTIVENESS
        );
    }

	/**
	 * @param Presentation $presentation
	 */
    protected function assertValidObject($presentation)
    {
        $this->assertEquals(self::ID, $presentation->getId());
        $this->assertEquals(self::ENTERED_BY_USER_ID, $presentation->getEnteredByUserId());
        $this->assertEquals(self::TYPE, $presentation->getType());
        $this->assertEquals(self::DATE, $presentation->getDate());
        $this->assertEquals(self::NOTES, $presentation->getNotes());
        $this->assertEquals(self::PARTICIPANTS, $presentation->getNumberOfParticipants());
        $this->assertEquals(self::FORMS_POST, $presentation->getNumberOfFormsReturnedPost());
        $this->assertEquals(self::FORMS_PRE, $presentation->getNumberOfFormsReturnedPre());
        $this->assertInstanceOf('STS\Domain\School', $presentation->getLocation());
        $this->assertInstanceOf('STS\Domain\Survey', $presentation->getSurvey());
	    $this->assertInstanceOf('STS\Domain\ProfessionalGroup',
		    $presentation->getProfessionalGroup());
        $this->assertTrue(is_array($presentation->getMembers()));
        $this->assertEquals(array(MemberTestCase::createValidMember()), $presentation->getMembers());
        $members = $presentation->getMembers();
        $this->assertInstanceOf('STS\Domain\Member', array_pop($members));
    }

	/**
	 * @param PresentationDto $dto
	 */
    protected function assertValidPresentationDto($dto)
    {
        $this->assertInstanceOf('STS\Core\Presentation\PresentationDto', $dto);
        $this->assertTrue(is_string($dto->getId()));
        $this->assertEquals(self::ID, $dto->getId());
        $this->assertEquals(SchoolTestCase::ID, $dto->getSchoolId());
        $this->assertEquals(SchoolTestCase::NAME, $dto->getSchoolName());
        $this->assertEquals(AreaTestCase::CITY, $dto->getSchoolAreaCity());
	    $this->assertEquals(ProfessionalGroupTestCase::NAME, $dto->getProfessionalGroupName());
        $this->assertEquals(self::PARTICIPANTS, $dto->getNumberOfParticipants());
        $this->assertEquals(self::TYPE, $dto->getType());
        $this->assertEquals(self::DISPLAY_DATE, $dto->getDate());
        $this->assertEquals(self::FORMS_POST, $dto->getNumberOfFormsReturnedPost());
        $this->assertEquals(self::FORMS_PRE, $dto->getNumberOfFormsReturnedPre());
        $this->assertEquals($this->getPresentationDtoMemberArray(), $dto->getMembersArray());
        $this->assertEquals(SurveyTestCase::ID, $dto->getSurveyId());
        $this->assertEquals(91, $dto->getPreFormsPercentage());
        $this->assertEquals(100, $dto->getPostFormsPercentage());
        $this->assertEquals(self::NOTES, $dto->getNotes());
	    $this->assertEquals(self::BEFORE_PERCENTAGE, $dto->getCorrectBeforePercentage());
	    $this->assertEquals(self::AFTER_PERCENTAGE, $dto->getCorrectAfterPercentage());
	    $this->assertEquals(self::EFFECTIVENESS, $dto->getEffectivenessPercentage());
    }

    public function getPresentationDtoMemberArray()
    {
        $member = MemberTestCase::createValidMember();
        return array(
            $member->getId() => array(
                'fullname'=> $member->getFullName(),
                'status' => $member->getStatus()
                )
            );
    }
}
