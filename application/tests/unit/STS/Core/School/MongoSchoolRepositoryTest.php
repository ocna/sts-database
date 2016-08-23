<?php
use STS\Core\School\MongoSchoolRepository;
use STS\TestUtilities\SchoolTestCase;

class MongoSchoolRepositoryTest extends SchoolTestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $mongoDb = Mockery::mock('MongoDB');
        $cache = Mockery::mock('STS\Core\Cache');
        $repo = new MongoSchoolRepository($mongoDb, $cache);
        $this->assertInstanceOf('STS\Core\School\MongoSchoolRepository', $repo);
    }
}
