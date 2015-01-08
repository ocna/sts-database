<?php
namespace STS\Domain\Survey\Question;

use STS\Domain\Survey\Question;

class TrueFalse extends MultipleChoice
{
    const QUESTION_TYPE = 'TrueFalse';

    public function __construct()
    {
        $this->addChoice('T', 'True');
        $this->addChoice('F', 'False');
    }
}
