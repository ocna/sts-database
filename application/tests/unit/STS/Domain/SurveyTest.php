<?php
use STS\Domain\Survey\Response\SingleResponse;
use STS\Domain\Survey\Question\ShortAnswer;
use STS\Domain\Survey\Response\PairResponse;
use STS\Domain\Survey;
use STS\Domain\Survey\Question\MultipleChoice;

class SurveyTest extends PHPUnit_Framework_TestCase {
    /**
     * @test
     */
    public function createValidObject() {
        $survey = $this->getValidSurveyObject();
        $this->assertEquals('Pick 2.', $survey->getQuestion(1)
                                              ->getPrompt());
    }
    /**
     * @test
     */
    public function answerSurveyQuestionBoth() {
        $survey = $this->getValidSurveyObject();
        $response = new PairResponse(3, 4);
        $survey->answerQuestion(1, array(
                    10 => $response
                ));
        $this->assertEquals($response, $survey->getResponse(1, 10));
    }
    /**
     * @test
     */
    public function answerSurveyQuestionSingle() {
        $survey = $this->getValidSurveyObject();
        $response = new SingleResponse('Response');
        $survey->answerQuestion(2, $response);
        $this->assertEquals($response, $survey->getResponse(2));
    }
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Only one response allowed for short answers.
     */
    public function throwExceptionForArrayResponsesToShortAnswer() {
        $survey = $this->getValidSurveyObject();
        $response = new SingleResponse('Response');
        $survey->answerQuestion(2, array($response));
    }
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Responses must be an array of choice id => responses.
     */
    public function throwExceptionForSingleResponseToMultipleChoice() {
        $survey = $this->getValidSurveyObject();
        $response = new PairResponse(3, 4);
        $survey->answerQuestion(1, $response);
    }

    private function getValidSurveyObject() {
        $multipleChoice = new MultipleChoice();
        $multipleChoice->setPrompt('Pick 2.')
                       ->setId(1)
                       ->addChoice(10, 'Choice 2');
        $shortAnswer = new ShortAnswer();
        $shortAnswer->setId(2)
                    ->isAsked(ShortAnswer::AFTER);
        $questions = array(
            $multipleChoice, $shortAnswer
        );
        $survey = new Survey($questions);
        return $survey;
    }
}
