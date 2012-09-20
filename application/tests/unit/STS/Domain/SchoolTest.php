<?php
use STS\TestUtilities\SchoolTestCase;
use STS\Domain\School;

class SchoolTest extends SchoolTestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $school = $this->getValidSchool();
        $this->assertValidSchool($school);
    }
    
    
    /**
     * @test
     */
    public function getValidTypes(){
        $this->assertEquals(array('School', 'Hospital'), School::getTypes());
    }
}
