<?php
namespace STS\Domain\Survey;

class Question {

    protected $id;
    protected $prompt;
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    public function getPrompt() {
        return $this->prompt;
    }
    public function setPrompt($prompt) {
        $this->prompt = $prompt;
        return $this;
    }
}
