<?php
use STS\TestUtilities\SurveyTestCase;

use STS\Domain\Survey\Response\SingleResponse;
use STS\Domain\Survey\Response\PairResponse;
use STS\Domain\Survey;

class SurveyTest extends SurveyTestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $survey = $this->getValidSurvey();
        $this->assertEquals('Women are screened regularly for ovarian cancer.',
	        $survey->getQuestion(2)->getPrompt()
        );
    }

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage Question not provided.
	 */
	public function throwsExceptionWithNoQuestions()
	{
		$survey = new Survey(array(0));
	}

	/**
	 * @test
	 */
	public function validGetId()
	{
		$survey = $this->getValidSurvey();
		$this->assertEquals(self::ID, $survey->getId());
	}

	/**
	 * @test
	 */
	public function validGetEnteredById()
	{
		$survey = $this->getValidSurvey();
		$this->assertEquals(self::ENTERED_BY, $survey->getEnteredByUserId());
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
        $survey->answerQuestion(2, array(
                10 => $response
            ));
        $this->assertEquals($response, $survey->getResponse(2, 10));
    }
    /**
     * @test
     */
    public function answerSurveyQuestionSingle()
    {
        $survey = $this->getValidSurvey();
        $response = new SingleResponse('Response');
        $survey->answerQuestion(3, $response);
        $this->assertEquals($response, $survey->getResponse(3));
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
        $survey->answerQuestion(3, array(
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
        $survey->answerQuestion(2, $response);
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
		                'id'        => 1,
		                'type'      => 'MultipleChoice',
		                'prompt'    => 'In general, I have a basic understanding of ovarian cancer including:',
		                'asked'     => 0,
		                'choices'   =>
			                array(
				                array(
					                'id'        => 1,
					                'prompt'    => 'Risk factors',
					                'response'  => array(
						                'type'          => 'Pair',
						                'beforeValue'   => 16,
						                'afterValue'    => 19
					                )
				                ),
				                array(
					                'id'        => 2,
					                'prompt'    => 'Signs and symptoms',
					                'response'  => array(
						                'type'          => 'Pair',
						                'beforeValue'   => 8,
						                'afterValue'    => 21
					                )
				                ),
				                array(
					                'id'        => 3,
					                'prompt'    => 'Diagnostic protocols',
					                'response'  => array(
						                'type'          => 'Pair',
						                'beforeValue'   => 4,
						                'afterValue'    => 14
					                )
				                )
			                )
	                ),
	                array(
		                'id'        => 2,
		                'type'      => 'MultipleChoice',
		                'prompt'    => 'Women are screened regularly for ovarian cancer.',
		                'asked'     => 0,
		                'choices'   =>
			                array(
				                array(
					                'id'        => 10,
					                'prompt'    => 'True',
					                'response'  => array(
						                'type'          => 'Pair',
						                'beforeValue'   => 1,
						                'afterValue'    => 1
					                )
				                ),
				                array(
					                'id'        => 11,
					                'prompt'    => 'False',
					                'response'  => array(
						                'type'          => 'Pair',
						                'beforeValue'   => 19,
						                'afterValue'    => 21
					                )
				                )
			                )
	                ),
	                array(
		                'id' => 3,
		                'type' => 'ShortAnswer',
		                'prompt' => null,
		                'asked' => 2,
		                'response' => array(
			                'type' => 'Single',
			                'value' => 'Response.'
		                )
	                )
                )
        );
        $this->assertEquals($expectedArray, $array);
    }

	/**
	 * @test
	 */
	public function validGetScorableResponses()
	{
		$survey = $this->getValidSurvey();
		$expectedResponses = array(
			array(
				'question'  => 1,
				'answers'   => array(
					new PairResponse(16, 19),
					new PairResponse(8, 21),
					new PairResponse(4, 14)
				)
			),
			array(
				'question'  => 2,
				'answers'   => array(
					new PairResponse(1, 1),
					new PairResponse(19, 21)
				)
			)
		);
		$this->assertEquals($expectedResponses, $survey->getScorableResponses());
	}

	/**
	 * @test
	 */
	public function validGetNumCorrectPreResponses()
	{
		$survey = $this->getValidSurvey();
		$this->assertEquals(self::NUM_CORRECT_BEFORE, $survey->getNumCorrectBeforeResponses());
	}

	/**
	 * @test
	 */
	public function validGetNumCorrectPostResponses()
	{
		$survey = $this->getValidSurvey();
		$this->assertEquals(self::NUM_CORRECT_AFTER, $survey->getNumCorrectAfterResponses());
	}
}
