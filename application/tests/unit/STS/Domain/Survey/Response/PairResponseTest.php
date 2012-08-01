<?php
use STS\Domain\Survey\Response\PairResponse;

class PairResponseTest extends PHPUnit_Framework_TestCase {
    const BEFORE_VALUE = 'Before Response Value';
    const AFTER_VALUE = 'After Response Value';
    /**
     * @test
     */
    public function createValidObject() {
        $response = new PairResponse(self::BEFORE_VALUE, self::AFTER_VALUE);
        $this->assertEquals(self::BEFORE_VALUE, $response->getBeforeResponse());
        $this->assertEquals(self::AFTER_VALUE, $response->getAfterResponse());
    }
}
