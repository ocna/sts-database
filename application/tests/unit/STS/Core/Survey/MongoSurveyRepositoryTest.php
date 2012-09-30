<?php
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
    public function teardown()
    {
        \Mockery::close();
    }
}
