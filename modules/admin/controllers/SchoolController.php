<?php
use STS\Core;
use STS\Web\Security\AclFactory;
use STS\Web\Controller\SecureBaseController;
use STS\Domain\User;
use STS\Core\Api\ApiException;
use STS\Core\Api\DefaultLocationFacade;
use STS\Core\Api\DefaultMemberFacade;
use STS\Core\Api\DefaultSchoolFacade;
use STS\Core\School\SchoolDto;
use STS\Core\Location\RegionDto;

class Admin_SchoolController extends SecureBaseController
{
    /**
     * @var DefaultSchoolFacade
     */
    protected $schoolFacade;

    /**
     * @var DefaultLocationFacade
     */
    protected $locationFacade;

    /**
     * @var DefaultMemberFacade
     */
    protected $memberFacade;

    /**
     * @var \Zend_Session_Namespace
     */
    protected $session;

    public function init()
    {
        parent::init();
        $core = Core::getDefaultInstance();
        $this->schoolFacade = $core->load('SchoolFacade');
        $this->locationFacade = $core->load('LocationFacade');
        $this->memberFacade = $core->load('MemberFacade');
        $this->session = new \Zend_Session_Namespace('admin');

    }

    public function indexAction()
    {
        $this->setPermissions();
        // filter by role
        $user = $this->getAuth()->getIdentity();

        // setup filters
        $form = $this->getFilterForm($user);
        $criteria = array();

        $this->session->criteria = $criteria;
        $params = $this->getRequest()->getParams();

        if (array_key_exists('reset', $params)) {
            return $this->_helper->redirector('index');
        }
        if (array_key_exists('update', $params)) {
            $form->setDefaults($params);
            $this->filterParams('region', $params, $criteria);
            $this->filterParams('type', $params, $criteria);
            $this->session->criteria = $criteria;
        }

        if (User::ROLE_COORDINATOR == $user->getRole()) {
            if (!isset($criteria['region'])) {
                $member = $this->memberFacade->getMemberById($user->getAssociatedMemberId());
                $regions = array_merge(array(''), $member->getCoordinatesForRegions());
                $criteria['region'] = $regions;
                $this->session->criteria = $criteria;
            }
        }

        if (!empty($criteria)) {
            // turn it into a specification?
            $this->view->objects = $this->schoolFacade->getSchoolsMatching($criteria);
        } else {
            $this->view->objects = $this->schoolFacade->getAllSchools();
        }

        $this->view->form = $form;

        $add = array();
        if ($this->view->can_edit) {
            $add['add'] = 'Add New School';
            $add['addRoute'] = '/admin/school/new';
        }
        $this->view->layout()->pageHeader = $this->view->partial(
            'partials/page-header.phtml',
            array('title' => 'Schools') + $add
        );
    }

    public function excelAction()
    {
        $criteria = $this->session->criteria;
        $schools = $this->schoolFacade->getSchoolsMatching($criteria);

        $headers = array(
            'Name',
            'Type',
            'Notes',
            'Region',
            'Area',
            'Address Line 1',
            'Address Line 2',
            'City',
            'State',
            'ZIP/PostalCode'
        );

        $data = array();

        /** @var SchoolDto $school */
        foreach ($schools as $school) {
            $data[] = array(
                $school->getName(),
                $school->getType(),
                $school->getNotes(),
                $school->getRegionName(),
                $school->getAreaName(),
                $school->getAddressLineOne(),
                $school->getAddressLineTwo(),
                $school->getAddressCity(),
                $school->getAddressState(),
                $school->getAddressZip()
            );
        }

        $this->outputCSV('schools', $data, $headers);
    }

    public function viewAction()
    {
        $id = $this->getRequest()->getParam('id');
        $dto = $this->schoolFacade->getSchoolById($id);
        $this->view->layout()->pageHeader = $this->view
            ->partial('partials/page-header.phtml', array(
                'title' => $dto->getName()
            ));
        $this->view->school = $dto;
    }

