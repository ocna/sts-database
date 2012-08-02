<?php
use STS\Core\Survey\StaticTemplateRepository;

class StaticTemplateRepositoryTest extends PHPUnit_Framework_TestCase {
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Template not found for given id.
     */
    public function throwExceptionForInvalidId() {
        $repo = new StaticTemplateRepository();
        $template = $repo->load(2);
    }
    /**
     * @test
     */
    public function checkCorrectQuestions() {
        $template = $this->getDefaultTemplate();
        $templateData = array(
                1 => array(
                        'type' => 'MultipleChoice', 'asked' => 0,
                        'prompt' => 'In general, I have a basic understanding of ovarian cancer including:',
                        'choices' => array(
                                1 => 'Risk factors', 2 => 'Signs and symptoms',
                                3 => 'Diagnostic protocols'
                        )
                ),
                2 => array(
                        'type' => 'TrueFalse', 'asked' => 0,
                        'prompt' => 'Women are screened regularly for ovarian cancer.'
                ),
                3 => array(
                        'type' => 'MultipleChoice', 'asked' => 0,
                        'prompt' => 'A family history of which of the following raises the risk of ovarian cancer:',
                        'choices' => array(
                                4 => 'Breast cancer', 5 => 'Ovarian cancer',
                                6 => 'Uterine cancer', 7 => 'Colon cancer',
                                8 => 'Lung cancer'
                        )
                ),
                4 => array(
                        'type' => 'MultipleChoice', 'asked' => 0,
                        'prompt' => 'A personal history of which of the following raises the risk of ovarian cancer:',
                        'choices' => array(
                                4 => 'Breast cancer',
                                9 => 'Never having children',
                                10 => 'Cervical cancer',
                                11 => 'Birth control pill use'
                        )
                ),
                5 => array(
                        'type' => 'MultipleChoice', 'asked' => 0,
                        'prompt' => 'Studies have shown that women diagnosed with ovarian cancer generally have a better likelihood of prolonged survival if treated by:',
                        'choices' => array(
                                12 => 'A gynecologist', 13 => 'An oncologist',
                                14 => 'A gynecologic oncologist'
                        )
                ),
                6 => array(
                        'type' => 'ShortAnswer', 'asked' => 0,
                        'prompt' => 'List three symptoms, which, if persistent, would lead you to consider ovarian cancer.'
                ),
                7 => array(
                        'type' => 'ShortAnswer', 'asked' => 2,
                        'prompt' => 'How has the presentation changed the way you think about ovarian cancer?'
                ),
                8 => array(
                        'type' => 'ShortAnswer', 'asked' => 2,
                        'prompt' => 'Would you consider this form of experiential learning an effective method of learning more about ovarian cancer or another condition?'
                ),
                9 => array(
                        'type' => 'ShortAnswer', 'asked' => 2,
                        'prompt' => 'How can the presentation be more effective in conveying survivorsÕ experiences, the importance of the symptoms or difficulties in diagnosis?'
                )
        );
        foreach ($templateData as $index => $questionData) {
            $this->assertEquals($questionData['prompt'], $template->getQuestion($index)
                                                                  ->getPrompt());
            if ($questionData['type'] == 'MultipleChoice') {
                foreach ($questionData['choices'] as $id => $choice) {
                    $this->assertEquals($choice, $template->getQuestion($index)
                                                          ->getChoice($id));
                }
            }
        }
    }
    private function getDefaultTemplate() {
        $repo = new StaticTemplateRepository();
        $template = $repo->load(1);
        return $template;
    }
}
