<?php
use STS\Core;
use STS\Web\Controller\SecureBaseController;

class Admin_SchoolController extends SecureBaseController
{
    protected $schoolFacade;
    protected $locationFacade;

    public function init()
    {
        parent::init();
        $core = Core::getDefaultInstance();
        $this->schoolFacade = $core->load('SchoolFacade');
        $this->locationFacade = $core->load('LocationFacade');
    }

    public function indexAction()
    {
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


        $this->view->objects = $this->schoolFacade->getSchoolsForSpecification(null);
        $this->view->layout()->pageHeader = $this->view
            ->partial('partials/page-header.phtml', array(
                'title' => 'Schools', 'add' => 'Add New School', 'addRoute' => '/admin/school/new'
            ));
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
                    $this->setFlashMessageAndUpdateLayout('An error occured while saving this information: ' . $e->getMessage(), 'error');
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
                    $this->setFlashMessageAndUpdateLayout('An error occured while saving this information: ' . $e->getMessage(), 'error');
                }
            } else {
                $this
                    ->setFlashMessageAndUpdateLayout('It looks like you missed some information, please make the corrections below.', 'error');
            }
        }
        $this->view->form = $form;
    }

    private function saveSchool($postData)
    {
        $school = $this->schoolFacade->saveSchool($postData['name'], $postData['area'], $postData['schoolType'], $postData['notes'], $postData['addressLineOne'], $postData['addressLineTwo'], $postData['city'], $postData['state'], $postData['zip']);
        return $school;
    }

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
     * @return Admin_MemberFilter
     */
    private function getFilterForm()
    {
        $form = new \Admin_SchoolFilter(
            array(
                'regions' => $this->getRegionsArray(),
                'types' => $this->getSchoolTypesArray(),
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

    private function getSchoolTypesArray()
    {
        return array_merge(array(''), $this->schoolFacade->getSchoolTypes());
    }
}
