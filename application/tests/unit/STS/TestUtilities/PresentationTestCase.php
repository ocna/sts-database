<?php
namespace STS\TestUtilities;
use STS\Domain\Presentation;

class PresentationTestCase extends \PHPUnit_Framework_TestCase
{
    const ENTERED_BY_USER_ID = 'muser';
    const ID = '50234bc4fe65f50a9579a8cd';
    const TYPE = 'MED';
    const DATE = '2012-05-10 11:55:23';
    const NOTES = 'The presentation went quite well I must say.';
    const PARTICIPANTS = 203;
    const FORMS = 198;
    protected function createValidObject()
    {
        $school = $this->getMock('STS\Domain\School');
        $members = array(
            $this->getMock('STS\Domain\Member')
        );
        $survey = $this->getMockBuilder('STS\Domain\Survey')->disableOriginalConstructor()->getMock();
        $presentation = new Presentation();
        $presentation->setEnteredByUserId(self::ENTERED_BY_USER_ID)->setId(self::ID)->setLocation($school)->setType(self::TYPE)->setDate(self::DATE)
            ->setNotes(self::NOTES)->setMembers($members)->setNumberOfParticipants(self::PARTICIPANTS)
            ->setNumberOfFormsReturned(self::FORMS)->setSurvey($survey);
        return $presentation;
    }
    protected function assertValidObject($presentation)
    {
        $this->assertEquals(self::ID, $presentation->getId());
        $this->assertEquals(self::ENTERED_BY_USER_ID, $presentation->getEnteredByUserId());
        $this->assertEquals(self::TYPE, $presentation->getType());
        $this->assertEquals(self::DATE, $presentation->getDate());
        $this->assertEquals(self::NOTES, $presentation->getNotes());
        $this->assertEquals(self::PARTICIPANTS, $presentation->getNumberOfParticipants());
        $this->assertEquals(self::FORMS, $presentation->getNumberOfFormsReturned());
        $this->assertInstanceOf('STS\Domain\School', $presentation->getLocation());
        $this->assertInstanceOf('STS\Domain\Survey', $presentation->getSurvey());
        $this->assertTrue(is_array($presentation->getMembers()));
        $this->assertInstanceOf('STS\Domain\Member', array_pop($presentation->getMembers()));
    }
}
