<?php
use STS\Web\Security\AclFactory;
use STS\Core\Api\ApiException;
use STS\Domain\School\Specification\MemberSchoolSpecification;
use STS\Domain\Presentation;
use STS\Core;
use STS\Web\Controller\SecureBaseController;

class Presentation_IndexController extends SecureBaseController
{

    private $user;

    /**
     * @var \STS\Core\Api\DefaultPresentationFacade
     */
    private $presentationFacade;
    private $surveyFacade;
    private $memberFacade;
    private $schoolFacade;

    /**
     * @var \STS\Core\Api\AuthFacade
     */
    private $authFacade;

    public function init()
    {
        parent::init();
        $core = Core::getDefaultInstance();
        $this->user = $this->getAuth()->getIdentity();
        $this->presentationFacade = $core->load('PresentationFacade');
        $this->surveyFacade = $core->load('SurveyFacade');
        $this->memberFacade = $core->load('MemberFacade');
        $this->schoolFacade = $core->load('SchoolFacade');
        $this->authFacade = $core->load('AuthFacade');

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

        // make sure user can edit presentations
        $role = $this->getAuth()->getIdentity()->getRole();
        if ($this->getAcl()->isAllowed($role, AclFactory::RESOURCE_PRESENTATION, 'edit')) {
            $this->view->can_edit = TRUE;
        } else {
            $this->view->can_edit = FALSE;
        }
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
        try {
            $this->view->survey = $this->surveyFacade->getSurveyById($dto->getSurveyId());
        } catch (\InvalidArgumentException $e) {
             $this->view->survey = null;
        }

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
                        ->setFlashMessageAndUpdateLayout('An error occurred while saving this information: '
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
        $surveyId = $dto->getSurveyId();
        $this->view->layout()->pageHeader = $this->view
            ->partial('partials/page-header.phtml', array(
                'title' => 'Edit: '.$dto->getSchoolName(). ' - '. $dto->getDate()
            ));
        try {
            $survey = $this->surveyFacade->getSurveyById($surveyId);
        } catch (\InvalidArgumentException $e) {
            $survey = $this->surveyFacade->getSurveyTemplate(1);
        }
        $form = $this->getForm($survey);
        $form->setAction('/presentation/index/edit?id='.$id);
        //populate form from existing values
        $form->populate(
            array(
                'location' => $dto->getSchoolId(),
                'presentationType' => $this->presentationFacade->getTypeKey($dto->getType()),
                'dateOfPresentation' => $dto->getDate(),
                'notes' => $dto->getNotes(),
                'participants' => $dto->getNumberOfParticipants(),
                'formsReturnedPre' => $dto->getNumberOfFormsReturnedPre(),
                'formsReturnedPost' => $dto->getNumberOfFormsReturnedPost()
            )
        );
        //populate members
        $members = array();
        foreach ($dto->getMembersArray() as $key => $value) {
            $members[$key]=$value['fullname'];
        }
        $this->view->storedMembers = $members;
        //process form
        $request = $this->getRequest();
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
                    $this->updatePresentation($postData, $id, $surveyId);
                    $this
                        ->setFlashMessageAndRedirect('You have successfully updated the presentation and survey!', 'success', array(
                            'module' => 'presentation', 'controller' => 'index', 'action' => 'view', 'params' => array('id' => $id)
                        ));
                } catch (ApiException $e) {
                    $this
                        ->setFlashMessageAndUpdateLayout('An error occurred while saving this information: '
                                        . $e->getMessage(), 'error');
                }
            } else {
                $this
                    ->setFlashMessageAndUpdateLayout('It looks like you missed some information, please make the corrections below.', 'error');
            }
        }
        $this->view->form = $form;
    }

    /**
     * @throws Symfony\Component\Finder\Exception\AccessDeniedException
     */
    public function deleteAction()
    {
        if (!$this->getRequest()->isPost()) {
            throw new \Symfony\Component\Finder\Exception\AccessDeniedException();
        }

        $post = $this->getRequest()->getPost();
        $id = $post['id'];

        $this->presentationFacade->deletePresentation($id);

        $this->setFlashMessageAndRedirect(
            'You have successfully deleted the presentation and survey!',
            'success',
            array(
                'module' => 'presentation', 'controller' => 'index', 'action' => 'index'
            )
        );
    }

    private function getForm($surveyOrTemplate)
    {
        $schools = $this->getSchoolsVisableToMember();
        $schoolsArray = array();
        foreach ($schools as $school) {
            $schoolsArray[$school->getId()] = $school->getName();
        }

        $typesArray = array_merge(array(''), $this->presentationFacade->getPresentationTypes());

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
        $this->presentationFacade->savePresentation(
                $userId,
                $postData['location'],
                $postData['presentationType'],
                $postData['dateOfPresentation'],
                $postData['notes'],
                $members,
                $postData['participants'],
                $postData['formsReturnedPost'],
                $surveyId,
                $postData['formsReturnedPre']
            );
        return true;
    }

    private function updatePresentation($postData, $presentationId, $surveyId)
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
        $surveyId = $this->surveyFacade->updateSurvey($userId, $templateId, $surveyData, $surveyId);
        //Then Save Presentation
        $members = array_keys($postData['membersAttended']);
        $this->presentationFacade->updatePresentation(
            $presentationId,
            $postData['location'],
            $postData['presentationType'],
            $postData['dateOfPresentation'],
            $postData['notes'],
            $members,
            $postData['participants'],
            $postData['formsReturnedPost'],
            $postData['formsReturnedPre']
            );
        return true;
    }
}
