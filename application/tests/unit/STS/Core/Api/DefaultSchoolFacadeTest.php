<?php
namespace STS\Core\Api;

use STS\Domain\Member;
use STS\Domain\School\Specification\MemberSchoolSpecification;
use STS\Domain\School;
use STS\Domain\Location\Area;
use STS\Core\Api\DefaultSchoolFacade;
use STS\Core\Location\MongoAreaRepository;
use STS\TestUtilities\SchoolTestCase;
use STS\TestUtilities\Location\AreaTestCase;

class DefaultSchoolFacadeTest extends SchoolTestCase
{
    /**
     * @test
     */
    public function validGetAllSchoolsWithNoSpec()
    {
        $facade = new DefaultSchoolFacade($this->getMockSchoolRepository(), $this->getMockAreaRepository());
        $schoolDtos = $facade->getSchoolsForSpecification(null);
        $this->assertCount(2, $schoolDtos);
    }
    /**
     * @test
     */
    public function validGetSchoolsPerMemberSpec()
    {
        $facade = new DefaultSchoolFacade($this->getMockSchoolRepository(), $this->getMockAreaRepository());
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
        $facade = new DefaultSchoolFacade($this->getMockSchoolRepository(), $this->getMockAreaRepository());
        $types = $facade->getSchoolTypes();
        $this->assertEquals(
            array(
                'TYPE_NP'       => 'NP',
                'TYPE_PA'       => 'PA',
                'TYPE_NURSING'  =>'Nursing',
                'TYPE_MEDICAL'  =>'Medical',
                'TYPE_OTHER'    => 'Other'
            ),
            $types
        );
    }

    private function getMockAreaRepository()
    {
        return \Mockery::mock('STS\Core\Location\MongoAreaRepository');
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

    /**
     * @test
     */
    public function validUpdateSchool()
    {
        $updatedSchoolName = 'Updated School Name';
        $oldSchool = $this->getValidSchool();
        $school = $this->getValidSchool();
        $school->setName($updatedSchoolName);
        $schoolRepository = \Mockery::mock('STS\Core\School\MongoSchoolRepository', array('load'=> $oldSchool, 'save'=>$school));
        $areaRepository = \Mockery::mock('STS\Core\Location\MongoAreaRepository', array('load'=>AreaTestCase::createValidArea()));
        $facade = new DefaultSchoolFacade($schoolRepository, $areaRepository);
        $updatedSchoolDto = $facade->updateSchool(
            $school->getId(),
            $school->getName(),
            $school->getArea()->getId(),
            'TYPE_OTHER',
            $school->getNotes(),
            $school->getAddress()->getLineOne(),
            $school->getAddress()->getLineTwo(),
            $school->getAddress()->getCity(),
            $school->getAddress()->getState(),
            $school->getAddress()->getZip()
        );
        $this->assertInstanceOf('STS\Core\School\SchoolDto', $updatedSchoolDto);
        $this->assertEquals($updatedSchoolName, $updatedSchoolDto->getName());
    }
}
