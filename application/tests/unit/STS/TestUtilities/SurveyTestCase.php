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
    protected function getValidSurvey()
    {
        $multipleChoice = new MultipleChoice();
        $multipleChoice->setPrompt('Pick 2.')->setId(1)->addChoice(10, 'Choice 2');
        $shortAnswer = new ShortAnswer();
        $shortAnswer->setId(2)->isAsked(ShortAnswer::AFTER);
        $questions = array(
            $multipleChoice, $shortAnswer
        );
        $survey = new Survey($questions);
        $survey->setId(self::ID);
        $survey->setEnteredByUserId(self::ENTERED_BY);
        $survey->answerQuestion(2, new SingleResponse('Response.'));
        $survey->answerQuestion(
            1,
            array(
                10 => new PairResponse(101, 111)
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
                    'id' => 1,
                    'type' => 'MultipleChoice',
                    'prompt' => 'Pick 2.',
                    'asked' => 0,
                    'choices' => array(
                        array(
                            'id' => 10,
                            'prompt' => 'Choice 2',
                            'response' => array(
                                'type' => 'Pair',
                                'beforeValue' => 101,
                                'afterValue' =>111
                                )
                            )
                        )
                    ),
                array(
                    'id' => 2,
                    'type' => 'ShortAnswer',
                    'prompt' => null,
                    'asked' => 2,
                    'response' => array(
                        'type' => 'Single',
                        'value' => 'Response.')
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
