<?php
use STS\Domain\Survey\Question;
use STS\Domain\Survey\Response\PairResponse;
use STS\Domain\Survey\Response\SingleResponse;
use STS\Domain\Survey\Question\MultipleChoice;

class MultipleAnswerTest extends PHPUnit_Framework_TestCase {
    /**
     * @test
     */
    public function createValidObject() {
        $question = $this->getQuestionObject();
        $this->assertEquals("Choice 2", $question->getChoice(2));
        $this->assertNull($question->getChoice(4));
    }
    /**
     * @test
     */
    public function addAnswerPairsForChoices() {
        $question = $this->getQuestionObject();
        $response = new PairResponse(4, 10);
        $question->addResponse(2, $response);
        $this->assertEquals($response, $question->getResponse(2));
        $this->assertNull($question->getResponse(1));
        $this->assertNull($question->getResponse(4));
    }
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Choice does not exist.
     */
    public function throwExceptionForGivingAResponseToNonExistantChoice() {
        $question = $this->getQuestionObject();
        $response = new PairResponse(4, 10);
        $question->addResponse(4, $response);
    }
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Question expects a response single.
     */
    public function throwExceptionPassingResponsePairToSingleQuestion() {
        $question = $this->getQuestionObject();
        $response = new PairResponse(4, 10);
        $question->isAsked(Question::AFTER);
        $question->addResponse(4, $response);
    }
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Question expects a response pair.
     */
    public function throwExceptionPassingResponseSingleToPairQuestion() {
        $question = $this->getQuestionObject();
        $response = new SingleResponse(4);
        $question->addResponse(4, $response);
    }
    private function getQuestionObject() {
        $question = new MultipleChoice();
        $question->addChoice(1, "Choice 1")
                 ->addChoice(2, "Choice 2")
                 ->addChoice(3, "Choice 3");
        return $question;
    }
}
