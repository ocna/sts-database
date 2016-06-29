<?php
use STS\Core\Presentation\MongoPresentationRepository;

class MongoPresentationRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Instance of Presentation expected.
     */
    public function throwExceptionForInvalidSavePresentation()
    {
        $repo = $this->getValidMockedRepo();
        $presentation = new stdClass();
        $repo->save($presentation);
    }
    /**
     * @test
     */
    public function createValidObject()
    {
        $repo = $this->getValidMockedRepo();
        $this->assertInstanceOf('STS\Core\Presentation\MongoPresentationRepository', $repo);
    }
    private function getValidMockedRepo()
    {
        $mongoDb = Mockery::mock('MongoDB');
        $cache = Mockery::mock('STS\Core\Cache');
        $repo = new MongoPresentationRepository($mongoDb, $cache);
        return $repo;
    }
}
