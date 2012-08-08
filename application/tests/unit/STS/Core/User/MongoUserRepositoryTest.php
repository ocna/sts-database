<?php
use STS\Core\User\MongoUserRepository;
use STS\TestUtilities\UserTestCase;

class MongoUserRepositoryTest extends UserTestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $mongoDb = Mockery::mock('MongoDB');
        $repo = new MongoUserRepository($mongoDb);
        $this->assertInstanceOf('STS\Core\User\MongoUserRepository', $repo);
    }
}
