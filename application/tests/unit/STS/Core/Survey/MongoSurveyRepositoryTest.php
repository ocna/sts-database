<?php
namespace STS\Core\Survey;

use STS\TestUtilities\SurveyTestCase;
use STS\Core\Survey\MongoSurveyRepository;

class MongoSurveyRepositoryTest extends SurveyTestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $repo = $this->getRepoWithMockedDeps();
        $this->assertInstanceOf('STS\Core\Survey\MongoSurveyRepository', $repo);
    }

    /**
     * @test
     */
    public function validLoadSurvey()
    {
        $mongoDb = \Mockery::mock('MongoDB');
        $mongoDb->shouldReceive('selectCollection')->andReturn($mongoDb);
        $mongoDb->shouldReceive('findOne')->andReturn($this->getValidSurveyData());
        $repo = new MongoSurveyRepository($mongoDb);
        $survey = $repo->load(self::ID);
        $this->assertEquals($this->getValidSurvey(), $survey);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Instance of Survey expected.
     */
    public function throwExceptionForNonSurveyPassedToSave()
    {
        $repo = $this->getRepoWithMockedDeps();
        $repo->save(null);
    }
    private function getRepoWithMockedDeps()
    {
        $mongoDb = \Mockery::mock('MongoDB');
        $mongoDb->shouldReceive('->survey->update')->with(\Mockery::any())->andReturn(array());
        $repo = new MongoSurveyRepository($mongoDb);
        return $repo;
    }
}
