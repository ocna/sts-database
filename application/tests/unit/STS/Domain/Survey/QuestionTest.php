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
        $question->setId(self::ID)->setPrompt(self::PROMPT);
        $this->assertEquals(self::ID, $question->getId());
        $this->assertEquals(self::PROMPT, $question->getPrompt());
    }
}
