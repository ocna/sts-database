<?php
namespace STS\Domain\Survey\Question;
use STS\Domain\Survey\Response\PairResponse;
use STS\Domain\Survey\AbstractResponse;
use STS\Domain\Survey\Question;

class MultipleChoice extends Question
{

    protected $choices;
    protected $responses;
    public function addChoice($choiceId, $choicePrompt)
    {
        $this->choices[$choiceId] = $choicePrompt;
        return $this;
    }
    public function getChoice($choiceId)
    {
        if ($this->choiceExists($choiceId)) {
            return $this->choices[$choiceId];
        } else {
            return null;
        }
    }
    public function getChoices()
    {
        return $this->choices;
    }
    public function addResponse($choiceId, AbstractResponse $response)
    {
        if ($this->asked != self::BOTH && $response instanceof PairResponse) {
            throw new \InvalidArgumentException('Question expects a response single.');
        } elseif ($this->asked == self::BOTH && !$response instanceof PairResponse) {
            throw new \InvalidArgumentException('Question expects a response pair.');
        }
        if ($this->choiceExists($choiceId)) {
            $this->responses[$choiceId] = $response;
            return $this;
        } else {
            throw new \InvalidArgumentException('Choice does not exist.');
        }
    }
    public function getResponse($choiceId)
    {
        if ($this->responseExists($choiceId)) {
            return $this->responses[$choiceId];
        } else {
            return null;
        }
    }
    private function responseExists($choiceId)
    {
        return array_key_exists($choiceId, $this->responses);
    }
    private function choiceExists($choiceId)
    {
        return array_key_exists($choiceId, $this->choices);
    }
}
