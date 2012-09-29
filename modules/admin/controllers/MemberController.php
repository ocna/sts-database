<?php
use STS\Web\Security\AclFactory;
use STS\Core;
use STS\Web\Controller\SecureBaseController;

class Admin_MemberController extends SecureBaseController
{

    protected $memberFacade;
    protected $userFacade;
    protected $locationFacade;
    public function init()
    {
        parent::init();
        $core = Core::getDefaultInstance();
        $this->memberFacade = $core->load('MemberFacade');
        $this->userFacade = $core->load('UserFacade');
        $this->locationFacade = $core->load('LocationFacade');
    }
    public function indexAction()
    {
        $this->view->members = $this->getMembersArray();
        $this->view->layout()->pageHeader = $this->view
            ->partial('partials/page-header.phtml', array(
                'title' => 'Members', 'add' => 'Add New Member', 'addRoute' => '/admin/member/new'
            ));
    }
    public function viewAction()
    {
        $id = $this->getRequest()->getParam('id');
        $member = $this->memberFacade->getMemberById($id);
        if ($member->getAssociatedUserId() != null) {
            $user = $this->userFacade->findUserById($member->getAssociatedUserId());
            $this->view->user = $user;
            $role = $user->getRole();
        } else {
            $role = 'member';
        }
        if ($member->isDeceased()) {
            $labelTitle = 'Deceased';
            $lableClass = 'label-inverse';
        } else {
            $labelTitle = $this->getRoleTitleForRole($role);
            $lableClass = $this->getRoleClassForRole($role);
        }
        $parameters = array(
                'title' => $member->getFirstName() . ' ' . $member->getLastName(),
                'label' => array(
                    'title' => $labelTitle, 'class' => $lableClass
                )
        );
        if ($member->isDeceased()) {
            $parameters['titleClass'] = 'muted';
        }
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', $parameters);
        $this->view->member = $member;
    }
    public function newAction()
    {
        $this->view->form = $this->getForm();
        $request = $this->getRequest();
        $form = $this->getForm();
        if ($this->getRequest()->isPost()) {
            $postData = $request->getPost();
            $validations = array();
            $validations[] = $form->getElement('firstName')->isValid($postData['firstName']);
            $validations[] = $form->getElement('lastName')->isValid($postData['lastName']);
            $validations[] = $form->getElement('memberType')->isValid($postData['memberType']);
            $validations[] = $form->getElement('role')->isValid($postData['role']);
            var_dump($postData['role']);
            if ($postData['role'] != '0') {
                //if role is not member, validate that a username and email has been entered
                $validations[] = $form->getElement('systemUserEmail')->isValid($postData['systemUserEmail']);
                $validations[] = $form->getElement('systemUsername')->isValid($postData['systemUsername']);
            } else {
                //else validate presents for
                if (!array_key_exists('presentsFor', $postData) || !is_array($postData['presentsFor'])) {
                    $form->getElement('presentsFor[]')
                        ->addErrors(array(
                            'Please enter at least one area.'
                        ))->markAsError();
                    $validations[] = false;
                } else {
                    $this->view->storedPresentsFor = $postData['presentsFor'];
                    $validations[] = true;
                }
            }
            if ($postData['role'] == 'ROLE_COORDINATOR') {
                //if role is coordinator, validate regions
                if (!array_key_exists('coordinatesFor', $postData) || !is_array($postData['coordinatesFor'])) {
                    $form->getElement('coordinatesFor[]')
                        ->addErrors(array(
                            'Please enter at least one region.'
                        ))->markAsError();
                    $validations[] = false;
                } else {
                    $this->view->storedCoordinatesFor = $postData['coordinatesFor'];
                    $validations[] = true;
                }
            }
            if ($postData['role'] == 'ROLE_FACILITATOR') {
                //if role is facilitator, validate areas
                if (!array_key_exists('facilitatesFor', $postData) || !is_array($postData['facilitatesFor'])) {
                    $form->getElement('facilitatesFor[]')
                        ->addErrors(array(
                            'Please enter at least one area.'
                        ))->markAsError();
                    $validations[] = false;
                } else {
                    $this->view->storedFacilitatesFor = $postData['facilitatesFor'];
                    $validations[] = true;
                }
            }
            if (!in_array('false', $validations)) {
                try {
                    //todo this is where you save the new member
                    //todo determine success message
                    $this
                        ->setFlashMessageAndRedirect($successMessage, 'success', array(
                            'module' => 'admin', 'controller' => 'member', 'action' => 'index'
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
    private function getMembersArray()
    {
        $memberData = array();
        $members = $this->memberFacade->getAllMembers();
        foreach ($members as $member) {
            $data = array(
                    'firstName' => $member->getFirstName(), 'lastName' => $member->getLastName(),
                    'deceased' => $member->isDeceased(), 'city' => $member->getAddressCity(),
                    'state' => $member->getAddressState()
            );
            if ($member->getAssociatedUserId() != null) {
                $user = $this->userFacade->findUserById($member->getAssociatedUserId());
                $role = $user->getRole();
            } else {
                $role = 'member';
            }
            if ($member->isDeceased()) {
                $data['role'] = 'Deceased';
                $data['roleClass'] = 'label-inverse';
            } else {
                $data['role'] = $this->getRoleTitleForRole($role);
                $data['roleClass'] = $this->getRoleClassForRole($role);
            }
            $memberData[$member->getId()] = $data;
        }
        return $memberData;
    }
    private function getRoleTitleForRole($role)
    {
        switch ($role) {
            case 'admin':
                $role = "Site Administrator";
                break;
            case 'coordinator':
                $role = "Regional Coordinator";
                break;
            case 'facilitator':
                $role = "Area Facilitator";
                break;
            default:
                $role = "Member";
                break;
        }
        return $role;
    }
    private function getRoleClassForRole($role)
    {
        switch ($role) {
            case 'admin':
                $roleClass = "label-important";
                break;
            case 'coordinator':
                $roleClass = "label-warning";
                break;
            case 'facilitator':
                $roleClass = "label-info";
                break;
            default:
                $roleClass = "";
                break;
        }
        return $roleClass;
    }
    private function getForm()
    {
        $statesArray = array_merge(array(
            ''
        ), $this->locationFacade->getStates());
        $rolesArray = array_merge(array(
            'Member'
        ), AclFactory::getAvailableRoles());
        $memberTypesArray = array_merge(array(
            ''
        ), $this->memberFacade->getMemberTypes());
        $form = new \Admin_Member(
                        array(
                            'states' => $statesArray, 'roles' => $rolesArray, 'memberTypes' => $memberTypesArray
                        ));
        return $form;
    }
}
