<?php
use STS\Domain\Survey\Question\ShortAnswer;
use STS\Domain\Survey\Question\MultipleChoice;
use STS\Domain\Survey\Response\SingleResponse;

class Presentation_Presentation extends Twitter_Bootstrap_Form_Horizontal
{
    protected $locations;
    protected $presentationTypes;
    protected $surveyTemplate;

    public function init()
    {
        $this->setName('presentationForm');
        $this->setMethod('post');

        // empty validator
        $notEmpty = new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::ZERO);

        // Location
        $this->addElement(
            'select',
            'location',
            array(
                'label' => 'Location',
                'dimension' => 4,
                'MultiOptions' => $this->locations,
                'required' => true,
                'validators' => array($notEmpty)
            )
        );

        // PresentationType
        $this->addElement(
            'select',
            'presentationType',
            array(
                'label' => 'Presentation Type',
                'dimension' => 2,
                'MultiOptions' => $this->presentationTypes,
                'required' => true,
                'validators' => array($notEmpty)
            )
        );

        // Date
        $this->addElement(
            'text',
            'dateOfPresentation',
            array(
                'label' => 'Date of Presentation',
                'dimension' => 2,
                'validators' => array(
                    new Zend_Validate_Date(array('format' => 'MM/dd/yyyy'))
                ),
                'required' => true,
                'append' => array(
                    'name' => 'dateOfPresentationButton',
                    'label' => '',
                    'icon' => 'calendar'
                )
            )
        );

        // Notes
        $this->addElement(
            'textarea',
            'notes',
            array(
                'label' => 'Notes',
                'dimension' => 4,
                'rows' => 5
            )
        );

        // MembersAttended
        $this->addElement(
            'text',
            'membersAttended[]',
            array(
                'label' => 'Members Attended',
                'class' => 'membersAttended',
                'isArray' => true,
                'required' => true,
                'description' => 'Begin typing names to search for and add members...',
                'validators' => array(new \Zend_Validate_NotEmpty(\Zend_Validate_NotEmpty::EMPTY_ARRAY))
            )
        );

        // Participants
        $this->addElement(
            'text',
            'participants',
            array(
                'label' => 'Number of Participants',
                'dimension' => 1,
                'required' => false,
                'validators' => array('int')
            )
        );

        // FormsReturned Pre
        $this->addElement(
            'text',
            'formsReturnedPre',
            array(
                'label' => 'Number of Forms Returned (Pre Class)',
                'dimension' => 1,
                'required' => false,
                'validators' => array('int')
            )
        );

        // FormsReturned Post
        $this->addElement(
            'text',
            'formsReturnedPost',
            array(
                'label' => 'Number of Forms Returned (Post Class)',
                'dimension' => 1,
                'required' => false,
                'validators' => array('int')
            )
        );

        // SurveyForm
        $this->buildSurveyForm();

        //Saving
        $this->addElement(
            'button',
            'submit', array(
                'label' => 'Save Presentation and Survey Information!',
                'type' => 'submit',
                'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_SUCCESS
            )
        );

        $this->addDisplayGroup(
            array('submit'),
            'actions',
            array(
                'disableLoadDefaultDecorators' => true,
                'decorators' => array('Actions')
            )
        );
    }

    /**
     * buildSurveyForm
     *
     * @throws InvalidArgumentException
     */
    public function buildSurveyForm()
    {
        $questionNumber = 1;
        foreach ($this->surveyTemplate->getQuestions() as $questionId => $question) {
            $questionName = "q_{$questionId}";
            $prompt = $questionNumber . ". " . $question->getPrompt();
            $elements = array();
            $pairs = array();

            // setup pre and post test columns depending on when this question is asked
            $whenAsked = $question->whenAsked();
            if ($whenAsked == 1) {
                $asks = array('pre',);
            } elseif ($whenAsked == 2) {
                $asks = array('post');
            } else {
                $asks = array('pre', 'post');
            }

            // add question elements
            if ($question instanceof MultipleChoice) {
                foreach ($question->getChoices() as $choiceId => $choice) {
                    $name = $questionName . "_c_$choiceId";
                    $pairs[] = $name;
                    foreach ($asks as $ask) {
                        $this->addElement(
                            'text', $name . "_$ask",
                            array(
                                'label' => $choice,
                                'dimension' => 1,
                                'required' => false,
                                'value' => $this->getMultiResponse($question, $choiceId, $ask),
                                'validators' => array('int')
                            )
                        );
                        $elements[] = $name . "_$ask";
                    }
                }
            } elseif ($question instanceof ShortAnswer) {
                $name = $questionName . "_c_0";
                $pairs[] = $name;
                foreach ($asks as $ask) {
                    $this->addElement(
                        'textarea',
                        $name . "_$ask",
                        array(
                            'label' => '',
                            'dimension' => 3,
                            'rows' => 10,
                            'required' => false,
                            'value' => $this->getShortResponse($question, $ask),
                        )
                    );
                    $elements[] = $name . "_$ask";
                }
            } else {
                throw new \InvalidArgumentException('Type of question not known.');
            }

            $this->addDisplayGroup(
                $elements,
                $questionId,
                array(
                    'legend' => $prompt,
                    'disableLoadDefaultDecorators' => true,
                    'decorators' => array(
                        array(
                            'ViewScript',
                            array(
                                'viewScript' => 'question.phtml',
                                'pairs' => $pairs,
                                'asks' => $asks
                            )
                        )
                    )
                )
            );
            $questionNumber++;
        }
    }

    public function setLocations($schools)
    {
        $this->locations = $schools;
    }

    public function setPresentationTypes($presentationTypes)
    {
        $this->presentationTypes = $presentationTypes;
    }

    public function setSurveyTemplate($surveyTemplate)
    {
        $this->surveyTemplate = $surveyTemplate;
    }

    private function getMultiResponse($question, $choiceId, $ask)
    {
        if ($response = $question->getResponse($choiceId)) {
            return $this->getResponseFromAsk($response, $ask);
        }
        return null;
    }

    private function getShortResponse($question, $ask)
    {
        if ($response = $question->getResponse()) {
            return $this->getResponseFromAsk($response, $ask);
        }
        return null;
    }

    private function getResponseFromAsk($response, $ask)
    {
        if ($response instanceof SingleResponse) {
            return $response->getResponse();
        } else {
            if ($ask == 'pre') {
                return $response->getBeforeResponse();
            } else {
                return $response->getAfterResponse();
            }
        }
    }
}
