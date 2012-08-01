<?php
namespace STS\Domain\Survey;

class Template {

    private $id;
    private $questions = array();
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    public function addQuestion(Question $question) {
        $index = count($this->questions) + 1;
        $this->questions[$index] = $question;
        return $this;
    }
    public function getQuestion($index) {
        return $this->questions[$index];
    }
}
