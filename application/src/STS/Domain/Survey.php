<?php
namespace STS\Domain;
use STS\Domain\Survey\Question\TrueFalse;
use STS\Domain\Survey\Question\MultipleChoice;
use STS\Domain\Survey\Question\ShortAnswer;
use STS\Domain\Survey\AbstractResponse;
use STS\Domain\Survey\Question;

class Survey
{

    private $id;
    private $enteredByUserId;
    private $questions = array();
    public function __construct($questions)
    {
        foreach ($questions as $question) {
            if (!$question instanceof Question) {
                throw new InvalidArgumentException('Question not provided.');
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
    public function getResponse($questionId, $choiceId = NULL)
    {
        if ($this->questions[$questionId] instanceof ShortAnswer) {
            return $this->questions[$questionId]->getResponse();
        } else {
            return $this->questions[$questionId]->getResponse($choiceId);
        }
    }
    public function toArray()
    {
        $questions = array();
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
                        $responseArray = array('type'=>'Pair', 'beforeValue'=>$response->getBeforeResponse(), 'afterValue'=>$response->getAfterResponse());
                    }else{
                        $responseArray = array('type'=>'Single', 'value'=>$response->getResponse());
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
