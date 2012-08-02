<?php
use STS\Domain\Survey\Response\SingleResponse;

class SingleResponseTest extends PHPUnit_Framework_TestCase {
    const VALUE = 'Single Response Value';
    /**
     * @test
     */
    public function createValidObject() {
        $response = new SingleResponse(self::VALUE);
        $this->assertEquals(self::VALUE, $response->getResponse());
    }
}
