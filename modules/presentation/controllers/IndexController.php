<?php
use STS\Domain\Presentation;
use STS\Core;
use STS\Web\Controller\SecureBaseController;

class Presentation_IndexController extends SecureBaseController
{

    private $core;
    public function init()
    {
        parent::init();
        $this->core = Core::getDefaultInstance();
    }
    public function newAction()
    {
        $this->view->form = $this->getForm();
        $request = $this->getRequest();
        $form = $this->getForm();
        if ($this->getRequest()->isPost()) {
            $postData = $request->getPost();
            if (!is_array($postData['membersAttended'])) {
                $form->getElement('membersAttended[]')
                    ->addErrors(array(
                        'Please enter at least one member.'
                    ))->markAsError();
                $membersValid = false;
            } else {
                $this->view->storedMembers = $postData['membersAttended'];
                $membersValid = true;
            }
            if ($form->isValid($postData) && $membersValid) {
                //process the form
                die('lets do it!');
            }
        }
        $this->view->form = $form;
    }
    private function getForm()
    {
        $schoolFacade = $this->core->load('SchoolFacade');
        $schools = $schoolFacade->getAllSchools();
        $schoolsArray = array(
            ''
        );
        foreach ($schools as $school) {
            $schoolsArray[$school->getId()] = $school->getName();
        }
        $typesArray = array_merge(array(
            ''
        ), Presentation::getTypes());
        $surveyFacade = $this->core->load('SurveyFacade');
        $surveyTemplate = $surveyFacade->getSurveyTemplate(1);
        $form = new \Presentation_Presentation(
                        array(
                                'schools' => $schoolsArray, 'presentationTypes' => $typesArray,
                                'surveyTemplate' => $surveyTemplate
                        ));
        return $form;
    }
}
