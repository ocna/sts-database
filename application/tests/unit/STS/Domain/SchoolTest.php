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
}
