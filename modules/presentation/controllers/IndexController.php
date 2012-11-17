<?php
use STS\Core\Api\ApiException;
use STS\Domain\School\Specification\MemberSchoolSpecification;
use STS\Domain\Presentation;
use STS\Core;
use STS\Web\Controller\SecureBaseController;

class Presentation_IndexController extends SecureBaseController
{

    private $user;
    private $presentationFacade;
    private $surveyFacade;
    private $memberFacade;
    private $schoolFacade;
    public function init()
    {
        parent::init();
        $core = Core::getDefaultInstance();
        $this->user = $this->getAuth()->getIdentity();
        $this->presentationFacade = $core->load('PresentationFacade');
        $this->surveyFacade = $core->load('SurveyFacade');
        $this->memberFacade = $core->load('MemberFacade');
        $this->schoolFacade = $core->load('SchoolFacade');
    }

    public function indexAction()
    {
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
            'title' => 'Presentations',
            'add' => 'Add New Presentation',
            'addRoute' => '/presentation/index/new'
        ));

        $dtos = $this->presentationFacade->getPresentationsForUserId($this->user->getId());
        $this->view->objects = $dtos;
    }

    public function viewAction()
    {
        $id = $this->getRequest()->getParam('id');
        $dto = $this->presentationFacade->getPresentationById($id);
        $this->view->layout()->pageHeader = $this->view
            ->partial('partials/page-header.phtml', array(
                'title' => $dto->getSchoolName(). ' - '. $dto->getDate()
            ));
        $this->view->presentation = $dto;
    }

    public function newAction()
    {
        $surveyTemplate = $this->surveyFacade->getSurveyTemplate(1);
        $this->view->form = $this->getForm($surveyTemplate);
        $request = $this->getRequest();
        $form = $this->getForm($surveyTemplate);
        $form->setAction('/presentation/index/new');
        if ($this->getRequest()->isPost()) {
            $postData = $request->getPost();
            if (!array_key_exists('membersAttended', $postData) || !is_array($postData['membersAttended'])) {
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
                try {
                    $this->savePresentation($postData);
                    $this
                        ->setFlashMessageAndRedirect('You have successfully completed the presentation and survey entry process!', 'success', array(
                            'module' => 'presentation', 'controller' => 'index', 'action' => 'index'
                        ));
                } catch (ApiException $e) {
                    $this
                        ->setFlashMessageAndUpdateLayout('An error occured while saving this information: '
                                        . $e->getMessage(), 'error');
                }
            } else {
                $this
                    ->setFlashMessageAndUpdateLayout('It looks like you missed some information, please make the corrections below.', 'error');
            }
        }
        $this->view->form = $form;
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $dto = $this->presentationFacade->getPresentationById($id);
        $this->view->layout()->pageHeader = $this->view
            ->partial('partials/page-header.phtml', array(
                'title' => 'Edit: '.$dto->getSchoolName(). ' - '. $dto->getDate()
            ));

        $survey = $this->surveyFacade->getSurveyById($dto->getSurveyId());
        $form = $this->getForm($survey);
        $form->setAction('/presentation/index/edit?id='.$id);
        //populate form from existing values
        $form->populate(
            array(
                'location'=>$dto->getSchoolId(),
                'presentationType'=> $this->presentationFacade->getTypeKey($dto->getType()),
                'dateOfPresentation'=>$dto->getDate(),
                'notes'=>$dto->getNotes(),
                'participants'=>$dto->getNumberOfParticipants(),
                'formsReturnedPre'=>$dto->getNumberOfFormsReturnedPre(),
                'formsReturnedPost'=>$dto->getNumberOfFormsReturnedPost()
            )
        );
        //populate members
        $members = array();
        foreach ($dto->getMembersArray() as $key => $value) {
            $members[$key]=$value['fullname'];
        }
        $this->view->storedMembers = $members;
        //need to get the survey object from the id then use it to populate the template
        //process form
        ##DO STUFF HERE
        $this->view->form = $form;
    }

    private function getForm($surveyOrTemplate)
    {
        $schools = $this->getSchoolsVisableToMember();
        $schoolsArray = array(
            ''
        );
        foreach ($schools as $school) {
            $schoolsArray[$school->getId()] = $school->getName();
        }
        $typesArray = array_merge(array(
            ''
        ), $this->presentationFacade->getPresentationTypes());
        $form = new \Presentation_Presentation(
                        array(
                                'schools' => $schoolsArray, 'presentationTypes' => $typesArray,
                                'surveyTemplate' => $surveyOrTemplate
                        ));
        return $form;
    }
    private function getSchoolsVisableToMember()
    {
        $schoolSpec = null;
        if ($this->user->getAssociatedMemberId() && $this->user->getRole() != 'admin') {
            $schoolSpec = $this->memberFacade->getMemberSchoolSpecForId($this->user->getAssociatedMemberId());
        }
        return $this->schoolFacade->getSchoolsForSpecification($schoolSpec);
    }
    private function savePresentation($postData)
    {
        //Get User
        $userId = $this->user->getId();
        $templateId = 1;
        //First Save Survey Built
        $surveyData = array();
        foreach ($postData as $key => $value) {
            if (substr($key, 0, 2) == 'q_') {
                $surveyData[$key] = $value;
            }
        }
        $surveyId = $this->surveyFacade->saveSurvey($userId, $templateId, $surveyData);
        //Then Save Presentation
        $members = array_keys($postData['membersAttended']);
        $this->presentationFacade
            ->savePresentation($userId, $postData['location'], $postData['presentationType'], $postData['dateOfPresentation'], $postData['notes'], $members, $postData['participants'], $postData['formsReturnedPost'], $surveyId, $postData['formsReturnedPre']);
        return true;
    }
}
