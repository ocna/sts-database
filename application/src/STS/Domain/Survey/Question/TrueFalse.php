<?php
namespace STS\Domain\Survey\Question;
use STS\Domain\Survey\Question;

class TrueFalse extends MultipleChoice {
    public function __construct() {
        $this->addChoice('T', 'True');
        $this->addChoice('F', 'False');
    }
}
