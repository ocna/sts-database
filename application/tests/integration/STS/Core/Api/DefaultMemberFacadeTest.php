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
            null,
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

        $this->assertNotNull($newMemberDto->getId());
        $this->assertValidMemberDto($newMemberDto, array('id', 'firstName', 'associatedUserId'));
        $this->cleanUp[] = array('collection'=>'member','_id'=> new \MongoId($newMemberDto->getId()));
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
