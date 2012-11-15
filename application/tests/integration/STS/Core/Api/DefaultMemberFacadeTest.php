<?php
namespace STS\Core\Api;

use STS\Core;
use STS\TestUtilities\MemberTestCase;
use STS\TestUtilities\MongoUtility;
use STS\Core\Api\DefaultMemberFacade;
use STS\TestUtilities\Location\AddressTestCase;

class DefaultMemberFacadeTest extends MemberTestCase
{
    protected $cleanUp = array();
    /**
     * @test
     */
    public function validGetMembersMatchingNullCriteria()
    {
        $facade = $this->loadFacadeInstance();
        $allMembers = $facade->getAllMembers();
        $membersMatching = $facade->getMembersMatching(null);
        $this->assertEquals(count($allMembers), count($membersMatching));
    }
    /**
     * @test
     */
    public function validUpdateMember()
    {
        //givens
        $updatedFirstName = 'Test User';
        $presentsFor = array_keys($this->getValidPresentsForAreasArray());
        $facilitatesFor = array_keys($this->getValidFacilitatesForAreasArray());
        $coordinatesFor = array_keys($this->getValidCoordinatesForAreasArray());
        //whens
        $facade = $this->loadFacadeInstance();
        $facade->updateMember(
            self::ID,
            $updatedFirstName,
            self::LAST_NAME,
            self::TYPE,
            self::STATUS,
            self::NOTES,
            $presentsFor,
            $facilitatesFor,
            $coordinatesFor,
            'muser',
            AddressTestCase::LINE_ONE,
            AddressTestCase::LINE_TWO,
            AddressTestCase::CITY,
            AddressTestCase::STATE,
            AddressTestCase::ZIP,
            self::EMAIL,
            self::DISPLAY_DATE_TRAINED,
            array('date'=>self::DISPLAY_DATE_TRAINED, 'stage'=>'I'),
            array(
                'work'=>'301-555-1234',
                'cell'=>'555-123-9999'
            )
        );
        //thens
        $updatedMemberDto = $facade->getMemberById(self::ID);
        $this->assertInstanceOf('STS\Core\Member\MemberDto', $updatedMemberDto);
        $this->assertEquals($updatedFirstName, $updatedMemberDto->getFirstName());
        //reset
        $facade->updateMember(
            self::ID,
            self::FIRST_NAME,
            self::LAST_NAME,
            self::TYPE,
            self::STATUS,
            self::NOTES,
            $presentsFor,
            $facilitatesFor,
            $coordinatesFor,
            'muser',
            AddressTestCase::LINE_ONE,
            AddressTestCase::LINE_TWO,
            AddressTestCase::CITY,
            AddressTestCase::STATE,
            AddressTestCase::ZIP,
            self::EMAIL,
            self::DISPLAY_DATE_TRAINED,
            array('date'=>self::DISPLAY_DATE_TRAINED, 'stage'=>'I'),
            array(
                'work'=>'301-555-1234',
                'cell'=>'555-123-9999'
            )
        );
        $updatedMemberDto = $facade->getMemberById(self::ID);
        $this->assertValidMemberDto($updatedMemberDto);
    }
    /**
     * @test
     */
    public function validSaveMember()
    {
        $facade = $this->loadFacadeInstance();
        $presentsFor = array_keys($this->getValidPresentsForAreasArray());
        $facilitatesFor = array_keys($this->getValidFacilitatesForAreasArray());
        $coordinatesFor = array_keys($this->getValidCoordinatesForAreasArray());
        $newMemberDto = $facade->saveMember(
            'Test User',
            self::LAST_NAME,
            self::TYPE,
            self::STATUS,
            self::NOTES,
            $presentsFor,
            $facilitatesFor,
            $coordinatesFor,
            'muser',
            AddressTestCase::LINE_ONE,
            AddressTestCase::LINE_TWO,
            AddressTestCase::CITY,
            AddressTestCase::STATE,
            AddressTestCase::ZIP,
            self::EMAIL,
            self::DISPLAY_DATE_TRAINED,
            array('date'=>self::DISPLAY_DATE_TRAINED, 'stage'=>'I'),
            array(
                'work'=>'301-555-1234',
                'cell'=>'555-123-9999'
            )
        );

        $this->assertNotNull($newMemberDto->getId());
        $this->assertValidMemberDto($newMemberDto, array('id', 'firstName', 'associatedUserId'));
        $this->cleanUp[] = array('collection'=>'member','_id'=> new \MongoId($newMemberDto->getId()));
    }

    /**
     * @test
     */
    public function validDeleteAMemberWithNoAssociations()
    {
        //givens
        $facade = $this->loadFacadeInstance();
        $presentsFor = array_keys($this->getValidPresentsForAreasArray());
        $facilitatesFor = array_keys($this->getValidFacilitatesForAreasArray());
        $coordinatesFor = array_keys($this->getValidCoordinatesForAreasArray());
        $newMemberDto = $facade->saveMember(
            'Delete Simple',
            self::LAST_NAME,
            self::TYPE,
            self::STATUS,
            self::NOTES,
            $presentsFor,
            $facilitatesFor,
            $coordinatesFor,
            null,
            AddressTestCase::LINE_ONE,
            AddressTestCase::LINE_TWO,
            AddressTestCase::CITY,
            AddressTestCase::STATE,
            AddressTestCase::ZIP,
            self::EMAIL,
            self::DISPLAY_DATE_TRAINED,
            null,
            array()
        );
        //whens
        $results = $facade->deleteMember($newMemberDto->getId());
        //thens
        $this->assertTrue($results);
    }
    
    /**
     * @test
     */
    public function searchForMembersByName()
    {
        $facade = $this->loadFacadeInstance();
        $members = $facade->searchForMembersByNameWithSpec('member us', null);
        $this->assertTrue(is_array($members), 'The search did not return an array!');
        $this->assertValidMemberDto($members[0]);
    }
    /**
     * @test
     */
    public function getValidMemberById()
    {
        $facade = $this->loadFacadeInstance();
        $memberDto = $facade->getMemberById(self::ID);
        $this->assertValidMemberDto($memberDto);
    }
    private function loadFacadeInstance()
    {
        $core = Core::getDefaultInstance();
        $facade = $core->load('MemberFacade');
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
