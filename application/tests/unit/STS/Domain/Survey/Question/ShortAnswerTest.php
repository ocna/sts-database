<?php
use STS\Domain\Survey\Response\PairResponse;
use STS\Domain\Survey\Question\ShortAnswer;
use STS\Domain\Survey\Question;
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
        $question->isAsked(Question::AFTER);
        $question->setResponse($response);
        $this->assertEquals($response, $question->getResponse());
    }
    /**
     * @test
     */
    public function addPairResponse() {
        $question = new ShortAnswer();
        $responsePair = new PairResponse(self::BEFORE_REPSONSE,
                self::AFTER_RESPONSE);
        $question->setResponse($responsePair);
        $this->assertEquals($responsePair, $question->getResponse());
    }
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Question expects a response single.
     */
    public function throwExceptionPassingResponsePairToSingleQuestion() {
        $question = new ShortAnswer();
        $response = new PairResponse(self::BEFORE_REPSONSE,
                self::AFTER_RESPONSE);
        $question->isAsked(Question::AFTER);
        $question->setResponse($response);
    }
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Question expects a response pair.
     */
    public function throwExceptionPassingResponseSingleToPairQuestion() {
        $question = new ShortAnswer();
        $response = new SingleResponse(self::AFTER_RESPONSE);
        $question->setResponse($response);
    }
}
