<?php
use STS\Core;
use STS\TestUtilities\SchoolTestCase;
use STS\Core\Api\DefaultSchoolFacade;

class DefaultSchoolFacadeTest extends SchoolTestCase
{
    /**
     * @test
     */
    public function findAllSchools()
    {
        $facade = $this->loadFacadeInstance();
        $schools = $facade->getAllSchools();
        $this->assertTrue(is_array($schools));
        $this->assertInstanceOf('STS\Core\School\SchoolDTO', $schools[0]);
    }
    private function loadFacadeInstance()
    {
        $core = Core::getDefaultInstance();
        $facade = $core->load('SchoolFacade');
        return $facade;
    }
}
