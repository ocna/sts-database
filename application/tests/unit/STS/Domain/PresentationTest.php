<?php
use STS\Domain\Presentation;

class PresentationTest extends \PHPUnit_Framework_TestCase
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
}
