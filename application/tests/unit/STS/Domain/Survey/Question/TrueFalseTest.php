<?php
use STS\Domain\Survey\Response\PairResponse;
use STS\Domain\Survey\Question\TrueFalse;

class TrueFalseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $question = $this->getQuestionObject();
        $this->assertEquals("False", $question->getChoice('F'));
        $this->assertEquals("True", $question->getChoice('T'));
    }
    /**
     * @test
     */
    public function addAnswerPairsForChoices()
    {
        $question = $this->getQuestionObject();
        $response = new PairResponse(4, 10);
        $question->addResponse('T', $response);
        $this->assertEquals($response, $question->getResponse('T'));
        $this->assertNull($question->getResponse('F'));
    }
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Choice does not exist.
     */
    public function throwExceptionForGivingAResponseToNonExistantChoice()
    {
        $question = $this->getQuestionObject();
        $response = new PairResponse(4, 10);
        $question->addResponse(4, $response);
    }
    private function getQuestionObject()
    {
        $question = new TrueFalse();
        return $question;
    }
}
