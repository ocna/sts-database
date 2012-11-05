<?php
use STS\Web\Security\AclFactory;
use STS\Core;
use STS\Web\Controller\SecureBaseController;
use STS\Core\Api\ApiException;
use STS\Domain\Member;

class Admin_MemberController extends SecureBaseController {
    protected $memberFacade;
    protected $userFacade;
    protected $locationFacade;
    protected $authFacade;
    protected $mailerFacade;
    public function init() {
        parent::init();
        $core = Core::getDefaultInstance();
        $this->memberFacade = $core->load('MemberFacade');
        $this->userFacade = $core->load('UserFacade');
        $this->locationFacade = $core->load('LocationFacade');
        $this->authFacade = $core->load('AuthFacade');
        $this->mailerFacade = $core->load('MailerFacade');
    }
    public function indexAction() {
        $this->view->members = $this->getMembersArray();
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
            'title' => 'Members',
            'add' => 'Add New Member',
            'addRoute' => '/admin/member/new'
        ));
    }
    public function viewAction() {
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
            'title' => $member->getFirstName() . ' ' . $member->getLastName() ,
            'label' => array(
                'title' => $labelTitle,
                'class' => $lableClass
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
            $validations[] = $form->getElement('systemUserEmail')->isValid($postData['systemUserEmail']);
            $validations[] = $form->getElement('memberType')->isValid($postData['memberType']);
            $validations[] = $form->getElement('role')->isValid($postData['role']);
            $validations[] = $form->getElement('memberStatus')->isValid($postData['memberStatus']);
            $validations[] = $form->getElement('dateTrained')->isValid($postData['dateTrained']);

            $validations[] = $form->getElement('workPhone')->isValid($postData['workPhone']);
            $validations[] = $form->getElement('cellPhone')->isValid($postData['cellPhone']);
            $validations[] = $form->getElement('homePhone')->isValid($postData['homePhone']);

            $validations[] = $form->getElement('addressLineOne')->isValid($postData['addressLineOne']);
            $validations[] = $form->getElement('addressLineTwo')->isValid($postData['addressLineTwo']);
            $validations[] = $form->getElement('city')->isValid($postData['city']);
            $validations[] = $form->getElement('state')->isValid($postData['state']);
            $validations[] = $form->getElement('zip')->isValid($postData['zip']);

            $validations[] = $form->getElement('diagnosisDate')->isValid($postData['diagnosisDate']);
            $validations[] = $form->getElement('diagnosisStage')->isValid($postData['diagnosisStage']);

            if ($postData['memberStatus'] == 'STATUS_ACTIVE') {
                //if member has been marked active, validate any relevant system user information
                if ($postData['role'] != '0') {
                    //if role is not member, validate that a username and email has been entered and that they are unique
                    $validations[] = $form->getElement('systemUsername')->isValid($postData['systemUsername']);
                    $validations[] = $form->getElement('tempPassword')->isValid($postData['tempPassword']);
                    $validations[] = $form->getElement('tempPasswordConfirm')->isValid($postData['tempPasswordConfirm']);
                    if($postData['tempPassword']!=$postData['tempPasswordConfirm']){
                        $form->getElement('tempPasswordConfirm')->addErrors(array(
                            'The two passwords do not match!'
                        ))->markAsError();
                        $validations[] = false;
                    }
                } else {
                    //else validate presents for
                    if (!array_key_exists('presentsFor', $postData) || !is_array($postData['presentsFor'])) {
                        $form->getElement('presentsFor[]')->addErrors(array(
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
                        $form->getElement('coordinatesFor[]')->addErrors(array(
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
                        $form->getElement('facilitatesFor[]')->addErrors(array(
                            'Please enter at least one area.'
                        ))->markAsError();
                        $validations[] = false;
                    } else {
                        $this->view->storedFacilitatesFor = $postData['facilitatesFor'];
                        $validations[] = true;
                    }
                }
            }
            if (!in_array(false, $validations)) {
                try {
                    if ($postData['role'] != '0') {
                        if ($this->userFacade->findUserById($postData['systemUsername']) != array()) {
                            throw new ApiException("A system user with the username: \"{$postData['systemUsername']}\" already exists. System users must have a unique email and username.");
                        }
                        if ($this->userFacade->findUserByEmail($postData['systemUserEmail']) != array()) {
                            throw new ApiException("A system user with the email address: \"{$postData['systemUserEmail']}\" already exists. System users must have a unique email and username.");
                        }
                    }
                    //save new member
                    $newMemberDto = $this->saveNewMember($postData);
                    $successMessage = "The new member \"{$postData['firstName']} {$postData['lastName']}\" has been successfully saved.";
                    if ($postData['role'] != '0') {
                        //save new system user
                        $tempPassword = $postData['tempPassword'];
                        $systemUserDto = $this->saveNewUser($postData, $newMemberDto, $tempPassword);
                        $successMessage .= " The new user with username: \"{$systemUserDto->getId()}\" and password: \"$tempPassword\" may now access the system. Please write this down.";
                    }
                    
                    $this->setFlashMessageAndRedirect($successMessage, 'success', array(
                        'module' => 'admin',
                        'controller' => 'member',
                        'action' => 'index'
                    ));
                }
                catch(ApiException $e) {
                    $this->setFlashMessageAndUpdateLayout('An error occured while saving this information: ' . $e->getMessage() , 'error');
                }
            } else {
                $this->setFlashMessageAndUpdateLayout('It looks like you missed some information, please make the corrections below.', 'error');
            }
        }
        $this->view->form = $form;
    }

    private function sendNotificationOfNewAccount($systemUserDto, $tempPassword){
        $name = $systemUserDto->getFirstName() . ' ' . $systemUserDto->getLastName();
        $username = $systemUserDto->getId();
        $email = $systemUserDto->getEmail();
        $this->mailerFacade->sendNewAccountNotification($name, $username, $email, $tempPassword);
    }
    private function saveNewUser($postData, $newMemberDto, $tempPassword) {
        $firstName = $newMemberDto->getFirstName();
        $lastName = $newMemberDto->getLastName();
        $email = $postData['systemUserEmail'];
        $username = $postData['systemUsername'];
        $password = $tempPassword;
        $role = AclFactory::getAvailableRole($postData['role']);
        $associatedMemberId = $newMemberDto->getId();
        
        return $this->userFacade->createUser($username, $firstName, $lastName, $email, $password, $role, $associatedMemberId);
    }
    private function saveNewMember($data) {
        if ($data['memberStatus'] == 'STATUS_ACTIVE') {
            if ($data['role'] == 'ROLE_ADMIN') {
                $userId = $data['systemUsername'];
                $presentsFor = array();
                $facilitatesFor = array();
                $coordinatesFor = array();
            } elseif ($data['role'] == 'ROLE_FACILITATOR') {
                $userId = $data['systemUsername'];
                $presentsFor = array();
                $facilitatesFor = array_keys($data['facilitatesFor']);
                $coordinatesFor = array();
            } elseif ($data['role'] == 'ROLE_COORDINATOR') {
                $userId = $data['systemUsername'];
                $presentsFor = array();
                $facilitatesFor = array();
                $coordinatesFor = $this->getAreasForRegionsArray(array_keys($data['coordinatesFor']));
            } else {
                $presentsFor = array_keys($data['presentsFor']);
                $facilitatesFor = array();
                $coordinatesFor = array();
                $userId = null;
            }
        } else {
            $presentsFor = array();
            $facilitatesFor = array();
            $coordinatesFor = array();
            $userId = null;
        }

        return $this->memberFacade->saveMember(
            $data['firstName'],
            $data['lastName'],
            Member::getAvailableType($data['memberType']),
            Member::getAvailableStatus($data['memberStatus']),
            $data['notes'],
            $presentsFor,
            $facilitatesFor,
            $coordinatesFor,
            $userId,
            $data['addressLineOne'],
            $data['addressLineTwo'],
            $data['city'],
            $data['state'],
            $data['zip'],
            $data['systemUserEmail'],
            $data['dateTrained'],
            array('date'=>$data['diagnosisDate'], 'stage'=>$data['diagnosisStage']),
            array('work' => $data['workPhone'], 'cell'=> $data['cellPhone'], 'home'=>$data['homePhone'])
        );
    }
    private function getMembersArray() {
        $memberData = array();
        $members = $this->memberFacade->getAllMembers();
        
        foreach ($members as $member) {
            $notes =$member->getNotes();
            $hasNotes = empty($notes) ? false : true;
            $data = array(
                'firstName' => $member->getFirstName() ,
                'lastName' => $member->getLastName() ,
                'deceased' => $member->isDeceased() ,
                'city' => $member->getAddressCity() ,
                'state' => $member->getAddressState() ,
                'status' => $member->getStatus(),
                'hasNotes' => $hasNotes
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
            $memberData[$member->getId() ] = $data;
        }
        
        return $memberData;
    }
    private function getRoleTitleForRole($role) {
        
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
    private function getRoleClassForRole($role) {
        
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
    private function getForm() {
        $diagnosisStagesArray = array_merge(array(
            ''
        ), $this->memberFacade->getDiagnosisStages());

        $statesArray = array_merge(array(
            ''
        ) , $this->locationFacade->getStates());
        $rolesArray = array_merge(array(
            'Member'
        ) , AclFactory::getAvailableRoles());
        $memberTypesArray = array_merge(array(
            ''
        ) , $this->memberFacade->getMemberTypes());
        $memberStatusesArray = $this->memberFacade->getMemberStatuses();
        $form = new \Admin_Member(array(
            'states' => $statesArray,
            'roles' => $rolesArray,
            'memberTypes' => $memberTypesArray,
            'memberStatuses' => $memberStatusesArray,
            'diagnosisStages' => $diagnosisStagesArray,
            'phoneNumberTypes' => $this->memberFacade->getPhoneNumberTypes()
        ));
        
        return $form;
    }
    private function getAreasForRegionsArray($array) {
        $dtos = $this->locationFacade->getAreasForRegions($array);
        $keys = array();
        
        foreach ($dtos as $dto) {
            $keys[] = $dto->getId();
        }
        
        return $keys;
    }
}
