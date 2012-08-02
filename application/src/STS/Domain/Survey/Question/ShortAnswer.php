<?php
namespace STS\Domain\Survey\Question;
use STS\Domain\Survey\AbstractResponse;
use STS\Domain\Survey\Question;
use STS\Domain\Survey\Response\PairResponse;

class ShortAnswer extends Question {
    protected $response = null;
    
    public function setResponse(AbstractResponse $response){
        if($this->asked != self::BOTH && $response instanceof PairResponse){
            throw new \InvalidArgumentException('Question expects a response single.');
        }elseif ($this->asked == self::BOTH && ! $response instanceof PairResponse){
            throw new \InvalidArgumentException('Question expects a response pair.');
        }
        $this->response = $response;
    }
    
    public function getResponse(){
        return $this->response;
    }
}
