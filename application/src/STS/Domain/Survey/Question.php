<?php
namespace STS\Domain\Survey;

class Question {
    const BOTH = 0;
    const BEFORE = 1;
    const AFTER = 2;

    protected $id;
    protected $prompt;
    protected $asked;
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
    public function isAsked($asked = 0) {
        $this->asked = $asked;
        return $this;
    }
    public function whenAsked() {
        return $this->asked;
    }
}
