<?php
namespace STS\Core\Member;

use STS\Core\Member\MongoMemberRepository;
use STS\TestUtilities\MemberTestCase;

class MongoMemberRepositoryTest extends MemberTestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $mongoDb = \Mockery::mock('MongoDB');
        $repo = new MongoMemberRepository($mongoDb);
        $this->assertInstanceOf('STS\Core\Member\MongoMemberRepository', $repo);
    }

    /**
     * @test
     */
    public function itShouldSetCanBeDeletedToFalseForPresentationAssociation()
    {
        //givens
        $mockMongoDb = \Mockery::mock('MongoDB');
        $mockMongoDb->shouldReceive('selectCollection->findOne')
                    ->andReturn(array(
                        '_id' => new \MongoId("50234bc4fe65f50a9579a8cd"),
                        'legacyid' => 0,
                        'fname' => "Member",
                        'lname' => "User",
                        'fullname' => "Member User",
                        'email'=> "member.user@email.com")
                    );
        $mockMongoDb->shouldReceive('selectCollection->find->count')
        ->andReturn(1);
        $repo = new MongoMemberRepository($mockMongoDb);
        //whens
        $member = $repo->load(self::ID);
        //thens
        $this->assertFalse($member->canBeDeleted());
    }
    
    /**
     * @test
     */
    public function itShouldLoadTheUserEmailIfTheMemberEmailIsNotFound()
    {
        //givens
        $mockMongoDb = \Mockery::mock('MongoDB');
        $areas = $this->getTestAreaData();
        $mockMongoDb->shouldReceive('selectCollection->findOne')
                    ->withAnyArgs()
                    ->andReturn($this->getOlderMemberDataSet(), $areas[0], $areas[1],$areas[0], $areas[1],$areas[0], $areas[1], $this->getOlderUserDataSet());
        $mockMongoDb->shouldReceive('selectCollection->find->count')->andReturn(1);
        $repo = new MongoMemberRepository($mockMongoDb);
        //whens
        $member = $repo->load(self::ID);
        //thens
        $this->assertEquals(self::EMAIL, $member->getEmail());
    }

     /**
      * getOlderUserDataSet returns dataset that could exist through 1.4.0 release
      * 
      * @return array $data
      */
    private function getOlderUserDataSet()
    {
        $data = array(
            '_id' => 'muser',
            'email' => 'member.user@email.com',
            'fname' => 'Member',
            'lname' => 'User',
            'legacyid' => 1,
            'role' => 'admin',
            'pw' => '64f5c419fb3ec946807544e7a6b40d16413cadc4',
            'salt' => 'f95299ac31b9b43d593d6165dc4d79e7',
            'member_id' => array('_id' => new \MongoId('50234bc4fe65f50a9579a8cd'))
            );
        return $data;
    }

     /**
      * getOlderDataSet returns dataset that could exist through 1.4.0 release
      * 
      * @return array $data
      */
    private function getOlderMemberDataSet()
    {
        $data = array(
            '_id' => new \MongoId("50234bc4fe65f50a9579a8cd"),
            'legacyid' => 0,
            'fname' => "Member",
            'lname' => "User",
            'fullname' => "Member User",
            'type' => "Survivor",
            'notes' => "This is an interesting note!",
            'user_id' => "muser",
            'address' => array(
                'line_one' => "123 Main Street",
                'line_two' => "Suite 200",
                'city' => "Grand Rapids",
                'state' => "MI",
                'zip' => "12345"
            ),
            'status' => "Deceased",
            'facilitates_for' => array(
                     array(
                        '_id' => new \MongoId("502d90100172cda7d649d465"),
                    ),
                     array(
                        '_id' => new \MongoId("502d90100172cda7d649d461"),
                ),
                'presents_for' =>
                     array(
                        '_id' => new \MongoId("502d90100172cda7d649d465"),
                    ),
                     array(
                        '_id' => new \MongoId("502d90100172cda7d649d461"),
                ),
                'coordinates_for' =>
                     array(
                        '_id' => new \MongoId("502d90100172cda7d649d465"),
                    ),
                     array(
                        '_id' => new \MongoId("502d90100172cda7d649d461"),
                    )
            )
        );
        return $data;
    }

    private function getMemberDataSet()
    {
        $data = array(
            '_id' => new \MongoId("50234bc4fe65f50a9579a8cd"),
            'legacyid' => 0,
            'fname' => "Member",
            'lname' => "User",
            'fullname' => "Member User",
            'email'=> "member.user@email.com",
            'type' => "Survivor",
            'notes' => "This is an interesting note!",
            'user_id' => "muser",
            'address' => array(
                'line_one' => "123 Main Street",
                'line_two' => "Suite 200",
                'city' => "Grand Rapids",
                'state' => "MI",
                'zip' => "12345"
            ),
            'status' => "Deceased",
            'facilitates_for' => array(
                     array(
                        '_id' => new \MongoId("502d90100172cda7d649d465"),
                    ),
                     array(
                        '_id' => new \MongoId("502d90100172cda7d649d461"),
                ),
                'presents_for' =>
                     array(
                        '_id' => new \MongoId("502d90100172cda7d649d465"),
                    ),
                     array(
                        '_id' => new \MongoId("502d90100172cda7d649d461"),
                ),
                'coordinates_for' =>
                     array(
                        '_id' => new \MongoId("502d90100172cda7d649d465"),
                    ),
                     array(
                        '_id' => new \MongoId("502d90100172cda7d649d461"),
                    )
            )
        );
        return $data;
    }
}
