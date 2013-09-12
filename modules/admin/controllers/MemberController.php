<?php
use STS\Web\Security\AclFactory;
use STS\Core;
use STS\Web\Controller\SecureBaseController;
use STS\Core\Api\ApiException;
use STS\Domain\Member;
use STS\Core\User\UserDTO;

/**
 * Class Admin_MemberController
 *
 * @property STS\Core\Api\MemberFacade $memberFacade
 * @property STS\Core\Api\UserFacade $userFacade
 * @property STS\Core\Api\LocationFacade $locationFacade
 * @property STS\Core\Api\AuthFacade $authFacade
 * @property STS\Core\Api\MailerFacade $mailerFacade
 */
class Admin_MemberController extends SecureBaseController
{
    protected $memberFacade;
    protected $userFacade;
    protected $locationFacade;
    protected $authFacade;
    protected $mailerFacade;

    public function init()
    {
        parent::init();
        $core = Core::getDefaultInstance();
        $this->memberFacade = $core->load('MemberFacade');
        $this->userFacade = $core->load('UserFacade');
        $this->locationFacade = $core->load('LocationFacade');
        $this->authFacade = $core->load('AuthFacade');
        $this->mailerFacade = $core->load('MailerFacade');
    }

    public function indexAction()
    {
        $this->view->layout()->pageHeader = $this->view->partial(
            'partials/page-header.phtml',
            array(
                'title' => 'Members',
                'add' => 'Add New Member',
                'addRoute' => '/admin/member/new'
                )
        );

        // setup filters
        $form = $this->getFilterForm();
        $criteria = array();
        $params = $this->getRequest()->getParams();

        if (array_key_exists('reset', $params)) {
            return $this->_helper->redirector('index');
        }
        if (array_key_exists('update', $params)) {
            $form->setDefaults($params);
            $this->filterParams('role', $params, $criteria);
            $this->filterParams('status', $params, $criteria);
            $this->filterParams('region', $params, $criteria);
        }
        $this->view->form = $form;

        // load all the members to display
        // TODO add pagination?
        $members = $this->memberFacade->getMembersMatching($criteria);
        $memberDtos = $this->getMembersArray($members);
        if(empty($memberDtos) && array_key_exists('update', $params)){
            $this->setFlashMessageAndRedirect('No members matched your selected filter criteria!','warning', array(
                        'module' => 'admin',
                        'controller' => 'member',
                        'action' => 'index'
                    ));
        }
        $this->view->members = $memberDtos;
    }

    private function filterParams($key, &$params, &$criteria)
    {
        if (array_key_exists($key, $params)) {
            $chaff = array_search('0', $params[$key]);
            if($chaff !== false){
                unset($params[$key][$chaff]);
            }
            $criteria[$key] = $params[$key];
        }
    }

    /**
     * getFilterForm
     *
     * Return the filter form for list of all members
     *
     * @return Admin_MemberFilter
     */
    private function getFilterForm()
    {
        $form = new \Admin_MemberFilter(
            array(
                'roles' => array_merge(array(0=>'', 'ROLE_MEMBER'=>'Member'), AclFactory::getAvailableRoles()),
                'regions' => $this->getRegionsArray(),
                'memberStatuses' => array_merge(array(''), $this->getMemberStatusesArray())
            )
        );
        return $form;
    }

    private function getRegionsArray()
    {
        $regionsArray = array('');
        foreach ($this->locationFacade->getAllRegions() as $region) {
            $regionsArray[$region->getName()] = $region->getName();
        }
        return $regionsArray;
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        try{
            $results = $this->memberFacade->deleteMember($id);
            if ($results === true) {
                $this->setFlashMessageAndRedirect('The member has been removed from the system!', 'success', array(
                        'module' => 'admin',
                        'controller' => 'member',
                        'action' => 'index'
                    ));
            } else {
                throw new ApiException("An error occured while deleting member.", 1);
            }
        } catch (ApiException $e) {
            $this->setFlashMessageAndRedirect('An error occured while deleting member: ' . $e->getMessage() , 'error', array(
                        'module' => 'admin',
                        'controller' => 'member',
                        'action' => 'index'));
        }
    }

