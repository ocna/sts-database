<?php
use STS\Domain\Presentation;
use STS\TestUtilities\PresentationTestCase;

class PresentationTest extends PresentationTestCase
{
    /**
     * @test
     */
    public function getListOfAllowableTypes()
    {
        $types = array(
                'med' => 'MED', 'pa' => 'PA', 'np' => 'NP', 'ns' => 'NS', 'resobgyn' => 'RES OBGYN',
                'resint' => 'RES INT', 'other' => 'OTHER'
        );
        $this->assertEquals($types, Presentation::getTypes());
    }
    /**
     * @test
     */
    public function createValidInstanceOfPresentation()
    {
        $presentation = $this->createValidObject();
        $this->assertValidObject($presentation);
    }
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Supplied presentation type is not recognized.
     */
    public function throwExceptionForInvalidPresentationType()
    {
        $presentation = $this->createValidObject();
        $presentation->setType('bad');
    }
    
}
