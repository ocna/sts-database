<?php
namespace STS\Core\Api;

use STS\Core;
use STS\TestUtilities\SchoolTestCase;
use STS\Core\Api\DefaultSchoolFacade;
use STS\TestUtilities\Location\AreaTestCase;
use STS\Core\School\SchoolDtoAssembler;

class DefaultSchoolFacadeTest extends SchoolTestCase
{
    /**
     * @test
     */
    public function findAllSchoolsPerSpec()
    {
        $facade = $this->loadFacadeInstance();
        $schools = $facade->getSchoolsForSpecification(null);
        $this->assertTrue(is_array($schools));
        $this->assertInstanceOf('STS\Core\School\SchoolDTO', $schools[0]);
    }
    private function loadFacadeInstance()
    {
        $core = Core::getDefaultInstance();
        $facade = $core->load('SchoolFacade');
        return $facade;
    }

    /**
     * @test
     */
    public function validUpdateSchool()
    {
        //givens
        $updatedSchoolName = 'Updated School Name';
        $updatedCity = 'Updated City';
        $facade = $this->loadFacadeInstance();
        $school = $facade->getSchoolById(self::ID);
        //whens
        $facade->updateSchool(
            $school->getId(),
            $updatedSchoolName,
            AreaTestCase::ID,
            'TYPE_SCHOOL',
            $school->getNotes(),
            $school->getAddressLineOne(),
            $school->getAddressLineTwo(),
            $updatedCity,
            $school->getAddressState(),
            $school->getAddressZip()
        );


        //thens
        $updatedSchoolDto = $facade->getSchoolById(self::ID);
        $this->assertInstanceOf('STS\Core\School\SchoolDto', $updatedSchoolDto);
        $this->assertEquals($updatedSchoolName, $updatedSchoolDto->getName());
        $this->assertEquals($updatedCity, $updatedSchoolDto->getAddressCity());
        //reset
        $facade->updateSchool(
            $school->getId(),
            self::NAME,
            AreaTestCase::ID,
            'TYPE_SCHOOL',
            $school->getNotes(),
            $school->getAddressLineOne(),
            $school->getAddressLineTwo(),
            $school->getAddressCity(),
            $school->getAddressState(),
            $school->getAddressZip()
        );
        $updatedSchoolDto = $facade->getSchoolById(self::ID);
        $this->assertValidSchoolDto($updatedSchoolDto);
    }
}