    public function viewAction()
    {
        $id = $this->getRequest()->getParam('id');
        $member = $this->memberFacade->getMemberById($id);
        if ($user = $this->userFacade->findUserById($member->getAssociatedUserId())) {
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

    /**
     * newAction
     *
     * Add a new member. Display the form, process POST data.
     *
     * @access public
     */
    public function newAction()
    {
        $this->view->form = $this->getForm();
        $request = $this->getRequest();
        $form = $this->getForm();
        $form->setAction('/admin/member/new');

        // handle POST input
        if ($this->getRequest()->isPost()) {
            $postData = $request->getPost();
            if ($this->formIsValid($form, $postData)) {
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
                        //send credentials via email
                        $name = $systemUserDto->getFirstName() . ' ' . $systemUserDto->getLastName();
                        $this->mailerFacade->sendNewAccountNotification($name, $systemUserDto->getId(), $systemUserDto->getEmail(), $tempPassword);
                        //update success message
                        $successMessage .= " The new user with username: \"{$systemUserDto->getId()}\" and password: \"$tempPassword\" may now access the system. This information has been emailed to them.";
                    }

                    $this->setFlashMessageAndRedirect($successMessage, 'success', array(
                        'module' => 'admin',
                        'controller' => 'member',
                        'action' => 'index'
                    ));
                } catch(ApiException $e) {
                    $this->setFlashMessageAndUpdateLayout('An error occured while saving this information: ' . $e->getMessage() , 'error');
                }
            } else {
                $this->setFlashMessageAndUpdateLayout('It looks like you missed some information, please make the corrections below.', 'error');
            }
        }
        $this->view->form = $form;
    }

    /**
     * editAction
     *
     * @access public
     **/
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $form = $this->getForm();

        // for checking edit permissions
        $acl = $this->getAcl();

        // load our member
        $dto = $this->memberFacade->getMemberById($id);

        // make sure form posts back to self
        $form->setAction('/admin/member/edit?' .http_build_query(array('id' => $id)));
        $this->view->member = $dto;
        $this->view->layout()->pageHeader = $this->view->partial(
            'partials/page-header.phtml', array(
                'title' => 'Edit: ' .$dto->getFirstName() . ' ' . $dto->getLastName()
            )
        );

        // get the member associated user id to see if the user is a member
        $associatedUserId = $dto->getAssociatedUserId();

        // also get the associated user if there is one
        // associatedUser is what holds login credentials
        $associatedUser = $this->userFacade->getUserByMemberId($dto->getId());

        // the user name will always be set
        $username = null;
        $hiddenUsername = null;

        if (!is_null($associatedUser)) {
            $username = $associatedUser->getId();
            $hiddenUsername = $username;

            // make sure user can change username
            $role = $this->getAuth()->getIdentity()->getRole();
            if (!$acl->isAllowed($role, AclFactory::RESOURCE_USER, 'change username')) {
                $form->getElement('systemUsername')->setAttrib('disabled', 'disabled');
            }

            $form->getElement('tempPassword')->setRequired(false);
            $form->getElement('tempPassword')->setAttrib('placeholder', 'xxxxxxxxxx');
            $form->getElement('tempPassword')->setDescription('This member has a user account and password. Only change these fields if you want to change their password!');
            $form->getElement('tempPasswordConfirm')->setRequired(false);
            $form->getElement('tempPasswordConfirm')->setAttrib('placeholder', 'xxxxxxxxxx');
        }

        // if the id is null, the member is just a member, so don't show user details
        // TODO this test for if someone is "just a member" should be in the memberFacade or model
        if (is_null($associatedUserId) || empty($associatedUserId)){
            $role = '0';
        } else {
            //else set the role
            $role = $this->userFacade->getUserRoleKey($associatedUser->getRole());
        }

