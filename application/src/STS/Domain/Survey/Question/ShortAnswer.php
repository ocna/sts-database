<?php
namespace STS\Domain\Survey\Question;
use STS\Domain\Survey\AbstractResponse;
use STS\Domain\Survey\Question;

class ShortAnswer extends Question {
    protected $response = null;
    
    public function setResponse(AbstractResponse $response){
        $this->response = $response;
    }
    
    public function getResponse(){
        return $this->response;
    }
}
