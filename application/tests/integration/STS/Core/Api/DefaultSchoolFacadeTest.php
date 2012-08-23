<?php
use STS\Core;
use STS\TestUtilities\SchoolTestCase;
use STS\Core\Api\DefaultSchoolFacade;

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
}
