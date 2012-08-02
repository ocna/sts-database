<?php
use STS\Domain\Survey\Question;

class QuestionTest extends PHPUnit_Framework_TestCase {
    const ID = 12;
    const PROMPT = "This is a question?";
    /**
     * @test
     */
    public function createValidObject() {
        $question = new Question();
        $this->assertNull($question->getId());
        $this->assertNull($question->getPrompt());
        $this->assertEquals(0, $question->whenAsked());
        $question->setId(self::ID)
                 ->setPrompt(self::PROMPT)
                 ->isAsked(Question::BEFORE);
        $this->assertEquals(self::ID, $question->getId());
        $this->assertEquals(self::PROMPT, $question->getPrompt());
        $this->assertEquals(1, $question->whenAsked());
        $question->isAsked(Question::AFTER);
        $this->assertEquals(2, $question->whenAsked());
    }
}