    public function newAction()
    {
        $this->view->form = $this->getForm();
        $request = $this->getRequest();
        $form = $this->getForm();
        $form->setAction('/admin/school/new');
        if ($this->getRequest()->isPost()) {
            $postData = $request->getPost();
            if ($form->isValid($postData)) {
                try {
                    $this->saveSchool($postData);
                    $this
                        ->setFlashMessageAndRedirect("The new school: \"{$postData['name']}\" has been created!", 'success', array(
                            'module' => 'admin', 'controller' => 'school', 'action' => 'index'
                        ));
                } catch (ApiException $e) {
                    $this->setFlashMessageAndUpdateLayout('An error occurred while saving this information: ' . $e->getMessage(), 'error');
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
        $form = $this->getForm();
        $form->setAction('/admin/school/edit?id='.$id);
        $dto = $this->schoolFacade->getSchoolById($id);
        $this->view->layout()->pageHeader = $this->view
            ->partial('partials/page-header.phtml', array(
                'title' => 'Edit: '.$dto->getName()
            ));
        $form->populate(
            array(
            'name'=>$dto->getName(),
            'notes'=>$dto->getNotes(),
            'area'=>$dto->getAreaId(),
            'schoolType'=>$dto->getTypeKey(),
            'addressLineOne'=>$dto->getAddressLineOne(),
            'addressLineTwo'=>$dto->getAddressLineTwo(),
            'city'=>$dto->getAddressCity(),
            'state'=>$dto->getAddressState(),
            'zip'=>$dto->getAddressZip()
            )
        );
        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest();
            $postData = $request->getPost();
            if ($form->isValid($postData)) {
                try {
                    $updatedSchool = $this->schoolFacade->updateSchool($id, $postData['name'], $postData['area'], $postData['schoolType'], $postData['notes'], $postData['addressLineOne'], $postData['addressLineTwo'], $postData['city'], $postData['state'], $postData['zip']);
                    $this
                        ->setFlashMessageAndRedirect("The school: \"{$updatedSchool->getName()}\" has been updated!", 'success', array(
                            'module' => 'admin', 'controller' => 'school', 'action' => 'view', 'params' => array('id'=>$updatedSchool->getId())
                        ));
                } catch (ApiException $e) {
                    $this->setFlashMessageAndUpdateLayout('An error occurred while saving this information: ' . $e->getMessage(), 'error');
                }
            } else {
                $this
                    ->setFlashMessageAndUpdateLayout('It looks like you missed some information, please make the corrections below.', 'error');
            }
        }
        $this->view->form = $form;
    }

    /**
     * @param array $postData
     * @return Core\School\SchoolDto
     */
    private function saveSchool($postData)
    {
        $school = $this->schoolFacade->saveSchool($postData['name'], $postData['area'], $postData['schoolType'], $postData['notes'], $postData['addressLineOne'], $postData['addressLineTwo'], $postData['city'], $postData['state'], $postData['zip']);
        return $school;
    }

    /**
     * @return Admin_School
     */
    private function getForm()
    {
        $statesArray = array_merge(array(''), $this->locationFacade->getStates());
        $areasArray = array_merge(array(''), $this->getAreasArray());
        $schoolTypesArray = $this->getSchoolTypesArray();

        $form = new \Admin_School(
                        array(
                            'states' => $statesArray, 'schoolTypes' => $schoolTypesArray, 'areas' => $areasArray
                        ));
        return $form;
    }

    /**
     * @return array
     */
    private function getAreasArray()
    {
        $areaDtos = $this->locationFacade->getAllAreas();
        $areaArray = array();
        foreach ($areaDtos as $dto) {
            $areaArray[$dto->getId()] = $dto->getName();
        }
        return $areaArray;
    }

    /**
     * getFilterForm
     *
     * Return the filter form for list of all schools
     *
     * @param User $user
     * @return Admin_MemberFilter
     */
    private function getFilterForm($user)
    {
        if (User::ROLE_COORDINATOR == $user->getRole()) {
            // limit filter options to regions they coordinate for
            $member = $this->memberFacade->getMemberById($user->getAssociatedMemberId());
            $regions = array_merge(array(''), $member->getCoordinatesForRegions());
        } else {
            $regions =  $this->getRegionsArray();
        }

        $form = new \Admin_SchoolFilter(
            array(
                'regions' => $regions,
                'types' => $this->getSchoolTypesArray(),
            )
        );
        return $form;
    }

    /**
     * @return array
     */
    private function getRegionsArray()
    {
        $regionsArray = array('');
        /** @var RegionDto $region */
        foreach ($this->locationFacade->getAllRegions() as $region) {
            $regionsArray[$region->getName()] = $region->getName();
        }
        return $regionsArray;
    }

    /**
     * @return array
     */
    private function getSchoolTypesArray()
    {
        return array_merge(array(''), $this->schoolFacade->getSchoolTypes());
    }

    /**
     * @param $key
     * @param array $params
     * @param array $criteria
     */
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

    private function setPermissions()
    {
        /** @var STS\Core\User\UserDTO $user */
        $user = $this->getAuth()->getIdentity();

        // permissions
        $this->view->can_view = $this->getAcl()->isAllowed($user->getRole(), AclFactory::RESOURCE_SCHOOL, 'view');
        $this->view->can_edit = $this->getAcl()->isAllowed($user->getRole(), AclFactory::RESOURCE_SCHOOL, 'edit');
        $this->view->can_delete = $this->getAcl()->isAllowed($user->getRole(), AclFactory::RESOURCE_SCHOOL, 'delete');
    }
}
