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
        $repo = new MongoSchoolRepository($mongoDb);
        $this->assertInstanceOf('STS\Core\School\MongoSchoolRepository', $repo);
    }
}
