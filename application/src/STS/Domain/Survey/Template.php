<?php
namespace STS\Domain\Survey;

use STS\Domain\Survey;

class Template
{
    private $id;
    private $questions = array();
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function addQuestion(Question $question)
    {
        $this->questions[$question->getId()] = $question;
        return $this;
    }
    public function getQuestion($index)
    {
        return $this->questions[$index];
    }

    public function getQuestions()
    {
        return $this->questions;
    }
    public function createSurveyInstance()
    {
        $survey = new Survey($this->questions);
        return $survey;
    }
}
