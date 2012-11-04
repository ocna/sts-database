<?php
use STS\Domain\Survey;
use STS\Domain\Survey\Question\ShortAnswer;
use STS\Domain\Survey\Question\MultipleChoice;
use STS\Domain\Survey\Question\TrueFalse;
use STS\Domain\Survey\Template;

class TemplateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $template = $this->getValidObject();
        $this->assertEquals($this->getMultipleChoice(), $template->getQuestion(2));
        $this->assertEquals(10, $template->getId());
        $this->assertEquals('The sky is blue.', $template->getQuestion(1)
                                                         ->getPrompt());
    }
    /**
     * @test
     */
    public function getSurveyObjectFromTemplate()
    {
        $template = $this->getValidObject();
        $survey = $template->createSurveyInstance();
        $this->assertTrue($survey instanceof Survey);
        $this->assertEquals($this->getMultipleChoice(), $survey->getQuestion(2));
    }
    private function getTrueFalse()
    {
        $trueFalse = new TrueFalse();
        $trueFalse->setPrompt('The sky is blue.')
                  ->setId(1);
        return $trueFalse;
    }
    private function getMultipleChoice()
    {
        $multipleChoice = new MultipleChoice();
        $multipleChoice->setId(2)
                       ->setPrompt('Which are brown?')
                       ->addChoice(1, 'cow')
                       ->addChoice(2, 'ham')
                       ->addChoice(3, 'tree');
        return $multipleChoice;
    }
    private function getShortAnswer()
    {
        $shortAnswer = new ShortAnswer();
        $shortAnswer->setId(3)
                    ->setPrompt('What is the meaning of life?');
        return $shortAnswer;
    }
    private function getValidObject()
    {
        $template = new Template();
        $trueFalse = $this->getTrueFalse();
        $multipleChoice = $this->getMultipleChoice();
        $shortAnswer = $this->getShortAnswer();
        $template->setId(10)
                 ->addQuestion($trueFalse)
                 ->addQuestion($multipleChoice)
                 ->addQuestion($shortAnswer);
        return $template;
    }
}
