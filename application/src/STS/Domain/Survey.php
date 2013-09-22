<?php
namespace STS\Domain;

use STS\Domain\Survey\Question\TrueFalse;
use STS\Domain\Survey\Question\MultipleChoice;
use STS\Domain\Survey\Question\ShortAnswer;
use STS\Domain\Survey\AbstractResponse;
use STS\Domain\Survey\Question;

class Survey
{
    const NUM_SCORABLE_QUESTIONS = 17;
    private $id;
    private $enteredByUserId;
    private $questions = array();
    private $correctBeforePerQuestion = null;
    private $correctAfterPerQuestion = null;
    private $incorrectBeforePerQuestion = null;
    private $incorrectAfterPerQuestion = null;

    public function __construct($questions)
    {
        foreach ($questions as $question) {
            if (!$question instanceof Question) {
                throw new \InvalidArgumentException('Question not provided.');
            }
            $this->questions[$question->getId()] = $question;
        }
    }
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function getEnteredByUserId()
    {
        return $this->enteredByUserId;
    }
    public function setEnteredByUserId($enteredByUserId)
    {
        $this->enteredByUserId = $enteredByUserId;
        return $this;
    }
    public function getQuestion($id)
    {
        return $this->questions[$id];
    }
    public function getQuestions()
    {
        return $this->questions;
    }
    public function answerQuestion($questionId, $responses)
    {
        if ($this->questions[$questionId] instanceof ShortAnswer) {
            if (!$responses instanceof AbstractResponse) {
                throw new \InvalidArgumentException('Only one response allowed for short answers.');
            }
            $this->questions[$questionId]->setResponse($responses);
        } else {
            if (!is_array($responses)) {
                throw new \InvalidArgumentException('Responses must be an array of choice id => responses.');
            }
            foreach ($responses as $choiceId => $response) {
                $this->questions[$questionId]->addResponse($choiceId, $response);
            }
        }
    }

    /**
     * @param $questionId
     * @param null $choiceId
     * @return mixed
     */
    public function getResponse($questionId, $choiceId = NULL)
    {
        if ($this->questions[$questionId] instanceof ShortAnswer) {
            return $this->questions[$questionId]->getResponse();
        } else {
            return $this->questions[$questionId]->getResponse($choiceId);
        }
    }

    /**
     * @return array
     */
    private function getScorableResponses()
    {
        $responses = array();

        /** @var MultipleChoice $question */
        foreach ($this->questions as $question) {
            if ($question->whenAsked() == Question::BOTH && in_array($question->getType(), array(
                MultipleChoice::QUESTION_TYPE, TrueFalse::QUESTION_TYPE
            ))) {
                $answers = array();
                foreach ($question->getChoices() as $id => $choice) {
                    /** @var \STS\Domain\Survey\Response\PairResponse $response */
                    $answers[] = $question->getResponse($id);
                }
                $responses[] = array(
                    'question'  => $question->getId(),
                    'answers'   => $answers
                );
            }
        }

        return $responses;
    }

    protected function scoreSurvey()
    {
        $answer_key = array(
            1 => array(true, true, true),
            2 => array(false, true),
            3 => array(true, true, false, true, false),
            4 => array(true, true, false, false),
            5 => array(false, false, true)
        );
        $before_correct_responses = 0;
        $after_correct_responses = 0;
        $before_incorrect_responses = 0;
        $after_incorrect_responses = 0;

        foreach ($this->getScorableResponses() as $response) {
            /** @var \STS\Domain\Survey\Response\PairResponse $response_answer */
            $response_answers = $response['answers'];
            foreach ($answer_key[$response['question']] as $answer_number => $answer) {
                $response_answer = $response_answers[$answer_number];
                if ($answer) {
                    $before_correct_responses += $response_answer->getBeforeResponse();
                    $after_correct_responses += $response_answer->getAfterResponse();
                } else {
                    $before_incorrect_responses += $response_answer->getBeforeResponse();
                    $after_incorrect_responses += $response_answer->getAfterResponse();
                }
            }
        }

        $this->correctBeforePerQuestion = $before_correct_responses / self::NUM_SCORABLE_QUESTIONS;
        $this->correctAfterPerQuestion = $after_correct_responses / self::NUM_SCORABLE_QUESTIONS;
        $this->incorrectBeforePerQuestion = $before_incorrect_responses /
            self::NUM_SCORABLE_QUESTIONS;
        $this->incorrectAfterPerQuestion = $after_incorrect_responses /
            self::NUM_SCORABLE_QUESTIONS;
    }

    /**
     * @return float
     */
    public function getCorrectBeforePerQuestion() {
        if (null === $this->correctBeforePerQuestion) {
            $this->scoreSurvey();
        }

        return $this->correctBeforePerQuestion;
    }

    /**
     * @return float
     */
    public function getCorrectAfterPerQuestion() {
        if (null === $this->correctAfterPerQuestion) {
            $this->scoreSurvey();
        }

        return $this->correctAfterPerQuestion;
    }

    /**
     * @return float
     */
    public function getIncorrectBeforePerQuestion() {
        if (null === $this->incorrectBeforePerQuestion) {
            $this->scoreSurvey();
        }

        return $this->incorrectBeforePerQuestion;
    }

    /**
     * @return float
     */
    public function getIncorrectAfterPerQuestion() {
        if (null == $this->incorrectAfterPerQuestion) {
            $this->scoreSurvey();
        }

        return $this->incorrectAfterPerQuestion;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $questions = array();
        /** @var Question $question */
        foreach ($this->questions as $question) {
            $questionArray = array(
                    'id' => $question->getId(), 'type' => $question->getType(), 'prompt' => $question->getPrompt(),
                    'asked' => $question->whenAsked()
            );
            if (in_array($question->getType(), array(
                MultipleChoice::QUESTION_TYPE, TrueFalse::QUESTION_TYPE
            ))) {
                $choices = array();
                foreach ($question->getChoices() as $id => $choice) {
                    $choiceArray = array(
                        'id' => $id, 'prompt' => $choice
                    );

                    if($question->getResponse($id)){
                        $response = $question->getResponse($id);
                        if($question->whenAsked()==0){
                            $responseArray = array('type'=>'Pair', 'beforeValue'=>$response->getBeforeResponse(), 'afterValue'=>$response->getAfterResponse());
                        }else{
                            $responseArray = array('type'=>'Single', 'value'=>$response->getResponse());
                        }
                    }
                    $choiceArray['response']= $responseArray;
                    $choices[] = $choiceArray;
                }
                $questionArray['choices']= $choices;
            }else{
                if($question->getResponse()){
                        $response = $question->getResponse();
                    if($question->whenAsked()==0){
                        $responseArray = array('type'=>'Pair', 'beforeValue'=>utf8_encode($response->getBeforeResponse()), 'afterValue'=>utf8_encode($response->getAfterResponse()));
                    }else{
                        $responseArray = array('type'=>'Single', 'value'=>utf8_encode($response->getResponse()));
                    }
                }
                $questionArray['response']= $responseArray;
            }
            $questions[]=$questionArray;
        }
        $survey = array(
            'id' => $this->id, 'entered_by_user_id' => $this->enteredByUserId, 'questions' => $questions
        );
        return $survey;
    }
}
