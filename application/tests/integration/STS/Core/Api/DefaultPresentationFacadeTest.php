<?php
namespace STS\Core\Api;

use STS\TestUtilities\PresentationTestCase;
use STS\Core;
use STS\Core\Api\DefaultPresentationFacade;
use STS\TestUtilities\MongoUtility;
use STS\TestUtilities\SchoolTestCase;

class DefaultPresentationFacadeTest extends PresentationTestCase
{
    protected $cleanUp = array();

    /**
     * @test
     */
    public function getDefaultInstance()
    {
        $this->assertInstanceOf('STS\Core\Api\DefaultPresentationFacade', $this->loadFacadeInstance());
    }

    /**
     * @test
     */
    public function itShouldGetOnePresentationById()
    {
        $facade = $this->loadFacadeInstance();
        $dto = $facade->getPresentationById('5068b274559ac99cfe2f6796');
        $this->assertEquals('5068b274559ac99cfe2f6796', $dto->getId());
    }


    /**
     * @test
     */
    public function adminUserShouldSeeAllPresentations()
    {
        $facade = $this->loadFacadeInstance();
        $presentations = $facade->getPresentationsForUserId('muser');
        $this->assertTrue(is_array($presentations));
        $this->assertCount(4, $presentations);
        $this->assertInstanceOf('STS\Core\Presentation\PresentationDto', $presentations[0]);
    }

    /**
     * @test
     */
    public function savePresentation()
    {
        $schoolId='502314eec6464712c1e7060e';
        $schoolClass = DefaultPresentationFacade::locationTypeSchool;
        $typeCode='TYPE_NP';
        $date='08/09/2012';
        $notes ='These are some notes!';
        $memberIds = array('50318d42066b83068e5d9452');
        $enteredByUserId = 'auser';
        $participants = 20;
        $forms = 18;
        $preForms = 20;
        $surveyId = '5035af240172cda7d649d477';

        $facade = $this->loadFacadeInstance();
        $presentationDto = $facade->savePresentation($enteredByUserId, $schoolId, $schoolClass, $typeCode,
            $date, $notes, $memberIds, $participants, $forms, $surveyId, $preForms);
        $this->assertNotNull($presentationDto->getId());
        $this->assertEquals($forms, $presentationDto->getNumberOfFormsReturnedPost());
        $this->assertEquals($preForms, $presentationDto->getNumberOfFormsReturnedPre());
        $this->cleanUp[] = array('collection'=>'presentation','_id'=> new \MongoId($presentationDto->getId()));
    }

    /**
     * @test
     */
    public function validUpdatePresentation()
    {
        //givens
        $updatedNotes = 'These are updated notes.';
        $updatedPostForms = 123;
        $facade = $this->loadFacadeInstance();
        $dto= $facade->getPresentationById(self::ID);
        //whens
        $facade->updatePresentation(
            self::ID, $dto->getLocationId(), $dto->getLocationClass(), 'TYPE_MED', $dto->getDate(), $updatedNotes,
            array_keys($dto->getMembersArray()), $dto->getNumberOfParticipants(), $updatedPostForms,
            $dto->getNumberOfFormsReturnedPre()
        );
        $school = SchoolTestCase::createValidSchool();
        //thens
        $updatedPresentationDto = $facade->getPresentationById(self::ID);
        $this->assertInstanceOf('STS\Core\Presentation\PresentationDto', $updatedPresentationDto);
        $this->assertEquals($updatedNotes, $updatedPresentationDto->getNotes());
        $this->assertEquals($updatedPostForms, $updatedPresentationDto->getNumberOfFormsReturnedPost());
        //reset
        $facade->updatePresentation(
            self::ID, $dto->getLocationId(), $dto->getLocationClass(), 'TYPE_MED', $dto->getDate(), $dto->getNotes(),
            array_keys($dto->getMembersArray()), $dto->getNumberOfParticipants(),
            $dto->getNumberOfFormsReturnedPost(), $dto->getNumberOfFormsReturnedPre()
        );
        $updatedPresentationDto = $facade->getPresentationById(self::ID);
        $this->assertValidPresentationDto($updatedPresentationDto);
    }


    private function loadFacadeInstance()
    {
        $core = Core::getDefaultInstance();
        $facade = $core->load('PresentationFacade');
        return $facade;
    }

    public function tearDown()
    {
        $mongoDb = MongoUtility::getDbConnection();
        foreach ($this->cleanUp as $object) {
            $mongoDb->selectCollection($object['collection'])->remove(
                array('_id' => $object['_id']),
                array("justOne" => true, "safe" => true)
            );
        }
    }
}
