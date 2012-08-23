<?php
namespace STS\TestUtilities;
use STS\Domain\Survey;
use STS\Domain\Survey\Response\SingleResponse;
use STS\Domain\Survey\Question\ShortAnswer;
use STS\Domain\Survey\Response\PairResponse;
use STS\Domain\Survey\Question\MultipleChoice;

class SurveyTestCase extends \PHPUnit_Framework_TestCase
{
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
        $survey->answerQuestion(2, new SingleResponse('Response.'));
        $survey->answerQuestion(1, array(
                10 => new PairResponse(101, 111)
            ));
        return $survey;
    }
}
