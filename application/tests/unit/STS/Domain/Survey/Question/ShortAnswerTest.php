<?php
use STS\Domain\Survey\Response\PairResponse;
use STS\Domain\Survey\Question\ShortAnswer;
use STS\Domain\Survey\Response\SingleResponse;

class ShortAnswerTest extends PHPUnit_Framework_TestCase {
    const BEFORE_REPSONSE = "This is a long pre presentation textual response!";
    const AFTER_RESPONSE = "This is a long post presentation textual response!";
    /**
     * @test
     */
    public function createValidObject() {
        $question = new ShortAnswer();
        $this->assertNull($question->getResponse());
        $response = new SingleResponse(self::AFTER_RESPONSE);
        $question->setResponse($response);
        $this->assertEquals($response, $question->getResponse());
        $responsePair = new PairResponse(self::BEFORE_REPSONSE,
                self::AFTER_RESPONSE);
        $question->setResponse($responsePair);
        $this->assertEquals($responsePair, $question->getResponse());
    }
}
