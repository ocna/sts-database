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

    public function editAction(){
        // $this->view->form = $this->getForm();
        // $id = $this->getRequest()->getParam('id');
        // $form = $this->getForm();
        // $dto = $this->schoolFacade->getSchoolById($id);

        // $form->populate(array('name'=>$dto->getName(), 'area'=>$));
        // $this->view->layout()->pageHeader = $this->view
        //     ->partial('partials/page-header.phtml', array(
        //         'title' => 'Edit: '.$dto->getName()
        //     ));

        //     $this->view->form = $form;
    }
    private function saveSchool($postData)
    {
        $this->schoolFacade
            ->saveSchool($postData['name'], $postData['area'], $postData['schoolType'], $postData['notes'], $postData['addressLineOne'], $postData['addressLineTwo'], $postData['city'], $postData['state'], $postData['zip']);
        return true;
    }
    private function getForm()
    {
        $statesArray = array_merge(array(
            ''
        ), $this->locationFacade->getStates());
        $areasArray = array_merge(array(
            ''
        ), $this->getAreasArray());
        $schoolTypesArray = array_merge(array(
            ''
        ), $this->schoolFacade->getSchoolTypes());
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
}
