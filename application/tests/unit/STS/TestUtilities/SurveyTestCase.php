<?php
namespace STS\TestUtilities;

use STS\Domain\Survey;
use STS\Domain\Survey\Response\SingleResponse;
use STS\Domain\Survey\Question\ShortAnswer;
use STS\Domain\Survey\Response\PairResponse;
use STS\Domain\Survey\Question\MultipleChoice;

class SurveyTestCase extends \PHPUnit_Framework_TestCase
{
    const ID = '5068ab5c559ac99cfe2f6792';
    const ENTERED_BY = 'jfox';
	const NUM_CORRECT_BEFORE = 47;
	const NUM_CORRECT_AFTER = 75;
    protected function getValidSurvey()
    {
        $firstMultipleChoice = new MultipleChoice();
        $firstMultipleChoice
	        ->setPrompt('In general, I have a basic understanding of ovarian cancer including:')
	        ->setId(1)
	        ->addChoice(1, 'Risk factors')
            ->addChoice(2, 'Signs and symptoms')
            ->addChoice(3, 'Diagnostic protocols');
	    $secondMultipleChoice = new MultipleChoice();
	    $secondMultipleChoice
		    ->setPrompt('Women are screened regularly for ovarian cancer.')
		    ->setId(2)
		    ->addChoice(10, 'True')
		    ->addChoice(11, 'False');
        $shortAnswer = new ShortAnswer();
        $shortAnswer->setId(3)->isAsked(ShortAnswer::AFTER);
        $questions = array(
            $firstMultipleChoice, $secondMultipleChoice, $shortAnswer
        );
        $survey = new Survey($questions);
        $survey->setId(self::ID);
        $survey->setEnteredByUserId(self::ENTERED_BY);
        $survey->answerQuestion(3, new SingleResponse('Response.'));
        $survey->answerQuestion(
            2,
            array(
                10 => new PairResponse(1, 1),
	            11 => new PairResponse(19, 21)
            )
        );
	    $survey->answerQuestion(
		    1,
		    array(
			    1 => new PairResponse(16, 19),
			    2 => new PairResponse(8, 21),
			    3 => new PairResponse(4, 14)
		    )
	    );
        return $survey;
    }

    protected function getValidSurveyData()
    {
        return array(
            '_id' => new \MongoId(self::ID),
            'entered_by_user_id' => self::ENTERED_BY,
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
    }

	public static function createValidSurvey()
	{
		$surveyTestCase = new SurveyTestCase();
		return $surveyTestCase->getValidSurvey();
	}
}
