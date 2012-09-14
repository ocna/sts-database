<?php
use STS\Core;

use STS\Web\Controller\SecureBaseController;
class Admin_SchoolController extends SecureBaseController
{
    protected $schoolFacade;

    public function init()
    {
        parent::init();
        $core = Core::getDefaultInstance();
        $this->schoolFacade = $core->load('SchoolFacade');
    }

    public function indexAction()
    {
        $this->view->objects = $this->schoolFacade->getSchoolsForSpecification(null);
        
        $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
                   'title' => 'Schools' ));
        
        
       // $this->view->layout()->pageHeader = $this->view->partial('partials/page-header.phtml', array(
         //   'title' => 'Schools' , 'add' => 'Add New User' , 
           // 'addRoute' => '/admin/user/add'
        //));
    }


}