        // populate the form data
        $form->populate(
            array(
                'firstName' => $dto->getFirstName(),
                'lastName' => $dto->getLastName(),
                'systemUserEmail' => $dto->getEmail(),
                'memberType' => $this->memberFacade->getMemberTypeKey($dto->getType()),
                'memberStatus' => $this->memberFacade->getMemberStatusKey($dto->getStatus()),
                'memberActivity' => $dto->getActivities(),
                'dateTrained' => $dto->getDateTrained(),
                'notes' => $dto->getNotes(),
                'workPhone' => $this->getPhoneNumberFromDto('work', $dto->getPhoneNumbers()),
                'homePhone' => $this->getPhoneNumberFromDto('home', $dto->getPhoneNumbers()),
                'cellPhone' => $this->getPhoneNumberFromDto('cell', $dto->getPhoneNumbers()),
                'addressLineOne' => $dto->getAddressLineOne(),
                'addressLineTwo' => $dto->getAddressLineTwo(),
                'city' => $dto->getAddressCity(),
                'state'=> $dto->getAddressState(),
                'zip' => $dto->getAddressZip(),
                'diagnosisDate' => $dto->getDiagnosisDate(),
                'diagnosisStage' => $dto->getDiagnosisStage(),
                'role' => $role,
                'systemUsername' => $username,
                'hiddenSystemUsername' => $hiddenUsername
            )
        );

        $this->view->storedPresentsFor = $dto->getPresentsForAreas();
        $this->view->storedCoordinatesFor = $dto->getCoordinatesForRegions();
        $this->view->storedFacilitatesFor = $dto->getFacilitatesForAreas();

