<?php
use STS\Domain\Member;
use STS\Domain\School\Specification\MemberSchoolSpecification;
use STS\Domain\School;
use STS\Domain\Location\Area;
use STS\Core\Api\DefaultSchoolFacade;
use \Mockery;

class DefaultSchoolFacadeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function validGetAllSchoolsWithNoSpec()
    {
        $facade = new DefaultSchoolFacade($this->getMockSchoolRepository());
        $schoolDtos = $facade->getSchoolsForSpecification(null);
        $this->assertCount(2, $schoolDtos);
    }
    /**
     * @test
     */
    public function validGetSchoolsPerMemberSpec()
    {
        $facade = new DefaultSchoolFacade($this->getMockSchoolRepository());
        $member = new Member();
        $areaA = new Area();
        $spec = new MemberSchoolSpecification($member->canPresentForArea($areaA->setId(11)));
        $schoolDtos = $facade->getSchoolsForSpecification($spec);
        $this->assertCount(1, $schoolDtos);
        $dto = $schoolDtos[0];
        $this->assertEquals(1, $dto->getId());
    }
    /**
     * @test 
     */
    public function getCorrectTypes()
    {
        $facade = new DefaultSchoolFacade($this->getMockSchoolRepository());
        $types = $facade->getSchoolTypes();
        $this->assertEquals(School::getTypes(), $types);
    }
    private function getMockSchoolRepository()
    {
        $areaA = new Area();
        $areaB = new Area();
        $schoolA = new School();
        $schoolB = new School();
        $schoolA->setId(1)->setArea($areaA->setId(11));
        $schoolB->setId(2)->setArea($areaB->setId(22));
        $schoolRepository = \Mockery::mock('STS\Core\School\MongoSchoolRepository');
        $schoolRepository->shouldReceive('find')->withNoArgs()
            ->andReturn(array(
                $schoolA, $schoolB
            ));
        return $schoolRepository;
    }
}
