<?php
namespace STS\Domain;
use STS\Domain\Survey\Question\ShortAnswer;
use STS\Domain\Survey\AbstractResponse;
use STS\Domain\Survey\Question;

class Survey {

    private $id;
    private $questions = array();
    public function __construct($questions) {
        foreach ($questions as $question) {
            if (!$question instanceof Question) {
                throw new InvalidArgumentException('Question not provided.');
            }
            $this->questions[$question->getId()] = $question;
        }
    }
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    public function getQuestion($id) {
        return $this->questions[$id];
    }
    public function answerQuestion($questionId, $responses) {
        if ($this->questions[$questionId] instanceof ShortAnswer) {
            if (!$responses instanceof AbstractResponse) {
                throw new \InvalidArgumentException(
                        'Only one response allowed for short answers.');
            }
            $this->questions[$questionId]
                    ->setResponse($responses);
        } else {
            if (!is_array($responses)) {
                throw new \InvalidArgumentException(
                        'Responses must be an array of choice id => responses.');
            }
            foreach ($responses as $choiceId => $response) {
                $this->questions[$questionId]
                        ->addResponse($choiceId, $response);
            }
        }
    }
    public function getResponse($questionId, $choiceId = NULL) {
        if ($this->questions[$questionId] instanceof ShortAnswer) {
            return $this->questions[$questionId]
                    ->getResponse();
        } else {
            return $this->questions[$questionId]
                    ->getResponse($choiceId);
        }
    }
}
