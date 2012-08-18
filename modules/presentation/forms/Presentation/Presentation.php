<?php
use STS\Domain\Survey\Question\ShortAnswer;
use STS\Domain\Survey\Question\MultipleChoice;

class Presentation_Presentation extends Twitter_Bootstrap_Form_Horizontal
{

    protected $schools;
    protected $presentationTypes;
    protected $surveyTemplate;
    public function init()
    {
        $this->setName('presentationForm');
        $this->setMethod('post');
        $this->setAction('/presentation/index/new');
        //Location
        $this
            ->addElement('select', 'location', array(
                    'label' => 'Location', 'dimension' => 4, 'MultiOptions' => $this->schools, 'required' => true,
                    'validators' => array(
                        new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::ZERO)
                    )
            ));
        //PresentationType
        $this
            ->addElement('select', 'presentationType', array(
                    'label' => 'Presentation Type', 'dimension' => 2, 'MultiOptions' => $this->presentationTypes,
                    'required' => true,
                    'validators' => array(
                        new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::ZERO)
                    )
            ));
        //Date
        $this
            ->addElement('text', 'dateOfPresentation', array(
                    'label' => 'Date of Presentation', 'dimension' => 2,
                    'validators' => array(
                            new Zend_Validate_Date(
                                            array(
                                                'format' => 'MM/dd/yyyy'
                                            ))
                    ), 'required' => true,
                    'append' => array(
                        'name' => 'dateOfPresentationButton', 'label' => '', 'icon' => 'calendar'
                    )
            ));
        //Notes
        $this
            ->addElement('textarea', 'notes', array(
                'label' => 'Notes', 'dimension' => 4, 'rows' => 5
            ));
        //MembersAttended
        $this
            ->addElement('text', 'membersAttended[]', array(
                    'label' => 'Members Attended', 'class' => 'membersAttended', 'isArray' => true,
                    'description' => 'Begin typing names to search for and add members...',
                    'validators' => array(
                        new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::EMPTY_ARRAY)
                    )
            ));
        //Participants
        $this
            ->addElement('text', 'participants', array(
                    'label' => 'Number of Participants', 'dimension' => 1, 'required' => true,
                    'validators' => array(
                        'int'
                    )
            ));
        //FormsReturned
        $this
            ->addElement('text', 'formsReturned', array(
                    'label' => 'Number of Forms Returned', 'dimension' => 1, 'required' => true,
                    'validators' => array(
                        'int'
                    )
            ));
        //SurveyForm
        $this->buildSurveyForm();
        //Saving
        $this
            ->addElement('button', 'submit', array(
                    'label' => 'Save Presentation and Survey Information!', 'type' => 'submit',
                    'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_SUCCESS
            ));
        $this
            ->addDisplayGroup(array(
                'submit'
            ), 'actions', array(
                    'disableLoadDefaultDecorators' => true,
                    'decorators' => array(
                        'Actions'
                    )
            ));
    }
    public function buildSurveyForm()
    {
        $questionNumber = 1;
        foreach ($this->surveyTemplate->getQuestions() as $questionId => $question) {
            $questionName = "q_{$questionId}";
            $prompt = $questionNumber . ". " . $question->getPrompt();
            $elements = array();
            $pairs = array();
            $whenAsked = $question->whenAsked();
            if ($whenAsked == 1) {
                $asks = array(
                    'pre',
                );
            }
            if ($whenAsked == 2) {
                $asks = array(
                    'post'
                );
            } else {
                $asks = array(
                    'pre', 'post'
                );
            }
            if ($question instanceof MultipleChoice) {
                foreach ($question->getChoices() as $choiceId => $choice) {
                    $name = $questionName . "_c_$choiceId";
                    $pairs[] = $name;
                    foreach ($asks as $ask) {
                        $this
                            ->addElement('text', $name . "_$ask", array(
                                    'label' => $choice, 'dimension' => 1, 'required' => true,
                                    'validators' => array(
                                        'int'
                                    )
                            ));
                        $elements[] = $name . "_$ask";
                    }
                }
            } elseif ($question instanceof ShortAnswer) {
                $name = $questionName . "_c_0";
                $pairs[] = $name;
                foreach ($asks as $ask) {
                    $this
                        ->addElement('textarea', $name . "_$ask", array(
                            'label' => '', 'dimension' => 3, 'rows' => 10, 'required' => true
                        ));
                    $elements[] = $name . "_$ask";
                }
            } else {
                throw new \InvalidArgumentException('Type of question not known.');
            }
            $this
                ->addDisplayGroup($elements, $questionId, array(
                        'legend' => $prompt, 'disableLoadDefaultDecorators' => true,
                        'decorators' => array(
                                array(
                                        'ViewScript',
                                        array(
                                            'viewScript' => 'question.phtml', 'pairs' => $pairs, 'asks' => $asks
                                        )
                                )
                        )
                ));
            $questionNumber++;
        }
    }
    public function setSchools($schools)
    {
        $this->schools = $schools;
    }
    public function setPresentationTypes($presentationTypes)
    {
        $this->presentationTypes = $presentationTypes;
    }
    public function setSurveyTemplate($surveyTemplate)
    {
        $this->surveyTemplate = $surveyTemplate;
    }
}
