<?php
use STS\TestUtilities\SurveyTestCase;

use STS\Domain\Survey\Response\SingleResponse;
use STS\Domain\Survey\Question\ShortAnswer;
use STS\Domain\Survey\Response\PairResponse;
use STS\Domain\Survey;
use STS\Domain\Survey\Question\MultipleChoice;

class SurveyTest extends SurveyTestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $survey = $this->getValidSurvey();
        $this->assertEquals('Pick 2.', $survey->getQuestion(1)->getPrompt());
    }

    /**
     * @test
     */
    public function validGetQuestions()
    {
        $survey = $this->getValidSurvey();
        $questions = $survey->getQuestions();
        $this->assertTrue(is_array($questions));
        $this->assertInstanceOf('\STS\Domain\Survey\Question', array_pop($questions));
    }
    /**
     * @test
     */
    public function answerSurveyQuestionBoth()
    {
        $survey = $this->getValidSurvey();
        $response = new PairResponse(3, 4);
        $survey->answerQuestion(1, array(
                10 => $response
            ));
        $this->assertEquals($response, $survey->getResponse(1, 10));
    }
    /**
     * @test
     */
    public function answerSurveyQuestionSingle()
    {
        $survey = $this->getValidSurvey();
        $response = new SingleResponse('Response');
        $survey->answerQuestion(2, $response);
        $this->assertEquals($response, $survey->getResponse(2));
    }
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Only one response allowed for short answers.
     */
    public function throwExceptionForArrayResponsesToShortAnswer()
    {
        $survey = $this->getValidSurvey();
        $response = new SingleResponse('Response');
        $survey->answerQuestion(2, array(
                $response
            ));
    }
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Responses must be an array of choice id => responses.
     */
    public function throwExceptionForSingleResponseToMultipleChoice()
    {
        $survey = $this->getValidSurvey();
        $response = new PairResponse(3, 4);
        $survey->answerQuestion(1, $response);
    }
    /**
     * @test
     */
    public function validToArray()
    {
        $survey = $this->getValidSurvey();
        $array = $survey->toArray();
        $expectedArray = array(
                'id' => self::ID, 'entered_by_user_id' => self::ENTERED_BY,
                'questions' => array(
                        array(
                                'id' => 1, 'type' => 'MultipleChoice', 'prompt' => 'Pick 2.', 'asked' => 0,
                                'choices' => array(
                                        array(
                                                'id' => 10, 'prompt' => 'Choice 2',
                                                'response' => array(
                                                    'type' => 'Pair', 'beforeValue' => 101, 'afterValue' => 111
                                                )
                                        )
                                )
                        ),
                        array(
                                'id' => 2, 'type' => 'ShortAnswer', 'prompt' => null, 'asked' => 2,
                                'response' => array(
                                    'type' => 'Single', 'value' => 'Response.'
                                )
                        )
                )
        );
        $this->assertEquals($expectedArray, $array);
    }

}
