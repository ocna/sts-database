<?php
namespace STS\Domain\Member;

use STS\Domain\Member\Diagnosis;

class DiagnosisTest extends \PHPUnit_Framework_TestCase
{
    const DATE = '2012-05-10 11:55:23';
    private $stages = array(
            'I'=>'I',
            'IA'=>'IA',
            'IB'=>'IB',
            'IC'=>'IC',
            'II'=>'II',
            'IIA'=>'IIA',
            'IIB'=>'IIB',
            'IIC'=>'IIC',
            'III'=>'III',
            'IIIA'=>'IIIA',
            'IIIB'=>'IIIB',
            'IIIC'=>'IIIC',
            'IV'=>'IV'
        );
    /**
     * @test
     */
    public function createValidObject()
    {
        $diagnosis = new Diagnosis(self::DATE, 'I');
        $this->assertEquals(self::DATE, $diagnosis->getDate());
        $this->assertEquals('I', $diagnosis->getStage());
    }

    /**
     * @test
     */
    public function confirmCorrectStages()
    {
        $this->assertEquals($this->stages, Diagnosis::getAvailableStages());
    }
}
