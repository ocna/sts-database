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
    public function getValidTypes()
    {
        $this
            ->assertEquals(
                array(
                'TYPE_SCHOOL' => 'School',
                'TYPE_HOSPITAL' => 'Hospital',
                'TYPE_NP' => 'NP',
                'TYPE_PA' => 'PA',
                'TYPE_NURSING'=>'Nursing',
                'TYPE_MEDICAL'=>'Medical'
            ), School::getAvailableTypes());
    }

    /**
     * @test
     */
    public function confirmSanitiezedName()
    {
        $school = $this->getValidSchool();

        $bad = ' Name with   spaces  and leading    trailing space ';
        $good = 'Name with spaces and leading trailing space';

        $this->assertEquals($good, $school->getName($school->setName($bad)));
    }



}
