<?php
namespace STS\Core\Member;

use STS\TestUtilities\MemberTestCase;

class MongoMemberRepositoryTest extends MemberTestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $mongoDb = \Mockery::mock('MongoDB');
        $repo    = new MongoMemberRepository($mongoDb);
        $this->assertInstanceOf('STS\Core\Member\MongoMemberRepository', $repo);
    }

    /**
     * @test
     */
    public function itShouldSetCanBeDeletedToFalseForPresentationAssociation()
    {
        //givens
        $mockMongoDb = \Mockery::mock('MongoDB');
        $mockMongoDb->shouldReceive('selectCollection')->andReturn($mockMongoDb);
        $mockMongoDb->shouldReceive('findOne')
                    ->andReturn(array(
                            '_id'      => new \MongoId("50234bc4fe65f50a9579a8cd"),
                            'legacyid' => 0,
                            'fname'    => "Member",
                            'lname'    => "User",
                            'fullname' => "Member User",
                            'email'    => "member.user@email.com"
                        )
                    );
        $mockMongoDb->shouldReceive('find')->andReturn($mockMongoDb);
        $mockMongoDb->shouldReceive('count')->andReturn(1);
        $repo = new MongoMemberRepository($mockMongoDb);
        //whens
        $member = $repo->load(self::ID);
        //thens
        $this->assertFalse($member->canBeDeleted());
    }

    /**
     * @test
     */
    public function itCanBeDeletedWithNoPresentationAssociation()
    {
        $mockMongoDb = \Mockery::mock('MongoDB');
        $mockMongoDb->shouldReceive('selectCollection')->andReturn($mockMongoDb);
        $mockMongoDb->shouldReceive('findOne')
                    ->andReturn(array(
                            '_id'      => new \MongoId("50234bc4fe65f50a9579a8cd"),
                            'legacyid' => 0,
                            'fname'    => "Member",
                            'lname'    => "User",
                            'fullname' => "Member User",
                            'email'    => "member.user@email.com"
                        )
                    );
        $mockMongoDb->shouldReceive('find')->andReturn($mockMongoDb);
        $mockMongoDb->shouldReceive('count')->andReturn(0);
        $repo = new MongoMemberRepository($mockMongoDb);
        //whens
        $member = $repo->load(self::ID);
        //thens
        $this->assertTrue($member->canBeDeleted());
    }

    /**
     * @test
     */
    public function validDeleteMember()
    {
        $mockMongoDb = \Mockery::mock('MongoDB');
        $mockMongoDb->shouldReceive('selectCollection->remove')->andReturn(array('ok' => 1));
        $repo = new MongoMemberRepository($mockMongoDb);
        //whens
        $results = $repo->delete(self::ID);
        //thens
        $this->assertTrue($results);
    }


    /**
     * @test
     */
    public function itShouldLoadTheUserEmailIfTheMemberEmailIsNotFound()
    {
        //givens
        $mockMongoDb = \Mockery::mock('MongoDB');
        $mockMongoDb->shouldReceive('selectCollection')->andReturn($mockMongoDb);
        $areas = $this->getTestAreaData();
        $mockMongoDb->shouldReceive('findOne')
                    ->withAnyArgs()
                    ->andReturn($this->getOlderMemberDataSet(), $areas[0], $areas[1], $areas[0],
                        $areas[1], $areas[0], $areas[1], $this->getOlderUserDataSet());
        $mockMongoDb->shouldReceive('find')->andReturn($mockMongoDb);
        $mockMongoDb->shouldReceive('count')->andReturn(1);
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
            '_id'       => 'muser',
            'email'     => 'member.user@email.com',
            'fname'     => 'Member',
            'lname'     => 'User',
            'legacyid'  => 1,
            'role'      => 'admin',
            'pw'        => '64f5c419fb3ec946807544e7a6b40d16413cadc4',
            'salt'      => 'f95299ac31b9b43d593d6165dc4d79e7',
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
        $address = <<<QQQQ
123 Main Street
Suite 200
Grand Rapids MI 12345
QQQQ;
        $data    = array(
            '_id'             => new \MongoId("50234bc4fe65f50a9579a8cd"),
            'legacyid'        => 0,
            'fname'           => "Member",
            'lname'           => "User",
            'fullname'        => "Member User",
            'type'            => "Survivor",
            'notes'           => "This is an interesting note!",
            'user_id'         => "muser",
            'address'         => $address,
            'status'          => "Deceased",
            'facilitates_for' => array(
                array(
                    '_id' => new \MongoId("502d90100172cda7d649d465"),
                ),
                array(
                    '_id' => new \MongoId("502d90100172cda7d649d461"),
                ),
                'presents_for'    =>
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
        $address = <<<QQQ
123 Main Street
Suite 200
Grand Rapids MI 12345
QQQ;

        $data = array(
            '_id'             => new \MongoId("50234bc4fe65f50a9579a8cd"),
            'legacyid'        => 0,
            'fname'           => "Member",
            'lname'           => "User",
            'fullname'        => "Member User",
            'email'           => "member.user@email.com",
            'type'            => "Survivor",
            'notes'           => "This is an interesting note!",
            'user_id'         => "muser",
            'address'         => $address,
            'status'          => "Deceased",
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
