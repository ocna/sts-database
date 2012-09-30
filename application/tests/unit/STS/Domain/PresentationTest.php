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
        $this
            ->assertEquals(array(
                    'TYPE_MED' => 'MED', 'TYPE_PA' => 'PA', 'TYPE_NP' => 'NP', 'TYPE_NS' => 'NS',
                    'TYPE_RES_OBGYN' => 'RES OBGYN', 'TYPE_RES_INT' => 'RES INT', 'TYPE_OTHER' => 'OTHER'
            ), Presentation::getAvailableTypes());
    }
    /**
     * @test
     */
    public function createValidInstanceOfPresentation()
    {
        $presentation = $this->createValidObject();
        $this->assertValidObject($presentation);
    }
}
