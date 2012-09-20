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
    public function newAction()
    {
        $this->view->form = $this->getForm();
    }
    private function getForm()
    {
        $statesArray = array_merge(array(
            ''
        ), $this->locationFacade->getStates());
        $areasArray = array_merge(array(
            ''
        ), array());
        $schoolTypesArray = array_merge(array(
            ''
        ), $this->schoolFacade->getSchoolTypes());
        $form = new \Admin_School(array(
            'states' => $statesArray, 'schoolTypes' => $schoolTypesArray, 'areas' => $areasArray
        ));
        return $form;
    }
}