        // process any updates if we get any
        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $postData = $request->getPost();
            if ($this->formIsValid($form, $postData)) {
                try {
                    // if a member has been upgraded to a system user, check the email
                    // and password to ensure no duplication
                    if (!empty($postData['systemUsername']) && $postData['role'] != '0') {

                        // test if the username is used by another record
                        $dupe = $this->userFacade->findUserById($postData['systemUsername']);
                        if (!empty($dupe) && $dupe->getAssociatedMemberId() != $associatedUser->getAssociatedMemberId()) {
                            throw new ApiException("A system user with the username: \"{$postData['systemUsername']}\" already exists. System users must have a unique email and username.");
                        }

                        // test if the email is used by another record
                        $dupe = $this->userFacade->findUserByEmail($postData['systemUserEmail']);
                        if (!empty($dupe) && $dupe->getAssociatedMemberId() != $associatedUser->getAssociatedMemberId()) {
                            throw new ApiException("A system user with the email address: \"{$postData['systemUserEmail']}\" already exists. System users must have a unique email and username.");
                        }
                    }
                    
                    // check if we are changing an existing user's name
                    if ($postData['role'] != '0' && !empty($postData['hiddenSystemUsername'])
                        && $postData['hiddenSystemUsername'] != $postData['systemUsername']
                    ) {
                        $this->changeUsername($associatedUser, $dto, $postData);

                        // handle other form updates
                        $postData['hiddenSystemUsername'] = $postData['systemUsername'];
                        $updatedMemberDto = $this->updateMember($id, $postData);
                    } else {
                        // if a member has be downgraded from a system user to a member
                        // its ok as that is handled by the saving
                        $updatedMemberDto = $this->updateMember($id, $postData);
                        $successMessage = "The member \"{$postData['firstName']} {$postData['lastName']}\" has been successfully updated.";

                        // if a system user is changed roles
                        // then confirm that and set the username to the hidden value
                        if ($postData['role'] != '0') {
                            if (! empty($postData['systemUsername'])) {
                                // the user is new, we must add them
                                $tempPassword = $postData['tempPassword'];
                                $systemUserDto = $this->saveNewUser($postData, $updatedMemberDto, $tempPassword);
                                // send credentials via email
                                $name = $systemUserDto->getFirstName() . ' ' . $systemUserDto->getLastName();
                                $this->mailerFacade->sendNewAccountNotification($name, $systemUserDto->getId(), $systemUserDto->getEmail(), $tempPassword);
                                $successMessage .= " The new user with username: \"{$systemUserDto->getId()}\" and password: \"$tempPassword\" may now access the system. This information has been emailed to them.";
                            } else {
                                // the user has changed, we must modify
                                $postData['systemUsername'] = $postData['hiddenSystemUsername'];
                                $tempPassword = $postData['tempPassword'];
                                $systemUserDto = $this->updateExistingUser($postData, $updatedMemberDto, $tempPassword);

                                // send credentials via email
                                $name = $systemUserDto->getFirstName() . ' ' . $systemUserDto->getLastName();
                                $this->mailerFacade->sendNewAccountNotification($name, $systemUserDto->getId(), $systemUserDto->getEmail(), $tempPassword);
                                $successMessage .= " The user with username: \"{$systemUserDto->getId()}\" has been updated! Updated information has been emaild to them.";
                            }
                        }
                    }

                    $this->setFlashMessageAndRedirect($successMessage, 'success', array(
                        'module' => 'admin',
                        'controller' => 'member',
                        'action' => 'view',
                        'params' => array('id' => $updatedMemberDto->getId())
                    ));
                } catch(ApiException $e) {
                    $this->setFlashMessageAndUpdateLayout('An error occurred while saving this information: ' . $e->getMessage() , 'error');
                }
            } else {
                $this->setFlashMessageAndUpdateLayout('It looks like you missed some information, please make the corrections below.', 'error');
            }
        }
        $this->view->form = $form;
    }

    private function getUserRoleFromDto($dto)
    {
        if (is_null($dto)) {
            return '0';
        } else {
           return $this->userFacade->getUserRoleKey($dto->getRole());
        }
    }

    private function getUserNameFromDto($dto)
    {
        if (is_null($dto)) {
            return null;
        } else {
           return $dto->getId();
        }
    }

    private function getPhoneNumberFromDto($type, $numbers)
    {
        if (! is_null($numbers) && array_key_exists($type, $numbers)) {
            $number = $numbers[$type]['number'];
            return sprintf('%s-%s-%s', substr($number, 0,3), substr($number, 3, -4), substr($number, -4));
        } else {
            return null;
        }
    }

    private function sendNotificationOfNewAccount($systemUserDto, $tempPassword)
    {
        $name = $systemUserDto->getFirstName() . ' ' . $systemUserDto->getLastName();
        $username = $systemUserDto->getId();
        $email = $systemUserDto->getEmail();
        $this->mailerFacade->sendNewAccountNotification($name, $username, $email, $tempPassword);
    }

    private function saveNewUser($postData, $newMemberDto, $tempPassword)
    {
        $firstName = $newMemberDto->getFirstName();
        $lastName = $newMemberDto->getLastName();
        $email = $postData['systemUserEmail'];
        $username = $postData['systemUsername'];
        $password = $tempPassword;
        $role = AclFactory::getAvailableRole($postData['role']);
        $associatedMemberId = $newMemberDto->getId();

        return $this->userFacade->createUser($username, $firstName, $lastName, $email, $password, $role, $associatedMemberId);
    }

    private function updateExistingUser($postData, $memberDto, $tempPassword)
    {
        $firstName = $memberDto->getFirstName();
        $lastName = $memberDto->getLastName();
        $email = $postData['systemUserEmail'];
        $username = $postData['systemUsername'];
        $password = $tempPassword;
        $role = AclFactory::getAvailableRole($postData['role']);
        $associatedMemberId = $memberDto->getId();
        return $this->userFacade->updateUser($username, $firstName, $lastName, $email, $password, $role, $associatedMemberId);
    }

    private function saveNewMember($data)
    {
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

        $activities = array();
        if ($data['memberActivity']) {
            $activities = array_keys($data['memberActivity']);
        }

        return $this->memberFacade->saveMember(
            $data['firstName'],
            $data['lastName'],
            Member::getAvailableType($data['memberType']),
            Member::getAvailableStatus($data['memberStatus']),
            $activities,
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

    /**
     * updateMember
     *
     * @param $id
     * @param $data
     * @return mixed
     */
    private function updateMember($id, $data)
    {
        // persist the username
        if (empty($data['systemUsername'])) {
            $data['systemUsername'] = $data['hiddenSystemUsername'];
        }

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

        $activities = array();
        if ($data['memberActivity']) {
            $activities = $data['memberActivity'];
        }

        return $this->memberFacade->updateMember($id,
            $data['firstName'],
            $data['lastName'],
            Member::getAvailableType($data['memberType']),
            Member::getAvailableStatus($data['memberStatus']),
            $activities,
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

    private function getMembersArray($members)
    {
        $memberData = array();
        if (empty($members)){
            return $memberData;
        }
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
                'hasNotes' => $hasNotes,
                'canBeDeleted' => $member->canBeDeleted()
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
        // get diagnosis select options
        $diagnosisStagesArray = array_merge(array(''), $this->memberFacade->getDiagnosisStages());

        // get states select options
        $statesArray = array_merge(array('-- Select One --') , $this->locationFacade->getStates());

        // get member types select options
        $memberTypesArray = array_merge(array('-- Select One --') , $this->memberFacade->getMemberTypes());

        // get member activities checkbox options
        $vals = array_values($this->memberFacade->getMemberActivities());
        $memberActivitiesArray = array_combine($vals, $vals);

        // build the zend form
        $form = new \Admin_Member(array(
            'states' => $statesArray,
            'roles' => $this->getRolesArray(),
            'memberTypes' => $memberTypesArray,
            'memberStatuses' => $this->getMemberStatusesArray(),
            'memberActivities' => $memberActivitiesArray,
            'diagnosisStages' => $diagnosisStagesArray,
            'phoneNumberTypes' => $this->memberFacade->getPhoneNumberTypes()
        ));

        return $form;
    }

    private function getRolesArray()
    {
        return array_merge(array('Member'), AclFactory::getAvailableRoles());
    }

    private function getMemberStatusesArray()
    {
        return $this->memberFacade->getMemberStatuses();
    }

    private function getAreasForRegionsArray($array)
    {
        $dtos = $this->locationFacade->getAreasForRegions($array);
        $keys = array();

        foreach ($dtos as $dto) {
            $keys[] = $dto->getId();
        }

        return $keys;
    }

    private function formIsValid(&$form, $postData)
    {
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
                if(array_key_exists('systemUsername', $postData)){
                    $validations[] = $form->getElement('systemUsername')->isValid($postData['systemUsername']);
                } else {
                    $validations[] = $form->getElement('systemUsername')->isValid($postData['hiddenSystemUsername']);

                }
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

        if (!in_array(false, $validations)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * changeUsername
     * 
     * Changes a username and updates references to it.
     * 
     * @param $associatedUser
     * @param $dto
     * @param $postData
     */
    private function changeUsername(UserDTO $srcUser, $dto, $postData) {
        // create user w/new username from old user
        // use data coming from the form in case its been edited
        if (!empty($postData['tempPassword'])) {
            $password = $postData['tempPassword'];
            $salt = NULL;
            $init_password = TRUE;
        } else {
            $password = $srcUser->getPassword();
            $salt = $srcUser->getSalt();
            $init_password = FALSE;
        }

        $user = $this->userFacade->createUser(
            $postData['systemUsername'],
            $postData['firstName'],
            $postData['lastName'],
            $postData['systemUserEmail'], // in case they update the form
            $password,
            AclFactory::getAvailableRole($postData['role']),
            $srcUser->getAssociatedMemberId(),
            $init_password,
            $salt
        );

        $old_user_id = $srcUser->getId();

        // change all references in presentation collection (entered_by_user_id)
        $core = Core::getDefaultInstance();
        $presentationFacade = $core->load('PresentationFacade');
        $presentationFacade->updateEnteredBy($old_user_id, $user->getId());

        // change all references in survey collection (entered_by_user_id)
        $surveyFacade = $core->load('SurveyFacade');
        $surveyFacade->updateEnteredBy($old_user_id, $user->getId());

        // finally, delete old user
        $this->userFacade->deleteUser($old_user_id);
    }
}
