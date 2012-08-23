<?php
use STS\Core\Member\MongoMemberRepository;
use STS\TestUtilities\MemberTestCase;

class MongoMemberRepositoryTest extends MemberTestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $mongoDb = Mockery::mock('MongoDB');
        $repo = new MongoMemberRepository($mongoDb);
        $this->assertInstanceOf('STS\Core\Member\MongoMemberRepository', $repo);
    }
}
